<?php

include_once("./pages_commons.php");

$template_args[PAGE_TITLE] = $_GET["name"];
$template_args[PAGE_BODY] = "./templates/category.php";

include_once("./templates/page_base.php");

?>