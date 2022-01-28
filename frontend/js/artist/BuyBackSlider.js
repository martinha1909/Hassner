$( function() {  

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


    // Accoridon init
    $( "#buy_accordion" ).accordion({
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