/**
 * Created with JetBrains PhpStorm.
 * User: micmax93
 */


function userAuthorized(v) {
    if (v["status"] == "ok")
        window.location = "authorized";
}

function authorizeUser() {
    var user = document.getElementById('userInput').value;
    var pass = document.getElementById('passwordInput').value;
    $.ajax({
        type: "POST",
        url: "authorize",
        data: {username: user, password: pass},
        success: userAuthorized
    });
}