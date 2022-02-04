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
        const parent = $("#books-list-section");

        if (books.length == 0) {
            parent.html("<p>You don't have any books for sale.</p>");
            return;
        }

        const booksList = $(`<ul id="books-list" class="d-flex flex-wrap m-0 p-0"></ul>`);
        parent.append(booksList);
        for (let i = 0; i < books.length; i++) {
            bookItem = $(`<li class="card shadow m-3">
                                <header class="card-header">
                                    <a class="black-link text-center stretched-link" href="./book.php?id=${books[i]["id"]}">${books[i]["title"]}</a>
                                </header>
                                <img class="p-3 book-cover" src="${books[i]["image"]}" alt="${books[i]["title"]} cover image"/>
                            </li>`);
            booksList.append(bookItem);
        }
    }
</script>

<section>    
    <div class="row col-12 mb-3">
        <p class="alert alert-danger login-alert" id="error-internal" role="alert">Something went wrong! Please try again.</p>
    </div>
</section>

<!-- USER INFO & MANAGMENT -->
<section class="border rounded m-3 p-3 shadow row">
    <header class="col mb-3 text-center">
        <h2><?php echo $user_info["first_name"] . " " . $user_info["last_name"]; ?></h2>
    </header>
    <button class="btn btn-danger col-12 col-md-auto float-end" type="button" data-bs-toggle="modal" data-bs-target="#delete-modal">Delete account</button>
</section>

<!-- USER BOOKS -->
<section id="books-list-section" class="m-3">
    <header>
        <h2>Books for sale:</h2>
    </header>
</section>