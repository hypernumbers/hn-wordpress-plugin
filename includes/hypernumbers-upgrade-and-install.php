<?php

// this module handles installing and upgrading the hypernumbers plugin

register_activation_hook(__FILE__,'hn_plugin_activation_fn');
add_action('plugins_loaded', 'hn_plugin_upgrade_fn');

function hn_activation_fn () {
	hn_upgrade_options();
	debug_logger("plugin is activated");
}


function hn_plugin_upgraded_fn() {
	hn_upgrade_options();
	debug_logger("plugin is upgraded")
}

function hn_upgrade_options() {

	$hn_options = new hn_check_options();
	$hn_options->getOptions();
}

?>