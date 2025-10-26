<?php
require_once '../handlers/redirect_login.php';
dieIfNotLoggedIn();

require_once '../components/header.php';
require_once '../utils/Messenger.php';
require_once '../repositories/ProjectRepository.php';
require_once '../includes/dbh.inc.php';
require_once '../utils/Messenger.php';

$repository = new ProjectRepository($pdo, new Messenger());
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>admin</title>
    <link rel="stylesheet" type="text/css" href="../styles.css">
</head>
<body>

    <h3>create a new project</h3>
    <form action="../includes/project.inc.php" method="post">
        <input type="text" name="project" placeholder="name of project" required>
        <input type="hidden" name="action" value="insert">
        <button>create</button>
    </form>     

    <br>
    <h3>existing projects</h3>

<?php
formatAsTable($repository->getAll());
?>
    <br>
<?php
Messenger::propigate();
?>

</body>
</html>

<?php

function formatAsTable($projects) {

    echo "<table border='1' cellpadding='5'>";
    echo "<tr><th>id</th><th>project</th><th>actions</th></tr>";

    foreach ($projects as $project) {
        $id = $project->getId();
        $name = $project->getProject();

        echo "<tr>";
        echo "<td>{$id}</td>";
        echo "<td>{$name}</td>";
        echo "<td>
            <form method='POST' action='../handlers/update_project.php' style='display:inline-block;'>
                <input type='hidden' name='id' value='{$id}'>
                <input type='submit' value='update'>
            </form>

            <form method='POST' action='../handlers/delete_project.php' style='display:inline-block;' onsubmit=\"return confirm('Are you sure you want to delete this project?');\">
                <input type='hidden' name='id' value='{$id}'>
                <input type='submit' value='delete'>
            </form>
        </td>";
        echo "</tr>";
    }

    echo "</table>";
}
?>