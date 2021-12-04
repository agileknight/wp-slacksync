<?php
class WPSlackSyncShortcode {

    static function get_authorize_link() {
        $scope = join( '%20', array(
            'identify',
            'client'
        ));
        $redirectUri = get_permalink();
        if (array_key_exists('wpslacksync_debug', $_GET)) {
            $redirectUri = $redirectUri . '?wpslacksync_debug';
        }
        return add_query_arg( array(
            'client_id' => get_option('_wpslacksync_client_id'),
            'redirect_uri' => $redirectUri,
            'team' => get_option('_wpslacksync_team_id'),
            'scope' => $scope,
            'state' => 'unused',
        ), 'https://slack.com/oauth/authorize' );
    }

    static function load_plugin_scripts() {
        wp_enqueue_script('jquery');
        wp_enqueue_script('js-cookie', plugin_dir_url(WPSlackSync::$_FILE) . 'assets/libraries/js-cookie/js.cookie-2.1.4.min.js', array(), WPSlackSync::$plugin_version);
        wp_enqueue_script('js-storage', plugin_dir_url(WPSlackSync::$_FILE) . 'assets/libraries/js-storage/js.storage.min.js', array('js-cookie'), WPSlackSync::$plugin_version);
        wp_enqueue_script('sjcl', plugin_dir_url(WPSlackSync::$_FILE) . 'assets/libraries/sjcl/sjcl.js', array(), WPSlackSync::$plugin_version);
        wp_enqueue_script('mustache-js', plugin_dir_url(WPSlackSync::$_FILE) . 'assets/libraries/mustache/mustache.min.js', array(), WPSlackSync::$plugin_version);
        wp_enqueue_script('wpslacksync-emojis-js', plugin_dir_url(WPSlackSync::$_FILE) . 'assets/js/wpslacksync-emojis.js', array(), WPSlackSync::$plugin_version);
        wp_enqueue_script('wpslacksync-app-js', plugin_dir_url(WPSlackSync::$_FILE) . 'assets/js-gen/wpslacksync-app.js', array(), WPSlackSync::$plugin_version);
        wp_enqueue_script('wpslacksync-js', plugin_dir_url(WPSlackSync::$_FILE) . 'assets/js/wpslacksync.js', array('wpslacksync-app-js', 'wpslacksync-emojis-js', 'jquery', 'js-storage', 'sjcl', 'mustache-js'), WPSlackSync::$plugin_version);
        wp_enqueue_script('wpslacksync-invite-js', plugin_dir_url(WPSlackSync::$_FILE) . 'assets/js/wpslacksync-invite.js', array('wpslacksync-js'), WPSlackSync::$plugin_version);
        wp_enqueue_style('wpslacksync', plugin_dir_url(WPSlackSync::$_FILE) . 'assets/css/wpslacksync.css', array(), WPSlackSync::$plugin_version);
        wp_enqueue_style('wpslacksync-font', 'https://fonts.googleapis.com/css?family=Lato&subset=latin,latin-ext', array(), WPSlackSync::$plugin_version);
        wp_enqueue_style('jquery-ui-css', plugin_dir_url(WPSlackSync::$_FILE) . 'assets/libraries/jQueryUI/jquery-ui.min.css', array(), WPSlackSync::$plugin_version);
        wp_enqueue_style('jquery-ui-theme', plugin_dir_url(WPSlackSync::$_FILE) . 'assets/libraries/jQueryUI/theme.css', array(), WPSlackSync::$plugin_version);
        wp_enqueue_script('jquery-ui-js', plugin_dir_url(WPSlackSync::$_FILE) . 'assets/libraries/jQueryUI/jquery-ui.min.js', array(), WPSlackSync::$plugin_version);
        wp_enqueue_style('imagecrop-css', plugin_dir_url(WPSlackSync::$_FILE) . 'assets/libraries/ImageCrop/css/jquery.Jcrop.css', array(), WPSlackSync::$plugin_version);
        wp_enqueue_script('imagecrop-js', plugin_dir_url(WPSlackSync::$_FILE) . 'assets/libraries/ImageCrop/js/jquery.Jcrop.js', array(), WPSlackSync::$plugin_version);
        wp_enqueue_media();
        $timestamp = get_option('_wpslacksync_show_timestamp_yn', 'on');
        wp_localize_script('wpslacksync-js', '_wpslacksync_settings', array(
            'plugin_dir_path' => plugin_dir_url(WPSlackSync::$_FILE),
            'leftsidebar_chnnl_color' => get_option('_wpslacksync_leftsidebar_chnnl_color', '#AB9BA9'),
            'leftsidebar_chnnl_active_color' => get_option('_wpslacksync_leftsidebar_chnnl_active_color', '#FFF'),
            'mainpanel_username_color' => get_option('_wpslacksync_mainpanel_username_color', '#3D3C40'),
            'mainpanel_msg_color' => get_option('_wpslacksync_mainpanel_msg_color', '#3D3C40'),
            'mainpanel_botmsg_color' => get_option('_wpslacksync_mainpanel_botmsg_color', '#939393'),
            'mainpanel_channeljoin_color' => get_option('_wpslacksync_mainpanel_botmsg_color', '#939393'), //same as botmsg
            'mainpanel_timestamp_enabled' => !empty($timestamp) ? true : false,
        ));
        wp_localize_script('wpslacksync-js', '_wpslacksync_settings_l10n', array(
            'joined_channel_x' => __('joined', WPSlackSync::$text_domain),
            'file_action_uploaded' => __('uploaded', WPSlackSync::$text_domain),
            'file_action_commented_on' => __('commented on', WPSlackSync::$text_domain),
            'file_action_shared' => __('shared', WPSlackSync::$text_domain),
            'file_action_and_commented' => __('and commented', WPSlackSync::$text_domain),
            'wait_for_photo_updating' => __('Please wait while your profile photo is updating...', WPSlackSync::$text_domain),
            'crop_your_photo' => __('Crop your photo', WPSlackSync::$text_domain),
            'button_submit' => __('Submit', WPSlackSync::$text_domain),
            'button_cancel' => __('Cancel', WPSlackSync::$text_domain),
        ));
        wp_localize_script('wpslacksync-invite-js', '_wpslacksync_invite_l10n', array(
            'invitation_sent' => __('Invitation Sent. Check inbox.', WPSlackSync::$text_domain),
            'already_invited' => __('Already invited. Check inbox.', WPSlackSync::$text_domain),
            'already_in_team' => __('Already in team. Please Sign In.', WPSlackSync::$text_domain),
            'invalid_auth' => __('Invalid Token. Let know site admin.', WPSlackSync::$text_domain),
            'invalid_email' => __('Invalid email.', WPSlackSync::$text_domain),
            'empty_name_email' => __('Type your Name &amp; Email', WPSlackSync::$text_domain),
            'consent_required' => __('You must check the box/boxes to proceed', WPSlackSync::$text_domain),
        ));
    }

