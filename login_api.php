<?php

include("users_consts.php");

include("./database.php");

database : $db_conn = new database("localhost", "root", "");

if (!isset($_POST[USER_EMAIL]) || !isset($_POST[USER_PSW]))
{
    echo json_encode(RESULT_MISSING_FIELDS);
    return;
}

bool : $result = $db_conn->get_users()->check_credentials($_POST[USER_EMAIL], $_POST[USER_PSW]);
echo json_encode($result ? RESULT_OK : RESULT_KO);

?>