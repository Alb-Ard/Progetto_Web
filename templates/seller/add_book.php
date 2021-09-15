<script type="text/javascript">
    function onAddBook() {
        $("#confirm-button").attr("disabled");
        $.ajax({
            url: "./apis/books_api.php", 
            data: new FormData($("#book-form").get()[0]), 
            type: "POST",
            processData: false,
            contentType: false,
            success: (result) => {
                if (!JSON.parse(result)) {
                    $("#confirm-button").removeAttr("disabled");
                    $("#error-internal").slideDown();
                } else
                    window.location.href = "./seller_dashboard.php?completed=true";
            }
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
        $image_required = true;
        include_once("./templates/seller/book_form.php");
        
        ?>
    </section>
</section>