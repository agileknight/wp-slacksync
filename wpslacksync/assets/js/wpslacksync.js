/* global _wpslacksync_settings, _wpslacksync_settings_l10n, ajaxurl */
var users = new Object(), channels = new Object(), privateChannelIds = new Object(), wpslacksync_token;
var wpslacksync = {};
var xsize,ysize,$pimg,boundx,boundy;
function updatePreview(c) {
    if (parseInt(c.w) > 0) {
      var rx = xsize / c.w;
      var ry = ysize / c.h;
      jQuery('#x').val(c.x);
      jQuery('#y').val(c.y);
      jQuery('#w').val(c.w);
      jQuery('#h').val(c.h);
      $pimg.css({
        width: Math.round(rx * boundx) + 'px',
        height: Math.round(ry * boundy) + 'px',
        marginLeft: '-' + Math.round(rx * c.x) + 'px',
        marginTop: '-' + Math.round(ry * c.y) + 'px'
      });
    }
}
function setUserPhoto() {
    var value_x = jQuery("#x").val();
    var value_y = jQuery("#y").val();
    var value_w = jQuery("#w").val();
    wpslacksync.state.profilePhotoUploadFormData.append( 'crop_x', value_x);
    wpslacksync.state.profilePhotoUploadFormData.append( 'crop_y', value_y);
    wpslacksync.state.profilePhotoUploadFormData.append( 'crop_w', value_w);
    jQuery("#dialog").html(_wpslacksync_settings_l10n.wait_for_photo_updating);
    wpslacksync.app.setPhoto();
}
jQuery(function ($) {
    $(function () {
        $('.wpslacksync-messages').css('max-height',$('.wpslacksync-container').height() - $('.wpslacksync-chatbox-container').height());
        $('.wpslacksync-messages').height($('.wpslacksync-container').height() - $('.wpslacksync-chatbox-container').height() - parseInt(100));

        var resizeFunc = function() {
            var containerWidthPx = Math.floor($('.wpslacksync-container').width()); // make sure we round the width to an exact integer amount to prevent a gap
            var sidebarWidthPx = $('.wpslacksync-left-sidebar').width();
            var contentWidthPx = parseInt(containerWidthPx) - parseInt(sidebarWidthPx);
            $('.wpslacksync-team').width(containerWidthPx);
            $('.wpslacksync-content').css('max-width',contentWidthPx);
            $('.wpslacksync-content').width(contentWidthPx);
            wpslacksync_fix_height();

            var position = $('.wpslacksync-sidebar-collapse').data('position');
            if (wpslacksync.shortcode.parseHideSidebar()) {
                if (position != 'collapsed') {
                    wpslacksync.app.toggleCollapse();
                }
                $('.wpslacksync-sidebar-collapse').hide();
            } else if (jQuery(window).width() > 780) {
                if (position != 'collapse') {
                    wpslacksync.app.toggleCollapse();
                }
            }
        };
        resizeFunc();
        $(window).on('resize', window, resizeFunc);

        if (wpslacksync.shortcode.parseCollapseSidebar()) {
            wpslacksync.app.toggleCollapse();
        }

        $('.wpslacksync-popup-user-profile-menu .about').click(function() {
                window.open('https://github.com/agileknight/wpslacksync', '_blank');
        });
        $("#dialog").dialog({
            autoOpen: false,
            resizable: false,
            modal: true,
            width: $(window).width() - 100,
            responsive: true,
            height: $(window).height() - 100,
            buttons: {
                "Cancel": function() {
                        $(this).dialog("close");
                }
            }
        });
    });
    $( "#set_photo" ).on( "click", function() {
        wpslacksync.state.profilePhotoUploadFormData = new FormData();
        var submit_btn = {
            "Cancel": function() {
                    $(this).dialog("close");
            }
        };
        jQuery("#dialog").html("<form runat='server' id='edit_member_profile_photo_form' name='edit_member_profile_photo_form' enctype='multipart/form-data' method='post'><div class='hidden_file_input' style='display: none'><input type='file' name='edit_member_profile_upload_photo' id='edit_member_profile_upload_photo' data-action='edit_member_profile_upload_photo' tabindex='-1' aria-hidden='true'></div></form>");
        $("#dialog .hidden_file_input input").trigger("click");
        $('.toggler-slack-menu').hide();
    });
    $(document).on( "change", "#edit_member_profile_photo_form input[type='file']", function() {
        wpslacksync.state.profilePhotoUploadFormData.append('image',this.files[0]);
        $('#dialog').dialog('option', 'height',800);
        $("#dialog").html("<div class='image_loader'><img src='<?php echo plugin_dir_url(WPSlackSync::$_FILE) ?>assets/images/loader.gif' /></div>");
        $("#dialog").dialog("option", "title", "Uploading your photo ...").dialog("open");
        var submit_btn = {};
        submit_btn[_wpslacksync_settings_l10n.button_submit] = setUserPhoto;
        submit_btn[_wpslacksync_settings_l10n.button_cancel] = function() {
                    jQuery(this).dialog("close");
        };
        if (this.files && this.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                $("#dialog").html("<form id='edit_member_profile_photo_crop_form' name='edit_member_profile_photo_crop_form' enctype='multipart/form-data' method='post'><div class='jcrop-box'><img class='main_image' src='"+e.target.result+"' id='target' alt='' /><div id='preview-pane'><div class='preview-container'><img src='"+e.target.result+"' class='jcrop-preview' alt='' /></div></div><div id='form-container'><input type='hidden' id='image_object' name='image_object' value='' /><input type='hidden' id='x' name='x'><input type='hidden' id='y' name='y'><input type='hidden' id='w' name='w'><input type='hidden' id='h' name='h'></div></div><input type='submit' tabindex='-1' style='position:absolute; top:-1000px'></form>");
                $("#dialog").dialog("option", "title", _wpslacksync_settings_l10n.crop_your_photo).dialog("open");
                $("#dialog").load(this.href, function() {
                        $(this).dialog("option", "title", $(this).find("h1").text());
                        $(this).find("h1").remove();
                });
                $('#dialog').dialog('option', 'buttons', submit_btn);
                var jcrop_api,
                $preview = $('#preview-pane');
                $pcnt = $('#preview-pane .preview-container');
                $pimg = $('#preview-pane .preview-container img');
                xsize = $pcnt.width();
                ysize = $pcnt.height();
                var image = new Image();
                image.src = e.target.result;
                image.onload = function() {
                    var img_height = parseInt(this.height);
                    var img_width = parseInt(this.width);
                    $('#target').Jcrop({
                      onChange: updatePreview,
                      onSelect: updatePreview,
                      trueSize: [img_width,img_height],
                      boxWidth: 750, boxHeight: 700,
                      aspectRatio: xsize / ysize
                    },function(){
                        var bounds = this.getBounds();
                        boundx = bounds[0];
                        boundy = bounds[1];
                        jcrop_api = this;
                        $preview.appendTo(jcrop_api.ui.holder);
                    });
                };
            }
            reader.readAsDataURL(this.files[0]);
        }
    });
});
function nl2br(str, is_xhtml) {
    var breakTag = (is_xhtml || typeof is_xhtml === 'undefined') ? '<br ' + '/>' : '<br>'; // Adjust comment to avoid issue on phpjs.org display
    return (str + '').replace(/([^>\r\n]?)(\r\n|\n\r|\r|\n)/g, '$1' + breakTag + '$2');
}
function wpslacksync_initialize_feed(config) {
    wpslacksync.config = config;
    wpslacksync.app.setUserList(function() {
        wpslacksync.app.loadChannelsList(function() {
            jQuery(function ($) {
                $('#wpslacksync-slack-login').attr('data-code', make_code());
                $(document).on('click', '.wpslacksync-a-channel', function () {
                    $('.wpslacksync-sidebar.wpslacksync-a-channel').removeClass('active');
                    $('.wpslacksync-sidebar.wpslacksync-a-channel[data-id="' + $(this).attr('data-id') + '"]').addClass('active');
                    var id = $(this).data('id');
                    wpslacksync.app.loadChannel(id, function() { return true; });
                });
                $(document).on('click', '.wpslacksync-thread-link', function () {
                    var channelId = $(this).data('channel');
                    var threadTs = $(this).data('thread');
                    wpslacksync.app.loadThread(channelId, threadTs, function() { return true; });
                });
                $(document).on('click', '.wpslacksync-channel-back-link', function () {
                    var channelId = $(this).data('channel');
                    wpslacksync.app.loadChannel(channelId, function() { return true; });
                });
                $(document).on('mouseover', '.wpslacksync-hover-toggle', function () {
                    var ts = $(this).attr('data-toggle');
                    var t = $(this).text();
                    $(this).text(ts).attr('data-toggle', t);
                });
                $(document).on('mouseout', '.wpslacksync-hover-toggle', function () {
                    var t = $(this).attr('data-toggle');
                    var ts = $(this).text();
                    $(this).text(t).attr('data-toggle', ts);
                });
                $(document).on('click', '.wpslacksync-sidebar-collapse', function (){
                    wpslacksync.app.toggleCollapse();
                });
            });
            setInterval(function () {
                wpslacksync.app.refreshActiveFeed(false);
            }, 3000);
        });
    });
}
function wpslacksync_initialize(config) {
    wpslacksync.gateway.feed = wpslacksync.gateway.client_feed;
    wpslacksync_initialize_feed(config);
    wpslacksync.app.loadUserProfile();

    if (!wpslacksync.shortcode.parseNoFileUpload()) {
        var attachButton = jQuery("#wpslacksync-chatbox-attach-button");
        var detachButton = jQuery("#wpslacksync-chatbox-detach-button");
        var fileInput = jQuery("#wpslacksync-chatbox-upload-input");
        attachButton.on('click', function() {
            fileInput.click();
        });
        detachButton.on('click', function() {
            wpslacksync.app.resetFileInput();
        });
        fileInput.on('change', function() {
            if (fileInput.val()) {
                attachButton.hide();
                detachButton.show();
                detachButton.fadeTo(100, 0.1).fadeTo(100, 1.0).fadeTo(100, 0.1).fadeTo(100, 1.0, function() {
                     jQuery('.wpslacksync-chatbox').focus();
                });
            } else {
                detachButton.hide();
                attachButton.show();
            }
        });
    }

    jQuery(document).on('keydown', '.wpslacksync-chatbox', function (e) {
        if (jQuery('.wpslacksync-chatbox').val().length > 0 && e.keyCode === 13 && !e.shiftKey) {
            e.preventDefault();
            if (!wpslacksync.shortcode.parseNoFileUpload()) {
                if (fileInput.val()) {
                    wpslacksync.app.uploadFileWithComment(fileInput[0].files[0], jQuery('.wpslacksync-chatbox').val());
                    return;
                }
            }
            wpslacksync.app.sendChat(jQuery('.wpslacksync-chatbox').val());
        }
    });

    jQuery(document).on('click', function() {
        jQuery(".wpslacksync-popup").hide();
    });
    jQuery(".wpslacksync-container").on('click', '.wpslacksync-popup-target', function(e) {
        jQuery(".wpslacksync-popup").hide();
        e.stopPropagation();
        var isRelative = jQuery(this).attr('data-popup-relative');
        var targetSelector = jQuery(this).attr('data-popup-selector');
        if (isRelative == 'parent') {
             jQuery(this).parent().find(targetSelector).show();
        } else {
             jQuery(targetSelector).show();
        }
    });
    jQuery(".wpslacksync-popup").on('click', function(e) {
        return false;
    });
    jQuery( "#logout_user" ).on('click', function() {
        wpslacksync.app.logout();
    });
}
function filter_text(text) {
    var mentions = text.replace(/<@(\w+)>/g, function (regex) {
        var user_id_regex = /[^<@]\w+[^>]/,
                user_id = user_id_regex.exec(regex),
                displayName = wpslacksync.app.getDisplayName(user_id);
        return "<i>@" + displayName + "</i>";
    });
    var mailto = mentions.replace(/<mailto:([^|<>]+)\|([^|<>]+)>/g, "<a href=\"mailto:$2\">$2</a>");
    var links_slack_sanitized = mailto.replace(/<((([A-Za-z]{3,9}:(?:\/\/)?)(?:[-;:&=\+\$,\w]+@)?[A-Za-z0-9.-]+|(?:www.|[-;:&=\+\$,\w]+@)[A-Za-z0-9.-]+)((?:\/[\+~%\/.\w-_]*)?\??(?:[-\+=&;%@.\w_]*)#?(?:[\w]*))?)\|([\w]+.[\w]+|[\w]+\.[\w]+\.[\w]+)>/g, "<a href='$1' target='_blank'>$5</a>");
    var links = links_slack_sanitized.replace(/<((([A-Za-z]{3,9}:(?:\/\/)?)(?:[-;:&=\+\$,\w]+@)?[A-Za-z0-9.-]+|(?:www.|[-;:&=\+\$,\w]+@)[A-Za-z0-9.-]+)((?:\/[\+~%\/.\w-_]*)?\??(?:[-\+=&;%@.\w_]*)#?(?:[\w]*))?)>/g, "<a href='$1' target='_blank'>$1</a>");
    var channel_links = links.replace(/<#(\w+)\|(\w+)>/g, "<i>#$2</i>");
    var emojis = wpslacksync.emojis.parse(channel_links);
    var bold = emojis.replace(/\*([\w&.\-]*)\*/g, '<strong>$1</strong>');
    var sanitized_text = bold;
    return sanitized_text;
}
function get_time(timestamp) {
    var date = new Date(timestamp * 1000);
    var h = date.getHours();
    var minutes = "0" + date.getMinutes();
    var hours, meridiem;
    if (h > 12) {
        hours = "0" + (h - 12);
        meridiem = "PM";
    } else {
        hours = "0" + h;
        meridiem = "AM";
    }
    return hours.substr(-2) + ':' + minutes.substr(-2) + ' ' + meridiem;
}
function get_timestamp(timestamp) {
    var date = new Date(timestamp * 1000);
    var h = date.getHours();
    var minutes = "0" + date.getMinutes();
    var seconds = "0" + date.getSeconds();
    var year = date.getFullYear();
    var month = date.getMonth() + 1;
    var day = date.getDate();
    var hours, meridiem;
    if (h > 12) {
        hours = "0" + (h - 12);
        meridiem = "PM";
    } else {
        hours = "0" + h;
        meridiem = "AM";
    }
    return year + '/' + month + '/' + day + ' at ' + hours.substr(-2) + ':' + minutes.substr(-2) + ':' + seconds.substr(-2) + ' ' + meridiem;
}
function wpslacksync_fix_height() {
    jQuery('.wpslacksync-left-sidebar').height(jQuery('.wpslacksync-container').height());
    jQuery('#wpslacksync-channel-list').css('overflow-x', 'auto');
    jQuery('#wpslacksync-user-profile').css('min-height', '100px');
    jQuery('#wpslacksync-user-profile').css('max-height', '150px');
    jQuery('#wpslacksync-user-profile').css('padding-top', '0');
    jQuery('#wpslacksync-channel-list').height(jQuery('.wpslacksync-container').height() - jQuery('#wpslacksync-user-profile').height());
}
function make_code()
{
    var text = "";
    var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
    for (var i = 0; i < 30; i++)
        text += possible.charAt(Math.floor(Math.random() * possible.length));
    return text;
}
function getParameterByName(name) {
    name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
    var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
            results = regex.exec(location.search);
    return results === null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
}
function display_error(message) {
    jQuery('#wpslacksync-display-info').removeClass('bg-success').addClass('bg-danger').html(message).slideDown().delay(1000).slideUp("slow");
}
function display_success(message) {
    jQuery('#wpslacksync-display-info').removeClass('bg-danger').addClass('bg-success').html(message).slideDown().delay(1000).slideUp("slow");
}
wpslacksync.gateway = {
    util: {
        ajaxCall: function(requestTemplate, success, error, parseResult) {
            var request = requestTemplate;
            request.success = function (dataReturned, textStatus, jqXHR) {
                var data = dataReturned;
                if (parseResult) {
                    data = JSON.parse(dataReturned);
                }
                if (wpslacksync.app.isDebug()) {
                    console.log(JSON.stringify(data, null, 2));
                }
                success(data);
            };
            request.error = function (jqXHR, textStatus, errorThrown) {
                    error(errorThrown);
            };
            if (wpslacksync.app.isDebug()) {
                console.log(JSON.stringify(request, null, 2));
            }
            jQuery.ajax(request);
        },
        connectToRtmApi: function() {
            if (wpslacksync.state.rtmConnection != '') {
                return;
            }
            wpslacksync.state.rtmConnection = 'connecting';
            wpslacksync.gateway.util.ajaxCall(
                {
                    url: 'https://slack.com/api/rtm.connect',
                    type: 'POST',
                    data: {
                        token: wpslacksync_token
                    },
                    cache: false
                },
                function(data) {
                    if (!data["ok"]) {
                        console.log("ERROR connecting to rtm-api: " + data["message"]);
                        if (data["error"] == "missing_scope") {
                            // we changed the scopes in recent updates, need to log in and oauth again
                            wpslacksync.app.logout();
                        }
                        return;
                    }
                    wpslacksync.state.rtmConnection = 'connected';
                    var socket = new WebSocket(data["url"]);
                    socket.onmessage = function(event) {
                        jsonData = JSON.parse(event.data);
                        if (jsonData["type"] != "message") {
                            return;
                        }
                        if(jsonData["hidden"]) {
                            return;
                        }
                        if (wpslacksync.app.isDebug()) {
                            console.log("WEBSOCKET MESSAGE:\n" + JSON.stringify(jsonData, null, 2));
                        }
                        if (jsonData["thread_ts"]) {
                            var threadId = wpslacksync.app.threadId(jsonData["channel"], jsonData["thread_ts"]);
                            if (!wpslacksync.state.newMessagesByThreadId.hasOwnProperty(threadId)) {
                                wpslacksync.state.newMessagesByThreadId[threadId] = [];
                            }
                            // conversations.replies returns older messages first
                            wpslacksync.state.newMessagesByThreadId[threadId].push(jsonData);

                            wpslacksync.app.updateAdditionalReplies(jsonData);
                        }
                        if (!jsonData["thread_ts"] || jsonData["subtype"] == "thread_broadcast") {
                             if (!wpslacksync.state.newMessagesByChannelId.hasOwnProperty(jsonData["channel"])) {
                                wpslacksync.state.newMessagesByChannelId[jsonData["channel"]] = [];
                            }
                            // conversations.history returns newer messages first
                            wpslacksync.state.newMessagesByChannelId[jsonData["channel"]].unshift(jsonData);
                        }
                    };
                },
                function(errorThrown) {
                    console.log("ERROR connecting to rtm-api: " + errorThrown);
                    wpslacksync.state.rtmConnection = 'connection-error';
                }
            );
        }
    },
    invitation: {
        sendInvitation: function(params) {
            wpslacksync.gateway.util.ajaxCall(
                {
                    url: ajaxurl,
                    data: {
                        action: 'invite',
                        fname: params.firstName,
                        lname: params.lastName,
                        email: params.email
                    }
                },
                params.success,
                params.error,
                true
            );
        },
        oauthAccess: function(params) {
            wpslacksync.gateway.util.ajaxCall(
                {
                    url: ajaxurl,
                    data: {
                         action: 'oauth_access',
                        authCode: params.authCode,
                        redirectUri: params.redirectUri
                    }
                },
                params.success,
                params.error,
                true
            );
        }
    },
    chat: {
        auth: function(params) {
            wpslacksync.gateway.util.ajaxCall(
                {
                    url:  'https://slack.com/api/auth.test',
                    type: 'POST',
                    data: {
                        token: wpslacksync_token
                    }
                },
                params.success,
                params.error
            );
        },
        queryUserInfo: function(params) {
            wpslacksync.gateway.util.ajaxCall(
                {
                    url:  'https://slack.com/api/users.info',
                    type: 'POST',
                    data: {
                        token: wpslacksync_token,
                        user: params.userId
                    }
                },
                params.success,
                params.error
            );
        },
        sendChat: function(params) {
            wpslacksync.gateway.util.ajaxCall(
                {
                    url:  'https://slack.com/api/chat.postMessage',
                    type: 'POST',
                    data: {
                        token: wpslacksync_token,
                        channel: params.channel,
                        thread_ts: params.threadTs,
                        text: params.text,
                        as_user: true
                    }
                },
                params.success,
                params.error
            );
        },
        uploadFileWithComment: function(params) {
            // special form data handling needed for file upload
            var formData = new FormData();
            formData.append('token', wpslacksync_token);
            formData.append('file', params.file);
            formData.append('filename', params.filename);
            formData.append('initial_comment', params.text);
            formData.append('channels', params.channel);
            formData.append('thread_ts', params.threadTs);

            wpslacksync.gateway.util.ajaxCall(
                {
                    url:  'https://slack.com/api/files.upload',
                    type: 'POST',
                    data: formData,
                    contentType: false, // needed for form data file upload handling
                    processData: false, // needed for form data file upload handling
                },
                params.success,
                params.error
            );
        }
    },
    feed: {
        queryUserList: function(params) {
            wpslacksync.gateway.util.ajaxCall(
                {
                    url:  ajaxurl,
                    data: {
                        action: 'query_user_list'
                    }
                },
                params.success,
                params.error,
                true
            );
        },
        queryPublicChannelsList: function(params) {
            wpslacksync.gateway.util.ajaxCall(
                {
                    url:  ajaxurl,
                    data: {
                        action: 'query_public_channel_list'
                    }
                },
                params.success,
                params.error,
                true
            );
        },
        queryPrivateChannelsList: function(params) {
            // private channels not available in view-only mode
            params.success()
        },
        queryPublicChannelHistory: function(params) {
            wpslacksync.gateway.util.ajaxCall(
                {
                    url:  ajaxurl,
                    data: {
                        action: 'query_public_channel_history',
                        channelId: params.channelId,
                        oldest: params.tsNewestLoadedMessage
                    },
                    cache: false,
                    timeout: 15000
                },
                params.success,
                params.error,
                true
            );
        },
        queryPrivateChannelHistory: function(params) {
            // private channels not available in view-only mode
            params.success()
        },
        queryThreadHistory: function(params) {
            wpslacksync.gateway.util.ajaxCall(
                {
                    url:  ajaxurl,
                    data: {
                        action: 'query_public_thread_history',
                        channelId: params.channelId,
                        threadTs: params.threadTs,
                        oldest: params.tsNewestLoadedMessage
                    },
                    cache: false,
                    timeout: 15000
                },
                params.success,
                params.error,
                true
            );
        }
    },
    client_feed: {
        queryUserList: function(params) {
            wpslacksync.gateway.util.ajaxCall(
                {
                    url: 'https://slack.com/api/users.list',
                    type: 'POST',
                    data: {
                        token: wpslacksync_token
                    }
                },
                params.success,
                params.error
            );
        },
        queryPublicChannelsList: function(params) {
            wpslacksync.gateway.util.ajaxCall(
                {
                    url: 'https://slack.com/api/conversations.list',
                    type: 'POST',
                    data: {
                        token: wpslacksync_token,
                        exclude_archived: true
                    }
                },
                params.success,
                params.error
            );
        },
        queryPrivateChannelsList: function(params) {
            wpslacksync.gateway.util.ajaxCall(
                {
                    url: 'https://slack.com/api/conversations.list',
                    type: 'POST',
                    data: {
                        token: wpslacksync_token,
                        types: 'private_channel',
                        exclude_archived: true
                    }
                },
                params.success,
                params.error
            );
        },
        queryChannelHistory: function(params) {
            wpslacksync.gateway.util.connectToRtmApi();
            if (!wpslacksync.state.fetchedHistoryByChannelId.hasOwnProperty(params.channelId)) {
                wpslacksync.gateway.util.ajaxCall(
                    {
                        url: 'https://slack.com/api/conversations.history',
                        type: 'POST',
                        data: {
                            token: wpslacksync_token,
                            channel: params.channelId,
                            oldest: params.tsNewestLoadedMessage
                        },
                        cache: false
                    },
                    function(data) {
                        params.success(data);
                        wpslacksync.state.fetchedHistoryByChannelId[params.channelId]=true;
                    },
                    params.error
                );
                // no concurrent checking when initializing
                return;
            }

            newerMessages = [];
            jQuery.each(wpslacksync.state.newMessagesByChannelId[params.channelId], function(index, value) {
                if (value["ts"] > params.tsNewestLoadedMessage) {
                    newerMessages.push(value);
                }
            });
            params.success({
                "ok": true,
                "messages": newerMessages
            });
            wpslacksync.state.newMessagesByChannelId[params.channelId] = [];
        },
        queryPublicChannelHistory: function(params) {
            wpslacksync.gateway.client_feed.queryChannelHistory(params);
        },
        queryPrivateChannelHistory: function(params) {
            wpslacksync.gateway.client_feed.queryChannelHistory(params);
        },
        queryThreadHistory: function(params) {
            wpslacksync.gateway.util.connectToRtmApi();
            if (!wpslacksync.state.fetchedHistoryByThreadId.hasOwnProperty(params.threadId)) {
                wpslacksync.gateway.util.ajaxCall(
                    {
                        url: 'https://slack.com/api/conversations.replies',
                        type: 'POST',
                        data: {
                            token: wpslacksync_token,
                            channel: params.channelId,
                            ts: params.threadTs,
                            oldest: params.tsNewestLoadedMessage,
                            limit: 100,
                        },
                    },
                    function(data) {
                        params.success(data);
                        wpslacksync.state.fetchedHistoryByThreadId[params.threadId]=true;
                    },
                    params.error
                );
                // no concurrent checking when initializing
                return;
            }

            newerMessages = [];
            jQuery.each(wpslacksync.state.newMessagesByThreadId[params.threadId], function(index, value) {
                if (value["ts"] > params.tsNewestLoadedMessage) {
                    newerMessages.push(value);
                }
            });
            params.success({
                "ok": true,
                "messages": newerMessages
            });
            wpslacksync.state.newMessagesByThreadId[params.threadId] = [];
        }
    },
    user:{
        setPhoto: function(params) {
            wpslacksync.state.profilePhotoUploadFormData.append( 'token', wpslacksync_token );
            wpslacksync.gateway.util.ajaxCall(
                {
                    url:  'https://slack.com/api/users.setPhoto',
                    type: 'POST',
                    data: wpslacksync.state.profilePhotoUploadFormData,
                    cache: false,
                    contentType: false,
                    processData: false,
                },
                params.success,
                params.error
            );
        }
    }
};
wpslacksync.templating = {
    templateCache: {},
    render: function(templateName, arguments) {
        if (!this.templateCache[templateName]) {
            var template = jQuery('#template-'+templateName).html();
            Mustache.parse(template);
            this.templateCache[templateName] = template;
        }
        return Mustache.render(this.templateCache[templateName], arguments);
    }
};
wpslacksync.tokenStore = (function () {
    var tokenStorageKey = 'wpslacksync_token';
    var tokenStorageCipher = 'vmZyyXVAq79wNUkXNATDaw85k';
    var isLocalStorageNameSupported = function() {
        var testKey = 'wpslacksync_test_key', storage = window.localStorage;
        try {
            storage.setItem(testKey, '1');
            storage.removeItem(testKey);
            return true;
        } catch (error) {
            return false;
        }
    };
    var bestStorage = (function() {
        if (isLocalStorageNameSupported()) {
            return Storages.localStorage;
        }
        return Storages.cookieStorage.setPath('/'); // Set path to '/' so that cookie is available on all sub-pages
    }());
    return {
        tokenInStore: function() {
            return !bestStorage.isEmpty(tokenStorageKey);
        },
        retrieveToken: function() {
            var payload = bestStorage.get(tokenStorageKey);
            try {
                // need to use paylaod because encrypted token string is valid JSON and will otherwise
                // be returned as an object instead of the original string
                var encryptedToken = payload.encryptedToken;
                var token = sjcl.decrypt(tokenStorageCipher, encryptedToken);
                return token;
            } catch (err) {
                return "decryption_error";
            }
        },
        storeToken: function(token) {
            try {
                var encryptedToken = sjcl.encrypt(tokenStorageCipher, token);
                bestStorage.set(tokenStorageKey, {encryptedToken: encryptedToken});
            } catch (err) {
                console.log("skipping storing oauth token as current browser/settings do not allow usage of local browser storage: " + err);
            }
        },
        clearToken: function() {
            bestStorage.remove(tokenStorageKey);
        }
    }
}());

wpslacksync.emojis = {
    parse: function(input) {
        var matcher = function(match, possibleEmojiName) {
            var mappedUnicode = wpslacksync_emoji_map[possibleEmojiName];
            if (mappedUnicode) {
                return '<span data-match="' + match + '">' + mappedUnicode + '</span>';
            }
            return match;
        };
        return input.replace(/:([a-z0-9_+-]+):/g, matcher);
    }
};

wpslacksync.emoticons = {
    parse: function(input) {
        var escape = function(string) {
            return string.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
        };
        var updated = input;
        jQuery.each(wpslacksync_emoticons_map, function(key, value) {
            var regex = new RegExp('(\\s+|^)' + escape(key) + '(\\s+|$)');
            while (regex.test(updated)) {
                // global match will not match whitespace appended in previous match
                updated = updated.replace(regex, '$1:' + value + ':$2');
            }
        });
        return updated;
    }
};

wpslacksync.shortcode = {
    shortcodeParam: function(name) {
        return jQuery('.wpslacksync-param-' + name).text();
    },
    parseDefaultChannel: function() {
        var defaultChannel = wpslacksync.shortcode.shortcodeParam('default_channel').trim();
        return defaultChannel;

    },
    parseAllowedPublicChannels: function() {
        var allowedChannels = []
        var deprecatedAllowedChannelsParam = wpslacksync.shortcode.shortcodeParam('allowed_channels').trim();
        var allowedChannelsParam = wpslacksync.shortcode.shortcodeParam('allowed_public_channels').trim();
        if (allowedChannelsParam == '') {
            allowedChannelsParam = deprecatedAllowedChannelsParam;
        }
        if (allowedChannelsParam != '') {
            var allowedChannelsRaw = allowedChannelsParam.split(/[ ,]+/);
            allowedChannelsRaw.forEach(function(elem) {
                var trimmedElem = elem.trim();
                if (trimmedElem) {
                    allowedChannels.push(trimmedElem);
                }
            });
        }
        return allowedChannels;
    },
    parseAllowedPrivateChannels: function() {
        var allowedChannels = []
        var allowedChannelsParam = wpslacksync.shortcode.shortcodeParam('allowed_private_channels').trim();
        if (allowedChannelsParam != '') {
            var allowedChannelsRaw = allowedChannelsParam.split(/[ ,]+/);
            allowedChannelsRaw.forEach(function(elem) {
                var trimmedElem = elem.trim();
                if (trimmedElem) {
                    allowedChannels.push(trimmedElem);
                }
            });
        }
        return allowedChannels;
    },
    parseNoFileUpload: function() {
        var trimmedParamValue = wpslacksync.shortcode.shortcodeParam('no_file_upload').trim();
        return trimmedParamValue == 'true';
    },
    parseHideSidebar: function() {
        var trimmedParamValue = wpslacksync.shortcode.shortcodeParam('hide_sidebar').trim();
        return trimmedParamValue == 'true';
    },
    parseCollapseSidebar: function() {
        var trimmedParamValue = wpslacksync.shortcode.shortcodeParam('collapse_sidebar').trim();
        return trimmedParamValue == 'true';
    }
}
wpslacksync.state = {
    activeChannelId: '',
    activeThreadTs: '',
    channelState:  {},
    threadState: {},
    rtmConnection: '',
    newMessagesByChannelId: {},
    newMessagesByThreadId: {},
    fetchedHistoryByChannelId: {},
    fetchedHistoryByThreadId: {},
    profilePhotoUploadFormData: new FormData()
}
wpslacksync.app = {
    getRealName: function(userId) {
        if (users[userId] && users[userId].realName) {
            return users[userId].realName;
        }
        return '<deleted user>';
    },
    getDisplayName: function(userId) {
        if (users[userId] && users[userId].displayName) {
            return users[userId].displayName;
        }
        return wpslacksync.app.getRealName(userId);
    },
    getUserImg: function(userId) {
        if (users[userId] && users[userId].img) {
            return users[userId].img;
        }
        return '';
    },
    getUserImgBig: function(userId) {
        if (users[userId] && users[userId].imgBig) {
            return users[userId].imgBig;
        }
        return '';
    },
    sendInvitation: function(fname, lname, email) {
        wpslacksync.gateway.invitation.sendInvitation({
            firstName:  fname,
            lastName:   lname,
            email:      email,
            success: function (data) {
                if (data['ok']) {
                    display_success(_wpslacksync_invite_l10n.invitation_sent);
                } else {
                    if (data.error === 'already_invited') {
                        display_error(_wpslacksync_invite_l10n.already_invited);
                    } else if (data.error === 'invalid_auth') {
                        display_error(_wpslacksync_invite_l10n.invalid_auth);
                    } else if (data.error === 'invalid_email') {
                        display_error(_wpslacksync_invite_l10n.invalid_email);
                    } else if (data.error === 'already_in_team') {
                        display_error(_wpslacksync_invite_l10n.already_in_team);
                    }
                }
            },
            error: function (error) {
                display_error(error);
            }
        });
    },
    validateEmail: function(email) {
        var re = /^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i;
        return re.test(email);
    },
    setUserProfile: function(userId) {
        wpslacksync.gateway.chat.queryUserInfo({
            userId: userId,
            success: function (data) {
                if (data.ok) {
                    jQuery('#wpslacksync-user-profile img').attr({src: data.user.profile.image_72, alt: data.user.name});
                    jQuery('.slack_menu_section .slack_menu_header .member_image').attr("style","background-image:url("+data.user.profile.image_72+")");
                    jQuery('.current_user_name.slack_menu_header_primary').text(data.user.real_name);
                    jQuery('.current_name.slack_menu_header_secondary').text('@' + data.user.name);
                    jQuery('#wpslacksync-user-profile span').text('@' + data.user.name);
                } else {
                    alert(data.error);
                }
            },
            error: function (error) {
                alert(error);
            }
        });
    },
    loadUserProfile: function() {
        wpslacksync.gateway.chat.auth({
            success: function (data) {
                if (!data['ok']) {
                    jQuery('.wpslacksync-content').html("error getting user id: " + data['error']);
                    return;
                }
                wpslacksync.app.setUserProfile(data.user_id);
            },
            error: function(error) {
                alert(error);
            }
        });
    },
    setUserList: function(callback) {
        wpslacksync.gateway.feed.queryUserList({
            success: function (data) {
                if (!data['ok']) {
                    jQuery('.wpslacksync-content').html("error getting user list: " + data['error']);
                    return;
                }
                jQuery.each(data['members'], function (i) {
                    users[data['members'][i]['id']] = {
                        img: data['members'][i]['profile']['image_32'],
                        imgBig: data['members'][i]['profile']['image_192'],
                        displayName: data['members'][i]['profile']['display_name'],
                        realName: data['members'][i]['profile']['real_name']
                    };
                });
                callback();
            },
            error: function(error) {
                alert(error);
            }
        });
    },
    loadChannelTitle: function(channelId) {
        jQuery('#wpslacksync-channel-name').html(wpslacksync.app.renderChannelName(channelId)).removeClass('wpslacksync-channel-back-link');
        jQuery('#wpslacksync-back-to-channel').hide();
    },
    renderChannelName: function(channelId) {
        var channelName = channels[channelId];
        var isPrivate = privateChannelIds[channelId] === true;
        var iconChar = '#';
        if (isPrivate) {
            iconChar = ':lock:';
        }
        return wpslacksync.emojis.parse(iconChar + channelName);
    },
    loadThreadTitle: function(channelId) {
        jQuery('#wpslacksync-channel-name').html('Back to ' + wpslacksync.app.renderChannelName(channelId)).addClass('wpslacksync-channel-back-link').data('channel', channelId);
        jQuery('#wpslacksync-back-to-channel').data('channel', channelId).show();
    },
    toggleCollapse: function() {
        jQuery(function ($) {
            var position = $('.wpslacksync-sidebar-collapse').data('position');
            if (position == 'collapsed'){
                $('.wpslacksync-sidebar-collapse').data('position', 'collapse');
                $('.wpslacksync-left-sidebar').animate({width: '100px', 'min-width': '100px','max-width':'100px', opacity: 1}, 500);
                $('.wpslacksync-content').animate({width: ($('.wpslacksync-container').width() - parseInt(102)),'max-width': ($('.wpslacksync-container').width() - parseInt(102))}, 500);
            } else{
                $('.wpslacksync-sidebar-collapse').data('position', 'collapsed')
                var position = $('.wpslacksync-sidebar-collapse').data('position');
                $('.wpslacksync-content').css('max-width',$('.wpslacksync-container').width()+'px');
                $('.wpslacksync-left-sidebar').animate({width: '0px', 'min-width': '0px','max-width': '0px', opacity: 0}, 500);
                $('.wpslacksync-content').animate({width: $('.wpslacksync-container').width()}, 500);
            }
        });
    },
    loadChannelsList: function(callback) {
        var allChannels = [];
        var publicChannelsAdded = false;
        var privateChannelsAdded = false;
        var sortChannels = function() {
            allChannels.sort(function(a, b) {
                return (a.name > b.name) - (a.name < b.name);
            });
        }
        var selectInitialActiveChannel = function() {
            var defaultChannel = wpslacksync.shortcode.parseDefaultChannel();
            var activeSelected = false;
            jQuery.each(allChannels, function (i, channel) {
                if (activeSelected) {
                    return;
                }
                if (channel['name'] == defaultChannel) {
                    channel['isInitiallyActive'] = true;
                    activeSelected = true;
                }
            });
            if (!activeSelected && allChannels.length > 0) {
                allChannels[0]['isInitiallyActive'] = true;
                activeSelected = true;
            }
            if (!activeSelected) {
                jQuery('.wpslacksync-content').html("no valid channels found. please check shortcode parameters!");
                return;
            }
        }
        var renderChannels = function() {
            jQuery('#wpslacksync-channel-list').empty();
            jQuery.each(allChannels, function (i, channel) {
                channels[channel.name] = channel.id;
                channels[channel.id] = channel.name;
                var activePart = '';
                if (channel.isInitiallyActive) {
                    activePart = ' active';
                }
                if (channel.isPrivate) {
                    privateChannelIds[channel.id] = true;
                }
                jQuery('<p></p>').html('<a class="wpslacksync-a" data-id="' + channel.id + '">' + wpslacksync.app.renderChannelName(channel.id) + '</a>').attr('data-id', channel.id).addClass('wpslacksync-sidebar wpslacksync-a-channel' + activePart).appendTo('#wpslacksync-channel-list');
            });
            var first_channel = jQuery('.wpslacksync-sidebar.wpslacksync-a-channel.active');
            wpslacksync.app.loadChannel(first_channel.data('id'), function() { return true; });
        }
        var completionCheck = function() {
            if (!publicChannelsAdded) {
                return;
            }
            if (!privateChannelsAdded) {
                return;
            }
            sortChannels();
            selectInitialActiveChannel();
            renderChannels();
            callback();
        }
        wpslacksync.gateway.feed.queryPublicChannelsList({
            success: function (data) {
                var allowedChannels = wpslacksync.shortcode.parseAllowedPublicChannels();
                if (!data['ok']) {
                    jQuery('.wpslacksync-content').html("error loading channel list: " + data['error']);
                    return;
                }
                jQuery.each(data['channels'], function (i, channel) {
                    if (allowedChannels.length > 0 && jQuery.inArray(channel['name'], allowedChannels) === -1) {
                        return;
                    }
                    if (!channel['is_archived'] && channel['is_member']) {
                        var isInitiallyActive = false;
                        allChannels.push({
                            id: channel['id'],
                            name: channel['name'],
                            isPrivate: false,
                            isInitiallyActive: false
                        });
                    }
                });
                publicChannelsAdded = true;
                completionCheck();
            },
            error: function (error) {
                jQuery('#wpslacksync-channel-list').html(error);
            }
        });
        if (wpslacksync.config.enablePrivateChannels && !wpslacksync.config.viewOnly) {
            wpslacksync.gateway.feed.queryPrivateChannelsList({
                success: function (data) {
                    var allowedChannels = wpslacksync.shortcode.parseAllowedPrivateChannels();
                    if (!data['ok']) {
                        console.log("ERROR: loading private channel list failed: " + data['error']);
                        privateChannelsAdded = true;
                        return;
                    }
                    jQuery.each(data['channels'], function (i, channel) {
                        if (allowedChannels.length > 0 && jQuery.inArray(channel['name'], allowedChannels) === -1) {
                            return;
                        }
                        if (!channel['is_archived']) {
                            allChannels.push({
                                id: channel['id'],
                                name: channel['name'],
                                isPrivate: true,
                                isInitiallyActive: false
                            });
                        }
                    });
                    privateChannelsAdded = true;
                    completionCheck();
                },
                error: function (error) {
                    console.log("ERROR: loading private channel list failed: " + error);
                    privateChannelsAdded = true;
                }
            });
        } else {
            privateChannelsAdded = true;
        }
    },
    refreshActiveFeed: function(forceScroll) {
        var activeChannelId = wpslacksync.state.activeChannelId;
        if (!activeChannelId) {
            // no active channel
            return;
        }

        var shouldScroll = function() {
            if (forceScroll) {
                return true;
            }
            var messages = jQuery('.wpslacksync-messages');
            var scrollHeight = messages.prop('scrollHeight');
            var scrollLeft = scrollHeight - messages.scrollTop();
            var scroll = messages.outerHeight() === scrollLeft ? true : false;
            return scroll;
        };

        var activeThreadTs = wpslacksync.state.activeThreadTs;
        if (activeThreadTs) {
            wpslacksync.app.loadThread(activeChannelId, activeThreadTs, shouldScroll);
        } else {
            wpslacksync.app.loadChannel(activeChannelId, shouldScroll);
        }
    },
    renderMessages: function(messages, additionalRepliesByParentTs = {}, renderThreadInfo, channelId) {
        var isReply = function(message) {
            return message.thread_ts && message.thread_ts != message.ts;
        }
        var getReplyCount = function(message) {
            return (message['reply_count'] || 0) + (additionalRepliesByParentTs[message['ts']] || 0);
        }
        var isThreadedMessage = function(message) {
            return message.thread_ts;
        }
        var isThreadParent = function(message) {
            return (isThreadedMessage(message) && !isReply(message)) || getReplyCount(message) > 0;
        }
        var isThreadBroadcast = function(message) {
            return message.subtype == "thread_broadcast";
        }
        var getTargetThreadTs = function(message) {
            if (isThreadedMessage(message)) {
                return message.thread_ts;
            }
            return message.ts;
        }
        var i = messages.length - 1;
        var messageItems = [];
        jQuery.each(messages, function () {
            var message = messages[i];
            try {
                var ts = message['ts'];
                var threadInfo = {};
                if (renderThreadInfo) {
                    threadInfo = {
                        showThreadInfo: true,
                        showReply: !wpslacksync.config.viewOnly,
                        numberOfReplies: getReplyCount(message),
                        isThreadBroadcast: isThreadBroadcast(message),
                        channelId: channelId,
                        threadTs: getTargetThreadTs(message)
                    };
                }
                var originalText = message['text'];
                if (originalText == null) {
                    originalText = '';
                }
                var text = filter_text(nl2br(originalText));
                var timestamp = get_timestamp(message['ts']);
                var time_ = get_time(message['ts']);
                var time = _wpslacksync_settings.mainpanel_timestamp_enabled ? time_ : '';
                var channelname = jQuery('#wpslacksync-channel-list').find('.wpslacksync-sidebar.wpslacksync-a-channel.active a').text();
                if (message['subtype'] === 'bot_message') {
                    var attachments = message['attachments'];
                    if (jQuery.isArray(attachments)) {
                        attachments.forEach(function(elem) {
                            text += filter_text(nl2br(elem['fallback']+"\n"));
                        });
                    }
                    messageItems.push({
                        type: 'bot',
                        ts: ts,
                        color: _wpslacksync_settings.mainpanel_botmsg_color,
                        username: message['username'],
                        text: text,
                        time: time,
                        timestamp: timestamp,
                        thread: threadInfo
                    });
                } else if (message['subtype'] === 'channel_join' || message['subtype'] === 'group_join') {
                    messageItems.push({
                        type: 'join',
                        ts: ts,
                        username_color: _wpslacksync_settings.mainpanel_channeljoin_color,
                        username: wpslacksync.app.getDisplayName(message['user']),
                        realname: wpslacksync.app.getRealName(message['user']),
                        message_color: _wpslacksync_settings.mainpanel_channeljoin_color,
                        img: wpslacksync.app.getUserImg(message['user']),
                        img_big: wpslacksync.app.getUserImgBig(message['user']),
                        text: _wpslacksync_settings_l10n.joined_channel_x + ' ' + channelname,
                        time: time,
                        timestamp: timestamp,
                        thread: threadInfo
                    });
                } else if (message['files'] && message['files'].length > 0) {
                    if (text) {
                        text += '<br/>';
                    }
                    var file = message['files'][0]; // currently support only first file
                    var fileAction = _wpslacksync_settings_l10n.file_action_uploaded;
                    if (!message['upload']) {
                        fileAction = _wpslacksync_settings_l10n.file_action_shared;
                    }
                    var userId = message['user'];
                    var fileUrl = file['url_private'];
                    messageItems.push({
                        type: 'with_file',
                        ts: ts,
                        username_color: _wpslacksync_settings.mainpanel_username_color,
                        username: wpslacksync.app.getDisplayName(userId),
                        realname: wpslacksync.app.getRealName(userId),
                        message_color: _wpslacksync_settings.mainpanel_msg_color,
                        img: wpslacksync.app.getUserImg(userId),
                        img_big: wpslacksync.app.getUserImgBig(userId),
                        text: text,
                        time: time,
                        timestamp: timestamp,
                        file: file,
                        file_url: fileUrl,
                        file_action: fileAction,
                        image_url: imageUrl,
                        image_width: imageWidth,
                        image_height: imageHeight,
                        thread: threadInfo
                    });
                } else if (message['attachments'] && message['attachments'].length > 0) {
                    if (text) {
                        text += '<br/>';
                    }
                    var attachment = message['attachments'][0]; // currently support only first attachment
                    var userId = message['user'];
                    if (attachment['is_share']) {
                        var fallbackText = filter_text(nl2br(attachment['fallback']));
                        messageItems.push({
                            type: 'with_share_attachment',
                            ts: ts,
                            username_color: _wpslacksync_settings.mainpanel_username_color,
                            username: wpslacksync.app.getDisplayName(userId),
                            realname: wpslacksync.app.getRealName(userId),
                            message_color: _wpslacksync_settings.mainpanel_msg_color,
                            img: wpslacksync.app.getUserImg(userId),
                            img_big: wpslacksync.app.getUserImgBig(userId),
                            text: text,
                            time: time,
                            timestamp: timestamp,
                            attachment: attachment,
                            fallbackText: fallbackText,
                            thread: threadInfo
                        });
                    }
                    else if (attachment['image_url'] && attachment['image_width'] && attachment['image_height']) {
                        var imageUrl = attachment['image_url'];
                        var imageWidth = attachment['image_width'];
                        var imageHeight = attachment['image_height'];
                        messageItems.push({
                            type: 'with_image_attachment',
                            ts: ts,
                            username_color: _wpslacksync_settings.mainpanel_username_color,
                            username: wpslacksync.app.getDisplayName(userId),
                            realname: wpslacksync.app.getRealName(userId),
                            message_color: _wpslacksync_settings.mainpanel_msg_color,
                            img: wpslacksync.app.getUserImg(userId),
                            img_big: wpslacksync.app.getUserImgBig(userId),
                            text: text,
                            time: time,
                            timestamp: timestamp,
                            attachment: attachment,
                            image_url: imageUrl,
                            image_width: imageWidth,
                            image_height: imageHeight,
                            thread: threadInfo
                        });
                    } else if (attachment['thumb_url'] && attachment['thumb_width'] && attachment['thumb_height']) {
                        var imageUrl = attachment['thumb_url'];
                        var imageWidth = attachment['thumb_width'];
                        var imageHeight = attachment['thumb_height'];
                        messageItems.push({
                            type: 'with_image_attachment',
                            ts: ts,
                            username_color: _wpslacksync_settings.mainpanel_username_color,
                            username: wpslacksync.app.getDisplayName(userId),
                            realname: wpslacksync.app.getRealName(userId),
                            message_color: _wpslacksync_settings.mainpanel_msg_color,
                            img: wpslacksync.app.getUserImg(userId),
                            img_big: wpslacksync.app.getUserImgBig(userId),
                            text: text,
                            time: time,
                            timestamp: timestamp,
                            attachment: attachment,
                            image_url: imageUrl,
                            image_width: imageWidth,
                            image_height: imageHeight,
                            thread: threadInfo
                        });
                    } else {
                        messageItems.push({
                            type: 'with_file_attachment',
                            ts: ts,
                            username_color: _wpslacksync_settings.mainpanel_username_color,
                            username: wpslacksync.app.getDisplayName(userId),
                            realname: wpslacksync.app.getRealName(userId),
                            message_color: _wpslacksync_settings.mainpanel_msg_color,
                            img: wpslacksync.app.getUserImg(userId),
                            img_big: wpslacksync.app.getUserImgBig(userId),
                            text: text,
                            time: time,
                            timestamp: timestamp,
                            attachment: attachment,
                            thread: threadInfo
                        });
                    }
                } else {
                    messageItems.push({
                        type: 'regular',
                        ts: ts,
                        username_color: _wpslacksync_settings.mainpanel_username_color,
                        username: wpslacksync.app.getDisplayName(message['user']),
                        realname: wpslacksync.app.getRealName(message['user']),
                        message_color: _wpslacksync_settings.mainpanel_msg_color,
                        img: wpslacksync.app.getUserImg(message['user']),
                        img_big: wpslacksync.app.getUserImgBig(message['user']),
                        text: text,
                        time: time,
                        timestamp: timestamp,
                        thread: threadInfo
                    });
                }
            } catch (err) {
                console.log("ERROR: processing message: " + err);
                console.log(JSON.stringify(message, null, 2));
            }
            i--;
        });
        return messageItems;
    },
    updateAdditionalReplies: function(message) {
        var additionalRepliesByParentTs = wpslacksync.state.channelState[message.channel].additionalRepliesByParentTs
        if (!additionalRepliesByParentTs[message.thread_ts]) {
            additionalRepliesByParentTs[message.thread_ts]  = 0;
        }
        additionalRepliesByParentTs[message.thread_ts] += 1;
    },
    loadChannel: function(id, shouldScroll) {
        var handleError = function(error, type) {
            wpslacksync.state.channelState[id].isWaitingForResponse = false;
            console.log('ERROR: loading ' + type + ' channel history: '+ error);
        }
        var handleSuccess = function(data, type) {
            wpslacksync.state.channelState[id].isWaitingForResponse = false;
            if (!data['ok']) {
                handleError(data['error'], type);
                return;
            }

            // sorted newest first, merge new messages to the front
            var mergedArray = jQuery.merge([], data['messages']);
            jQuery.merge(mergedArray, wpslacksync.state.channelState[id].messages);
            wpslacksync.state.channelState[id].messages = mergedArray;
            wpslacksync.state.channelState[id].tsNewestLoadedMessage = wpslacksync.state.channelState[id].messages[0].ts; // sorted newest first

            var messageItems = wpslacksync.app.renderMessages(wpslacksync.state.channelState[id].messages, wpslacksync.state.channelState[id].additionalRepliesByParentTs, true, id);
            var messagesDiv = jQuery('.wpslacksync-messages');
            // check with old div scrolling state before appending new messages
            var scrollAfterRendering = shouldScroll();
            wpslacksyncApp.renderMessagesDisplay(messageItems, messagesDiv[0]);
            if(scrollAfterRendering) {
                messagesDiv.scrollTop(messagesDiv.prop('scrollHeight'));
                wpslacksync_fix_height();
            }
        };
        wpslacksync.state.activeChannelId = id;
        wpslacksync.state.activeThreadTs = '';
        if (!wpslacksync.state.channelState[id]) {
            wpslacksync.state.channelState[id] = {
                tsNewestLoadedMessage: '0',
                messages: [],
                threadsByParentTs: {},
                additionalRepliesByParentTs: {},
                isWaitingForResponse: false
            };
        }
        wpslacksync.app.loadChannelTitle(id);
        if (wpslacksync.state.channelState[id].isWaitingForResponse) {
            // still waiting for response, do not send again and mess up loaded message state
            return;
        }
        wpslacksync.state.channelState[id].isWaitingForResponse = true;
        if (privateChannelIds[id]) {
            var type = 'private';
            wpslacksync.gateway.feed.queryPrivateChannelHistory({
                channelId: id,
                tsNewestLoadedMessage: wpslacksync.state.channelState[id].tsNewestLoadedMessage,
                success: function (data) {
                    handleSuccess(data, type);
                },
                error: function (error) {
                    handleError(error, type)
                }
            });
        } else {
            var type = 'public';
            wpslacksync.gateway.feed.queryPublicChannelHistory({
                channelId: id,
                tsNewestLoadedMessage: wpslacksync.state.channelState[id].tsNewestLoadedMessage,
                success: function (data) {
                    handleSuccess(data, type);
                },
                error: function (error) {
                    handleError(error, type)
                }
            });
        }
    },
    threadId: function(channelId, threadTs) {
        return channelId + "_" + threadTs;
    },
    loadThread: function(channelId, threadTs, shouldScroll) {
        var threadId = wpslacksync.app.threadId(channelId, threadTs);
        var handleError = function(error) {
            wpslacksync.state.threadState[threadId].isWaitingForResponse = false;
            console.log('ERROR: loading thread history: '+ error);
        }
        var handleSuccess = function(data) {
            wpslacksync.state.threadState[threadId].isWaitingForResponse = false;
            if (!data['ok']) {
                handleError(data['error']);
                return;
            }

            // sorted oldest first, merge new messages to the front individually
            data['messages'].forEach(function (message, index) {
                // conversations.replies always returns parent message first, remove it unless it is the first call or the message comes from rtm
                if (index == 0 && wpslacksync.state.threadState[threadId].messages.length > 0 && !message['event_ts']) {
                    return;
                }
                wpslacksync.state.threadState[threadId].messages.unshift(message);
            });
            wpslacksync.state.threadState[threadId].tsNewestLoadedMessage = wpslacksync.state.threadState[threadId].messages[0].ts; // sorted newest first

            var messageItems = wpslacksync.app.renderMessages(wpslacksync.state.threadState[threadId].messages);
            var messagesDiv = jQuery('.wpslacksync-messages');
            // check with old div scrolling state before appending new messages
            var scrollAfterRendering = shouldScroll();
            wpslacksyncApp.renderMessagesDisplay(messageItems, messagesDiv[0]);
            if(scrollAfterRendering) {
                messagesDiv.scrollTop(messagesDiv.prop('scrollHeight'));
                wpslacksync_fix_height();
            }
        };
        wpslacksync.state.activeChannelId = channelId;
        wpslacksync.state.activeThreadTs = threadTs;
        if (!wpslacksync.state.threadState[threadId]) {
            wpslacksync.state.threadState[threadId] = {
                tsNewestLoadedMessage: '0',
                messages: [],
                isWaitingForResponse: false
            };
        }
        wpslacksync.app.loadThreadTitle(channelId);
        if (wpslacksync.state.threadState[threadId].isWaitingForResponse) {
            // still waiting for response, do not send again and mess up loaded message state
            return;
        }
        wpslacksync.state.threadState[threadId].isWaitingForResponse = true;
        wpslacksync.gateway.feed.queryThreadHistory({
            channelId: channelId,
            threadTs: threadTs,
            threadId: threadId,
            tsNewestLoadedMessage: wpslacksync.state.threadState[threadId].tsNewestLoadedMessage,
            success: function (data) {
                handleSuccess(data);
            },
            error: function (error) {
                handleError(error)
            }
        });
    },
    sendChat: function(text) {
        jQuery('.wpslacksync-chatbox').attr('disabled', true);
        wpslacksync.gateway.chat.sendChat({
            text: wpslacksync.emoticons.parse(text),
            channel: wpslacksync.state.activeChannelId,
            threadTs: wpslacksync.state.activeThreadTs,
            success: function (data) {
                jQuery('.wpslacksync-chatbox').val("");
                wpslacksync.app.refreshActiveFeed(true);
                jQuery('.wpslacksync-chatbox').attr('disabled', false);
                jQuery('.wpslacksync-chatbox').focus();
            },
            error: function (error) {
                console.log(error);
                jQuery('.wpslacksync-chatbox').attr('disabled', false);
                jQuery('.wpslacksync-chatbox').focus();
            }
        });
    },
    uploadFileWithComment: function(file, text) {
        jQuery('.wpslacksync-chatbox').attr('disabled', true);
        jQuery('.wpslacksync-chatbox').val("uploading");
        wpslacksync.gateway.chat.uploadFileWithComment({
            file: file,
            filename: file.name,
            text: text,
            channel: wpslacksync.state.activeChannelId,
            threadTs: wpslacksync.state.activeThreadTs,
            success: function (data) {
                if (!data['ok']) {
                    console.log(data['error']);
                }
                jQuery('.wpslacksync-chatbox').val("");
                wpslacksync.app.refreshActiveFeed(true);
                wpslacksync.app.resetFileInput();
                jQuery('.wpslacksync-chatbox').attr('disabled', false);
                jQuery('.wpslacksync-chatbox').focus();
            },
            error: function (error) {
                console.log(error);
                jQuery('.wpslacksync-chatbox').val(text); // reset from 'uploading' to original value
                jQuery('.wpslacksync-chatbox').attr('disabled', false);
                jQuery('.wpslacksync-chatbox').focus();
            }
        });
    },
    resetFileInput: function() {
        if (wpslacksync.shortcode.parseNoFileUpload()) {
            return;
        }
        var fileInput = jQuery("#wpslacksync-chatbox-upload-input");
        fileInput.closest('form').get(0).reset();
        fileInput.change();
    },
    isDebug: function() {
        if (wpslacksync.app.getUrlParameter("wpslacksync_debug")) {
            return true
        }
        return false
    },
    getUrlParameter: function(sParam) {
        var sPageURL = decodeURIComponent(window.location.search.substring(1)),
        sURLVariables = sPageURL.split('&'),
        sParameterName,
        i;
        for (i = 0; i < sURLVariables.length; i++) {
            sParameterName = sURLVariables[i].split('=');

            if (sParameterName[0] === sParam) {
                return sParameterName[1] === undefined ? true : sParameterName[1];
            }
        }
    },
    setPhoto: function (){
         wpslacksync.gateway.user.setPhoto({
            success: function (data) {
                jQuery("#dialog").dialog( "close" );
                jQuery('#wpslacksync-user-profile img').attr({src: data.profile.image_72});
                jQuery('.slack_menu_section .slack_menu_header .member_image').attr("style","background-image:url("+data.profile.image_72+")");
            },
            error: function (error) {
                console.log(error);
            }
        });
    },
    logout: function(){
        wpslacksync.tokenStore.clearToken();
        location.reload();
    }
};