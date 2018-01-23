<?php

/*
# Programinute - Javascript Output
#
# Copyright 2016 Amir Hachaichi
#
#
#
#
#
#
#
#
*/

	define( 'INCLUDE_BASE', $_SERVER['DOCUMENT_ROOT'].'/pg/' );

if( !isset( $_GET['id'] ) ){
								header('Location: dashboard.php');	exit();
}


require_once( INCLUDE_BASE . 'cls/database.php' );

require_once( INCLUDE_BASE . 'cls/userps.php' );
	
require_once( INCLUDE_BASE . 'cls/constants.php' );

require_once( INCLUDE_BASE . 'cls/cdt.php' );

require_once( INCLUDE_BASE . 'cls/jswriter.php' );


require_once( INCLUDE_BASE . 'cls/codeswitch.php' );

require_once( INCLUDE_BASE . 'cls/tbli_input_text.php' ); // No init needed

require_once( INCLUDE_BASE . 'cls/tbli_output_blocks.php' ); // Init problem

require_once( INCLUDE_BASE . 'cls/tbli_output_text.php' ); // No init needed

require_once( INCLUDE_BASE . 'cls/tbli_output_call.php' ); // No init needed

require_once( INCLUDE_BASE . 'cls/tbli_prescript_main.php' );

require_once( INCLUDE_BASE . 'cls/proc_loader.php' );


require_once( INCLUDE_BASE . 'cls/ps_main.php' );


// Initializing classes

	$dbz = new db(); // Connect to Database
	
	$userps = new UserPrescript();
	
	if( $userps->usrid === false ){
										header('Location: gate.php');	exit();
	}
	
	$pshdrq = $dbz->prepSelectAll( 'prescript_header', array( 
																[ 'id', '=', $_GET['id'], 'i' ],
																
																[ 'user', '=', $userps->usrid, 's' ]
																		
															), false );
	if( $row = $dbz->fetch_array( $pshdrq ) ){
		
			$pstitle = $row['title'];	
			
			$psinstr = ( empty( $row['dsc'] ) ) ? 'None given' : $row['dsc'];		
			
			$psauthor = ( empty( $row['author'] ) ) ? 'Anonymous' : $row['author'] ;
	}
	else{
			header('Location: dashboard.php');	exit();
	}

	$codesw = new CodeSwitch( $_GET['id'] ); // Preload on init


	$tbi_out_blocks = new TBIOutputBlocks( $_GET['id'] );

	$tbi_out_call = new TBIOutputCall( $_GET['id'] );


// Load and build
	
	$tbi_out_txt->compileReady( $_GET['id'] );
	
	$loadproc = new ProcessingLoader( $_GET['id'],'javascript' ); // Load processings
	
	$loadcdt = new ConditionTable( $_GET['id'],false,false ); // Load condition tables

	$start = new PrescriptMain( $_GET['id'],'javascript' ); // Load PS to Js output
	
	$jswrt->writeFinalFile( $pstitle, $psinstr, $psauthor );
	


	// Prompt download
	header( 'Content-Description: File Transfert' );
    header( 'Content-Type: text/html' );
    header( 'Content-Disposition: attachment; filename="pm' . $_GET['id'] . '.html"' );
    header( 'Expires: 0' );
    header( 'Cache-Control: must-revalidate' );
    header( 'Pragma: public' );
    header( 'Content-Length: ' . strlen( $jswrt->finaljs ) );
	
	echo $jswrt->finaljs;
	
	//echo $dbz->test; // Debug

	
	exit();

?>
