/**
 * User: phisikus
 * Date: 23.03.13
 * Time: 14:50
 */
var listaOkien = Array();
var currentView = Array();

/**
 * createWindow()
 * Tworzy okno, jego elementy sterujące oraz grid i wywołuje addCanvas i drawOnCanvas
 * w celu wyrysowania wszystkich zdjęć na canvasach.
 * W miedzyczasie ustawia wszystkie parametry pozwalające na przesuwanie elementów.
 * @param parentId
 * @param id
 * @param width
 * @param height
 * @param rows
 * @param rowSize
 * @param images
 */
function createWindow(parentId, id, width, height, rowSize, rows, photoId, images) {

    // Oznacz wszystkie pozostałe okna jako nieaktywne
    var window;
    var max = 0;

    // Oblicz max zIndex okien
    if (listaOkien.length > 0) {
        for (var i = 0; i < listaOkien.length; i++) {
            window = document.getElementById(listaOkien[i]);
            if (parseInt(window.style.zIndex) > max) max = parseInt(window.style.zIndex);
            window.getElementsByTagName("div")[0].className = "titleBar";
        }
    }


    // Dodaj diva ze strukturą okna
    $('#' + parentId).append('' +
        '<div class="imageWindow" id="' + id + '_window"  onmousedown="firstPlanWindow(\'' + id + '_window\');" photoId="' + photoId + '">' +
        '   <div class="titleBarActive" onmousedown="firstPlanWindow(\'' + id + '_window\');" id="' + id + '_title"><p>' + id + ' [' + width * rowSize + 'x' + height * rows + ']</p>' +
        ' Zoom:' +
        '       <a href="javascript:zoom(\'' + id + '_grid\',1.2);" class="smallButton">+</a>' +
        '       <a href="javascript:zoom(\'' + id + '_grid\',0.8);" class="smallButton">-</a>' +
        ' Contrast:' +
        '       <a href="javascript:contrastCanvas(\'' + id + '_img\',-20);" class="smallButton">+</a>' +
        '       <a href="javascript:contrastCanvas(\'' + id + '_img\',20);" class="smallButton">-</a>' +
        ' Brightness:' +
        '       <a href="javascript:brightnessCanvas(\'' + id + '_img\',20);" class="smallButton">+</a>' +
        '       <a href="javascript:brightnessCanvas(\'' + id + '_img\',-20);" class="smallButton">-</a>' +
        ' Revert:' +
        '       <a href="javascript:canvasRevert(\'' + id + '_grid\',-20);" class="smallButton">O</a>' +
        '       <a href="javascript:closeWindow(\'' + id + '_window\');" class="close">X</a>' +
        '   </div>' +
        '   <div class="viewport" id="' + id + '_viewport">' +
        '       <div class="imageGrid" id="' + id + '_grid">' +
        '       </div>' +
        '   </div>' +
        '</div>' +
        '<script type="text/javascript">' +
        '   document.getElementById(\'' + id + '_grid\').addEventListener(\'dblclick\',' +
        '   function(e) { gridClicked(e,this.id); }, false); ' +
        '</script>' +
        '');
    // Obliczenie skali okna
    var scale = 1;
    var maxWidth = 0.7 * screen.width;
    var maxHeight = 0.7 * screen.height;

    if (height * rows <= 700) scale = 700 / (height * rows);
    if (width * rowSize <= 700) scale = 700 / (width * rowSize);
    if (height * rows >= maxHeight) scale = 1000 / (height * rows);
    if (width * rowSize >= maxWidth) scale = 1000 / (width * rowSize);


    // Dodaj okno do listy i umożliw jego przesuwanie a także zwiększ o wysokość paska tytułowego
    listaOkien.push(id + '_window');

    $('#' + id + '_window').draggable({opacity: 0.8, containment: "parent", handle: '#' + id + '_title', stop: function () {
        sendSessionUpdate();
    }
    }).css('top', (listaOkien.length * 20)).width(width * rowSize * scale).height(height * scale * rows + 20);
    $('#' + id + '_grid').width(width * rowSize).height(height * rows).attr('basicHeight', height * rows).attr('basicWidth', width * rowSize).attr('zoom', 1);


    tc = addCanvas(id + '_grid', id + '_img', width * rowSize, height * rows);
    // Dodaj kanwę
    if (!Array.isArray(images)) {
        // Rysuj zdjęcie
        drawImage(id + '_img', images, 0, 0, 1);
    }
    else {
        var x = -1;
        var y = 0;
        for (var i = 0; i < images.length; i++) {
            x++;
            // Rysuj zdjęcie
            if ((i + 1) == images.length)
                drawImage(id + '_img', images[i], x * width, y * height, 1, 1);
            else
                drawImage(id + '_img', images[i], x * width, y * height, 1);
            if (((x + 1) % rowSize) == 0) {
                x = -1;
                y++;
            }

        }

    }

    // using the event helper
    $('#' + id + '_viewport').bind('mousewheel', function (event, delta, deltaX, deltaY) {
        zoom(id + '_grid', ((delta > 0) ? 1.2 : 0.8));
    });

    // Wyświetl tagi przynależne do zdjęcia.
    // Przy odtwarzaniu stanu zostaną one naniesione później - po zaaplikowaniu zoomu
    if (!currentView[id])
        addTags(photoId, id + '_grid');

    // Ustaw przesuwalność zdjęć wewnątrz viewportu i pierwszoplanowość okna
    $('#' + id + '_grid').draggable({cursor: "move",
        stop: function () {
            sendSessionUpdate();
        }}).bind('dragend', function () {
            sendSessionUpdate();
        });
    $('#' + id + '_viewport').css('z-index', 0);
    $('#' + id + '_window').css('z-index', max + 1).bind('dragend', function () {
        sendSessionUpdate();
    });
    sendSessionUpdate();
}

