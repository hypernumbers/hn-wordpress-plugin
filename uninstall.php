<? php

// If uninstall not called from WordPress exit
if( !defined( 'WP_UNINSTALL_PLUGIN' ) ) 
    exit ();
 
// Delete option from options table
delete_option( 'hypernumbers_plugin_options' ); 
delete_option( 'hypernumbers_secret' ); 
delete_option( 'hypernumbers_spreadsheet_site' ); 
delete_option( 'hypernumbers_time_drift' ); 

// remove any additional options and custom tables 
?>
