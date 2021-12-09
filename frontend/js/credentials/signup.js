$(document).ready(function() {
    $(".artistRadio").hide();
    $('input[type="radio"]').click(function(){
        var inputValue = $(this).attr("value");
        if(inputValue == "artist"){
            $(".artistRadio").show();
        }
        else{
            $(".artistRadio").hide();
        }
    });
});