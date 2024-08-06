<?php

use Slim\App;
use Slim\Middleware\ErrorMiddleware;

return function (App $app) {
    $app->addErrorMiddleware(true, true, true);
};
