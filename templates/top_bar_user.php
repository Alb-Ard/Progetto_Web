<script type="text/javascript">
    let menuOpen = false;

    function toggleUserMenu() {
        $('#user-menu').slideToggle();
        $('#user-menu-avatar-anchor').attr("href", menuOpen ? "#" : "#user-menu");
        menuOpen = !menuOpen;
    }
</script>

<?php

$show_user_info = is_user_logged();
$unseen_notifications_count = $show_user_info ? $db_conn->get_notifications()->get_user_unseen_count(get_client_info()["email"]) : 0;
$avatar_column_size = $show_user_info ? 6 : 12;

function print_user_avatar() { 
    global $show_user_info;

    ?>
    <img class="top-bar-icon" src="./imgs/user_icon.png" alt="user icon"/>
    <?php 

    if ($show_user_info) { ?>
        <p class="w-100 text-center mt-1">
            <?php echo get_client_info()["first_name"]; ?>
        </p>
    <?php } 
}

if ($show_user_info) { ?>
    <span class="col-6">
        <a class="black-link" href="./notifications.php">
            <img class="top-bar-icon" src="./imgs/notifications.png" alt="unread notifications: <?php echo $unseen_notifications_count; ?>">
            <p class="w-100 text-center mt-1 <?php if ($unseen_notifications_count > 0) { ?> rounded-pill top-bar-notification-badge-unseen <?php } ?>">
                <?php echo $unseen_notifications_count; ?>
            </p>
        </a>
    </span>
<?php }

?>
<span class="col-<?php echo $avatar_column_size; ?>">
    <?php

    if (!isset($template_args[PAGE_HIDE_NAVBAR]) || !$template_args[PAGE_HIDE_NAVBAR]) { ?>
        <a id="user-menu-avatar-anchor" class="black-link" href="#" onclick="toggleUserMenu();">
            <?php print_user_avatar(); ?>
        </a>
    <?php } else {
        print_user_avatar();
    }
    
?>
</span>