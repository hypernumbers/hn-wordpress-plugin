<?php

add_action('init', 'hn_create_custom_posts_type');
function hn_create_custom_posts_type() {
	register_post_type('random app',
	array(
		'labels' => array(
			'name' => 'Lez Randomz',
			'singular_name' => 'El Rando',
			'add_new' => 'spin',
			'add_new_item' => 'spim, ya bas!',
			'edit' => 'edito',
			'edit_item' => 'edito bandito',
			'new_item' => 'el Neuvo Randomono',
			'view' => 'PePe Le View',
			'view_item' => 'Pepe Le View-Hoo',
			'search_items' => 'Searchundo',
			'not_found' => 'No habbo',
			'not_found_in_trash' => 'no bluddy habbo',
			'parent' => 'Daddio'),
		'public' => true,
		'menu_position' => 20,
		'supports' => array('title', 'editor', 'comments', 'thumbnail',
		'custom-fields'),
		'taxonomies' => array(''),
		'menu_icon' => plugins_url('eek-16x16.png', __FILE__),
		'has_archive' => true)
	);
}

?>