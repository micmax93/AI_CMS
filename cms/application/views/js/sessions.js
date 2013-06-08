/**
 * Created with JetBrains PhpStorm.
 * User: phisikus
 * Date: 27.04.13
 * Time: 16:43
 * To change this template use File | Settings | File Templates.
 */

function saveView() {

    $.ajax({
        type: "POST",
        url: baseUrl + "index.php/view/set/0",
        data: {status: "PUBLIC", state: JSON.stringify(getBoardState()), start: startTime, end: new Date()},
        success: function () {
        }
    });
}


function applyBoardState(id) {
    $.ajax({
        dataType: "json",
        url: "index.php/view/get/" + id,
        success: function (v) {
            if (v["id"] != "null")
                setBoardState(v["state"]);
        }
    });

}