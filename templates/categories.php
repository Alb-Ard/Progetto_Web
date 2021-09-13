<script type="text/javascript">
    $(document).ready(() => {
        <?php if (isset($_GET["completed"])) { ?>
            new bootstrap.Modal(document.getElementById('completed-modal')).show();
        <?php } ?>
    });
</script>
<header class="row text-center">
    <h2 class="col-12">Browse categories</h2>
</header>
<ul class="row p-0 m-0 justify-content-around">
    <?php

    $categories = $db_conn->get_categories()->get_categories();

    foreach ($categories as $category) { ?>
        <li class="col position-relative home-category">
            <a class="black-link stretched-link" href='./category.php?id=<?php echo $category["id"]; ?>&name=<?php echo$category["name"]; ?>'><?php echo $category["name"]; ?></a>
        </li>
    <?php }

    ?>
</ul>