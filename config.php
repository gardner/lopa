<?php
	define('DOMAIN', 'cohal.org');
	define('DB_USER', 'nopalopa');
	define('DB_PASS', 'n0P4L0paaaaiF9Yl');
	
	session_start();

	// Set the next CSRF token to use in forms
	$_SESSION['NEXTCSRF_TOKEN'] = openssl_random_pseudo_bytes(16);
	if (!isset($_SESSION['CSRF_TOKEN'])) {
		$_SESSION['CSRF_TOKEN'] = $_SESSION['NEXTCSRF_TOKEN'];
	}

	 /**
	 *	Check the HTTP_REFERER is valid when method is POST
	 */
	 if (('POST' === $_SERVER['REQUEST_METHOD']) && isset($_SERVER['HTTP_REFERER']) && 
	    (!strncmp($_SERVER['HTTP_REFERER'], $needle, strlen($needle)))) {
		 $u = url_parse($_SERVER['HTTP_REFERER']);
		 if (($u === FALSE) || // parse_url() can return false on mangled input 
		    (stristr($u['host'], DOMAIN) === FALSE)) {
		   header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
		   echo "<h1>500 Internal Server Error</h1>";
		   die;
		 }
	 }

	 /**
	 *	If the user agent is too short, contains curl, or wget, then just throw an error.
	 */
	 if (isset($_SERVER['HTTP_USER_AGENT'])) {
		$ua = $_SERVER['HTTP_USER_AGENT'];
		if ((strlen($ua) < 4) ||
		   (stristr($ua, 'curl') !== FALSE) ||
		   (stristr($ua, 'wget') !== FALSE)) {
			   header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
			   echo "<h1>500 Internal Server Error</h1>";
			   die;
	 	}
	}
	
