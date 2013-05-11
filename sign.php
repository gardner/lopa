<?php
	include_once('config.php');

	if ('POST' === $_SERVER['REQUEST_METHOD']) {
		$lopa = new Lopa();		
		$lopa->check_referer();
		$mysqli = $lopa->con();

		// pulling the state out here ensures that it happens once per signature.
		$stmnt = $mysqli->prepare("select state from zipcodes where zip = ?");
		$stmnt->bind_param('i', $_POST['zip']);
		$stmnt->execute();
		$res = $stmnt->get_result();
		$row = $res->fetch_array();
		$state = $row;

		// duplicating state in the signature table prevents expensive joins on read.
		$stmnt = $mysqli->prepare("insert into signatures (fullname, email, mailinglist, zip) values (?, ?, ?, ?)");
		$stmnt->bind_param('ssii', $_POST['fullname'], $_POST['email'], $_POST['mailinglist'], $_POST['zip']);
		$stmnt->execute();		
	}
