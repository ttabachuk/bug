<?php
session_start();

class ErrorLogger {
    public static function log($key) {
        
        if (isset($_SESSION[$key])) {
            $errors = $_SESSION[$key];

            echo "<br>";

            foreach ($errors as $error) {
                echo "{$error}<br>";
            }

            unset($_SESSION[$key]);
        }
    }
}
