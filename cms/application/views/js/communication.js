/**
 * Created with JetBrains PhpStorm.
 * User: micmax93
 */

function register(addr) {
    jQuery.post("index.php/cms/get_code", function (data) {
        myName = data['uname'];
        myHash = data['hash'];
    });
}

function login() {
    var str = '{"cmd":"login","args":{"user":"' + myName + '","hash":"' + myHash + '"}}';
    webSocket.send(str);
}


function update() {
    var args = {};
    args['uname'] = myName;
    args['hash'] = myHash;
    jQuery.post("index.php/cms/update", args, function (data) {
        active = data['args']['active'];
        delayed = data['args']['delayed'];
        repaintList();
    });
}

function setupRefresh(timeout) {
    window.setInterval(function () {
        refreshList();
        //repaintList();
    }, timeout);
}


function onMessage(evt) {
    data = jQuery.parseJSON(evt.data);
    if (data['cmd'] == 'list') {
        active = data['args']['active'];
        delayed = data['args']['delayed'];
        repaintList();
        setupRefresh(1000);
    }
    else if (data['cmd'] == 'update') {
        userUpdate(data['args']['user'], data['args']['action'], data['args']['time']);
        //repaintList();
    }
}
