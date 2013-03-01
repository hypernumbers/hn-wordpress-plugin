<?php

// add the shortcode handler to available shortcodes
add_shortcode ( 'hn', array ( 'hn_shortcode', 'shortcode') );

// this is the callback class that will be used on rendering the page
class hn_shortcode {
      function shortcode( $atts, $content = null ) {
      	       extract ( shortcode_atts ( array ( 'url'    => '', 
      	       									  'cells'  => '',
      	       									  'width'  => '', 
      	       									  'height' => '' ), 
      	       							  $atts ) );
	       if (empty($url)) return '<!--No hn Url -->';

	       $hn_h = "width:600px;";
	       $hn_w = "height:800px;";
	       $hn_resizeFlag = "";

	       if ( !empty ( $height ) ) {
	         $hn_h = "height:$height;";
                 $hn_resizeFlag = "hn_dont_resize";
	       }

	       if ( !empty ( $hn_width ) ) {
	         $hn_w = "width:$width;";
			 $hn_resizeFlag = "hn_dont_resize";
	       }

	       // concatenate the strings
	       // $style = "border:0;display:none;" . $h . $w;
	       $hn_style = "border:0;" . $hn_h . $hn_w;

	       // add the custom javascript and css
	       hn_load_css_and_javascript();

	       // now return the html
	       $hn_page = utf8_uri_encode(get_permalink());
	       $hn_name = uniqid();
	       $hn_iframe = "<iframe id='$hn_name' "
	       . "class='hn_wordpress $hn_resizeFlag' "
	       . "style='$hn_style' "
	       . "src='$url$cells#wordpress!$hn_page!$hn_name'>"
	       . "</iframe>";
	       return $hn_iframe;
	       }
}

// we need to load customer js and css - this function will be called to do it
// when the shortcode is rendered
function hn_load_css_and_javascript() {

 	 # CSS first
	 wp_register_style('hn.wp.css', FP_PLUGIN_URL . 'css/hn.wp.css');
	 wp_enqueue_style('hn.wp.css');

	 # Now JS
	 wp_register_script('hn.wp.js', FP_PLUGIN_URL . 'js/hn.wp.js', false, "", true);
	 wp_enqueue_script('hn.wp.js');
	 wp_register_script('jquery.ba-postmessage.js', FP_PLUGIN_URL . 'js/jquery.ba-postmessage.js', false, "", true);
	 wp_enqueue_script('jquery.ba-postmessage.js');
}

?>