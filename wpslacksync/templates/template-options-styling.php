<table class="form-table">
    <tr>
        <th>
    <h3 class="title"><?php _e('Container', WPSlackSync::$text_domain) ?></h3>
</th>
</tr>
<tr>
    <th>
        <label for="_wpslacksync_container_border_color"><?php _e('Border Color', WPSlackSync::$text_domain) ?></label>
    </th>
    <td>
        <input name="_wpslacksync_container_border_color" type="text" class="color-field" id="_wpslacksync_container_border_color" value="<?php echo get_option('_wpslacksync_container_border_color', '#E9622D') ?>" class="regular-text">
    </td>
</tr>
<tr>
    <th>
        <label for="_wpslacksync_container_border_radius"><?php _e('Border Radius', WPSlackSync::$text_domain) ?></label>
    </th>
    <td class="border-radius">
        <input name="_wpslacksync_container_border_radius" type="text" id="_wpslacksync_container_border_radius" value="<?php echo get_option('_wpslacksync_container_border_radius', '0px') ?>" class="regular-text">
    </td>
</tr>
<tr>
    <th>
        <label for="_wpslacksync_chat_width"><?php _e('Chat Width', WPSlackSync::$text_domain) ?></label>
    </th>
    <td>
        <input name="_wpslacksync_chat_width" type="text" id="chat_width" value="<?php echo get_option('_wpslacksync_chat_width') ?>" placeholder="800px" class="regular-text">
        <i class="fa fa-info-circle tooltip" aria-hidden="true" title="<?php _e('Enter either in px or %. Examples: 800px or 100%', WPSlackSync::$text_domain) ?>"></i>
    </td>
</tr>
<tr>
    <th>
        <label for="_wpslacksync_chat_height"><?php _e('Chat Height', WPSlackSync::$text_domain) ?></label>
    </th>
    <td>
        <input name="_wpslacksync_chat_height" type="text" id="chat_height" value="<?php echo get_option('_wpslacksync_chat_height') ?>" placeholder="700px" class="regular-text">
        <i class="fa fa-info-circle tooltip" aria-hidden="true" title="<?php _e('Enter either in px or %. Examples: 800px or 100%', WPSlackSync::$text_domain) ?>"></i>
    </td>
</tr>
<tr>
    <th>
        <label for="_wpslacksync_left_sidebar_width"><?php _e('Left Sidebar Width', WPSlackSync::$text_domain) ?></label>
    </th>
    <td>
        <input name="_wpslacksync_left_sidebar_width" type="text" id="left_sidebar_width" value="<?php echo get_option('_wpslacksync_left_sidebar_width') ?>" placeholder="125px" class="regular-text">
        <i class="fa fa-info-circle tooltip" aria-hidden="true" title="<?php _e('Enter in px. Example: 125px.', WPSlackSync::$text_domain) ?>"></i>
    </td>
</tr>
<tr>
        <th>
    <h3 class="title"><?php _e('Invite Form', WPSlackSync::$text_domain) ?></h3>
</th>
</tr>
<tr>
    <th>
        <label for="_wpslacksync_inviteform_title_color"><?php _e('Title Color', WPSlackSync::$text_domain) ?></label>
    </th>
    <td>
        <input name="_wpslacksync_inviteform_title_color" type="text" class="color-field" id="_wpslacksync_inviteform_title_color" value="<?php echo get_option('_wpslacksync_inviteform_title_color', '#E9622D') ?>" class="regular-text">
    </td>
</tr>
<tr>
    <th>
        <label for="_wpslacksync_inviteform_inputborder_color"><?php _e('Input Border Color', WPSlackSync::$text_domain) ?></label>
    </th>
    <td>
        <input name="_wpslacksync_inviteform_inputborder_color" type="text" class="color-field" id="_wpslacksync_inviteform_inputborder_color" value="<?php echo get_option('_wpslacksync_inviteform_inputborder_color', '#E9622D') ?>" class="regular-text">
    </td>
</tr>
<tr>
    <th>
        <label for="_wpslacksync_inviteform_button_color"><?php _e('Button Color', WPSlackSync::$text_domain) ?></label>
    </th>
    <td>
        <input name="_wpslacksync_inviteform_button_color" type="text" class="color-field" id="_wpslacksync_inviteform_button_color" value="<?php echo get_option('_wpslacksync_inviteform_button_color', '#FFFFFF') ?>" class="regular-text">
