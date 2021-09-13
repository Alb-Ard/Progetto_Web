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
        $("#add-button").attr("disabled");
        $("#add-button-spinner").show();
        $.post("./apis/books_api.php", data, (result) => {
            if (!JSON.parse(result)) {
                $("#add-button").removeAttr("disabled");
                $("#add-button-spinner").hide();
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
        <?php $book = new book_data(); include_once("./templates/seller/book_form.php"); ?>
    </section>
</section>