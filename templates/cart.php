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
                updateBooks();
            }
        });
    }

    function updateBooks() {
        $.post("./apis/cart_api.php", { "action": "get" }, (result) => {
            if (result) {
                const cartedBooks = JSON.parse(result);
                const booksList = $("#book-list");
                let advanceSection = $("#advance-section");

                booksList.children().remove();
                if (!cartedBooks || cartedBooks.length < 1) {
                    if (advanceSection.length > 0) {
                        advanceSection.remove();
                    }
                    $("#book-list").before(`<h3 id="empty-label">There are no books in your cart!</h3>`); 
                } else {
                    let totalSum = 0.0;
                    for (let idx in cartedBooks) {
                        const book = cartedBooks[idx];
                        totalSum += parseFloat(book["price"]);
                        const bookItem = $(`<li class="col-12 col-sm-6 col-md-3 row position-relative category-list-book p-1" id="${book["id"]}">
                                                <header>
                                                    <h3 class="col-12">
                                                        <a class="black-link" href="./book.php?id=${book["id"]}">${book["title"]}</a>
                                                    </h3>
                                                    <img class="col-12" src="${book["image"]}" alt="${book["title"]} cover image">
                                                </header>
                                                <p class="col-12">${book["price"]}€</p>
                                                <button class="col-12 btn button-secondary" type="button" onclick="onRemoveBook(${book["id"]});" aria-label="remove book form cart">Remove from cart</button>
                                            </li>`);
                        booksList.append(bookItem);
                    }
                    if (advanceSection.length < 1) {
                        advanceSection = $(`<section id="advance-section" class="row m-0 p-0 justify-content-center">
                                                <p id="total-price" class="text-center"></p>
                                                <a class="btn button-primary w-25" href="./order.php">Proceed to order</a>
                                            </section>`);
                        $("#book-list-section").after(advanceSection);
                    }
                    $("#total-price").html("Total: " + totalSum.toFixed(2) + "€");
                    $("#empty-label").remove();
                }
            }
        })
    }
</script>
<header class="row">
    <h2 class="col text-center">Your cart</h2>
</header>
<section id="book-list-section" class="row m-0 p-0 justify-content-center">
    <ul id="book-list">
    </ul>
</section>