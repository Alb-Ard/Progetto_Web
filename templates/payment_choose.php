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

<aside>
    <p id="error-internal" class="alert alert-danger login-alert" role="alert">Something went wrong! Check the values and try again.</p>
</aside>

<section>
    <?php
    
    $payment_methods = $db_conn->get_payment_methods()->get_payment_methods((get_client_info()["email"]));
    
    if (count($payment_methods) == 0) { ?>
        <h3>There are no credit cards!</h3>
    <?php } else { ?> 
        <ul class="d-flex flex-wrap m-0 p-0 justify-content-center">
            <?php
            foreach($payment_methods as $card) { ?>
                <li class="m-1 card card-no-hover category-list-book">
                    <header class="card-header center">
                        <h3 class="card-title"><?php echo $card->type; ?></h3>
                    </header>
                    <div class="card-body text-center">
                        <img class="img-fluid w-50" src="./imgs/credit-card.png" alt="">
                        <p>Ends with: <?php echo substr($card->number, 12); ?></p>
                        <div class="row mx-1">
                            <div class="col-12 col-md-6 p-1">
                                <a class="w-100 btn button-primary black-link" href="./order.php?card=<?php echo $card->payment_id; ?>">Select</a>
                            </div>
                            <div class="col-12 col-md-6 p-1">
                                <button class="w-100 btn btn-danger" onclick="onRemoveCard(<?php echo $card->payment_id?>);">Remove</button>
                            </div>
                        </div>
                    </div>
                </li>
            <?php } ?>
        </ul>
    <?php } ?>
</section>

<div class="row my-3">
    <hr class="visually-hidden"/>
    <div class="col my-auto"><div class="h-0 border border-dark"></div></div>
    <p class="col-auto my-auto">OR</p>
    <div class="col my-auto"><div class="h-0 border border-dark"></div></div>
</div>

<section>
    <header class="text-center">
        <h2>Add new card</h2>
    </header>
    <?php 
    
    $card = new payment_data(); 
    $on_confirm_value = "Add";
    $on_confirm_func = "onAddCard();";
    include_once("./templates/card_form.php");
    
    ?>
</section>