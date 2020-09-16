<?php

namespace App\Action;

use App\Domain\Account\Service\AccountTransactionService;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Http\Cookies;

final class DepositAction
{
    private $depositAction;

    public function __construct(AccountTransactionService $depositAction)
    {
        $this->depositAction = $depositAction;
    }

    public function __invoke(
        ServerRequestInterface $request, 
        ResponseInterface $response, array $data
    ): ResponseInterface 
    {
        // Invoke the Domain with inputs and retain the result
        $result = $this->depositAction->makeDeposit($data);

        // Build the HTTP response
        $response->getBody()->write((string)json_encode($result));

        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(201);
    }
}
