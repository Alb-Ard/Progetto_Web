<header class="row">
    <h2 class="col text-center"><?php echo $template_args[PAGE_TITLE]; ?></h2>
</header>
<section>
    <ul class="row m-0 p-0 justify-content-center">
        <?php
        
        $addresses = $db_conn->get_addresses()->get_addresses((get_client_info()["email"]));
        
        if (count($addresses) == 0) { ?>
            <h3>There are no addresses!</h3>
        <?php } else {
            foreach($addresses as $address) { ?>
                <li class="col-5 col-md-1 row position-relative category-list-book">
                    <header>
                        <h3 class="col-12">
                            <a class="stretched-link black-link" href="../order.php?addr=<?php echo $address->address; ?>&card=<?php echo $_GET["card"]; ?>" ><?php echo $address->address; ?></a>
                        </h3>
                        <img class="col-12" src="./imgs/archive.png" alt="<?php echo $address->address; ?> image">
                    </header>
                    <p class="col-12"><?php echo $address->address ?>€</p>
                </li>
            <?php }
        }
        
        ?>
    </ul>
</section>