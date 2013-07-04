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

function registerUser() {
    var user = document.getElementById('newUserInput').value;
    var pass = document.getElementById('newPasswordInput').value;
    var data = {};
    data["username"] = user;
    data["full_name"] = user;
    data["email"] = user + "@" + "ChangeMe.com";
    data["password"] = pass;
    jQuery.post(baseUrl + "index.php/user/set/0", data, function (v) {
        $('#userInput').val(user);
        $('#newUserInput').val('');
        $('#passwordInput').val(pass);
        $('#newPasswordInput').val('');
        authorizeUser();
    });

}