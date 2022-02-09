<script type="text/javascript">
    function onAddOrder() {
        const data = {
            "action": "add",
            "card": $_GET["card"],
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
<section>
    <header class="row col text-center">
        <h2>Add new order</h2>
    </header>
    <section>
        <p id="error-internal" class="row col-12 alert alert-danger login-alert" role="alert">Something went wrong! Check the values and try again.</p>
        <?php
        
        $card = new payment_data(); 
        $on_confirm_value = "Add";
        $on_confirm_func = "onAddOrder();";?>
        <form class="col-10 offset-1 col-md-6 offset-md-3 p-0 row text-center" method="post">
        <input class="col-12 justify-content-center btn button-primary m-0" type="button" id="confirm-button" onclick="<?php echo $on_confirm_func; ?>" value="<?php echo $on_confirm_value; ?>"/>
        </form>
    </section>