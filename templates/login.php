<?php 

include_once("./users_consts.php"); 
$require_login = isset($template_args[PAGE_REQUIRE_LOGIN]) && $template_args[PAGE_REQUIRE_LOGIN] && !is_user_logged();

?>
<script type="text/javascript">
    function onLogin() {
        $("#error-ko").hide();
        $("#error-internal").hide();
        $("#error-missing-fields").hide();
        $("#error-needs_login").hide();

        const email = $("input#email").val();
        const password = $("input#psw").val();
        if (email == "" || password == "")
        {
            $("#error-missing-fields").slideDown();
            return;
        }

        const button = $("#login-button");
        button.attr("disabled");
        tryLogin(email, password, (result) => {
            if (result == Result.Ok){
                window.location.href = "<?php echo isset($_GET["from"]) ? $_GET["from"] : "./index.php"; ?>";
            } else {
                button.removeAttr("disabled");
                switch(result) {
                    case Result.Ko:
                        $("#error-ko").slideDown();
                        break;
                    case Result.Internal:
                        $("#error-internal").slideDown();
                        break;
                    case Result.MissingFields:
                        $("#error-missing-fields").slideDown();
                        break;
                }
            }
        });
    }
</script>

<aside>
    <p id="error-require-login" class="row col-12 col-md-6 offset-md-3 alert alert-danger <?php if (!$require_login) { echo "login-alert"; } ?>" role="alert">This page requires you to login or register.</p>
    <p id="error-missing-fields" class="row col-12 col-md-6 offset-md-3 alert alert-danger login-alert" role="alert">Some of the required fields are invalid. Please check them and try again.</p>
    <p id="error-internal" class="row col-12 col-md-6 offset-md-3 alert alert-danger login-alert" role="alert">Something went wrong! Please try again.</p>
    <p id="error-ko" class="row col-12 col-md-6 offset-md-3 alert alert-danger login-alert" role="alert">Error! Please make sure you're registered and that your credentials are valid.</p>
</aside>

<section>
    <header class="row col text-center">
        <h2>Login</h2>
    </header>
    <form class="col-10 offset-1 col-md-6 offset-md-3 row text-center" method="post">
        <label class="col-12 form-label mb-3 p-0" for="email">Email:
            <input class="form-control" type="email" id="email" name="email" placeholder="Insert email" required/>
        </label>
        <label class="col-12 form-label mb-3 p-0" for="psw">Password:
            <input class="form-control" type="password" id="psw" name="psw" placeholder="Insert password" required/>
        </label>
        <input class="col-12 btn button-primary mb-3" type="button" id="login-button" onclick="onLogin();" value="Login"/>
        <a class="col-12" href="./register.php?from=<?php echo isset($_GET['from']) ? $_GET['from'] : "./"; ?>">Not registered? Signup here</a>
    </form>
</section>