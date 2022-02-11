<?php

include_once("./pages_commons.php");

$template_args[PAGE_TITLE] = "Cart";
$template_args[PAGE_BODY] = "./templates/cart.php";
$template_args[PAGE_REQUIRE_LOGIN] = true;

include_once("./templates/page_base.php");

?>