<?php
class WPSlackSyncOptions {

    static function show_wpslacksync_settings() {
        ?>

        <div class="wrap wpslacksync_menu">
            <h2><?php _e('WPSlackSync Settings', WPSlackSync::$text_domain) ?></h2>
            <form action="options.php" method="post">
                <?php
                settings_fields('_wpslacksync');
                do_settings_sections('_wpslacksync');

                self::option_tabs();
                ?>
            </form>
        </div>

        <?php
    }

    static function option_tabs() {        
        ?>
        <input type="hidden" class="_wpslacksync_currenttab" name="_wpslacksync_currenttab" value="<?php echo get_option('_wpslacksync_currenttab') ?>" />
        <p>CurrentTab: <?php get_option('_wpslacksync_currenttab') ?></p>
        <h2 class="nav-tab-wrapper">
            <a href="javascript:;" id="tab1" class="nav-tab <?php if(get_option('_wpslacksync_currenttab') == "tab1" || get_option('_wpslacksync_currenttab') == "tab0" || get_option('_wpslacksync_currenttab') == ""){ echo "nav-tab-active"; } ?>"><?php _e('General', WPSlackSync::$text_domain) ?></a>
            <a href="javascript:;" id="tab-gdpr" class="nav-tab <?php if(get_option('_wpslacksync_currenttab') == "tab-gdpr"){ echo "nav-tab-active"; } ?>"><?php _e('GDPR', WPSlackSync::$text_domain) ?></a>
            <a href="javascript:;" id="tab2" class="nav-tab <?php if(get_option('_wpslacksync_currenttab') == "tab2"){ echo "nav-tab-active"; } ?>"><?php _e('Team Details', WPSlackSync::$text_domain) ?></a>
            <a href="javascript:;" id="tab3" class="nav-tab <?php if(get_option('_wpslacksync_currenttab') == "tab3"){ echo "nav-tab-active"; } ?>"><?php _e('Display', WPSlackSync::$text_domain) ?></a>
            <a href="javascript:;" id="tab4" class="nav-tab <?php if(get_option('_wpslacksync_currenttab') == "tab4"){ echo "nav-tab-active"; } ?>"><?php _e('Styling', WPSlackSync::$text_domain) ?></a>
        <?php if(false) { // alpha feature is disabled by default ?>
            <a href="javascript:;" id="tab-archive" class="nav-tab <?php if(get_option('_wpslacksync_currenttab') == "tab-archive"){ echo "nav-tab-active"; } ?>"><?php _e('Slack Archive (beta)', WPSlackSync::$text_domain) ?></a>
        <?php } ?>
        </h2>
        <h2 class="wpe-content-wrapper">
            <div id="contenttab1" class="tabs-content <?php if(get_option('_wpslacksync_currenttab') == "tab1" || get_option('_wpslacksync_currenttab') == "tab0" || get_option('_wpslacksync_currenttab') == ""){ echo ""; }else{ echo "hidden"; } ?>">
                <?php include plugin_dir_path(WPSlackSync::$_FILE) . 'templates/template-options-general.php' ?>
            </div>
             <div id="contenttab-gdpr" class="tabs-content <?php if(get_option('_wpslacksync_currenttab') == "tab-gdpr"){ echo ""; }else{ echo "hidden"; } ?>">
                <?php include plugin_dir_path(WPSlackSync::$_FILE) . 'templates/template-options-gdpr.php' ?>
            </div>
            <div id="contenttab2" class="tabs-content <?php if(get_option('_wpslacksync_currenttab') == "tab2"){ echo ""; }else{ echo "hidden"; } ?>">
                <?php include plugin_dir_path(WPSlackSync::$_FILE) . 'templates/template-options-team-details.php' ?>
            </div>
            <div id="contenttab3" class="tabs-content <?php if(get_option('_wpslacksync_currenttab') == "tab3"){ echo ""; }else{ echo "hidden"; } ?>" >
                <?php include plugin_dir_path(WPSlackSync::$_FILE) . 'templates/template-options-display.php' ?>
            </div>
            <div id="contenttab4" class="tabs-content <?php if(get_option('_wpslacksync_currenttab') == "tab4"){ echo ""; }else{ echo "hidden"; } ?>" >
                <?php include plugin_dir_path(WPSlackSync::$_FILE) . 'templates/template-options-styling.php' ?>
            </div>
            <div id="contenttab5" class="tabs-content <?php if(get_option('_wpslacksync_currenttab') == "tab-archive"){ echo ""; }else{ echo "hidden"; } ?>" >
                <?php include plugin_dir_path(WPSlackSync::$_FILE) . 'templates/template-import-chat.php' ?>
            </div>
        </h2>
        <?php
    }

}
