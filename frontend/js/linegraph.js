$(document).ready(function() {

    var json_transfer = JSON.parse(document.querySelector('#artist_user_share_info_script').getAttribute('artist_json'));
    var y_axis = [];
    var x_axis = [];
    var len = json_transfer.length;

    for (var i = 0; i < len; i++) {
        if (json_transfer[i].artist_username == "Drake") {
            y_axis.push(json_transfer[i].price_per_share);
            x_axis.push(json_transfer[i].time_recorded);
        }
    }

    var ctx = $("#mycanvas");

    var data = {
        labels : x_axis,
        datasets : [
            {
                label : json_transfer[0].artist_username,
                data : y_axis,
                backgroundColor : "#0a60d0",
                borderColor : "#0a60d0",
                fill : false,
                lineTension : 0,
                pointRadius : 5
            }
        ]
    };

    var options = {
        title : {
            display : true,
            position : "top",
            text : "Drake last 24hr",
            fontSize : 18,
            fontColor : "#e2cda9ff"
        },
        legend : {
            display : true,
            position : "bottom"
        }
    };

    var chart = new Chart( ctx, {
        type : "line",
        data : data,
        options : options
    } );

});