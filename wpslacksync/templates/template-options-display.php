<table class="form-table">
    <tr>
        <th>
            <label for="slack_team_name"><?php _e('Team name', WPSlackSync::$text_domain) ?></label>
        </th>
        <td>
            <input name="_wpslacksync_team_name" type="text" id="slack_team_name" value="<?php echo get_option('_wpslacksync_team_name') ?>" class="regular-text">
            <i class="fa fa-info-circle tooltip" aria-hidden="true" title="<?php _e('The team name is shown above the invite form.', WPSlackSync::$text_domain) ?>"></i>
        </td>
    </tr>
    <tr>
        <th>
            <label for="slack_enter_name_invite_yn"><?php _e('Enter name for invite', WPSlackSync::$text_domain) ?></label>
        </th>
        <td class="checkbox_cl">
            <input name="_wpslacksync_enter_name_invite_yn" type="checkbox" id="slack_enter_name_invite_yn" <?php echo get_option('_wpslacksync_enter_name_invite_yn', 'on') == "on" ? 'checked' : '' ?>><i class="fa fa-info-circle tooltip" aria-hidden="true" title="<?php _e('The invite form will include input fields for first and last name in addition to the required email field.', WPSlackSync::$text_domain) ?>"></i>
            <p class="description"><?php _e('Include first and last name in invite form.', WPSlackSync::$text_domain) ?></p>
        </td>
    </tr>
    <tr>
        <th>
            <label for="slack_view_only_yn"><?php _e('View Only mode', WPSlackSync::$text_domain) ?></label>
        </th>
        <td class="checkbox_cl">
            <input name="_wpslacksync_view_only_yn" type="checkbox" id="slack_view_only_yn" <?php echo get_option('_wpslacksync_view_only_yn', '') == "on" ? 'checked' : '' ?>>
            <p class="description"><?php _e('Guests can view channels and messages before joining.', WPSlackSync::$text_domain) ?></p>
        </td>
    </tr>
    <tr>
        <th>
            <label><?php _e('Mode', WPSlackSync::$text_domain) ?></label>
        </th>
        <td class="radio_cl">
            <label><input name="_wpslacksync_mode" type="radio" value="invite" <?php echo get_option('_wpslacksync_mode') == "invite" ? 'checked' : '' ?>> <?php _e('Invite Mode', WPSlackSync::$text_domain) ?></label><i class="fa fa-info-circle tooltip" aria-hidden="true" title="<?php _e('The login to slack button will be hidden and the plugin chat will not be used.', WPSlackSync::$text_domain) ?>"></i>
            <p class="description"><?php _e('Guests only can get invited to your team.', WPSlackSync::$text_domain) ?></p>
            <br/>
            <label><input name="_wpslacksync_mode" type="radio" value="intern" <?php echo get_option('_wpslacksync_mode') == "intern" ? 'checked' : '' ?>> <?php _e('Intern Mode', WPSlackSync::$text_domain) ?></label><i class="fa fa-info-circle tooltip" aria-hidden="true" title="<?php _e('Will hide the invite form. Only the login to slack button will be shown. Chat is enabled.', WPSlackSync::$text_domain) ?>"></i>
            <p class="description"><?php _e('Only the people already on team will have access.', WPSlackSync::$text_domain) ?></p>
            <br/>
            <label><input name="_wpslacksync_mode" type="radio" value="full" <?php echo get_option('_wpslacksync_mode', 'full') == "full" ? 'checked' : '' ?>> <?php _e('Full Mode', WPSlackSync::$text_domain) ?></label><i class="fa fa-info-circle tooltip" aria-hidden="true" title="<?php _e('Both the invite form and the login to slack button will be shown. Chat is enabled.', WPSlackSync::$text_domain) ?>"></i>
            <p class="description"><?php _e('Both new and current users on aboard.', WPSlackSync::$text_domain) ?></p>
            <br/>
            <label><input name="_wpslacksync_mode" type="radio" value="passive" <?php echo get_option('_wpslacksync_mode') == "passive" ? 'checked' : '' ?>> <?php _e('Passive Mode', WPSlackSync::$text_domain) ?></label><i class="fa fa-info-circle tooltip" aria-hidden="true" title="<?php _e('To be used with view-only mode. No join this chat button is shown. Only reading is possible', WPSlackSync::$text_domain) ?>"></i>
            <p class="description"><?php _e('Disable both login and invite.', WPSlackSync::$text_domain) ?></p>
            <br/>
        </td>
    </tr>
    <tr>
        <th>
            <label for="slack_enable_private_channels_yn"><?php _e('Enable private channels', WPSlackSync::$text_domain) ?></label>
        </th>
        <td class="checkbox_cl">
            <input name="_wpslacksync_enable_private_channels_yn" type="checkbox" id="slack_enable_private_channels_yn" <?php echo get_option('_wpslacksync_enable_private_channels_yn', '') == "on" ? 'checked' : '' ?>><i class="fa fa-info-circle tooltip" aria-hidden="true" title="<?php _e('All private channels a user belongs to will be available.', WPSlackSync::$text_domain) ?>"></i>
            <p class="description"><?php _e('Users see and can chat in private channels they belong to.', WPSlackSync::$text_domain) ?></p>
        </td>
    </tr>
    <tr class="enable_upload_profile_photo_row">
        <th>
            <label for="slack_wpslacksync_enable_upload_profile_photo_yn"><?php _e('Enable upload profile photo', WPSlackSync::$text_domain) ?></label>
        </th>
        <td class="checkbox_cl">
            <input name="_wpslacksync_enable_upload_profile_photo_yn" type="checkbox" id="slack_wpslacksync_enable_upload_profile_photo_yn" <?php echo get_option('_wpslacksync_enable_upload_profile_photo_yn', 'on') == "on" ? 'checked' : '' ?>><i class="fa fa-info-circle tooltip" aria-hidden="true" title="<?php _e('Click on user picture below channel list to find the menu. Set photo will only appear with this option activated and when the browser size is large enough. This is to prevent usability issues for mobile users.', WPSlackSync::$text_domain) ?>"></i>
            <p class="description"><?php _e('Users can update their profile picture.', WPSlackSync::$text_domain) ?></p>
        </td>
    </tr>
    <tr>
        <th>
            <label for="slack_show_timestamp_yn"><?php _e('View message time', WPSlackSync::$text_domain) ?></label>
        </th>
        <td class="checkbox_cl">
            <input name="_wpslacksync_show_timestamp_yn" type="checkbox" id="slack_show_timestamp_yn" <?php echo get_option('_wpslacksync_show_timestamp_yn', 'on') == "on" ? 'checked' : '' ?>><i class="fa fa-info-circle tooltip" aria-hidden="true" title="<?php _e('Posted messages in the chat will show the time when they were posted.', WPSlackSync::$text_domain) ?>"></i>
        </td>
    </tr>
    <tr>
        <td><?php submit_button(); ?></td>
    </tr>
</table>