<script type="text/javascript">
    let id;

    $(document).ready(() => {
        let exampleModal = $("#delete-modal").get()[0].addEventListener("show.bs.modal", (event) => {
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
            }
            else
                $("#error-internal").slideDown();
        });
    }
</script>
<section class="row">
    <p class="alert alert-danger login-alert" id="error-internal" role="alert">Something went wrong! Please try again.</p>
</section>
<ul class="row m-0 p-0 justify-content-around">
    <?php
    
    $listings = $db_conn->get_books()->get_user_books($user_info["email"]);

    foreach($listings as $listing) { ?>
        <li class="col-12 col-md-5 position-relative seller-listing-book mx-0 my-1" id="<?php echo $listing->id; ?>">
            <article class="row justify-content-around m-0 p-1">
                <header class="col-12 col-md-8">
                    <h3 class="d-inline-block text-truncate w-100"><?php echo $listing->title; ?></h3>
                </header>
                <p class="col-12 col-md-4 text-end">Price: <?php echo $listing->price; ?>€</p>
                <ul class="col-12 row text-center">
                    <li class="col-12 col-md-6 d-inline position-relative mb-1">
                        <a class="w-100 btn button-secondary stretched-link" href="./seller_edit.php?id=<?php echo $listing->id; ?>">Edit listing</a>
                    </li>
                    <li class="col-12 col-md-6 d-inline position-relative mb-1">
                        <a class="w-100 btn btn-danger stretched-link" href="#" data-bs-toggle="modal" data-bs-target="#delete-modal" data-bs-id="<?php echo $listing->id; ?>">Delete listing</a>
                    </li>
                </ul>
            </article>
        </li>
    <?php }

    ?>
</ul>