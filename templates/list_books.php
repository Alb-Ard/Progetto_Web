<header class="row">
    <h2 class="col text-center"><?php echo $template_args[PAGE_TITLE]; ?></h2>
</header>
<?php

$books_count = count($books);

if (isset($show_order) && $show_order && $books_count > 0) { ?>
    <div class="row align-items-center m-3">
        <p class="col-12 col-md-auto text-center">Order by:</p>
        <ul class="col-12 col-md-auto text-center">
            <li class="d-inline position-relative btn button-primary"><a class="stretched-link black-link" href="<?php echo get_order_href(0); ?>">Title</a></li>
            <li class="d-inline position-relative btn button-primary"><a class="stretched-link black-link" href="<?php echo get_order_href(1); ?>">Author</a></li>
            <li class="d-inline position-relative btn button-primary"><a class="stretched-link black-link" href="<?php echo get_order_href(2); ?>">Price</a></li>
        </ul>
    </div>
<?php }

?>
<section class="row">
    <?php
        
    if (count($books) == 0) { ?>
        <h3 class="col text-center">No books found!</h3>
    <?php } else { ?>
        <ul class="m-0 p-0 justify-content-center" id="book-list"> <?php
            foreach($books as $book) { ?>
                <li class="col-5 col-md-2 col-xxl-1 row position-relative category-list-book">
                    <header>
                        <h3 class="col-12 text-truncate">
                            <a class="stretched-link black-link" href="./book.php?id=<?php echo $book->id; ?>"><?php echo $book->title; ?></a>
                        </h3>
                        <img class="col-12" src="<?php echo $book->image ?>" alt="<?php echo $book->title; ?> image">
                    </header>
                    <p class="col-12"><?php echo $book->price ?>€</p>
                </li>
            <?php } ?>
        </ul>
    <?php } ?>
</section>
<?php

if (isset($show_pages) && $show_pages && $books_count > 0) { 
    $previous_page = max(min($current_page - 1, $pages_count - 1), 0);
    $next_page = max(min($current_page + 1, $pages_count - 1), 0);
    ?>
    <nav class="row" aria-label="Navigate book pages">
        <ul class="col pagination justify-content-center">
            <li class="page-item"><a class="page-link" href="<?php echo get_page_href($previous_page); ?>">Previous</a></li>
            <?php for($i = $previous_page; $i <= $next_page; $i++) { ?>
                <li class="page-item<?php if ($i == $current_page) echo " active"; ?>">
                    <a class="page-link" href="<?php echo get_page_href($i); ?>"><?php echo $i + 1; ?></a>
                </li>
            <?php } ?>
            <li class="page-item"><a class="page-link" href="<?php echo get_page_href($next_page); ?>">Next</a></li>
        </ul>
    </nav>
<?php }

?>