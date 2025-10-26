<?php
session_start();
require_once 'includes/login_view.inc.php';
?>

<!DOCTYPE html>
<html>
<head>
    <title>login</title>
    <link rel="stylesheet" type="text/css" href="../styles.css">
</head>
<body>
    <h3>login</h3>
    <form action="includes/login.inc.php" method="post">
        <input type="text" name="username" placeholder="username">
        <input type="password" name="pwd" placeholder="password">
        <button>login</button>
    </form>

    <?php
    check_login_errors();
    ?>

</body>
</html>