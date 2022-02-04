<?php

include_once("./pages_commons.php");

$template_args[PAGE_TITLE] = "Orders";
$template_args[PAGE_BODY] = "./templates/orders.php";
$template_args[PAGE_FOOTER] = "./templates/cancel_order_modal.php";

include_once("./templates/page_base.php");

?>