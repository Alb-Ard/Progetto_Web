<?php 

if (!is_user_logged()) {
    die("Error loggin in, please go back to the home page and retry.");
}

$user_info = get_client_info();

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Dashboard - <?php echo $template_args[PAGE_TITLE]; ?></title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <script src="https://code.jquery.com/jquery-3.4.1.min.js"
            integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" 
            crossorigin="anonymous"></script>
        <script src="./scripts/js-sha256.js"></script>
        <script src="./scripts/login.js"></script>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" 
            rel="stylesheet" 
            integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" 
            crossorigin="anonymous">
        <link rel="stylesheet" type="text/css" href="./css/default.css" />
    </head>
    <body class="container-fluid p-0 m-0">
        <nav class="top-bar shadow">
            <!-- MAIN HEADER -->
            <section class="row m-0 p-3 align-items-center">
                <header class="col-12">
                    <h1 class="top-bar-logo">Seller's dashboard</h1>
                    <p class="h2"><?php echo $user_info["email"]?></p>
                    <p class="h3"><?php echo $user_info["first_name"] . " " . $user_info["last_name"]; ?></p>
                </header>
            </section>

            <!-- NAVBAR -->
            <ul class="row align-items-center justify-content-right mb-0 p-0 pt-3 top-bar-seller-menu">
                <li class="col-12 col-md-3 top-bar-user-menu-item"><a class="black-link stretched-link" href="./">Back to home</a></li>
                <li class="col-12 col-md-3 top-bar-user-menu-item"><a class="black-link stretched-link" href="./seller_dashboard.php">Current listings</a></li>
                <li class="col-12 col-md-3 top-bar-user-menu-item"><a class="black-link stretched-link" href="./seller_orders.php">Current orders</a></li>
                <li class="col-12 col-md-3 top-bar-user-menu-item"><a class="black-link stretched-link" href="./seller_add_book.php">List new book</a></li>
            </ul>
        </nav>

        <main class="m-3">
            <?php include_once($template_args[PAGE_BODY]); ?>
        </main>

        <div class="modal fade" id="completed-modal" tabindex="-1">
            <div class="modal-dialog">
                <section class="modal-content">
                    <header class="modal-header">
                        <p class="modal-title">Info</p>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </header>
                    <div class="modal-body">
                        <p>Operation completed sucesfully.</p>
                    </div>
                    <footer class="modal-footer">
                        <button type="button" class="btn button-primary w-100" data-bs-dismiss="modal">Ok</button>
                    </footer>
                </section>
            </div>
        </div>

        <?php
        
        if (isset($template_args[PAGE_FOOTER]))
            include_once($template_args[PAGE_FOOTER]);
        
        ?>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" 
            integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" 
            crossorigin="anonymous"></script>
    </body>
</html>