/**
 * closeWindow()
 * Zamyka okno - ukrywa, a potem usuwa z dokumentu i listy okien
 *
 * @param id
 */
function closeWindow(id) {
    $('#' + id).css('display', 'none').remove();
    for (var i = 0; i < listaOkien.length; i++) {

        if (listaOkien[i] == id) {
            var name = listaOkien[i].substr(0, listaOkien[i].length - 7) + "_img";

            if (contrast.hasOwnProperty(name))
                delete contrast[name];
            if (brightness.hasOwnProperty(name))
                delete brightness[name];

            listaOkien.splice(i, 1);
        }


    }
    sendSessionUpdate();
}

/**
 * firstPlanWindow()
 * Wyciąga okno na pierwszy plan i zmienia klasę paska tytułu
 * @param name
 */
function firstPlanWindow(name) {
    var window;
    var maxzIndex = 0;
    for (var i in listaOkien) {
        window = document.getElementById(listaOkien[i]);
        if (parseInt(window.style.zIndex) > maxzIndex) maxzIndex = parseInt(window.style.zIndex);
        window.getElementsByTagName("div")[0].className = "titleBar";
    }

    window = document.getElementById(name);

    if (window) {
        if (maxzIndex != parseInt(window.style.zIndex)) {
            window.style.zIndex = maxzIndex + 1;
            sendSessionUpdate();
        }
        window.getElementsByTagName("div")[0].className = "titleBarActive";
    }
}

/**
 * showHide()
 * Dla obiektu o podanym id ukrywa go bądź pokazuje (parametr display)
 * @param thing
 */
function showHide(thing) {
    var el = document.getElementById(thing);
    if (el.style.display == "none") {
        el.style.display = "block";
        firstPlanWindow(thing);
        //el.parentNode.style.height = parseInt(el.height) + "px";
    }
    else {
        el.style.display = "none";
        //el.parentNode.style.height = (parseInt(el.parentNode.style.height) - parseInt(el.height)) + "px";

    }
    sendSessionUpdate();
}

/**
 * showHideOrLoad()
 * Ukrywa bądź pokazuje okno.
 * Jeżeli nie istnieje - zostanie zainicjalizowane pobranie metadanych.
 * Funkcja imageReceived dokona dalszego wywołania createWindow dla odebranych danych.
 *
 * @param id
 * @param name
 */
function showHideOrLoad(id, name) {
    var el = document.getElementById(name + '_window');

    if (!el) {
        var data;
        $.ajax({
            dataType: "json",
            url: "index.php/image/get/" + id,
            data: data,
            success: imageReceived
        });
    }
    else {
        if (el.style.display == "none") {
            el.style.display = "block";
            firstPlanWindow(name + '_window');
        }
        else {
            el.style.display = "none";

        }

    }
    sendSessionUpdate();
}

/**
 * removePhoto()
 * zamyka okno i zleca usunięcie zdjęcia z bazy danych.
 * @param id
 * @param name
 */
function removePhoto(id, name) {
    $.ajax({
        dataType: "json",
        url: "index.php/image/delete/" + id,
        photoListItem: "photoListItem_" + id,
        windowName: name + "_window",
        success: function (v) {
            if (v["status"] == "ok") {
                closeWindow(this.windowName);
                $('#' + this.photoListItem).remove();
            }
        }
    });
    sendSessionUpdate();
}

