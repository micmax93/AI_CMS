<?php
/**
 * Created by: Marcin Biernacki <phisikus@platinum.linux.pl>
 * User: phisikus
 * Date: 15.03.13
 */
echo '
<div id="chatContent">
<script type="text/javascript">
$(function(){

$("#chatInput").keyup(function (e) {
    if (e.which == 13) {
        sendChatMessage();
    }
 });

});

</script>

<p id="chatRoomId">Czat</p>
<hr>
<table id="chatList">
</table>

</div>


';


