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
    <title>bug details</title>
    <link rel="stylesheet" type="text/css" href="../styles.css">
</head>
<body>
    <h3>bug details</h3>

    <h4>summary</h4>
    <?php echo "{$bug->getSummary()}"; ?>
    <h4>description</h4>
    <?php echo "{$bug->getDescription()}"; ?>
    <h4>fix description</h4>
    <?php echo "{$bug->getFixDescription()}"; ?>
</body>
</html>