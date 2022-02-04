<script type="text/javascript">
    $(document).ready(() => {
        <?php if (isset($_GET["completed"])) { ?>
            new bootstrap.Modal(document.getElementById('completed-modal')).show();
        <?php } ?>
    });
</script>
<section>
    <header class="row text-center">
        <h2 class="col-12">Browse categories</h2>
    </header>
    <ul class="p-0 m-0 d-flex flex-wrap justify-content-center">
        <?php

        $categories = $db_conn->get_categories()->get_categories();

        foreach ($categories as $category) { ?>
            <li class="card shadow home-category flex-fill">
                <a class="black-link stretched-link" href='./category.php?id=<?php echo $category["id"]; ?>&name=<?php echo $category["name"]; ?>&order=0&page=0'><?php echo $category["name"]; ?></a>
            </li>
        <?php }

        ?>
    </ul>
</section>