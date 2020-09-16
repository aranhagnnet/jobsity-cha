<?php

namespace App\Domain\User\Service;

use App\Domain\User\Repository\UserLoginRepository;
use App\Exception\ValidationException;

/**
 * Service.
 */
final class UserLogoutService
{
    /**
     * @var UserLoginRepository
     */
    private $repository;

    /**
     * The constructor.
     *
     * @param UserLoginRepository $repository The repository
     */
    public function __construct(UserLoginRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * User login process: verify password and, if password is ok, create cookie
     *
     * @param array $data The form data
     *
     * @return void
     */
    public function logoutUser(array $data): void
    {
        $this->repository->deleteUserSession($data);
    }

}
