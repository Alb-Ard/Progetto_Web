<?php

define("SESSION_EMAIL", "email");
define("SESSION_TYPE", "user_type");
define("SESSION_TYPE_CLIENT", "user");
define("SESSION_TYPE_SELLER", "seller");

session_start();

function login_user(string $email, string $type) : bool {
    if (is_user_logged())
        return false;
    
    $_SESSION[SESSION_EMAIL] = $email;
    $_SESSION[SESSION_TYPE] = $type;
    return true;
}

function logout_user() : void {
    session_reset();
    session_destroy();
}

function is_user_logged() : bool {
    return session_status() == PHP_SESSION_ACTIVE && isset($_SESSION[SESSION_EMAIL]);
}

?>