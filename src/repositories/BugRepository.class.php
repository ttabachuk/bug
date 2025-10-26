<?php
include('../includes/dbh.inc.php');
include('../models/Bug.class.php');

class BugRepository {

    private $pdo;
    private $table = 'bugs';
    private $model = 'Bug';

    public function __construct($pdo){
        $this->pdo = $pdo;
    }

    public function getAll() {
        $query = "SELECT * FROM {$this->table}";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute();

        $bugs = $stmt->fetchAll(PDO::FETCH_CLASS, $this->model);
        return $bugs;
    }
}