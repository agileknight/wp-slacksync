/* global ajaxurl, wpslacksync_name_in_invite, _wpslacksync_invite_l10n */

jQuery(document).on('click', '#wpslacksync-get-invite', function (e) {
    var fname = '';
    var lname = '';
    var email = jQuery('#wpslacksync-form input[name="email"]').val();

    if (wpslacksync_name_in_invite) {
        fname = jQuery('#wpslacksync-form input[name="fname"]').val();
        lname = jQuery('#wpslacksync-form input[name="lname"]').val();
        if (fname === "" || email === "") {
            display_error(_wpslacksync_invite_l10n.empty_name_email);
            return;
        }
    }

    if (!wpslacksync.app.validateEmail(email)) {
        display_error(_wpslacksync_invite_l10n.invalid_email);
        return;
    }

    var consentCheckboxes = jQuery('.wpslacksync-consent-mandatory');
    var anyConsentMissing = false;
    consentCheckboxes.each(function(index, checkbox) {
        if (!checkbox.checked) {
            jQuery(checkbox).parent().addClass('wpslacksync-consent-required-error');
            anyConsentMissing = true;
        } else {
            jQuery(checkbox).parent().removeClass('wpslacksync-consent-required-error');
        }
    });

    if (anyConsentMissing) {
        display_error(_wpslacksync_invite_l10n.consent_required);
        return;
    }

    wpslacksync.app.sendInvitation(fname, lname, email);
});
