<?php
include('../includes/dbh.inc.php');
include('../models/Project.php');
require_once '../utils/Messenger.php';

class ProjectRepository {

    private $pdo;
    private $table = 'project';
    private $model = 'Project';
    private $messenger;

    public function __construct($pdo, $messenger){
        $this->pdo = $pdo;
        $this->messenger = $messenger;
    }

    public function insert($project) {

        if ($this->exists($project)) {
            $this->messenger->put("unable to create - project {$project} already exists");
            $this->messenger->save();
        } else {
            $query = "INSERT INTO {$this->table} (Project) VALUES(:project)";
            $stmt = $this->pdo->prepare($query);
            $stmt->bindParam(":project", $project);
            $stmt->execute();
        }
    }

    public function update($id, $project) {

        if ($this->exists($project)) {
            $this->messenger->put("unable to update - project {$project} already exists");
            $this->messenger->save();
        } else {
            $query = "UPDATE {$this->table} SET Project = :project WHERE Id = :id";
            $stmt = $this->pdo->prepare($query);
            $stmt->bindParam(":id", $id);
            $stmt->bindParam(":project", $project);
            $stmt->execute();
        }
    }

    public function getAll() {
        $query = "SELECT * FROM {$this->table}";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute();

        $projects = $stmt->fetchAll(PDO::FETCH_CLASS, $this->model);
        return $projects;
    }

    public function delete($id) {
        $query = "DELETE FROM {$this->table} WHERE Id = (:id)";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(":id", $id);
        $stmt->execute();
    }

    public function get($id) {
        $query = "SELECT * FROM {$this->table} WHERE Id = (:id)";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(":id", $id);
        $stmt->execute();

        $stmt->setFetchMode(PDO::FETCH_CLASS, 'Project');
        return $stmt->fetch();
    }

    private function exists($project) {
        $query = "SELECT COUNT(*) FROM {$this->table} WHERE Project = (:project)";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(":project", $project);
        $stmt->execute();

        return $stmt->fetchColumn() > 0;
    }
}