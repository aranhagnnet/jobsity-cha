<?php

namespace App\Domain\User\Service;

use App\Domain\User\Repository\UserLoginRepository;
use App\Exception\ValidationException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Cookie;
use Slim\Http\Cookies;

/**
 * Service.
 */
final class UserLoginService
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
     * @return bool
     */
    public function loginUser(array $data): bool
    {
        // Password validation
        if($this->passwordMatch($data))
        {
            $username=$data['login'];

            if(setcookie("jobsity_challenge", $username, time()+3600, "/", null, false))
            {
                return TRUE; 
            }
            else 
            {
                return FALSE;
            }
        }
        else 
        {
            return FALSE;
        }
    }


    /**
     * Passwords match? 
     *
     * @param array $data The form data
     *
     * @return bool
     */
     public function passwordMatch(array $data): bool
     {
        $db_pass = $this->repository->getPassword($data);

        if(($db_pass==$data['password']) and ($db_pass!=NULL))
        {
            return TRUE; 
        }
        else 
        {
            return FALSE;
        } 
     }
 

}
