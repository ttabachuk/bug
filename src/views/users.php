<?php

require_once '../handlers/redirect_login.php';
dieIfNotLoggedIn();

require_once '../components/header.php';
require_once '../utils/Messenger.php';
require_once '../repositories/UserRepository.php';
require_once '../repositories/ProjectRepository.php';
require_once '../includes/dbh.inc.php';

$projectRepository = new projectRepository($pdo, new Messenger());
$projects = $projectRepository->getAll();
$userRepository = new UserRepository($pdo, new Messenger());
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>users</title>
    <link rel="stylesheet" type="text/css" href="../styles.css">
</head>
<body>

    <h3>add a new user</h3>
    <form action="../includes/user.inc.php" method="post">
        <input type="hidden" name="action" value="insert">

        <label for="name">name:</label><br>
        <input type="text" id="name" name="name" required><br>

        <label for="username">username:</label><br>
        <input type="text" id="username" name="username" required><br>

        <label for="password">password:</label><br>
        <input type="text" id="password" name="password" required><br>

        <label for="role_id">role:</label><br>
        <select id="role_id" name="role_id" required>
            <option value="">select role</option>
            <option value="1">admin</option>
            <option value="2">manager</option>
            <option value="3">user</option>
        </select><br>

        <label for="project_id">assign project:</label><br>
        <select id="project_id" name="project_id">
        <option value="">select project</option>
        <?php foreach ($projects as $project): ?>
            <option value="<?= $project->getId(); ?>">
            <?= htmlspecialchars($project->getProject()); ?>
            </option>
        <?php endforeach; ?>
        </select><br><br>

        <button>add</button>
    </form> 

    <hr>
    <h3>existing users</h3>

<?php
formatAsTable($userRepository->getAll());
?>
    <br>
<?php
Messenger::propigate();
?>

</body>
</html>

<?php

function formatAsTable($users) {

    echo "<table border='1' cellpadding='5'>";
    echo "<tr>
            <th>id</th>
            <th>username</th>
            <th>role id</th>
            <th>project id</th>
            <th>name</th>
            <th>actions</th>
        </tr>";

    foreach ($users as $user) {
        $id = $user->getId();
        $username = $user->getUserName();
        $roleId = $user->getRoleID();
        $projectId = $user->getProjectId();
        $name = $user->getName();

        echo "<tr>";
        echo "<td>{$id}</td>";
        echo "<td>{$username}</td>";
        echo "<td>{$roleId}</td>";
        echo "<td>{$projectId}</td>";
        echo "<td>{$name}</td>";
        echo "<td>
            <form method='POST' action='../handlers/delete_user.php' style='display:inline-block;' onsubmit=\"return confirm('Are you sure you want to delete this user?');\">
                <input type='hidden' name='id' value='{$id}'>
                <input type='hidden' name='username' value='{$username}'>
                <input type='submit' value='delete'>
            </form>
        </td>";
        echo "</tr>";
    }

    echo "</table>";
}
?>