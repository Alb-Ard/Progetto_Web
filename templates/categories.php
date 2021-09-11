<ul class="row p-0 m-0 justify-content-around">
    <?php

    for ($i = 0; $i < 10; $i++) { ?>
        <li class="col-md-3 m-3 p-3 text-center home-category">
            <a class="text-wrap home-category-text" href="./category?id=<?php echo $i; ?>">Category <?php echo $i + 1; ?></a>
        </li>
    <?php }

    ?>
</ul>