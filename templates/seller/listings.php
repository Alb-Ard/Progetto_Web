<script type="text/javascript">
    let id;

    $(document).ready(() => {
        $("#delete-modal").get()[0].addEventListener("show.bs.modal", (event) => {
            id = event.relatedTarget.getAttribute("data-bs-id");
        });
    });

    function onRemove() {
        const data = {
            "action": "remove",
            "email": "<?php echo $user_info['email']; ?>",
            "id": id,
        };
        $("#error-internal").hide();
        $.post("./apis/books_api.php", data, (result) => {
            if (JSON.parse(result)) {
                $("#" + id).remove();
            } else {
                $("#error-internal").slideDown();
            }
        });
    }
</script>

<aside>
    <p class="alert alert-danger login-alert" id="error-internal" role="alert">Something went wrong! Please try again.</p>
</aside>

<section>
    <header>
        <h2 class="text-center">Your active listings:</h2>
    </header>
    <ul class="d-flex flex-wrap justify-content-center m-3 p-0">
        <?php
        
        $listings = $db_conn->get_books()->get_user_books($user_info["email"]);
        $active_count = 0;

        foreach($listings as $listing) { 
            if ($listing->available != BOOK_SOLD) {
                $active_count++;
                ?>
                <li class="card shadow m-3" id="<?php echo $listing->id; ?>">
                    <header class="card-header">
                        <h3 class="card-title"><?php echo $listing->title; ?></h3>
                    </header>
                    <p class="card-text mx-3 mt-3">Price: <?php echo $listing->price; ?>€</p>
                    <ul class="btn-group mx-3 mb-3 p-0">
                        <li class="btn button-secondary">
                            <a class="w-100 black-link stretched-link" href="./seller_edit.php?id=<?php echo $listing->id; ?>">Edit listing</a>
                        </li>
                        <li class="btn btn-danger">
                            <a class="w-100 black-link" href="#" data-bs-toggle="modal" data-bs-target="#delete-modal" data-bs-id="<?php echo $listing->id; ?>">Delete listing</a>
                        </li>
                    </ul>
                </li>
            <?php }
        }

        ?>
    </ul>
    <?php 
    
    if ($active_count < 1) { ?>
        <p class="text-center h5">You don't have any active listing</p>
    <?php }
    
    ?>
</section>