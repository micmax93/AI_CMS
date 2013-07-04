/**
 * Created with JetBrains PhpStorm.
 * User: micmax93
 */


function update() {
    var args = {};
    args['uname'] = myName;
    args['hash'] = myHash;
    args['group'] = myGroup;
    jQuery.post("index.php/cms/update", args, function (data) {
        active = data['args']['active'];
        delayed = data['args']['delayed'];
        repaintList();
    });
}