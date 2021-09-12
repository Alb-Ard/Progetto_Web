<?php

include("../database.php");
include("../session.php");

try {
    database : $db_conn = new database();
    if (!$db_conn->connect("localhost", "root", "") || !isset($_POST["action"])) {
        echo json_encode(false);
        return;
    }

    switch($_POST["action"]) {
        case "list":
            $result = $db_conn->get_books()->get_user_books($_POST["email"]);
            echo json_encode($result);
            break;
        case "add":
            if (!is_user_logged()) {
                echo json_encode(false);
                break;
            }
            if (!isset($_POST["title"]) || !isset($_POST["author"]) || !isset($_POST["state"]) || !isset($_POST["category"]) || !isset($_POST["price"])) {
                echo json_encode(false);
                break;
            }
            if (!is_numeric($_POST["price"]) || !is_numeric($_POST["category"])) {
                echo json_encode(false);
                break;
            }

            book_data : $book = new book_data();
            $book->title = $_POST["title"];
            $book->author = $_POST["author"];
            $book->state = $_POST["state"];
            $book->category = $_POST["category"];
            $book->price = $_POST["price"];
            $book->user_email = get_client_info()["email"];
            
            echo json_encode($db_conn->get_books()->add_book($book));
            break;
    }
} catch(exception $e) {
    echo json_encode(false);
}
?>