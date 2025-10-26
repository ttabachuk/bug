<?php
include('../includes/dbh.inc.php');
include('../models/User.class.php');

class UserRepository {

    private $pdo;
    private $table = 'user_details'
    private $model = 'User';

    public function __construct($pdo){
        $this->pdo = $pdo;
    }

    public function getAllBugs() {
        $query = "SELECT * FROM $table";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute();

        $bugs = $stmt->fetchAll(PDO::FETCH_CLASS, $model);
        return $bugs;
    }
}