    static function set_jsvars() {
        $name_in_invite = get_option('_wpslacksync_enter_name_invite_yn', 'on');
        ?>
        <script type="text/javascript">
            var ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>';
            var wpslacksync_name_in_invite = <?php echo empty($name_in_invite) ? '0' : '1' ?>;
        </script>
        <?php
    }

    static function execute_shortcode($atts) {
 		$wpslacksync_atts = shortcode_atts(array(
        	'default_channel' => '',
        	'allowed_channels' => '',
            'allowed_public_channels' => '',
            'allowed_private_channels' => '',
            'no_file_upload' => '',
            'hide_sidebar' => '',
            'collapse_sidebar' => '',
            'view_only' => get_option('_wpslacksync_view_only_yn'),
            'mode' => get_option('_wpslacksync_mode', 'full'),
    	), $atts);

        $view_only = $wpslacksync_atts['view_only'];
        $mode = $wpslacksync_atts['mode'];
        $no_file_upload = $wpslacksync_atts['no_file_upload'];

        self::load_plugin_scripts();

        $name_in_invite = get_option('_wpslacksync_enter_name_invite_yn', 'on');
        $enable_private_channels = get_option('_wpslacksync_enable_private_channels_yn');
        $use_external_style = get_option('_wpslacksync_use_external_style_yn');
        $enable_invite = ($mode == 'invite' || $mode == 'full');
        $enable_login = ($mode == 'intern' || $mode == 'full');

        ob_start();
        self::set_jsvars();
        $border_radius = get_option('_wpslacksync_container_border_radius', '10px');
        ?>
        <script type="text/javascript">
            function wpslacksync_deferUntilLoaded(method) {
                if (window.jQuery && window.wpslacksync) {
                    method();
                } else {
                    setTimeout(function() { wpslacksync_deferUntilLoaded(method) }, 50);
                }
            }
            wpslacksync_deferUntilLoaded(function () {
                var view_only = <?php echo empty($view_only) ? '0' : '1' ?>;
                var mode = '<?php echo $mode ?>';
                var enable_private_channels = <?php echo empty($enable_private_channels) ? '0' : '1' ?>;

                if (mode == 'invite' || mode == 'passive') {
                    wpslacksync.tokenStore.clearToken();
                }

                if(wpslacksync.tokenStore.tokenInStore()) {
                    wpslacksync_token = wpslacksync.tokenStore.retrieveToken();
                    wpslacksync.gateway.chat.auth({
                        success: function (data) {
                            if (!data['ok'] || data['team_id'] != '<?php echo get_option('_wpslacksync_team_id') ?>') {
                                wpslacksync.tokenStore.clearToken();
                                jQuery('#wpslacksync-sign-in-container').show();
                                jQuery('.wpslacksync-team').remove();
                            } else {
                                jQuery('#wpslacksync-sign-in-container').remove();
                                jQuery('.wpslacksync-team').show();
                                if (!wpslacksync.shortcode.parseNoFileUpload()) {
                                     jQuery('#wpslacksync-chatbox-attach-button').show();
                                }
                                jQuery('.wpslacksync-chatbox').show();
                                wpslacksync_initialize({
                                    enablePrivateChannels: enable_private_channels,
                                    viewOnly: false
                                });
                            }
                        },
                        error: function(error) {
                            alert(error);
                        }
                    });

                    return;
                }

                var slack_code = getParameterByName('code');
                if (!slack_code) {
                    if (view_only) {
                        if (mode == 'passive') {
                            jQuery('.wpslacksync-chatbox-container').height('0px');
                        } else {
                            jQuery('#wpslacksync-join-chat').show().click(function() {
                                jQuery('#wpslacksync-sign-in-container').show();
                                jQuery('.wpslacksync-team').remove();
                                jQuery('.wpslacksync-container').first()[0].scrollIntoView();
                                return false;
                            });
                        }
                        wpslacksync_initialize_feed({
                            enablePrivateChannels: enable_private_channels,
                            viewOnly: true,
                        });
                    } else {
                        jQuery('#wpslacksync-sign-in-container').show();
                        jQuery('.wpslacksync-team').remove();
                    }
                    return;
                }

                var redirectUri = '<?php echo get_permalink(); ?>';
                if (wpslacksync.app.isDebug()) {
                    redirectUri = redirectUri + '?wpslacksync_debug';
                }
                wpslacksync.gateway.invitation.oauthAccess({
                    authCode: slack_code,
                    redirectUri: redirectUri,
                    success: function (data) {
                        if (!data['ok'] || data['team_id'] != '<?php echo get_option('_wpslacksync_team_id') ?>') {
                            jQuery('#wpslacksync-sign-in-container').show();
                            jQuery('.wpslacksync-team').remove();
                            display_error('Invalid Token');
                        } else {
                            jQuery('#wpslacksync-sign-in-container').remove();
                            jQuery('.wpslacksync-team').show();
                            if (!wpslacksync.shortcode.parseNoFileUpload()) {
                                     jQuery('#wpslacksync-chatbox-attach-button').show();
                            }
                            jQuery('.wpslacksync-chatbox').show();
                            var currentTitle = jQuery(document).find("title").text();
                            window.history.pushState(null, currentTitle, redirectUri);
                            wpslacksync_token = data['access_token'];
                            wpslacksync.tokenStore.storeToken(wpslacksync_token);
                            wpslacksync_initialize({
                                enablePrivateChannels: enable_private_channels,
                                viewOnly: false,
                            });
                        }
                    },
                    error: function(error) {
                            alert(error);
                    }
                });
            });
        </script>
        <div class="wpslacksync-container">
            <div id="wpslacksync-sign-in-container" style="display:none;">
                <h1 id="wpslacksync-team-name"><?php echo get_option('_wpslacksync_team_name', 'WPSLACKSYNC') ?></h1>
                <div class="wpslacksync-block" style="<?php echo $enable_invite ? '' : 'display:none;' ?>">
                    <div class="wpslacksync-login">
                        <form id="wpslacksync-form">
                            <?php if($name_in_invite == 'on') { ?>
                            <div class="wpslacksync-login-input-container">
                                <span><input type="text" name="fname" placeholder="<?php _e('First Name', WPSlackSync::$text_domain) ?>"></span>
                                <span><input type="text" name="lname" placeholder="<?php _e('Last Name', WPSlackSync::$text_domain) ?>"></span>
                            </div>
                            <?php } ?>
                            <div class="wpslacksync-login-input-container">
                                <input type="email" name="email" placeholder="<?php _e('Email Address', WPSlackSync::$text_domain) ?>">
                            </div>
                            <?php if(get_option('_wpslacksync_include_consent_html_yn', '') == 'on') { ?>
                            <div class="wpslacksync-login-input-container">
                                <?php echo get_option('_wpslacksync_consent_html') ?>
                            </div>
                            <?php } ?>
                            <div class="wpslacksync-login-input-container">
                                <div id="wpslacksync-get-invite" class="wpslacksync-submit-button"><?php _e('Get invited to team', WPSlackSync::$text_domain) ?></div>
                            </div>
                        </form>
                        <div id="wpslacksync-display-info" class="bg-danger"></div>
                    </div>
                </div>
                <div class="wpslacksync-block" style=" <?php echo $enable_login ? '' : 'display:none;' ?>">
                    <p><?php _e('Already a member?', WPSlackSync::$text_domain) ?></p>
                    <p><?php _e('Sign in with', WPSlackSync::$text_domain) ?></p>
                    <p><a id="wpslacksync-slack-login" href="<?php echo self::get_authorize_link() ?>"><img src="<?php echo plugin_dir_url(WPSlackSync::$_FILE) ?>assets/images/slacklogo.png"/></a></p>
                </div>
                <div class="clear-fix"></div>
            </div>
            <div class="wpslacksync-team" <?php empty($view_only) ? 'style="display:none;"' : '' ?>>
                <?php foreach($wpslacksync_atts as $att_name => $att_value) {
                	echo "<div class=\"wpslacksync-param-${att_name}\" style=\"display:none;\">${att_value}</div>";
                } ?>
                <div class="wpslacksync-left-sidebar">
                    <div id="wpslacksync-channel-list"><?php _e('Loading...', WPSlackSync::$text_domain) ?></div>
                    <div class="wpslacksync-popup-user-profile-menu wpslacksync-popup toggler-slack-menu" style="display:none">
                        <div id="effect" class="ui-widget-content ui-corner-all">
                        <section class="slack_menu_you slack_menu_section">
                                <h2 class="slack_menu_header">
                                        <div data-thumb-size="36" class="member_preview_link member_image thumb_36" ></div>
                                        <span class="current_user_name slack_menu_header_primary overflow_ellipsis"></span>
                                        <span class="current_name slack_menu_header_secondary overflow_ellipsis"></span>
                                </h2>
                                <ul class="menu_list main_menu">
                                    <li>
                                        <span class="menu_item_label about"><?php _e('About', WPSlackSync::$text_domain) ?></span>
                                    </li>
                                    <?php if(get_option('_wpslacksync_enable_upload_profile_photo_yn', 'on') == "on"){ ?>
                                    <li class="set_photo_menu">
                                        <span class="menu_item_label set_photo" id="set_photo"><?php _e('Set Photo', WPSlackSync::$text_domain) ?></span>
                                    </li>
                                    <?php } ?>
                                </ul>
                                <ul class="menu_list">
                                    <li role="menuitem" id="member_presence">
                                        <span class="menu_item_label" id="logout_user"><?php _e('Log Out', WPSlackSync::$text_domain) ?></span>
                                    </li>
                                </ul>
                        </section>
                        </div>
                    </div>
                    <div id="dialog"></div>
                    <div id="wpslacksync-user-profile" class="wpslacksync-popup-target wpslacksync-popup-target-style wpslacksync-popup-parent" data-popup-selector=".wpslacksync-popup-user-profile-menu"><img class="img-circle" src="" alt=""/><br><span class="noselect">&nbsp;</span></div>
                </div>
                <div class="wpslacksync-content">
					<span class="wpslacksync-sidebar-collapse" data-position="collapse"> &rsaquo; </span>
                    <h2 id="wpslacksync-channel-name"></h2>
                    <div class="wpslacksync-messages"><?php _e('Loading...', WPSlackSync::$text_domain) ?></div>
                    <div id="wpslacksync-back-to-channel" class="wpslacksync-channel-back-link" style="display:none;">
                        <a href="#">
                            <?php _e('Back to channel', WPSlackSync::$text_domain) ?>
                        </a>
                    </div>
                    <div class="wpslacksync-chatbox-container">
                        <a id="wpslacksync-join-chat" class="wpslacksync-submit-button" href="#" style="display:none;">
                            <?php _e('Join this chat', WPSlackSync::$text_domain) ?>
                        </a>
                        <?php if (trim($no_file_upload) != 'true') { ?>
                        <form><input type="file" id="wpslacksync-chatbox-upload-input"/></form>
                        <img id="wpslacksync-chatbox-attach-button" src="<?php echo plugin_dir_url(WPSlackSync::$_FILE) ?>assets/images/attach.png" style="display: none;"/>
                        <img id="wpslacksync-chatbox-detach-button" src="<?php echo plugin_dir_url(WPSlackSync::$_FILE) ?>assets/images/detach.png" style="display: none;"/>
                        <?php } ?>
                        <textarea class="wpslacksync-chatbox" style="display:none;"></textarea>
                    </div>
                </div>
            </div>
            <div class="clear-fix"></div>
        </div>
        <?php
        if (empty($use_external_style)) {
			echo '<style type="text/css">';
				// Title color
				$_wpslacksync_inviteform_title_color=get_option('_wpslacksync_inviteform_title_color', '#E9622D');
				echo ($_wpslacksync_inviteform_title_color) ? '#wpslacksync-team-name{ color:'.$_wpslacksync_inviteform_title_color.'}' : '';

				// Input border color
				$_wpslacksync_inviteform_inputborder_color=get_option('_wpslacksync_inviteform_inputborder_color', '#E9622D');
				echo ($_wpslacksync_inviteform_inputborder_color) ? '.wpslacksync-login-input-container input[type="text"],.wpslacksync-login-input-container input[type="text"]:focus,.wpslacksync-login-input-container input[type="email"],.wpslacksync-login-input-container input[type="email"]:focus { border: 2px solid '.$_wpslacksync_inviteform_inputborder_color.';}' : '';

				// Input button border color
				echo '.wpslacksync-submit-button,.wpslacksync-submit-button:hover {';
					$_wpslacksync_inviteform_buttonbg_color=get_option('_wpslacksync_inviteform_buttonbg_color', '#E9622D');
					echo ($_wpslacksync_inviteform_buttonbg_color) ? 'background:'.$_wpslacksync_inviteform_buttonbg_color.';' : '';
					$_wpslacksync_inviteform_buttonborder_color=get_option('_wpslacksync_inviteform_buttonborder_color', '#b93207');
					echo ($_wpslacksync_inviteform_buttonborder_color) ? 'border-color:'.$_wpslacksync_inviteform_buttonborder_color.';' : '';
					$_wpslacksync_inviteform_button_color=get_option('_wpslacksync_inviteform_button_color', '#FFFFFF');
					echo ($_wpslacksync_inviteform_button_color) ? 'color:'.$_wpslacksync_inviteform_button_color.';' : '';
				echo '}';

				// Wpslacksync Container Style
				echo '.wpslacksync-container{';
					$_wpslacksync_container_border_color=get_option('_wpslacksync_container_border_color', '#E9622D');
					echo ($_wpslacksync_container_border_color) ? 'border-color:'.$_wpslacksync_container_border_color.';' : '';
					$_wpslacksync_container_border_radius=get_option('_wpslacksync_container_border_radius', '0px');
					echo ($_wpslacksync_container_border_radius) ? 'border-radius:'.$_wpslacksync_container_border_radius.';' : '';
					$_wpslacksync_chat_width=get_option('_wpslacksync_chat_width');
					$_wpslacksync_chat_height=get_option('_wpslacksync_chat_height');
					if (empty($_wpslacksync_chat_width)) {
						$_wpslacksync_chat_width = '800px';
					}
					if (empty($_wpslacksync_chat_height)) {
						$_wpslacksync_chat_height = '700px';
					}
					echo 'max-width:'.$_wpslacksync_chat_width.';';
					echo 'height:'.$_wpslacksync_chat_height.';';
					echo 'max-height:'.$_wpslacksync_chat_height.';';
				echo '}';

				echo '.wpslacksync-left-sidebar{';
					$_wpslacksync_leftsidebar_bckg_color=get_option('_wpslacksync_leftsidebar_bckg_color', '#393939');
					echo ($_wpslacksync_leftsidebar_bckg_color) ? 'background-color:'.$_wpslacksync_leftsidebar_bckg_color.';' : '';
					$_wpslacksync_left_sidebar_width=get_option('_wpslacksync_left_sidebar_width');
					if (empty($_wpslacksync_left_sidebar_width)) {
						$_wpslacksync_left_sidebar_width = '125px';
					}
					echo 'max-width:'.$_wpslacksync_left_sidebar_width.';';
					echo 'width:'.$_wpslacksync_left_sidebar_width.';';
				echo '}';


				// Wpslacksync Channel Color
				$_wpslacksync_leftsidebar_chnnl_color=get_option('_wpslacksync_leftsidebar_chnnl_color', '#393939');
				echo ($_wpslacksync_leftsidebar_chnnl_color) ? '.wpslacksync-a-channel > .wpslacksync-a{color:'.$_wpslacksync_leftsidebar_chnnl_color.'}' : '';

				// Wpslacksync Channel Hover/Active Color
				$_wpslacksync_leftsidebar_chnnl_active_color=get_option('_wpslacksync_leftsidebar_chnnl_active_color', '#FFFFFF');
				echo ($_wpslacksync_leftsidebar_chnnl_active_color) ? '.wpslacksync-a-channel > .wpslacksync-a:hover , .wpslacksync-a-channel.active > .wpslacksync-a{ color:'.$_wpslacksync_leftsidebar_chnnl_active_color.'; }' : '';

				// Wpslacksync Channel Background Color
				$_wpslacksync_leftsidebar_chnnl_bg_color=get_option('_wpslacksync_leftsidebar_chnnl_bg_color', '#FFFFFF');
				echo ($_wpslacksync_leftsidebar_chnnl_bg_color) ? '.wpslacksync-a-channel > .wpslacksync-a{background-color:'.$_wpslacksync_leftsidebar_chnnl_bg_color.'}' : '';

				// Wpslacksync Channel Hover/Active Background Color
				$_wpslacksync_leftsidebar_chnnl_active_bg_color=get_option('_wpslacksync_leftsidebar_chnnl_active_bg_color', '#E9622D');
				echo ($_wpslacksync_leftsidebar_chnnl_active_bg_color) ? '.wpslacksync-a-channel > .wpslacksync-a:hover ,.wpslacksync-a-channel.active > .wpslacksync-a { background-color:'.$_wpslacksync_leftsidebar_chnnl_active_bg_color.' }' : '';

				// Wpslacksync Channel Bottom Border Color
				$_wpslacksync_leftsidebar_chnnl_bottomborder_color=get_option('_wpslacksync_leftsidebar_chnnl_bottomborder_color', '#FFFFFF');
				echo ($_wpslacksync_leftsidebar_chnnl_bottomborder_color) ? '.wpslacksync-a-channel { border-bottom-color:'.$_wpslacksync_leftsidebar_chnnl_bottomborder_color.' }' : '';

				// Wpslacksync Channel Bottom Border Color
				$_wpslacksync_mainpanel_channeltitle_color=get_option('_wpslacksync_mainpanel_channeltitle_color', '#3D3C40');
				echo ($_wpslacksync_mainpanel_channeltitle_color) ? '#wpslacksync-channel-name { color:'.$_wpslacksync_mainpanel_channeltitle_color.' }' : '';

				// Wpslacksync Channel Bottom Border Color
				$_wpslacksync_mainpanel_channelbgtitle_color=get_option('_wpslacksync_mainpanel_channelbgtitle_color', '#FFFFFF');
				echo ($_wpslacksync_mainpanel_channelbgtitle_color) ? '#wpslacksync-channel-name { background-color:'.$_wpslacksync_mainpanel_channelbgtitle_color.' }' : '';

				echo '.wpslacksync-sidebar-collapse {';
					// Wpslacksync Mobile Sidebar Collapse Background
					$_wpslacksync_leftsidebar_collapsebg_color=get_option('_wpslacksync_leftsidebar_collapsebg_color', '#393939');
					echo ($_wpslacksync_leftsidebar_collapsebg_color) ? ' background:'.$_wpslacksync_leftsidebar_collapsebg_color.' ;' : '';

					// Wpslacksync Mobile Sidebar Collapse Color
					$_wpslacksync_leftsidebar_collapse_color=get_option('_wpslacksync_leftsidebar_collapse_color', '#FFFFFF');
					echo ($_wpslacksync_leftsidebar_collapse_color) ? ' color:'.$_wpslacksync_leftsidebar_collapse_color.' ;' : '';
				echo ' }';

			echo '</style>';
		}
        $content = ob_get_contents();
        ob_end_clean();
        return $content;
    }

}
