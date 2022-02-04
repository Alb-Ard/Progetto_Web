<?php

include("../database.php");
include("../session.php");

try {
    $db_conn = new database();
    if (!$db_conn->connect("localhost", "root", "") || !isset($_POST["action"])) {
        echo json_encode(false);
        return;
    }

    if (!is_user_logged()) {
        echo json_encode(false);
        return;
    }

    switch($_POST["action"]) {
        case "add":
            if (!isset($_POST["book_id"])) {
                echo json_encode(false);
                return;
            }
            echo json_encode($db_conn->get_carted_books()->add_book_to_cart(get_client_info()["email"], $_POST["book_id"]));
            break;
        case "remove":
            if (!isset($_POST["book_id"])) {
                echo json_encode(false);
                return;
            }
            echo json_encode($db_conn->get_carted_books()->remove_book_to_cart(get_client_info()["email"], $_POST["book_id"]));
            break;
        case "get":
            echo json_encode($db_conn->get_carted_books()->get_carted_books(get_client_info()["email"]));
            break;
        case "get_total_price":
            $books = $db_conn->get_carted_books()->get_carted_books(get_client_info()["email"]);
            $total = 0;
            foreach ($books as $book) {
                $total += $book->price;
            }
            echo json_encode($total);
            break;
    }

} catch(exception $e) {
    echo json_encode(false);
}

?>