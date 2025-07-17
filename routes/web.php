<?php

use App\Controllers\UserController;

function registerRoutes($router)
{
    $router->get('/users', [UserController::class, 'index']);
    $router->get('/users/create', [UserController::class, 'create']);
}
