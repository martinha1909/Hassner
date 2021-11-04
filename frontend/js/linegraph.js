$(document).ready(function() {
    var json_transfer = JSON.parse(document.querySelector('#artist_user_share_info_script').getAttribute('artist_json'));
    var artist_market_tag = document.querySelector('#artist_user_share_info_script').getAttribute('artist_tag');
    var graph_option = document.querySelector('#artist_user_share_info_script').getAttribute('graph_option');
    var y_axis = [];
    var x_axis = [];
    var len = json_transfer.length;

    if(graph_option === "1D")
    {
        for (var i = 0; i < len; i++) {
            y_axis.push(json_transfer[i].price_per_share);
            x_axis.push(json_transfer[i].time_recorded);
        }
    }
    else if(graph_option === "5D")
    {
        for (var i = 0; i < len; i++) {
            y_axis.push(json_transfer[i].price_per_share);
            x_axis.push(json_transfer[i].date_recorded);
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
            text : artist_market_tag + " (" + json_transfer[0].artist_username + ")",
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