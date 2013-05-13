<?php
/**
 * config.php configuration parameters and a php class that contains testable 
 * code all in one place.
 *
 * @author     Gardner Bickford <gardner@invulnerable.org
 * @copyright  2013 Gardner Bickford
 * @license    https://www.gnu.org/licenses/gpl.txt  GPL License 3
 */

define('DOMAIN', 'cohal.org');
//	define('DB_HOST', 'mysql.parity.cc');
define('DB_HOST', '127.0.0.1');
define('DB_NAME', 'lopa');
define('DB_USER', 'paritycc');
define('DB_PASS', 'GvQ4C5A8TgcEyAYIMEncdP4a');
define('TIME_FILE', '/tmp/last_signature.tmp')

// start up a session
session_start();


class Lopa {
	var $mysqli = null;
	
	function con() {
		if ($mysqli === null) {}
			$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
			if ($mysqli->connect_errno) {
			    echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
				die;
			}
		}
		return $mysqli;
	}
	
  	 // Check the HTTP_REFERER is valid when method is POST
//    function check_referer($method = $_SERVER['REQUEST_METHOD'], $ref = $_SERVER['HTTP_REFERER']) {
    function check_referer($method = NULL, $ref = NULL) {
		$method = (NULL == $method) ? $_SERVER['REQUEST_METHOD'] : $method;
		$ref = (NULL == $ref) ? $_SERVER['HTTP_REFERER'] : $ref;
		
	   	 if (('POST' === $method) && isset($ref) && 
	   	    (!strncmp($ref, DOMAIN, strlen(DOMAIN)))) {
	   		 $u = url_parse($ref);
	   		 if (($u === FALSE) || // parse_url() can return false on mangled input 
	   		    (stristr($u['host'], DOMAIN) === FALSE)) {
	   		   return false;
	   		 }
	   	 }
		 return true;
    }

	// Set the next CSRF token to use in forms
	function set_csrf_token(&$session = NULL) {
		$session = (NULL == $session) ? $_SESSION : $session;
		
		$session['CSRF_TOKEN'] = md5(uniqid(rand(), true));
	}
	
	 // If the user agent is too short, contains curl, or wget, then just throw an error.
	 function checkua($ua = NULL) {
		$ua = (NULL === $ua) ? $_SERVER['HTTP_USER_AGENT'] : $ua;
		
		if (isset($ua)) {
			if ((strlen($ua) > 4) &&
			   (stristr($ua, 'curl') === FALSE) &&
			   (stristr($ua, 'wget') === FALSE)) {
				   return true;
		 	}
		} 
		return false;
	}
	
	// This runs when needed to update the static json file.
	function update_json() {
		// if the time file is there and it was touched more than 2 seconds ago
		if ((file_exists(TIME_FILE)) && (time() - filemtime(TIME_FILE) >= 2)) {
			// update the time file
			touch(TIME_FILE);

			// update the json file
			$db = $this->con();

			$res = $db->query("select count(email) from signatures group by state ");
			$row = $res->fetch_array();
			$total = $row[0];
			
			$res = $db->query("select state,count(state) from signatures order group by count(state) ");
			$row = $res->fetch_array();
			$total = $row[0];

			$res = $db->query("select count(email) from signatures");
			$row = $res->fetch_array();
			$total = $row[0];

			$res = $db->query("select count(email) from signatures");
			$row = $res->fetch_array();
			$total = $row[0];
			
			
		}
	}
	
	function add_signature($fullname, $email, $mailinglist, $zip) {
		$this->check_referer();
		$db = $this->con();
		
		// pulling the state out here ensures that it happens once per signature.
		$stmnt = $db->prepare("select state from zipcodes where zip = ?");
		$stmnt->bind_param('i', $zip);
		$stmnt->execute();
		$res = $stmnt->get_result();
		$row = $res->fetch_array();
		$state = $row;

		// duplicating state in the signature table prevents expensive joins on read.
		$stmnt = $db->prepare("insert into signatures (fullname, email, mailinglist, zip) values (?, ?, ?, ?)");
		$stmnt->bind_param('ssii', $fullename, $email, $mailinglist, $zip);
		$stmnt->execute();
		
		touch(TIME_FILE);
	}
}

