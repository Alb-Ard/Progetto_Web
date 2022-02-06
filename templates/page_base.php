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
        <nav class="top-bar shadow">
            <!-- MAIN HEADER -->
            <section class="m-0 p-3">
                <div class="row align-items-center justify-content-around mb-3">
                    <header class="col order-0">
                        <h1>
                            <a class="top-bar-logo black-link" href="./">Bookshelf</a>
                        </h1>
                    </header>
                    <div class="col-12 col-md-3 col-lg-2 col-xxl-1 row">
                        <?php include_once("./templates/top_bar_user.php"); ?>
                    </div>
                </div>
                <div class="row top-bar-search">
                    <form class="h-100 col-12 col-md-10 offset-md-1" action="./find_books.php" method="get">
                        <label class="visually-hidden" for="key">Search query</label>
                        <label class="visually-hidden" for="search_confirm">Execute Search</label>
                        <div class="input-group h-100">
                            <input class="col form-control h-100" type="text" id="key" name="key"/>
                            <input class="col-2 col-md-1 btn button-secondary top-bar-search-icon" type="image" id="search_confirm" src="./imgs/search.png" alt="search book"/>
                        </div>
                    </form>
                </div>
            </section>
        </nav>

        <main class="m-3">
            <?php include_once($template_args[PAGE_BODY]); ?>
        </main>
        
        <div class="modal fade" id="completed-modal" tabindex="-1">
            <div class="modal-dialog">
                <section class="modal-content">
                    <header class="modal-header">
                        <h2 class="modal-title">Info</h2>
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