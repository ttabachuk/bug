
<?php

require_once '../utils/Sanitizer.class.php';
require_once '../utils/ErrorLogger.class.php';
require_once '../repositories/ProjectRepository.class.php';

class ProjectController {
    private $repository;

    public function __construct($repository) {
        $this->repository = $repository;
    }

    public function listAll() {
        $projects = $this->repository->getAll();

        echo "<table border='1' cellpadding='5'>";
        echo "<tr><th>id</th><th>project</th><th>actions</th></tr>";

        foreach ($projects as $project) {
            $id = $project->getId();
            $name = $project->getProject();

            echo "<tr>";
            echo "<td>{$id}</td>";
            echo "<td>{$name}</td>";
            echo "<td>
                <form method='POST' action='../handlers/update_project.php' style='display:inline-block;'>
                    <input type='hidden' name='id' value='{$id}'>
                    <input type='submit' value='update'>
                </form>

                <form method='POST' action='../handlers/delete_project.php' style='display:inline-block;' onsubmit=\"return confirm('Are you sure you want to delete this project?');\">
                    <input type='hidden' name='id' value='{$id}'>
                    <input type='submit' value='delete'>
                </form>
            </td>";
            echo "</tr>";
        }

        echo "</table>";
    }
}