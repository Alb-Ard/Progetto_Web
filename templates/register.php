<script type="text/javascript">
    function checkEmail(email) {
        return email.match(/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/);
    }

    function showErrorFromResult(result) {
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

    function onRegister() {
        $("#error-ko").hide();
        $("#error-internal").hide();
        $("#error-missing-fields").hide();
        $("#error-email-invalid").hide();
        $("#error-passwords-mismatch").hide();

        const email = $("input#email").val();
        const password = $("input#psw").val();
        const passwordRepeated = $("input#psw-repeat").val();
        const firstName = $("input#first_name").val();
        const lastName = $("input#last_name").val();

        if (email == "" || password == "" || firstName == "") {
            $("#error-missing-fields").slideDown();
            return;
        }

        if (!checkEmail(email)) {
            $("#error-email-invalid").slideDown();
            return;
        }

        if (password != passwordRepeated) {
            $("#error-passwords-mismatch").slideDown();
            return;
        }

        const button = $("#register-button");
        button.attr("disabled");

        tryRegister(email, password, firstName, lastName, (result) => {
            if (result != Result.Ok) {
                button.removeAttr("disabled");
                showErrorFromResult(result);
                return;
            }
            tryLogin(email, password, (result) => {
                if (result != Result.Ok) {
                    button.removeAttr("disabled");
                    showErrorFromResult(result);
                    return;
                }
                window.location.href = "<?php echo $_GET["from"]; ?>";
            });
        });
    }
</script>

<aside>
    <p id="error-missing-fields" class="alert alert-danger login-alert" role="alert">Some of the required fields are invalid. Please check them and try again.</p>
    <p id="error-email-invalid" class="alert alert-danger login-alert" role="alert">The email format is not correct. Please check it and try again.</p>
    <p id="error-passwords-mismatch" class="alert alert-danger login-alert" role="alert">The passwords do not match. Please check them and try again.</p>
    <p id="error-internal" class="alert alert-danger login-alert" role="alert">Something went wrong! Please try again.</p>
    <p id="error-ko" class="alert alert-danger login-alert" role="alert">Error! Please make sure you're not already registered.</p>
</aside>

<section>
    <header class="row col text-center">
        <h2>Register</h2>
    </header>
    <form class="col-10 offset-1 col-md-6 offset-md-3 row text-center" method="post">
        <label class="col-12 form-label mb-3 p-0" for="email">Email:
            <input class="form-control" type="email" id="email" name="email" placeholder="Insert email" required/>
        </label>
        <label class="col-12 form-label mb-3 p-0" for="psw">Password:
            <input class="form-control" type="password" id="psw" name="psw" placeholder="Insert password" required/>
        </label>
        <label class="col-12 form-label mb-3 p-0" for="psw-repeat">Repeat password:
            <input class="form-control" type="password" id="psw-repeat" placeholder="Repeat password" required/>
        </label>
        <label class="col-12 form-label mb-3 p-0" for="first_name">First name:
            <input class="form-control" type="text" id="first_name" name="first_name" placeholder="Insert first name" required/>
        </label>
        <label class="col-12 form-label mb-3 p-0" for="last_name">Last name (optional):
            <input class="form-control" type="text" id="last_name" name="last_name" placeholder="Insert last name"/>
        </label>
        <input class="col-12 btn button-primary mb-3" type="button" id="register-button" onclick="onRegister();" value="Register"/>
        <a class="col-12" href="./login.php?from=<?php echo isset($_GET['from']) ? $_GET['from'] : "./"; ?>">Already registered? Login here</a>
    </form>
</section>