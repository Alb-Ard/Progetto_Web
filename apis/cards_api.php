<?php

include("../database.php");
include("../session.php");

function create_card_from_data(array $data): ?payment_data {
    if (!isset($data["type"]) || !isset($data["date"]) || !isset($data["number"]) || !isset($data["cvv"]))
        return NULL;
    if (!is_numeric($data["number"]) || !is_numeric($data["cvv"]))
        return NULL;

    $card = new payment_data();
    $card->user_id = get_client_info()["email"];
    $card->type = $data["type"];
    $card->number = $data["number"];
    $card->cvv = $data["cvv"];
    $card->date = $data["date"];
    return $card;
}

try {
    $db_conn = new database();
    if (!$db_conn->connect("localhost", "root", "") || !isset($_POST["action"])) {
        echo json_encode(false);
        return;
    }

    switch($_POST["action"]) {
        case "list":
            $result = $db_conn->get_payment_methods()->get_payment_methods($_POST["email"]);
            echo json_encode($result);
            break;
        case "add":
            if (!is_user_logged()) {
                echo json_encode(false);
                break;
            }

            $card = create_card_from_data($_POST);
            echo json_encode($card == NULL ? false : $db_conn->get_payment_methods()->add_card($card));
            break;
        case "edit":
            if (!is_user_logged() || !isset($_POST["id"])) {
                echo json_encode(false);
                break;
            }
            if ($db_conn->get_payment_methods()->get_card($_POST["id"])->user_email != get_client_info()["email"]) {
                echo json_encode(false);
                break;
            }
            
            $card = create_card_from_data($_POST);
            if ($card == NULL) {
                echo json_encode(false);
                break;
            }
            
            $card->payment_id = $_POST["payment_id"];
            echo json_encode($db_conn->get_payment_methods()->edit_card($card));
            break;
        case "remove":
            echo json_encode($db_conn->get_payment_methods()->remove_card(get_client_info()["email"], $_POST["payment_id"]));
            break;
    }
} catch(exception $e) {
    echo json_encode(false);
}
?>