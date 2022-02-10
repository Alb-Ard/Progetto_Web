<?php

define("SESSION_EMAIL", "email");
define("HAS_ACCEPTED_COOKIES", "cookies_enabled");
define("COOKIE_DURATION", 3600 * 24 * 31); // One month

function login_user(string $email) : bool {
    if (is_user_logged())
        logout_user();

    $_SESSION[SESSION_EMAIL] = $email;
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

function has_accepted_cookies() {
    return isset($_COOKIE[HAS_ACCEPTED_COOKIES]) && ($_COOKIE[HAS_ACCEPTED_COOKIES] == "yes");
}

database : $db_conn = new database();

try {
    if (!$db_conn->connect("localhost", "root", ""))
        die("Error connecting to db.");
} catch(exception $e) {
    die("Error connecting to db: " . $e);
}

session_start();

$cookie_options = [ 
    "expires" => time() + COOKIE_DURATION, 
    "path" => "/", 
    "samesite" => "Lax" ];
$cookies_enabled = (isset($_GET[HAS_ACCEPTED_COOKIES]) && $_GET[HAS_ACCEPTED_COOKIES]) || has_accepted_cookies();
setcookie(HAS_ACCEPTED_COOKIES, $cookies_enabled ? "yes" : "no", $cookie_options);

?>