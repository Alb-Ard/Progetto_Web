<?php

include("users_consts.php");

include("./database.php");

database : $db_conn = new database("localhost", "root", "");

if (!isset($_POST[USER_EMAIL]) || !isset($_POST[USER_PSW]) || !isset($_POST[USER_FIRST_NAME]) || !isset($_POST[USER_LAST_NAME]))
{
    echo json_encode(RESULT_MISSING_FIELDS);
    return;
}

bool : $result = $db_conn->get_users()->add_user($_POST[USER_EMAIL], $_POST[USER_PSW], $_POST[USER_FIRST_NAME], $_POST[USER_LAST_NAME]);
echo json_encode($result ? RESULT_OK : RESULT_OK);

?>