<?php

include_once("./pages_commons.php");

$template_args[PAGE_TITLE] = "Order";
$template_args[PAGE_BODY] = "./templates/order.php";
$template_args[PAGE_REQUIRE_LOGIN] = true;

include_once("./templates/page_base.php");

?>