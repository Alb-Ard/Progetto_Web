<script type="text/javascript">
    function onAddBook() {
        $("#confirm-button").attr("disabled");
        $.post("./apis/books_api.php", new FormData($("#book-form").get()[0]), (result) => {
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
        $action = "add";
        include_once("./templates/seller/book_form.php");
        
        ?>
    </section>
</section>