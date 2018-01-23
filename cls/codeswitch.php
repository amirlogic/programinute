<?php


// Programinute - Code Switch - Send code to Display or Prog

// Also handles External tables

// Copyright 2015-2016 Amir Hachaichi



	class CodeSwitch {
		
		
			public $prescript;
		
			public $mode; // html / python
			
			public $position;
			
			public $curinside = false; 
			
			
			//public $tblnum = array(); // CDT Table ID to Table Number
			
			public $cdtype = array(); // Condition column type: (if/elseif) or else | [tbid][letter] (Where do the data come from?)
			
			
			public $cdt_headerr = array(); // Only num for the moment
			
			public $input_textrr = array();
			
			public $output_textrr = array();
			
			
			public $error;
			
			public $test;
			
			
			
			
			public function __construct( $psid ){ // -------------------------------------------------------------- INIT
				
				global $dbz;
				global $userps;
				
				$this->prescript = $psid;
				
				// ------------------[ PreLoad ]
				
				// Cdt Headers
				
				$cdthdrr = $dbz->prepSelectAll( 'cdt_header', array( 	[ 'prescript', '=', $this->prescript, 'i' ],
																		
																		[ 'user', '=', $userps->usrid, false ]
																		
																	),false );
				while( $row = $dbz->fetch_array( $cdthdrr ) ){
					
					$this->cdt_headerr[ $row['id'] ] = $row['num'];
				}
				
				
				// Input Text
				
				$inptxtrr = $dbz->prepSelectAll( 'cmd_input_text', array( 	[ 'prescript', '=', $this->prescript, 'i' ],
																		
																			[ 'user', '=', $userps->usrid, false ]
																		
																	),array( [ 'vnum','ASC' ] ) );
				while( $row = $dbz->fetch_array( $inptxtrr ) ){
					
					$this->input_textrr[ $row['id'] ] = $row;
					
					//array( $row['type'], $row['vnum'], $row['title'], $row['dscr'], $row['nrows'], $row['array'] );
				}
				
				
				// Output Text
				
				$outxtrr = $dbz->prepSelectAll( 'cmd_output_text', array( 	[ 'prescript', '=', $this->prescript, 'i' ],
																		
																			[ 'user', '=', $userps->usrid, false ]
																		
																	),false );
				while( $row = $dbz->fetch_array( $outxtrr ) ){
					
					$this->output_textrr[ $row['oid'] ] = $row;
					
				}
			}
			
			
			public function setMode( $swmode ){ // ----------------------------------------------------------------- SET MODE
				
				$this->mode = $swmode;
			}
			
			
			public function startInputSec(){ // -------------------------------------------------------------- INPUT SEC START
				
				global $code_display;
				
				if( $this->mode == 'html' ){
					
					$code_display->startInputSection();
					
				}
				
			}
			
			
			public function inputText( $inpid,$pos ){ // ---------------------------------------------------------- INPUT TEXT
				
				global $tbi_inp_txt;
				global $code_display;
				global $py_comp;
				global $jswrt;
				
				$this->position = $pos;
				
				
				if( $this->mode == 'html' ){
					
					$code_display->inputText( $this->input_textrr[ $inpid ],true );
					
				}
				else if( $this->mode == 'python' ){
					
					$py_comp->inputText( $this->input_textrr[ $inpid ] );
					
				}
				else if( $this->mode == 'javascript' ){
					
					$jswrt->addTextInput( $this->input_textrr[ $inpid ] );
				}
				
			}
			
			
			public function endInputSec(){ // ------------------------------------------------------------------ INPUT TEXT END
				
				global $code_display;
				
				if( $this->mode == 'html' ){
					
					$code_display->endInputSection();
					
				}
				
			}
			
			
			public function startProcSec(){ // ----------------------------------------------------------- START PROCESSING SEC
				
				global $code_display;
				
				if( $this->mode == 'html' ){
					
					$code_display->startProcSection();
					
				}
				
			}
			
			
			public function endProcSec(){ // --------------------------------------------------------------  END PROCESSING SEC
				
				global $code_display;
				
				if( $this->mode == 'html' ){
					
					$code_display->endProcSection();
					
				}
			}
			
			
			public function startOutputSec(){ // ------------------------------------------------------------ START OUTPUT SEC
				
				global $code_display;
				
				if( $this->mode == 'html' ){
					
					$code_display->startOutputSection();
					
				}
			}
			
			
			public function endOutputSec(){ // ---------------------------------------------------------------- END OUTPUT SEC
				
				global $code_display;
				
				if( $this->mode == 'html' ){
					
					$code_display->endOutputSection();
					
				}
			}
			
			
			public function startFlowSec(){ // ------------------------------------------------------------------ FLOW SEC START
				
				global $code_display;
				global $py_comp;
				
				if( $this->mode == 'html' ){
					
					$code_display->startFlowSection();
					
				}
				else if( $this->mode == 'python' ){
					
					//$py_comp->initProcOutvarDict();
				}
			}
			
			
			public function endFlowSec(){ // -------------------------------------------------------------------- FLOW SEC END
				
				global $code_display;
				
				if( $this->mode == 'html' ){
					
					$code_display->endFlowSection();
					
				}
			}
			
			
			// #=========================================================================================================#
			
			
			public function startOutputText( $outid,$pos ){ // ----------------------------------------------- START OUTPUT TEXT
				
				global $tbi_out_txt;
				global $code_display;
				global $py_comp;
				global $jswrt;
				
				$this->position = $pos;
				
				
				if( $this->mode == 'html' ){
					
					$code_display->startOutputText( $this->output_textrr[ $outid ],true );
					
				}
				else if( $this->mode == 'python' ){
					
					$py_comp->startOutputText( $this->output_textrr[ $outid ][ 'otnum' ] );
					
				}
				else if( $this->mode == 'javascript' ){
					
					$jswrt->startOutputText( $this->output_textrr[ $outid ][ 'otnum' ] );
					
				}
			}
			
			
			public function addOutputBlock( $blkid,$pos ){ // ----------------------------------------------- ADD OUTPUT BLOCK 
				
				global $code_display;
				global $py_comp;
				global $jswrt;
				global $tbi_out_blocks;
				
				$this->position = $pos;
				
				$tbi_out_blocks->setBlockId( $blkid );
				
				
				if( $this->mode == 'html' ){
					
					$code_display->startOutputBlock( $blkid,true );
					
						$tbi_out_blocks->sendBricksTo('html');
					
					$code_display->endOutputBlock();
					
				}
				else if( $this->mode == 'python' ){
					
					$py_comp->startOutputBlock();
					
						$tbi_out_blocks->sendBricksTo('python');
					
					$py_comp->endOutputBlock();
					
				}
				else if( $this->mode == 'javascript' ){
					
					$jswrt->startOutputBlock();
					
						$tbi_out_blocks->sendBricksTo('javascript');
					
					$jswrt->endOutputBlock();
				}
				
			}
			
			
			public function endOutputText( $pos ){ // ------------------------------------------------------- END OUTPUT TEXT
				
				global $code_display;
				global $py_comp;
				global $jswrt;
				
				$this->position = $pos;
				
				
				if( $this->mode == 'html' ){
					
					$code_display->endOutputText();
				}
				else if( $this->mode == 'python' ){
					
					$py_comp->endOutputText();
				}
				else if( $this->mode == 'javascript' ){
					
					$jswrt->endOutputText();
					
				}
				
			}
			
			
			public function conditionTable( $tblid,$pos ){ // ---------------------------------------------- CONDITION TABLE
				
				global $code_display;
				global $loadcdt;
				global $jswrt;
				global $py_comp;
				
				$this->position = $pos;

				
				if( $this->mode == 'html' ){
					
					$code_display->conditionTable( $tblid,$this->cdt_headerr[ $tblid ],$loadcdt->html[ $tblid ],true );
				}
				else if( $this->mode == 'javascript' ){
					
					$jswrt->load_cdt_table( $this->cdt_headerr[ $tblid ], // Table Num
					
											$loadcdt->ifvar_alldata[ $tblid ], $loadcdt->allcol_letters[ $tblid ] );
				}
				else if( $this->mode == 'python' ){
					
					$py_comp->conditionTable( $tblid,$this->cdt_headerr[ $tblid ] );
				}
				
			}
			
			
			public function startCdtSwitch( $swid,$tblid,$pos ){ // --------------------------------------- START CDT SWITCH
				
				global $code_display;
				global $py_comp;
				global $jswrt;
				
				$this->position = $pos;
				
				if( $this->mode == 'html' ){
					
					$code_display->startCdtSwitch( $swid,$this->cdt_headerr[ $tblid ],true );
				}
				else if( $this->mode == 'javascript' ){
					
					$jswrt->startCdtSwitch( $tblid );
				}
				else if( $this->mode == 'python' ){
					
					$py_comp->startCdtSwitch( $tblid );
				}
				
				
			}
			
			
			public function startCondition( $tblid,$letter,$level,$pos ){ // -------------------------------------- CONDITION
				
				global $code_display;
				global $py_comp;
				global $jswrt;
				
				
				if( $this->mode == 'html' ){
					
					$code_display->startCondition( $this->cdt_headerr[ $tblid ],$letter,$level );
					
				}
				else if( $this->mode == 'javascript' ){
					
					$jswrt->startCondition( 	$tblid,
					
												$this->cdt_headerr[ $tblid ], // Table Number
											
												$letter,
											
												$this->cdtype[ $tblid ][ $letter ], // Type (if/elseif or else)
											
												$level	);
				}
				else if( $this->mode == 'python' ){
					
					$py_comp->startCondition( 	$tblid,
					
												$this->cdt_headerr[ $tblid ], // Table Number
											
												$letter,
											
												$this->cdtype[ $tblid ][ $letter ], // Type (if/elseif or else)
											
												$level	);
				}
				
			}
			
			
			public function endCondition( $level,$pos ){ // --------------------------------------------------- END CONDITION
				
				global $code_display;
				global $py_comp;
				global $jswrt;
				
				
				if( $this->mode == 'html' ){
					
					$code_display->endCondition($level);
					
				}
				else if( $this->mode == 'javascript' ){
					
					$jswrt->endCondition();
				}
				else if( $this->mode == 'python' ){
					
					$py_comp->endCondition($level);
				}
			}
			
			
			public function endCdtSwitch( $tblid,$pos ){ // ------------------------------------------------ START CDT SWITCH
				
				global $code_display;
				global $py_comp;
				
				$this->position = $pos;
				
				if( $this->mode == 'html' ){
					
					$code_display->endCdtSwitch($tblid);
					
				}
				else if( $this->mode == 'python' ){
					
					//$py_comp->endCdtSwitch($tblid);
				}
				
				
			}
			
			
			public function stemLine( $id,$cdt,$sub,$target ){ // ------------------------------------------------- STEM LINE
				
				global $code_display;
				
				if( $this->mode == 'html' ){
					
					$code_display->stemLine( $id,$cdt,$sub,$target );
					
				}
				
			}
			
			public function outputCall( $otype,$oclid ){ // -------------------------------------------------- OUTPUT CALL
				
				global $code_display;
				global $py_comp;
				global $tbi_out_call;
				global $jswrt;
				
				
				$tbi_out_call->setOutputType( $otype );
				
				$tbi_out_call->setOutputCallId( $oclid );
				
				
				if( $this->mode == 'html' ){
					
					$tbi_out_call->sendLinkedOvarsTo( 'html' );
					
					$code_display->outputCall( $oclid,$otype,true );
					
				}
				else if( $this->mode == 'python' ){
					
					$tbi_out_call->sendLinkedOvarsTo( 'python' );
					
					$py_comp->outputCall();
					
				}
				else if( $this->mode == 'javascript' ){
					
					$tbi_out_call->sendLinkedOvarsTo( 'javascript' );
					
					$jswrt->outputCall( $otype,$oclid );
				}
				
			}
			
			
			public function varProc( $pid ){ // ------------------------------------------------------------- VAR PROCESSING
				
				global $code_display;
				global $py_comp;
				global $tbi_proc;
				global $loadproc;
				global $jswrt;
				
				if( $this->mode == 'html' ){ // -------------------------------------------------------------- Html
					
					$code_display->startVarProc( $pid,true );
					
						foreach( $loadproc->invarr[$pid] as $invline ) // IN
						{
							$code_display->varProcInVars( $invline[1], '' );
						}
						
						$code_display->varProcInStem( $pid,$loadproc->instmid[$pid] );
						
						foreach( $loadproc->outvarr[$pid] as $outvline ) // OUT
						{
							$code_display->varProcOutVar( $outvline[1], $outvline[2], true );
						}
						
						$code_display->varProcOutStem( $loadproc->outstmid[$pid] );
						
						// LATER: Integrate 
					
					$code_display->endVarProc();
					
				}
				else if( $this->mode == 'python' ){ // ------------------------------------------------------- Py
					
					$py_comp->procInVar( $pid,$loadproc->invarr[$pid] );
					
					$py_comp->callProcessing( $pid );
					
					foreach( $loadproc->outvarr[$pid] as $outvline ) // OUT
					{
						$py_comp->procNewPvar( $outvline[1] );
					}
					
				}
				else if( $this->mode == 'javascript' ){ // -------------------------------------------------- Js
					
					$jswrt->insertProcData( $pid );
				}
				
			}
			
			
	}
	
	

?>