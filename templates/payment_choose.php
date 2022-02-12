<header class="row">
    <h2 class="col text-center"><?php echo $template_args[PAGE_TITLE]; ?></h2>
</header>
<script type="text/javascript">
function onRemoveCard(id) {
        const data = {
            "action": "remove",
            "payment_id": id,
            };
        $("#confirm-button").attr("disabled");
        $.post("./apis/cards_api.php", data, (result) => {
            if (!JSON.parse(result)) {
                $("#confirm-button").removeAttr("disabled");
                $("#error-internal").slideDown();
            } else
                window.location.href = "./payment_choose.php?completed=true";
        });
    } 
    </script>
<section>
    <ul class="row m-0 p-0 justify-content-center">
        <?php
        
        $payment_methods = $db_conn->get_payment_methods()->get_payment_methods((get_client_info()["email"]));
        
        if (count($payment_methods) == 0) { ?>
            <h3>There are no credit cards!</h3>
        <?php } else {
            foreach($payment_methods as $card) { ?>
                <li class="col-5 col-md-1 m-1 row position-relative category-list-book">
                    <header class="card-header center">
                        <h3 class="card-title">
                            <h2 class="col-12 text-center"><?php echo $card->type; ?></a>
                        </h3>
                        <img class="col-12 text-center" src="./imgs/credit-card.png" alt="<?php echo $card->number; ?> image">
                    
                    <p class="text-center"><?php echo "** ". substr($card->number, 12) ?></p>
                    </header>
                <button class="btn button-primary m-0" onclick="window.location.href='./order.php?card=<?php echo $card->payment_id; ?>'">Select</button>
                <button class="btn btn-danger" onclick=onRemoveCard(<?php echo $card->payment_id?>)>Remove</button>
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
            "cvv": $("#cvv").val(),
            "date": $("#year").val()+'-'+$("#month").val(),
        };
        $("#confirm-button").attr("disabled");
        $.post("./apis/cards_api.php", data, (result) => {
            if (!JSON.parse(result)) {
                $("#confirm-button").removeAttr("disabled");
                $("#error-internal").slideDown();
            } else
                window.location.href = "./payment_choose.php?completed=true";
        });   
    }
</script>
<section>
    <header class="row col text-center">
        <h2>OR</h2>
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