/**
 * Created with JetBrains PhpStorm.
 * User: phisikus
 * Date: 15.03.13
 * Time: 22:03
 * To change this template use File | Settings | File Templates.
 */

function setup() {
    setupWebSocket();
    loadRooms();

    // Otwieranie zarządzania userami
    document.onkeydown = keydown;
    function keydown(evt) {
        if (!evt) evt = event;
        if (evt.shiftKey && evt.altKey && evt.keyCode == 76) { // ctrl+alt+l
            popupWindow("Manage users", "index.php/user/manage");
        }
    }

}
