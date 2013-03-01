<?php
// Requires php5-mycrypt package installed

require_once 'classes/Bert.php';

class hn_single_signon
{
	// API Functions

	public function make_response($tag) {
		$current_user = wp_get_current_user();
		$user = array();
		$email = $current_user->get('user_email');
		if ($email == false) {
			$user['email'] = "";
		} else {
			$user['email'] = $email;
		};
		$user['groups'] = $this->get_groups($current_user->get('wp_capabilities'));
		$date = new DateTime();
		$timestamp = $date->GetTimestamp();
		$Bert = Bert::encode(Bert::t(Bert::a('signon'), 
							        $user['email'],
							        $user['groups'],
									$tag['URL'],
									$timestamp));
		return $this->encode($Bert);
	}

	public function validate_signon($tag) {

		// Set up the return array
		$validity = array('is_valid'=>TRUE,
			               'valid_time'=>TRUE,
			               'valid_url'=>TRUE);

		// first check the timestamp
		$date = new DateTime();
		$timestamp = $date->GetTimestamp() * 100000;
		$diff = abs($timestamp - $tag['Timestamp']);
		$drift = get_option('hn_time_drift') * 1000000;
		if ($diff > $drift) {
			$validity['is_valid']=FALSE;
			$validity['vaild_time']=FALSE;
		}

		// now check the url
	    $cleanurl  = filter_var($tag['URL'], FILTER_VALIDATE_URL );
    	$tokens = parse_url($cleanurl);
		$boundsite = get_option('hn_spreadsheet_site');
		$site = $tokens['scheme'] . "://" . $tokens['host'] . ":" . $tokens['port'];
		if ($site != $boundsite) {
			$validity['is_valid']=FALSE;
			$validity['valid_url']=FALSE;
		}

		return $validity;
	}

	public function open_hypertag($Hypertag, $IVector) {
		// Erlang and PHP versions of Base64 encoding are different!
		$Cyphertext = base64_decode(str_replace(" ", "+", $Hypertag));
		$IV = base64_decode(str_replace(" ", "+", $IVector));
		$Key = get_option ( 'hn_secret' );
		// aes_cfb_128 cipher
		$Plain = mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $Key, $Cyphertext, 'ncfb', $IV);
		$Trimmed = $this->unpad($Plain);
		$N = strpos($Trimmed, "http");
		$Return = substr($Trimmed, $N, strlen($Trimmed) - $N);
		$Timestamp = substr($Trimmed, 0, $N - 1);
		return array('URL'=>$Return,
				     'Timestamp'=>$Timestamp);
	}

	// Internal Functions

	private function encode($Binary) {
		$Padded = $this->extend($Binary);
		$Key = get_option ( 'hn_secret' );
		$Site = get_option('hn_spreadsheet_site');
		$Page = "/_sync/wordpress/logon/?hypertag=";
		$Param = "&ivector=";
		$Size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, 'ncfb');
	    $IV = mcrypt_create_iv($Size, MCRYPT_DEV_RANDOM);
		$Crypt = mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $Key, $Padded, 'ncfb', $IV);
		$EncIV = base64_encode($IV);
		$Encoded = base64_encode($Crypt);
		return $Site . $Page . $Encoded . $Param . $EncIV;
	}

	private function get_groups($Groups) {
		if ($Groups == "") {
			$gr = "";
		} else {
			$gr = array();
			$n = 0;
			foreach ($Groups as $key => $value) {
				$gr[$n] = $key;
			}
		}
		return $gr;
	}

	// functions to use the same padding schema for the 
	// aes cfb crypto as used on the Erlang side
	private function extend($Binary) {
		$Blocksize = 16;
		$Len = strlen($Binary);
		$First = floor($Len / 256);
		$Second = $Len % 256;
		$Pad = $Blocksize - (($Len + 2) % $Blocksize);
		$Binary .= str_repeat(chr(0), $Pad);
		return chr($First). chr($Second) . $Binary;
	}

	private function unpad($Binary) {
		$First = $Binary[0];
		$Second = $Binary[1];
		$Length = ord($First) * 256 + ord($Second);
		return substr($Binary, 2, $Length);
	}

	private function dump($Msg, $Binary) {
		echo "<p>" . $Msg . ":";
		$Len = strlen($Binary);
		for ($i = 0; $i < $Len; $i = $i + 1) {
			echo ord($Binary[$i]) . " ";
		}
		echo "</p>";
	}
 
	// Internal test functions
	private function assert_equal ($Test, $A, $B) {
		if ($A == $B) {
			echo "Test " . $Test . " passes\n";
		} else {
			echo "Test " . $Test . " fails\n";
		}
	}

	// unit test functions

}

$hn_signon = new hn_single_signon();

?>