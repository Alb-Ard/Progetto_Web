<?php 

include_once("./users_consts.php"); 

?>
<script type="text/javascript">
    function onRegister() {
        $("#error-ko").hide();
        $("#error-internal").hide();
        $("#error-missing-fields").hide();
        $("#error-passwords-mismatch").hide();

        const email = $("input#<?php echo USER_EMAIL; ?>").val();
        const password = $("input#<?php echo USER_PSW; ?>").val();
        const passwordRepeated = $("input#<?php echo USER_PSW; ?>-repeat").val();
        const firstName = $("input#<?php echo USER_FIRST_NAME; ?>").val();
        const lastName = $("input#<?php echo USER_LAST_NAME; ?>").val();

        if (email == "" || password == "" || firstName == "") {
            $("#error-missing-fields").slideDown();
            return;
        }

        if(password != passwordRepeated) {
            $("#error-passwords-mismatch").slideDown();
            return;
        }

        const button = $("#register-button");
        button.attr("disabled");
        button.val("");
        $("#register-button-spinner").show();

        tryRegister(email, password, firstName, lastName, (result) => {
            if (result == Result.Ok)
                window.location.href = "./";
            else
            {
                button.val("Register");
                button.removeAttr("disabled");
                $("#register-button-spinner").hide();
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
        <h2>Register</h2>
    </header>
    <section>
        <p id="error-missing-fields" class="row col-12 alert alert-danger login-alert" role="alert">Some of the required fields are invalid. Please check them and try again.</p>
        <p id="error-passwords-mismatch" class="row col-12 alert alert-danger login-alert" role="alert">The passwords do not match. Please check them and try again.</p>
        <p id="error-internal" class="row col-12 alert alert-danger login-alert" role="alert">Something went wrong! Please try again.</p>
        <p id="error-ko" class="row col-12 alert alert-danger login-alert" role="alert">Error! Please make sure you're registered and that your credentials are valid.</p>

        <form class="container-md text-center" method="post">
            <label class="row col justify-content-center form-label mb-3">Email:
                <input class="form-control" type="email" id="<?php echo USER_EMAIL; ?>" name="<?php echo USER_EMAIL; ?>" placeholder="Insert email" required="true"/>
            </label>
            <label class="row col justify-content-center form-label mb-3">Password:
                <input class="form-control" type="password" id="<?php echo USER_PSW; ?>" name="<?php echo USER_PSW; ?>" placeholder="Insert password" required="true"/>
            </label>
            <label class="row col justify-content-center form-label mb-3">Repeat password:
                <input class="form-control" type="password" id="<?php echo USER_PSW; ?>-repeat" placeholder="Repeat password" required="true"/>
            </label>
            <label class="row col justify-content-center form-label mb-3">First name:
                <input class="form-control" type="text" id="<?php echo USER_FIRST_NAME; ?>" name="<?php echo USER_FIRST_NAME; ?>" placeholder="Insert first name" required="true"/>
            </label>
            <label class="row col justify-content-center form-label mb-3">Last name (optional):
                <input class="form-control" type="text" id="<?php echo USER_LAST_NAME; ?>" name="<?php echo USER_LAST_NAME; ?>" placeholder="Insert last name"/>
            </label>
            <input class="row col-md-3 justify-content-center btn button-primary" type="button" id="register-button" onclick="onRegister();" value="Register">
                <div class="spinner-border spinner-border-sm d-none" id="register-button-spinner" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
            </input>
        </form>
    </section>
</section>