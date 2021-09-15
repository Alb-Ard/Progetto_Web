<?php

$book = $db_conn->get_books()->get_book($_GET["id"]);

?>
<script type="text/javascript">
    function onEditBook() {
        $("#edit-button").attr("disabled");
        $.ajax({
            url: "./apis/books_api.php", 
            data: new FormData($("#book-form").get()[0]), 
            type: "POST",
            processData: false,
            contentType: false,
            success: (result) => {
                if (!JSON.parse(result)) {
                    $("#edit-button").removeAttr("disabled");
                    $("#error-internal").slideDown();
                } else
                    window.location.href = "./seller_dashboard.php?completed=true";
            }
        });
    }
</script>
<section>
    <header class="row col text-center">
        <h2>Edit book listing</h2>
    </header>
    <section>
        <p id="error-internal" class="row col-12 alert alert-danger login-alert" role="alert">Something went wrong! Check the values and try again.</p>
        <?php 
        
        $on_confirm_value = "Edit";
        $on_confirm_func = "onEditBook();";
        $action = "edit";
        $image_required = false;
        include_once("./templates/seller/book_form.php");
        
        ?>
    </section>
</section>