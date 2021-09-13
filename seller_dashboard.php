<?php

include_once("./pages_commons.php");

$template_args[PAGE_TITLE] = "Dashboard";
$template_args[PAGE_BODY] = "./templates/seller/listings.php";
$template_args[PAGE_FOOTER] = "./templates/completed_modal.php";

include_once("./templates/seller/page_base.php");

?>