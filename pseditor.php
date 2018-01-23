<?php

// Prescript Viewer Version beta 1 - Copyright 2015-2016 Amir Hachaichi		PROTECTED

	define( 'INCLUDE_BASE', $_SERVER['DOCUMENT_ROOT'].'/pg/' );


	if( !isset( $_GET['id'] ) ){
									header('Location: dashboard.php');	exit();
	}

	// Files

	require_once( INCLUDE_BASE . 'cls/database.php' );
	
	require_once( INCLUDE_BASE . 'cls/userps.php' );
	
	require_once(  INCLUDE_BASE . 'cls/constants.php' );
	
	require_once( INCLUDE_BASE . 'cls/ps_main.php' );
	
	require_once( INCLUDE_BASE . 'cls/cdt.php' );

	require_once( INCLUDE_BASE . 'cls/codedisplayer.php' );
	
	require_once( INCLUDE_BASE . 'cls/proc_displayer.php' );

	require_once( INCLUDE_BASE . 'cls/codeswitch.php' );

	require_once( INCLUDE_BASE . 'cls/tbli_input_text.php' ); // No init needed

	require_once( INCLUDE_BASE . 'cls/tbli_output_blocks.php' ); // Init problem

	require_once( INCLUDE_BASE . 'cls/tbli_output_text.php' ); // No init needed

	require_once( INCLUDE_BASE . 'cls/tbli_output_call.php' ); // No init needed

	require_once( INCLUDE_BASE . 'cls/tbli_prescript_main.php' );
	
	require_once( INCLUDE_BASE . 'cls/proc_loader.php' );

	



// Initializing classes

	$dbz = new db(); // Connect to Database
	
	$userps = new UserPrescript();

	$codesw = new CodeSwitch( $_GET['id'] ); // Preload on init

	$code_display = new CodeDisplayer( $_GET['id'] );

	$tbi_out_blocks = new TBIOutputBlocks( $_GET['id'] );

	$tbi_out_call = new TBIOutputCall( $_GET['id'] );

	
	if( $userps->usrid === false ){
										header('Location: gate.php');	exit();
	}

	$pshdrq = $dbz->prepSelectAll( 'prescript_header', array( 
																[ 'id', '=', $_GET['id'], 'i' ],
																
																[ 'user', '=', $userps->usrid, 's' ]
																		
															), false );
	if( $row = $dbz->fetch_array( $pshdrq ) ){
		
			$pstitle = $row['title'];	$psinstr = $row['dsc'];		$psauthor = $row['author'];	
	}
	else{
			header('Location: dashboard.php');	exit();
	}

?>
<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">

<title>Prescript Editor</title>

<meta name="description" content="">

<link href="style.css" rel="stylesheet" type="text/css" media="all" />

<?php 

//require_once('js/scripts.php');
require_once('js/ajax.php');

?>

