var max_limit = 0;
var min_limit = 0;
var max_num_of_shares = 0;
var sellable_shares = 0;
var step_value = 0.1;
var url_max_num_shares = window.location.protocol + "//" + window.location.hostname + ":" + window.location.port + "/Hassner/backend/sliders/MaxNumOfShares.php";
var url_sellable_shares = window.location.protocol + "//" + window.location.hostname + ":" + window.location.port + "/Hassner/backend/sliders/SellableShares.php";

function recalcSliderLimits(new_chosen_min, new_chosen_max) 
{
  $.ajax({
    url : url_max_num_shares,
    method : "POST",
    data : {
      min_lim: min_limit,
      max_lim: max_limit, 
      chosen_min: new_chosen_min,
      chosen_max: new_chosen_max
    },
    async: false,
    success : function(data) {
      console.log(data);
      max_num_of_shares = data;
      $("#buy_num").slider("option", "max", data);
      $("#buy_num_shares").val($("#buy_num").slider("value"));
      if(new_chosen_max < max_limit)
      {
        $("#buy_cost").val($("#buy_num").slider("value") * max);
      }
      if(new_chosen_min > min_limit)
      {
        $("#buy_cost").val($("#buy_num").slider("value") * min);
      }
      if((new_chosen_max == max_limit && new_chosen_min == min_limit) || (new_chosen_max < max_limit && new_chosen_min > min_limit))
      {
        $("#buy_cost").val($("#buy_num").slider("value") * $("#pps").text());
      }

      if(data === 0)
      {
        $("#not_available_error_buy").text("No available sell orders found");
        $("#not_available_error_buy").show();
        $("#buy_order").hide();
      }
      else
      {
        $("#buy_order").show();
        $("#not_available_error_buy").hide();
      }
    },
    error : function(data){

    }
  });
}

function recalcSellSlider(new_chosen_min, new_chosen_max)
{
  // $.ajax({
  //   url : url_sellable_shares,
  //   method : "POST",
  //   data : {
  //     min_lim: min_limit,
  //     max_lim: max_limit, 
  //     chosen_min: new_chosen_min,
  //     chosen_max: new_chosen_max
  //   },
  //   async: false,
  //   success : function(data) {
  //     sellable_shares = data;

  //     $("#sell_num").slider("option", "max", sellable_shares);
  //     $("#sell_num_shares").val($("#sell_num").slider("value"));
      

  //     if(data === 0)
  //     {
  //       $("#not_available_error_sell").text("No available buy orders found");
  //       $("#not_available_error_sell").show();
  //       $("#sell_order").hide();
  //     }
  //     else
  //     {
  //       $("#sell_order").show();
  //       $("#not_available_error_sell").hide();
  //     }
  //   },
  //   error : function(data){

  //   }
  // });

  if(new_chosen_max < max_limit)
  {
    $("#sell_cost").val($("#sell_num").slider("value") * max);
  }
  if(new_chosen_min > min_limit)
  {
    $("#sell_cost").val($("#sell_num").slider("value") * min);
  }
  if((new_chosen_max == max_limit && new_chosen_min == min_limit) || (new_chosen_max < max_limit && new_chosen_min > min_limit))
  {
    $("#sell_cost").val($("#sell_num").slider("value") * $("#pps").text());
  }
}

$( function() {
  var url_max_limit = window.location.protocol + "//" + window.location.hostname + ":" + window.location.port + "/Hassner/backend/sliders/StockPrice.php";

  $.ajax({
    url : url_max_limit,
    method : "GET",
    async: false,
    success : function(data){
      max_limit = parseFloat(Math.ceil((data*2).toFixed(1)));
      //We allow users to set the min limit to be half the current stock price
      min_limit = parseFloat(Math.floor((data/2).toFixed(1)));
      if(max_limit < 1 || min_limit < 1)
      {
        step_value = 0.05;
      }
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
      step: step_value,
      slide: function( event, ui ) {
        min = ui.values[0];
        max = ui.values[1];
        if(min == min_limit && max == max_limit){
          $("#buy_tip").text("Order will be executed as market price");
          $("#buy_cost").val("$" + $("#buy_num").slider("value")*$("#pps").text());
          recalcSliderLimits(min, max);
        }
        else if (min > min_limit && max == max_limit){
          $("#buy_tip").text("The buy order will be executed as soon as the price is ≤  " + min);
          $("#buy_cost").val("$" + $("#buy_num").slider("value")*min);
          recalcSliderLimits(min, max);
        }
        else if (min > min_limit && max < max_limit){
          $("#buy_tip").text("The buy order will be executed as soon as the price is ≤  " + min + " or ≥ " + max);
          recalcSliderLimits(min, max);
        }
        else if (min == min_limit && max < max_limit){
          $("#buy_tip").text("The buy order will be executed as soon as the price is ≥ " + max);
          $("#buy_cost").val("$" + $("#buy_num").slider("value")*max);
          recalcSliderLimits(min, max);
        }
      }
    });

    // Sell slider init
    $( "#sell_limit" ).slider({
      range: true,
      min: min_limit,
      max: max_limit,
      values: [ min_limit, max_limit ],
      step: step_value,
      slide: function( event, ui ) {
        min = ui.values[0];
        max = ui.values[1];
        if(min == min_limit && max == max_limit){
          $("#sell_tip").text("Order will be executed as market price");
          $("#sell_cost").val("$" + $("#sell_num").slider("value")*$("#pps").text());
          recalcSellSlider(min, max);
        }
        else if (min > min_limit && max == max_limit){
          $("#sell_tip").text("The sell order will be executed as soon as the price is ≤ " + min);
          $("#sell_cost").val("$" + $("#sell_num").slider("value")*min);
          recalcSellSlider(min, max);
        }
        else if (min > min_limit && max < max_limit){
          $("#sell_tip").text("The sell order will be executed as soon as the price is ≤ " + min + " or ≥ " + max);
          recalcSellSlider(min, max);
        }
        else if (min == min_limit && max < max_limit){
          $("#sell_tip").text("The sell order will be executed as soon as the price is ≥ " + max);
          $("#sell_cost").val("$" + $("#sell_num").slider("value")*max);
          recalcSellSlider(min, max);
        }
      }
    });
    
    $.ajax({
      url : url_max_num_shares,
      method : "POST",
      data : {
        min_lim: min_limit,
        max_lim: max_limit, 
        chosen_min: min_limit,
        chosen_max: max_limit
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
      min: 0,
      max: max_num_of_shares,
      value: 0,
      step: 1,
      slide: function( event, ui ) {
        console.log(max_num_of_shares);
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
      method : "POST",
      data: {
        min_lim: min_limit,
        max_lim: max_limit, 
        chosen_min: min_limit,
        chosen_max: max_limit
      },
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
      min: 0,
      max: sellable_shares,
      value: 0,
      step: 1,
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
          market_price: $("#pps").text(),
          num_shares: $("#buy_num_shares").val(),
          cost: $('#buy_cost').val()
        },
        success : function(data){
          console.log(data);
          if(data === "Price Outdated")
          {
            $("#price_outdated").text("Price has changed, please refresh the page and try again");
          }
          else if(data === "SUCCESS")
          {
            window.location = window.location.protocol + "//" + window.location.hostname + ":" + window.location.port + "/Hassner/frontend/listener/Listener.php";
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
            window.location = window.location.protocol + "//" + window.location.hostname + ":" + window.location.port + "/Hassner/frontend/listener/Listener.php";
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
    recalcSliderLimits();
  } );