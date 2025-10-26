<?php
require_once '../utils/Sanitizer.php';
include('../repositories/UserRepository.php');
require_once '../utils/Messenger.php';

$messenger = new Messenger();
$repository = new UserRepository($pdo, $messenger);

session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $roleId = $_POST['role_id'];
    $projectId = $_POST['project_id'] ?? null;

    $validRoles = ['1', '2', '3'];

    // ensure a valid role was selected
    if (!in_array($roleId, $validRoles)) {
        $messenger->put("invalid role was selected")
        header("Location: ../views/users.php");
        die();
    }

    // ensure a project is assigned if a `user` is selected
    if (empty($projectId) and $roleId === 3) {
        $messenger->put("a project must be assigned to users");
        $messenger->save();
        header("Location: ../views/users.php");
        die();
    }

    // ensure no project is assigned if a `manager` or `admin` are selected
    if (!empty($projectId) and $roleId !== 3) {
        $messenger->put("only users can be assigned projects");
        $messenger->save();
        header("Location: ../views/users.php");
        die();
    }

    try {
        require_once 'dbh.inc.php';

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