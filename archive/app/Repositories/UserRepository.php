<?php

namespace App\Repositories;

use PDO;

class UserRepository
{
    protected PDO $pdo;

    // inject the pdo database connection
    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    // retrieve all users from the database
    public function getAllUsers() {}

    // retrieve a single user by its id
    public function getUsersById() {}

    // insert a new user or update an existing one
    public function upsertUser() {}

    // delete a user by its id
    public function deleteUser() {}
}
