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

// start up a session
session_start();


class LopaDB {
	function con() {
		$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
		if ($mysqli->connect_errno) {
		    echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
			die;
		}
		return $mysqli;
	}
	
	function 
}

class Lopa {
	var $db;
	
    function __construct() {
        $this->db = new LopaDB();
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
}

