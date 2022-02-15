var campaign_commence_form = document.getElementById('create_campaign_form');
var campaign_commence_btn = document.getElementById('commence_campaign_btn');

campaign_commence_form.addEventListener('submit', function() {

    // Disable the submit button
    campaign_commence_btn.setAttribute('disabled', 'disabled');
 
    // Change the "Submit" text
    campaign_commence_btn.value = 'Please wait...';
             
 }, false);