</tr>
<tr>
    <th>
        <label for="_wpslacksync_inviteform_buttonbg_color"><?php _e('Button Background Color', WPSlackSync::$text_domain) ?></label>
    </th>
    <td>
        <input name="_wpslacksync_inviteform_buttonbg_color" type="text" class="color-field" id="_wpslacksync_inviteform_buttonbg_color" value="<?php echo get_option('_wpslacksync_inviteform_buttonbg_color', '#E9622D') ?>" class="regular-text">
    </td>
</tr>
<tr>
    <th>
        <label for="_wpslacksync_inviteform_buttonborder_color"><?php _e('Button Border Color', WPSlackSync::$text_domain) ?></label>
    </th>
    <td>
        <input name="_wpslacksync_inviteform_buttonborder_color" type="text" class="color-field" id="_wpslacksync_inviteform_buttonborder_color" value="<?php echo get_option('_wpslacksync_inviteform_buttonborder_color', '#b93207') ?>" class="regular-text">
    </td>
</tr>
<tr>
    <th>
<h3 class="title"><?php _e('Left Sidebar', WPSlackSync::$text_domain) ?></h3>
</th>
</tr>
<tr>
    <th>
        <label for="_wpslacksync_leftsidebar_bckg_color"><?php _e('Background Color', WPSlackSync::$text_domain) ?></label>
    </th>
    <td>
        <input name="_wpslacksync_leftsidebar_bckg_color" type="text" class="color-field" id="_wpslacksync_leftsidebar_bckg_color" value="<?php echo get_option('_wpslacksync_leftsidebar_bckg_color', '#393939') ?>" class="regular-text">
    </td>
</tr>
<tr>
    <th>
        <label for="_wpslacksync_leftsidebar_chnnl_color"><?php _e('Channel Name Color', WPSlackSync::$text_domain) ?></label>
    </th>
    <td>
        <input name="_wpslacksync_leftsidebar_chnnl_color" type="text" class="color-field" id="_wpslacksync_leftsidebar_chnnl_color" value="<?php echo get_option('_wpslacksync_leftsidebar_chnnl_color', '#393939') ?>" class="regular-text">
    </td>
</tr>
<tr>
    <th>
        <label for="_wpslacksync_leftsidebar_chnnl_active_color"><?php _e('Hover/Active Channel Name Color', WPSlackSync::$text_domain) ?></label>
    </th>
    <td>
        <input name="_wpslacksync_leftsidebar_chnnl_active_color" type="text" class="color-field" id="_wpslacksync_leftsidebar_chnnl_active_color" value="<?php echo get_option('_wpslacksync_leftsidebar_chnnl_active_color', '#FFFFFF') ?>" class="regular-text">
    </td>
</tr>
<tr>
    <th>
        <label for="_wpslacksync_leftsidebar_chnnl_bg_color"><?php _e('Channel Background Color', WPSlackSync::$text_domain) ?></label>
    </th>
    <td>
        <input name="_wpslacksync_leftsidebar_chnnl_bg_color" type="text" class="color-field" id="_wpslacksync_leftsidebar_chnnl_bg_color" value="<?php echo get_option('_wpslacksync_leftsidebar_chnnl_bg_color', '#FFFFFF') ?>" class="regular-text">
    </td>
</tr>
<tr>
    <th>
        <label for="_wpslacksync_leftsidebar_chnnl_active_bg_color"><?php _e('Hover/Active Channel Background Color', WPSlackSync::$text_domain) ?></label>
    </th>
    <td>
        <input name="_wpslacksync_leftsidebar_chnnl_active_bg_color" type="text" class="color-field" id="_wpslacksync_leftsidebar_chnnl_active_bg_color" value="<?php echo get_option('_wpslacksync_leftsidebar_chnnl_active_bg_color', '#E9622D') ?>" class="regular-text">
    </td>
</tr>
<tr>
    <th>
        <label for="_wpslacksync_leftsidebar_chnnl_bottomborder_color"><?php _e('Channel Bottom Border Color', WPSlackSync::$text_domain) ?></label>
    </th>
    <td>
        <input name="_wpslacksync_leftsidebar_chnnl_bottomborder_color" type="text" class="color-field" id="_wpslacksync_leftsidebar_chnnl_bottomborder_color" value="<?php echo get_option('_wpslacksync_leftsidebar_chnnl_bottomborder_color', '#FFFFFF') ?>" class="regular-text">
    </td>
