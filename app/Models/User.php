<?php

namespace App\Models;

use Core\Database;

class User
{
    protected $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    public function all()
    {
        return $this->db->selectAll('users');
    }
}
