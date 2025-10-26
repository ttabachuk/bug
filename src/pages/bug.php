<?php
    include('../components/header.php');
    include('../repositories/BugRepository.php');
    include('../includes/dbh.inc.php');

    session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>bugs</title>
    <link rel="stylesheet" type="text/css" href="../styles.css">
</head>
<body>
    <h3>bugs</h3>
    <?php
        $repository = new BugRepository($pdo);
        $bugs = $repository->getAll();

        var_dump($bugs);

    ?>
</body>
</html>