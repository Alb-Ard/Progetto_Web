<?php 

include_once("./users_consts.php"); 

?>

<section>
    <header class="row col text-center">
        <h2>Notifications</h2>
    </header>
    <section>
        <ol class="list-group">
            <?php 

            $notifications = $db_conn->get_notifications()->get_user(get_client_info()["email"]);
            foreach ($notifications as $n) { ?>
                <li class="list-group-item">
                    <p>Your order from <?php echo $n["from_user"]; ?> is now in state <?php echo $n["order_state"]; ?></p>
                    <p><?php echo $n["created_timestamp"]; ?></p>
                </li>
            <?php } 
            
            ?>
        </ol>
    </section>
</section>