<header class="row">
    <h2 class="col text-center"><?php echo $template_args[PAGE_TITLE]; ?></h2>
</header>
<?php

$books_count = count($books);

if (isset($show_order) && $show_order && $books_count > 0) { ?>
    <aside class="row m-3 border rounded p-3">
        <p class="col-12 col-md-auto my-auto text-center">Order by:</p>
        <ul class="col-12 col-md-auto btn-group m-0">
            <li class="col d-inline position-relative btn button-primary">
                <a class="stretched-link black-link" href="<?php echo get_order_href(0); ?>">Title</a>
            </li>
            <li class="col d-inline position-relative btn button-primary">
                <a class="stretched-link black-link" href="<?php echo get_order_href(1); ?>">Author</a>
            </li>
            <li class="col d-inline position-relative btn button-primary">
                <a class="stretched-link black-link" href="<?php echo get_order_href(2); ?>">Price</a>
            </li>
        </ul>
    </aside>
<?php }

?>
<section class="row">
    <?php
    
		$available_books = [];
		foreach($books as $book) {
			if ($book->available != BOOK_SOLD) {
				array_push($available_books, $book);
			}
		}
		
    if (count($available_books) == 0) { ?>
        <header class="col text-center">
            <h3>No books found!</h3>
        </header>
    <?php } else { ?>
        <ul class="p-3 d-flex flex-wrap justify-content-center" id="book-list"> <?php
            foreach($available_books as $book) { 
                if ($book->available != BOOK_SOLD) { ?>
                    <li class="card shadow m-3 category-book-card">
                        <header class="card-header">
                            <h3 class="text-truncate card-title">
                                <a class="stretched-link black-link" href="./book.php?id=<?php echo $book->id; ?>"><?php echo $book->title; ?></a>
                            </h3>
                        </header>
                        <div class="mx-auto">
                            <img class="book-cover m-2" src="<?php echo $book->image ?>" alt="<?php echo $book->title; ?> image">
                        </div>
                        <p class="text-center"><strong><?php echo $book->price ?>???</strong></p>
                    </li>
                <?php } 
            }
            ?>
        </ul>
    <?php } ?>
</section>
<?php

if (isset($show_pages) && $show_pages && $books_count > 0) { 
    $previous_page = max(min($current_page - 1, $pages_count - 1), 0);
    $next_page = max(min($current_page + 1, $pages_count - 1), 0);
    ?>
    <nav class="row" aria-label="Navigate book pages">
        <ul class="col pagination d-flex justify-content-center">
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