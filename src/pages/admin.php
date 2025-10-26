<?php
require_once '../handlers/redirect_login.php';
dieIfNotLoggedIn();

require_once '../components/header.php';
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
    <h3>admin</h3>
<?php
// users
if ($_SESSION['roleId'] == '3') {
    echo "<p>this page is only visible for managers and admins</p>";
}
else {
    echo "<a href='../views/projects.php'>project administration</a><br>";
    // admin
    if ($_SESSION['roleId'] == '1') {
        echo "<a href='../views/users.php'>user administration</a><br>";
    }
}
?>
</body>
</html>