<?php

require './hmac_api_lib.php';

// the test library for hmac_api_lib.php
//
// this implements the same unit test suite as the Erlang library

class erlang_hmac_api_lib_tests extends erlang_hmac_api_lib {

	public function unit_test() {
		echo $this->hash_test1();
		echo $this->hash_test2();
		echo $this->hash_test3();
		echo $this->signature_test1();
		echo $this->signature_test2();
		echo $this->signature_test3();
		echo $this->signature_test4();
		echo $this->signature_test5();
		echo $this->signature_test6();
		echo $this->signature_test7();
		echo $this->signature_test8();
		echo $this->signature_test9();
        echo $this->signature_test10();
        echo $this->signature_test11();
		echo $this->amazon_test1();
	}

	private function hash_test1() {
	    
	    $sig = "DELETE\n\n\n\nx-amz-date:Tue, 27 Mar 2007 21:20:26 +0000\n/johnsmith/photos/puppy.jpg";
    	$key = ERLANG_PRIVATE_KEY;

    	$hash = $this->sign2($key, $sig);
    	$expected = "k3nL7gH3+PadhTEVn5Ip83xlYzk=";

		$this->assert_equal("hash_test1", $expected, $hash);
	}

	private function hash_test2() {

		$sig = "GET\n\n\nTue, 27 Mar 2007 19:44:46 +0000\n/johnsmith/?acl";
    	$key = ERLANG_PRIVATE_KEY;

    	$hash = $this->sign2($key, $sig);
    	$expected = "thdUi9VAkzhkniLj96JIrOPGi0g=";

		$this->assert_equal("hash_test2", $expected, $hash);
	}

	private function hash_test3() {

	    $sig = "GET\n\n\nWed, 28 Mar 2007 01:49:49 +0000\n/dictionary/"
        . "fran%C3%A7ais/pr%c3%a9f%c3%a8re";
    	$key = ERLANG_PRIVATE_KEY;

    	$hash = $this->sign2($key, $sig);
    	$expected = "dxhSBHoI6eVSPcXJqEghlUzZMnY=";

		$this->assert_equal("hash_test3", $expected, $hash);
	}

	private function signature_test1() {
		$URL = "http://example.com:90/tongs/ya/bas";
    	$Method = "post";
    	$ContentMD5 = "";
    	$ContentType = "";
    	$Date = "Sun, 10 Jul 2011 05:07:19 UTC";
    	$Headers = array();
    	$Signature = array('method'=>$Method,
    					   'contentmd5'=>$ContentMD5,
    					   'contenttype'=>$ContentType,
    					   'date'=>$Date,
    					   'headers'=>$Headers,
    					   'resource'=>$URL);
    	$Sig = $this->make_signature_string($Signature);
    	$Expected = "POST\n\n\nSun, 10 Jul 2011 05:07:19 UTC\n\n/tongs/ya/bas";

		$this->assert_equal("signature_test1", $Expected, $Sig);
	}

	private function signature_test2() {
		$URL = "http://example.com:90/tongs/ya/bas";
    	$Method = "get";
    	$ContentMD5 = "";
    	$ContentType = "";
    	$Date = "Sun, 10 Jul 2011 05:07:19 UTC";
    	$Headers = array('x-amz-acl'=>"public-read");
    	$Signature = array('method'=>$Method,
    					   'contentmd5'=>$ContentMD5,
    					   'contenttype'=>$ContentType,
    					   'date'=>$Date,
    					   'headers'=>$Headers,
    					   'resource'=>$URL);
    	$Sig = $this->make_signature_string($Signature);
    	$Expected = "GET\n\n\nSun, 10 Jul 2011 05:07:19 UTC\nx-amz-acl:public-read\n/tongs/ya/bas";

		$this->assert_equal("signature_test2", $Expected, $Sig);
	}

	private function signature_test3() {
		$URL = "http://example.com:90/tongs/ya/bas";
    	$Method = "get";
    	$ContentMD5 = "";
    	$ContentType = "";
    	$Date = "Sun, 10 Jul 2011 05:07:19 UTC";
    	$Headers = array("x-amz-acl"=>"public-write;public-read",
    			"yantze"=>"blast-off",
    			"x-amz-doobie"=>"bongwater");
    	$Signature = array('method'=>$Method,
    					   'contentmd5'=>$ContentMD5,
    					   'contenttype'=>$ContentType,
    					   'date'=>$Date,
    					   'headers'=>$Headers,
    					   'resource'=>$URL);
    	$Sig = $this->make_signature_string($Signature);
    	$Expected = "GET\n\n\nSun, 10 Jul 2011 05:07:19 UTC\nx-amz-acl:public-read;public-write\nx-amz-doobie:bongwater\n/tongs/ya/bas";

		$this->assert_equal("signature_test3", $Expected, $Sig);
	}

