<?php

function check_login_errors()
{
    if(isset($_SESSION["errors_login"])) {
        $errors = $_SESSION["errors_login"];
        
        echo "<br>";

        foreach($errors as $error){
            echo "${error}<br>";
        }
        unset($_SESSION['errors_login']);
    }
    else if (isset($_GET['login']) && $_GET['login'] === "success") {
        echo "user logged in successfully!";

        // redirect to home page
        header("Refresh: 1; url=./pages/home.php");
    }
}