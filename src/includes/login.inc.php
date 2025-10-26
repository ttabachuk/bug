<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = $_POST["username"];
    $pwd = $_POST["pwd"];

    try {
        require_once 'dbh.inc.php';
        require_once 'login_model.inc.php';
        require_once 'login_contr.inc.php';

        // handle errors
        $errors = [];

        if (is_input_empty($username, $pwd)) {
            $errors["empty_input"] = "fill in all fields";
        }

        $result = get_user($pdo, $username, $pwd);
        $_SESSION["result"] = $result;

        if (is_username_wrong($result)) {
            $errors["incorrect_login"] = "incorrect username provided";
        }

        if (!is_username_wrong($result) && is_password_wrong($pwd, $result["Password"])) {
            $errors["incorrect_login"] = "incorrect password provided";
        }

        if ($errors) {
            $_SESSION["errors_login"] = $errors;
            header("Location: ../index.php");
            die();
        }

        $newSessionId = session_create_id();
        $sessionId = $newSessionId . "_" . $result["Id"];
        session_id($sessionId);

        $_SESSION["user_id"] = $result["Id"];
        $_SESSION["user_username"] = htmlspecialchars($result["Username"]);

        $_SESSION["last_regeneration"] = time();

        header("Location: ../index.php?login=success");
        
        // clean up
        $pdo = null;
        $stmt = null;
        die();

    } catch (PDOException $e) {
        die("query failed: " . $e->getMessage());

    }
} else {
    header("Location: ../index.php");
    die();
}
