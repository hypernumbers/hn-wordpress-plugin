<?php

// register some settings
add_action ( 'admin_init', 'hn_register_settings_fn');

// add the admin menus
add_action ( 'admin_menu', 'hn_add_admin_page_fn' );

function hn_register_settings_fn () {
	
	// register the settings for the plugin
	register_setting ( 'hn_settings',
					   'hypernumbers_spreadsheet_site',
					   'hn_validate_options_fn' );

	register_setting ( 'hn_settings',
					   'hypernumbers_secret',
					   'hn_validate_secret_fn' );

	register_setting ( 'hn_settings',
					   'hypernumbers_time_drift',
					   'hn_validate_time_drift_fn' );

	add_settings_section ( 'hn_main', 
					   	   'hn_settings',
					   	   'hn_section_text_fn',
						   'hypernumbers-plugin' );

	add_settings_field( 'hn_text_string',
						'Enter url of HyperNumbers site',
						'hn_settings_input_fn',
						'hypernumbers-plugin',
						'hn_main' );

	add_settings_field( 'hn_secret_string',
						'Enter shared secret (emailed to you)',
						'hn_secret_input_fn',
						'hypernumbers-plugin',
						'hn_main' );

	add_settings_field( 'hn_time_drift_integer',
						'Allowable time drift for single-signon (15 mins/9,000 seconds)',
						'hn_time_drift_fn',
						'hypernumbers-plugin',
						'hn_main' );

}

function hn_add_admin_page_fn ( ) {

	add_options_page( 'HyperNumbers',  
					  'HyperNumbers', 
					  'manage_options', 
					  'hypernumbers-plugin', 
					  'hn_options_page_fn' );

}

function hn_options_page_fn () {
	?>
	<div class="wrap">
		<?php screen_icon(); ?>
		<h2>HyperNumbers</h2>
		<form action='options.php' method='post'>
			<?php settings_fields ( 'hn_settings' );
			do_settings_fields ( 'hn_settings', 'hn_main' );
			do_settings_sections ( 'hypernumbers-plugin' );
			submit_button ( );
			?>
		</form>
	</div>
	<?php
}

function hn_section_text_fn () {
	$hn_txt1 = '<p>'
				  . __('Please give the URL of the HyperNumbers site 
					    that you are putting underneath this 
					    WordPress site, eg', 'hypernumbers-plugin')
	    		  . ' <code>http://example.com</code></p>'
	    		  .'<p>'
	    		  . __('You must save your changes')
	      		  . '</p>'
	      		  . '<p style="color:red;font-weight:bold;">'
	      		  . __ ('Fix so it only works with permalinks' )
	      		  . '</p>';

	if ( ! hn_has_prettylinks () ) {
    	$hn_txt2 = "<span style='color:red;font-weight:bold;'>"
    	              . __( "hypernumbers plugin "
    	              . "requires prettylinks" )
    	              . "</span>";
    }  else {
    	$hn_txt2 = '';
    }
    _e( $hn_txt1 . $hn_txt2 );
}

function hn_settings_input_fn () {
	$hn_options = get_option ( 'hn_spreadsheet_site' );
	echo "<input id='hn_text' 
		  class='regular-text ltr'
		  name='hn_spreadsheet_site'
		  type='text'
	  	  value='{$hn_options}'
	  	  />";
}	

function hn_secret_input_fn () {
	$hn_options = get_option ( 'hn_secret' );
	echo "<input id='hn_secret_text' 
		  class='regular-text ltr'
		  name='hn_secret'
		  type='text'
	  	  value='{$hn_options}'
	  	  />";
}	

function hn_time_drift_fn () {
	$hn_options = get_option ( 'hn_time_drift' );
	echo "<input id='hn_time_drift' 
		  class='regular-text ltr'
		  name='hn_time_drift'
		  type='text'
	  	  value='{$hn_options}'
	  	  />";
}	


function hn_validate_options_fn ( $input ) {
    // check this is a valid url
    $hn_cleanurl  = filter_var ( $input, FILTER_VALIDATE_URL );
    $hn_tokens = parse_url ( $hn_cleanurl );
    if ( $hn_tokens['scheme'] === "http" || 
         $hn_tokens['scheme'] === "https" ) {
    	$hn_site = $hn_tokens['scheme'] 
    					. '://' . $hn_tokens['host'];
    	if ( array_key_exists ('port', $hn_tokens ) ) {
   			$hn_site = $hn_site . ":" 
   							. strval ( $hn_tokens['port'] );
    	} 
	} else {
    	$hn_site = '';
    }
    return $hn_site ;
}

function hn_validate_secret_fn($input) {
	return $input;
}

function hn_validate_time_drift_fn($input) {
	return $input;
}


?>