<?php

// add a button to open up the spreadsheet in editing mode
add_action ( 'media_buttons_context', 
	'hn_add_open_spreadsheet_button_fn' );

// add a button to the visual editor to select a bit of the spreadsheet
// to insert into the page

add_action ( 'init', 'hn_add_insert_spreadsheet_button_fn' );

// add a custom button to the rich text editor
add_filter( 'tiny_mce_version', 'hn_refresh_mce_fn');

function hn_add_open_spreadsheet_button_fn( $context ) {

	global $post;
	global $pagenow;

	// Check we are on the post-new.php page or bail
	// this stops the spreadsheet appearing on QuickPress malarky...
	if ($pagenow != 'post-new.php') {
		return $context;
	}

	// Now check permissions
	if ( (! current_user_can('edit_posts') ) 
		&& ( ! current_user_can('edit_pages') ) ) {
    	return $context;
    }

    // Finally check pretty links
	if (! hn_has_prettylinks () ) {
    	return $context .= "<span style='color:red;font-weight:bold;'>"
    	 . _("HyperNumbers plugin requires prettylinks")
    	 . "</span>";
    }

	// append the icon if the plugin is configured
	$hn_spreadsheet_site = get_option('hn_spreadsheet_site');

	if ($hn_spreadsheet_site !== '') {
		// icon
		$hn_icon = plugins_url ( 'images/spreadsheet.png', 
	 								 dirname ( __FILE__ ) );
		// title
		$hn_title = __( 'Insert Spreadsheet' );

		$context .= "<a id='hn_open_spreadsheet' title='{$hn_title}' "
				. "href='#' data-href='" . $hn_spreadsheet_site . "' "
	 			. "data-postid='" . $post->ID . "' class='button' "
	 			. "disabled='disabled'>"
		 	    . "<img src='{$hn_icon}' />" 
		 	    . __( 'Open Spreadsheet' ) 
		 	    . "</a>"
		 	    . "<span id='hn_hidden_permalink'>url is null</span>";
	} else {
		$context .= '';
	}	 
	return $context;
}

// add the TinyMCE button is mostly taken from this tutorial
// http://wp.tutsplus.com/tutorials/theme-development/wordpress-shortcodes-the-right-way/

function hn_add_insert_spreadsheet_button_fn () {

	// Check permissions

	if ( (! current_user_can('edit_posts') )
		&& ( ! current_user_can('edit_pages') )
		|| ! hn_has_prettylinks () ) {
    	return;
    }

	// load the javascript for handling the permalinks
	hn_load_javascript();

    if ( get_user_option ( 'rich_editing' ) == 'true' ) {
     	add_filter ( 'mce_external_plugins', 'hn_add_ss_tinymce_plugin_fn' );
     	add_filter ( 'mce_buttons', 'hn_register_ss_button_fn' );
   }

}

function hn_register_ss_button_fn($buttons) {

	array_push($buttons, "|", "insertss");
	return $buttons;

}

function hn_add_ss_tinymce_plugin_fn($plugin_array) {

	debug_logger("loading tiny mce ss javascript");
	// get the path of the javascript
	$hn_js = plugins_url ( 'js/hn.tinymce-editor.plugin.js', 
	 							 dirname ( __FILE__ ) );

	$plugin_array['insertss'] = get_bloginfo('template_url') . $hn_js;
	return $plugin_array;

}

function hn_refresh_mce_fn($ver) {

  $ver += 3;
  return $ver;

}

function hn_load_javascript() {

	if ( (! current_user_can('edit_posts') )
		&& ( ! current_user_can('edit_pages') )
		|| ! hn_has_prettylinks () ) {
    	return;
    }

	# Load the JS
	wp_register_script('hn.wp.permalink.js', HN_PLUGIN_URL . 'js/hn.wp.permalink.js', false, "", true);
	wp_enqueue_script('hn.wp.permalink.js');

}

?>