<?php
require_once './redirect_login.php';
dieIfNotLoggedIn();

require_once '../repositories/BugRepository.php';
require_once '../includes/dbh.inc.php';
require_once '../utils/Messenger.php';

$repository = new BugRepository($pdo, new Messenger());

if (isset($_POST['id'])) {
    $id = $_POST['id'];
    $bug = $repository->get($id);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>close bug</title>
    <link rel="stylesheet" type="text/css" href="../styles.css">
</head>
<body>
    <h3>close bug</h3>

    <form action="../includes/bug.inc.php" method="post">
        <input type="hidden" name="id" value="<?= htmlspecialchars($id); ?>">
        <label for="fix_description">fix description:</label><br>
        <textarea id="fix_description" name="fix_description" rows="4" cols="50" required></textarea><br><br>
        <input type="hidden" name="action" value="close">
        <button>close</button>
    </form>
</body>
</html>