<script type="text/javascript">

	var inputBuffer = {};

	
	function attReader( id )
	{
		var dataHolder = document.getElementById( id );
		
		if( dataHolder.hasAttribute( 'data-stack' ) )
		{
			var stack = dataHolder.getAttribute( 'data-stack' );
			
			if( dataHolder.hasAttribute( 'data-init' ) )
			{
				inputBuffer[stack] = JSON.parse( decodeURIComponent( dataHolder.getAttribute( 'data-init' ) ) );
			}
			else
			{
				if( dataHolder.hasAttribute( 'data-key' ) )
				{
					var stackey = dataHolder.getAttribute( 'data-key' );
					
					if( dataHolder.hasAttribute( 'data-payload' ) )
					{
						var payload = dataHolder.getAttribute( 'data-payload' );
						
						inputBuffer[stack][stackey] = payload;
					}
					else
					{
						if( dataHolder.getAttribute( 'data-getfrom' ) == 'value' )
						{
							inputBuffer[stack][stackey] = dataHolder.value ;
						}
					}
				}
				else if( dataHolder.hasAttribute( 'data-multikey' ) )
				{
					var multikey = JSON.parse( decodeURIComponent( dataHolder.getAttribute( 'data-multikey' ) ) );
					var onekey;
					
					for( onekey in multikey )
					{
						inputBuffer[stack][multikey[onekey].ky] = multikey[onekey].val;
					}
				}
				else if( dataHolder.hasAttribute( 'data-monoinput' ) )
				{
					var fdata = JSON.parse( decodeURIComponent( dataHolder.getAttribute( 'data-monoinput' ) ) );
					
					fdata.miuserdata = dataHolder.value;
					
					inputBuffer[stack] = fdata;
				}
			}
			
			
			if( dataHolder.hasAttribute( 'data-flush' ) )
			{
				// Check if init has been done before
				
				if( inputBuffer[stack].sent == false )
				{
					doGet( encodeURIComponent( JSON.stringify( inputBuffer[stack] ) ) );
					
					if( dataHolder.getAttribute( 'data-flush' ) == 'value' )
					{
						dataHolder.value = 'Processing...';
					}
					
					inputBuffer[stack].sent = true;
				}
				
			}
			
			console.log( inputBuffer );
		}
		
		
		if( dataHolder.hasAttribute( 'data-rawsend' ) )
		{
			doGet( dataHolder.getAttribute( 'data-rawsend' ) );
		}
		
		
		if( dataHolder.hasAttribute( 'data-directpost' ) )
		{
			var dpdata = JSON.parse( decodeURIComponent( dataHolder.getAttribute( 'data-directpost' ) ) );
			
			for( i=0; i < dpdata.inputdata.length; i++ )
			{
				dpdata.senddata[ dpdata.inputdata[i][0] ] = document.getElementById( dpdata.inputdata[i][1] ).value;
			}
			
			//console.log(dpdata.inputdata);
			console.log(dpdata.senddata);
			doGet( encodeURIComponent( JSON.stringify( dpdata.senddata ) ) );
			
			dataHolder.value = 'Processing...';
			dataHolder.disabled = true;
		}
		
		
		if( dataHolder.hasAttribute( 'data-switch-hide' ) )
		{
			var hideid = dataHolder.getAttribute( 'data-switch-hide' );
			
			document.getElementById(hideid).style.display = 'none';
		}
		
		if( dataHolder.hasAttribute( 'data-switch-show' ) )
		{
			var showid = dataHolder.getAttribute( 'data-switch-show' );
			
			document.getElementById(showid).style.display = 'block';
		}
		
	}
	
	
	function stylEdit()
	{
		
	}
	
</script>

<style type="text/css">

body
{
	font-family:Verdana,Geneva,sans-serif;
	background-color:#E1E1E1;
}

#main
{
	width:100%;
	padding:80px 0;
	background-color:transparent;
	
}


.sec_wrp
{
	border-left:2px solid #777;
	padding:20px;
	margin-bottom:20px;
}

.pad10
{
	padding:10px;
}

footer
{
	border-top:1px solid #BBBBBB;
}

</style>


<style type="text/css">

.sec_header
{
	margin:60px 0 10px 0;
	background-color:#FFFFFF;	
	border-style:solid;
	border-width:2px 1px 1px 1px;
	border-color:#BFBFBF;
}

.sec_title
{
	display:inline-block;		vertical-align:middle;
	width:25%; 		padding-top:3px;	padding-left:3%;
}

.sec_title h1
{
	font-size:20px;
}

.secintro
{
	display:inline-block;	vertical-align:top;
	width:67%;	padding:20px 2%;	border-left:1px solid #CCCCCC;
	font-family:Verdana,Geneva,sans-serif;	font-size:14px;
}

.sec_out_wrp
{
	padding:10px 40px;
	margin:30px 0;
}

.master_stem_wrp
{
	margin-top:30px;
	padding:5px 20px;
	background-color:#FCFCFC;
	border:1px solid #CCCCCC;
}

#input_sec_left
{
	display:inline-block;
	width:45%;
	vertical-align:top;
	padding:0 2%;
	background-color:#FFFFFF;
	border:2px solid #DDDDDD;
	
}

