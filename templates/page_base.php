<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Bookshelf - <?php echo $template_args[PAGE_TITLE]; ?></title>
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
        <script type="text/javascript">
            function onLogout() {
                logout((result) => { window.location.href = "./"; });
            }
        </script>
    </head>
    <body class="container-fluid p-0 m-0">
        <nav class="top-bar">
            <!-- MAIN HEADER -->
            <section class="row m-0 p-3 justify-content-around">
                <header class="col-3 order-0">
                    <h1>
                        <a class="top-bar-logo black-link" href="./">Bookshelf</a>
                    </h1>
                </header>
                <div class="col-12 order-2 col-md-6 order-md-1">
                    <form class="row" action="./find_books.php" method="get">
                        <label class="col visually-hidden" for="key">Search query</label>
                        <label class="col visually-hidden" for="search_confirm">Execute Search</label>
                        <div class="input-group">
                            <input class="col form-control" type="text" id="key" name="key"/>
                            <input class="col-2 col-md-1 btn button-secondary img-fluid" type="image" id="search_confirm" src="./imgs/search.png" alt="search book"/>
                        </div>
                    </form>
                </div>
                <div class="col-3 order-1 col-md-1 order-md-2">
                    <a class="black-link" href="#" onclick="$('#user-menu').slideToggle();">
                        <img class="img-fluid" src="./imgs/user_icon.png" alt="user icon"/>
                        <?php if (is_user_logged()) { ?>
                            <p class="w-100 text-center"><?php echo get_client_info()["first_name"]; ?></p>
                        <?php } ?>
                    </a>
                </div>
            </section>
            <?php
                if (!isset($template_args[PAGE_HIDE_NAVBAR]) || !$template_args[PAGE_HIDE_NAVBAR]) { ?>
                <!-- NAVBAR -->
                <ul class="row align-items-center justify-content-right mx-0 mb-3 py-3 px-0 top-bar-user-menu" id="user-menu"><?php
                    if (!is_user_logged()) { ?>
                        <li class="col top-bar-user-menu-item"><a class="black-link text-nowrap" href="./login.php?from=<?php echo $_SERVER["REQUEST_URI"]; ?>">Login</a></li>
                        <li class="col top-bar-user-menu-item"><a class="black-link text-nowrap" href="./register.php?from=<?php echo $_SERVER["REQUEST_URI"]; ?>">Register</a></li>
                    <?php } else { ?> 
                        <li class="col top-bar-user-menu-item"><a class="black-link text-nowrap" href="./cart.php">Cart</a></li>
                        <li class="col top-bar-user-menu-item"><a class="black-link text-nowrap" href="./orders.php">Orders</a></li>
                        <li class="col top-bar-user-menu-item"><a class="black-link text-nowrap" href="./user.php">Profile</a></li>
                        <li class="col top-bar-user-menu-item"><a class="black-link text-nowrap" href="./seller_dashboard.php">Go to seller dashboard</a></li>
                        <li class="col top-bar-user-menu-item"><a class="black-link text-nowrap" href="#" onclick="onLogout();">Logout</a></li>
                    <?php }
                ?></ul>
                <?php }
            ?>
        </nav>

        <main class="m-3">
            <?php include_once($template_args[PAGE_BODY]); ?>
        </main>
        
        <?php
        
        if (isset($template_args[PAGE_FOOTER]))
            include_once($template_args[PAGE_FOOTER]);
        
        ?>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" 
            integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" 
            crossorigin="anonymous"></script>
    </body>
</html>