<?php

include("../database.php");
include("../session.php");

try {
    $db_conn = new database();
    if (!$db_conn->connect("localhost", "root", "") || !isset($_POST["action"]) || !isset($_POST["book_id"])) {
        echo json_encode(false);
        return;
    }

    if (!is_user_logged()) {
        echo json_encode(false);
        return;
    }

    switch($_POST["action"]) {
        case "add":
            echo json_encode($db_conn->get_carted_books()->add_book_to_cart(get_client_info()["email"], $_POST["book_id"]));
            break;
        case "remove":
            echo json_encode($db_conn->get_carted_books()->remove_book_to_cart(get_client_info()["email"], $_POST["book_id"]));
            break;
    }

} catch(exception $e) {
    echo json_encode(false);
}

?>