=== WPSlackSync ===
Requires at least: 3.8
Tested up to: 5.7
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

WordPress Slack Integration.

== Description ==
A WordPress plugin that synchronizes your Slack channels in WordPress!

== Changelog ==

= 1.11.0 =
* Cleanup code for publishing as an open-source project
* Remove EDD licensing and update code

= 1.10.3 =
* Fix Slack API access invalid_auth errors

= 1.10.2 =
* Remove inline image display of slack uploads
* Fix setting profile photo again in the same session

= 1.10.1 =
* Add additional back to channel link from threads

= 1.10.0 =
* Implement Slack message threads

= 1.9.2 =
* Exclude archived channels when querying the Slack API
* Add more css classes to shared message rendering

= 1.9.1 =
* Fix rendering of email addresses in messages
* Add basic display of shared messages

= 1.9.0 =
* Use oauth flow for admin API token instead of deprecated legacy test token
* Script in shortcode waits for loaded scripts
* Fix php notice
* Fix jquery migrate deprecation warning

= 1.8.10 =
* Use new Slack conversations API

= 1.8.9 =
* Fix emoji display
* Parse emoticons when sending message
* Remove deprecated sellwire plugin update handling

= 1.8.8 =
* Fix image attachment display after Slack API change

= 1.8.7 =
* Add collapse_sidebar shortcode parameter

= 1.8.6 =
* Security: Fix leaked token

= 1.8.5 =
* Improve invitation consent error message and highlighting

= 1.8.4 =
* Improve invite form header font size on mobile

= 1.8.3 =
* View-only mode no longer loads private channels to improve privacy
* Add custom GDPR consent HTML option with checkbox validation for invite form
* Improve handling of hidden thread update messages

= 1.8.2 =
* Fix jquery accessed as dollar sign synax error

= 1.8.1 =
* Fix errors from token with missing rtm api scope by logging out

= 1.8.0 =
* Use rtm api to receive new messages instead of web api
* Cache server-side channel history requests in view-only mode
* Fix sorting of new messages when changing active channel

= 1.7.2 =
* Fix refresh spamming log with errors when no channel is active

= 1.7.1 =
* Fix page scroll position being lost when clicking join this chat button

= 1.7.0 =
* Use display name instead of deprecated username
* Fix private channel displaying messages multiple times
* Fix channel switch not working when no new messages are loaded
* Fix channel message list loading errors breaking chat
* Prevent simultaneous loading requests per channel
* Focus message input again after sending message

= 1.6.4 =
* Fix scrolling for resized message area
* Improve message loading, only load new messages
* Make no_file_upload option more robust

= 1.6.3 =
* Fix method return value in write context php error

= 1.6.2 =
* Chat session storage in Safari private browsing mode persists across sub-pages

= 1.6.1 =
* Fix chat session storage in Safari private browsing mode

= 1.5.10 =
* Support EDD licensing and plugin updating

= 1.5.9 =
* Add hide_sidebar shortcode parameter

= 1.5.8 =
* Fix resizing when toggling screen orientation
* Fix toggling collapse on resize to large width

= 1.5.7 =
* Use site protocol to load font-awesome (not force http)

= 1.5.6 =
* Fix broken styling options for invite form and mobile collapse button

= 1.5.5 =
* Allow overwrite of view-only and mode through shortcode parameters

= 1.5.4 =
* Fix PHP code reference to non-existent variable

= 1.5.3 =
* Add option to use only email for invite form

= 1.5.2 =
* Fix invite form selectors selecting unrelated form elements
* Fix user profile popup styling for file upload messages

= 1.5.1 =
* Add left sidebar width styling option
* Fix styling not applied for container border color and border radius
* Fix styling not applied for left sidebar background color and channel bottom border color
* Fix display gap in active channel header background

= 1.5.0 =
* Add profile upload functionality on desktop
* Remove body white text color styling that was accidentally included

