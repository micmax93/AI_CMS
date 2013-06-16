<?php

echo '
<!DOCTYPE html>
<html>
<head>
 <title>' . $title . '</title>
 <script src="' . URL::base() . 'application/views/js/jquery-1.9.1.js"></script>
 <script src="' . URL::base() . 'application/views/js/jquery-ui.js"></script>
 <script src="' . URL::base() . 'application/views/js/login.js"></script>
</head>
<body>

<script type="text/javascript">
    $(function(){

    $("#passwordInput").keyup(function (e) {
        if (e.which == 13) {
            authorizeUser();
        }
     });

    });
</script>

<p>Logowanie</p>
  <div id="Login_viewport">
    login: <input type="text" id="userInput"><br>
    hasło: <input type="password" id="passwordInput"><br>
    <button onclick="javascript:authorizeUser();">Zaloguj</button>
  </div>
</body>

</html> ';

?>