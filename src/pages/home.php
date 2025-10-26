<?php
include('../components/header.php');
require_once '../handlers/redirect_login.php';
dieIfNotLoggedIn();
?>

<!DOCTYPE html>
<html>
<head>
    <title>home</title>
    <link rel="stylesheet" type="text/css" href="../styles.css">
</head>
<body>
    <p>welcome to home!</p>
</body>
</html>