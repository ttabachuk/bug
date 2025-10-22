<?php
session_start();
?>

<!DOCTYPE html>
<html>
<head>
    <title>My PHP App</title>
</head>
<body>
    <nav>
        <a href="index.php?page=admin">admin</a> |
        <a href="index.php?page=bug">bug</a> |
        <a href="logout.php">logout</a>
    </nav>

    <hr>

    <div>
        <?php
        if (isset($_GET['page'])) {
            $page = $_GET['page'];
            if ($page == 'admin') {
                include 'admin.php';
            } elseif ($page == 'bug') {
                include 'bug.php';
            } else {
                echo "<p>Page not found.</p>";
            }
        } else {
            echo "<p>Welcome! Please select a page.</p>";
        }
?>
    </div>
</body>
</html>

