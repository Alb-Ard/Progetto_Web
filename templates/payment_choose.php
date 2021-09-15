<header class="row">
    <h2 class="col text-center"><?php echo $template_args[PAGE_TITLE]; ?></h2>
</header>
<section>
    <ul class="row m-0 p-0 justify-content-center">
        <?php
        
        $payment_methods = $db_conn->get_payment_methods()->get_payment_methods((get_client_info()["email"]));
        
        if (count($payment_methods) == 0) { ?>
            <h3>There are no credit cards!</h3>
        <?php } else {
            foreach($payment_methods as $card) { ?>
                <li class="col-5 col-md-1 row position-relative category-list-book">
                    <header>
                        <h3 class="col-12">
                            <a class="stretched-link black-link" href="../address_choose.php?card=<?php echo $card->payment_id; ?>"><?php echo $card->type; ?></a>
                        </h3>
                        <img class="col-12" src="./imgs/credit-card.png" alt="<?php echo $card->number; ?> image">
                    </header>
                    <p class="col-12"><?php echo $card->number ?></p>
                </li>
            <?php }
        }
        
        ?>
    </ul>
    <script type="text/javascript">
    function onAddCard() {
        const data = {
            "action": "add",
            "type": $("#type").val(),
            "number": $("#number").val(),
            "date": $("#date").val(),
        };
        $("#confirm-button").attr("disabled");
        $.post("./apis/cards_api.php", data, (result) => {
            if (!JSON.parse(result)) {
                $("#confirm-button").removeAttr("disabled");
                $("#error-internal").slideDown();
            } else
                window.location.href = "./seller_dashboard.php?completed=true";
        });
    }
</script>
<section>
    <header class="row col text-center">
        <h2>Add new card</h2>
    </header>
    <section>
        <p id="error-internal" class="row col-12 alert alert-danger login-alert" role="alert">Something went wrong! Check the values and try again.</p>
        <?php 
        
        $card = new payment_data(); 
        $on_confirm_value = "Add";
        $on_confirm_func = "onAddCard();";
        include_once("./templates/card_form.php");
        
        ?>
    </section>
</section>
</section>