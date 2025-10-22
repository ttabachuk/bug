<?php

namespace App\Repositories;

use PDO;

class ProjectRepository
{
    protected PDO $pdo;

    // inject the pdo database connection
    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    // retrieve all projects from the database
    public function getAllProjects() {}

    // retrieve a single project by its id
    public function getProjectById() {}

    // insert a new project or update an existing one
    public function upsertProject() {}

    // delete a project by its id
    public function deleteProject() {}
}
