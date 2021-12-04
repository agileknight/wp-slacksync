<table class="form-table">
<tr>
    <th>
        <label for="consent_html"><?php _e('Consent HTML', WPSlackSync::$text_domain) ?></label>
    </th>
    <td>
        <textarea rows="20" cols="100" name="_wpslacksync_consent_html" type="text" id="consent_html"><?php echo get_option('_wpslacksync_consent_html', '') ?></textarea><br /><i class="fa fa-info-circle tooltip" aria-hidden="true" title="<?php _e('Custom HTML to be included above the invite button. Checkboxes with class wpslacksync-consent-mandatory will be required to be checked before the invite is sent.', WPSlackSync::$text_domain) ?>"></i>
    </td>
</tr>
<tr>
    <th>
        <label for="include_consent_html_yn"><?php _e('Include consent HTML', WPSlackSync::$text_domain) ?></label>
    </th>
    <td>
        <input name="_wpslacksync_include_consent_html_yn" type="checkbox" id="include_consent_html_yn" <?php echo get_option('_wpslacksync_include_consent_html_yn', '') == "on" ? 'checked' : '' ?>>
        <p class="description"><?php _e('Include specified consent HTML in invite form.', WPSlackSync::$text_domain) ?></p>
    </td>
</tr>
<tr>
    <td><?php submit_button(); ?></td>
</tr>
</table>