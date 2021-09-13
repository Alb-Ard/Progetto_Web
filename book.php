<?php

include_once("./pages_commons.php");

book_data : $book = $db_conn->get_books()->get_book($_GET["id"]);

$template_args[PAGE_TITLE] = $book->title;
$template_args[PAGE_BODY] = "./templates/book.php";

include_once("./templates/page_base.php");

?>