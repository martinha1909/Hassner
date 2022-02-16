//form group
var campaign_commence_form = document.getElementById('create_campaign_form');

//button group
var campaign_commence_btn = document.getElementById('commence_campaign_btn');

function disableButton(button)
{
    // Disable the submit button
    button.setAttribute('disabled', 'disabled');

    // Change the "Submit" text
    button.value = 'Please wait...';
}

campaign_commence_form.addEventListener('submit', function() {
    disableButton(campaign_commence_btn);
 }, false);