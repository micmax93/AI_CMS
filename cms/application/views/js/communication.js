/**
 * Created with JetBrains PhpStorm.
 * User: phisikus
 * Date: 26.03.13
 * Time: 13:52
 * To change this template use File | Settings | File Templates.
 */

/**
 * roomsReceived
 * Funkcja ta tworzy strukturę html i umieszcza ją w panelu bocznym
 * po odebraniu struktury danych pokojów i pacjentów.
 *
 * @param data
 */
function roomsReceived(data) {

    var list = '';
    var lid = 0;
    var addRoom;
    document.getElementById('roomList').innerHTML = "<div id=\"rooms\"></div>";
    $.each(data, function (room, patients) {
        var room_id = room.split("/");
        room_id = room_id[room_id.length - 1];
        room = room.slice(0, room.lastIndexOf("/"));

        list += '<h3>' + room + '</h3>';
        list += '<div class="rooms"  style="height:100%;">';
        $.each(patients, function (patient, photos) {
            var patient_id = patient.split("/");
            patient_id = patient_id[patient_id.length - 1];
            patient = patient.slice(0, patient.lastIndexOf("/"));

            var listOfWindows = Array();
            for (var key in photos) {
                listOfWindows.push(key + "_window");
            }
            list += '<div class="patient" id="patientListItem_' + patient_id + '" listOfWindows="' + listOfWindows.toString() + '">';
            list += '   <h3><img src="application/views/img/folder-horizontal.png"><a href="javascript:menuRoll(\'patientLink' + lid + '\');">' + patient + '</a></h3>' +
                '<a href="javascript:removePatient(' + patient_id + ');"><img src="application/views/img/remove-small.png" class="patientRemove"></a>' +
                '<img src="application/views/img/edit.png" class="patientEdit" onclick="popupWindow(\'Edit patient...\',\'index.php/patient/edit/' + patient_id + '?room=' + room_id + '\');"></a>';
            list += '   <div id="patientLink' + lid + '" style="display:none;">';
            list += '       <ul>';

            for (var key in photos) {
                list += '       <li class="photoListItem" id="photoListItem_' + photos[key] + '">';
                list += '           <a href="javascript:showHideOrLoad(\'' + photos[key] + '\',\'' + key + '\');">' + key + '</a><a href="javascript:removePhoto(\'' + photos[key] + '\',\'' + key + '\');"><img src="application/views/img/remove-small.png" class="photoRemove"></a>';
                list += '       </li>';

            }
            list += '<li class="photoListItemAdd"><p onclick="popupWindow(\'Image Upload\',\'index.php/image/load/' + patient_id + '\');">Add...</p></li>';


            list += '       </ul>';
            list += '   </div>';
            list += '</div>';
            lid++;

        });
        list += '<div class="patient">';
        list += '<h3 onclick="popupWindow(\'Edit patient...\',\'index.php/patient/edit/0?room=' + room_id + '\');"><img src="application/views/img/plus.png">Add patient...</h3>';
        list += '</div>';
        list += '</div>';

    });

    addRoom = '<div class="manageRooms"><p onclick="popupWindow(\'Manage Rooms\',\'index.php/room/load/0\');"><img src="application/views/img/edit.png"> Edit Rooms</p></div>';

    // Rozwijanie listy oddziałów
    document.getElementById('rooms').innerHTML = list;
    $(function () {
        $("#rooms").append(addRoom);
        $('#rooms > h3').each(function () {
            $(this).next().toggle();
            $(this).click(function () {
                $(this).next().toggle();
            });
        }).css('cursor', 'pointer');

    });


}

/**
 * imageReceived()
 * Poniższa funkcja dla otrzymanych danych powoduje utworzenie okna.
 * @param v
 */
function imageReceived(v) {
    createWindow('main', v["title"], v["width"], v["height"], v["rowSize"], v["numberOfRows"], v["id"], v["images"]);
    applyView(v["title"]);
}


/**
 * addTags()
 * Zleca pobranie tagów dla zdjęcia.
 * @param photoId
 * @param canvasId
 */
function addTags(photoId, canvasId) {
    var data;
    $.ajax({
        dataType: "json",
        url: "index.php/tag/getAll/" + photoId,
        data: data,
        success: function (v) {
            tagsReceived(v, canvasId);
        }
    });

}
/**
 * sessionsListReceived()
 * Aktualizuje tabelę aktywnych sesji.
 * @param data
 * @param users
 */
function sessionsListReceived(data, users) {
    requestSessionList();
}

/**
 * tagsReceived()
 * Funkcja po otrzymaniu danych o tagach zaaplikuje je do zdjęcia
 *
 * @param v
 * @param gridId
 */
function tagsReceived(v, gridId) {
    for (var i = 0; i < v.length; i++) {
        addTag(gridId, "tag_" + v[i]["id"], v[i]["y"] + "px", v[i]["x"] + "px", v[i]["title"]);
    }
    fixTagsPositions(gridId, $('#' + gridId).attr('zoom'));


}

