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
                if (!is_user_logged()) { ?>
                    <li class="top-bar-user-menu-item"><a class="black-link text-nowrap stretched-link" href="./login.php?from=<?php echo $_SERVER["REQUEST_URI"]; ?>">Login</a></li>
                    <li class="top-bar-user-menu-item"><a class="black-link text-nowrap stretched-link" href="./register.php?from=<?php echo $_SERVER["REQUEST_URI"]; ?>">Register</a></li>
                <?php } else { ?> 
                    <li class="top-bar-user-menu-item"><a class="black-link text-nowrap stretched-link" href="./cart.php">Cart</a></li>
                    <li class="top-bar-user-menu-item"><a class="black-link text-nowrap stretched-link" href="./orders.php">Orders</a></li>
                    <li class="top-bar-user-menu-item"><a class="black-link text-nowrap stretched-link" href="./user.php">Profile</a></li>
                    <li class="top-bar-user-menu-item"><a class="black-link text-nowrap stretched-link" href="#" onclick="onLogout();">Logout</a></li>
                    <li><hr class="dropdown-divider"/></li>
                    <li class="top-bar-user-menu-item"><a class="black-link text-nowrap stretched-link" href="./seller_dashboard.php">Seller's dashboard</a></li>
                <?php }
            ?></ul>
        </div>
    <?php } else {
        print_user_avatar();
    }
    
?>
</div>