<?php

namespace App\Domain\User\Repository;

use PDO;

/**
 * Repository.
 */
class UserLoginRepository
{
    /**
     * @var PDO The database connection
     */
    private $connection;

    /**
     * Constructor.
     *
     * @param PDO $connection The database connection
     */
    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }




    /**
     * Loads user account's password from the database
     *
     * @param array $data The username 
     *
     * @return string The current password from the database
     */
     public function getPassword(array $data): string
     {
        $username = $data['login'];

        $sql = "select password from users where username='$username'";

        $sql_value = $this->connection->query($sql)->fetch();

        if((is_array($sql_value)) and ($sql_value!=NULL))
        {
            return $sql_value['password'];
        }
        else 
        {
            $no_value['password']=NULL;
            return $no_value;
        }
     }
 

     
    /**
     * Sets session_id for user
     *
     * @param array $data The username 
     *
     * @return void
     */
     public function setSessionId(array $data, $session_id): void
     {
        $username = $data['login'];

        $expires = time()+3600;

        $sql = "update users set session_id=?, session_id_expires=? where username=? ";

        $stmt=$this->connection->prepare($sql);
        $stmt->execute([$session_id, $expires, $username]);
     }


    /**
     * Delete session_id for logged user
     *
     * @param array $data The username 
     *
     * @return void
     */
     public function deleteUserSession(array $data): void
     {
        $username = $data['username'];

        $sql = "update users set session_id=?, session_id_expires=? where username=? ";

        $stmt=$this->connection->prepare($sql);
        $stmt->execute([0, 0, $username]);
     }
}
