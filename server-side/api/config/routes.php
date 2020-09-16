<?php

use Slim\App;

return function (App $app) {
    //$app->get('/', \App\Action\HomeAction::class)->setName('home'); // test and debug

    // User routes
    $app->post('/users', \App\Action\UserCreateAction::class);
    $app->post('/login/{login}/password/{password}', \App\Action\UserLoginAction::class)->setName('login');
    $app->get('/logout/{username}', \App\Action\UserLogoutAction::class)->setName('logout');

    // Money routes
    $app->get('/balance/{username}/{sess}', \App\Action\ShowBalanceAction::class)->setName('balance');
    $app->get('/transactions/{username}/{sess}', \App\Action\ShowTransactionsAction::class)->setName('transactions');
    $app->get('/deposit/{deposit_value}/{currency_code}/{username}/{sess}', \App\Action\DepositAction::class)->setName('deposit');
    $app->get('/withdraw/{withdraw_value}/{currency_code}/{username}/{sess}', \App\Action\WithdrawAction::class)->setName('withdraw');

    // Currency routes
    $app->get('/convert/{value}/{currency_code1}/{currency_code2}/{username}/{sess}', \App\Action\ConvertCurrencyAction::class)->setName('currency');
    $app->get('/get_currency_codes', \App\Action\GetCurrencyCodesAction::class)->setName('currencycodes');
};

