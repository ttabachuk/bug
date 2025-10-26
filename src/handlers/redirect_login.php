<?php
session_start();

function dieIfNotLoggedIn() {
    if (!isset($_SESSION['authenticated'])) {
        header("Location: ../index.php");
        die();
    }
}
