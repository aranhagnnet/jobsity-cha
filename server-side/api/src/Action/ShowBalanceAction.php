<?php

namespace App\Action;

use App\Domain\Account\Service\AccountTransactionService;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

final class ShowBalanceAction
{
    private $showBalance;

    public function __construct(AccountTransactionService $showBalance)
    {
        $this->showBalance = $showBalance;
    }

    public function __invoke(
        ServerRequestInterface $request, 
        ResponseInterface $response, array $data
    ): ResponseInterface {
        //$method = $request->getMethod();
        //print_r($method); exit();
        
        // Collect input from the HTTP request
        //POST: //$data = (array)$request->getParsedBody();

        // Invoke the Domain with inputs and retain the result
        $balance = $this->showBalance->showBalance($data);

        // Build the HTTP response
        $response->getBody()->write(json_encode($balance));

        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(201);
    }
}
