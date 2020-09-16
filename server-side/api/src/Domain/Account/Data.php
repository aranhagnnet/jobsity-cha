<?php

namespace App\Domain\Account\Data;

final class AccountData
{
    /**
     * @var int
     */
    public $id;

    /** @var string */
    public $username;

    /** @var float */
    public $previous_balance;

    /** @var string */
    public $operation_code;

    /** @var float */
    public $operation_value;

    /** @var string */
    public $currency_code;

    /** @var float */
    public $current_balance;

    /** @var string */
    public $date_time;
}


