$(document).ready(function() {

    var transfer = JSON.parse(document.querySelector('#current_script').getAttribute('myvar'));
    console.log(transfer);
    var y_axis = [];
    var x_axis = [];
    var len = transfer.length;

    for (var i = 0; i < len; i++) {
        if (transfer[i].artist_username == "Drake") {
            y_axis.push(transfer[i].price_per_share);
            x_axis.push(transfer[i].time_recorded);
        }
    }

    var ctx = $("#mycanvas");

    var data = {
        labels : x_axis,
        datasets : [
            {
                label : "Drake stock",
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