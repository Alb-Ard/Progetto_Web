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
        case "get_purchased":
            echo json_encode($db_conn->get_orders()->get_orders(get_client_info()["email"]));
            break;
        case "advance":
            if (!isset($_POST["order_id"]) || !isset($_POST["book_id"]) || !isset($_POST["state"])) {
                echo false;
                return;
            }
            $new_state = ([ "WAITING" => "SENT", "SENT" => "RECEIVED" ])[$_POST["state"]];
            $order = $_POST["order_id"];
            $book = $_POST["book_id"];
            if (!$db_conn->get_orders()->set_ordered_book_state($book, $order, $new_state)){
                echo false;
                return;
            }
            $client = $db_conn->get_orders()->get_order_client($order, $book);
            $db_conn->get_notifications()->add($client, get_client_info()["email"], $order, $book, $new_state);
            echo json_encode($new_state);
            break;
        case "cancel":
            if (!isset($_POST["book_id"])) {
                echo false;
                return;
            }
            $book = $_POST["book_id"];
            $order_info = $db_conn->get_orders()->get_book_order($book);
            if (count($order_info) < 1) {
                echo false;
                return;
            }
            $order = $order_info["order_id"];
            $client = $db_conn->get_orders()->get_order_client($order, $book);
            if (!$db_conn->get_orders()->cancel_order($order, $book)) {
                echo false;
                return;
            }
            $db_conn->get_notifications()->add($client, get_client_info()["email"], $order, $book, "CANCELED");
            $db_conn->get_notifications()->add(get_client_info()["email"], $client, $order, $book, "CANCELED");
            echo true;
            break;
        case "add":
            if (!isset($_POST["card"])) {
                echo false;
                return;
            }
            $payment_id = $_POST["card"];
            $card = $db_conn->get_payment_methods()->get_card($payment_id);
            if($card->user_id!=get_client_info()["email"]){
                echo false;
                return;
            }
            $books = $db_conn->get_carted_books()->get_carted_books(get_client_info()["email"]);
            if(count($books)<=0){
                echo false;
                return;
            }
            $order = $db_conn->get_orders()->add_order(get_client_info()["email"], $payment_id, 1);
            if($order>-1){
                foreach($books as $book){
                    if ($db_conn->get_orders()->add_ordered_book($order, $book->id, "waiting")){
                    $owner = $book->user_email;
                    $db_conn->get_notifications()->add($owner, get_client_info()["email"], $order, $book->id, "WAITING");
                    }
                    else {
                        echo false;
                        return;
                    }
                }
            }
            echo true;
            break;
    }
} catch(exception $e) {
    echo false;
}
?>