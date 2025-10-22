<?php

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

        if (is_username_wrong($result)) {
            $errors["empty_input"] = "fill in all fields";
        }
        if (!is_username_wrong($result) && is_password_wrong($pwd, $result["pwd"])) {
            $errors["incorrect_login"] = "incorrect login info";
        }

        require_once 'config.session.inc.php';

        if ($errors) {
            $_SESSION["errors_signup"] = $errors;
            header("Location: ../views/index.php");
            die();
        }

        $newSessionId = session_create_id();
        $sessionId = $newSessionId . "_" . $result["id"];
        session_id($sessionId);

    } catch (PDOException $e) {
        die("query failed: " . $e->getMessage());

    }
} else {
    header("Location: ../views/index.php");
    die();
}
