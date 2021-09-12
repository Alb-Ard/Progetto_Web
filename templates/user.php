<?php

$user_info = get_client_info();

if (count($user_info) == 0)
    die ("Error getting user info");

?>

<script type="text/javascript">
    const booksAmount = 6;
    let currentBookIndex = 0;
    let books = null;

    $(document).ready(() => {
        $.post("./apis/books_api.php", { "action": "list", "email": "<?php echo $user_info['email']; ?>" }, (result) => {
            books = JSON.parse(result);
            updateBooks();
        });
    });

    function onShowBooks() {
        currentBookIndex += booksAmount;
        updateBooks();
    }

    function updateBooks() {
        const count = books.length;
        const parent = $("#books-parent");

        if (count == 0) {
            parent.html("<p>No books for sale!</p>");
            return;
        }

        currentBookIndex = Math.min(currentBookIndex, count);
        const to = Math.min(currentBookIndex + booksAmount, count);

        $("#load-books").removeAttr("disabled");
        for (let i = currentBookIndex; i < to; i++) {
            parent.append('<article class="col-sm-' + (12 / booksAmount) + ' position-relative">\
                <img class="w-100" src="./imgs/archive.png" alt="">\
                <a class="black-link w-100 text-center stretched-link" href="./book.php?id=' + books[i]["id"] + '">' + books[i]["title"] + '</a>\
                </article>');
        }
    }
</script>

<!-- USER GENERIC INFO -->
<header class="row col mb-3 text-center">
    <h2><?php echo $user_info["first_name"] . " " . $user_info["last_name"]; ?></h2>
</header>

<!-- USER BOOKS -->
<section>
    <section class="row mb-3" id="books-parent"></section>
    <footer class="row mb-3 justify-content-around">
        <input class="col-5 btn button-primary" type="button" value="Add new book" onclick="window.location.href = './add_book.php';"></input>
        <input class="col-5 btn button-primary" id="load-books" type="button" value="Load more books" onclick="onShowBooks();" disabled></input>
    </footer>
</section>

<!-- USER MANAGMENT -->
<section class="row justify-content-center text-center">
    <p id="error-internal" class="col-11 alert alert-danger login-alert" role="alert">Something went wrong! Please try again.</p>
    <input class="btn col-11 button-primary" type="button" value="Delete account" data-bs-toggle="modal" data-bs-target="#delete-modal"></input>
</section>