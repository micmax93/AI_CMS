/**
 * Created with JetBrains PhpStorm.
 * User: micmax93
 */

var myName;
var myHash;
var myGroup=0;

function register(addr) {
    var args = {'group':myGroup};
    jQuery.post("index.php/cms/get_code", args, function (data) {
        myName = data['uname'];
        myHash = data['hash'];

        setupWebSocket();
    });
}

function setup() {
    register();
    //setupWebSocket();
}

var webSocket = null;
function setupWebSocket() {
    stopAsyncUpdate();
    if(webSocket!=null) {return;}
    webSocket = new WebSocket("ws://" + window.location.host + ":12345/echo");
    webSocket.onopen = function (evt) {
        login();
        $('#modeSwitch').val('Switch mode to AJAX')
            //value='Switch mode to AJAX';
    };
    webSocket.onclose = function (evt) {
        //onClose(evt)
    };
    webSocket.onmessage = function (evt) {
        onMessage(evt);
    };
    webSocket.onerror = function (evt) {
        //onError(evt);
        setupAsyncUpdate();
    };
}

function closeWebSocket() {
    if (webSocket != null) {
        webSocket.close();
        webSocket = null;
    }
}


var int_update = null;
function setupAsyncUpdate() {
    closeWebSocket();
    if(int_update==null) {
        $('#modeSwitch').val('Switch mode to Websocket');
            //.setAttribute('value','Switch mode to Websocket');
        update();
        if (int_update == null) {
            int_update = window.setInterval(update, 5000);
        }
    }
}
function stopAsyncUpdate() {
    if (int_update != null) {
        window.clearTimeout(int_update);
        int_update = null;
    }
}

function switchMode() {
    if(webSocket!=null) {setupAsyncUpdate();}
    else {setupWebSocket();}
}