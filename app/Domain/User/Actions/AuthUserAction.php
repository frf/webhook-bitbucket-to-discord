<?php

namespace Domain\User\Actions;

use Carbon\Carbon;
use Domain\User\Bags\UserBag;
use Domain\User\Models\User;
use Domain\User\Repositories\UserRepository;
use GuzzleHttp\Psr7\Response;
use Illuminate\Events\Dispatcher;
use Illuminate\Support\Facades\Hash;
use Laravel\Passport\Bridge\AccessToken;
use Laravel\Passport\Bridge\AccessTokenRepository;
use Laravel\Passport\Bridge\Client;
use Laravel\Passport\Bridge\RefreshTokenRepository;
use Laravel\Passport\Passport;
use Laravel\Passport\TokenRepository;
use League\OAuth2\Server\CryptKey;
use League\OAuth2\Server\Entities\AccessTokenEntityInterface;
use League\OAuth2\Server\Exception\OAuthServerException;
use League\OAuth2\Server\Exception\UniqueTokenIdentifierConstraintViolationException;
use League\OAuth2\Server\ResponseTypes\BearerTokenResponse;
use Laravel\Passport\Client as ClientPassport;

class AuthUserAction
{
    protected UserRepository $userRepository;
    protected const CLIENT_AUTH = 'api-docfacil';

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function execute(UserBag $userBag)
    {
        $user = $this->userRepository->findBy('email', $userBag->email);

        if (!$user) {
            return response()->json([
                'error' => 'Invalid credentials'
            ], 401);
        }

        // Validate Password
        if (!Hash::check($userBag->password, $user->password)) {
            return response()->json([
                'error' => 'Invalid credentials 2'
            ], 401);
        }

        $client = \Laravel\Passport\Client::where([
            'id' => env('CLIENT_WEB_ADMIN_ID'),
            'revoked' => false,
        ])->first();

        if (!$client) {
            $client = new ClientPassport([
                'name' => self::CLIENT_AUTH,
                'secret' => $this->generateUniqueIdentifier(32),
                'provider' => 'users',
                'redirect' => 'http://localhost',
                'personal_access_client' => false,
                'password_client' => true,
                'revoked' => false,
            ]);
            $client->save();
        }

        return $this->getBearerTokenByUser($user, [], $client->getKey(), true);
    }

    private function generateUniqueIdentifier($length = 40)
    {
        try {
            return bin2hex(random_bytes($length));
        } catch (\TypeError $e) {
            throw OAuthServerException::serverError('An unexpected error has occurred');
        } catch (\Error $e) {
            throw OAuthServerException::serverError('An unexpected error has occurred');
        } catch (\Exception $e) {
            // If you get this message, the CSPRNG failed hard.
            throw OAuthServerException::serverError('Could not generate a random string');
        }
        // @codeCoverageIgnoreEnd
    }

    private function issueRefreshToken(AccessTokenEntityInterface $accessToken)
    {
        $maxGenerationAttempts = 10;
        $refreshTokenRepository = app(RefreshTokenRepository::class);

        $refreshToken = $refreshTokenRepository->getNewRefreshToken();
        $dateTime = Carbon::now()->toDateTimeImmutable();
        $refreshToken->setExpiryDateTime($dateTime->add(Passport::refreshTokensExpireIn()));
        $refreshToken->setAccessToken($accessToken);

        while ($maxGenerationAttempts-- > 0) {
            $refreshToken->setIdentifier($this->generateUniqueIdentifier());
            try {
                $refreshTokenRepository->persistNewRefreshToken($refreshToken);

                return $refreshToken;
            } catch (UniqueTokenIdentifierConstraintViolationException $e) {
                if ($maxGenerationAttempts === 0) {
                    throw $e;
                }
            }
        }
    }

    protected function sendBearerTokenResponse($accessToken, $refreshToken)
    {
        $response = new BearerTokenResponse();
        $response->setAccessToken($accessToken);
        $response->setRefreshToken($refreshToken);

        $privateKey = new CryptKey('file://'.Passport::keyPath('oauth-private.key'));

        $response->setPrivateKey($privateKey);
        $response->setEncryptionKey(app('encrypter')->getKey());

        return $response->generateHttpResponse(new Response);
    }

    protected function getBearerTokenByUser(User $user, array $scopes, $clientId, $output = true)
    {
        $passportToken = $this->createPassportTokenByUser($user, $scopes, $clientId);
        $bearerToken = $this->sendBearerTokenResponse($passportToken['access_token'], $passportToken['refresh_token']);

        if (!$output) {
            $bearerToken = json_decode($bearerToken->getBody()->__toString(), true);
        }

        return $bearerToken;
    }

    protected function createPassportTokenByUser(User $user, $scopes, $clientId)
    {
        $privateKey = new CryptKey(Passport::keyPath('oauth-private.key'));

        $client = new Client($clientId, self::CLIENT_AUTH, null);
        $accessToken = new AccessToken($user->getKey(), $scopes, $client);
        $accessToken->setPrivateKey($privateKey);
        $accessToken->setIdentifier($this->generateUniqueIdentifier(40));
        $dateTime = Carbon::now()->toDateTimeImmutable();
        $accessToken->setExpiryDateTime($dateTime->add(Passport::tokensExpireIn()));

        $accessTokenRepository = new AccessTokenRepository(new TokenRepository(), new Dispatcher());
        $accessTokenRepository->persistNewAccessToken($accessToken);
        $refreshToken = $this->issueRefreshToken($accessToken);

        return [
            'access_token' => $accessToken,
            'refresh_token' => $refreshToken,
        ];
    }
}
