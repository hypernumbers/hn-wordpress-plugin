<?php
/**
 * @package HyperNumbers
 * @version 1.0
 */
/*
Plugin Name: HyperNumbers
Plugin URI: http://wordpress.org/extend/plugins/hypernumbers/
Description: Turbocharges WordPress by putting a spreadsheet behind every page
Author: Gordon Guthrie
Version: 1.0
Author URI: http://wordpress.vixo.com
License: GPL2
*/

/*  Copyright 2013 Hypernumbers Ltd (trading as vixo.com) gordon@vixo.com

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

/*
  include in the code that provides the shortcode
  This means users can insert [hn cells="a10:b15"]
  In their text and it will insert a vixo control
*/

//include 'includes/hypernumbers-activate.php';
include 'includes/debug-logger.php';
include 'includes/hypernumbers-spreadsheet-buttons.php';
include 'includes/hypernumbers-shortcode.php';
include 'includes/hypernumbers-options.php';
include 'includes/hypernumbers-util.php';
include 'includes/hypernumbers-singlesignon.php';
include 'includes/classes/hypernumbers-api.php';
include 'includes/classes/hypernumbers-check-options.php';

// define the plugin URL
define ( 'HN_PLUGIN_URL', plugin_dir_url ( __FILE__ ) );

// setup localisation
load_plugin_textdomain( 'hypernumbers-plugin', 
                        false, 
                        'hypernumbers-plugin/languages' );

// set up the ajax calls for opening the spreadsheet
add_action('wp_ajax_hn_get_sample_permalink', 
           'hn_get_sample_permalink_fn');

function hn_get_sample_permalink_fn() {
  $id = $_REQUEST['id'];
  echo json_encode(hn_get_path($id));
  die ();
};

// set up the ajax calls for single signon
add_action('wp_ajax_hn_single_sign_on', 
           'hn_single_sign_on_fn');

add_action('wp_ajax_nopriv_hn_single_sign_on', 
           'hn_single_sign_on_fn');  

function hn_single_sign_on_fn () {

    $hypertag = $_REQUEST['hypertag'];
    $ivector  = $_REQUEST['ivector'];

    $hn_signon = new hn_hn_single_signon();
    $tag = $hn_signon->open_hypertag($hypertag, $ivector);
    $valid = $hn_signon->validate_signon($tag);
    if ($valid['is_valid'] == TRUE) {
      $path = $hn_signon->make_response($tag);
      header('Location: ' . $path);
    } else {
      echo "<p>Invalid attempt at single signon</p>";
    }
    die ();

}
?>