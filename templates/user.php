<?php

$user_info = get_client_info();

if (count($user_info) == 0)
    die ("Error getting user info");

?>

<script type="text/javascript">
    $(document).ready(() => {
        $.post("./apis/books_api.php", { "action": "list", "email": "<?php echo $user_info['email']; ?>" }, (result) => {
            updateBooks(JSON.parse(result));
        });
    });

    function updateBooks(books) {
        const parent = $("#books-parent");

        if (books.length == 0) {
            parent.html("<p>This user has no books for sale!</p>");
            return;
        }

        for (let i = 0; i < books.length; i++) {
            parent.append('<article class="col-6 col-md-2 position-relative">\
                    <img class="w-100" src="./imgs/archive.png" alt="">\
                    <a class="black-link w-100 text-center stretched-link" href="./book.php?id=' + books[i]["id"] + '">' + books[i]["title"] + '</a>\
                </article>');
        }
    }
</script>

<!-- USER INFO & MANAGMENT -->
<section>
    <header class="row col mb-3 text-center">
        <h2><?php echo $user_info["first_name"] . " " . $user_info["last_name"]; ?></h2>
    </header>
    <section>
        <header class="row mb-3">
            <p id="error-internal" class="col-12 alert alert-danger login-alert" role="alert">Something went wrong! Please try again.</p>
        </header>
        <section class="row justify-content-center">
            <input class="col-md btn button-primary mb-3 mx-1" type="button" value="Queued orders"></input>
            <input class="col-md btn btn-danger mb-3 mx-1" type="button" value="Delete account" data-bs-toggle="modal" data-bs-target="#delete-modal"></input>
        </section>
    </section>
</section>

<!-- USER BOOKS -->
<section class="row mb-3" id="books-parent"></section>