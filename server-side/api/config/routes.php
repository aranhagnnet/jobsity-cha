<?php

use Slim\App;

return function (App $app) {
    //$app->get('/', \App\Action\HomeAction::class)->setName('home');
    $app->post('/users', \App\Action\UserCreateAction::class);
    $app->post('/login/{login}/password/{password}', \App\Action\UserLoginAction::class);
    $app->get('/balance/{username}/{sess}', \App\Action\ShowBalanceAction::class);
    $app->get('/transactions/{username}/{sess}', \App\Action\ShowTransactionsAction::class)->setName('transactions');
    $app->get('/convert/{value}/{currency_code1}/{currency_code2}/{username}/{sess}', \App\Action\ConvertCurrencyAction::class)->setName('currency');
    $app->get('/deposit/{deposit_value}/{currency_code}/{username}/{sess}', \App\Action\DepositAction::class);
    $app->get('/withdraw/{withdraw_value}/{currency_code}/{username}/{sess}', \App\Action\WithdrawAction::class);
    $app->get('/get_currency_codes', \App\Action\GetCurrencyCodesAction::class)->setName('currencycodes');
};

