<?php
session_start();
require_once 'utils/Messenger.php';
?>

<!DOCTYPE html>
<html>
<head>
    <title>login</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
</head>
<body>
    <h3>login</h3>
    <form action="includes/login.inc.php" method="post">
        <input type="text" name="username" placeholder="username">
        <input type="password" name="pwd" placeholder="password">
        <button>login</button>
    </form>

<?php
if (isset($_SESSION['authenticated']) && $_SESSION['authenticated'] == true) {
    echo "user logged in successfully!";

    // redirect to home page
    header("Refresh: 1; url=pages/home.php");
}
?>

<?php
Messenger::propigate();
?>

</body>
</html>