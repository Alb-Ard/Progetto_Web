<?php

$seller = $db_conn->get_users()->get_infos($book->user_email);
$logged = is_user_logged();
$user = get_client_info();
$book_available = $book->available != BOOK_SOLD;
$book_in_cart = $logged ? $db_conn->get_carted_books()->is_book_in_cart($user["email"], $book->id) : false;
$button_value = $logged ? ($book_available ? ($book_in_cart ? "Remove from cart" : "Add to cart") : "Unavailable") : "Login to add to your cart"; 
$button_func = !$logged? "$('#user-menu').slideDown();" : "onAddToCart();";

?>
<script type="text/javascript">
    let next_action = "<?php echo $book_in_cart ? "remove" : "add" ?>";

    $(document).ready(() => {
        $("#success").hide();
        $("#failed").hide();
    });

    function onAddToCart() {
        const data = {
            "action": next_action,
            "book_id": <?php echo $book->id; ?>,
        };
        $("#action-button").attr("disabled");
        $("#success").hide();
        $("#failed").hide();
        $.post("./apis/cart_api.php", data, (result) => {
            $("#action-button").removeAttr("disabled");
            if (JSON.parse(result)) {
                $("#action-button").val(next_action == "add" ? "Remove from cart" : "Add to cart");
                next_action = next_action == "add" ? "remove" : "add";
                $("#success").slideDown();
            } else {
                $("#failed").slideDown();
            }
        });
    }
</script>
<p id="success" class="row col-12 col-md-10 offset-md-1 alert alert-primary" role="alert">Operation completed!</p>
<p id="failed" class="row col-12 col-md-10 offset-md-1 alert alert-danger" role="alert">Something went wrong, please try again.</p>
<header class="row">
    <h2 class="col-12 text-center"><?php echo $book->title; ?></h2>
</header>
<section class="row justify_content_center">
    <img class="col-12 col-md-3 offset-md-1" src="./imgs/archive.png" alt="book image"/>
    <div class="col-12 col-md-4">
        <h3>Book info:</h3>
        <p>Written by <?php echo $book->author; ?></p>
        <p>Sold by <?php 

        echo $seller["first_name"] . " " . $seller["last_name"];

        ?></p>
        <p>Price: <?php echo $book->price; ?>€</p>
        <h3>Condition reported by seller:</h3>
        <p class="text-wrap"><?php echo $book->state; ?></p>
    </div>
    <aside class="col-12 col-md-3">
        <?php
        
        if ($logged && $book->user_email == $user["email"]) { ?>
            <a class="btn button-primary w-100" type="button" href="./seller_edit.php?id=<?php echo $book->id; ?>">Edit listing</a>
        <?php } else { ?>
            <input class="btn button-secondary w-100 mb-3" id="action-button" type="button" value="<?php echo $button_value; ?>" onclick="<?php echo $button_func; ?>"<?php if(!$book_available) echo ' disabled="true"'; ?>/>
        <?php }
        
        ?>
    </aside>
</section>