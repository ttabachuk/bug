<?php

require_once '../handlers/redirect_login.php';
dieIfNotLoggedIn();

require_once '../components/header.php';
require_once '../utils/Messenger.php';
require_once '../repositories/BugRepository.php';
require_once '../repositories/UserRepository.php';
require_once '../repositories/ProjectRepository.php';
require_once '../includes/dbh.inc.php';
require_once '../models/Bug.php';

function listOptions($items, $labelMethod) {
    foreach ($items as $item) {
        $id = htmlspecialchars($item->getId());
        $label = htmlspecialchars($item->$labelMethod());
        echo "<option value=\"$id\">$label ($id)</option>";
    }
}

function clearFilters() {
    unset($_SESSION['filter']);
    unset($_SESSION['project_id']);
}

function formatAsTable($bugs) {
    echo "<table border='1' cellpadding='5'>";
    echo "<tr>
            <th>id</th>
            <th>project id</th>
            <th>owner id</th>
            <th>assigned to id</th>
            <th>status id</th>
            <th>priority id</th>
            <th>date raised</th>
            <th>target date</th>
            <th>date closed</th>
            <th>actions</th>
        </tr>";

    foreach ($bugs as $bug) {

        $id = htmlspecialchars($bug->getId());
        $projectId = htmlspecialchars($bug->getProjectId());
        $ownerId = htmlspecialchars($bug->getOwnerId());
        $assignedToId = htmlspecialchars($bug->getAssignedToId());
        $statusId = htmlspecialchars($bug->getStatusId());
        $priorityId = htmlspecialchars($bug->getPriorityId());
        $dateRaised = htmlspecialchars($bug->getDateRaised());
        $targetDate = htmlspecialchars($bug->getTargetDate());
        $dateClosed = htmlspecialchars($bug->getDateClosed());

        echo "<tr>
                <td>$id</td>
                <td>$projectId</td>
                <td>$ownerId</td>
                <td>$assignedToId</td>
                <td>$statusId</td>
                <td>$priorityId</td>
                <td>$dateRaised</td>
                <td>$targetDate</td>
                <td>$dateClosed</td>
                <td>
                    <form method='POST' action='../handlers/view_bug_details.php' style='display:inline-block;'>
                        <input type='hidden' name='id' value='{$id}'>
                        <input type='submit' value='view'>
                    </form>

                    <form method='POST' action='../handlers/close_bug.php' style='display:inline-block;'>
                        <input type='hidden' name='id' value='{$id}'>
                        <input type='submit' value='close'>
                    </form>

                    <form method='POST' action='../handlers/update_bug.php' style='display:inline-block;'>
                        <input type='hidden' name='id' value='{$id}'>
                        <input type='submit' value='update'>
                    </form>
                </td>
              </tr>";
    }

    echo "</table>";
}

$messenger = new Messenger();
$bugRepository = new BugRepository($pdo, $messenger);
$userRepository = new UserRepository($pdo, $messenger);
$projectRepository = new ProjectRepository($pdo, $messenger);
$projects = $projectRepository->getAll();
$users = $userRepository->getAll();
$strictlyUsers = $userRepository->getOnlyUsers();
$statuses = $bugRepository->getOpenStatuses();
$priorities = $bugRepository->getPriorities();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>bug</title>
    <link rel="stylesheet" href="../styles.css">
</head>
<body>

<h3>create a new bug</h3>
<form action="../includes/bug.inc.php" method="post">
    <input type="hidden" name="action" value="insert">

    <label for="summary">summary:</label><br>
    <textarea id="summary" name="summary" rows="3" cols="50" required></textarea><br>

    <label for="description">description:</label><br>
    <textarea id="description" name="description" rows="5" cols="50" required></textarea><br>

    <label for="owner">owner:</label><br>
    <select id="owner" name="owner">
        <option value="">select user</option>
        <?php listOptions($users, 'getUsername'); ?>
    </select><br><br>

    <label for="project_id">associated project:</label><br>
    <select id="project_id" name="project_id">
        <option value="">select project</option>
        <?php listOptions($projects, 'getProject'); ?>
    </select><br><br>

    <!-- prevent users from assigning bugs when creating -->
    <?php if (isset($_SESSION['roleId']) && $_SESSION['roleId'] === '3'): ?>
    <label for="assigned_to">assigned to:</label><br>
    <select id="assigned_to" name="assigned_to" disabled>
        <option value="">not permitted</option>
    </select>
    <input type="hidden" name="assigned_to" value="">
    <?php else: ?>
        <label for="assigned_to">assigned to:</label><br>
        <select id="assigned_to" name="assigned_to">
            <option value="">select user</option>
            <?php listOptions($strictlyUsers, 'getUsername'); ?>
        </select>
    <?php endif; ?>
    <br><br>

    <label for="bug_status">bug status:</label><br>
    <?php if (isset($_SESSION['roleId']) && $_SESSION['roleId'] === '3'): ?>
        <select id="bug_status" name="bug_status" disabled>
            <option value="1">unassigned</option>
        </select>
        <input type="hidden" name="bug_status" value="1">
    <?php else: ?>
        <select id="bug_status" name="bug_status">
            <option value="">select status</option>
            <?php listOptions($statuses, 'getStatus'); ?>
        </select>
    <?php endif; ?>
    <br><br>

    <label for="priority">priority:</label><br>
    <?php if (isset($_SESSION['roleId']) && $_SESSION['roleId'] === '3'): ?>
        <select id="priority" name="priority" disabled>
            <option value="2">medium</option>
        </select>
        <input type="hidden" name="priority" value="2">
    <?php else: ?>
        <select id="priority" name="priority">
            <option value="">select priority</option>
            <?php listOptions($priorities, 'getPriority'); ?>
        </select>
    <?php endif; ?>
    <br><br>

    <label for="date_raised">date raised:</label><br>
    <input type="date" id="date_raised" name="date_raised" required><br>
    
    <label for="target_date">target date:</label><br>
    
    <?php if (isset($_SESSION['roleId']) && $_SESSION['roleId'] === '3'): ?>
        <input type="date" id="target_date" name="target_date" disabled><br><br>
    <?php else: ?>
        <input type="date" id="target_date" name="target_date" required><br><br>
    <?php endif; ?>
    <br>

    <button type="submit">create</button>
</form>

<hr>
<h3>existing bugs</h3>

<form method="GET" action="../handlers/filter_bugs.php">
    <select name="filter">
        <option value="all">all bugs</option>
        <option value="open">open bugs</option>
        <option value="overdue">overdue bugs</option>
    </select>

    <select id="project_id" name="project_id">
        <option value="">all projects</option>
        <?php listOptions($projects, 'getProject'); ?>
    </select>

    <button type="submit">apply filter</button>
</form>

<?php
$filterType = $_SESSION['filter'] ?? 'all';
$projectId = $_SESSION['project_id'] ?? null;
$bugs = $bugRepository->getFilteredBugs($filterType, $projectId);

// if a user is using our app, allow them to see only assigned bugs
if ($_SESSION['roleId'] == '3') {
    $userId = $_SESSION['user_id'];
    $bugs = array_filter($bugs, function($bug) use ($userId) {
        return $bug->getAssignedToId() == $userId;
    });
}

formatAsTable($bugs);
clearFilters();
?>

<br>
<?php Messenger::propigate();?>

</body>
</html>