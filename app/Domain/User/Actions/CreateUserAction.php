<?php

namespace Domain\User\Actions;

use Domain\User\Bags\MailUserBag;
use Domain\User\Bags\UserBag;
use Domain\User\Repositories\UserRepository;
use Illuminate\Support\Facades\Hash;

class CreateUserAction
{
    protected UserRepository $userRepository;
    protected AuthUserAction $authUserAction;

    public function __construct(
        UserRepository $userRepository,
        AuthUserAction $authUserAction
    ) {
        $this->userRepository = $userRepository;
        $this->authUserAction = $authUserAction;
    }

    public function execute(UserBag $userBag)
    {
        $data = $userBag->attributes();
        $data['password'] = Hash::make($data['password']);

        $user = $this->userRepository->create($data);
        $user->guard(['api'])->assignRole(['user']);
        $user->save();

        return $this->userRepository->find($user->id);
    }
}