#input_add_new
{
	font-family:Verdana,Geneva,sans-serif;
	font-size:12px;
	cursor:pointer;
}

#input_sec_right
{
	display:inline-block;
	width:48%;
	vertical-align:top;
	padding-left:2%;
}

#input_sec_main
{
	padding:20px 0;
	background-color:#FFFFFF;
	border:1px solid #CCCCCC;
}

.output_sec_wrp
{
	padding:20px 0;
	margin-bottom:40px;
}

#output_sec_main
{
	
}

.flow_sec_wrp
{
	padding:20px 0;
	margin-bottom:40px;
}

#flow_sec_main
{
	
}

.input_text_wrp
{
	
	padding:10px 20px;
	background-color:transparent;
}

.input_text_frame
{
	border:2px solid #E3F0DA;
	padding:5px 8px;
	margin-left:20px;
	margin-right:40px;
	display:inline-block;
	min-width:200px;
	font-family:Verdana,Geneva,sans-serif;
	font-size:16px;
	color:#909090;
	background-color:#FFFFFF;
	letter-spacing:1px;
}

.varbox
{
	padding:5px 8px;
	background-color:#F8FAF2;
	color:#333333;
	font-size:16px;
	letter-spacing:1px;
	border:1px solid #EEEEEE;
	font-family:'Courier New',monospace;
}

.inline_var
{
	padding:2px 5px 0 5px;
	font-family:'Courier New',monospace;
	color:#333333;
	letter-spacing:1px;
	font-size:16px;
	background-color:#F3F7F2;
	border:1px solid #EEEEEE;
}

.procvar
{
	padding:5px 7px 3px 7px;
	color:#333333;
	font-family:'Courier New',monospace;
	font-size:16px;
	letter-spacing:1px;
	background-color:#F9F5FA;
	border:1px solid #EEEEEE;
}

.inline_select
{
	display:inline-block;
	min-width:60px;
	padding:10px;
	margin-right:30px;
	cursor:pointer;
	font-family:Verdana,Geneva,sans-serif;
	font-size:14px;
}

.output_text_wrp
{
	margin-top:50px;
}

.output_text_left
{
	display:inline-block;
	width:19%;
	margin-right:2%;
	min-height:100px;
	vertical-align:top;
	border-style:solid;
	border-width:2px 1px 1px 1px;
	border-color:#BBBBBB #CCCCCC #CCCCCC #CCCCCC;
	background-color:#FCFCFC;
}

.output_text_right
{
	display:inline-block;
	width:77%;
	vertical-align:top;
}

.output_text_title
{
	font-family:Verdana,Geneva,sans-serif;
	letter-spacing:1px;
	font-weight:bold;
	padding:10px 10px;
}

.output_text_desc
{
	padding:10px 10px;
	border-bottom:1px solid #CCCCCC;
}

.output_text_blockstm
{
	font-family:Verdana,Geneva,sans-serif;
	text-transform:uppercase;
	font-size:12px;
	letter-spacing:1px;
	padding:10px 10px;
	cursor:pointer;
	text-align:right;
}

.output_text_block_wrp
{
	padding:0;
}

.output_text_block_left
{
	display:inline-block;
	width:80%;
	margin-right:2%;
	vertical-align:top;
}

.output_text_block_right
{
	display:inline-block;
	width:15%;
	padding:10px 1%;
	vertical-align:top;
	background-color:#FCFCFC;
	border-style:solid;
	border-width:2px 1px 1px 1px;
	border-color:#BBBBBB #CCCCCC #CCCCCC #CCCCCC;
}

.output_text_block_payload
{
	padding:20px 30px;
	background-color:#FFFFFF;
	border-style:solid solid solid solid;
	border-width:1px 1px 1px 1px;
	border-color:#CCCCCC;
}

.output_block_action
{
	display:inline-block;
	vertical-align:top;
	padding:3px;
	text-transform:uppercase;
	font-size:12px;
	color:#E8AE3A;
	font-family:Verdana,Geneva,sans-serif;
	cursor:pointer;
}

