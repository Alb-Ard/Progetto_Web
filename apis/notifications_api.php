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
        case "remove":
            if (!isset($_POST["id"])) {
                echo false;
                return;
            }
            echo $db_conn->get_notifications()->remove($_POST["id"]);
            break;
        case "get":
            echo json_encode($db_conn->get_notifications()->get_user(get_client_info()["email"]));
            break;
    }
} catch(exception $e) {
    echo false;
}
?>