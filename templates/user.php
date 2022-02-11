<script type="text/javascript">
    $(document).ready(() => {
        $.post("./apis/books_api.php", { "action": "list", "email": "<?php echo $user_info['email']; ?>" }, (result) => {
            if (result) {
                const parent = $("#books-list-section");
                const booksList = $("#books-list");
                const soldList = $("#sold-list");
                const books = JSON.parse(result);

                if (books.length == 0) {
                    booksList.before($("<p>You don't have any books for sale.</p>"));
                    return;
                }

                for (let i = 0; i < books.length; i++) {
                    const isSold = books[i]["available"] == "SOLD";
                    if (isSold) {
                        const bookItem = $(`<li class="card card-disabled shadow m-3">
                                            <header class="card-header">
                                                <p class="text-center m-0" href="./book.php?id=${books[i]["id"]}">${books[i]["title"]}</p>
                                            </header>
                                            <img class="p-3 book-cover" src="${books[i]["image"]}" alt="${books[i]["title"]} cover image"/>
                                        </li>`);
                        soldList.append(bookItem);
                    } else {
                        const bookItem = $(`<li class="card shadow m-3">
                                            <header class="card-header">
                                                <a class="d-block black-link text-center stretched-link" href="./book.php?id=${books[i]["id"]}">${books[i]["title"]}</a>
                                            </header>
                                            <img class="p-3 book-cover" src="${books[i]["image"]}" alt="${books[i]["title"]} cover image"/>
                                        </li>`);
                        booksList.prepend(bookItem);
                    }
                }
            }
        });
    });
</script>

<aside>    
    <p class="alert alert-danger login-alert" id="error-internal" role="alert">Something went wrong! Please try again.</p>
</aside>

<!-- USER INFO & MANAGMENT -->
<section class="border rounded m-3 p-3 shadow row">
    <header class="col text-center">
        <h2><?php echo $user_info["first_name"] . " " . $user_info["last_name"]; ?></h2>
    </header>
    <button class="btn btn-danger col-12 col-md-auto float-end" type="button" data-bs-toggle="modal" data-bs-target="#delete-modal">Delete account</button>
</section>

<!-- USER BOOKS -->
<section id="books-list-section" class="m-3">
    <header>
        <h2 class="text-center">Books for sale:</h2>
    </header>
    <ul id="books-list" class="d-flex flex-wrap justify-content-center m-0 p-0">
    </ul>
</section>
<section id="books-sold-section" class="m-3">
    <header>
        <h2 class="text-center">Books sold:</h2>
    </header>
    <ul id="sold-list" class="d-flex flex-wrap justify-content-center m-0 p-0">
    </ul>
</section>