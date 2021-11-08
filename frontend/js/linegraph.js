$(document).ready(function(){
    var graph_option = "1D";
    JSONToAJAX(graph_option);
    $("#1D").click(function (){
        graph_option = "1D";
        JSONToAJAX(graph_option);
    });
    $("#5D").click(function (){
        graph_option = "5D";
        JSONToAJAX(graph_option);
    });
    $("#1M").click(function (){
        graph_option = "1M";
        JSONToAJAX(graph_option);
    });
    $("#6M").click(function (){
        graph_option = "6M";
        JSONToAJAX(graph_option);
    });
    $("#YTD").click(function (){
        graph_option = "YTD";
        JSONToAJAX(graph_option);
    });
    $("#1Y").click(function (){
        graph_option = "1Y";
        JSONToAJAX(graph_option);
    });
    $("#5Y").click(function (){
        graph_option = "5Y";
        JSONToAJAX(graph_option);
    });
});

function JSONToAJAX(graph_option)
{
    $.ajax({
        url : "http://localhost:8080/Hassner/backend/graph/LineGraphData.php",
        method : "POST",
        data:{
            graph_option: graph_option
        },
        success : function(data){
            // var graph_option = document.querySelector('#artist_user_share_info_script').getAttribute('graph_option');
            var artist_market_tag = document.querySelector('#artist_user_share_info_script').getAttribute('artist_tag');
            var y_axis = [];
            var x_axis = [];
            var len = data.length;
            var last_fetched_date = "";

            //one-day graph and five-day graph are filtered for x axis here
            if(graph_option === "1D" || graph_option === "5D" || graph_option === 0)
            {
                for (var i = 0; i < len; i++) 
                {
                    const json_date_split = data[i].date_recorded.split(" ");
                    var date_recorded = json_date_split[0];
                    var time_recorded = json_date_split[1];
                    
                    //don't care about the seconds
                    time_recorded = time_recorded.substring(0, 5);
                    
                    if(x_axis.length === 0)
                    {
                        x_axis.push(date_recorded);
                        y_axis.push(data[i].price_per_share);
                        last_fetched_date = date_recorded;
                    }
                    else if(date_recorded === last_fetched_date)
                    {
                        x_axis.push(time_recorded);
                        y_axis.push(data[i].price_per_share);
                    }
                    else if(date_recorded != last_fetched_date)
                    {
                        x_axis.push(date_recorded);
                        y_axis.push(data[i].price_per_share);
                        last_fetched_date = date_recorded;
                    }
                }

                console.log(x_axis);
            }
            //one-month graph, 6-month graph, YTD graph, and 1-year graph are pre-filtered
            else
            {
                //Since the data is pre-filtered by day, 5-day graph would add to the axis every 5 data points
                if(graph_option == "5Y")
                {
                    // console.log(data);
                    var counter = 0;
                    for (var i = 0; i < len; i++)
                    {
                        if(counter % 5 === 0)
                        {
                            x_axis.push(data[i].date_recorded);
                            y_axis.push(data[i].price_per_share);
                        }
                        counter++;
                    }
                }
                else
                {
                    for (var i = 0; i < len; i++)
                    {
                        x_axis.push(data[i].date_recorded);
                        y_axis.push(data[i].price_per_share);
                    }
                }
            }
            
            var ctx = $("#stock_graph");

            var graph_data = {
                labels : x_axis,
                datasets : [
                    {
                        label : data[0].artist_username,
                        data : y_axis,
                        backgroundColor : "#0a60d0",
                        borderColor : "#0a60d0",
                        fill : false,
                        lineTension : 0,
                        pointRadius : 2
                    }
                ]
            };

            var options = {
                title : {
                    display : true,
                    position : "top",
                    text : artist_market_tag.toUpperCase() + " (" + data[0].artist_username + ")",
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
}