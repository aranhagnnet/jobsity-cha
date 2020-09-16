<?php

namespace App\Domain\User\Service;

use App\Domain\User\Repository\UserLoginRepository;
use App\Exception\ValidationException;

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
     * @return string session_id
     */
    public function loginUser(array $data): string
    {
        // Password validation
        if($this->passwordMatch($data))
        {
            session_start();
            $session_id = session_id(); 
            
            $this->repository->setSessionId($data, $session_id);
            
            return ($session_id);
        }
        else 
        {
            return 0;
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
