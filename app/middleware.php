<?php

declare(strict_types=1);

use App\Application\Middleware\SessionMiddleware;
use App\Application\Middleware\JwtMiddleware;

use Slim\App;

return function (App $app) {
    //add SessionMiddleware
    $app->add(SessionMiddleware::class);
    //add JWTMiddleware
    $app->add(JwtMiddleware::class);
};
