<?php
/*
  Plugin Name: WPSlackSync
  Plugin URI: https://github.com/agileknight/wpslacksync
  Description: WordPress Slack Integration.
  Version: 1.11.0
  Author: Philipp Meier
  License: GPLv2 or later
  Text Domain: _wpslacksync
  Domain Path: ./localization/
 */
/*
This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.

Copyright 2015 - 2021 Philipp Meier
*/
require 'includes/class-wpslacksync.php';
require 'includes/class-wpslacksync-options.php';
require 'includes/class-wpslacksync-shortcode.php';
require 'includes/class-wpslacksync-gate.php';
require 'includes/class-wpslacksync-import.php';
require 'assets/libraries/curlcurl/curlCurl.php';

$plug_version = '1.11.0';

// alpha feature is disabled by default
//register_activation_hook(__FILE__, array(new WPSlackSync_Import(),'install'));

$text_domain = '_wpslacksync';

new WPSlackSync(__FILE__, $plug_version);