/**
 * menuRoll()
 *
 * Animowana funkcja ukrywania i rozwijania dla menu
 *
 * @param thing
 */
function menuRoll(thing) {
    $('#' + thing).animate({
        height: 'toggle'
    }, 250, function () {

    });

    // Wariant statyczny
    /* var el = document.getElementById(thing);
     if (el.style.display == "none") {
     el.style.display = "block";
     }
     else {
     el.style.display = "none";
     }*/


}


/**
 * getBoardState()
 *
 * Pobiera w postaci JSON stan okien otwartych na planszy oraz przekształcenia wewnątrz
 */
function getBoardState() {

    var windows = Array();
    var el;
    var grid;
    var win = {};

    for (var i = 0; i < listaOkien.length; i++) {
        el = document.getElementById(listaOkien[i]);
        grid = document.getElementById(el.id.substr(0, el.id.length - 7) + "_grid");
        win = {};
        win.title = el.id.substr(0, el.id.length - 7);
        win.photoId = $(el).attr('photoId');
        win.top = el.style.top;
        win.left = el.style.left;
        win.zoom = $(grid).attr('zoom');
        win.brightness = brightness[win.title + "_img"];
        win.contrast = contrast[win.title + "_img"];
        win.gridTop = grid.style.top;
        win.gridLeft = grid.style.left;
        win.gridHeight = grid.style.height;
        win.gridWidth = grid.style.width;
        win.display = el.style.display;
        win.firstPlan = el.style.zIndex;
        windows.push(win);
    }
    windows.sort(function (a, b) {
        if (a.firstPlan > b.firstPlan)
            return 1;
        if (a.firstPlan == b.firstPlan)
            return 0;
        if (a.firstPlan < b.firstPlan)
            return -1;

    });
    return windows;
}


/**
 * setBoardState()
 *
 * Odtwarza zwrócony przez getBoardState stan.
 *
 */
var nofWindows = -1;
var createdWindows = 0;
function setBoardState(state) {
    if (createdWindows > 0) return;
    nofWindows = -1;
    createdWindows = 0;
    // zamknij okna i usuń
    for (var i = 0; i < listaOkien.length; i++) {
        $('#' + listaOkien[i]).css('display', 'none').remove();
    }
    listaOkien.length = 0;

    var windows = JSON.parse(state);
    currentView = Array();
    nofWindows = windows.length;
    for (var i = 0; i < windows.length; i++) {
        currentView[windows[i].title] = windows[i];
        showHideOrLoad(windows[i].photoId, windows[i].title);
    }
}

function isNumber(n) {
    return !isNaN(parseFloat(n)) && isFinite(n);
}


/**
 * applyFilter()
 * Aplikuje na istniejących oknach parametry dla aktywnego widoku (nie dotyczy zawartości)
 *
 * @param x
 */
function applyView(x) {
    var el = document.getElementById(x + '_window');
    var grid = document.getElementById(x + '_grid');
    if (el && currentView[x]) {
        el.style.top = currentView[x].top;
        el.style.left = currentView[x].left;
        el.style.zIndex = currentView[x].firstPlan;
        if (currentView[x].display == "none") {
            el.style.display = "none";
        }
        $(grid).attr('zoom', currentView[x].zoom);
        grid.style.top = currentView[x].gridTop;
        grid.style.left = currentView[x].gridLeft;
        grid.style.height = currentView[x].gridHeight;
        grid.style.width = currentView[x].gridWidth;

        if (isNumber(currentView[x].contrast))
            contrast[x + '_img'] = currentView[x].contrast;
        if (isNumber(currentView[x].brightness))
            brightness[x + '_img'] = currentView[x].brightness;
        addTags(currentView[x].photoId, currentView[x].title + '_grid');


        //alert('w');

    }
}

/**
 * applyViewFilters()
 * Po załadowaniu treści okien aplikowane są filtry za pomocą tej funkcji
 * @param canvas
 */
function applyViewFilers(canvas) {
    var grid = canvas.parentNode;
    Caman('#' + canvas.id, function () {
        this.revert();
        if (isNumber(contrast[canvas.id]))
            this.contrast(contrast[canvas.id]);
        if (isNumber(brightness[canvas.id]))
            this.brightness(brightness[canvas.id]);
        this.render();

    });
    createdWindows++;
    if (createdWindows >= nofWindows) {
        sendSesionAck();
        createdWindows = 0;
    }
}

