<?php

namespace App\Action;

use App\Domain\Account\Service\AccountTransactionService;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

final class ConvertCurrencyAction
{
    private $convertCurrency;

    public function __construct(AccountTransactionService $convertCurrency)
    {
        $this->convertCurrency = $convertCurrency;
    }

    public function __invoke(
        ServerRequestInterface $request, 
        ResponseInterface $response, array $data
    ): ResponseInterface {

        // Invoke the Domain with inputs and retain the result
        $converted_value = $this->convertCurrency->convertCurrencyFixer($data);

        // Build the HTTP response
        $response->getBody()->write((string)json_encode($converted_value));

        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(201);
    }
}
