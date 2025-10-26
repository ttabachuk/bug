<?php
require_once '../utils/Sanitizer.php';
include('../repositories/ProjectRepository.php');
require_once '../utils/Messenger.php';
require_once 'dbh.inc.php';

$repository = new ProjectRepository($pdo, new Messenger());

session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $project = $_POST["project"];

    try {

        // handle errors
        $errors = [];

        if (empty($project)) {
            $errors["empty_input"] = "cannot create empty project";
        }

        if ($errors) {
            $_SESSION["errors_project"] = $errors;
            header("Location: ../views/projects.php");
            die();
        }

        if ($_POST["action"] === "insert") {
            $repository->insert($project);
        }
        else {
            $id = $_POST["id"];
            $repository->update($id, $project);
        }

        // clean up
        $pdo = null;
        $stmt = null;
        header("Location: ../views/projects.php");
        die();

    } catch (PDOException $e) {
        die("query failed: " . $e->getMessage());

    }
} else {
    header("Location: ../pages/admin.php");
    die();
}