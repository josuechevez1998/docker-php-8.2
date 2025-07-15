<?php

use App\Controllers\UserController;

function registerRoutes($router)
{
    $router->get('/users', [UserController::class, 'index']);
}