.output_text_actions_cont
{
	padding:10px 0;
}

.output_block_stem_wrp
{
	display:none;
	width:76%;
	font-family:Verdana,Geneva,sans-serif;
	padding:15px 2%;
	background-color:#FFFFDE;
	border-style:none solid solid solid;
	border-width:0px 1px 1px 1px;
	border-color:#CCCCCC;
}

.output_text_brick_stem
{
	display:none;
	background-color:#FFFFDE;
}

.output_text_var
{
	padding:2px 5px 0 5px;
	color:#333333;
	font-family:'Courier New',monospace;
	background-color:#F2F4FA;
	border:1px solid #EEEEEE;
	letter-spacing:2px;
	font-size:1.2em;
}

.flow_step_wrp
{
	margin-top:40px;
}

.flow_step_left
{
	display:inline-block;
	vertical-align:top;
	width:300px;
	min-height:100px;
	background-color:#FCFCFC;
	border-style:solid;
	border-width:2px 1px 1px 1px;
	border-color:#BBBBBB #CCCCCC #CCCCCC #CCCCCC;
}

.flow_step_title
{
	padding:10px;
	letter-spacing:1px;
}

.flow_step_actions
{
	border-top:1px solid #CCCCCC;
}

.flow_step_right
{
	display:inline-block;
	vertical-align:top;
	min-width:800px;
	padding:0 20px;
	
	border-left:4px solid #EFEFEF;
}

.flow_step_hdr_left
{
	display:inline-block;
	vertical-align:top;
	width:31%;
	padding:10px 2%;
	height:18px;
	font-family: Verdana, Geneva, sans-serif;
	text-transform:uppercase;
	letter-spacing:2px;
	font-size:12px;
	color:#E85656;
}

.flow_step_hdr_right
{
	display:inline-block;
	vertical-align:top;
	width:61%;
	padding:0 2%;
	height:38px;
}

.flow_step_hdr_right a
{
	text-decoration:none;
}

.flowstep_menu_cmd
{
	display:inline-block;
	vertical-align:top;
	padding:10px;
	margin-right:10px;
	cursor:pointer;
}

.flowstep_menu_txt
{
	display:inline-block;
	vertical-align:top;
	padding:10px;
	margin-right:10px;
}

.cdt_table_in_wrp
{
	min-width:245px;
	padding-left:auto;
	padding-right:auto;
	margin-bottom:20px;
	
}

.cdt_table_col_header
{
	padding:10px;
	text-align:center;
	border-bottom:1px solid #DDD;
}

.cdt_table_col_wrp
{
	display:inline-block;
	min-width:120px;
	border:1px solid #999999;
	vertical-align:top;
	min-height:150px;
	background-color:#FFFFFF;
}

.cdt_table_col_header
{
	padding:5px 10px;
	text-align:center;
	border-bottom:1px solid #DDD;
}

.cdt_table_col_body
{
	padding:20px;
}

.cdt_table_grp_wrp
{
	padding:10px 0 10px 10px;
	border-left:5px solid #AAA;
	margin-top:20px;
}

.cdt_table_coledit
{
	padding:20px 30px;
	border:1px solid #DDDDDD;
	margin:30px 0;
	background-color:#FFFFFF;
}

.cdtheader_coledit_hdr
{
	margin:0 3px 15px 3px;
	padding:10px;
	border-bottom:1px solid #999;
}

.cdt_table_actions_wrp
{
	padding:10px 0;
}

.cdtheader_colop_select
{
	min-width:120px;
	padding:5px 10px;
	margin-right:30px;
	cursor:pointer;
	font-family:Verdana,Geneva,sans-serif;
	font-size:14px;
}

.cdt_switch_wrp
{
	margin-bottom:50px;
}

.cdt_switch_hcol_wrp
{
	padding-left:20px;
	border-left:1px dashed #BBB;
	margin-bottom:10px;
}

.cdt_switch_hcol_inner
{
	border-left:5px solid #E67878;
	padding:0 0 0 30px;
	min-height:50px;
	margin:20px 0;
}

