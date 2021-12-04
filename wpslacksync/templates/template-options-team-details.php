<table class="form-table">
    <tr>
        <th>
    <h3 class="title"><?php _e('Team details', WPSlackSync::$text_domain) ?></h3>
</th>
<td class="auto_fill">
    <div id="wpslacksync-autoload-team-details" class="button"><?php _e('Auto Fill', WPSlackSync::$text_domain) ?></div>
    <i class="fa fa-info-circle tooltip" aria-hidden="true" title="<?php _e('Please click on Auto Fill. This verifies the entered team test token is correct and should automatically fill the team name and team id of the target slack team.', WPSlackSync::$text_domain) ?>"></i>
    <p class="description" id="wpslacksync-team-details-description" style="display: none;"><?php _e('Type token first.', WPSlackSync::$text_domain) ?></p>
    <p class="description" id="wpslacksync-team-details-invalid-token" style="display: none;"><?php _e('Invalid token.', WPSlackSync::$text_domain) ?></p>
</td>
</tr>
<tr>
    <th>
        <label for="slack_team"><?php _e('Domain', WPSlackSync::$text_domain) ?></label>
    </th>
    <td class="domain_name">
        <input name="_wpslacksync_team" type="text" id="slack_team" value="<?php echo get_option('_wpslacksync_team') ?>" class="regular-text">.slack.com
    </td>
</tr>
<tr>
    <th>
        <label for="slack_team_id"><?php _e('ID', WPSlackSync::$text_domain) ?></label>
    </th>
    <td>
        <input name="_wpslacksync_team_id" type="text" id="slack_team_id" value="<?php echo get_option('_wpslacksync_team_id') ?>" class="regular-text">
    </td>
</tr>
<tr>
    <td><?php submit_button(); ?></td>
</tr>
</table>