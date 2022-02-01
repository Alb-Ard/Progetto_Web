<?php

include("../database.php");
include("../session.php");

try {
    $db_conn = new database();
    if (!$db_conn->connect("localhost", "root", "") || !is_user_logged()) {
        echo false;
        return;
    }

    if (!isset($_POST["action"])) {
        echo false;
        return;
    }

    switch($_POST["action"]) {
        case "get":
            echo json_encode($db_conn->get_orders()->get_seller_ordered_books(get_client_info()["email"]));
            break;
        case "advance":
            $new_state = ([ "WAITING" => "SENT", "SENT" => "RECEIVED" ])[$_POST["state"]];
            $order = $_POST["order_id"];
            $book = $_POST["book_id"];
            if (!$db_conn->get_orders()->set_ordered_book_state($book, $order, $new_state)){
                echo false;
                return;
            }

            $client = $db_conn->get_orders()->get_order_client($order, $book);
            $db_conn->get_notifications()->add($client, get_client_info()["email"], $order, $new_state);
            echo json_encode($new_state);
            break;
    }
} catch(exception $e) {
    echo false;
}
?>