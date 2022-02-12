<script type="text/javascript">
    function onAddOrder() {
        event.preventDefault();
        const data = {
            "action": "add",
            "card": <?php echo $_GET["card"]; ?>,
        };
        $("#confirm-button").attr("disabled");
        $.post("./apis/orders_api.php", data, (result) => {
            if (!JSON.parse(result)) {
                $("#confirm-button").removeAttr("disabled");
                $("#error-internal").slideDown();
            } else
                window.location.href = "./orders.php?completed=true";
        });
    }
</script>

<aside>
    <p id="error-internal" class="row col-12 alert alert-danger login-alert" role="alert">Something went wrong! Check the values and try again.</p>
</aside>

<section>
    <header class="text-center">
        <h2>Confirm Order</h2>
    </header>
    <div class="row">
        <section class="col-12 col-lg-10">
            <header>
                <h3 class="visually-hidden">Cart</h3>
            </header>
            <ul class="p-3 d-flex flex-wrap justify-content-center" id="book-list">
                <?php 
                
                $books = $db_conn->get_carted_books()->get_carted_books(get_client_info()["email"]); 
                $total = 0;

                foreach($books as $book) { 
                    $total += floatval($book->price);
                    ?>
                    <li class="card card-no-hover shadow m-3">
                        <header class="card-header">
                            <h3 class="text-truncate card-title"><?php echo $book->title; ?></h3>
                        </header>
                        <img class="book-cover m-2" src="<?php echo $book->image ?>" alt="<?php echo $book->title; ?> image"/>
                        <p class="text-center"><strong><?php echo $book->price ?>€</strong></p>
                    </li>
                    <?php } ?>
            </ul>
        </section>
        <aside class="col-12 col-lg-2 border shadow bg-light p-3 h-100">
            <?php 

            $card = $db_conn->get_payment_methods()->get_card($_GET["card"]); 
            $censored_card_number = "Ends with: " . substr($card->number, strlen($card->number) - (strlen($card->number) / 4));

            ?>
            <section class="border-bottom border-dark mb-3">
                <header class="text-center py-1">
                    <h3>Payment method</h3>
                </header>
                <div class="row">
                    <div class="col-3 col-lg-12 col-xxl-3 my-auto">
                        <img class="img-fluid" src="./imgs/credit-card.png" alt="card image">
                    </div>
                    <div class="col-9 col-lg-12 col-xxl-9">
                        <p><?php echo $card->type; ?></p>
                        <p><?php echo $censored_card_number ?></p>
                    </div>
                    <div class="col-12">
                        <a class="btn button-secondary w-100 mb-3" href="./payment_choose.php">Change</a>
                    </div>
                </div>
            </section>
            <form class="p-0 text-center" method="post" action="#">
                <p>Total: <strong><?php echo $total; ?>€</strong></p>
                <input class="w-100 btn button-primary m-0" type="submit" id="confirm-button" onclick="onAddOrder(event);" value="Confirm"/>
            </form>
        </aside>
    </div>
</section>