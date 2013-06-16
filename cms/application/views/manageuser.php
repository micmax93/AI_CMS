<?php

echo '
<!DOCTYPE html>
<html>
<head>
 <title>Manage Users...</title>
 <script src="' . URL::base() . 'application/views/js/jquery-1.9.1.js"></script>
 <script src="' . URL::base() . 'application/views/js/jquery-ui.js"></script>
 <script>
    var baseUrl = "' . URL::base() . '";
 </script>
 <script src="' . URL::base() . 'application/views/js/users.js"></script>
</head>
<body>
<h1>Manage Users</h1>
        ';
if (isset($message)) echo $message;
else {
    echo '
    <table>
        <thead>
        <tr>
        <th>username</th>
        <th>email</th>
        <th>full name</th>
        <th>password</th>
        <th colspan="2">options</th>
        </tr>
        </thead>
        <tbody>
';
    foreach ($users as $user) {
        echo '<tr>
        <td><input value="' . stripcslashes($user->username) . '" id="userEditName_' . $user->id . '"></td>
        <td><input value="' . stripcslashes($user->email) . '" id="userEditEmail_' . $user->id . '"></td>
        <td><input value="' . stripcslashes($user->full_name) . '" id="userEditFullName_' . $user->id . '"></td>
        <td><input value="" id="userEditPassword_' . $user->id . '" type="password"></td>
        <td><button onclick="saveUser(' . $user->id . ');"><img src="' . URL::base() . 'application/views/img/tick.png" /></button></td>
        <td><button onclick="deleteUser(' . $user->id . ');"><img src="' . URL::base() . 'application/views/img/remove-small.png" /></button></td>
        </tr>
        ';
    }
    echo '
    </tbody>
        </table>
        Add New: <input id="userEdit_New">
    <button onclick="newUser(\'userEdit_New\');"><img src="' . URL::base() . 'application/views/img/plus-small.png" /></button>
        ';

}

echo '

</body>
</html> ';

?>