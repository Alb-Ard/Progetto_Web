<?php

include_once("./pages_commons.php");

$template_args[PAGE_TITLE] = "Categories";
$template_args[PAGE_BODY] = "./templates/categories.php";
$template_args[PAGE_FOOTER] = "./templates/completed_modal.php";

include_once("./templates/page_base.php");

?>