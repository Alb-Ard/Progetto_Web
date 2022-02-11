<?php 

if (!is_user_logged()) {
    $template_args[PAGE_REQUIRE_LOGIN] = true;
    include_once("./login.php");
    return;
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
        <script src="./scripts/utils.js"></script>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" 
            rel="stylesheet" 
            integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" 
            crossorigin="anonymous">
        <link rel="stylesheet" type="text/css" href="./css/default.css" />
    </head>
    <body class="container-fluid">
        <main class="d-flex flex-column">
            <!-- MAIN HEADER -->
            <header class="top-bar shadow">
                <section class="m-0 p-0 mb-3">
                    <h1 class="top-bar-logo m-0 p-0 text-center">Seller's dashboard</h1>
                    <p class="h4 mx-2 mx-sm-3"><?php echo $user_info["first_name"] . " " . $user_info["last_name"]; ?></p>
                    <p class="h5 mx-2 mx-sm-3"><?php echo $user_info["email"]?></p>
                </section>
                <!-- NAVBAR -->
                <?php
                
                $unseen_notifications_count = $db_conn->get_notifications()->get_user_unseen_count(get_client_info()["email"]);
                
                ?>
                <nav class="navbar navbar-expand-sm">
                    <div class="container-fluid">
                        <button class="navbar-toggler w-100" type="button" data-bs-toggle="collapse" data-bs-target="#seller-top-bar" aria-controls="seller-top-bar" aria-expanded="false" aria-label="Toggle menu">
                            Menu
                            <?php if ($unseen_notifications_count > 0) { ?>
                                <span class="rounded-pill top-bar-notification-badge-unseen px-3 mx-2"><?php echo $unseen_notifications_count; ?></span>
                            <?php } ?>
                        </button>
                        <ul id="seller-top-bar" class="navbar-nav collapse navbar-collapse top-bar-seller-menu">
                            <li class="top-bar-seller-menu-item"><a class="black-link stretched-link" href="./">Back to home</a></li>
                            <li class="top-bar-seller-menu-item"><a class="black-link stretched-link" href="./seller_dashboard.php">Current listings</a></li>
                            <li class="top-bar-seller-menu-item"><a class="black-link stretched-link" href="./seller_orders.php">Current orders</a></li>
                            <li class="top-bar-seller-menu-item"><a class="black-link stretched-link" href="./seller_add_book.php">List new book</a></li>
                            <li class="top-bar-seller-menu-item"><a class="black-link stretched-link" href="./seller_notifications.php">Notifications (<?php echo $unseen_notifications_count; ?>)</a>
                        </ul>
                    </div>
                </nav>
            </header>    

            <div class="m-3">
                <?php
                
                include_once($template_args[PAGE_BODY]);

                ?>
            </div>

            <?php include_once("./templates/page_footer.php"); ?>
            <?php include_once("./templates/cookies_alert.php"); ?>
        </main>

        <aside class="modal fade" id="completed-modal" tabindex="-1">
            <div class="modal-dialog">
                <section class="modal-content">
                    <header class="modal-header">
                        <h2 class="modal-title h3">Info</h2>
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
        </aside>

        <?php
        
        if (isset($template_args[PAGE_FOOTER]))
            include_once($template_args[PAGE_FOOTER]);
        
        ?>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" 
            integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" 
            crossorigin="anonymous"></script>
    </body>
</html>