<header class="row">
    <h2 class="col-12 text-center"><?php echo $book->title; ?></h2>
</header>
<section class="row justify_content_center">
    <img class="col-12 col-md-3 offset-md-1" src="./imgs/archive.png" alt="book image"></img>
    <section class="col-12 col-md-4">
        <h3>Book info:</h3>
        <p>Written by <?php echo $book->author; ?></p>
        <p>Sold by <?php 

        $user = $db_conn->get_users()->get_infos($book->user_email);
        echo $user["first_name"] . " " . $user["last_name"];

        ?></p>
        <p>Price: <?php echo $book->price; ?>€</p>
        <h3>Condition reported by seller:</h3>
        <p class="text-wrap"><?php echo $book->state; ?></p>
    </section>
    <aside class="col-12 col-md-3">
        <?php
        
        if (is_user_logged() && $book->user_email == get_client_info()["email"]) { ?>
            <input class="btn button-primary w-100" type="button" value="Edit listing" onclick="window.document.href = './seller/edit.php?id=<?php echo $book->id; ?>';"></input>
        <?php } else {
            $book_available = $book->available != BOOK_SOLD;
            $add_to_cart_value = is_user_logged() ? ($book_available ? "Add to cart" : "Unavailable") : "Login to add to your cart"; 
            $add_to_cart_onclick = !is_user_logged() ? "$('#user-menu').slideDown();" : ""; ?>
            <!-- TODO: disable if already in cart -->
            <input class="btn button-secondary w-100 mb-3" type="button" value="<?php echo $add_to_cart_value; ?>" onclick="<?php echo $add_to_cart_onclick; ?>"<?php if(!$book_available) echo ' disabled="true"'; ?>></input>
        <?php }
        
        ?>
    </aside>
</section>