<?php

	// PGD Prescript Body Loader


	class PrescriptMain {
		
		
		public $mode; // html (Display) or python
		
		public $cdt_subsw = array();
		
		public $cdt_data = array();
		
		public $cdt_letter = array();
		
		public $cdt_sw_top = array();
		
		public $cdt_parsw = array();
		
		public $pstr = array();
		
		
		
		public $error;
		
		
		public function __construct( $psid,$swmode ){
			
			global $dbz;
			global $userps;
			global $codesw;
			
			$bodyrr = $dbz->prepSelectAll( 'str_prescript_body',
			
											array(
													[ 'prescript', '=', $psid, 'i' ],	[ 'user', '=', $userps->usrid, false ]
												),
											
											array(
													[ 'pos', 'ASC' ], [ 'subpos', 'ASC' ], [ 'cdtpos', 'ASC' ], 
													
													[ 'inpos', 'ASC' ], [ 'insubpos', 'ASC' ]
												) );
			
			/*$psbq = "SELECT * FROM `str_prescript_body` WHERE `prescript`='" . $psid 
			. "' ORDER BY `pos` ASC, `subpos` ASC, `cdtpos` ASC, `inpos` ASC, `insubpos` ASC;";$bodyrr = $dbz->query($psbq);*/
			
			
			$i = 0;
			//$curpos = 0; // Current position
			$lastpos = false;
	
			$lasttarget = false;
	
			$curinside = false; // sub or cdt_sub
	
			// SUB
			$cursub = false;
			
			$lastinpos = array();
			
			// Code Switch
			
			$codesw->setMode($swmode);
			
			
			
			while( $row = $dbz->fetch_array($bodyrr) ){ // ----------------------------------------------------------- WHILE
				
				
				if($i == 0){ // Start Check
			
					if($row['pos'] != 0){
				
						$this->error = "DB Error: Position problem";
					}
				}

				
				if( $row['target'] == 'cmd' ){ // -------------------------------------------------------------------# CMD #
			
					// Position Check
			
					if($row['pos'] != 0){
				
						if($row['pos'] <= $lastpos){
					
							$this->error = "DB Error: Position problem";
						}
					}
			
					if( $row['cmd'] == 'input_text' ){ // ----------------------------------------------------- INPUT_TEXT
				
						
						$codesw->inputText( $row['cmdlink'],$row['pos'] );
				
						/*$this->pstr[$row['pos']] = array('input_text', // Command type
												$row['cmdlink'] // Input id in the input table
												);*/
				
						
					}
					else if( $row['cmd'] == 'secstart_output' ){ // ------------------------------------------- OUTPUT SEC START
						
						$codesw->startOutputSec();
						
					}
					else if( $row['cmd'] == 'secend_output' ){ // --------------------------------------------- OUTPUT SEC END
						
						$codesw->endOutputSec();
						
					}
					else if( $row['cmd'] == 'secstart_flow' ){ // --------------------------------------------- FLOW SEC START
						
						$codesw->startFlowSec();
						
					}
					else if( $row['cmd'] == 'secend_flow' ){ // ----------------------------------------------- FLOW SEC END
						
						$codesw->endFlowSec();
						
					}
					else if( $row['cmd'] == 'outcall_text' ){ // ---------------------------------------------- OUTCALL TEXT
						
						$codesw->outputCall( 'text',$row['cmdlink'] );
						
					}
					else if( $row['cmd'] == 'varproc' ){ // --------------------------------------------------- VAR PROCESSING
						
						$codesw->varProc( $row['cmdlink'] );
						
					}
			
					//$lastinc = 'pos';
			
				}
				else if( $row['target'] == 'substart' ){ // -------------------------------------------------- # SUBSTART #
			
					// Position Check
			
					if($row['pos'] != 0){
				
						if($row['pos'] <= $lastpos){
					
							$this->error = "DB Error: Position problem";
						}
					}
			
				
					$curinside = 'sub';
					$cursub = $row['cmd'];
					
					
					if( $row['cmd'] == 'output_text' ){ // -------------------------------------------------- OUTPUT_TEXT_START
				
						$codesw->startOutputText( $row['cmdlink'],$row['pos'] );
				
					}
					else if( $row['cmd'] == 'input_sec' ){
						
						$codesw->startInputSec();
					}
			
				}
				else if( $row['target'] == 'subcmd' ){ // --------------------------------------------------------# SUBCMD #
			
					// Position Check
			
					if( $row['pos'] != $lastpos || $curinside != 'sub' ){
					
						$this->error = "DB Error: Position problem";
					}
			
					if( $row['cmd'] == 'output_block' ){ // -------------------------------------------- OUTPUT_TEXT_BLOCK
				
						$codesw->addOutputBlock( $row['cmdlink'],$row['pos'] );
				
						//$this->pstr[$row['pos']][2][] = $row['cmdlink']; // New text block
					}
					else if( $row['cmd'] == 'input_text' ){ // ----------------------------------------- INPUT_TEXT
								
						$codesw->inputText( $row['cmdlink'],$row['pos'] );
					}
			
					
				}
				else if( $row['target'] == 'subend' ){ // -----------------------------------------------------------# SUBEND #
			
					// Position Check
			
					if( $row['pos'] != $lastpos || $curinside != 'sub' ){
					
						//$this->error = "DB Error: Position problem");
					}
			
					if( $row['cmd'] == 'output_text' ){ // OUTPUT_TEXT_END
				
						$codesw->endOutputText( $row['pos'] );
					}
					else if( $row['cmd'] == 'input_sec' ){
						
						$codesw->endInputSec();
					}
			
					$curinside = false;
			
				}
				else if( $row['target'] == 'cdt' ){ // ##########################################################> Condition <#
			
			
					if( $row['cdtfunc'] == 'header' ){ // ----------------------------------------------------- CONDITION_TABLE
				
						if($row['pos'] != 0){
				
							if($row['pos'] <= $lastpos){
					
								$this->error = "DB Error: Position problem";
							}
						}
						
						$codesw->conditionTable( $row['cdtable'],$row['pos'] );
						
						/*$this->pstr[$row['pos']] = array('cdt_header', // Command type
												$row['cdtable'] // Output id in the condition table
												);*/
			
				
					}
					else{ // --------------------------------------------------------------- SWITCH
		
						if( $row['cdtfunc'] == 'swstart' ){ // --------------------------------------------------- SWITCH START
					
					
							if( $row['pos'] == $lastpos || $row['cdtpos'] != 0 ){
						
								$this->error = "DB Error: Position problem";
							}
					
					
							$curcdtpos = 0;
					
							$codesw->startCdtSwitch( $row['id'], $row['cdtable'], $row['pos'] );
					
							/*$this->pstr[$row['pos']] = array('cdt_switch', // Command type
														$row['cdtable'] // Reference cdtable
														);*/
					
					
							// ---------------------------------------------------------- Preparing arrays
							$this->cdt_data[$row['pos']] = array(); // Preparing Data
							$this->cdt_parsw[$row['pos']] = array(); // Subswitches
							$this->cdt_sw_top[$row['pos']] = array(); // Top level
							$this->cdt_letter[$row['pos']] = array(); // Letters
					
							// ---------------------------------------------------------- Counters
							$cdt_level = false;
							$curcond = false; // Current condition open cdtpos
							
							
							
							// Position update
							//$lastcdtpos = $row['cdtpos'];
							//$lastpos = $row['pos'];
					
						}
						else if( $row['cdtfunc'] == 'cdtstart' ){ // ------------------------------------------- CONDITION START
					
							if( $row['pos'] != $lastpos ){
						
								$this->error = "DB Error: Position problem";
							}
					
							if( $cdt_level === false ){ // Top level
						
								$this->cdt_sw_top[$row['pos']][] = $row['cdtpos'];
								$cdt_level = 0;
						
							}
							else{ // Nested
						
								$this->cdt_parsw[$row['pos']][$row['cdtpos']] = $curcond;
								$cdt_level++;
							}
					
							$this->cdt_letter[$row['pos']][$row['cdtpos']] = $row['cdtlink']; // Letter
							$curcond = $row['cdtpos']; // Current condition
					
							
							$codesw->startCondition( $row['cdtable'],$row['cdtlink'],$cdt_level,$row['pos'] );
							
							// Position update
							//$lastcdtpos = $row['cdtpos'];
							//$lastpos = $row['pos'];
					
						}
						else if( $row['cdtfunc'] == 'cmd' ){ // ------------------------------------------------------- CDT_CMD
					
					
							if( $row['cmd'] == 'outcall_text' ){ // ---------------------------------------------- OUTCALL TEXT
						
								$codesw->outputCall( 'text',$row['cmdlink'] );
							}
							else if( $row['cmd'] == 'varproc' ){ // --------------------------------------------- VAR PROCESSING
						
								$codesw->varProc( $row['cmdlink'] );
							}
							
							//$this->cdt_data[ $row['pos'] ][$curcond][] = array($row['cmd'],$row['cmdlink']);
							//Position update
							//$lastcdtpos = $row['cdtpos'];
							//$lastpos = $row['pos'];
							
						}
						else if( $row['cdtfunc'] == 'substart' ){ // --------------------------------------------- CDT_SUBSTART
					
					
							//$this->cdt_data[$row['pos']][$curcond][] = array($row['cmd'],$row['cmdlink'],array() );
																	
							$curinside = 'cdt_sub';
							$cursub = $row['cmd'];
					
							if( $row['cmd'] == 'output_text' ){ // ------------------------------------- CDT_OUTPUT_TEXT
								
								$codesw->startOutputText( $row['cmdlink'],$row['pos'] );
							}
							
							
					
						}
						else if( $row['cdtfunc'] == 'subcmd' ){ // ------------------------------------------------ CDT_SUBCMD
					
							if( $curinside != 'cdt_sub' ){
						
								$this->error = "DB Error: Nesting error";
							}
					
							// Check
							if( $row['cmd'] == 'output_block' ){
						
								if( $cursub != 'output_text' ){
							
									$this->error = "DB Error: Nesting error";
								}
							}
							else{
						
								$this->error = "DB Error: Unknown command";
							}
					
							
							if( $row['cmd'] == 'output_block' ){ // --------------------------------- CDT_OUTPUT_BLOCK
								
								$codesw->addOutputBlock( $row['cmdlink'],$row['pos'] );
								
							}
							
					
							// Add to array
							//$addindex = count($this->cdt_data[$row['pos']][$curcond]) - 1;
							//$this->cdt_data[$row['pos']][$curcond][$addindex][2][] = $row['cmdlink'];
					
					
							
					
						}
						else if( $row['cdtfunc'] == 'subend' ){ // ------------------------------------------------ CDT_SUBEND
					
							if( $curinside != 'cdt_sub' ){
						
								$this->error = "DB Error: Nesting error";
							}
					
							if( $row['cmd'] != $cursub ){
						
								$this->error = "DB Error: Nesting error";
						
							}
					
							$curinside = false;
							
							if( $row['cmd'] == 'output_text' ){ // -------------------------- CDT_OUTPUT_TEXT_END
				
								$codesw->endOutputText( $row['pos'] );
						
							}
					
						}
						else if( $row['cdtfunc'] == 'cdtend' ){ // ---------------------------------------------- CONDITION END
					
							$codesw->endCondition( $cdt_level,$row['pos'] );
					
							if($cdt_level === 0){
						
								$cdt_level = false;
							}
							else{
						
								$cdt_level--;
							}
							
							// Position update
							//$lastcdtpos = $row['cdtpos'];
							//$lastpos = $row['pos'];
					
						}
						else if( $row['cdtfunc'] == 'swend' ){ // -------------------------------------------------- SWITCH END
					
							$lastcdtpos = false;
							
							$codesw->endCdtSwitch( $row['cdtable'], $row['pos'] );
							
							
							//echo "</div>"; // TEST
							// Position update
							//$lastcdtpos = $row['cdtpos'];
							//$lastpos = $row['pos'];
							
						}
						else if( $row['cdtfunc'] == 'stm' ){ // --------------------------------------------------- STEM LINE
							
							$cdt = true;
							$sub = false;
							
							$codesw->stemLine( $row['id'],$cdt,$sub,$row['cmd'] );
							
						}
						else if( $row['cdtfunc'] == 'substm' ){ // ------------------------------------------------ STEM LINE
							
							$cdt = $sub = true;
							
							$codesw->stemLine( $row['id'],$cdt,$sub,$row['cmd'] );
							
						}
						else{
					
							$this->error = "DB Error: Invalid entry";
						}
					}
				}
				else if( $row['target'] == 'stm' ){ // ------------------------------------------------------------ STEM LINE
					
					$sub = $cdt = false;
					
					
					$codesw->stemLine( $row['id'],$cdt,$sub,$row['cmd'] );
					
				}
				else if( $row['target'] == 'substm' ){ // ----------------------------------------------------- SUB STEM LINE
					
					$sub = true;
					$cdt = false;
					
					$codesw->stemLine( $row['id'],$cdt,$sub,$row['cmd'] );
					
				}
				else{
			
					$this->error = "DB Error";
				}
		
		
				$i++;
				$lastpos = $row['pos'];
				$lasttarget = $row['target'];
				
			}
			
		}
		
	}

	
?>