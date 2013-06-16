/**
 * Created with JetBrains PhpStorm.
 * User: micmax93
 */

function setup() {
    register();
    //setupWebSocket();
}


var webSocket = null;
function setupWebSocket() {
    webSocket = new WebSocket("ws://" + window.location.host + ":12345/echo");
    webSocket.onopen = function (evt) {
        login();
    };
    webSocket.onclose = function (evt) {
        //onClose(evt)
    };
    webSocket.onmessage = function (evt) {
        onMessage(evt);
    };
    webSocket.onerror = function (evt) {
        //onError(evt);
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
    update();
    if (int_update == null) {
        int_update = window.setInterval(update, 5000);
    }
}
function stopAsyncUpdate() {
    if (int_update != null) {
        window.clearTimeout(int_update);
        int_update = null;
    }
}