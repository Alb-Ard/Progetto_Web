<?php

include_once("./pages_commons.php");

$template_args[PAGE_TITLE] = "Choose payment";
$template_args[PAGE_BODY] = "./templates/payment_choose.php";
$template_args[PAGE_REQUIRE_LOGIN] = true;

include_once("./templates/page_base.php");

?>