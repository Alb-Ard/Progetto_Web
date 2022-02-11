<script type="text/javascript">
    function onAddOrder() {
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
<?php $card = $db_conn->get_payment_methods()->get_card($_GET["card"]); ?>

<section>
    <header class="row col text-center">
        <h2>Confirm Order</h2>
    </header>
    <ul class="p-3 d-flex flex-wrap justify-content-center" id="book-list">
    <li class="col-5 col-md-1 row position-relative category-list-book">
        
                    <header class="card-header">
                        <h3 class="card-title">
                        <p class="stretched-link black-link"><?php echo $card->type; ?></a>
                        </h3>
                        <img class="col-12" src="./imgs/credit-card.png" alt="<?php echo $card->number; ?> image">
                    <p class="col-12"><?php echo $card->number ?></p>
                    </header>
                </li>
                </ul>
                <?php $books = $db_conn->get_carted_books()->get_carted_books(get_client_info()["email"]); 
                foreach($books as $book) {?>
                <ul class="p-3 d-flex flex-wrap justify-content-center" id="book-list">
                    <li class="card shadow m-3">
                        <header class="card-header">
                            <h3 class="text-truncate card-title">
                                <p class="black-link"><?php echo $book->title; ?></a>
                            </h3>
                        </header>
                        <img class="book-cover m-2" src="<?php echo $book->image ?>" alt="<?php echo $book->title; ?> image">
                        <p class="text-center"><strong><?php echo $book->price ?>€</strong></p><?php
                } ?>
                    </li>
            </ul>
    <section>
        <p id="error-internal" class="row col-12 alert alert-danger login-alert" role="alert">Something went wrong! Check the values and try again.</p>
        <?php
        
        $card = new payment_data(); 
        $on_confirm_value = "Confirm";
        $on_confirm_func = "onAddOrder();";?>
        <form class="col-10 offset-1 col-md-6 offset-md-3 p-0 row text-center" method="post">
        <input class="col-12 justify-content-center btn button-primary m-0" type="button" id="confirm-button" onclick="<?php echo $on_confirm_func; ?>" value="<?php echo $on_confirm_value; ?>"/>
        </form>
    </section>