<table class="form-table">
    <tr>
        <th>
            <label for="slack_client_id"><?php _e('App Client ID', WPSlackSync::$text_domain) ?></label>
        </th>
        <td>
            <input name="_wpslacksync_client_id" type="text" id="slack_client_id" value="<?php echo get_option('_wpslacksync_client_id') ?>" data-saved-value="<?php echo get_option('_wpslacksync_client_id') ?>" class="regular-text">
            <p class="description" id="wpslacksync-clientid-description"><?php _e('Create application here:', WPSlackSync::$text_domain) ?> <a href="https://api.slack.com/applications/new" target="_blank"><?php _e('Slack New Application', WPSlackSync::$text_domain) ?></a></p>
        </td>
    </tr>
    <tr>
        <th>
            <label for="slack_client_secret"><?php _e('App Client Secret', WPSlackSync::$text_domain) ?></label>
        </th>
        <td>
            <input name="_wpslacksync_client_secret" type="text" id="slack_client_secret" value="<?php echo get_option('_wpslacksync_client_secret') ?>" data-saved-value="<?php echo get_option('_wpslacksync_client_secret') ?>" class="regular-text">
            <p class="description" id="wpslacksync-clientid-description"><?php _e('Issued when you create an application', WPSlackSync::$text_domain) ?></p>
        </td>
    </tr>
    <tr>
        <th>
            <label for="slack_token"><?php _e('API Token', WPSlackSync::$text_domain) ?></label>
        </th>
        <td>
            <input name="_wpslacksync_token" type="text" id="slack_token" value="<?php echo get_option('_wpslacksync_token') ?>" class="regular-text">
            <i class="fa fa-info-circle tooltip" aria-hidden="true" title="<?php _e('The API token is used to invite users on behalf of the channel owner and query messages in read-only mode.', WPSlackSync::$text_domain) ?>"></i>
            <p class="description" id="wpslacksync-token-description"><?php _e('Save changes, then', WPSlackSync::$text_domain) ?>&nbsp;<a href="#" id="wpslacksync-slack-authorize"><?php _e('Authorize with Slack', WPSlackSync::$text_domain) ?></a></p>
            <p class="description" style="color:red"><?php echo get_transient('_wpslacksync_oauth_access_admin_error') ?></p>
        </td>
    </tr>
    <tr>
        <td><?php submit_button(); ?></td>
    </tr>
</table>