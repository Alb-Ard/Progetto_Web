<script type="text/javascript">
    $(document).ready(() => {
        updateBooks();
    });
    
    function onRemoveBook(id) {
        const data = {
            "action": "remove",
            "book_id": id,
        };
        $.post("./apis/cart_api.php", data, (result) => {
            if (result) {
                updateTotal();
                $("#book-" + id).fadeOut("fast", () => { $("#book-" + id).remove(); });
            }
        });
    }

    function updateBooks() {
        $.post("./apis/cart_api.php", { "action": "get" }, (result) => {
            if (result) {
                const cartedBooks = JSON.parse(result);
                const booksList = $("#book-list");

                booksList.children().remove();
                if (cartedBooks && cartedBooks.length > 0) {
                    for (let idx in cartedBooks) {
                        const book = cartedBooks[idx];
                        const bookItem = $(`<li id="book-${book["id"]}" class="card shadow m-3">
                                                <header class="card-header">
                                                    <h3 class="card-title d-inline-block">
                                                        <a class="black-link" href="./book.php?id=${book["id"]}">${book["title"]}</a>
                                                    </h3>
                                                    <button class="p-3 btn btn-close float-end" type="button" onclick="onRemoveBook(${book["id"]});" aria-label="remove book form cart"></button>
                                                </header>
                                                <img class="p-3 book-cover" src="${book["image"]}" alt="${book["title"]} cover image"/>
                                                <p class="p-3">${book["price"]}€</p>
                                            </li>`);
                        booksList.append(bookItem);
                    }
                }
            }
        });
        updateTotal();
    }

    function updateTotal() {
        $.post("./apis/cart_api.php", { "action": "get_total_price" }, (result) => {
            if (result) {
                const total = JSON.parse(result);
                $("#total-price").html("Total: " + total.toFixed(2) + "€");
                if (total <= 0) {
                    $("#book-list").before(`<p id="empty-label">There are no books in your cart.</p>`); 
                    $("#advance-button").addClass("disabled");
                } else {
                    $("#empty-label").remove();
                    $("#advance-button").removeClass("disabled");
                }
            }
        });
    }
</script>
<section id="book-list-section" class="m-3 p-0">
    <header class="row">
        <h2 class="col text-center">Your cart</h2>
    </header>
    <ul id="book-list" class="m-0 p-0 d-flex flex-wrap justify-content-center">
    </ul>
</section>
<aside id="advance-section" class="row m-0 p-0 justify-content-center">
    <p id="total-price" class="text-center"></p>
    <a id="advance-button" class="btn button-primary w-25" href="./payment_choose.php">Proceed to order</a>
</aside>
