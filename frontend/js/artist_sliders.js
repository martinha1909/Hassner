$( function() {  
    // Buy slider init
    $( "#buy_limit" ).slider({
      range: true,
      min: 0,
      max: 500,
      values: [ 0, 500 ], // TODO: ajax Query db, get min and max limit
      slide: function( event, ui ) {
        min = ui.values[0];
        max = ui.values[1];
        if(min == 0 && max == 500){
          $("#buy_tip").text("Without limits the next available share(s) will be purchased at market price");
        }
        else if (min > 0 && max == 500){
          $("#buy_tip").text("The buy order will be executed as soon as the price is <= " + min);
        }
        else if (min > 0 && max < 500){
          $("#buy_tip").text("The buy order will be executed as soon as the price is between " + min + " and " + max);
        }
        else if (min == 0 && max < 500){
          $("#buy_tip").text("The buy order will be executed as soon as the price is >= " + max);
        }
        $("#buy_cost").val("$" + min*$("#buy_num").slider("value") + " - $" + max*$("#buy_num").slider("value"));
      }
    });
    $( "#buy_num_shares" ).val($("#buy_limit").slider("values", 0));
    $( "#buy_cost" ).val($("#buy_limit").slider("values", 1));



    // Sell slider init
    $( "#sell_limit" ).slider({
      range: true,
      min: 0,
      max: 500,
      values: [ 0, 500 ], // TODO: ajax Query db, get min and max limit
      slide: function( event, ui ) {
        min = ui.values[0];
        max = ui.values[1];
        if(min == 0 && max == 500){
          $("#sell_tip").text("Without limits the next available share(s) will be purchased at market price");
        }
        else if (min > 0 && max == 500){
          $("#sell_tip").text("The sell order will be executed as soon as the price is <= " + min);
        }
        else if (min > 0 && max < 500){
          $("#sell_tip").text("The sell order will be executed as soon as the price is >= " + min + " <= " + max);
        }
        else if (min == 0 && max < 500){
          $("#sell_tip").text("The sell order will be executed as soon as the price is >= " + max);
        }
        $("#sell_cost").val("$" + min*$("#sell_num").slider("value") + " - $" + max*$("#sell_num").slider("value"));
      }
    });
    $( "#sell_min" ).val($("#sell_limit").slider("values", 0));
    $( "#sell_max" ).val($("#sell_limit").slider("values", 1));

    // # Shares to buy slider
    $("#buy_num").slider({
      range: "min",
      min: 0,
      max: 500,
      value: 0,
      slide: function( event, ui ) {
        $("#buy_num_shares").val(ui.value);
        $("#buy_cost").val("$" + ui.value*$("#buy_limit").slider("values", 0) + " - $" + ui.value*$("#buy_limit").slider("values", 1));
      }
    })

     // # Shares to sell slider
     $("#sell_num").slider({
      range: "min",
      min: 0,
      max: 500,
      value: 0,
      slide: function( event, ui ) {
        $("#sell_num_shares").val(ui.value);
        $("#sell_cost").val("$" + ui.value*$("#sell_limit").slider("values", 0) + " - $" + ui.value*$("#sell_limit").slider("values", 1));
      }
    })

    // Accoridon init
    $( "#buy_accordion" ).accordion({
      heightStyle: "content",
      collapsible: true,
      active: false
    });

    $( "#sell_accordion" ).accordion({
      heightStyle: "content",
      collapsible: true,
      active: false
    });

    $("#buy_order").click(function(){
      // AJAX
    })

    $("#sell_order").click(function(){
      // AJAX
    })
  } );