= 1.4.8 =
* Add user profile popup
* Fix showing end of day line after first message of day
* Add allowed_private_channels shortcode parameter
* Support more emojis
* Remove iOS/Android emoji style switch
* Improve input text color styling
* Fix php notice about undefined constant

= 1.4.7 =
* Fix php error with older verions of php

= 1.4.6 =
* Only include scripts/css when on page with shortcode
* Add passive mode for view-only without invite/login
* Only display horizontal line between messages at end of day
* Add option to use custom stylesheet
* Show real name when hovering username

= 1.4.5 =
* Add basic handling of bot message attachments using text-only fallback

= 1.4.4 =
* Fix default chat width and height

= 1.4.3 =
* Add options to set chat width and height
* Add POT translation template (e.g. for Loco Translate)
* Add about link to user profile menu
* Add additional help info in plugin settings
* Fix image loading messing up scroll position
* Fix broken emoji style option

= 1.4.2 =
* Fix images not showing in Internet Explorer
* Add color pickers for color settings
* Add new user menu with logout link
* Fix saving in settings to lose the active tab

= 1.4.1 =
* Enable license check for automatic plugin updates
* Use different plugin updates provider

= 1.4.0 =
* Add option to enable private Slack channels
* Users can see and chat in the private Slack chanenls they belong to
* Fix feed not scrolling when new messages arrive from other users

= 1.3.4 =
* Add wpslacksync_debug get parameter support for detailed request logging in javascript console

= 1.3.3 =
* Fix hidden file upload button still being clickable in upper part of text input area

= 1.3.2 =
* Add no_file_upload shortcode parameter that hides file upload button
* Fix images not showing after page refresh in view-only mode
* Fix accepting authorization for wrong Slack team domain</li>

= 1.3.1 =
* No longer show inaccessible images in view-only mode

= 1.3.0 =
* Add file upload support
* Properly display uploaded files, snippets, posts, and their comments
* Improve multi-line chat layout
* Javascript errors during message processing now log to console instead of breaking chat

= 1.2.4 =
* Use Slack object tokens (instead of deprecated oauth scope: client)
* Fix mixed content javascript error when using https

= 1.2.3 =
* Fix img property undefined javascript error when user not known

= 1.2.2 =
* Allow comma-separation for allowed_channels shortcode parameter
* Improve security by moving slack oauth authorization api call to server
* Fix unknown user name breaking the chat through a javascript error
* Fix scrollHeight javascript error log spam when on invite screen in read-only mode

= 1.2.1 =
*Fix js/css caching issues by adding correct plugin version to resource URLs

= 1.2.0 =
* Re-activate optional view-only mode

= 1.1.5 =
* Fixed plugin deactivating on auto-update (used random plugin name)

= 1.1.4 =
* Fixed broken Netherlands translation
* Pre-load javascript templates
* Removed option for incoming webhook that is no longer needed

= 1.1.3 =
* Fixed token genrator broken link
* Use different plugin updates provider

= 1.1.2 =
* Fixed app not working in Safari private mode

= 1.1.1 =
* Bugfixes and improve slack api call error output

= 1.1.0 =
* Added allowed_channels shortcode parameter to limit the available slack channels
* Added default_channel shortcode parameter to set the initially selected channel
* Fixed users having to re-authorize on each loading of page
* Clean up browser URL after authorization

= 1.0.2 =
* Bugfixes and change of author

= 1.0.1 =
* Added styling options for invite form
* Get rid of the joined notification
* Few bugfixes

= 1.0 =
* Added responsive output
* Added more color styling options for frontend view
* Added timestamps on messages when hover on time
* Added user profile picture and name in sidebar
* Added users profile pictures with chat message
* Added option to hide the login function on frontend
* Added option to hide the invite function on frontend
* Added line breaks so paragraphs show for larger messages
* Fixed issue with usernames not shown properly in output
* Fixed auto-fill bug on backend
* Fixed jquery tabs on backend
* More little bug fixes and improvements

= 0.0.2 =
* Added automatic update function
* Added styling function for frontend view
* Added Dutch translation

= 0.0.1 =
* Initial Release