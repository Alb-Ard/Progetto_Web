<?php

define("SESSION_EMAIL", "email");

define("SESSION_TYPE", "user_type");
define("SESSION_TYPE_CLIENT", "user");
define("SESSION_TYPE_SELLER", "seller");

database : $db_conn = new database();

try {
    if (!$db_conn->connect("localhost", "root", ""))
        die("Error connecting to db.");
} catch(exception $e) {
    die("Error connecting to db: " . $e);
}

session_start();

function login_user(string $email, string $type) : bool {
    if (is_user_logged())
        return false;
    
    $_SESSION[SESSION_EMAIL] = $email;
    $_SESSION[SESSION_TYPE] = $type;
    return true;
}

function get_client_info() : array {
    if (!is_user_logged())
        return [];
    
    GLOBAL $db_conn;
    return $db_conn->get_users()->get_infos($_SESSION[SESSION_EMAIL]);
}

function logout_user() : void {
    session_reset();
    session_destroy();
}

function is_user_logged() : bool {
    return session_status() == PHP_SESSION_ACTIVE && isset($_SESSION[SESSION_EMAIL]);
}

?>