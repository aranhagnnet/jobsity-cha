<?php

namespace App\Domain\Account\Service;

use App\Domain\Account\Repository\AccountTransactionRepository;
use App\Exception\ValidationException;


/**
 * Service.
 */
final class AccountTransactionService
{
    /**
     * @var AccountTransactionRepository
     */
    private $repository;

     /**
     * The constructor.
     *
     * @param AccountTransactionRepository $repository The repository
     */
    public function __construct(AccountTransactionRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Get Account's Balance
     *
     * @param array $data user data
     *
     * @return array 
     */
    public function showBalance(array $data): array
    {
        // Input validation
        $this->validateAccount($data);

        if($this->repository->isLoginValid($data))
        {
            // get Balance
            $balance = $this->repository->getBalance($data);

            // Add the username
            $balance['username']=$data['username'];

            return $balance;
        }
        else
        {
            $balance=[];
            return $balance;
        }
    }



    /**
     * Get user Account's Transaction logs
     *
     * @param array $data user data
     *
     * @return array bidimensional array with transactions
     */
     public function showTransactions(array $data): array
     {
        // Input validation
        $this->validateAccount($data);

        if($this->repository->isLoginValid($data))
        {
            // get Transactions
            return $this->repository->getTransactions($data);
        }
        else 
        {
            return NULL; 
        }
     }




    /**
     * Make a deposit
     *
     * @param array $data value and currency for the new deposit
     *
     * @return array  the new balance (inside an array)
     */
     public function makeDeposit(array $data): array
     {
         // Login validation
         if(!$this->repository->isLoginValid($data))
         {
            return NULL;
         }
 
         $errors = [];

         // Get current balance and account's currency code
         $balance_account = $this->showBalance($data);

         // Different currencies? Then I must convert the amount first
         if($balance_account['currency_code']!=$data['currency_code']) 
         {
            $data2['value'] = $data['deposit_value']; 
            // from 
            $data2['currency_code1'] = $data['currency_code'];
            // to
            $data2['currency_code2'] = $balance_account['currency_code'];

            $new_value = number_format($this->convertCurrencyFixer($data2), 2);

            // New value, in the account's default currency
            $data['deposit_value'] = $new_value;
            $data['currency_code'] = $balance_account['currency_code'];
         }

         // save the current balance into $data
         $data['previous_balance'] = $balance_account['current_balance'];
         $data['current_balance'] = $data['previous_balance']+$data['deposit_value'];

          // Make deposit
         $this->repository->saveDeposit($data);

         $current_balance = $this->showBalance($data);

         return $current_balance;
     }
 




    /**
     * Make a withdraw
     *
     * @param array $data value and currency for the new deposit
     *
     * @return array  the new balance (in a array)
     */
     public function makeWithdraw(array $data): array
     {
         // Login validation
         if(!$this->repository->isLoginValid($data))
         {
            return NULL;
         }

         $errors = [];         

         // Get current balance and account's currency code
         $balance_account = $this->showBalance($data);

         $data['current_balance'] = $balance_account['current_balance'];

         // Different currencies? Then I must convert withdraw first
         if($balance_account['currency_code']!=$data['currency_code']) 
         {
            $data2['value'] = $data['withdraw_value']; 
            // from 
            $data2['currency_code1'] = $data['currency_code'];
            // to
            $data2['currency_code2'] = $balance_account['currency_code'];

            $new_value = number_format($this->convertCurrencyFixer($data2), 2);

            // New value, in the account's default currency
            $data['withdraw_value'] = $new_value;
            $data['currency_code'] = $balance_account['currency_code'];

            // --TODO deveria logar aqui a conversao de deposito?
         }

         //print_r($data); exit();
         // Do I have enough Balance? 
         if($data['withdraw_value']<=$balance_account['current_balance'])
         {
            // save the current balance into $data
            $data['previous_balance'] = $balance_account['current_balance'];            
            $data['current_balance'] = $data['previous_balance']-$data['withdraw_value'];

            // Make withdraw
            $this->repository->saveWithdraw($data);

            $current_balance = $this->showBalance($data);

            return $current_balance;
         }
         else  // return error - no balance
         {
            return $errors;
         }
     }
 



    /**
     * User Account validation. (account exists?)
     *
     * @param string $data username
     *
     * @throws ValidationException
     *
     * @return void
     */
    private function validateAccount(array $data): void
    {
        $errors = [];

        // Here you can also use your preferred validation library
        if (empty($data['username'])) 
        {
            $errors['username'] = 'Input required';
        }
        
        if ($errors) 
        {
            throw new ValidationException('Please check your input', $errors);
        }
    }





    /**
     * 
     *
     * @param array $data The form data
     *
     * @return array $codes Array of supported currency codes
     */
     public function getCodes(array $data): array
     {
        $api_key = "8116664379236ef575cba840ccabbaf8";

        $endpoint = 'latest';
       
        $url = "http://data.fixer.io/api/$endpoint?access_key=$api_key";

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 15); 
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        $json_string = curl_exec($ch);
        $parsed_json = json_decode($json_string);
        $rates = (array)$parsed_json->rates;

        foreach($rates as $currency=>$rate)
        {
            $a_rates[]=$currency;
        }

        return $a_rates;

    }


    /**
     * ConvertCurrency: Convert $value from $code1 to $code2 using Fixer IO's API 
     *
     * @param array $data The form data
     *
     * @return float $converted_value The converted value
     */
     public function convertCurrencyFixer(array $data): float
     {
        // Login validation
        if(!$this->repository->isLoginValid($data))
        {
           return NULL;
        }

        /*$api_key = $this->get('settings')['fixer_apikey'];
        print_r($api_key); exit();*/

        $api_key = "8116664379236ef575cba840ccabbaf8";

        $value = $data['value'];
        $currency1 = strtoupper($data['currency_code1']);
        $currency2 = strtoupper($data['currency_code2']);

        $endpoint = 'latest';
       
        $url = "http://data.fixer.io/api/$endpoint?access_key=$api_key";

        //print($url); exit();

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 15); 
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        $json_string = curl_exec($ch);
        $parsed_json = json_decode($json_string);

        //print("<pre>");print_r($parsed_json); exit();
        if($parsed_json->success==1)
        {
            $rate1 = (float)$parsed_json->rates->$currency1;
            $rate2 = (float)$parsed_json->rates->$currency2;
            $amount = (float)($value*$rate2)/$rate1;
        }
        else 
        {
            $amount = 0.00;
        }
        return $amount;
     }


}