</tr>
<tr>
    <th>
        <label for="_wpslacksync_leftsidebar_collapse_color"><?php _e('Mobile Left Sidebar Collapse Color', WPSlackSync::$text_domain) ?></label>
    </th>
    <td>
        <input name="_wpslacksync_leftsidebar_collapse_color" type="text" class="color-field" id="_wpslacksync_leftsidebar_collapse_color" value="<?php echo get_option('_wpslacksync_leftsidebar_collapse_color', '#FFFFFF') ?>" class="regular-text">
    </td>
</tr>
<tr>
    <th>
        <label for="_wpslacksync_leftsidebar_collapsebg_color"><?php _e('Mobile Left Sidebar Collapse Background', WPSlackSync::$text_domain) ?></label>
    </th>
    <td>
        <input name="_wpslacksync_leftsidebar_collapsebg_color" type="text" class="color-field" id="_wpslacksync_leftsidebar_collapsebg_color" value="<?php echo get_option('_wpslacksync_leftsidebar_collapsebg_color', '#393939') ?>" class="regular-text">
    </td>
</tr>
<tr>
    <th>
<h3 class="title"><?php _e('Slack Panel', WPSlackSync::$text_domain) ?></h3>
</th>
</tr>
<tr>
    <th>
        <label for="_wpslacksync_mainpanel_channeltitle_color"><?php _e('Channel Title Color', WPSlackSync::$text_domain) ?></label>
    </th>
    <td>
        <input name="_wpslacksync_mainpanel_channeltitle_color" type="text" class="color-field" id="_wpslacksync_mainpanel_channeltitle_color" value="<?php echo get_option('_wpslacksync_mainpanel_channeltitle_color', '#3D3C40') ?>" class="regular-text">
    </td>
</tr>
<tr>
    <th>
        <label for="_wpslacksync_mainpanel_channelbgtitle_color"><?php _e('Channel Title Background Color', WPSlackSync::$text_domain) ?></label>
    </th>
    <td>
        <input name="_wpslacksync_mainpanel_channelbgtitle_color" type="text" class="color-field" id="_wpslacksync_mainpanel_channelbgtitle_color" value="<?php echo get_option('_wpslacksync_mainpanel_channelbgtitle_color', '#FFFFFF') ?>" class="regular-text">
    </td>
</tr>
<tr>
    <th>
	<label for="_wpslacksync_mainpanel_username_color"><?php _e('Username Color', WPSlackSync::$text_domain) ?></label>
    </th>
    <td>
        <input name="_wpslacksync_mainpanel_username_color" type="text" class="color-field" id="_wpslacksync_mainpanel_username_color" value="<?php echo get_option('_wpslacksync_mainpanel_username_color', '#3D3C40') ?>" class="regular-text">
    </td>
</tr>
<tr>
    <th>
        <label for="_wpslacksync_mainpanel_msg_color"><?php _e('Message Color', WPSlackSync::$text_domain) ?></label>
    </th>
    <td>
        <input name="_wpslacksync_mainpanel_msg_color" type="text" class="color-field" id="_wpslacksync_mainpanel_msg_color" value="<?php echo get_option('_wpslacksync_mainpanel_msg_color', '#3D3C40') ?>" class="regular-text">
    </td>
</tr>
<tr>
    <th>
        <label for="_wpslacksync_mainpanel_botmsg_color"><?php _e('BOT Message Color', WPSlackSync::$text_domain) ?></label>
    </th>
    <td>
        <input name="_wpslacksync_mainpanel_botmsg_color" type="text" class="color-field" id="_wpslacksync_mainpanel_botmsg_color" value="<?php echo get_option('_wpslacksync_mainpanel_botmsg_color', '#939393') ?>" class="regular-text">
    </td>
</tr>
<tr>
        <th>
            <label for="use_external_style_yn"><?php _e('External stylesheet', WPSlackSync::$text_domain) ?></label>
        </th>
        <td class="checkbox_cl">
            <input name="_wpslacksync_use_external_style_yn" type="checkbox" id="use_external_style_yn" <?php echo get_option('_wpslacksync_use_external_style_yn', '') == 'on' ? 'checked' : '' ?>>
            <i class="fa fa-info-circle tooltip" aria-hidden="true" title="<?php _e('When activated, the above options will be ignored and no css will be generated/included for them.', WPSlackSync::$text_domain) ?>"></i>
            <p class="description"><?php _e('Use custom stylesheet.', WPSlackSync::$text_domain) ?></p>
        </td>
    </tr>
<tr>
    <td><?php submit_button(); ?></td>
</tr>
</table>
