<?php


namespace App\Services;

use Domain\User\Repositories\PatientRepository;

class UserService extends BaseService
{
    /**
     * UserService constructor.
     * @param PatientRepository $repository
     */
    public function __construct(PatientRepository $repository)
    {
        parent::__construct($repository);
    }
}
