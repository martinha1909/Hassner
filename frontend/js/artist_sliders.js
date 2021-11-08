$( function() {  
    // Buy slider init
    $( "#buy_slider" ).slider({
      range: true,
      min: 0,
      max: 500,
      values: [ 75, 300 ], // TODO: ajax Query db, get min and max shares that can be bought/sold.
      slide: function( event, ui ) {
        $( "#buy_min" ).val(ui.values[0]);
        $( "#buy_max" ).val(ui.values[1]);
      }
    });
    $( "#buy_min" ).val($("#buy_slider").slider("values", 0));
    $( "#buy_max" ).val($("#buy_slider").slider("values", 1));

    $("#buy_min").change(function() {
      $("#buy_slider").slider("values",0,$(this).val());
    });
    $("#buy_max").change(function() {
      $("#buy_slider").slider("values",1,$(this).val());
    });


    // Sell slider init
    $( "#sell_slider" ).slider({
      range: true,
      min: 0,
      max: 500,
      values: [ 75, 300 ], // TODO: ajax Query db, get min and max shares that can be bought/sold.
      slide: function( event, ui ) {
        $( "#sell_min" ).val(ui.values[0]);
        $( "#sell_max" ).val(ui.values[1]);
      }
    });
    $( "#sell_min" ).val($("#sell_slider").slider("values", 0));
    $( "#sell_max" ).val($("#sell_slider").slider("values", 1));

    $("#sell_min").change(function() {
      $("#sell_slider").slider("values",0,$(this).val());
    });
    $("#sell_max").change(function() {
      $("#sell_slider").slider("values",1,$(this).val());
    });

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