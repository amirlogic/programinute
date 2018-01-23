<?php

// PROGRAMINUTE USER LOGOUT


//require_once($_SERVER['DOCUMENT_ROOT'].'/cls/database.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/cls/userps.php');

//$dbz = new db(); // Connect to db

$userps = new UserPrescript();

	if( !$userps->usrid ){
							header( 'Location: gate.php' );		exit();
	}

	$userps->logout();
	
	$userps->deleteLoginCookie();

	header( 'Location: gate.php' );		exit();
	
?>