<?php

include_once("./pages_commons.php");

$current_page = isset($_GET["page"]) ? $_GET["page"] : 0;
$current_order = isset($_GET["order"]) ? $_GET["order"] : 0;
$books = $db_conn->get_books()->get_books_in_category($_GET["id"], $current_order, $current_page);
$pages_count = $db_conn->get_books()->get_book_pages_in_category_count($_GET["id"]);

$show_order = true;
$show_pages = true;

function get_page_href(int $index) : string {
    global $current_order;
    return "./category.php?id={$_GET['id']}&name={$_GET['name']}&order=$current_order&page=$index";
}

function get_order_href(int $index) : string {
    global $current_page;
    return "./category.php?id={$_GET['id']}&name={$_GET['name']}&order=$index&page=$current_page"; 
}

$template_args[PAGE_TITLE] = $_GET["name"];
$template_args[PAGE_BODY] = "./templates/list_books.php";

include_once("./templates/page_base.php");

?>