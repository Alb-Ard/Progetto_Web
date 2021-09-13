<header class="row">
    <h2 class="col text-center"><?php echo $template_args[PAGE_TITLE]; ?></h2>
</header>
<section>
    <ul class="row m-0 p-0 justify-content-center">
        <?php
        
        $carted_books = $db_conn->get_books()->get_carted_books((get_client_info()["email"]));
        
        if (count($carted_books) == 0) { ?>
            <h3>There are no books in your cart!</h3>
        <?php } else {
            foreach($carted_books as $book) { ?>
                <li class="col-5 col-md-1 row position-relative category-list-book">
                    <header>
                        <h3 class="col-12">
                            <a class="stretched-link black-link" href="./book.php?id=<?php echo $book->id; ?>"><?php echo $book->title; ?></a>
                        </h3>
                        <img class="col-12" src="./imgs/archive.png" alt="<?php echo $book->title; ?> image">
                    </header>
                    <p class="col-12"><?php echo $book->price ?>€</p>
                </li>
            <?php }
        }
        
        ?>
    </ul>
</section>