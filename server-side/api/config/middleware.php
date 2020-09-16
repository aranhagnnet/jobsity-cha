<?php

use Slim\App;
use Slim\Middleware\ErrorMiddleware;
use App\Middleware\SessionMiddleware;

return function (App $app) {
    // Parse json, form data and xml
    $app->addBodyParsingMiddleware();

    $app->add(SessionMiddleware::class);

    // Add the Slim built-in routing middleware
    $app->addRoutingMiddleware();

    // Catch exceptions and errors
    $app->add(ErrorMiddleware::class);

};


