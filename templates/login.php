<?php 

include_once("./users_consts.php"); 

?>
<script type="text/javascript">
    function onLogin() {
        $("#error-ko").hide();
        $("#error-internal").hide();
        $("#error-missing-fields").hide();

        const email = $("input#<?php echo USER_EMAIL; ?>").val();
        const password = $("input#<?php echo USER_PSW; ?>").val();
        if (email == "" || password == "")
        {
            $("#error-missing-fields").slideDown();
            return;
        }

        const button = $("#login-button");
        button.attr("disabled");
        button.val("");
        $("#login-button-spinner").show();
        tryLogin(email, password, (result) => {
            if (result == Result.Ok)
                window.location.href = "<?php echo $_GET["from"]; ?>";
            else {
                button.val("Login");
                button.removeAttr("disabled");
                $("#login-button-spinner").hide();
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

<section>
    <header class="row col text-center">
        <h2>Login</h2>
    </header>
    <section>
        <p id="error-missing-fields" class="row col-12 col-md-6 offset-md-3 alert alert-danger login-alert" role="alert">Some of the required fields are invalid. Please check them and try again.</p>
        <p id="error-internal" class="row col-12 col-md-6 offset-md-3 alert alert-danger login-alert" role="alert">Something went wrong! Please try again.</p>
        <p id="error-ko" class="row col-12 col-md-6 offset-md-3 alert alert-danger login-alert" role="alert">Error! Please make sure you're registered and that your credentials are valid.</p>
        <form class="col-12 col-md-6 offset-md-3 row container-md text-center" method="post">
            <label class="col-12 form-label mb-3 p-0">Email:
                <input class="form-control" type="email" id="<?php echo USER_EMAIL; ?>" name="<?php echo USER_EMAIL; ?>" placeholder="Insert email" required="true"/>
            </label>
            <label class="col-12 form-label mb-3 p-0">Password:
                <input class="form-control" type="password" id="<?php echo USER_PSW; ?>" name="<?php echo USER_PSW; ?>" placeholder="Insert password" required="true"/>
            </label>
            <input class="col-12 btn button-primary mb-3" type="button" id="login-button" onclick="onLogin();" value="Login">
                <div class="spinner-border spinner-border-sm d-none" id="login-button-spinner" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
            </input>
            <a class="col-12" href="./register.php?from=<?php echo isset($_GET['from']) ? $_GET['from'] : "./"; ?>">Not registered? Signup here</a>
        </form>
    </section>
</section>