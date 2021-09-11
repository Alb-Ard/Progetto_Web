<?php include("./users_consts.php"); ?>
<!DOCTYPE html>
<html>
    <head>
        <title>Login/Register development test</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
        <script src="./scripts/js-sha256.js"></script>
        <script src="./scripts/login.js"></script>
        <script type="text/javascript">
            $(document).ready(() => { <?php                
                if (is_user_logged())
                    echo 'showLoggedParagraph("' . $_SESSION[SESSION_EMAIL] . '");';
                else
                    echo '$("section#session_section").hide();';
            ?> });

            function onLoginRequested() {
                const email = $("input#login_<?php echo USER_EMAIL; ?>").val();
                const password = $("input#login_<?php echo USER_PSW; ?>").val();
                tryLogin(email, password, (result) => {
                    if (result == Result.Ok)
                        showLoggedParagraph(email);
                    else
                        alert(result);
                });
            }

            function onRegisterRequested() {
                const email = $("input#register_<?php echo USER_EMAIL; ?>").val();
                const password = $("input#register_<?php echo USER_PSW; ?>").val();
                const firstName = $("input#register_<?php echo USER_FIRST_NAME; ?>").val();
                const lastName = $("input#register_<?php echo USER_LAST_NAME; ?>").val();
                tryRegister(email, password, firstName, lastName, (result) => {
                    if (result == Result.Ok)
                        showLoggedParagraph(email);
                    else
                        alert(result);
                });
            }

            function onLogoutRequested() {
                logout((result) => {
                    if (result == Result.Ok)
                        $("section#session_section").hide();
                    else
                        alert(result);
                });
            }

            function showLoggedParagraph(email) {
                $("section#session_section").show();
                $("section#session_section > p > span#session_email").html(email);
            }
        </script>
    </head>
    <body>
        <header><h1>Test Login/Register</h1></header>
        <main>
            <section id="session_section">
                <p>You're logged in as <span id="session_email">NULL</span></p>
                <form>
                    <input type="button" onclick="onLogoutRequested();" value="Logout" />
                </form>
            </section>
            <section>
                <header>
                    <h2>Login</h2>
                </header>
                <form>
                    <label>Email:
                        <input type="email" id="login_<?php echo USER_EMAIL; ?>" name="<?php echo USER_EMAIL; ?>" placeholder="Insert email" />
                    </label>
                    <label>Password:
                        <input type="password" id="login_<?php echo USER_PSW; ?>" name="<?php echo USER_PSW; ?>" placeholder="Insert password" />
                    </label>
                    <input type="button" onclick="onLoginRequested();" value="Login" />
                </form>
            </section>
            <section>
                <header>
                    <h2>Register</h2>
                </header>
                <form>
                    <label>Email:
                        <input type="email" id="register_<?php echo USER_EMAIL; ?>" name="<?php echo USER_EMAIL; ?>" placeholder="Insert email" />
                    </label>
                    <label>Password:
                        <input type="password" id="register_<?php echo USER_PSW; ?>" name="<?php echo USER_PSW; ?>" placeholder="Insert password" />
                    </label>
                    <label>First name:
                        <input type="text" id="register_<?php echo USER_FIRST_NAME; ?>" name="<?php echo USER_FIRST_NAME; ?>" placeholder="Insert first name" />
                    </label>
                    <label>Last name:
                        <input type="text" id="register_<?php echo USER_LAST_NAME; ?>" name="<?php echo USER_LAST_NAME; ?>" placeholder="Insert last name" />
                    </label>
                    <input type="button" onclick="onRegisterRequested();" value="Register" />
                </form>
            </section>
        </main>
    </body>
</html>