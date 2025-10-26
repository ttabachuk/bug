<?php
require_once './redirect_login.php';
dieIfNotLoggedIn();

require_once '../repositories/BugRepository.php';
require_once '../repositories/ProjectRepository.php';
require_once '../repositories/UserRepository.php';
require_once '../includes/dbh.inc.php';
require_once '../utils/Messenger.php';

$messenger = new Messenger();
$bugRepository = new BugRepository($pdo, $messenger);
$userRepository = new UserRepository($pdo, $messenger);
$projectRepository = new ProjectRepository($pdo, $messenger);

$projects = $projectRepository->getAll();
$users = $userRepository->getAll();
$strictlyUsers = $userRepository->getOnlyUsers();
$statuses = $bugRepository->getOpenStatuses();
$priorities = $bugRepository->getPriorities();

if (isset($_POST['id'])) {
    $id = $_POST['id'];
    $bug = $bugRepository->get($id);
}

function listOptions($items, $labelMethod, $selectedId = null) {
    foreach ($items as $item) {
        $id = htmlspecialchars($item->getId());
        $label = htmlspecialchars($item->$labelMethod());
        $selected = ($id == $selectedId) ? 'selected' : '';
        echo "<option value=\"$id\" $selected>$label ($id)</option>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>update bug</title>
    <link rel="stylesheet" type="text/css" href="../styles.css">
</head>
<body>
    <h3>update bug</h3>
    <form action="../includes/bug.inc.php" method="post">
        <input type="hidden" name="action" value="update">
        <input type="hidden" name="id" value="<?= htmlspecialchars($bug->getId()) ?>">

        <label for="summary">summary:</label><br>
        <textarea id="summary" name="summary" rows="3" cols="50" required><?= htmlspecialchars($bug->getSummary()) ?></textarea><br>

        <label for="description">description:</label><br>
        <textarea id="description" name="description" rows="5" cols="50" required><?= htmlspecialchars($bug->getDescription()) ?></textarea><br>

        <label for="owner">owner:</label><br>
        <select id="owner" name="owner">
            <option value="">select user</option>
            <?php listOptions($users, 'getUsername', $bug->getOwnerId()); ?>
        </select><br><br>

        <label for="project_id">associated project:</label><br>
        <select id="project_id" name="project_id">
            <option value="">select project</option>
            <?php listOptions($projects, 'getProject', $bug->getProjectId()); ?>
        </select><br><br>

    <!-- prevent users from assigning bugs when creating -->
        <?php if (isset($_SESSION['roleId']) && $_SESSION['roleId'] === '3'): ?>
        <label for="assigned_to">assigned to:</label><br>
        <select id="assigned_to" name="assigned_to" disabled>
            <option value=<?php echo "{$bug->getAssignedToId()}"?>><?= htmlspecialchars($bug->getAssignedToId()) ?></option>
        </select>
        <input type="hidden" name="assigned_to" value="<?= htmlspecialchars($bug->getAssignedToId()) ?>">
        <?php else: ?>
            <label for="assigned_to">assigned to:</label><br>
            <select id="assigned_to" name="assigned_to">
                <option value="">select user</option>
                <?php listOptions($strictlyUsers, 'getUsername', $bug->getAssignedToId()); ?>
            </select>
        <?php endif; ?>
        <br><br>

        <label for="bug_status">bug status:</label><br>
        <select id="bug_status" name="bug_status">
            <option value="">select status</option>
            <?php listOptions($statuses, 'getStatus', $bug->getStatusId()); ?>
        </select>
        <br><br>

        <label for="priority">priority:</label><br>
        <select id="priority" name="priority">
            <option value="">select priority</option>
            <?php listOptions($priorities, 'getPriority', $bug->getPriorityId()); ?>
        </select>
        <br><br>

        <label for="date_raised">date raised:</label><br>
        <input type="date" id="date_raised" name="date_raised" required
            value="<?= $bug ? date('Y-m-d', strtotime($bug->getDateRaised())) : '' ?>"><br>

        <label for="target_date">target date:</label><br>
        <input type="date" id="target_date" name="target_date" required
            value="<?= $bug ? date('Y-m-d', strtotime($bug->getTargetDate())) : '' ?>"><br><br>
        <br>

        <button type="submit">update</button>
    </form>
</body>
</html>