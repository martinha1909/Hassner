$( function() {
  var max_limit = 0;
  var min_limit = 0;
  var max_num_of_shares = 0;
  var sellable_shares = 0;
  var url_max_limit = window.location.protocol + "//" + window.location.hostname + ":" + window.location.port + "/Hassner/backend/sliders/StockPrice.php";
  var url_max_num_shares = window.location.protocol + "//" + window.location.hostname + ":" + window.location.port + "/Hassner/backend/sliders/MaxNumOfShares.php";
  var url_sellable_shares = window.location.protocol + "//" + window.location.hostname + ":" + window.location.port + "/Hassner/backend/sliders/SellableShares.php";
  $.ajax({
    url : url_max_limit,
    method : "GET",
    async: false,
    success : function(data){
      max_limit = data;
    },
    error : function(data){

    }
  });
  $.ajax({
    url : url_max_limit,
    method : "GET",
    async: false,
    success : function(data){
      max_limit = parseFloat((data*2).toFixed(1));
      //We allow users to set the min limit to be half the current stock price
      min_limit = parseFloat((data/2).toFixed(1));
    },
    error : function(data){

    }
  });

    // Buy slider init
    $( "#buy_limit" ).slider({
      range: true,
      min: min_limit,
      max: max_limit,
      values: [ min_limit, max_limit ],
      step:0.5,
      slide: function( event, ui ) {
        min = ui.values[0];
        max = ui.values[1];
        if(min == min_limit && max == max_limit){
          $("#buy_tip").text("Without limits the next available share(s) will be purchased at market price");
          $("#buy_cost").val("$" + $("#buy_num").slider("value")*$("#pps").text());
        }
        else if (min > min_limit && max == max_limit){
          $("#buy_tip").text("The buy order will be executed as soon as the price is <= " + min);
          $("#buy_cost").val("$" + $("#buy_num").slider("value")*min);
        }
        else if (min > min_limit && max < max_limit){
          $("#buy_tip").text("The buy order will be executed as soon as the price is <= " + min + " or >= " + max);
        }
        else if (min == min_limit && max < max_limit){
          $("#buy_tip").text("The buy order will be executed as soon as the price is >= " + max);
          $("#buy_cost").val("$" + $("#buy_num").slider("value")*max);
        }
      }
    });

    // Sell slider init
    $( "#sell_limit" ).slider({
      range: true,
      min: min_limit,
      max: max_limit,
      values: [ min_limit, max_limit ],
      slide: function( event, ui ) {
        min = ui.values[0];
        max = ui.values[1];
        if(min == min_limit && max == max_limit){
          $("#sell_tip").text("Without limits the next available share(s) will be purchased at market price");
          $("#sell_cost").val("$" + $("#sell_num").slider("value")*$("#pps").text());
        }
        else if (min > min_limit && max == max_limit){
          $("#sell_tip").text("The sell order will be executed as soon as the price is <= " + min);
          $("#sell_cost").val("$" + $("#sell_num").slider("value")*min);
        }
        else if (min > min_limit && max < max_limit){
          $("#sell_tip").text("The sell order will be executed as soon as the price is <= " + min + " or >= " + max);
        }
        else if (min == min_limit && max < max_limit){
          $("#sell_tip").text("The sell order will be executed as soon as the price is >= " + max);
          $("#sell_cost").val("$" + $("#sell_num").slider("value")*max);
        }
      }
    });

    $.ajax({
      url : url_max_num_shares,
      method : "POST",
      data : {
        min_lim: min_limit,
        max_lim: max_limit, 
        chosen_min: $("#buy_limit").slider("values", 0),
        chosen_max: $("#buy_limit").slider("values", 1)
      },
      async: false,
      success : function(data) {
        max_num_of_shares = data;
      },
      error : function(data){

      }
    });
    // # Shares to buy slider
    $("#buy_num").slider({
      range: "min",
      min: 1,
      max: max_num_of_shares,
      value: 1,
      slide: function( event, ui ) {
        var min_limit_top = $("#buy_limit").slider("values", 0);
        var max_limit_top = $("#buy_limit").slider("values", 1);
        if((min_limit_top == min_limit && max_limit_top == max_limit) || (min_limit_top > min_limit && max_limit_top < max_limit))
        {
          $("#buy_num_shares").val(ui.value);
          $("#buy_cost").val("$" + ui.value*$("#pps").text());
        }
        else if(min_limit_top > min_limit && max_limit_top == max_limit)
        {
          $("#buy_num_shares").val(ui.value);
          $("#buy_cost").val("$" + ui.value*min_limit_top);
        }
        else if(min_limit_top == min_limit && max_limit_top < max_limit)
        {
          $("#buy_num_shares").val(ui.value);
          $("#buy_cost").val("$" + ui.value*max_limit_top);
        }
      }
    })

    $.ajax({
      url : url_sellable_shares,
      method : "GET",
      async: false,
      success : function(data){
        sellable_shares = data;
      },
      error : function(data){

      }
    });
     // # Shares to sell slider
     $("#sell_num").slider({
      range: "min",
      min: 1,
      max: sellable_shares,
      value: 1,
      slide: function( event, ui ) {
        var min_limit_top = $("#buy_limit").slider("values", 0);
        var max_limit_top = $("#buy_limit").slider("values", 1);
        if((min_limit_top == min_limit && max_limit_top == max_limit) || (min_limit_top > min_limit && max_limit_top < max_limit))
        {
          $("#sell_num_shares").val(ui.value);
          $("#sell_cost").val("$" + ui.value*$("#pps").text());
        }
        else if(min_limit_top > min_limit && max_limit_top == max_limit)
        {
          $("#sell_num_shares").val(ui.value);
          $("#sell_cost").val("$" + ui.value*min_limit_top);
        }
        else if(min_limit_top == min_limit && max_limit_top < max_limit)
        {
          $("#sell_num_shares").val(ui.value);
          $("#sell_cost").val("$" + ui.value*max_limit_top);
        }
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
    var min_limit_top = $("#buy_limit").slider("values", 0);
    var max_limit_top = $("#buy_limit").slider("values", 1);
    var url_event = window.location.protocol + "//" + window.location.hostname + ":" + window.location.port + "/Hassner/backend/sliders/BuyAndSellEvent.php";
      $.ajax({
        url : url_event,
        method : "POST",
        data:{
          user_event: "BUY",
          num_of_shares: $("#buy_num_shares").val(),
          chosen_min: min_limit_top,
          chosen_max: max_limit_top,
          min_lim: min_limit,
          max_lim: max_limit,
          market_price: $("#pps").text()
        },
        success : function(data){
          console.log(data);
          if(data === "Price Outdated")
          {
            //Error handling for prices don't match here
          }
          else if(data === "SUCCESS")
          {
            window.location = window.location.protocol + "//" + window.location.hostname + ":" + window.location.port + "/Hassner/frontend/listener/listener.php";
          }
        },
        error : function(data){

        }
      });
    })

    $("#sell_order").click(function(){
      var min_limit_top = $("#sell_limit").slider("values", 0);
      var max_limit_top = $("#sell_limit").slider("values", 1);
      var url_event = window.location.protocol + "//" + window.location.hostname + ":" + window.location.port + "/Hassner/backend/sliders/BuyAndSellEvent.php";
      $.ajax({
        url : url_event,
        method : "POST",
        data:{
          user_event: "SELL",
          num_of_shares: $("#sell_num_shares").val(),
          chosen_min: min_limit_top,
          chosen_max: max_limit_top,
          min_lim: min_limit,
          max_lim: max_limit,
          market_price: $("#pps").text()
        },
        success : function(data){
          console.log(data);
          if(data === "Price Outdated")
          {
            //Error handling for prices don't match here
          }
          else if(data === "SUCCESS")
          {
            window.location = window.location.protocol + "//" + window.location.hostname + ":" + window.location.port + "/Hassner/frontend/listener/listener.php";
          }
        },
        error : function(data){

        }
      });
    })
    $( "#sell_num_shares" ).val($("#sell_num").slider("value"));
    $( "#buy_num_shares" ).val($("#buy_num").slider("value"));
    $("#buy_cost").val("$" + $("#buy_num").slider("value")*$("#pps").text());
    $("#sell_cost").val("$" + $("#sell_num").slider("value")*$("#pps").text());
  } );