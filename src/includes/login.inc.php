<?php
session_start();

require_once '../utils/Messenger.php';
require_once '../repositories/UserRepository.php';
require_once '../includes/dbh.inc.php';

$messenger = new Messenger();
$repository = new UserRepository($pdo, $messenger);

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = $_POST["username"];
    $pwd = $_POST["pwd"];

    try {
        require_once 'login_contr.inc.php';

        // handle errors
        $errors = [];

        if (is_input_empty($username, $pwd)) {
            $messenger->put("fill in all fields");
        }

        $user = $repository->getByUsername($username);
        
        if (is_username_wrong($user)) {
            $messenger->put("incorrect username provided");
        }

        if (!is_username_wrong($user) && is_password_wrong($pwd, $user->getPassword())) {
            $messenger->put("incorrect password provided");
        }

        // if we have any messages at this point, an error occurred
        if ($messenger->hasNewMessages()) {
            $messenger->save();
            header("Location: ../index.php");
            die();
        }

        $newSessionId = session_create_id();
        $sessionId = $newSessionId . "_" . $user->getId();
        session_id($sessionId);

        $_SESSION["user_id"] = $user->getId();
        $_SESSION["user_username"] = htmlspecialchars($user->getUsername());

        $_SESSION["last_regeneration"] = time();

        $_SESSION["authenticated"] = true;
        $_SESSION['roleId'] = $user->getRoleID();
        header("Location: ../index.php");
        
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
