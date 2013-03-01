<?php

include 'hmac_api_lib.php';

if( !class_exists( 'WP_Http' ) ) {
	include_once( ABSPATH . WPINC. '/class-http.php' );
}

define('PUBLIC_KEY',  "9db1641e4679c522f25971a073df37eb");
define('PRIVATE_KEY', "3139609bfcb868256481f9443ef56238");

class vixo_crypto {

	// this is an example library of using the Vixo authenticated
	// and signed API to get and set spreadsheet data

	public function test() {

		// prepare the request
		$url = "http://hypernumbers.dev:9000/some/page/";
		$data = array('bish'=>'erk', 'bosh'=>'berk');
		$headers = array('yantze'=>"boogaloo",
					  'content-type'=>"application/x-www-form-urlencoded; charset=UTF-8");
		$params = array('method'=>'POST',
					    'body'=>$data,
						'headers'=>$headers);

		$hmac_sha = new erlang_hmac_api_lib();
		$signedparams = $hmac_sha->sign(PUBLIC_KEY, PRIVATE_KEY, $url, $params);
		// the signed parameters have the content-type header in them...
		// BUT if you pass the HTTP request with the content-type in it 
		// will add another set of them, so you need to take them out
		$fixedparam = $this->fix_params($signedparams);
		$request = new WP_Http;
		$result = $request->request($url, $fixedparam);
		debug_logger($result);
	}

	private function fix_params($params) {
		$headers = $params['headers'];
		unset($headers['content-type']);
		$params['headers'] = $headers;
		return $params;
	}
}

$crypto = new vixo_crypto();
$crypto->test();

?>