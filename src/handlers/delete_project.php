<?php
require_once '../repositories/ProjectRepository.php';
require_once '../includes/dbh.inc.php';
require_once '../utils/Messenger.php';

$repository = new ProjectRepository($pdo, new Messenger());

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $repository->delete($_POST['id']);
    header("Location: ../views/projects.php");
}