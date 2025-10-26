<?php
require_once './redirect_login.php';
dieIfNotLoggedIn();
require_once '../repositories/UserRepository.php';
require_once '../includes/dbh.inc.php';
require_once '../utils/Messenger.php';

$messenger = new Messenger();
$repository = new UserRepository($pdo, $messenger);

if ($_SERVER['REQUEST_METHOD'] === 'POST' and isset($_POST['id'])) {
    // make sure we don't try to delete the admin (root) account
    if (isset($_POST['username']) and $_POST['username'] === 'admin'){
        $messenger->put("unable to perform delete on user 'admin'");
        $messenger->save();
    }
    else {
        $repository->delete($_POST['id']);
    }
    
    header("Location: ../views/users.php");
}