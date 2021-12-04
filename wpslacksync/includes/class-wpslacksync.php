<?php

class WPSlackSync {

    public static $plugin_version;
    public static $text_domain, $_FILE;

    const OPTIONS_SCHEMA_VERSION = 2;
    const OPTIONS_SCHEMA_VERSION_WITH_LICENSE_STATUS = 2;
    const OPTIONS_SCHEMA_VERSION_WITH_CONST_ACCESSOR_BUG = 1;

    function __construct($theFile = null, $version = null) {
        self::$_FILE = $theFile;
        self::$text_domain = '_wpslacksync';
        self::$plugin_version = $version;
        add_action('wp_head', array($this, 'add_ie_image_fix_meta'));
        add_action('init', array($this, 'migrate_options'));
        add_action('admin_menu', array($this, 'add_wpmenus'));
        add_action('admin_init', array($this, 'register_settings'));
        add_action('admin_init', array('WPSlackSyncGate', 'oauth_access_admin'));
        add_action('after_setup_theme', array($this, 'load_languages'));
        add_action('admin_enqueue_scripts', array($this, 'load_plugin_scripts_admin'));
        add_shortcode('wpslacksync', array('WPSlackSyncShortcode', 'execute_shortcode'));
        add_action('wp_ajax_query_user_list', array('WPSlackSyncGate', 'query_user_list'));
        add_action('wp_ajax_nopriv_query_user_list', array('WPSlackSyncGate', 'query_user_list'));
        add_action('wp_ajax_query_public_channel_list', array('WPSlackSyncGate', 'query_public_channel_list'));
        add_action('wp_ajax_nopriv_query_public_channel_list', array('WPSlackSyncGate', 'query_public_channel_list'));
        add_action('wp_ajax_query_public_channel_history', array('WPSlackSyncGate', 'query_public_channel_history'));
        add_action('wp_ajax_nopriv_query_public_channel_history', array('WPSlackSyncGate', 'query_public_channel_history'));
        add_action('wp_ajax_query_public_thread_history', array('WPSlackSyncGate', 'query_public_thread_history'));
        add_action('wp_ajax_nopriv_query_public_thread_history', array('WPSlackSyncGate', 'query_public_thread_history'));
        add_action('wp_ajax_invite', array('WPSlackSyncGate', 'invite'));
        add_action('wp_ajax_nopriv_invite', array('WPSlackSyncGate', 'invite'));
        add_action('wp_ajax_oauth_access', array('WPSlackSyncGate', 'oauth_access'));
        add_action('wp_ajax_nopriv_oauth_access', array('WPSlackSyncGate', 'oauth_access'));
    }

    function add_ie_image_fix_meta() {
        echo '<meta http-equiv="X-UA-Compatible" content="IE=edge" />';
    }

    function migrate_options() {
        $schema_version = get_option('wpslacksync_schema_version');
        if (!$schema_version) {
            // the view only option was available before and then hidden for users
            // make sure users do not accidentally have an old activated value in the database
            update_option('_wpslacksync_view_only_yn', '');
        }

        if ($schema_version == 'OPTIONS_SCHEMA_VERSION') {
            update_option('wpslacksync_schema_version', self::OPTIONS_SCHEMA_VERSION_WITH_CONST_ACCESSOR_BUG);
        }

        if ($schema_version !== self::OPTIONS_SCHEMA_VERSION) {
            update_option('wpslacksync_schema_version', self::OPTIONS_SCHEMA_VERSION);
        }
    }

    function add_wpmenus() {
        add_submenu_page('options-general.php', 'WPSlackSync Settings', 'WP SlackSync', 'manage_options', 'wpslacksync-settings', array('WPSlackSyncOptions', 'show_wpslacksync_settings'));
    }

