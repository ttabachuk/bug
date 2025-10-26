<?php
session_start();

// a utility class for storing messages in sessions

class Messenger {

    private $messages;
    public static function propigate() {
        
        if (isset($_SESSION['message'])) {
            $messages = $_SESSION['message'];

            echo "<br>";

            foreach ($messages as $message) {
                echo "{$message}<br>";
            }

            unset($_SESSION['message']);
        }
    }

    public function put($message) {
        $this->messages[] = $message;
    }

    public function save() {
        // save messages in a session variable
        $_SESSION['message'] = $this->messages;
    }
}
