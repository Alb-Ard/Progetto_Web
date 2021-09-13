<header class="row">
    <h2 class="col text-center"><?php echo $template_args[PAGE_TITLE]; ?></h2>
</header>
<section>
    <?php
        
    if (count($books) == 0) { ?>
        <h3 class="row col-12 text-center">No books found!</h3>
    <?php } else { ?>
        <ul class="row m-0 p-0 justify-content-center"> <?php
            foreach($books as $book) { ?>
                <li class="col-5 col-md-2 row position-relative category-list-book">
                    <header>
                        <h3 class="col-12 text-truncate">
                            <a class="stretched-link black-link" href="./book.php?id=<?php echo $book->id; ?>"><?php echo $book->title; ?></a>
                        </h3>
                        <img class="col-12" src="./imgs/archive.png" alt="<?php echo $book->title; ?> image">
                    </header>
                    <p class="col-12"><?php echo $book->price ?>€</p>
                </li>
            <?php } ?>
        </ul>
    <?php } ?>
</section>