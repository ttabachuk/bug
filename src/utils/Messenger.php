<?php
// a utility class for storing messages in sessions

class Messenger {

    private $messages;
    private $hasNewMessages;

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
        $this->hasNewMessages = true;
    }

    public function putMany($messages) {
        foreach ($messages as $message) {
            $this->put($message);
        }
    }

    public function save() {
        // save messages in a session variable
        $_SESSION['message'] = $this->messages;
        $this->hasNewMessages = false;
    }

    public function hasNewMessages() {
        return $this->hasNewMessages;
    }
}
