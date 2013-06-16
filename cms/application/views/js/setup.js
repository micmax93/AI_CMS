/**
 * Created with JetBrains PhpStorm.
 * User: phisikus
 * Date: 15.03.13
 * Time: 22:03
 * To change this template use File | Settings | File Templates.
 */

function setup() {
    setupWebSocket();
}

function no_ws_setup() {
    jQuery.post("get_code", function (data) {
        myName=data['uname'];
        myHash=data['hash'];
    });
}

var int_update=null;
function start_update() {
    if(int_update==null) {
        int_update=window.setInterval(update,5000);
    }
}
function stop_update() {
    if(int_update!=null) {
        window.clearTimeout(int_update);
        int_update=null;
    }
}