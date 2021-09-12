<?php

include("../users_consts.php");
include("../database.php");
include("../session.php");

// TODO: Add seller functionality

try {
    database : $db_conn = new database();
    if (!$db_conn->connect("localhost", "root", "")) {
        echo RESULT_INTERNAL;
        return;
    }

    if (!isset($_POST[USER_EMAIL])) {
        echo RESULT_MISSING_FIELDS;
        return;
    }

    if (!is_user_logged() || $_POST[USER_EMAIL] != get_client_info()["email"]) {
        echo RESULT_INTERNAL;
        return;
    }

    bool : $result = $db_conn->get_users()->remove_user($_POST[USER_EMAIL]);

    if ($result)
        logout_user();

    echo $result ? RESULT_OK : RESULT_KO;
} catch(exception $e) {
    echo RESULT_INTERNAL;
}

?>