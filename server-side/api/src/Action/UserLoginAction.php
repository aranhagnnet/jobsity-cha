<?php

namespace App\Action;

use App\Domain\User\Service\UserLoginService;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

final class UserLoginAction
{
    private $userLogin;

    public function __construct(UserLoginService $userLogin)
    {
        $this->userLogin = $userLogin;
    }

    public function __invoke(
        ServerRequestInterface $request, 
        ResponseInterface $response
    ): ResponseInterface 
    {
        // Collect input from the HTTP request (POST)
        $data = (array)$request->getParsedBody();

        // Invoke the Domain with inputs and retain the result
        $session_id = $this->userLogin->loginUser($data);

        // Build the HTTP response
        $response->getBody()->write((string)json_encode($session_id));

        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(201);
    }
}
