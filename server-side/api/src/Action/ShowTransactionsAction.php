<?php

namespace App\Action;

use App\Domain\Account\Service\AccountTransactionService;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

final class ShowTransactionsAction
{
    private $showTransactions;

    public function __construct(AccountTransactionService $showTransactions)
    {
        $this->showTransactions = $showTransactions;
    }

    public function __invoke(
        ServerRequestInterface $request, 
        ResponseInterface $response, array $data
    ): ResponseInterface 
    {
        // Invoke the Domain with inputs and retain the result
        $transactions = $this->showTransactions->showTransactions($data);

        // Build the HTTP response
        $response->getBody()->write(json_encode($transactions));

        return $response;
    }
}
