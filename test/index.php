<?php
require_once(dirname(__FILE__) . '/simpletest/autorun.php');
require_once(dirname(__FILE__) . '/../config.php');

class TestOfLopa extends UnitTestCase {
    function testLopaCheckReferer() {
		$lopa = new Lopa();
		$this->assertTrue($lopa->check_referer('POST', '')); 
    }
    function testLopaSetCSRF() {
		$lopa = new Lopa();
		$s = array();
		$lopa->set_csrf_token($s);
		$this->assertTrue(strlen($s['CSRF_TOKEN']) > 3, $s['CSRF_TOKEN']);		
    }
		
    function testLopatestua() {
		$lopa = new Lopa();
		$res = $lopa->checkua('Mozilla/5.0 (Macintosh; Intel Mac OS X 10.7; rv:20.0) Gecko/20100101 Firefox/20.0');
		$this->assertTrue($res, 'testua returned false on a real user agent.');		
    }

    function testLopanegativeCurltestua() {
		$lopa = new Lopa();
		$res = $lopa->checkua('curl');
		$this->assertFalse($res, 'testua returned true on a curl user agent.');		
    }

    function testLopanegativeWgettestua() {
		$lopa = new Lopa();
		$res = $lopa->checkua('wget');
		$this->assertFalse($res, 'testua returned true on a wget user agent.');		
    }
	
    function testLopanegativetestuaToShort() {
		$lopa = new Lopa();
		$res = $lopa->checkua('c');
		$this->assertFalse($res, 'testUA returned true on a fake user agent.');		
    }
	
    function testDbConnection() {
		$lopa = new Lopa();
		$res = $lopa->$db->con();
		$this->assertFalse($res === FALSE, "Unable to connect to the database.");
    }
	
	function testAddSig() {
		$lopa = new Lopa();
        $seconds_since_last_should_be_higher = $lopa->get_number_of_seconds_since_last_signature();
		$lopa->add_signature("Gardner Bickford", "gardner@invulnerable.org", true, 94110);
        // now seconds since last signature should be less than 
        $this->assertTrue($seconds_since_last_should_be_higher > $lopa->get_number_of_seconds_since_last_signature());
	}
	
}
?>