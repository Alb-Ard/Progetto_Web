<?php

include_once("./pages_commons.php");

$books = $db_conn->get_books()->search($_GET["key"]);

$template_args[PAGE_TITLE] = "Search results";
$template_args[PAGE_BODY] = "./templates/list_books.php";

include_once("./templates/page_base.php");

?>