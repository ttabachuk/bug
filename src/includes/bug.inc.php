<?php
require_once '../utils/Sanitizer.php';
include('../repositories/BugRepository.php');
require_once '../utils/Messenger.php';
require_once 'dbh.inc.php';

$messenger = new Messenger();
$repository = new BugRepository($pdo, $messenger);

session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    try {
        if ($_POST["action"] === "insert") {
            $fields = processRequest($messenger);
            $repository->insert($fields);
        }
        else if ($_POST["action"] === "close"){
            $repository->close($_POST['fix_description'], $_POST['id']);
        }
        else if ($_POST["action"] === "update"){
            $id = $_POST["id"];
            $fields = processRequest($messenger);
            $repository->update($fields);
        }
        // clean up
        $pdo = null;
        $stmt = null;
        header("Location: ../pages/bug.php");
        die();

    } catch (PDOException $e) {
        die("query failed: " . $e->getMessage());
    }
} else {
    header("Location: ../pages/bug.php");
    die();
}

function processRequest($messenger) {
    $fields = getFields();
    $fields = sanitizeFields($fields);
    $fields = validate($fields, $messenger);
    return $fields;
}

function getFields() {

    return [
        'id' => $_POST['id'],
        'description' => $_POST['description'] ?? '',
        'summary' => $_POST['summary'] ?? '',
        'owner' => $_POST['owner'] ?? '',
        'project_id' => $_POST['project_id'] ?? '',
        'assigned_to' => $_POST['assigned_to'] ?? '',
        'bug_status' => $_POST['bug_status'] ?? '',
        'priority' => $_POST['priority'] ?? '',
        'date_raised' => $_POST['date_raised'] ?? '',
        'target_date' => $_POST['target_date'] ?? '',
    ];
}

function sanitizeFields($fields) {

    foreach ($fields as &$field) {
        $field = Sanitizer::sanitize($field);
        if (empty($field)) {
            $field = null;
        }
    }
    unset($field);
    return $fields;
}

function validate($fields, $messenger) {
    $roleId = $_SESSION['roleId'] ?? null;

    // Alphanumeric validation
    foreach (['description', 'summary'] as $key) {
        if (!preg_match('/^[a-zA-Z0-9 .,]+$/', $fields[$key])) {
            $errors[] = "'$key' must be alphanumeric and may include commas and periods";
        }
    }

    // Required fields
    if (empty($fields['owner'])) {
        $errors[] = "'owner' is required";
    }

    if (empty($fields['project_id'])) {
        $errors[] = "'project' is required";
    }

    if (empty($fields['assigned_to']) && $roleId != '3') {
        $errors[] = "'assigned to' is required";
    }

    if (empty($fields['bug_status'])) {
        $errors[] = "'bug status' is required";
    }

    if (empty($fields['priority'])) {
        $errors[] = "'priority' is required";
    }

    // if we assign a bug and give it a status of "unassigned", log an error
    if (!empty($fields['assigned_to']) and !empty($fields['bug_status']) and $fields['bug_status'] == '1') {
        $errors[] = "unable to create a bug with status 'unassigned', since a user was assigned";
    }

    // date raised must be valid and not in the future
    if (!DateTime::createFromFormat('Y-m-d', $fields['date_raised'])) {
        $errors[] = "'date raised' must be a valid date.";
    } elseif ($fields['date_raised'] > date('Y-m-d')) {
        $errors[] = "'date raised' cannot be in the future";
    }

    // target date required unless role 3, must be valid and in the future
    if (empty($fields['target_date']) && $roleId != '3') {
        $errors[] = "'target date' is required";
    } elseif (!empty($fields['target_date'])) {
        $targetDateObj = DateTime::createFromFormat('Y-m-d', $fields['target_date']);
        $today = new DateTime();

        if (!$targetDateObj) {
            $errors[] = "'target date' must be a valid date.";
        } elseif ($targetDateObj <= $today) {
            $errors[] = "'target date' must be a future date.";
        }
    }

    if (!empty($errors)) {
        $messenger->putMany($errors);
        $messenger->save();
        header("Location: ../pages/bug.php");
        die();
    }

    return $fields;
}