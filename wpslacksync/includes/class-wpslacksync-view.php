<?php
class WPSlackSync_View{
    public static function init(){
        $class = __CLASS__;
        new $class;
    }
    function __construct(){
        add_shortcode( 'wpslacksync_archive', array($this,'wpslacksync_archive'));
    }
    public function wpslacksync_archive( $atts){
        extract( shortcode_atts(
            array(),
            $atts
        ));
        ob_start();

        global $wpdb;
        $table          =   $wpdb->prefix."slacksync_messages";

        if( isset($_GET['_year']) && isset($_GET['_month']) && isset($_GET['_day']) && isset($_GET['_channel']) ) {
            $year = filter_var( $_GET['_year'], FILTER_VALIDATE_INT );
            $month = filter_var( $_GET['_month'], FILTER_VALIDATE_INT );
            $day = filter_var( $_GET['_day'], FILTER_VALIDATE_INT );
            $channel = filter_var( $_GET['_channel'], FILTER_SANITIZE_STRING );

            $messages_query = $wpdb->prepare( "SELECT *, UNIX_TIMESTAMP(ts) as unix_ts FROM `$table` WHERE year = '%d' AND month = '%d' AND day = '%d' AND channel = '%s'", $year , $month, $day, $channel );
            $messages = $wpdb->get_results( $messages_query );

            $messages_decoded = array();
            foreach ( $messages as $message ) {
                $messages_decoded[] = json_decode($message->json);
            }

            $users_by_uid = $this->load_users();

            WPSlackSyncShortcode::load_plugin_scripts();

            echo '#'.$channel.' '.$year.'-'.$month.'-'.$day.'</br>';
            ?>
            <script type="text/javascript">
                jQuery(document).on('ready', function () {
                    var users_raw = <?php echo json_encode($users_by_uid) ?>;
                    var messages_raw = <?php echo json_encode($messages_decoded) ?>;

                    jQuery.each(users_raw, function (uid, details) {
                        users[uid] = {
                            img: details.profile.image_32,
                            imgBig: details.profile.image_192,
                            displayName: details.profile.display_name,
                            realName: details.profile.real_name
                        };
                    });

                    var table = jQuery("#wpslacksync_messages_table");
                    jQuery.each(messages_raw, function (i, message) {
                        table.append('<tr><td>' + get_time(message.ts) + '</td><td>' + wpslacksync.app.getRealName(message.user) + '</td><td>' + filter_text(nl2br(message.text)) + '</td></tr>');
                    });
                });
            </script>
            <table id="wpslacksync_messages_table">
                <tr><th>Time</th><th>User</th><th>Message</th></tr>
            </table>
            <?php
            echo '< <a href="'.get_permalink(get_the_ID()).'?_year='.$year.'&_month='.$month.'&_channel='.$channel.'">Back</a></br>';
        }
        else if( isset($_GET['_year']) && isset($_GET['_month']) && isset($_GET['_channel']) ) {
            $year = filter_var( $_GET['_year'], FILTER_VALIDATE_INT );
            $month = filter_var( $_GET['_month'], FILTER_VALIDATE_INT );
            $channel = filter_var( $_GET['_channel'], FILTER_SANITIZE_STRING );

            $count_by_day_query = $wpdb->prepare( "SELECT day, count(*) as count FROM `$table` WHERE year = '%d' AND month = '%d' AND channel = '%s' GROUP BY day", $year , $month, $channel );
            $count_by_day = $wpdb->get_results( $count_by_day_query );

            echo '#'.$channel.' '.$year.'-'.$month.'</br>';
            foreach($count_by_day as $day_row) {
                $day = $day_row->day;
                echo '<a href="'.get_permalink(get_the_ID()).'?_year='.$year.'&_month='.$month.'&_day='.$day.'&_channel='.$channel.'">'.$day.'</a></br>';
            }
            echo '<br/>< <a href="'.get_permalink(get_the_ID()).'?_year='.$year.'&_month='.$month.'">Back</a></br>';
        } else if( isset($_GET['_year']) && isset($_GET['_month']) ) {
            $year = filter_var( $_GET['_year'], FILTER_VALIDATE_INT );
            $month = filter_var( $_GET['_month'], FILTER_VALIDATE_INT );

            $count_by_channel_query = $wpdb->prepare( "SELECT channel, count(*) as count FROM `$table` WHERE year = '%d' AND month = '%d' GROUP BY channel", $year , $month );
            $count_by_channel = $wpdb->get_results( $count_by_channel_query );

            echo $year.'-'.$month.'</br>';
            foreach($count_by_channel as $channel_row) {
                $channel = $channel_row->channel;
                echo '<a href="'.get_permalink(get_the_ID()).'?_year='.$year.'&_month='.$month.'&_channel='.$channel.'">#'.$channel.'</a></br>';
            }
            echo '<br/>< <a href="'.get_permalink(get_the_ID()).'?_year='.$year.'">Back</a></br>';
        } else if(isset($_GET['_year'])){
            $year = filter_var( $_GET['_year'], FILTER_VALIDATE_INT );

            $count_by_month_query = $wpdb->prepare( "SELECT month, count(*) as count FROM `$table` WHERE year = '%d' GROUP BY month", $year );
            $count_by_month = $wpdb->get_results( $count_by_month_query );

            echo $year.'</br>';
            foreach($count_by_month as $month_row) {
                $month = $month_row->month;
                echo '<a href="'.get_permalink(get_the_ID()).'?_year='.$year.'&_month='.$month.'">'.$month.'</a></br>';
            }
            echo '<br/>< <a href="'.get_permalink(get_the_ID()).'">Back</a></br>';
        } else {
            $count_by_year  =   $wpdb->get_results( "SELECT year, count(*) as count FROM `$table` GROUP BY year");

            foreach($count_by_year as $year_row) {
                $year = $year_row->year;
                echo '<a href="'.get_permalink(get_the_ID()).'?_year='.$year.'">'.$year.'</a></br>';
            }
        }

        $o=ob_get_contents();
        ob_end_clean();
        return $o;
    }

    public function load_users() {
        global $wpdb;
        $table = $wpdb->prefix."slacksync_users";
        $users = $wpdb->get_results( "SELECT * FROM `$table`" );

        $users_by_uid = array();
        foreach($users as $user){
            $uid = $user->uid;
            $details = json_decode($user->json);
            $users_by_uid[$uid] = $details;
        }

        return $users_by_uid;
    }
}
add_action('plugins_loaded',array('WPSlackSync_View','init'));