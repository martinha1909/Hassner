$(document).ready(function(){
    $.ajax({
        url : "http://localhost:8080/Hassner/backend/graph/LineGraphData.php",
        type : "GET",
        success : function(data){
            // console.log(data);
            var graph_option = document.querySelector('#artist_user_share_info_script').getAttribute('graph_option');
            var artist_market_tag = document.querySelector('#artist_user_share_info_script').getAttribute('artist_tag');
            var y_axis = [];
            var x_axis = [];
            var len = data.length;
            var last_fetched_date = "";

            for (var i = 0; i < len; i++) 
            {
                if(x_axis.length === 0)
                {
                    y_axis.push(data[i].price_per_share);
                    x_axis.push(data[i].date_recorded);
                    last_fetched_date = data[i].date_recorded;
                }
                else if(data[i].date_recorded === last_fetched_date)
                {
                    y_axis.push(data[i].price_per_share);
                    x_axis.push(data[i].time_recorded);
                }
                else if(data[i].date_recorded != last_fetched_date)
                {
                    y_axis.push(data[i].price_per_share);
                    x_axis.push(data[i].date_recorded);
                    last_fetched_date = data[i].date_recorded;
                }
            }
            
            var ctx = $("#stock_graph");

            var graph_data = {
                labels : x_axis,
                datasets : [
                    {
                        label : "88Glam",
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
                    text : artist_market_tag + " (" + data[0].artist_username + ")",
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
                data : graph_data,
                options : options
            });

        },
        error : function(data){

        }
    });
});