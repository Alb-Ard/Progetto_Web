const Result = {
    Ok: "ok",
    Ko: "ko",
    MissingFields: "missing_fields",
    Internal: "internal"
}

const LoginApiUrl = "./login_api.php";
const RegisterApiUrl = "./register_api.php";

function tryLogin(email, psw, onComplete) {
    const hashedPsw = sha256(psw);
    const data = { "email": email, "psw": hashedPsw, };
    $.post(LoginApiUrl, data, onComplete)
}

function tryRegister(email, psw, firstName, lastName, onComplete) {
    const hashedPsw = sha256(psw);
    const data = { "email": email, "psw": hashedPsw, "first_name": firstName, "last_name": lastName };
    $.post(RegisterApiUrl, data, onComplete)
}

function logout(onComplete) {
    $.post(LoginApiUrl, "", onComplete);
}