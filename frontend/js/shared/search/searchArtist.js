$( function() {
    $('#search_artist').each(function() {
        $(this).find('#submit_search_form').keypress(function(e) {
            if(e.which == 10 || e.which == 13) {
                this.form.submit();
            }
        });
    });
});