	private function signature_test4() {
		$URL = "http://example.com:90/tongs/ya/bas";
    	$Method = "get";
    	$ContentMD5 = "";
    	$ContentType = "";
    	$Date = "Sun, 10 Jul 2011 05:07:19 UTC";
    	$Headers = array("x-amz-acl"=>"public-write;public-read",
    			"yantze"=>"blast-off",
    			"x-amz-doobie  oobie \t boobie "=>"bongwater");
    	$Signature = array('method'=>$Method,
    					   'contentmd5'=>$ContentMD5,
    					   'contenttype'=>$ContentType,
    					   'date'=>$Date,
    					   'headers'=>$Headers,
    					   'resource'=>$URL);
    	$Sig = $this->make_signature_string($Signature);
    	$Expected = "GET\n\n\nSun, 10 Jul 2011 05:07:19 UTC\nx-amz-acl:public-read;public-write\nx-amz-doobie oobie boobie:bongwater\n/tongs/ya/bas";

		$this->assert_equal("signature_test4", $Expected, $Sig);
	}

	private function signature_test5() {
		$URL = "http://example.com:90/tongs/ya/bas";
    	$Method = "get";
    	$ContentMD5 = "";
    	$ContentType = "";
    	$Date = "Sun, 10 Jul 2011 05:07:19 UTC";
    	$Headers = array("x-amz-acl"=>"public-write; public-Read  ", // added more spaces to test!
    			"yantze"=>"Blast-off",
    			"x-amz-doobie  Oobie \t boobie "=>" bongwater");
    	$Signature = array('method'=>$Method,
    					   'contentmd5'=>$ContentMD5,
    					   'contenttype'=>$ContentType,
    					   'date'=>$Date,
    					   'headers'=>$Headers,
    					   'resource'=>$URL);
    	$Sig = $this->make_signature_string($Signature);
    	$Expected = "GET\n\n\nSun, 10 Jul 2011 05:07:19 UTC\nx-amz-acl:public-Read;public-write\nx-amz-doobie oobie boobie:bongwater\n/tongs/ya/bas";

		$this->assert_equal("signature_test5", $Expected, $Sig);
	}

	private function signature_test6() {
		$URL = "http://example.com:90/tongs/ya/bas/?andy&zbish=bash&bosh=burp";
    	$Method = "get";
    	$ContentMD5 = "";
    	$ContentType = "";
    	$Date = "Sun, 10 Jul 2011 05:07:19 UTC";
    	$Headers = array();
    	$Signature = array('method'=>$Method,
    					   'contentmd5'=>$ContentMD5,
    					   'contenttype'=>$ContentType,
    					   'date'=>$Date,
    					   'headers'=>$Headers,
    					   'resource'=>$URL);
    	$Sig = $this->make_signature_string($Signature);
    	$Expected = "GET\n\n\nSun, 10 Jul 2011 05:07:19 UTC\n\n/tongs/ya/bas/?andy&zbish=bash&bosh=burp";

		$this->assert_equal("signature_test6", $Expected, $Sig);
	}

	private function signature_test7() {
		$URL = "http://exaMPLe.Com:90/tONgs/ya/bas/?ANdy&ZBish=Bash&bOsh=burp";
    	$Method = "get";
    	$ContentMD5 = "";
    	$ContentType = "";
    	$Date = "Sun, 10 Jul 2011 05:07:19 UTC";
    	$Headers = array();
    	$Signature = array('method'=>$Method,
    					   'contentmd5'=>$ContentMD5,
    					   'contenttype'=>$ContentType,
    					   'date'=>$Date,
    					   'headers'=>$Headers,
    					   'resource'=>$URL);
    	$Sig = $this->make_signature_string($Signature);
    	$Expected = "GET\n\n\nSun, 10 Jul 2011 05:07:19 UTC\n\n/tongs/ya/bas/?andy&zbish=bash&bosh=burp";

		$this->assert_equal("signature_test7", $Expected, $Sig);
	}

	private function signature_test8() {
		$URL = "http://exaMPLe.Com:90/tONgs/ya/bas/?ANdy&ZBish=Bash&bOsh=burp";
    	$Method = "get";
    	$ContentMD5 = "";
    	$ContentType = "";
    	$Date = "";
    	$Headers = array('x-aMz-daTe'=>"Tue, 27 Mar 2007 21:20:26 +0000");
    	$Signature = array('method'=>$Method,
    					   'contentmd5'=>$ContentMD5,
    					   'contenttype'=>$ContentType,
    					   'date'=>$Date,
    					   'headers'=>$Headers,
    					   'resource'=>$URL);
    	$Sig = $this->make_signature_string($Signature);
    	$Expected = "GET\n\n\n\nx-amz-date:Tue, 27 Mar 2007 21:20:26 +0000\n/tongs/ya/bas/?andy&zbish=bash&bosh=burp";

		$this->assert_equal("signature_test8", $Expected, $Sig);
	}

