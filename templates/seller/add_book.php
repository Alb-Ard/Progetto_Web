<script type="text/javascript">
    function onAddBook(event) {
        event.preventDefault();
        if (!validateBookForm()) {
            return;
        }
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

<aside>
    <p id="error-internal" class="alert alert-danger login-alert" role="alert">Something went wrong! Check the values and try again.</p>
</aside>

<section>
    <header class="text-center">
        <h2>Add new book</h2>
    </header>
    <?php 
    
    $book = new book_data(); 
    $on_confirm_value = "Add";
    $on_confirm_func = "onAddBook(event);";
    $action = "add";
    $image_required = true;
    include_once("./templates/seller/book_form.php");
    
    ?>
</section>