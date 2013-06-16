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

var myName;
var myHash;

function register(addr) {
    jQuery.post("index.php/cms/get_code", function (data) {
        myName=data['uname'];
        myHash=data['hash'];
        login();
    });
}
function login() {
    var str = '{"cmd":"login","args":{"user":"' + myName + '","hash":"' + myHash + '"}}';
    webSocket.send(str);
}

var active={};
var delayed={};
var lastUpdate=new Date().getTime() / 1000;

function updateTimeouts() {
    var currTime=new Date().getTime() / 1000;
    var diff=currTime-lastUpdate;
    for (u in active)
    {
        if(active[u]['actv']!='inf')
        {
            active[u]['actv']-=diff;
            active[u]['remv']-=diff;
        }
    }
    for (u in delayed)
    {
        delayed[u]['actv']-=diff;
        delayed[u]['remv']-=diff;
    }
    lastUpdate=currTime;
}

function refreshList() {
    updateTimeouts();
    for (u in active)
    {
        if((active[u]['actv']!='inf')&&(active[u]['actv']<=0))
        {
            delayed[u]=active[u];
            delete active[u];
        }
    }
    for (u in delayed)
    {
        if(delayed[u]['remv']<=0)
        {
            delete delayed[u];
        }
    }
}

function userUpdate(user,action,times)
{
    if(user in active)
        {delete active[user];}
    if(user in delayed)
        {delete delayed[user];}
    refreshList();
    if((action=='login')||(action=='update'))
        {active[user]=times;}
    if(action=='logout')
        {delayed[user]=times;}
}
function repaintList() {
    $('#activeList tr').remove();
    for (u in active)
    {
        $('#activeList').append("<tr><td>" + u + " " + active[u]['actv'] + "</td></tr>");
            //.prepend("<tr><td>" + u + "</td></tr>");
    }
    $('#delayedList tr').remove();
    for (u in delayed)
    {
        $('#delayedList').append("<tr><td>" + u + " " + delayed[u]['remv'] + "</td></tr>");
    }
}

function showMsg(str) {
    $('#chatList').prepend("<tr><td>" + str + "</td></tr>");
}

function setupRefresh(timeout) {
    window.setInterval(function() {
        refreshList();
        repaintList();
    },timeout);
}

function setupWebSocket() {

    webSocket = new WebSocket("ws://" + window.location.host + ":12345/echo");
    webSocket.onopen = function (evt) {
        register();
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
    if (data['cmd'] == 'list') {
        active = data['args']['active'];
        delayed = data['args']['delayed'];
        repaintList();
        setupRefresh(1000);
    }
    else if (data['cmd'] == 'update') {
        userUpdate(data['args']['user'],data['args']['action'],data['args']['time']);
        repaintList();
    }
}


function update() {
    var args={};
    args['uname']=myName;
    args['hash']=myHash;
    jQuery.post("update",args, function (data) {
        active = data['args']['active'];
        delayed = data['args']['delayed'];
        repaintList();
    });
}

function onError(evt) {
    alert('Błąd czatu: ' + evt.data);
}