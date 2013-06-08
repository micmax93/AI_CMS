/**
 * Created with JetBrains PhpStorm.
 * User: phisikus
 * Date: 27.04.13
 * Time: 09:44
 * To change this template use File | Settings | File Templates.
 */

function deleteUser(id) {

    $.ajax({
        dataType: "json",
        url: baseUrl + "index.php/user/delete/" + id,
        roomId: id,
        success: function (v) {
            if (v["status"] == "ok")
                location.reload();
        }
    });

}

function newUser(field) {
    var text = document.getElementById(field).value;
    var data = {};
    data["username"] = text;
    data["full_name"] = text;
    data["email"] = text + "@" + "ChangeMe.com";
    data["password"] = text;
    jQuery.post(baseUrl + "index.php/user/set/0", data, function (v) {
        location.reload();
    });

}

function saveUser(id) {
    var username = document.getElementById('userEditName_' + id).value;
    var email = document.getElementById('userEditEmail_' + id).value;
    var full_name = document.getElementById('userEditFullName_' + id).value;
    var password = document.getElementById('userEditPassword_' + id).value;
    var data = {};
    data["username"] = username;
    data["email"] = email;
    data["full_name"] = full_name;
    if (password.length > 0)
        data["password"] = password;
    jQuery.post(baseUrl + "index.php/user/set/" + id, data, function () {
        alert('Saved.');
    });
}