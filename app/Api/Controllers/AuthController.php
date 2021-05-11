<?php

namespace App\Api\Controllers;

use App\Api\Requests\LoginUserRequest;
use App\Api\Requests\RegisterUserRequest;
use App\Api\Resources\UserResource;
use Domain\User\Actions\AuthUserAction;
use Domain\User\Actions\CreatePatientAction;
use Domain\User\Actions\CreateUserAction;
use Domain\User\Actions\SendUserMailAction;
use Domain\User\Bags\MailUserBag;
use Domain\User\Bags\PatientBag;
use Domain\User\Bags\UserBag;
use Domain\User\Models\User;

class AuthController extends Controller
{
    protected CreateUserAction $registerUserAction;
    protected AuthUserAction $authUserAction;
    protected SendUserMailAction $sendUserMailAction;

    public function __construct(
        CreateUserAction $registerUserAction,
        SendUserMailAction $sendUserMailAction,
        AuthUserAction $authUserAction
    ) {
        $this->registerUserAction = $registerUserAction;
        $this->authUserAction = $authUserAction;
        $this->sendUserMailAction = $sendUserMailAction;
    }

    public function register(RegisterUserRequest $registerUserRequest)
    {
        $userBag = UserBag::fromRequest($registerUserRequest->validated());
        $user = $this->registerUserAction->execute($userBag);

        if ($user instanceof User) {
            $mailBag = MailUserBag::fromRequest(
                $userBag,
                'Welcome',
                'docfacil_bemvindo'
            );

            $this->sendUserMailAction
                ->onQueue('welcome')
                ->execute($mailBag);
        }

        return $this->authUserAction->execute($userBag);
    }

    public function login(LoginUserRequest $loginUserRequest)
    {
        $userBag = UserBag::fromRequest($loginUserRequest->validated());
        return $this->authUserAction->execute($userBag);
    }

    public function me()
    {
        return UserResource::make(auth()->user());
    }
}
