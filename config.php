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

class Db {
	
	function con() {
		$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
		if ($mysqli->connect_errno) {
		    echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
			die;
		}
		return $mysqli;
	}
	
}


class Lopa {
	var $db = new Db();
	var $sess = false;
	
	// a procrastinator method to start the session if it has not been started yet and to return it.
	function get_session() {
		if (!$sess) {
			session_start();
		}
		return $_SESSION;
	}
  	 // Check the HTTP_REFERER is valid when method is POST
    function check_referer($method = $_SERVER['REQUEST_METHOD'], $ref = $_SERVER['HTTP_REFERER']) {
	   	 if (('POST' === $rmethod) && isset($ref) && 
	   	    (!strncmp($ref, $needle, strlen($needle)))) {
	   		 $u = url_parse($ref);
	   		 if (($u === FALSE) || // parse_url() can return false on mangled input 
	   		    (stristr($u['host'], DOMAIN) === FALSE)) {
	   		   header('500 Internal Server Error', true, 500);
	   		   echo "<h1>500 Internal Server Error</h1>";
	   		   die;
	   		 }
	   	 }
		 return true;
    }

	// Set the next CSRF token to use in forms
	function set_csrf_token($session = get_session()) {
		$session['NEXTCSRF_TOKEN'] = openssl_random_pseudo_bytes(16);
		if (!isset($session['CSRF_TOKEN'])) {
			$session['CSRF_TOKEN'] = $session['NEXTCSRF_TOKEN'];
		}
	}
	
	 // If the user agent is too short, contains curl, or wget, then just throw an error.
	 function checkua($ua = $_SERVER['HTTP_USER_AGENT']) {
		if (isset($ua)) {
			if ((strlen($ua) < 4) ||
			   (stristr($ua, 'curl') !== FALSE) ||
			   (stristr($ua, 'wget') !== FALSE)) {
				   header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
				   echo "<h1>500 Internal Server Error</h1>";
				   die;
		 	}
		}
	}
}

