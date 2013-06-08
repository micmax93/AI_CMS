<?php

echo '
<!DOCTYPE html>
<html>
<head>
 <title>' . $title . '</title>
 <link href="http://fonts.googleapis.com/css?family=Share+Tech+Mono" rel="stylesheet" type="text/css">
 <link rel="stylesheet" type="text/css" href="' . URL::base() . 'application/views/css/style.css">
 <script src="' . URL::base() . 'application/views/js/jquery-1.9.1.js"></script>
 <script src="' . URL::base() . 'application/views/js/jquery-ui.js"></script>
 <script src="' . URL::base() . 'application/views/js/windows.js"></script>
 <script src="' . URL::base() . 'application/views/js/functions.js"></script>
 <script src="' . URL::base() . 'application/views/js/setup.js"></script>
</head>
<body onload="setup()" >
<script type="text/javascript">
$(function(){

$("#passwordInput").keyup(function (e) {
    if (e.which == 13) {
        authorizeUser();
    }
 });

});

</script>
<div onmouseover="javascript:$(this).draggable()" class="imageWindow ui-draggable" id="Login_window" onmousedown="firstPlanWindow(\'Login_window\');" photoid="-1" style="top: 20px;z-index: 1;">
<div class="titleBarActive" onmousedown="firstPlanWindow(\'Login_window\');" id="Login_title">
<p>Logowanie</p></div>
  <div class="viewport" id="Login_viewport" style="z-index: 0;">
  <p style="background:white;padding:3px;">
    login: <input type="text" id="userInput"><br>
    hasło: <input type="password" id="passwordInput"><br>
    <button onclick="javascript:authorizeUser();">Zaloguj</button>
  </p>
  </div>
    </div>
<div id="container"  style="opacity: 0.1;">
    <div id="sideBar">
        <h1>diagShare </h1>
        <div id="roomList">

        </div>
        <div id="messageBox">
        </div>
        <div id="chatControl">
            <input type="text" id="chatInput"><button onclick="">Wyślij</button>
        </div>

    </div>
    <div id="main">

</div>
</body>

</html> ';

?>