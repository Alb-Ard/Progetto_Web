<?php

include("../database.php");
include("../session.php");

try {
    $db_conn = new database();
    if (!$db_conn->connect("localhost", "root", "") || !is_user_logged()) {
        echo json_encode(false);
        return;
    }

    $new_states = [ "WAITING" => "SENT", "SENT" => "RECEIVED"];
    echo json_encode($db_conn->get_orders()->set_ordered_book_state($_POST["book_id"], $_POST["order_id"], $new_states[$_POST["state"]]));
} catch(exception $e) {
    echo json_encode(false);
}
?>