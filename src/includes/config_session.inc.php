<?php

function regenerate_session_id()
{
    session_regenerate_id(true);
    $_SESSION["last_regeneration"] = time();
}

function regenerate_session_id_loggedin()
{
    session_regenerate_id(true);

    $userId = $_SESSION["user_id"];
    $newSessionId = session_create_id();
    $sessionId = $newSessionId . "_" . $result["Id"];
    session_id($sessionId);
    
    $_SESSION["last_regeneration"] = time();
}
