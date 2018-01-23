<?php

	// Programinute - AJAX DISH

	// Copyright 2016 Amir Hachaichi
	
	define( 'INCLUDE_BASE', $_SERVER['DOCUMENT_ROOT'].'/pg/' );
	
	require_once(  INCLUDE_BASE . 'cls/constants.php' );

	require_once(  INCLUDE_BASE . 'cls/database.php' );

	require_once(  INCLUDE_BASE . 'cls/userps.php' );
	
	require_once(  INCLUDE_BASE . 'cls/tbli_prescript_main.php' );
	
	require_once(  INCLUDE_BASE . 'cls/switchorg.php' ); // Extends
	require_once(  INCLUDE_BASE . 'cls/highlevel_main.php' ); // Extends (no init)
	
	require_once( INCLUDE_BASE . 'cls/tbli_cdtable.php' );
	require_once( INCLUDE_BASE . 'cls/tbli_output_text.php' );
	require_once( INCLUDE_BASE . 'cls/tbli_output_blocks.php' );
	require_once( INCLUDE_BASE . 'cls/tbli_input_text.php' );
	require_once( INCLUDE_BASE . 'cls/tbli_output_call.php' );
	require_once( INCLUDE_BASE . 'cls/tbli_processing.php' );
	require_once( INCLUDE_BASE . 'cls/tbli_var.php' );
	
	require_once(  INCLUDE_BASE . 'cls/cdt.php' );
	
	require_once(  INCLUDE_BASE . 'cls/action_do.php' );
	
	require_once(  INCLUDE_BASE . 'cls/codedisplayer.php' );
	
	require_once(  INCLUDE_BASE . 'cls/proc_displayer.php' );

	require_once(  INCLUDE_BASE . 'cls/json.php' );
	
	require_once(  INCLUDE_BASE . 'cls/action_return.php' );
	
	require_once(  INCLUDE_BASE . 'cls/postgate.php' );

	
	$dbz = new db(); // Connect to Database
	
	$userps = new UserPrescript();
	
	$postgate = new POSTGate( $_POST['data'] ); // Reads POST data
	
	
	
?>