    function register_settings() {
        register_setting('_wpslacksync', '_wpslacksync_currenttab');
        register_setting('_wpslacksync', '_wpslacksync_team');
        register_setting('_wpslacksync', '_wpslacksync_team_name');
        register_setting('_wpslacksync', '_wpslacksync_hook_url');
        register_setting('_wpslacksync', '_wpslacksync_token');
        register_setting('_wpslacksync', '_wpslacksync_client_id');
        register_setting('_wpslacksync', '_wpslacksync_client_secret');
        register_setting('_wpslacksync', '_wpslacksync_team_id');
        register_setting('_wpslacksync', '_wpslacksync_consent_html');
        register_setting('_wpslacksync', '_wpslacksync_include_consent_html_yn');
        register_setting('_wpslacksync', '_wpslacksync_mode');
        register_setting('_wpslacksync', '_wpslacksync_enter_name_invite_yn');
        register_setting('_wpslacksync', '_wpslacksync_view_only_yn');
        register_setting('_wpslacksync', '_wpslacksync_enable_private_channels_yn');
        register_setting('_wpslacksync', '_wpslacksync_enable_upload_profile_photo_yn');
        register_setting('_wpslacksync', '_wpslacksync_show_timestamp_yn');
        register_setting('_wpslacksync', '_wpslacksync_use_external_style_yn');
        register_setting('_wpslacksync', '_wpslacksync_chat_height');
        register_setting('_wpslacksync', '_wpslacksync_chat_width');
        register_setting('_wpslacksync', '_wpslacksync_left_sidebar_width');
        register_setting('_wpslacksync', '_wpslacksync_container_border_color');
        register_setting('_wpslacksync', '_wpslacksync_container_border_radius');
        register_setting('_wpslacksync', '_wpslacksync_leftsidebar_bckg_color');
        register_setting('_wpslacksync', '_wpslacksync_leftsidebar_chnnl_color');
        register_setting('_wpslacksync', '_wpslacksync_leftsidebar_chnnl_active_color');
        register_setting('_wpslacksync', '_wpslacksync_leftsidebar_chnnl_bg_color');
        register_setting('_wpslacksync', '_wpslacksync_leftsidebar_chnnl_active_bg_color');
        register_setting('_wpslacksync', '_wpslacksync_leftsidebar_chnnl_bottomborder_color');
        register_setting('_wpslacksync', '_wpslacksync_leftsidebar_collapse_color');
        register_setting('_wpslacksync', '_wpslacksync_leftsidebar_collapsebg_color');
        register_setting('_wpslacksync', '_wpslacksync_mainpanel_channeltitle_color');
        register_setting('_wpslacksync', '_wpslacksync_mainpanel_channelbgtitle_color');
        register_setting('_wpslacksync', '_wpslacksync_mainpanel_username_color');
        register_setting('_wpslacksync', '_wpslacksync_mainpanel_msg_color');
        register_setting('_wpslacksync', '_wpslacksync_mainpanel_botmsg_color');
        register_setting('_wpslacksync', '_wpslacksync_inviteform_title_color');
        register_setting('_wpslacksync', '_wpslacksync_inviteform_inputborder_color');
        register_setting('_wpslacksync', '_wpslacksync_inviteform_buttonbg_color');
        register_setting('_wpslacksync', '_wpslacksync_inviteform_buttonborder_color');
        register_setting('_wpslacksync', '_wpslacksync_inviteform_button_color');
    }

    function load_languages() {
        load_plugin_textdomain(self::$text_domain, false, dirname(plugin_basename(self::$_FILE)) . '/localization/');
    }

    function load_plugin_scripts_admin() {
        if (isset($_GET['page']) && $_GET['page'] != 'wpslacksync-settings')
            return;
        wp_enqueue_script('jquery');
        wp_enqueue_script('jquery-ui-core', false, array('jquery'));
        wp_enqueue_script('jquery-ui-tabs', false, array('jquery'));
        wp_enqueue_script('wp-color-picker', false, array('jquery'));
        wp_enqueue_script('wpslacksync-adminjs', plugin_dir_url(self::$_FILE) . 'assets/js/wpslacksync-admin.js', array('jquery'), self::$plugin_version);
        wp_enqueue_style('jquery-ui-css', plugin_dir_url(self::$_FILE) . 'assets/libraries/jQueryUI/jquery-ui.min.css', array(), self::$plugin_version);
        wp_enqueue_style('jquery-ui-theme', plugin_dir_url(self::$_FILE) . 'assets/libraries/jQueryUI/theme.css', array(), self::$plugin_version);
        wp_enqueue_script('jquery-ui-js', plugin_dir_url(self::$_FILE) . 'assets/libraries/jQueryUI/jquery-ui.min.js', array(), self::$plugin_version);
        wp_enqueue_script('evol-colorpicker-js', plugin_dir_url(self::$_FILE) . 'assets/libraries/evolColorpicker/evol.colorpicker.min.js', array(), self::$plugin_version);
        wp_enqueue_style('evol-colorpicker-css', plugin_dir_url(self::$_FILE) . 'assets/libraries/evolColorpicker/evol.colorpicker.min.css', array(), self::$plugin_version);
        wp_enqueue_style('wpslacksync-admincss', plugin_dir_url(self::$_FILE) . 'assets/css/wpslacksync-admin.css', array(), self::$plugin_version);
        wp_enqueue_style('font-awesome-css', '//netdna.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.css', array(), self::$plugin_version);
    }

}