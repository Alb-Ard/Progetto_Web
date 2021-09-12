<?php

include_once("./pages_commons.php");

define("REGISTER_ACTION", "action");
define("REGISTER_CLIENT", "client");
define("REGISTER_SELLER", "seller");

$template_args[PAGE_TITLE] = "Register";

if (isset($_GET[REGISTER_ACTION]))
    $template_args[PAGE_BODY] =  "./templates/" . ($_GET[REGISTER_ACTION] == REGISTER_CLIENT ? "register_client.php" : "register_seller.php");
else
    $template_args[PAGE_BODY] = "./templates/register_choose.php";

include_once("./templates/page_base.php");

?>