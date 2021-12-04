jQuery(document).on('click', '.nav-tab-wrapper a', function ()
{
    /*Add & Remove active class to menu*/
    jQuery('.nav-tab.nav-tab-active').removeClass('nav-tab-active');
    jQuery(this).addClass('nav-tab-active');

    /*Active current content*/    
    jQuery('._wpslacksync_currenttab').attr('value',jQuery(this).attr('id'));
    jQuery('.tabs-content').addClass('hidden');
    jQuery('.tabs-content').eq(jQuery(this).index()).removeClass('hidden');
});

jQuery(document).on('blur', '#slack_token', function () {
    hideMessages();
    var token = jQuery('#slack_token').val();
    if (token != "") {
        getTeamInfo();
    }
});
jQuery(document).on('click', '#wpslacksync-autoload-team-details', function () {
    hideMessages();
    var token = jQuery('#slack_token').val();
    if (token == "") {
        jQuery('#wpslacksync-team-details-invalid-token').fadeOut();
        jQuery('#wpslacksync-team-details-description').fadeIn().fadeOut().fadeIn();
    } else {
        getTeamInfo();
    }
});

jQuery(document).on('click', '#wpslacksync-slack-authorize', function () {
    var clientId = jQuery('#slack_client_id').val();
    var clientSecret= jQuery('#slack_client_secret').val();
    var savedClientId = jQuery('#slack_client_id').data('saved-value');
    var savedClientSecret = jQuery('#slack_client_secret').data('saved-value');
    if (!clientId || !clientSecret || (clientId != savedClientId) || (clientSecret != savedClientSecret)) {
        alert('Save Client ID and Client Secret first!');
        return;
    }
    var params = {
        client_id: clientId,
        redirect_uri: location.protocol + '//' + location.host + location.pathname + '?page=wpslacksync-settings',
        scope: 'identify client',
        state: 'unused',
    };
    window.location = 'https://slack.com/oauth/authorize?' + jQuery.param(params);
});

jQuery(document).ready(function() {
    jQuery('.wpslacksync_menu .color-field').colorpicker();
    jQuery('.wpslacksync_menu .tooltip').tooltip();
});

function getTeamInfo() {
    hideMessages();
    var code = jQuery('#slack_token').val();
    jQuery.ajax({
        url: 'https://slack.com/api/team.info',
        type: 'POST',
        data: {token: code},
        success: function (data, textStatus, jqXHR) {
            if (data['ok']) {
                jQuery('#slack_team').val(data['team']['domain']);
                jQuery('#slack_team_id').val(data['team']['id']);
                hideMessages();
            } else {
                jQuery('#wpslacksync-team-details-invalid-token').fadeIn().fadeOut().fadeIn();
            }
        }
    });
}

function hideMessages() {
    jQuery('#wpslacksync-team-details-description').fadeOut();
    jQuery('#wpslacksync-team-details-invalid-token').fadeOut();
}

function getParameterByName(name) {
    name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
    var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
            results = regex.exec(location.search);
    return results === null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
}