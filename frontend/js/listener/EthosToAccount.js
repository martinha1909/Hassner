var personalPageSelected = true;

function toPersonalPage()
{
    if(personalPageSelected)
    {
        $("#ethos_page_content").hide();
        $("#personal_page_content").show();
        personalPageSelected = false;
    }
    else
    {
        $("#ethos_page_content").show();
        $("#personal_page_content").hide();
        personalPageSelected = true;
    }
}

$( function() {
    $("#ethos_page_account_btn").click(toPersonalPage);
});