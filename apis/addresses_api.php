<?php

include("../database.php");
include("../session.php");

function create_address_from_data(array $data): ?address_data {
    if (!isset($data["address"]))
        return NULL;

    $address = new address_data();
    $address->user_id = get_client_info()["email"];
    $address->address = $data["address"];
    return $address;
}

try {
    $db_conn = new database();
    if (!$db_conn->connect("localhost", "root", "") || !isset($_POST["action"])) {
        echo json_encode(false);
        return;
    }

    switch($_POST["action"]) {
        case "list":
            $result = $db_conn->get_addresses()->get_addresses($_POST["email"]);
            echo json_encode($result);
            break;
        case "add":
            if (!is_user_logged()) {
                echo json_encode(false);
                break;
            }

            $address = create_address_from_data($_POST);
            echo json_encode($card == NULL ? false : $db_conn->get_addresses()->add_address($card));
            break;
        case "edit":
            if (!is_user_logged() || !isset($_POST["id"])) {
                echo json_encode(false);
                break;
            }
            if ($db_conn->get_addresses()->get_address($_POST["id"])->user_email != get_client_info()["email"]) {
                echo json_encode(false);
                break;
            }
            
            $address = create_address_from_data($_POST);
            if ($card == NULL) {
                echo json_encode(false);
                break;
            }
            
            $address->address_id = $_POST["address_id"];
            echo json_encode($db_conn->get_addresses()->edit_address($card));
            break;
        case "remove":
            echo json_encode($db_conn->get_addresses()->remove_address(get_client_info()["email"], $_POST["address_id"]));
            break;
    }
} catch(exception $e) {
    echo json_encode(false);
}
?>