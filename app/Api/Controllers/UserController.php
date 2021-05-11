<?php

namespace App\Api\Controllers;

use App\Api\Requests\RegisterUserRequest;
use App\Api\Resources\UserResource;
use App\Exceptions\ResourceNotFoundException;
use Illuminate\Http\Request;

class UserController extends Controller
{


    public function __construct(
    ) {
    }

    public function create(RegisterUserRequest $createUserRequest)
    {
//        $userBag = PatientBag::fromRequest($createUserRequest->validated());
//        return UserResource::make($this->createUserAction->execute($userBag));
    }

    public function show($id)
    {
//        $user = $this->userRepository->find($id);
//
//        $this->authorize('ownResource', $user);
//
//        if (!$user) {
//            throw new ResourceNotFoundException();
//        }
//
//        return UserResource::make($user);
    }

    public function update(Request $updateProductRequest, $id)
    {
//        if (!$user = $this->userRepository->find($id)) {
//            throw new ResourceNotFoundException();
//        }
//
//        $this->authorize('ownResource', $user);
//
//        $userBag = PatientBag::fromRequest($updateProductRequest, 'update');
//        return UserResource::make($this->updateUserAction->execute($userBag, $id));
    }
}
