/**
 * Created with JetBrains PhpStorm.
 * User: phisikus
 * Date: 23.03.13
 * Time: 14:51
 * To change this template use File | Settings | File Templates.
 */

/**
 * addCanvas()
 * Do podanego elementu nadrzędnego dodaje nową kanwę
 *
 * @param parentId
 * @param id
 * @return {HTMLElement}
 */
function addCanvas(parentId, id, width, height) {
    var newCanvas = $('<canvas/>', {'id': id});
    newCanvas.className = "imageCanvas";
    $('#' + parentId).append(newCanvas);
    var retCanvas = document.getElementById(id);
    retCanvas.className = "imageCanvas";
    retCanvas.width = width;
    retCanvas.height = height;
    return retCanvas;
}


/**
 * drawImage()
 * Rysuje obraz na kanwie startując z podanej pozycji dla podanej skali
 * @param id
 * @param imageUrl
 * @param x
 * @param y
 * @param scale
 */
var img = Array();
function drawImage(id, imageUrl, x, y, scale, last) {
    var canvas = document.getElementById(id);
    var context = canvas.getContext('2d');
    var i = img.length;
    img[i] = new Image();
    var drawImageOnLoad = function (context, img, x, y, scale) {
        return function () {
            context.drawImage(img, x, y, img.width * scale, img.height * scale);
        }
    };

    var drawImageOnLoadAndCallView = function (canvas, context, img, x, y, scale) {
        return function () {
            context.drawImage(img, x, y, img.width * scale, img.height * scale);
            applyViewFilers(canvas);
        }
    };


    if (!last)
        img[i].onload = drawImageOnLoad(context, img[i], x, y, scale);
    else
        img[i].onload = drawImageOnLoadAndCallView(canvas, context, img[i], x, y, scale);

    img[i].src = imageUrl;
}


/**
 * zoom()
 * Powiększa i przesuwa grid w zależności od skali.
 * @param id
 * @param scale
 */
function zoom(id, scale) {
    var grid = $('#' + id);
    var vp = grid.parent();
    var ax, ay, bx, by;

    //$('#chatList').append("<tr><td>(" + vcX + "," + vcY + ")  (" + gcX + "," + gcY + ")</td></tr>");

    // Ograniczenie skalowania
    if (((grid.height() < 150) || (grid.width() < 150)) && (scale < 1)) return;
    if (((grid.height() > 8000) || (grid.width() > 8000)) && (scale > 1)) return;
    grid.attr('zoom', grid.attr('zoom') * scale);

    fixTagsPositions(id, scale);

    ax = grid.width();
    ay = grid.height();
    bx = grid.width() * scale;
    by = grid.height() * scale;


    var dh, dw, top, left, vph, vpw;
    dw = bx - ax;
    dh = by - ay;

    top = parseFloat(grid.css('top'));
    left = parseFloat(grid.css('left'));
    if (isNaN(top)) {
        top = 0;
        left = 0;
    }
    vph = vp.height();
    vpw = vp.width();

    // Inspirowane:
    // http://jsfiddle.net/YFPRB
    // Na poczatku obliczamy w jakim procencie gridu znajduje się środek viewportu:
    // (vph/2)
    // obliczamy też różnicę w wymiarach przed i po skalowaniu (ax, ay, bx, by)
    // od pozycji odejmujemy iloczyn procentu i delty wymiarów przed i po skalowaniu (dw,dh)
    grid.css('top', top - (dh * (((vph / 2) - top) / ay)));
    grid.css('left', left - (dw * (((vpw / 2) - left) / ax)));

    grid.height(by);
    grid.width(bx);


    var tags = document.getElementsByClassName('imageTag');
    sendSessionUpdate();
}


var contrast = Array();
/**
 * contrastCanvas()
 * Zwiększa kontrast.
 *
 * @param id
 * @param value
 */
function contrastCanvas(id, value) {

    var canvas = document.getElementById(id);
    if (id in contrast) {
        contrast[id] += value;
    }
    else
        contrast[id] = value;

    Caman('#' + canvas.id, function () {
        this.revert();
        if (id in brightness)
            this.brightness(brightness[id]);
        this.contrast(contrast[id]);
        this.render();
    });
    sendSessionUpdate();
}


var brightness = Array();

/**
 * brightnessCanvas()
 * Zwiększa jasność
 *
 * @param id
 * @param value
 */
function brightnessCanvas(id, value) {
    var canvas = document.getElementById(id);
    if (id in brightness) {
        brightness[id] += value;
    }
    else
        brightness[id] = value;

    Caman('#' + canvas.id, function () {
        this.revert();

        if (id in contrast)
            this.contrast(contrast[id]);

        this.brightness(brightness[id]);
        this.render();
    });
    sendSessionUpdate();
}


/**
 * canvasRevert()
 * Odwraca skutki działania filtrów i ustawia grid na pozycji (0,0)
 * @param id
 */
function canvasRevert(id) {
    var grid = document.getElementById(id);
    $(grid).height($(grid).attr('basicHeight'));
    $(grid).width($(grid).attr('basicWidth'));
    grid.style.top = 0;
    grid.style.left = 0;
    contrast[grid.id] = 0;
    brightness[grid.id] = 0;
    var canvases = grid.getElementsByTagName("canvas");
    fixTagsPositions(grid.id, 1 / ($(grid).attr('zoom')));
    $(grid).attr('zoom', 1);
    for (var i = 0; i < canvases.length; i++) {
        Caman('#' + canvases[i].id, function () {
            this.revert();
        });
    }

    sendSessionUpdate();
}