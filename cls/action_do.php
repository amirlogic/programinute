<?php

// Programinute - Action Do - Form Data Handling [ $doit ] ( init in $postgate )

// Copyright 2015-2016 Amir Hachaichi

	
	

	class ActionDo {
		
		public $error;
		
		public $prescript;
		
		public $data;
		
		public $message;
		
		public $lasterror = false;
		
		
		
		public function __construct( $psid ){
			
			$this->prescript = $psid;
			
			
		}
		
		
		public function prescriptHeader( $target,$uptxt ){ // #=============================================== PS HEADER
			
			global $userps;
			
			$areturn = new ActionReturn( $this->prescript );
			
			$userps->updatePrescriptHeader( $this->prescript,$target,$uptxt );
			
			if( $userps->lasterror === false ){
				
				$areturn->prescriptHeaderUpdate( true, $target );
			}
			else{
					$areturn->prescriptHeaderUpdate( false, $target );
					//echo $userps->lasterror;
			}
		}
		
		
		public function cdtHeader( $mod ){ // #=========================================================== CDT TABLE =======
			
			
			$tbi_cdt = new TBIConditions( $this->prescript );

			$areturn = new ActionReturn( $this->prescript );
			
			
			if( $mod == 'new_header' ){ // ------------------------------------------------------------------- NEW CDT HEADER
				
				$tbi_ps_main = new TBIPrescriptMain( $this->prescript );
				
				$hgmain = new HighLevelMain( $this->prescript );
				
				$tbi_cdt->newTable();
				
				if( $tbi_cdt->lasterror === false ){ 
					
					$hgmain->addConditionTable( $this->data,$tbi_cdt->tblid );
				}
				else{
						$this->lasterror = $tbi_cdt->lasterror;
				}
				
				if( $this->lasterror === false ){ // Success
					
					$areturn->successNewCdtHeader( array(
															'tablenum' => $tbi_cdt->tablenum,				
														
															'tableid' => $tbi_cdt->tblid			
														
															) );
				}
				else{
						echo "ERROR: " . $this->lasterror;
				}	
			}
			else if( $mod == 'new_top_column' ){ // ---------------------------------------------------- NEW TOP LEVEL COL
				
				$tbi_cdt->loadTable( $this->data[0] ); // Must provide ID
				
				$tbi_cdt->newColumn( false );
				
				if( $tbi_cdt->lasterror === false ){
					
					$areturn->cdtHeaderAddColumn( true, true, $this->data[0], $this->data[1] );
				}
				else{
						//$areturn->reloadCdtHeader( $this->data[0] );
				}
				
				
				
			}
			else if( $mod == 'new_sub_column' ){ // ---------------------------------------------- NEW TABLE COLUMN INSIDE
				
				$tbi_cdt->loadTable( $this->data[0] ); // Must provide ID

				$tbi_cdt->addNewSubColumn( $this->data[1] );
				
				if( $tbi_cdt->lasterror === false ){
					
					$areturn->cdtHeaderAddColumn( true, false, $this->data[0], $this->data[2] );
				}
				else{
					
				}
				
			}
			else if( $mod == 'load_column' ){ // -------------------------------------------------------- LOAD COLUMN
				
				$tbi_cdt->loadTable( $this->data[0] );
				
				$tbi_cdt->loadColumn( $this->data[1] );
				
				$this->lasterror = $tbi_cdt->lasterror;
				
				if( $this->lasterror === false ){ // Success
					
					$areturn->successCdtHeaderColEdit( $this->data[0],$this->data[1],
														
														$tbi_cdt->edithtml, $tbi_cdt->showhtml,false );
				}
				else{
						$areturn->cdtHeaderError( $this->data[0], 0, 'load_column', $tbi_cdt->errortxt );
				}
			}
			else if( $mod == 'clear_column' ){ // ------------------------------------------------------ CLEAR COLUMN
				
				$tbi_cdt->loadTable( $this->data[0] );
				
				$tbi_cdt->clearColumn( $this->data[1] );
				
				$this->lasterror = $tbi_cdt->lasterror;
				
				if( $this->lasterror === false ){ // Success
					
					$areturn->successCdtHeaderClearColumn( $this->data[0], $this->data[2], $this->data[1]  );
				}
				else{
						echo "Error: " . $this->lasterror;
				}
			}
			else if( $mod == 'delete_column' ){ // ------------------------------------------------------ DELETE COLUMN
				
				$tbi_cdt->loadTable( $this->data[0] );
				
				$tbi_cdt->deleteColumn( $this->data[1] );
				
				$this->lasterror = $tbi_cdt->lasterror;
				
				if( $this->lasterror === false ){ // Success
					
					$areturn->successCdtHeaderDeleteColumn( $this->data[0], $this->data[2], $this->data[1]  );
				}
				else{
						$areturn->cdtHeaderError( $this->data[0], $this->data[2], 'delete_column', $tbi_cdt->errortxt );
					
						//echo "Error: " . $this->lasterror;
				}
			}
			else if( $mod == 'col_ins_top_cdt' ){ // ------------------------------------------------------- INSERT COL TOP CDT
				
				$tbi_cdt->loadTable( $this->data[0] );
				
				$tbi_cdt->setCurColumn( $this->data[1] );
				
				$tbi_cdt->addMainStackCdt( $this->data[2],$this->data[3],$this->data[4],$this->data[5],$this->data[6],$this->data[7] );
				
				
				$this->lasterror = $tbi_cdt->lasterror;
				
				if( $this->lasterror === false ){ // Success
					
					$areturn->successCdtHeaderColEdit( $this->data[0],$this->data[1],
														
														$tbi_cdt->edithtml, $tbi_cdt->showhtml, true );
				}
				else{
					
					echo $this->lasterror;
				}
				
			}
			else if( $mod == 'col_ins_top_stack' ){ // ------------------------------------------- INSERT COL TOP STACK
				
				
			}
			
			
		}
		
		
		public function cdtSwitch( $mod ){ // #========================================================= CDT TABLE =======
			
			$tbi_ps_main = new TBIPrescriptMain( $this->prescript );
			
			$sworg = new SwitchOrganizer( $this->prescript );
			
			$areturn = new ActionReturn( $this->prescript );
			
			
			if( $mod == 'new_switch' ){ // --------------------------------------------------------------- NEW CDT SWITCH
				
				$sworg->setTable( $this->data[1] );		$sworg->loadTable();
				
				$sworg->newSwitch( $this->data[0] );
				
				if( $tbi_ps_main->lasterror === false && $sworg->lasterror === false ){
					
					$areturn->successNewSwitch( $sworg->switchid, $sworg->html );
				}
				else{ // Error
					
				}
				
			}
			
		}
		
		
		public function outputText( $mod ){ // #================================================== OUTPUT TEXT ==============
			
			global $tbi_out_txt;
			global $areturn;
			
			$tbi_ps_main = new TBIPrescriptMain( $this->prescript );
			
			$hgmain = new HighLevelMain( $this->prescript );
			
			$areturn = new ActionReturn( $this->prescript );
			
			
			if( $mod == 'new_output_text' ){ // --------------------------------------------------------- OUTPUT TEXT
				
				$tbi_out_txt->setPrescript( $this->prescript );
				
				$tbi_out_txt->addOutput( $this->data[1],$this->data[2] );
				
				// Adding output block
				
				$tbi_out_blocks = new TBIOutputBlocks( $this->prescript );
				
				$tbi_out_blocks->setOutputId( $tbi_out_txt->lastid );
				
				$tbi_out_blocks->addStem(); // Also generates new block id and stores stem id
				
				
				if( $tbi_out_txt->lasterror === false ){
					
					$hgmain->addOutputText( $this->data[0],$tbi_out_txt->lastid,$tbi_out_blocks->block );
					
					$this->lasterror = $tbi_ps_main->lasterror;
					
				}
				else{
					
					$this->lasterror = $tbi_out_txt->lasterror;
				}
				
				if( $this->lasterror == false ){ // Everything succeded
					
					global $dbz;
					
					$areturn->successOutputText( array(
														
														'otnum' => $tbi_out_txt->nxtotnum,
														
														'stmid' => $this->data[0], // Output Sec Stem ID
														
														'blockstmid' => $dbz->lastinsid,
														
														'blockid' => $tbi_out_blocks->block,
														
														'brickstmid' => $tbi_out_blocks->newstem,
														
														'title' => $this->data[1]
														
														) );
					
				}
				else{ // Error
					
					echo "Error: " . $this->lasterror; // Debug
				}
								
			}
			else if( $mod == 'new_output_block' ){ // --------------------------------------------------------- OUTPUT BLOCK
				
				
				$hgmain->getStem( $this->data[0],$this->data[1] ); // Also gets Output ID
				
				
				$tbi_out_blocks = new TBIOutputBlocks( $this->prescript );
				
				$tbi_out_blocks->setOutputId( $hgmain->stminside );
				
				$tbi_out_blocks->addStem(); // Also generates new block id
				
				if( $tbi_out_blocks->lasterror === false ){
					
					$hgmain->addOutputBlock( $tbi_out_blocks->block );
					
					$this->lasterror = $hgmain->lasterror;
				}
				else{
					
					$this->lasterror = $tbi_out_blocks->lasterror;
				}
				
				
				if( $this->lasterror === false ){ // Success
					
					$areturn->successOutputBlock( array(	'otnum' => $this->data[1],
															
															'blockid' => $tbi_out_blocks->block,
														
															'brickstmid' => $tbi_out_blocks->newstem,
															
															'stemid' => $this->data[0]
														) );
				}
				else{ // Error
					
					echo "Error: " . $this->lasterror; // Debug
				}
								
			}
			else if( $mod == 'new_output_brick' ){ // ------------------------------------------------------------ OUTPUT BRICK
				
				
				$tbi_out_blocks = new TBIOutputBlocks( $this->prescript );
				
				$tbi_out_blocks->getStem( $this->data[0] );
				
				$tbi_out_blocks->stemClear();
				
				$tbi_out_blocks->addBrick( $this->data[1],$this->data[2],$this->data[3] );
				
				$this->lasterror = $tbi_out_blocks->lasterror;
				
				
				if( $this->lasterror == false ){ // Success
					
					$areturn->successOutextBrick( array(
					
														'type' => $this->data[1],
														
														'blockid' => $tbi_out_blocks->block,
														
														'newline' => $this->data[2],
														
														'txt' => $this->data[3],
														
														'vnum' => $tbi_out_blocks->nxtovarnum,
														
														'stemid' => $this->data[0]
														
														) );
				}
				else{ // Error
					
					
				}
				
			}
			
		}
		
		
		public function inputText( $mod ){ // #======================================================= INPUT TEXT ==============
			
			global $tbi_inp_txt;

			
			$tbi_ps_main = new TBIPrescriptMain( $this->prescript );
			
			$hgmain = new HighLevelMain( $this->prescript );
			
			$varef = new VariablesReference( $this->prescript );
			
			$areturn = new ActionReturn( $this->prescript );
			
			
			if( $mod == 'new_input_text' ){
				
				$tbi_inp_txt->setPrescript( $this->prescript );
				
				$tbi_inp_txt->getNextVarNum();
				
				$tbi_inp_txt->addInputText( $this->data[1],$this->data[2],$this->data[3],$this->data[4],0 );
				
				if( $tbi_inp_txt->lasterror === false ){
					
					// Registering Variable
					$varef->addNew( 'in',$tbi_inp_txt->nxtvarnum,$tbi_inp_txt->newid,$this->data[3],0,0,0 );
					
					if( $varef->lasterror === false ){
						
						$hgmain->addInputText( $this->data[0],$tbi_inp_txt->newid );
					}
					else{
					
						$this->lasterror = $varef->lasterror;
					}
				}
				else{
					
					$this->lasterror = $tbi_inp_txt->lasterror;
				}
				
				if( $this->lasterror === false ){ // Success
				
					$areturn->successInputText( array(
														'title' => $this->data[3],
													
														'vnum' => $tbi_inp_txt->nxtvarnum,
													
														'stmid' => $this->data[0]
													
													) );
				
				
					//$this->message = $retmsg[0];
				}
				else{ // Error
				
					//$this->lasterror = $tbi_ps_main->lasterror;
					//$this->message = $retmsg[1];
				}
				
			}
			
			
		}
		
		
		public function outputCall( $mod ){ // #=================================================== FLOW - OUTPUT CALL =========
			
			global $tbi_out_txt;
			global $tbi_out_call;
			
			$tbi_ps_main = new TBIPrescriptMain( $this->prescript );
			
			$hgmain = new HighLevelMain( $this->prescript );
			
			$varef = new VariablesReference( $this->prescript );
			
			$tbi_out_call = new TBIOutputCall( $this->prescript );
			
			$tbi_out_blocks = new TBIOutputBlocks( $this->prescript );
			
			$areturn = new ActionReturn( $this->prescript );
			
			
			if( $mod == 'new_outcall' ){ // -------------------------------------------------------------------------- NEW OUTCALL
				
				if( $this->data[1] == 'text' ){
					
	
					$tbi_out_txt->setPrescript( $this->prescript );
					
					$tbi_out_txt->loadOutputByNum( $this->data[2] );
					
					$tbi_out_call->setNewOutcallId();
					
					$tbi_out_call->setOutputNum( $tbi_out_txt->onum );
					
					$tbi_out_blocks->setOutputId( $tbi_out_txt->oid );
					
					$tbi_out_blocks->sendOvars(); // to output call
					
					$tbi_out_call->sortNewUlOvars();
					
					
					if( $tbi_out_txt->lasterror === false 
					
						&& $tbi_out_call->lasterror === false 

						&& $tbi_out_blocks->lasterror === false ){
						
						$hgmain->addOutputCall( $this->data[0],'text',$tbi_out_call->callid );
						
						$this->lasterror = $hgmain->lasterror;
					}
					else{
						
						$this->lasterror = 'ovarspopulate';
					}
					
					//$tbi_out_call
					
					if( $this->lasterror == false ){ // Success
						
						$areturn->successNewOutputCall(array(
																'outputnum' => $this->data[2],	'outcallid' => $tbi_out_call->callid,
																
																'ovarr' => $tbi_out_call->newulovars,	'outype' => 'text',		
																
																'switchid' => $this->data[3], 'coletter' => $this->data[4] 
														));
					}
					else{ // Error
							
					}
					
				}
			}
			else if( $mod == 'outvar_link' ){ // --------------------------------------------------------------------- OUTCAL LINK
				
				$tbi_out_call->linkOvarTo( $this->data[0],$this->data[1],$this->data[2],'' ); // LATER: Add lnked var id
				
				$this->lasterror = $tbi_out_call->lasterror;
				
				if( $this->lasterror == false ){ // Success
					
					$areturn->successLinkOutputCallVar(array(	'ovid' => $this->data[0],	'ovnum' => $this->data[3],
																
																'vtxt' => $this->data[1],	'vparse' => $this->data[2]
															));
				}
				else{ // Error
					
					
				}
			}
			else if( $mod == 'reset_outcall' ){ // ----------------------------------------------------------- RESET OUTCALL
				
				$tbi_out_call->pingLoad( $this->data );			$tbi_out_call->deleteAllOvars();
				
				$tbi_out_blocks->setOutputId( $tbi_out_call->outputId );		$tbi_out_blocks->sendOvars();
				
				$this->lasterror = $tbi_out_call->lasterror;
				
				if( $this->lasterror === false ){ // Success
					
					$areturn->successResetOutputCall( $tbi_out_call->callid, $tbi_out_call->onum, $tbi_out_call->newulovars );
				}
				else{
						echo "Error: " . $this->lasterror;
				}
			}
			
			
		}
		
		
		public function varProcessing( $mod ){ // ---------------------------------------------------------- VAR PROCESSING
			
			
			$tbi_ps_main = new TBIPrescriptMain( $this->prescript );
			
			$hgmain = new HighLevelMain( $this->prescript );
			
			$varef = new VariablesReference( $this->prescript );
			
			$tbi_proc = new TBIVarProcessing( $this->prescript );
			
			$areturn = new ActionReturn( $this->prescript );
			
			
			if( $mod == 'new_varproc' ){ // ---------------------------------------------------------------- NEW VARPROC
				
				$tbi_proc->addNewProc();
				
				if( $tbi_proc->lasterror === false ){ // No error
					
					$hgmain->addProcessing( $this->data[0],$tbi_proc->procid );
					
					$this->lasterror = $hgmain->lasterror;
					
				}
				else{
					
					$this->lasterror = $tbi_proc->lasterror;
				}
				
				if( $this->lasterror === false ){ // Success
					
					$areturn->successFlowProcessing(array(
															'stmid' => $this->data,	'procid' => $tbi_proc->procid,
															
															'invstem' => $tbi_proc->invarstem,	'operstem' => $tbi_proc->operstem,
															
															'outvstem' => $tbi_proc->outvarstem,
															
															'switchid' => $this->data[1], 'coletter' => $this->data[2] 
														));
					
				}
				else{ // Error
					
					
				}
				
				/*$retmsg = array("Processing successfully created","Error: Could not create new proc");*/
			}
			else if( $mod == 'varproc_addinvar' ){ // ---------------------------------------------------------- ADD IN-VAR
				
				
				$tbi_proc->addInVar( $this->data[0],$this->data[1],0 ); // Later: Add Var ID
				
				$this->lasterror = $tbi_proc->lasterror;
				
				if( $this->lasterror === false ){ // Success
					
					$areturn->successVarProcNewInvar( array(	'stemid' => $this->data[0],
															
																'procid' => $tbi_proc->procid,
															
																'vtxt' => $this->data[1]		));
				}
				else{ // Failure
					
					echo $this->lasterror;
				}
				
				//$retmsg = array("In-Var successfully added","Error: Could not add In-Var");
			}
			else if( $mod == 'varproc_addoutvar' ){ // -------------------------------------------------------- ADD OUT-VAR
				
				
				$tbi_proc->addOutVar( $this->data[0],0,$this->data[1] ); // Later: Add Var ID
				
				$this->lasterror = $tbi_proc->lasterror;
				
				if( $this->lasterror === false ){ // Success
					
					$areturn->successAddProcOutVar( array(	'procid' => $tbi_proc->procid,
															
															'outnum' => $tbi_proc->outnum,
															
															'stemid' => $this->data[0],
															
															'ctxt' => $this->data[1]			));
				}
				else{ // Error
					
					
				}
			}
			else if( $mod == 'varproc_addoper' ){ // ---------------------------------------------------------- ADD OPERATION
				
				$opmap = array(
								'count' => array(
													// header => array( multiple,allow_nonvar )
													'allchar' => array( false,false )
												),
											
								'math' => array( // LATER: Group this????
													'log10' => array( false,true,1 ),'lnep' => array( false,true,1 ),
													'exp' => array( false,true,1 ),'sqroot' => array( false,true,1 ),
													'sinus' => array( false,true,1 ),'cosinus' => array( false,true,1 ),
													'tang' => array( false,true,1 ),'absolute' => array( false,false,1 ),
														
													// header => array( multiple,funclist,dispgrp,opfunc )
													'plus' => array( true,'list',2,array(['x',true],['y',true]) ),
													'minus' => array( true,'list',2,array(['x',true],['y',true]) ),
													'multiply' => array( true,'list',2,array(['x',true],['y',true]) ),
													'divide' => array( true,'list',2,array(['x',true],['y',true]) ),
													
													'power' => array( true,'list',2,array(['x',true],['y',true]) ),
													'root' => array( true,'list',2,array(['x',true],['y',true]) )
												
												)
						
							);
				
				if( array_key_exists( $this->data['opertype'],
									  $opmap ) && array_key_exists( $this->data['operheader'],
																	$opmap[ $this->data['opertype'] ] ) ){
					
					if( $opmap[ $this->data['opertype'] ][ $this->data['operheader'] ][0] ){ // MultiVar
						
						$multirow = true;

						$linesrr = array();		$operdata = array();
						
						for( $v = 0; $v<count($this->data['uservinp']); $v++ )
						{							
							$truevar = is_numeric( $this->data['uservinp'][$v] ) ? false : true;
							
							if( $opmap[ $this->data['opertype'] ][ $this->data['operheader'] ][1] == 'list' ){

								if( !$truevar ){ // Not a variable
									
									if( !$opmap[$this->data['opertype']][$this->data['operheader']][3][$v][1] ){ // Allow NV
									
										$this->lasterror = 'Non variable';
									}
								}
								
								$linesrr[$v] = array(

												$opmap[$this->data['opertype']][$this->data['operheader']][3][$v][0],
												
												$this->data['uservinp'][$v],

												$truevar,0	); // Add ID Later
								
								
								$operdata[$opmap[$this->data['opertype']][$this->data['operheader']][3][$v][0]] = array(

									$truevar,$this->data['uservinp'][$v]	);
								

							}
							
							
							unset($truevar);
						}
						
						if( $this->lasterror == false ){
							
							$tbi_proc->addMultiVarOperation( $this->data['stmid'],$this->data['opertype'],
														 	 $this->data['operheader'],$linesrr );
						}

					}
					else{ // Single Var
						
						$truevar = is_numeric( $this->data['uservinp'] ) ? false : true;
						$multirow = false;
						
						// WARNING: custom text and additional parameter not supported! Fix this later
						
						if( $truevar ){ // LATER: Fetch VarID
							
							$varid = 0;
							
							$operdata = array( 'vartxt' => $this->data['uservinp'], 'operpin' => '' ); // Display data
						}
						else{
							$varid = '';
							
							$operdata = array( 'vartxt' => '', 'operpin' => $this->data['uservinp'] ); // Display data
						}
						
						$tbi_proc->addSingleVarOperation( $this->data['stmid'],$this->data['opertype'],$this->data['operheader'],
						
															$this->data['uservinp'],$truevar,$varid );
															
					}
					
					$this->lasterror = $tbi_proc->lasterror;
				
					if( $this->lasterror === false ){ // Success
					
						$areturn->successAddVarProcOperation(array(
																	'procid' => $tbi_proc->procid, 'rnum' => $tbi_proc->resnum,
																 
																	'opertype' => $this->data['opertype'],
																	
																	'operheader' => $this->data['operheader'],
																	
																	'multirow' => $multirow,
																	
																	'operdata' => $operdata,
																 
																	'stemid' => $this->data['stmid'],
																	
																	'opmap' => $opmap
																) );
					}
					else{ // Error
						
						echo $this->lasterror;
					}
				}
					
			}
			else if( $mod == 'bindoutvar' ){ // -------------------------------------------------------------- BIND OUTVAR
				
				$tbi_proc->bindOutvar(	$this->data[0], // OutVar
										$this->data[1] // ResNum
									);
				
				$this->lasterror = $tbi_proc->lasterror;
				
				if( $this->lasterror === false ){ // Success
					
					$areturn->successBindOutvar( array(
															'outnum' => $this->data[0],
														
															'resnum' => $this->data[1]
													) );
				}
				else{ // Error
					
					
				}
				
			}
			
		}
	
	}
	
	
	
	
?>