var chatType = 'tag';
var chatId = 3;
var chatLast = 0;

function sendChatMessage() {
    var msg = document.getElementById('chatInput').value;
    document.getElementById('chatInput').value = '';
    //webSocket.send(msg);
    var data = {};
    data["type"] = chatType;
    data["id"] = chatId;
    data["content"] = msg;
    //jQuery.post( url [, data ] [, success(data, textStatus, jqXHR) ] [, dataType ] )
    jQuery.post("index.php/chat/newpost", data);
}

function sendMsg(cmd, type, id) {
    var str = '{"cmd":"' + cmd + '","type":"' + type + '","id":' + id + '}';
    webSocket.send(str);
}
function sendHashMsg(cmd, type, id, hash) {
    var str = '{"cmd":"' + cmd + '","type":"' + type + '","id":' + id + ',"hash":"' + hash + '"}';
    webSocket.send(str);
}
function sendDataMsg(cmd, type, id, data) {
    var str = '{"cmd":"' + cmd + '","type":"' + type + '","id":' + id + ',"data":' + data + '}';
    webSocket.send(str);
}

function register() {
    sendMsg('register', 'user', '0');
}
function request(type, id) {
    sendMsg('request', type, id);
}
function ignore(type, id) {
    sendMsg('ignore', type, id);
}

function downloadPosts() {
    var args = {};
    args['id'] = chatId;
    args['last'] = chatLast;
    jQuery.post("index.php/chat/" + chatType, args, function (data) {
        if ((chatType != data['type']) || (chatId != data['id'])) {
            return;
        }
        document.getElementById('chatRoomId').innerHTML = '#' + data['title'];
        for (i = 0; i < data['posts'].length; i++) {
            if (data['posts'][i]['id'] <= chatLast) {
                continue;
            }
            $('#chatList').prepend("<tr><td>" + data['posts'][i]['owner'] + ": " + data['posts'][i]['content'] + "</td></tr>");
            chatLast = data['posts'][i]['id'];
        }
    });

}

function setChat(type, id) {
    if ((chatType == type) && (chatId == id)) {
        return;
    }
    ignore(chatType, chatId);
    chatType = type;
    chatId = id;
    chatLast = 0;
    $('#chatList tr').remove();
    downloadPosts();
    request(chatType, chatId);
}


function setupWebSocket() {

    webSocket = new WebSocket("ws://" + window.location.host + ":12345/echo");
    webSocket.onopen = function (evt) {
        register();
        request('room', 0);
    };
    webSocket.onclose = function (evt) {
        onClose(evt)
    };
    webSocket.onmessage = function (evt) {
        onMessage(evt);
    };
    webSocket.onerror = function (evt) {
        onError(evt);

    };
}


function onClose(evt) {
    alert('Rozłączono!');
}
function onMessage(evt) {
    //$('#chatList').append("<tr><td>" + evt.data + "</td></tr>");
    data = jQuery.parseJSON(evt.data);
    if ((data['type'] == chatType) && (data['id'] == chatId)) {
        downloadPosts();
    }
    if ((data['type'] == 'room') && (data['id'] == 0)) {
        loadRooms();
    }
    if ((data['type'] == 'list') && (data['id'] == 0)) {
        sessionsListReceived(data['data'], 0);
    }
    if (data['type'] == 'live') {
        if (typeof data['error'] != 'undefined') {
            alert("Błąd: " + data['error']);
            unlockBoard();
        }
        else if (data['cmd'] == 'update') {
            var board = JSON.stringify(data['data']);
            setBoardState(board);
        }
        else if (data['cmd'] == 'list') {
            //alert(sessionWindow);
            var lst = sessionWindow.document.getElementById('sessionList');
            $(lst).empty();
            $(lst).append('<thead><tr><th>username</th></tr></thead>');
            for (var i = 0; i < data['data'].length; i++)
                $(lst).append('<tr><td onclick="window.opener.sendSessionRequest(' + parseInt(data['data'][i]) + ');">' + sessionWindow.userList[parseInt(data['data'][i])] + " </td></tr > ");

        }
    }
    //TODO odczytanie rodzaju zasobu
}
function onError(evt) {
    alert('Błąd czatu: ' + evt.data);
}


var liveChanel = null;
var liveListener = null;

function startLiveSession() {
    jQuery.get("index.php/chat/live", function (data) {
        liveChanel = data['chanel'];
        sendHashMsg('open', 'live', data['chanel'], data['hash']);
        alert("Uruchomiono współdzielenie sesji.");
    });
}

function sendSessionUpdate() {
    if (liveChanel != null) {
        var board = JSON.stringify(getBoardState());
        sendDataMsg('update', 'live', liveChanel, board);
    }
}

function sendSessionRequest(id) {
    request('live', id);
    liveListener = id;
    blockBoard();
}

function sendSesionAck() {
    sendMsg('ack', 'live', liveListener);
}

function requestSessionList() {
    sendMsg('ls', 'live', 0);
}

function stopLiveSession() {
    sendMsg('close', 'live', liveChanel);
    liveChanel = null;
}