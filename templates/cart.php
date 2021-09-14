<script type="text/javascript">
    $(document).ready(() => {
        updateEmptyLabel();
    });
    
    function onRemoveBook(id) {
        const data = {
            "action": "remove",
            "book_id": id,
        };
        $.post("./apis/cart_api.php", data, (result) => {
            if (result) {
                $("#" + id).remove();
                updateEmptyLabel();
            }
        });
    }

    function updateEmptyLabel() {
        if ($("#book-list").children().length == 0) {
            $("#empty-label").show();
        } else {
            $("#empty-label").hide();
        }
    }
</script>
<header class="row">
    <h2 class="col text-center">Your cart</h2>
</header>
<section class="row m-0 p-0 justify-content-center">
    <h3 id="empty-label">There are no books in your cart!</h3> 
    <ul id="book-list">
        <?php

        $carted_books = $db_conn->get_carted_books()->get_carted_books((get_client_info()["email"]));

        foreach($carted_books as $book) { ?>
            <li class="col-5 col-md-1 row position-relative category-list-book p-1" id="<?php echo $book->id; ?>">
                <header>
                    <h3 class="col-12">
                        <a class="black-link" href="./book.php?id=<?php echo $book->id; ?>"><?php echo $book->title; ?></a>
                    </h3>
                    <img class="col-12" src="./imgs/archive.png" alt="<?php echo $book->title; ?> image">
                </header>
                <p class="col-12"><?php echo $book->price ?>€</p>
                <button class="col-12 btn button-secondary" type="button" onclick="onRemoveBook(<?php echo $book->id; ?>);" aria-label="remove book form cart">Remove from cart</button>
            </li>
        <?php }
        
        ?>
    </ul>
</section>
<aside>
</aside>