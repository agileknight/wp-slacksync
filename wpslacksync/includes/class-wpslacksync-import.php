<?php
require 'class-wpslacksync-view.php';
class WPSlackSync_Import{
    public static function init(){
        $class = __CLASS__;
        new $class;
    }
    function __construct() {
        add_action('wp_ajax_wpslacksync_do_ajax',array($this,'wpslacksync_do_ajax'));
    }
    public function wpslacksync_do_ajax(){
        if($_POST){
            check_ajax_referer('wpslacksync-slack-archive-import-ajax', 'security');

            $act        =   $_POST['act'];
            WP_Filesystem();
            $temp_dir            = get_temp_dir();
            $extraction_prefix   = $temp_dir.'/wpslacksync-';

            if($act=='extract_zip'){
                global $wp_filesystem;
                $aid                    =   $_POST['aid'];
                $filename               =   $_POST['filename'];
                $extraction_folder      =   $extraction_prefix.rtrim($filename, '.zip');
                if ( is_dir($extraction_folder) ) {
                    if( !$wp_filesystem->delete($extraction_folder, true ) ) {
                        wp_send_json_error('Extraction folder already exists and could not be deleted.', 500);
                    }
                }
                $attached_file          = get_attached_file($aid);
                $unzipfile              = unzip_file($attached_file, $extraction_folder);

                if(is_wp_error($unzipfile)){
                    wp_send_json_error('There was an error unzipping the file.', 500);
                }

                $file_list  =   list_files($extraction_folder);
                $file_sizes = array();
                foreach ($file_list as $file) {
                    $file_sizes[] = filesize($file);
                }

                wp_send_json(array('status'=>'success','files'=>$file_list,'file_sizes'=>$file_sizes));
            }
            if($act=='import_files'){
                $import_files    =    explode(',', $_POST['import_files']);
                foreach($import_files as $import_file) {
                    if (substr($import_file, 0, strlen($extraction_prefix)) !== $extraction_prefix) {
                        wp_send_json_error('Invalid import file', 500);
                    }
                    if (basename($import_file) == 'channels.json') {
                        WPSlackSync_Import::import_channels($import_file);
                        continue;
                    }
                    if (basename($import_file) == 'users.json') {
                        WPSlackSync_Import::import_users($import_file);
                        continue;
                    }
                    if (basename($import_file) == 'integration_logs.json') {
                        // integration logs currently not needed
                        continue;
                    }
                    WPSlackSync_Import::import_messages($import_file);
                }

                wp_send_json(array('status'=>'success'));
            }
            if($act=='cleanup'){
                global $wp_filesystem;
                $filename               =   $_POST['filename'];
                $extraction_folder      =   $extraction_prefix.rtrim($filename, '.zip');

                if( !$wp_filesystem->delete($extraction_folder, true ) ) {
                    wp_send_json_error('Extraction folder could not be deleted after use.', 500);
                }

                wp_send_json(array('status'=>'success'));
            }
        }
        wp_die();
    }

    public static function read_json_file($json_file){
        $json           = file_get_contents($json_file);
        $obj            = json_decode($json);
        return $obj;
    }

    public static function import_channels($channels_file){
        global $wpdb;
        $table_name = $wpdb->prefix."slacksync_channels";
        $channels   =   WPSlackSync_Import::read_json_file($channels_file);
        if($channels){
            $values = array();

            foreach($channels as $channel){
                if (!$channel->id) {
                    continue;
                }
                $values[] = $wpdb->prepare( "(%s,%s)", $channel->id, json_encode($channel) );
            }

            $query = "REPLACE INTO " . $table_name ." (cid, json) VALUES ";
            $query .= implode( ",\n", $values );
            $wpdb->query($query);
        }
    }

    public static function import_users($users_file){
        global $wpdb;
        $table_name = $wpdb->prefix."slacksync_users";
        $users   =   WPSlackSync_Import::read_json_file($users_file);
        if($users){
            $values = array();

            foreach($users as $user){
                if (!$user->team_id) {
                    continue;
                }
                if (!$user->id) {
                    continue;
                }
                $values[] = $wpdb->prepare( "(%s,%s,%s)",
                    $user->team_id, $user->id, json_encode($user) );
            }

            $query = "REPLACE INTO " . $table_name ." (team_id, uid, json) VALUES ";
            $query .= implode( ",\n", $values );
            $wpdb->query($query);
        }
    }

    public static function import_messages($messages_file){
        global $wpdb;
        $table_name     =   $wpdb->prefix."slacksync_messages";
        $messages       =   WPSlackSync_Import::read_json_file($messages_file);
        $full_dir       =   dirname($messages_file);
        $channel        =   substr($full_dir, strrpos($full_dir, '/') + 1);
        if($messages){
            $values = array();

            foreach($messages as $msg){
                if (!$msg->ts) {
                    continue;
                }
                $unix_ts  = substr($msg->ts, 0, strpos($msg->ts, '.'));
                $values[] = $wpdb->prepare( "(%s,%s,FROM_UNIXTIME(%d),YEAR(FROM_UNIXTIME(%d)),MONTH(FROM_UNIXTIME(%d)),DAY(FROM_UNIXTIME(%d)),%s)",
                    $channel, $msg->ts, $unix_ts, $unix_ts, $unix_ts, $unix_ts, json_encode($msg) );
            }

            $query = "REPLACE INTO " . $table_name ." (channel, slack_ts, ts, year, month, day, json) VALUES ";
            $query .= implode( ",\n", $values );
            $wpdb->query($query);
        }
    }

    public function install(){
        global $wpdb;
        global $charset_collate;
        require_once ABSPATH . 'wp-admin/includes/upgrade.php';
        $charset_collate                    =   $wpdb->get_charset_collate();
        $table_wp_slack_channels            =   $wpdb->prefix.'slacksync_channels';
        $table_wp_slack_users               =   $wpdb->prefix.'slacksync_users';
        $table_wp_slack_messages            =   $wpdb->prefix.'slacksync_messages';

        $sql_table_wp_slack_channels = "CREATE TABLE IF NOT EXISTS `$table_wp_slack_channels` (
        `cid` varchar(255) NOT NULL,
        `json` json NOT NULL,
        PRIMARY KEY (`cid`)) $charset_collate;";
        dbDelta($sql_table_wp_slack_channels);

         $sql_table_wp_slack_users = "CREATE TABLE IF NOT EXISTS `$table_wp_slack_users` (
        `team_id` varchar(255) NOT NULL,
        `uid` varchar(255) NOT NULL,
        `json` json NOT NULL,
        PRIMARY KEY (`team_id`, `uid`)) $charset_collate;";
        dbDelta($sql_table_wp_slack_users);

        $sql_table_wp_slack_messages = "CREATE TABLE IF NOT EXISTS `$table_wp_slack_messages`(
        `channel` varchar(255) NOT NULL,
        `slack_ts` varchar(255) NOT NULL,
        `ts` timestamp NOT NULL,
        `year` smallint NOT NULL,
        `month` tinyint NOT NULL,
        `day` tinyint NOT NULL,
        `json` json NOT NULL,
        PRIMARY KEY (`channel`, `slack_ts`),
        INDEX (`year`, `month`, `day`)) $charset_collate;";
        dbDelta($sql_table_wp_slack_messages);

    }

}
add_action('plugins_loaded',array('WPSlackSync_Import','init'));