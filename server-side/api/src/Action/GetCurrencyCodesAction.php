<?php

namespace App\Action;

use App\Domain\Account\Service\AccountTransactionService;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

final class GetCurrencyCodesAction
{
    private $getCodes;

    public function __construct(AccountTransactionService $getCodes)
    {
        $this->getCodes = $getCodes;
    }

    public function __invoke(
        ServerRequestInterface $request, 
        ResponseInterface $response, array $data
    ): ResponseInterface {

        // Invoke the Domain with inputs and retain the result
        $converted_value = $this->getCodes->getCodes($data);

        foreach($converted_value as $currency)
        {
            echo "<option value='$currency'> $currency </option> ";
        }

        // Build the HTTP response
        $response->getBody()->write((string)json_encode($converted_value));

        return $response;    
    }
}