	private function signature_test9() {
		$URL = "http://exaMPLe.Com:90/tONgs/ya/bas/?ANdy&ZBish=Bash&bOsh=burp";
    	$Method = "get";
    	$ContentMD5 = "";
    	$ContentType = "";
    	$Date = "Sun, 10 Jul 2011 05:07:19 UTC";
    	$Headers = array('x-amz-date'=>"Tue, 27 Mar 2007 21:20:26 +0000");
    	$Signature = array('method'=>$Method,
    					   'contentmd5'=>$ContentMD5,
    					   'contenttype'=>$ContentType,
    					   'date'=>$Date,
    					   'headers'=>$Headers,
    					   'resource'=>$URL);
    	$Sig = $this->make_signature_string($Signature);
    	$Expected = "GET\n\n\n\nx-amz-date:Tue, 27 Mar 2007 21:20:26 +0000\n/tongs/ya/bas/?andy&zbish=bash&bosh=burp";

		$this->assert_equal("signature_test9", $Expected, $Sig);

	}

    private function signature_test10() {
        $URL = "http://exaMPLe.Com:90/tONgs/ya/bas/?ANdy&ZBish=Bash&bOsh=burp";
        $Method = "get";
        $ContentMD5 = "";
        $ContentType = "application/x-www-form-urlencoded; charset=UTF-8";
        $Date = "Sun, 10 Jul 2011 05:07:19 UTC";
        $Headers = array('x-amz-date'=>"Tue, 27 Mar 2007 21:20:26 +0000");
        $Signature = array('method'=>$Method,
                           'contentmd5'=>$ContentMD5,
                           'contenttype'=>$ContentType,
                           'date'=>$Date,
                           'headers'=>$Headers,
                           'resource'=>$URL);
        $Sig = $this->make_signature_string($Signature);
        $Expected = "GET\n\napplication/x-www-form-urlencoded; charset=UTF-8\n\nx-amz-date:Tue, 27 Mar 2007 21:20:26 +0000\n/tongs/ya/bas/?andy&zbish=bash&bosh=burp";

        $this->assert_equal("signature_test10", $Expected, $Sig);

    }

    private function signature_test11() {
        $URL = "http://exaMPLe.Com:90/tONgs/ya/bas/?ANdy&ZBish=Bash&bOsh=burp";
        $Method = "get";
        $ContentMD5 = "";
        $ContentType = "application/x-www-form-urlencoded; charset=UTF-8";
        $Date = "";
        $Headers = array();
        $Signature = array('method'=>$Method,
                           'contentmd5'=>$ContentMD5,
                           'contenttype'=>$ContentType,
                           'date'=>$Date,
                           'headers'=>$Headers,
                           'resource'=>$URL);
        $Sig = $this->make_signature_string($Signature);
        $Expected = "GET\n\napplication/x-www-form-urlencoded; charset=UTF-8\n\n\n/tongs/ya/bas/?andy&zbish=bash&bosh=burp";

        $this->assert_equal("signature_test11", $Expected, $Sig);


    }

	private function amazon_test1() {

	$URL = "http://exAMPLE.Com:90/johnsmith/photos/puppy.jpg";
    $Method = 'delete';
    $ContentMD5 = "";
    $ContentType = "";
    $Date = "";
   	$Headers = array('x-amz-date'=>"Tue, 27 Mar 2007 21:20:26 +0000");
   	$Signature = array('method'=>$Method,
    					   'contentmd5'=>$ContentMD5,
    					   'contenttype'=>$ContentType,
    					   'date'=>$Date,
    					   'headers'=>$Headers,
    					   'resource'=>$URL);
    $Sig = $this->sign_data(ERLANG_PRIVATE_KEY, $Signature);
    $Expected = "k3nL7gH3+PadhTEVn5Ip83xlYzk=";
	$this->assert_equal("amazon_test1", $Expected, $Sig);
	}

	// Internal test functions
	private function assert_equal ($Test, $A, $B) {
		if ($A == $B) {
			echo "Test " . $Test . " passes\n";
		} else {
			echo "Test " . $Test . " fails\n-Expected:\n   ". $A . "\n-Got\n   " . $B . "\n";
		}
	}

}

$test = new erlang_hmac_api_lib_tests();
$test->unit_test();