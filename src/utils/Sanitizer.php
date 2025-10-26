<?php

class Sanitizer {
    public static function sanitize($input) {
        
        // guard
        if ($input) {
            $input = trim($input);
            $input = stripslashes($input);
            $input = htmlspecialchars($input, ENT_QUOTES, 'UTF-8');
            return $input;
        }
    }
}