<!DOCTYPE html>
<html>
    <head>
        <title>Bookshelf - <?php echo $template_args[PAGE_TITLE]; ?></title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <script src="https://code.jquery.com/jquery-3.4.1.min.js"
            integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" 
            crossorigin="anonymous"></script>
        <script src="./scripts/js-sha256.js"></script>
        <link rel="stylesheet" 
            href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
            integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" 
            crossorigin="anonymous" />
        <link rel="stylesheet" type="text/css" href="./css/default.css" />
    </head>
    <body class="container-fluid p-0 m-0">
        <header class="mt-3 mb-3 top-bar">
            <div class="row align-items-center">
                <h1 class="col">Bookshelf</h1>
                <!-- TODO: add search bar -->
                <img class="col-1 m-1" src="./imgs/user_icon.png" alt=""></img>
            </div>
            <nav class="row col-12">
                <ul class="nav nav-pills">
                    <li class="nav-item"><a href="./index.php">Categories</a></li>
                </ul>
            </nav>
        </header>

        <main class="m-3">
            <?php include_once($template_args[PAGE_BODY]); ?>
        </main>

        <script type="text/javascript" 
            src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js" 
            integrity="sha384-q2kxQ16AaE6UbzuKqyBE9/u/KzioAlnx2maXQHiDX9d4/zp8Ok3f+M7DPm+Ib6IU" 
            crossorigin="anonymous"></script>
        <script type="text/javascript" 
            src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.min.js" 
            integrity="sha384-pQQkAEnwaBkjpqZ8RU1fF1AKtTcHJwFl3pblpTlHXybJjHpMYo79HY3hIi4NKxyj" 
            crossorigin="anonymous"></script>
    </body>
</html>