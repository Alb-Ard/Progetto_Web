<script type="text/javascript">
    function onChangeState(book, order, fromState) {
        const data = {
            "book_id": book,
            "order_id": order,
            "state": fromState,
        };
        $.post("./apis/orders_api.php", data, (result) => {
            if(JSON.parse(result)) {
                window.location.reload(true);
            }
        });
    }
</script>
<section class="row">
    <p class="alert alert-danger login-alert" id="error-internal" role="alert">Something went wrong! Please try again.</p>
</section>
<ul class="row m-0 p-0 justify-content-around">
    <?php
    
    $orders = $db_conn->get_orders()->get_seller_ordered_books($user_info["email"]);
    $order_books = $db_conn->get_orders()->get_seller_orders($user_info["email"]);

    for($i = 0; $i < count($orders); $i++) { 
        $order = $orders[$i];
        $book = $order_books[$i];
        ?>
        <li class="col-12 col-md-5 position-relative seller-listing-book mx-0 my-1" id="<?php echo $book->id; ?>">
            <article class="row justify-content-around m-0 p-1">
                <header class="col-12 col-md-8">
                    <h3 class="d-inline-block text-truncate w-100"><?php echo $book->title; ?></h3>
                </header>
                <p class="col-12 col-md-4 text-end">Price: <?php echo $book->price; ?>€</p>
                <p class="col-12 col-md-8 text-end">Current state: <?php echo $order["advancement"]; ?></p>
                <button class="col-12 col-md-4 btn button-secondary" 
                onclick='onChangeState(<?php echo "{$book->id}, {$order["order_id"]}, \"{$order["advancement"]}\""; ?>);'
                <?php if ($order["advancement"] == "RECEIVED") echo " disabled"; ?>>Advance state</button>
            </article>
        </li>
    <?php }

    ?>
</ul>