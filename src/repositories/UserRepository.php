<?php
include('../includes/dbh.inc.php');
include('../models/User.php');
require_once '../utils/Messenger.php';

class UserRepository {

    private $pdo;
    private $messenger;
    private $table = 'user_details';
    private $model = 'User';

    public function __construct($pdo, $messenger){
        $this->pdo = $pdo;
        $this->messenger = $messenger;
    }

    public function getAll() {
        $query = "SELECT * FROM {$this->table}";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_CLASS, $this->model);
    }

    public function getOnlyUsers() {
        $query = "SELECT * FROM {$this->table} WHERE RoleID = 3";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_CLASS, $this->model);
    }

    function getByUsername(string $username)
    {
        $query = "SELECT * FROM user_details WHERE username = :username;";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(":username", $username);
        $stmt->execute();

        $stmt->setFetchMode(PDO::FETCH_CLASS, $this->model);
        return $stmt->fetch();
    }

    public function get($id) {
        $query = "SELECT * FROM {$this->table} WHERE Id = (:id)";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(":id", $id);
        $stmt->execute();

        $stmt->setFetchMode(PDO::FETCH_CLASS, $this->model);
        return $stmt->fetch();
    }

    public function insert($name, $username, $password, $roleId, $projectId) {

        if ($this->exists($username)) {
            $this->messenger->put("unable to add user - username '{$username}' already exists");
            $this->messenger->save();
        } else {
            $query = "INSERT INTO {$this->table} (Username, RoleID, ProjectId, Password, Name) VALUES(:username, :roleId, :projectId, :password, :name)";
            $stmt = $this->pdo->prepare($query);
            $stmt->bindParam(":username", $username);
            $stmt->bindParam(":roleId", $roleId);
            $stmt->bindParam(":projectId", $projectId);
            $stmt->bindParam(":password", $password);
            $stmt->bindParam(":name", $name);
            $stmt->execute();
        }
    }

    public function delete($id) {
        $query = "DELETE FROM {$this->table} WHERE Id = (:id)";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(":id", $id);
        $stmt->execute();
    }

    private function exists($username) {
        $query = "SELECT COUNT(*) FROM {$this->table} WHERE Username = (:username)";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(":username", $username);
        $stmt->execute();

        return $stmt->fetchColumn() > 0;
    }
}