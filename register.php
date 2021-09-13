<?php

include_once("./pages_commons.php");

define("REGISTER_ACTION", "action");
define("REGISTER_CLIENT", "client");
define("REGISTER_SELLER", "seller");

$template_args[PAGE_TITLE] = "Register";
$template_args[PAGE_BODY] = "./templates/register.php";

include_once("./templates/page_base.php");

?>