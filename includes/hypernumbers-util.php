<?php

function hn_has_prettylinks () {
	$hn_has_pretty_links = get_option('permalink_structure');
	if ($hn_has_pretty_links === '') {
		return false;
	} else {
		return true;
	}
}

function hn_get_path ($id) {
	$get_sample_permalink = get_sample_permalink($id);
	$url = str_replace("%postname%", $get_sample_permalink[1], $get_sample_permalink[0]);
	$tokens = parse_url($url);
	return array('path'=>$tokens['path']);
}
?>