<?php

include_once("./pages_commons.php");

$template_args[PAGE_TITLE] = "You";
$template_args[PAGE_BODY] = "./templates/user.php";
$template_args[PAGE_FOOTER] = "./templates/unregister_user_modal.php";

include_once("./templates/page_base.php");

?>