.cdt_switch_hcol_letter
{
	padding:10px;
	font-weight:strong;
	margin-bottom:20px;
}

.cdt_switch_hcol_letin
{
	padding:10px;
	color:#EB4B4B;
	background-color:#FFFFFF;
	border:1px solid #CCCCCC;
}

.uservalue
{
	font-family:'Courier New',monospace;
	font-size:1.2em;
}

.outcall_ovars_cont
{
	display:inline-block;
	min-width:300px;
	min-height:30px;
	padding:20px;
	background-color:#FFFFFF;
	border:1px solid #CCCCCC;
}

.outcall_ovar_wrp
{
	padding:5px;
}

.outcall_ovar_wrp input,select
{
	margin-right:10px;
}

#proc_sec_main
{
	min-height:100px;
}

@keyframes fpwanime {
    from {background-color:#FEFEFE;}
    to {background-color:#CCE9FF;}
}

.varproc_wrp:hover
{
	background-color:#CCE9FF;
	animation-name: fpwanime;
    animation-duration: 1s;
}

.flow_proc_invar_otr
{
	display:inline-block;
	vertical-align:top;
	width:40%;
	background-color:#FFFFFF;
	border-style:solid;
	border-width:1px;
	border-color:#DDDDDD;
	margin-right:2%;
}

.flow_proc_outvar_otr
{
	display:inline-block;
	vertical-align:top;
	width:57%;
	background-color:#FFFFFF;
	border-style:solid;
	border-width:1px;
	border-color:#DDDDDD;
}

.varproc_column
{
	display:inline-block;
	width:50%;
	vertical-align:top;
}

.flow_proc_in_hdr
{
	font-family:Verdana,Geneva,sans-serif;
	text-align:center;
	padding:5px 0;
	margin:5px 3%;
	color:#777777;
	letter-spacing:1px;
}

.flow_proc_out_hdr
{
	font-family:Verdana,Geneva,sans-serif;
	text-align:center;
	padding:5px 0;
	margin:5px 3%;
	color:#777777;
	letter-spacing:1px;
}

.flow_proc_ptxt
{
	margin-left:20px;
}

.flow_proc_invar_cont
{
	padding:25px 20px;
	border:1px solid #FAF9AF;
	border-top:1px solid #CCCCCC;
	border-bottom:1px solid #CCCCCC;
}

.flow_proc_outvar_cont
{
	padding:10px 5px;
	min-height:30px;
	border-top:1px solid #CCCCCC;
	border-bottom:1px solid #CCCCCC;
}

.varproc_stem_wrp
{
	margin-right:20px;
	padding:5px;
}

.varproc_stem_wrp input
{
	margin-right:10px;
}

.varproc_stem_showform
{
	padding:10px;
	text-transform:uppercase;
	font-size:12px;
	letter-spacing:1px;
	color:#1089B1;
	font-family:Verdana, Geneva, sans-serif;
	font-weight:300;
	cursor:pointer;
}

.flowproc_instem_input
{
	width:60px;
}

.proc_invar
{
	padding:3px 6px 2px 6px;
	margin-right:20px;
	color:#333333;
	font-family:'Courier New',monospace;
	font-size:17px;
	letter-spacing:1px;
	background-color:#F2F8FA;
	border:1px solid #EEEEEE;
}

.flow_proc_outvar
{
	margin-bottom:10px 0;
	padding:8px 0;
}

.flow_stem_click
{
	padding:10px 10px;
	cursor:pointer;
	font-family:Verdana, Geneva, sans-serif;
	letter-spacing:1px;
}

#wrp_procsec
{
	padding-top:20px;
}

.proc_wrp
{
	margin-top:50px;
	/*border-top:3px solid #BFBFBF;
	padding:20px 0;
	background-color:#E0E0E0;*/
}

.procsec_procnum
{
	display:inline-block;
	vertical-align:top;
	width:3%;
	padding:10px 1%;
	font-size:16px;
	font-weight:600;
	border-style:solid;
	border-width:1px 1px 1px 1px;
	border-color:#CCCCCC;
	background-color:#EEEEEE;
}

.procsec_mid_cont
{
	display:inline-block;
	vertical-align:top;
	width:45%;
	margin:0 5px;
	border-style:solid;
	border-width:4px 1px 1px 1px;
	border-color:#BFBFBF #CCCCCC #CCCCCC #CCCCCC;
	background-color:#FFFFFF;
	font-size:13px;
	color:#777777;
}

.procsec_invar_cont
{
	padding:10px 20px;
}

.proc_sec_opwrp
{
	padding:15px 10px;
	margin:0;
	color:#555555;
	background-color:#FFFFFF;
	
}

.mathop
{
	font-family:Verdana, Geneva, sans-serif;
	font-size:16px;
	padding:0 3px;
	margin:0;
}

.procsec_operline
{
	padding:4px 5px;
}

.proc_oper_stem_wrp
{
	padding:10px 20px;
	margin:0;
	color:#555555;
}

.proc_oper_stem_wrp:hover
{
	opacity:1.0;
}

.procsec_outvar_cont
{
	display:inline-block;
	vertical-align:top;
	width:46%;
	min-height:50px;
	padding:10px 1%;
	border-style:solid;
	border-width:1px 1px 1px 1px;
	border-color:#CCCCCC;
	background-color:#FFFFFF;
}

.procsec_outvar_wrp
{
	padding:10px 10px;
	
}

.resnum_var
{
	font-family:'Courier New',monospace;
	font-size:16px;
	letter-spacing:1px;
}

.procsec_outvar_leftcont
{
	display:inline-block;
	vertical-align:top;
	width:41%;
	font-family:'Courier New',monospace;
	font-size:16px;
	letter-spacing:1px;
}

.procsec_outvar_binput
{
	width:50px;
}

.procsec_outvar_rightcont
{
	display:inline-block;
	width:43%;
	padding:3px 3%;
}

.procsec_opinput_wrp
{
	display:none;
	padding:20px 20px 20px 60px;
	border-top:2px solid #EEEEEE;
	border:1px solid #BBBBBB;
	background-color:#FFFFFF;
}

#ps_header
{
	width:850px;
	margin-left:auto;
	margin-right:auto;
	margin-bottom:60px;
	padding-top:20px;
	background-color:#FFFFFF;
	border:3px solid #CCCCCC;
}

.pshd_line
{
	padding:10px 30px;
}

.pshd_input_wrp
{
	display:inline-block;
	vertical-align:top;
	width:75%;
}

#pstitle
{
	width:550px;
	font-size:16px;
	font-family:Verdana, Geneva, sans-serif;
	font-size:16px;
	padding:5px;
	color:#555555;
	border:1px solid #FFFFFF;
}

#pshdup_title_submit
{
	font-family:Verdana, Geneva, sans-serif;
	padding:3px 8px;
}

#psinstr
{
	width:500px;
	height:80px;
	padding:5px;
	font-family:Arial, Helvetica, sans-serif;
	font-size:14px;
	color:#888888;
	border:1px solid #CCCCCC;
}

#psauthor
{
	border:1px solid #FFFFFF;
}

.pshd_update_wrp
{
	display:inline-block;
	vertical-align:top;
	width:20%;
	text-align:right;
}

.mosharp
{
	display: inline-block;
	opacity: 0.5;
}

@keyframes opanime {
    from {opacity: 0.5;}
    to {opacity: 1.0;}
}

.mosharp:hover
{
	opacity: 1.0;
	animation-name: opanime;
    animation-duration: 1s;
}

#compile
{
	display:inline-block;
	margin:40px 0 0 30px;
	padding:10px 20px;
	font-size:20px;
	font-family:Verdana, Geneva, sans-serif;
	color:#FFFFFF;
	background-color:#EB6060;
}



</style>


</head>

<body>

<?php 

	require_once('parts/before_content.php'); 

?>

    <div id="main">	
	
		<div id="ps_header">
			
			<div class="pshd_line">
				
				<div class="pshd_input_wrp">
					
					<input type="text" id="pstitle" placeholder="Title" value="<?php echo $pstitle; ?>" />
					
				</div>
				
				<div class="pshd_update_wrp">
					
					<input type="button" id="pshdup_title_submit" value="Update title" data-directpost="<?php
					
						echo urlencode(json_encode(array(
																	
													'senddata' => 	array(
																	
															PRESCRIPTID => $_GET['id'],
																		
															'action' => PS_HEADER_UPDATE,	
																		
															PS_HDUP_TARGET => 'title'
																			
																			),					     													
													'inputdata' => 	array(
																	
															[ PS_HDUP_TEXT, 'pstitle' ]
																			)										
																										)));
					?>" onclick = "attReader(this.id);" />
					
				</div>
			
			</div>
			
			<div class="pshd_line">
				
				<div class="pshd_input_wrp">
				
					<textarea id="psinstr" placeholder="Instructions"><?php echo $psinstr; ?></textarea>
					
				</div>
				
				<div class="pshd_update_wrp">
					
					<input type="button" id="pshdup_instr_submit" value="Update instructions" data-directpost="<?php
					
						echo urlencode(json_encode(array(
																	
													'senddata' => 	array(
																	
															PRESCRIPTID => $_GET['id'],
																		
															'action' => PS_HEADER_UPDATE,	
																		
															PS_HDUP_TARGET => 'instr'
																			
																			),					     													
													'inputdata' => 	array(
																	
															[ PS_HDUP_TEXT, 'psinstr' ]
																			)										
																										)));
					?>" onclick = "attReader(this.id);" />
					
				</div>
			
			</div>
			
			<div class="pshd_line">
			
				<div class="pshd_input_wrp">
				
					<input type="text" id="psauthor" placeholder="Author" value="<?php echo $psauthor; ?>" />
					
				</div>
				
				<div class="pshd_update_wrp">
					
					<input type="button" id="pshdup_author_submit" value="Update author" data-directpost="<?php
					
						echo urlencode(json_encode(array(
																	
													'senddata' => 	array(
																	
															PRESCRIPTID => $_GET['id'],
																		
															'action' => PS_HEADER_UPDATE,	
																		
															PS_HDUP_TARGET => 'author'
																			
																			),					     													
													'inputdata' => 	array(
																	
															[ PS_HDUP_TEXT, 'psauthor' ]
																			)										
																										)));
					?>" onclick = "attReader(this.id);" />
					
				</div>
			
			</div>
			
			<div id="pshdupfeedback" class="pshd_line"></div>
			
		</div>
	
		
		<?php
			
	
			$disproc = new ProcessingDisplayer( $_GET['id'] ); // Processings diaplayer
	
			$loadproc = new ProcessingLoader( $_GET['id'],'html' ); // Load processings
			
			$loadcdt = new ConditionTable( $_GET['id'],false,true ); // Load condition tables
			
			// LATER: Load variables
			
			$tbi_out_txt->displayReady( $_GET['id'] ); // Output Text
			
			$ps_main = new PrescriptMain( $_GET['id'],'html' ); // Load PS to HTML output
			
		?>
		
		<div id="wrp_code">
		
			<?php echo $code_display->html; ?>
			
		</div>
		
		<div class="sec_out_wrp">
		
		<div class="sec_header">
		
			<div class="sec_title"><h1>Processing</h1></div>
			
			<div class="secintro">Allows you to create new variables using existing ones. 
			IN-Vars and OUT-Vars can only be added from the flow section. 
			It's recommended that you finish building the flow section before you get to this section.</div>
			
		</div>
		
		<div id="wrp_procsec">
		
			<div id="proc_sec_main">
			
				<?php echo $disproc->html; ?>
				
			</div>
		
		</div>
		
		</div>
		
		<a href="getjs.php?id=<?php echo $_GET['id']; ?>"><div id="compile">Download the program</div></a>

	
    </div>


<?php require_once('parts/after_content.php'); ?>

</body>
</html>
