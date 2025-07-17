<?php

namespace App\Controllers;

use App\Models\User;

class UserController
{
    public function index()
    {
        $users = (new User())->all();
        view('users.index', compact('users'));
    }

    public function create()
    {
        view('users.create');
    }
}
