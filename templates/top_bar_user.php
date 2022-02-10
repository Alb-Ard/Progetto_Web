<?php

$show_user_info = is_user_logged();
$unseen_notifications_count = $show_user_info ? $db_conn->get_notifications()->get_user_unseen_count(get_client_info()["email"]) : 0;

function print_user_avatar() { 
    global $show_user_info;

    ?>
    <img class="top-bar-icon" src="./imgs/user_icon.png" alt="user icon"/>
    <?php 

    if ($show_user_info) { ?>
        <a class="black-link stretched-link mt-1 w-100 text-center d-block"><?php echo get_client_info()["first_name"]; ?></a>
    <?php } 
}

function get_encoded_request_uri() {
    return urlencode($_SERVER["REQUEST_URI"]);
}

function print_menu_item(string $href, string $text, string $onclick = null, string $img_src = null) { ?>
    <li class="top-bar-user-menu-item">
        <div class="row m-0 g-0">
            <?php
            
            if ($img_src != null) { ?>
                <img class="col-3 img-fluid my-auto" src="<?php echo $img_src; ?>" alt=""/>
            <?php }

            ?>
            <a class="col black-link stretched-link" href="<?php echo $href ?>" <?php if ($onclick != null) { ?> onclick="<?php echo $onclick; ?>" <?php } ?>><?php echo $text; ?></a>
        </div>
    </li>
<?php }

if ($show_user_info) { ?>
    <div class="col position-relative">
        <img class="top-bar-icon mb-1" src="./imgs/notifications.png" alt="unread notifications: <?php echo $unseen_notifications_count; ?>"/>
        <a class="d-block text-center black-link stretched-link <?php if ($unseen_notifications_count > 0) { ?> rounded-pill top-bar-notification-badge-unseen <?php } ?>" href="./notifications.php">
            <?php echo $unseen_notifications_count; ?>
        </a>
    </div>
<?php }

?>
<div class="col">
    <?php

    if (!isset($template_args[PAGE_HIDE_NAVBAR]) || !$template_args[PAGE_HIDE_NAVBAR]) { ?>
        <div class="dropdown p-0">
            <div id="user-menu-avatar-anchor position-relative" data-bs-toggle="dropdown">
                <?php print_user_avatar(); ?>
            </div>
            <ul class="dropdown-menu top-bar-user-menu" id="user-menu"><?php
                if (!is_user_logged()) { 
                    print_menu_item("./login.php?from=" . get_encoded_request_uri(), "Login", null, null);
                    print_menu_item("./register.php?from=" . get_encoded_request_uri(), "Register", null, null);
                } else { 
                    print_menu_item("./cart.php", "Cart", null, "./imgs/shopping-cart.png");
                    print_menu_item("./orders.php", "Orders", null, "./imgs/archive.png");
                    print_menu_item("./user.php", "Profile", null, "./imgs/user_icon.png");
                    print_menu_item("#", "Logout", "onLogout();", "./imgs/return.png");
                    ?>
                    <li><hr class="dropdown-divider my-0"/></li>
                    <?php 
                    print_menu_item("./seller_dashboard.php", "Seller's dashboard", null, "./imgs/seller_icon.png");
                    ?>
                <?php }
            ?></ul>
        </div>
    <?php } else {
        print_user_avatar();
    }
    
?>
</div>