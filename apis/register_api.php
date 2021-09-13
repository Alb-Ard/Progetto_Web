<?php

include("../users_consts.php");
include("../database.php");
include("../session.php");

try {
    $db_conn = new database();
    if (!$db_conn->connect("localhost", "root", ""))
    {
        echo RESULT_INTERNAL;
        return;
    }

    if (!isset($_POST[USER_EMAIL]) || !isset($_POST[USER_PSW]) || !isset($_POST[USER_FIRST_NAME]) || !isset($_POST[USER_LAST_NAME]))
    {
        echo RESULT_MISSING_FIELDS;
        return;
    }

    $result = $db_conn->get_users()->add_user($_POST[USER_EMAIL], $_POST[USER_PSW], $_POST[USER_FIRST_NAME], $_POST[USER_LAST_NAME]);

    // TODO: Add seller functionality
    if ($result)
        $result |= login_user($_POST[USER_EMAIL], SESSION_TYPE_CLIENT);

    echo $result ? RESULT_OK : RESULT_KO;
} catch(exception $e) {
    echo RESULT_INTERNAL;
}

?>