<?php

namespace App\Repositories;

use PDO;

class BugRepository
{
    protected PDO $pdo;

    // inject the pdo database connection
    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    // retrieve all bugs from the database
    public function getAllBugs() {}

    // retrieve a single bug by its id
    public function getBugById() {}

    // insert a new bug or update an existing one
    public function upsertBug() {}

    // delete a bug by its id
    public function deleteBug() {}
}
