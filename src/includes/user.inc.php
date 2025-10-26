<?php
require_once '../utils/Sanitizer.php';
include('../repositories/UserRepository.php');
require_once '../utils/Messenger.php';
require_once 'dbh.inc.php';

$messenger = new Messenger();
$repository = new UserRepository($pdo, $messenger);

session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $name = trim($_POST['name']);
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $roleId = $_POST['role_id'];
    $projectId = empty($_POST['project_id']) ? null: $_POST['project_id'];

    $validRoles = ['1', '2', '3'];

    // ensure a valid role was selected
    if (!in_array($roleId, $validRoles)) {
        $messenger->put("invalid role was selected");
        $messenger->save();
        header("Location: ../views/users.php");
        die();
    }

    // ensure a project is assigned if a `user` is selected
    if (empty($projectId) and $roleId == '3') {
        $messenger->put("unable to add user - users must be assigned to a project");
        $messenger->save();
        header("Location: ../views/users.php");
        die();
    }

    // ensure no project is assigned if a `manager` or `admin` are selected
    if (!empty($projectId) and $roleId != '3') {
        $messenger->put("only users can be assigned projects");
        $messenger->save();
        header("Location: ../views/users.php");
        die();
    }

    try {
        
        if ($_POST["action"] === "insert") {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $repository->insert($name, $username, $hashedPassword, $roleId, $projectId);
        }
        else {
            $id = $_POST["id"];
            $repository->update($id, $project);
        }

        // clean up
        $pdo = null;
        $stmt = null;
        header("Location: ../views/users.php");
        die();

    } catch (PDOException $e) {
        die("query failed: " . $e->getMessage());

    }
} else {
    header("Location: ../pages/admin.php");
    die();
}