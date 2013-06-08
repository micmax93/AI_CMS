/**
 * Created with JetBrains PhpStorm.
 * User: phisikus
 * Date: 27.04.13
 * Time: 09:44
 * To change this template use File | Settings | File Templates.
 */

function deleteRoom(id) {

    $.ajax({
        dataType: "json",
        url: baseUrl + "index.php/room/delete/" + id,
        roomId: id,
        success: function (v) {
            if (v["status"] == "ok")
                $('#roomList_' + this.roomId).remove();
            window.close();
        }
    });

}

function newRoom(field) {
    var text = document.getElementById(field).value;
    var data = {};
    data["name"] = text;
    jQuery.post(baseUrl + "index.php/room/set/0", data,function(v) {
       window.close();
    });

}

function saveRoom(id) {
    var text = document.getElementById('roomEdit_' + id).value;
    var data = {};
    data["name"] = text;
    jQuery.post(baseUrl + "index.php/room/set/" + id, data, function () {
        window.close();
    });
}