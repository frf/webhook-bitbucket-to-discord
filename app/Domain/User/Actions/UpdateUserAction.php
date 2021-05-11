<?php

namespace Domain\User\Actions;

use Domain\User\Bags\UserBag;
use Domain\User\Repositories\UserRepository;

class UpdateUserAction
{
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function execute(UserBag $userBag, int $id)
    {
        $dataToUpdate = $userBag->attributes();

        if (isset($dataToUpdate['profile_photo'])) {
            unset($dataToUpdate['profile_photo']);
        }

        $this->userRepository->update($dataToUpdate, $id);

        return $this->userRepository->find($id);
    }
}
