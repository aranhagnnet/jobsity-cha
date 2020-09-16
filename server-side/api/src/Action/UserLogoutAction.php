<?php

namespace App\Action;

use App\Domain\User\Service\UserLogoutService;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

final class UserLogoutAction
{
    private $userLogout;

    public function __construct(UserLogoutService $userLogout)
    {
        $this->userLogout = $userLogout;
    }

    public function __invoke(
        ServerRequestInterface $request, 
        ResponseInterface $response, array $data 
    ): ResponseInterface 
    {
        // Invoke the Domain with inputs and retain the result
        $this->userLogout->logoutUser($data);

        // Build the HTTP response
        $response->getBody()->write((string)json_encode("true"));

        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(201);
    }
}
