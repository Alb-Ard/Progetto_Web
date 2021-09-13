<?php

include_once("./pages_commons.php");

$template_args[PAGE_TITLE] = "Login";
$template_args[PAGE_BODY] = "./templates/login.php";
$template_args[PAGE_HIDE_NAVBAR] = true;

include_once("./templates/page_base.php");

?>