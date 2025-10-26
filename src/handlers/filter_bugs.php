<?php
session_start();

// Get filter inputs
$filterType = $_GET['filter'] ?? 'all';
$projectId = $_GET['project_id'] ?? null;

$_SESSION['filter'] = $filterType;
$_SESSION['project_id'] = $projectId;

// Redirect back to bug page
header("Location: ../pages/bug.php");
exit;
