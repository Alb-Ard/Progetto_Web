<?php

include_once("./pages_commons.php");

$books = $db_conn->get_books()->get_books_in_category($_GET["id"]);

$template_args[PAGE_TITLE] = $_GET["name"];
$template_args[PAGE_BODY] = "./templates/list_books.php";

include_once("./templates/page_base.php");

?>