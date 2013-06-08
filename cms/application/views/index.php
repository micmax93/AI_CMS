<?php

echo '
<!DOCTYPE html>
<html>
<head>
 <title>' . $title . '</title>
 <link href="http://fonts.googleapis.com/css?family=Share+Tech+Mono" rel="stylesheet" type="text/css">
 <link rel="stylesheet" type="text/css" href="' . URL::base() . 'application/views/css/style.css">
 <link rel="stylesheet" type="text/css" href="' . URL::base() . 'application/views/css/jquery-ui.css">
 <script type="text/javascript">
  var base_url = "' . URL::base() . '";
  var baseUrl = "' . URL::base() . '";
  var wsUri = "ws://echo.websocket.org/";
  var webSocket;
  var sessionWindow;
  var startTime = new Date();
 </script>
 <script src="' . URL::base() . 'application/views/js/jquery-1.9.1.js"></script>
 <script src="' . URL::base() . 'application/views/js/jquery-ui.js"></script>
 <script src="' . URL::base() . 'application/views/js/jquery.mousewheel.js"></script>
 <script src="' . URL::base() . 'application/views/js/caman.full.min.js"></script>
 <script src="' . URL::base() . 'application/views/js/jquery.blockUI.js"></script>
 <script src="' . URL::base() . 'application/views/js/communication.js"></script>
 <script src="' . URL::base() . 'application/views/js/functions.js"></script>
 <script src="' . URL::base() . 'application/views/js/tags.js"></script>
 <script src="' . URL::base() . 'application/views/js/canvas.js"></script>
 <script src="' . URL::base() . 'application/views/js/windows.js"></script>
 <script src="' . URL::base() . 'application/views/js/sessions.js"></script>
 <script src="' . URL::base() . 'application/views/js/setup.js"></script>
</head>
<body onload="setup()">
    <h1>Lista użytkowników:</h1>
</body>

</html> ';

?>