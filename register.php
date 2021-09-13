<?php

include_once("./pages_commons.php");

$template_args[PAGE_TITLE] = "Register";
$template_args[PAGE_BODY] = "./templates/register.php";
$template_args[PAGE_HIDE_NAVBAR] = true;

include_once("./templates/page_base.php");

?>