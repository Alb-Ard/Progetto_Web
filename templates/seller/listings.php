<ul class="row m-0 p-0 justify-content-around">
    <?php
    
    $listings = $db_conn->get_books()->get_user_books($user_info["email"]);

    foreach($listings as $listing) { ?>
        <li class="col-12 col-md-5 position-relative seller-listing-book mx-0 my-1">
            <article class="row justify-content-around m-0 p-1">
                <header class="col-12 col-md-8">
                    <h3 class="d-inline-block text-truncate w-100"><?php echo $listing->title; ?></h3>
                </header>
                <p class="col-12 col-md-4 text-end">Price: <?php echo $listing->price; ?>€</p>
                <a class="col-12 btn button-secondary stretched-link" href="./seller_edit.php?id=<?php echo $listing->id; ?>">Edit listing</a>
            </article>
        </li>
    <?php }

    ?>
</ul>