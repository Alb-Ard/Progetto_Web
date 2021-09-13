<script type="text/javascript">
    function onAddBook() {
        const data = {
            "action": "add",
            "title": $("#title").val(),
            "author": $("#author").val(),
            "category": $("#category").val(),
            "state": $("#state").val(),
            "price": $("#price").val(),
        };
        $("#confirm-button").attr("disabled");
        $.post("./apis/books_api.php", data, (result) => {
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
        <h2>Add new book</h2>
    </header>
    <section>
        <p id="error-internal" class="row col-12 alert alert-danger login-alert" role="alert">Something went wrong! Check the values and try again.</p>
        <?php 
        
        $book = new book_data(); 
        $on_confirm_value = "Add";
        $on_confirm_func = "onAddBook();";
        include_once("./templates/seller/book_form.php");
        
        ?>
    </section>
</section>