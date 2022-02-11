<?php

include_once("./pages_commons.php");

$template_args[PAGE_TITLE] = "Choose address";
$template_args[PAGE_BODY] = "./templates/address_choose.php";
$template_args[PAGE_REQUIRE_LOGIN] = true;

include_once("./templates/page_base.php");

?>