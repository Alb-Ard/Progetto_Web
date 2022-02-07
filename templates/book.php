<?php

$seller = $db_conn->get_users()->get_infos($book->user_email);
$logged = is_user_logged();
$user = get_client_info();
$book_available = $book->available != BOOK_SOLD;
$book_in_cart = $logged ? $db_conn->get_carted_books()->is_book_in_cart($user["email"], $book->id) : false;
$button_value = $logged ? ($book_available ? ($book_in_cart ? "Remove from cart" : "Add to cart") : "Unavailable") : "Login or register to buy"; 
$button_func = !$logged? "window.location.href = './login.php';" : "onAddToCart();";

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

<aside>
    <p id="success" class="alert alert-primary text-center" role="alert">Operation completed! <a class="stretched-link" href="./cart.php">Go to cart...</a></p>
    <p id="failed" class="alert alert-danger text-center" role="alert">Something went wrong, please try again.</p>
</aside>

<section class="row justify-content-center">
    <header>
        <h2 class="text-center"><?php echo $book->title; ?></h2>
    </header>
    <span class="col-12 col-lg-3 mb-1">
        <img class="img-fluid mx-auto" src="<?php echo $book->image; ?>" alt="book image"/>
    </span>
    <span class="col-12 col-lg-4">
        <h3>Book info:</h3>
        <p>Written by <?php echo $book->author; ?></p>
        <p>Sold by <?php echo $seller["first_name"] . " " . $seller["last_name"]; ?></p>
        <h3>Condition reported by seller:</h3>
        <p class="text-wrap"><?php echo $book->state; ?></p>
    </span>
    <footer class="col-12 col-lg-3">
        <div class="border rounded px-3 pt-2">
            <p>Price: <strong><?php echo $book->price; ?>€</strong></p>
            <?php
            
            if ($logged && $book->user_email == $user["email"]) { ?>
                <a class="btn button-primary w-100" href="./seller_edit.php?id=<?php echo $book->id; ?>">Edit listing</a>
            <?php } else { ?>
                <button id="action-button" class="btn button-secondary w-100 mb-3" type="button" onclick="<?php echo $button_func; ?>"<?php if (!$book_available) echo ' disabled="true"'; ?>><?php echo $button_value; ?></button>
            <?php }

            ?>
        </div>
    </footer>
</section>