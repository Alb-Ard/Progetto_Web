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

    for ($i = 0; $i < 10; $i++) { ?>
        <li class="col position-relative home-category">
            <a class="black-link stretched-link" href="./category?id=<?php echo $i; ?>">Category <?php echo $i + 1; ?></a>
        </li>
    <?php }

    ?>
</ul>