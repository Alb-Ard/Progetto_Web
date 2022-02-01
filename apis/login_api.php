<?php

include("../users_consts.php");
include("../database.php");
include("../session.php");

try {
    database : $db_conn = new database();
    if (!$db_conn->connect("localhost", "root", ""))
    {
        echo RESULT_INTERNAL;
        return;
    }

    if (is_user_logged()) {
        logout_user();
        echo RESULT_OK;
        return;
    }

    if (!isset($_POST[USER_EMAIL]) || !isset($_POST[USER_PSW]))
    {
        echo RESULT_MISSING_FIELDS;
        return;
    }

    bool : $result = $db_conn->get_users()->check_credentials($_POST[USER_EMAIL], $_POST[USER_PSW]);

    if ($result)
        $result |= login_user($_POST[USER_EMAIL]);

    echo $result ? RESULT_OK : RESULT_KO;
} catch(exception $e) {
    echo RESULT_INTERNAL;
}
?>