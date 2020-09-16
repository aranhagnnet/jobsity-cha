<?php

namespace App\Domain\Account\Repository;

use PDO;

/**
 * Repository for financial transactions
 */
class AccountTransactionRepository
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
     * Loads if user login is still valid
     *
     * @param array $data The username and login session id
     *
     * @return bool 
     */
     public function isLoginValid(array $data): bool
     {
        $username = $data['username'];
        $session_id = $data['sess'];
        $now = time();

        if($session_id!=NULL)
        {
            $sql = "select session_id, session_id_expires from users where username='$username'";
            $sql_value = $this->connection->query($sql)->fetch();
            if(($session_id==$sql_value['session_id']) and ($sql_value['session_id_expires']>$now))
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
     * Loads user account's balance from the database
     *
     * @param array $data The username 
     *
     * @return array The current balance and currency code
     */
    public function getBalance(array $data): array
    {
        $username = $data['username'];

        $sql = "select current_balance, currency_code from transactions where username='$username' order by date_time desc limit 1";

        $sql_value = $this->connection->query($sql)->fetch();

        if((is_array($sql_value)) and ($sql_value!=NULL))
        {
            return $sql_value;
        }
        else 
        {
            $no_value['current_balance']=0.00;
            $no_value['currency_code']='USD';
            $no_value['username']=$username;
            return $no_value;
        }
    }




    

    /**
     * Loads user account's transaction log from the database
     *
     * @param array $data The username 
     *
     * @return array The current balance and currency code
     */
     public function getTransactions(array $data): array
     {
        $errors=[];
        $username = $data['username'];

        $sql = "select * from transactions where username='$username' order by date_time desc";

        $sql_value = $this->connection->query($sql)->fetchAll();

        foreach($sql_value as $row)
        {
          $sql_rows[] = $row;
        }

        $qtd = count($sql_rows);

        if($qtd==0)
        {
          $errors[]="No transactions for $username";
          return $errors;
        }
        else 
        {
          return $sql_rows;
        }
     }
 


    /**
     * Save deposit transaction to database
     *
     * @param array 
     *
     * @return void
     */
    public function saveDeposit(array $data): void
    {
        
        $sql = "INSERT INTO transactions (username, previous_balance, operation_code, operation_value, currency_code, current_balance) values (?, ?, ?, ?, ?, ?)";

        $this->connection->prepare($sql)->execute([$data['username'], $data['previous_balance'], 'D', $data['deposit_value'], $data['currency_code'], $data['current_balance']]);

    }


    /**
     * Save withdraw transaction to database
     *
     * @param array 
     *
     * @return void
     */
     public function saveWithdraw(array $data): void
     {
         
         $sql = "INSERT INTO transactions (username, previous_balance, operation_code, operation_value, currency_code, current_balance) values (?, ?, ?, ?, ?, ?)";
 
         $this->connection->prepare($sql)->execute([$data['username'], $data['previous_balance'], 'W', $data['withdraw_value'], $data['currency_code'], $data['current_balance']]);
 
     }
 
}
