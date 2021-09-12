<script type="text/javascript">
    function onAddBook() {
        const data = {
            "action": "add",
            "title": $("#title").val(),
            "author": $("#author").val(),
            "category": "0", //$("#category").val(),
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
                window.location.href = "./?completed=true";
        });
    }
</script>
<section>
    <header class="row col text-center">
        <h2>Add new book</h2>
    </header>
    <section>
        <p id="error-internal" class="row col-12 alert alert-danger login-alert" role="alert">Something went wrong! Check the values and try again.</p>

        <form class="container-md text-center" method="post">
            <label class="row col justify-content-center form-label mb-3">Title:
                <input class="form-control" type="text" id="title" name="title" placeholder="Insert title" required="true"/>
            </label>
            <label class="row col justify-content-center form-label mb-3">Author:
                <input class="form-control" type="text" id="author" name="author" placeholder="Insert author" required="true"/>
            </label>
            <!--<label class="row col justify-content-center form-label mb-3">Category:
                <input class="form-control" type="option" id="category" name="category" placeholder="Repeat password" required="true"/>
            </label>-->
            <label class="row col justify-content-center form-label mb-3">State:
                <input class="form-control" type="text" id="state" name="state" placeholder="Insert state" required="true"/>
            </label>
            <label class="row col justify-content-center form-label mb-3">Price:
                <input class="form-control" type="number" id="price" min="0.00" step="0.01" required="true" value="0"/>
            </label>
            <input class="row col-md-3 justify-content-center btn button-primary" type="button" id="add-button" onclick="onAddBook();" value="Add">
                <div class="spinner-border spinner-border-sm d-none" id="add-button-spinner" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
            </input>
        </form>
    </section>
</section>