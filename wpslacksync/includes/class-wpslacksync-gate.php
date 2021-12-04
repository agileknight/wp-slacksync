<?php
class WPSlackSyncGate {

    const channel_history_cache_retention_seconds = 10;

    static function invite() {
        $token = get_option('_wpslacksync_token');
        $curl = new Curl();
        $server_output = $curl->url('https://slack.com/api/users.admin.invite?t=' . time())->binaryTransfer()->sslVerifyPeer(FALSE)->returnTransfer()->postFields('email=' . $_GET['email'] . '&first_name=' . $_GET['fname'] . '&last_name=' . $_GET['lname'] . '&token=' . $token . '&set_active=true&_attempts=1')->curl();
        echo $server_output;
        wp_die();
    }

    static function oauth_access() {
        $clientId = get_option('_wpslacksync_client_id');
        $clientSecret = get_option('_wpslacksync_client_secret');
        $authCode = $_GET['authCode'];
        $redirectUri = $_GET['redirectUri'];
        $curl = new Curl();
        $server_output = $curl->url('https://slack.com/api/oauth.access')->binaryTransfer()->sslVerifyPeer(FALSE)->returnTransfer()->postFields('client_id=' . $clientId . '&client_secret=' . $clientSecret . '&code=' . $authCode . '&redirect_uri=' . $redirectUri)->curl();
        echo $server_output;
        wp_die();
    }

    static function oauth_access_admin() {
        if (isset($_GET['code']) && isset($_GET['state']) && ($_GET['state'] == 'unused')) {
            $clientId = get_option('_wpslacksync_client_id');
            $clientSecret = get_option('_wpslacksync_client_secret');
            $code = $_GET['code'];
            $redirectUri = admin_url() . 'options-general.php?page=wpslacksync-settings';
            $curl = new Curl();
            $server_output = $curl->url('https://slack.com/api/oauth.access')->binaryTransfer()->sslVerifyPeer(FALSE)->returnTransfer()->postFields('client_id=' . $clientId . '&client_secret=' . $clientSecret . '&code=' . $code . '&redirect_uri=' . $redirectUri)->curl();
            $result = json_decode($server_output, true);
            if (!$result['ok']) {
                if ($result['error'] == 'code_already_used') {
                    # query string is kept so code is invoked again with old code when hitting save
                    return;
                }
                set_transient('_wpslacksync_oauth_access_admin_error', 'Error: ' . $result['error'], 3);
                return;
            }
            update_option('_wpslacksync_token', $result['access_token']);
        }
    }

    static function query_user_list() {
        if (get_option('_wpslacksync_view_only_yn') !== 'on') {
            wp_die();
        }
        $token = get_option('_wpslacksync_token');
        $curl = new Curl();
        $server_output = $curl->url('https://slack.com/api/users.list')->binaryTransfer()->sslVerifyPeer(FALSE)->returnTransfer()->postFields('token=' . $token)->curl();
        echo $server_output;
        wp_die();
    }

    static function query_public_channel_list() {
        if (get_option('_wpslacksync_view_only_yn') !== 'on') {
            wp_die();
        }
        $token = get_option('_wpslacksync_token');
        $curl = new Curl();
        $server_output = $curl->url('https://slack.com/api/conversations.list')->binaryTransfer()->sslVerifyPeer(FALSE)->returnTransfer()->postFields('token=' . $token . '&exclude_archived=true')->curl();
        echo $server_output;
        wp_die();
    }

    static function query_public_channel_history() {
        if (get_option('_wpslacksync_view_only_yn') !== 'on') {
            wp_die();
        }
        $token = get_option('_wpslacksync_token');
        $channelId = $_GET['channelId'];
        $oldest = $_GET['oldest'];

        $transient_slug_name = 'wpslacksync_chh_' . $channelId . '_' . $oldest;
        if ( false === ( $server_output = get_transient( $transient_slug_name ) ) ) {
            $curl = new Curl();
            $server_output_temp = $curl->url('https://slack.com/api/conversations.history')->binaryTransfer()->sslVerifyPeer(FALSE)->returnTransfer()->postFields('token=' . $token . '&channel=' . $channelId . '&oldest=' . $oldest)->curl();
            $output_marked_as_cached = substr($server_output_temp, 0, -1) . ', "wpslacksync_cached": true}';
            $server_output = substr($server_output_temp, 0, -1) . ', "wpslacksync_cached": false}';
            set_transient($transient_slug_name, $output_marked_as_cached, self::channel_history_cache_retention_seconds);
        }

        echo $server_output;
        wp_die();
    }

    static function query_public_thread_history() {
        if (get_option('_wpslacksync_view_only_yn') !== 'on') {
            wp_die();
        }
        $token = get_option('_wpslacksync_token');
        $channelId = $_GET['channelId'];
        $threadTs = $_GET['threadTs'];
        $oldest = $_GET['oldest'];

        $transient_slug_name = 'wpslacksync_thh_' . $channelId . '_' . $threadTs . '_' . $oldest;
        if ( false === ( $server_output = get_transient( $transient_slug_name ) ) ) {
            # make sure the conversation is a public channel and can be shared with everyone
            $infoCurl = new Curl();
            $conversationInfoOutput = $infoCurl->url('https://slack.com/api/conversations.info')->binaryTransfer()->sslVerifyPeer(FALSE)->returnTransfer()->postFields('token=' . $token . '&channel=' . $channelId)->curl();
            $conversationInfo = json_decode($conversationInfoOutput, true);
            if ( ! $conversationInfo['ok'] || ! $conversationInfo['channel']['is_channel'] || $conversationInfo['channel']['is_private'] ) {
                $server_output = '{"error": true, "wpslacksync_cached": false}';
                $output_marked_as_cached = '{"error": true, "wpslacksync_cached": true}';
            } else {
                $curl = new Curl();
                $server_output_temp = $curl->url('https://slack.com/api/conversations.replies')->binaryTransfer()->sslVerifyPeer(FALSE)->returnTransfer()->postFields('token=' . $token . '&channel=' . $channelId . '&ts=' . $threadTs . '&oldest=' . $oldest . '&limit=100')->curl();
                $output_marked_as_cached = substr($server_output_temp, 0, -1) . ', "wpslacksync_cached": true}';
                $server_output = substr($server_output_temp, 0, -1) . ', "wpslacksync_cached": false}';
            }
            set_transient($transient_slug_name, $output_marked_as_cached, self::channel_history_cache_retention_seconds);
        }

        echo $server_output;
        wp_die();
    }
}
