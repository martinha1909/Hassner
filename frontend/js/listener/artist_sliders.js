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
      max_num_of_shares = data;
      $("#buy_num").slider("option", "max", data);
      $("#buy_num_shares").val($("#buy_num").slider("value"));
      if(new_chosen_min == min_limit && new_chosen_max < max_limit)
      {
        $("#buy_cost").val($("#buy_num").slider("value") * new_chosen_max);
      }
      if(new_chosen_min > min_limit && new_chosen_max == max_limit)
      {
        $("#buy_cost").val($("#buy_num").slider("value") * new_chosen_min);
      }
      if((new_chosen_max == max_limit && new_chosen_min == min_limit))
      {
        $("#buy_cost").val($("#buy_num").slider("value") * $("#pps").text());
      }
      if(new_chosen_max < max_limit && new_chosen_min > min_limit)
      {
        $("#buy_cost").val($("#buy_num").slider("value") * new_chosen_max);
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
  if(new_chosen_min == min_limit && new_chosen_max < max_limit)
  {
    $("#sell_cost").val($("#sell_num").slider("value") * new_chosen_max);
  }
  if(new_chosen_min > min_limit && new_chosen_max == max_limit)
  {
    $("#sell_cost").val($("#sell_num").slider("value") * new_chosen_min);
  }
  if((new_chosen_max == max_limit && new_chosen_min == min_limit))
  {
    $("#sell_cost").val($("#sell_num").slider("value") * $("#pps").text());
  }
  if(new_chosen_max < max_limit && new_chosen_min > min_limit)
  {
    $("#sell_cost").val($("#sell_num").slider("value") * new_chosen_max);
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

  $(this).find('#buy_limit_val').keypress(function(e) {
    if(e.which == 13) {
      if($("#buy_limit_val").val())
      {
        if(!isNaN($("#buy_limit_val").val()))
        {
          //Round to 1 decimal
          $("#buy_limit_val").val(Math.round($("#buy_limit_val").val() * 10) / 10);
          if($("#buy_limit_val").val() <= min_limit)
          {
            $("#buy_limit_val").val("None");
          }
          else if($("#buy_limit_val").val() > $("#buy_limit").slider("values", 1))
          {
            $("#buy_limit_val").val($("#buy_limit").slider("values", 1));
          }
        }
        else
        {
          $("#buy_limit_val").val("None");
        }
      }
      else
      {
        $("#buy_limit_val").val("None");
      }
      if($("#buy_limit_val").val() != "None")
      {
        $("#buy_limit").slider("values", 0, $("#buy_limit_val").val());
        if($("#buy_limit_val").val() < parseFloat($("#pps").text()))
        {
          $("#buy_limit_val").removeClass("suc-msg");
          $("#buy_limit_val").addClass("error-msg");
        }
        else
        {
          $("#buy_limit_val").removeClass("error-msg");
          $("#buy_limit_val").addClass("suc-msg");
        }
        if($("#buy_limit").slider("values", 1) < max_limit)
        {
          $("#buy_tip").text("The buy order will be executed as soon as the price is ≤  " + $("#buy_limit_val").val() + " or ≥ " + $("#buy_limit").slider("values", 1));
        }
        else
        {
          $("#buy_tip").text("The buy order will be executed as soon as the price is ≤  " + $("#buy_limit_val").val());
        }
        recalcSliderLimits($("#buy_limit_val").val(), $("#buy_limit").slider("values", 1));
      }
      else
      {
        $("#buy_limit").slider("values", 0, min_limit);
        $("#buy_limit_val").removeClass("suc-msg");
        $("#buy_limit_val").removeClass("error-msg");
        if($("#buy_limit").slider("values", 1) < max_limit)
        {
          $("#buy_tip").text("The buy order will be executed as soon as the price is ≥ " + $("#buy_limit").slider("values", 1));
        }
        else
        {
          $("#buy_tip").text("Order will be executed as market price");
        }
        recalcSliderLimits(min_limit, $("#buy_limit").slider("values", 1));
      }
    }
  });

  $(this).find('#buy_stop_val').keypress(function(e) {
    if(e.which == 13) {
      if($("#buy_stop_val").val())
      {
        if(!isNaN($("#buy_stop_val").val()))
        {
          //Round to 1 decimal
          $("#buy_stop_val").val(Math.round($("#buy_stop_val").val() * 10) / 10);
          if($("#buy_stop_val").val() >= max_limit)
          {
            $("#buy_stop_val").val("None");
          }
          else if($("#buy_stop_val").val() < $("#buy_limit").slider("values", 0))
          {
            $("#buy_stop_val").val($("#buy_limit").slider("values", 0));
          }
        }
        else
        {
          $("#buy_stop_val").val("None");
        }
      }
      else
      {
        $("#buy_stop_val").val("None");
      }
      if($("#buy_stop_val").val() != "None")
      {
        $("#buy_limit").slider("values", 1, $("#buy_stop_val").val());
        if($("#buy_stop_val").val() > parseFloat($("#pps").text()))
        {
          $("#buy_stop_val").removeClass("suc-msg");
          $("#buy_stop_val").addClass("error-msg");
        }
        else
        {
          $("#buy_stop_val").removeClass("error-msg");
          $("#buy_stop_val").addClass("suc-msg");
        }
        if($("#buy_limit").slider("values", 0) > min_limit)
        {
          $("#buy_tip").text("The buy order will be executed as soon as the price is ≤ " + $("#buy_limit").slider("values", 0) + " or ≥ " + $("#buy_stop_val").val());
        }
        else
        {
          $("#buy_tip").text("The buy order will be executed as soon as the price is ≥ " + $("#buy_stop_val").val());
        }
        recalcSliderLimits($("#buy_limit").slider("values", 0), $("#buy_stop_val").val());
      }
      else
      {
        $("#buy_limit").slider("values", 1, max_limit);
        $("#buy_stop_val").removeClass("error-msg");
          $("#buy_stop_val").removeClass("suc-msg");
        if($("#buy_limit").slider("values", 0) > min_limit)
        {
          $("#buy_tip").text("The buy order will be executed as soon as the price is ≤ " + $("#buy_limit").slider("values", 0));
        }
        else
        {
          $("#buy_tip").text("Order will be executed as market price");
        }
        recalcSliderLimits($("#buy_limit").slider("values", 0), max_limit);
      }
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
          $("#buy_limit_val").removeClass("error-msg");
          $("#buy_limit_val").removeClass("suc-msg");
          $("#buy_limit_val").val("None");
          $("#buy_stop_val").removeClass("error-msg");
          $("#buy_stop_val").removeClass("suc-msg");
          $("#buy_stop_val").val("None");
          recalcSliderLimits(min, max);
        }
        else if (min > min_limit && max == max_limit){
          $("#buy_tip").text("The buy order will be executed as soon as the price is ≤  " + min);
          $("#buy_cost").val("$" + $("#buy_num").slider("value")*min);
          if(min > $("#pps").text())
          {
            $("#buy_limit_val").removeClass("error-msg");
            $("#buy_limit_val").addClass("suc-msg");
            $("#buy_limit_val").val(min);
          }
          else
          {
            $("#buy_limit_val").removeClass("suc-msg");
            $("#buy_limit_val").addClass("error-msg");
            $("#buy_limit_val").val(min);
          }
          $("#buy_stop_val").val("None");
          recalcSliderLimits(min, max);
        }
        else if (min > min_limit && max < max_limit){
          $("#buy_tip").text("The buy order will be executed as soon as the price is ≤  " + min + " or ≥ " + max);
          $("#buy_cost").val("$" + $("#buy_num").slider("value")*max);
          if(min > $("#pps").text())
          {
            $("#buy_limit_val").removeClass("error-msg");
            $("#buy_limit_val").addClass("suc-msg");
            $("#buy_limit_val").val(min);
          }
          else
          {
            $("#buy_limit_val").removeClass("suc-msg");
            $("#buy_limit_val").addClass("error-msg");
            $("#buy_limit_val").val(min);
          }
          if(max < $("#pps").text())
          {
            $("#buy_stop_val").removeClass("suc-msg");
            $("#buy_stop_val").addClass("error-msg");
            $("#buy_stop_val").val(max);
          }
          else
          {
            $("#buy_stop_val").removeClass("error-msg");
            $("#buy_stop_val").addClass("suc-msg");
            $("#buy_stop_val").val(max);
          }
          // $("#buy_stop_val").val(max);
          recalcSliderLimits(min, max);
        }
        else if (min == min_limit && max < max_limit){
          $("#buy_tip").text("The buy order will be executed as soon as the price is ≥ " + max);
          $("#buy_cost").val("$" + $("#buy_num").slider("value")*max);
          $("#buy_limit_val").val("None");
          if(max < $("#pps").text())
          {
            $("#buy_stop_val").removeClass("suc-msg");
            $("#buy_stop_val").addClass("error-msg");
            $("#buy_stop_val").val(max);
          }
          else
          {
            $("#buy_stop_val").removeClass("error-msg");
            $("#buy_stop_val").addClass("suc-msg");
            $("#buy_stop_val").val(max);
          }
          recalcSliderLimits(min, max);
        }
      }
    });

    $(this).find('#sell_limit_val').keypress(function(e) {
      if(e.which == 13) {
        if($("#sell_limit_val").val())
        {
          if(!isNaN($("#sell_limit_val").val()))
          {
            //Round to 1 decimal
            $("#sell_limit_val").val(Math.round($("#sell_limit_val").val() * 10) / 10);
            if($("#sell_limit_val").val() >= max_limit)
            {
              $("#sell_limit_val").val("None");
            }
            else if($("#sell_limit_val").val() < $("#sell_limit").slider("values", 0))
            {
              $("#sell_limit_val").val($("#sell_limit").slider("values", 0));
            }
          }
          else
          {
            $("#sell_limit_val").val("None");
          }
        }
        else
        {
          $("#sell_limit_val").val("None");
        }
        if($("#sell_limit_val").val() != "None")
        {
          $("#sell_limit").slider("values", 1, $("#sell_limit_val").val());
          $("#sell_cost").val($("#sell_limit_val").val() * $("#sell_num").slider("value"));
          if($("#sell_limit_val").val() > parseFloat($("#pps").text()))
          {
            $("#sell_limit_val").removeClass("suc-msg");
            $("#sell_limit_val").addClass("error-msg");
          }
          else
          {
            $("#sell_limit_val").removeClass("error-msg");
            $("#sell_limit_val").addClass("suc-msg");
          }
          if($("#sell_limit").slider("values", 0) > min_limit)
          {
            $("#sell_tip").text("The sell order will be executed as soon as the price is ≤ " + $("#sell_limit").slider("values", 0) + " or ≥ " + $("#sell_limit_val").val());
          }
          else
          {
            $("#sell_tip").text("The sell order will be executed as soon as the price is ≥ " + $("#sell_limit_val").val());
          }
          recalcSellSlider($("#sell_limit").slider("values", 0), $("#sell_limit_val").val());
        }
        else
        {
          if($("#sell_limit").slider("values", 0) != min_limit)
          {
            $("#sell_cost").val($("#sell_num").slider("value") * $("#sell_limit").slider("values", 0));
          }
          else
          {
            $("#sell_cost").val($("#sell_num").slider("value") * $("#pps").text());
          }
          $("#sell_limit").slider("values", 1, max_limit);
          $("#sell_limit_val").removeClass("error-msg");
          $("#sell_limit_val").removeClass("suc-msg");
          if($("#sell_limit").slider("values", 0) > min_limit)
          {
            $("#sell_tip").text("The sell order will be executed as soon as the price is ≤ " + $("#sell_limit").slider("values", 0));
          }
          else
          {
            $("#sell_tip").text("Order will be executed as market price");
          }
          recalcSellSlider($("#sell_limit").slider("values", 0), max_limit);
        }
      }
    });
  
    $(this).find('#sell_stop_val').keypress(function(e) {
      if(e.which == 13) {
        if($("#sell_stop_val").val())
        {
          if(!isNaN($("#sell_stop_val").val()))
          {
            //Round to 1 decimal
            $("#sell_stop_val").val(Math.round($("#sell_stop_val").val() * 10) / 10);
            if($("#sell_stop_val").val() <= min_limit)
            {
              $("#sell_stop_val").val("None");
            }
            else if($("#sell_stop_val").val() > $("#sell_limit").slider("values", 1))
            {
              $("#sell_stop_val").val($("#sell_limit").slider("values", 1));
            }
          }
          else
          {
            $("#sell_stop_val").val("None");
          }
        }
        else
        {
          $("#sell_stop_val").val("None");
        }
        if($("#sell_stop_val").val() != "None")
        {
          $("#sell_limit").slider("values", 0, $("#sell_stop_val").val());
          if($("#sell_limit").slider("values", 1) != max_limit)
          {
            $("#sell_cost").val($("#sell_num").slider("value") * $("#sell_limit").slider("values", 1));
          }
          else
          {
            $("#sell_cost").val($("#sell_num").slider("value") * $("#sell_stop_val").val());
          }
          if($("#sell_stop_val").val() < parseFloat($("#pps").text()))
          {
            $("#sell_stop_val").removeClass("suc-msg");
            $("#sell_stop_val").addClass("error-msg");
          }
          else
          {
            $("#sell_stop_val").removeClass("error-msg");
            $("#sell_stop_val").addClass("suc-msg");
          }
          if($("#sell_limit").slider("values", 1) < max_limit)
          {
            $("#sell_tip").text("The sell order will be executed as soon as the price is ≤  " + $("#sell_stop_val").val() + " or ≥ " + $("#sell_limit").slider("values", 1));
          }
          else
          {
            $("#sell_tip").text("The sell order will be executed as soon as the price is ≤ " + $("#sell_stop_val").val());
          }
          recalcSellSlider($("#sell_stop_val").val(), $("#sell_limit").slider("values", 1));
        }
        else
        {
          if($("#sell_limit").slider("values", 1) != max_limit)
          {
            $("#sell_cost").val($("#sell_num").slider("value") * $("#sell_limit").slider("values", 1));
          }
          else
          {
            $("#sell_cost").val($("#sell_num").slider("value") * $("#pps").text());
          }
          $("#sell_limit").slider("values", 0, min_limit);
          $("#sell_stop_val").removeClass("error-msg");
          $("#sell_stop_val").removeClass("suc-msg");
          if($("#sell_limit").slider("values", 1) < max_limit)
          {
            $("#sell_tip").text("The sell order will be executed as soon as the price is ≥ " + $("#sell_limit").slider("values", 1));
          }
          else
          {
            $("#sell_tip").text("Order will be executed as market price");
          }
          recalcSellSlider(min_limit, $("#sell_limit").slider("values", 1));
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
          $("#sell_limit_val").removeClass("error-msg");
          $("#sell_limit_val").removeClass("suc-msg");
          $("#sell_limit_val").val("None");
          $("#sell_stop_val").removeClass("error-msg");
          $("#sell_stop_val").removeClass("suc-msg");
          $("#sell_stop_val").val("None");
          recalcSellSlider(min, max);
        }
        else if (min > min_limit && max == max_limit){
          $("#sell_limit_val").removeClass("error-msg");
          $("#sell_limit_val").removeClass("suc-msg");
          $("#sell_limit_val").val("None");
          $("#sell_tip").text("The sell order will be executed as soon as the price is ≤ " + min);
          $("#sell_cost").val("$" + $("#sell_num").slider("value")*min);
          if(min > $("#pps").text())
          {
            $("#sell_stop_val").removeClass("error-msg");
            $("#sell_stop_val").addClass("suc-msg");
            $("#sell_stop_val").val(min);
          }
          else
          {
            $("#sell_stop_val").removeClass("suc-msg");
            $("#sell_stop_val").addClass("error-msg");
            $("#sell_stop_val").val(min);
          }
          recalcSellSlider(min, max);
        }
        else if (min > min_limit && max < max_limit){
          $("#sell_tip").text("The sell order will be executed as soon as the price is ≤ " + min + " or ≥ " + max);
          if(min > $("#pps").text())
          {
            $("#sell_stop_val").removeClass("error-msg");
            $("#sell_stop_val").addClass("suc-msg");
            $("#sell_stop_val").val(min);
          }
          else
          {
            $("#sell_stop_val").removeClass("suc-msg");
            $("#sell_stop_val").addClass("error-msg");
            $("#sell_stop_val").val(min);
          }
          if(max < $("#pps").text())
          {
            $("#sell_limit_val").removeClass("suc-msg");
            $("#sell_limit_val").addClass("error-msg");
            $("#sell_limit_val").val(max);
          }
          else
          {
            $("#sell_limit_val").removeClass("error-msg");
            $("#sell_limit_val").addClass("suc-msg");
            $("#sell_limit_val").val(max);
          }
          recalcSellSlider(min, max);
        }
        else if (min == min_limit && max < max_limit){
          $("#sell_stop_val").removeClass("error-msg");
          $("#sell_stop_val").removeClass("suc-msg");
          $("#sell_stop_val").val("None");
          $("#sell_tip").text("The sell order will be executed as soon as the price is ≥ " + max);
          $("#sell_cost").val("$" + $("#sell_num").slider("value")*max);
          if(max < $("#pps").text())
          {
            $("#sell_limit_val").removeClass("suc-msg");
            $("#sell_limit_val").addClass("error-msg");
            $("#sell_limit_val").val(max);
          }
          else
          {
            $("#sell_limit_val").removeClass("error-msg");
            $("#sell_limit_val").addClass("suc-msg");
            $("#sell_limit_val").val(max);
          }
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

    $(this).find('#buy_num_shares').keypress(function(e) {
      if(e.which == 13) {
        if($("#buy_num_shares").val())
        {
          if($("#buy_num_shares").val() > max_num_of_shares)
          {
            $("#buy_num_shares").val(max_num_of_shares);
          }
        }
        else
        {
          $("#buy_num_shares").val(0);
        }
        $("#buy_num").slider("option", "value", $("#buy_num_shares").val());
        if($("#buy_limit").slider("values", 0) > min_limit && $("#buy_limit").slider("values", 1) == max_limit)
        {
          $("#buy_cost").val("$" + $("#buy_num_shares").val()*$("#buy_limit").slider("values", 0));
        }
        else if($("#buy_limit").slider("values", 0) == min_limit && $("#buy_limit").slider("values", 1) == max_limit)
        {
          $("#buy_cost").val("$" + $("#buy_num_shares").val()*$("#pps").text());
        }
        else if($("#buy_limit").slider("values", 0) > min_limit && $("#buy_limit").slider("values", 1) < max_limit)
        {
          $("#buy_cost").val("$" + $("#buy_num_shares").val()*$("#buy_limit").slider("values", 1));
        }
        else if($("#buy_limit").slider("values", 0) == min_limit && $("#buy_limit").slider("values", 1) < max_limit)
        {
          $("#buy_cost").val("$" + $("#buy_num_shares").val()*$("#buy_limit").slider("values", 1));
        }
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
        var min_limit_top = $("#buy_limit").slider("values", 0);
        var max_limit_top = $("#buy_limit").slider("values", 1);
        if(min_limit_top == min_limit && max_limit_top == max_limit)
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
        else if(min_limit_top > min_limit && max_limit_top < max_limit)
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

    $(this).find('#sell_num_shares').keypress(function(e) {
      if(e.which == 13) {
        if($("#sell_num_shares").val())
        {
          if($("#sell_num_shares").val() > sellable_shares)
          {
            $("#sell_num_shares").val(sellable_shares);
          }
        }
        else
        {
          $("#sell_num_shares").val(0);
        }
        $("#sell_num").slider("option", "value", $("#sell_num_shares").val());
        if($("#sell_limit").slider("values", 0) > min_limit && $("#sell_limit").slider("values", 1) == max_limit)
        {
          $("#sell_cost").val("$" + $("#sell_num_shares").val()*$("#sell_limit").slider("values", 0));
        }
        else if($("#sell_limit").slider("values", 0) == min_limit && $("#sell_limit").slider("values", 1) == max_limit)
        {
          $("#sell_cost").val("$" + $("#sell_num_shares").val()*$("#pps").text());
        }
        else if($("#sell_limit").slider("values", 0) > min_limit && $("#sell_limit").slider("values", 1) < max_limit)
        {
          $("#sell_cost").val("$" + $("#sell_num_shares").val()*$("#sell_limit").slider("values", 1));
        }
        else if($("#sell_limit").slider("values", 0) == min_limit && $("#sell_limit").slider("values", 1) < max_limit)
        {
          $("#sell_cost").val("$" + $("#sell_num_shares").val()*$("#sell_limit").slider("values", 1));
        }
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
        var min_limit_top = $("#sell_limit").slider("values", 0);
        var max_limit_top = $("#sell_limit").slider("values", 1);
        if(min_limit_top == min_limit && max_limit_top == max_limit)
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
        else if(min_limit_top > min_limit && max_limit_top < max_limit)
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

    $("#buy_order").click(function(event){
      if(!event.detail || event.detail == 1)
      {
        var min_limit_top = $("#buy_limit").slider("values", 0);
        var max_limit_top = $("#buy_limit").slider("values", 1);
        console.log(min_limit_top);
        console.log(max_limit_top);
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
              else if(data === "NUM_OF_SHARES_INVALID")
              {
                $("#price_outdated").text("Orders with quantity of 0 not allowed");
              }
              else if (data === "BALANCE_OUTDATED")
              {
                $("#price_outdated").text("Your balance has changed, please refresh and try again");
              }
              else if(data === "BUYABLE_OUTDATED")
              {
                $("#price_outdated").text("Data has changed, please refresh and try again");
              }
              else if(data === "CANNOT_CREATE_BUY")
              {
                $("#price_outdated").text("Not enough balance due to other open buy orders");
              }
              else if(data === "SUCCESS")
              {
                window.location = window.location.protocol + "//" + window.location.hostname + ":" + window.location.port + "/Hassner/frontend/listener/Listener.php";
              }
            },
            error : function(data){

            }
          });
        }
        else
        {
          $("#buy_order").prop('disabled', true);
        }
    })

    $("#sell_order").click(function(event){
      if(!event.detail || event.detail == 1)
      {
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
            if(data === "Price Outdated")
            {
              $("#price_outdated").text("Price has changed, please refresh the page and try again");
            }
            else if(data === "NUM_OF_SHARES_INVALID")
            {
              $("#price_outdated").text("Orders with quantity of 0 not allowed");
            }
            else if(data == "SELLABLE_OUTDATED")
            {
              $("#price_outdated").text("Data outdated, please refresh and try again");
            }
            else if(data === "SUCCESS")
            {
              window.location = window.location.protocol + "//" + window.location.hostname + ":" + window.location.port + "/Hassner/frontend/listener/Listener.php";
            }
          },
          error : function(data){

          }
        });
      }
      else
      {
        $("#sell_order").prop('disabled', true);
      }
    })
    $( "#sell_num_shares" ).val($("#sell_num").slider("value"));
    $( "#buy_num_shares" ).val($("#buy_num").slider("value"));
    $("#buy_cost").val("$" + $("#buy_num").slider("value")*$("#pps").text());
    $("#sell_cost").val("$" + $("#sell_num").slider("value")*$("#pps").text());
  } );