<?php

echo '
<!DOCTYPE html>
<html>
<head>
 <title>' . $title . '</title>
 <link rel="stylesheet" type="text/css" href="' . URL::base() . 'application/views/css/style.css">
 <script src="' . URL::base() . 'application/views/js/jquery-1.9.1.js"></script>
 <script src="' . URL::base() . 'application/views/js/jquery-ui.js"></script>
 <script src="' . URL::base() . 'application/views/js/login.js"></script>
</head>
<body>

<script type="text/javascript">
  var base_url = "' . URL::base() . '";
  var baseUrl = "' . URL::base() . '";
    $(function(){

    $("#passwordInput").keyup(function (e) {
        if (e.which == 13) {
            authorizeUser();
        }
     });

    });
</script>
<table class="user_table"><tr>

<td class="table_login">
    <p>Logowanie</p>
      <div id="Login_viewport">
        login: <input type="text" id="userInput"><br>
        hasło: <input type="password" id="passwordInput"><br>
        <button onclick="javascript:authorizeUser();">Zaloguj</button>
      </div>
</td>

<td class="table_seperator"></td>

<td class="table_register">
    <p>Rejestracja</p>
      <div id="Register_viewport">
        login: <input type="text" id="newUserInput"> <br>
        hasło: <input type="password" id="newPasswordInput"> <br>
        <button onclick="javascript:registerUser();">Rejestruj</button>
      </div>
</td>
</tr></table>
</body>

</html> ';

?>