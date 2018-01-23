<?php

// Programinute - POST Gate - Form Data Handling [ $postgate ]

// Copyright 2015-2016 Amir Hachaichi


	
	class POSTGate {
		
		public $error;
		
		public $prescript;
		
		//public $postrr; // POSTed Data
		
		public $data; // POST
		
		public $message;
		
		public $lasterror = false;
		
		
		
		public function __construct( $postjson ){
			
			global $json;
			
			
			$postrr = json_decode( stripslashes( $postjson ),true );
			
			
			if( isset( $postrr['action'] ) ){
				
				
				$doit = new ActionDo( $postrr[ PRESCRIPTID ] );
				
				
				
				
				if( $postrr['action'] == PRE_NEWITEM ){ // ------------------------------------------------- PRE NEW ITEM
					
					
					$areturn = new ActionReturn( $postrr[ PRESCRIPTID ] );
					
					
					if( $postrr[ PRENEW_TARGET ] == PRENEW_INPUT ){
						
						$areturn->formNewInput( $postrr['type'] );
						
					}
					else if( $postrr[ PRENEW_TARGET ] == PRENEW_OUTPUT ){
						
						$areturn->formNewOutput( $postrr['type'] );
					}
					else if( $postrr[ PRENEW_TARGET ] == PRENEW_OTBRICK ){
						
						$areturn->formNewOutextBrick( $postrr['type'],$postrr[ STEM_ID ] );
					}
					
					
				}
				else if( $postrr['action'] == PS_HEADER_UPDATE ){ // ---------------------------------- PS HEADER UPDATE
					
					$doit->prescriptHeader( $postrr[ PS_HDUP_TARGET ], $postrr[ PS_HDUP_TEXT ] );
					
				}
				else if( $postrr['action'] == CDT_NEW_HEADER ){ // ------------------------------------------- CDT NEW TABLE
					
					
					$doit->data = $postrr[ STEM_ID ];
					
					$doit->cdtHeader('new_header');
					
				}
				else if( $postrr['action'] == CDT_HDR_NEWTOPCOL ){ // ----------------------------- CDT HEADER NEW TOP COL
					
					
					$doit->data = array( $postrr[ CDT_HEADER_ID ], $postrr[ CDT_HEADER_NUM ] );
					
					$doit->cdtHeader( 'new_top_column' );
					
				}
				else if( $postrr['action'] == CDT_HDR_NEWINSCOL ){ // ----------------------------- CDT HEADER NEW INS COL
					
					
					$doit->data = array( $postrr[ CDT_HEADER_ID ], $postrr[ CDT_NWTBLCOL_INS ], $postrr[ CDT_HEADER_NUM ] );
					
					$doit->cdtHeader( 'new_sub_column' );
					
				}
				else if( $postrr['action'] == CDT_HDR_LOADCOL ){ // --------------------------------- CDT HDR LOAD COLUMN
					
					$doit->data = array( $postrr[ CDT_HEADER_ID ], $postrr[ CDT_HDR_COL_LETTER ] );
					
					$doit->cdtHeader( 'load_column' );
					
				}
				else if( $postrr['action'] == CDT_HDR_CLEARCOL ){ // -------------------------------- CDT HDR CLEAR COLUMN
					
					$doit->data = array( $postrr[ CDT_HEADER_ID ], $postrr[ CDT_HDR_COL_LETTER ], $postrr[ CDT_HEADER_NUM ] );
					
					$doit->cdtHeader( 'clear_column' );
					
				}
				else if( $postrr['action'] == CDT_HDR_DELETECOL ){ // -------------------------------- CDT HDR DELETE COLUMN
					
					$doit->data = array( $postrr[ CDT_HEADER_ID ], $postrr[ CDT_HDR_COL_LETTER ], $postrr[ CDT_HEADER_NUM ] );
					
					$doit->cdtHeader( 'delete_column' );
					
				}
				else if( $postrr['action'] == CDT_HDR_ADDCDT_TOPSTK ){ // ------------------------------- CDT HDR TOP COL CDT
					
					$doit->data = array( $postrr[ CDT_HEADER_ID ], // 0
					
											$postrr[ CDT_HDR_COL_LETTER ],$postrr[ CDT_HDCOL_CDT_ANDOR ], // 1 - 2

											$postrr[ CDT_HDCOL_CDT_PFUNC ],$postrr[ CDT_HDCOL_CDT_TARGET ], // 3 - 4
											
											$postrr[ CDT_HDCOL_CDT_LINK ],$postrr[ CDT_HDCOL_CDT_VALUE ], // 5 - 6
											
											$postrr[ CDT_HDCOL_CDT_VPARSE ]	); // 7
					
					$doit->cdtHeader( 'col_ins_top_cdt' );
					
				}
				/*else if( $postrr['action'] == CDT_HDCOL_FIRSTSTK ){ // ----------------------------- CDT HDR FIRST COL STACK
				}
				else if( $postrr['action'] == CDT_HDR_ADDCDT ){ // --------------------------------------- CDT HEADER ADD CDT
					$doit->data = array( $postrr[ CDT_HEADER_ID], 
										 $postrr[ CDT_NWTBLCDT_INS ], $postrr[ CDT_NWTBLCDT_TRG ],
										 $postrr[ CDT_NWTBLCDT_LNK ],$postrr[ CDT_NWTBLCDT_VAL ]);
					$doit->cdtHeader( 'add_cdt' );
				}*/
				else if( $postrr['action'] == CDT_NEW_SWITCH ){ // ---------------------------------------------- CDT NEW SWITCH
					
					
					$doit->data = array( $postrr[ STEM_ID ], $postrr[ CDT_SW_TBLNUM ] );
					
					$doit->cdtSwitch( 'new_switch' );
					
				}
				else if( $postrr['action'] == NEW_OUTPUT_TEXT ){ // -------------------------------------------- NEW OUTPUT TEXT
					
					
					$doit->data = array( $postrr[ STEM_ID ], $postrr[ NEW_OUTTXT_TTL ], $postrr[ NEW_OUTTXT_DSC ] );
					
					$doit->outputText( 'new_output_text' );
					
				}
				else if( $postrr['action'] == NEW_OUTPUT_BLOCK ){ // ------------------------------------------- NEW OUTPUT BLOCK
					
					
					$doit->data = array( $postrr[ STEM_ID ],$postrr[ OUTPUT_TXT_NUM ] );
					
					$doit->outputText( 'new_output_block' );
					
				}
				else if( $postrr['action'] == NEW_OUTPUT_BRICK ){ // ------------------------------------------- NEW OUTPUT BRICK
					
					if( !in_array($postrr[ NEW_OUTBRK_TYPE ],array('text','var')) ){
						
						return false;
					}
					
					if( !in_array($postrr[ NEW_OUTBRK_BR ],array(0,1)) ){
						
						return false;
					}
					
					$doit->data = array( 
											$postrr[ STEM_ID ],	$postrr[ NEW_OUTBRK_TYPE ],
										
											$postrr[ NEW_OUTBRK_BR ],	$postrr[ NEW_OUTBRK_TXT ]
										);
					
					$doit->outputText( 'new_output_brick' );
					
				}
				else if( $postrr['action'] == NEW_INPUT_TEXT ){ // ---------------------------------------------- NEW INPUT TEXT
					
					if( !in_array( $postrr[ NEW_INPTXT_TYPE ],array('text','select') ) ){
						
						return false;
					}
					
					if( !is_numeric( $postrr[ NEW_INPTXT_ROWS ] ) ){
						
						return false;
					}
					
					$doit->data = array( 
											$postrr[ STEM_ID ],	$postrr[ NEW_INPTXT_TYPE ],
										
											$postrr[ NEW_INPTXT_ROWS ],	$postrr[ NEW_INPTXT_TTL ],
										
											$postrr[ NEW_INPTXT_DSC ]
										
											//$postrr[ NEW_INPTXT_ARR ] 
										);
					
					$doit->inputText( 'new_input_text' );
					
				}
				else if( $postrr['action'] == NEW_OUTPUT_CALL ){ // ---------------------------------------------- NEW OUTPUT CALL
					
					
					$doit->data = array( $postrr[ STEM_ID ], $postrr[ NEW_OUTCAL_TYP ], 
					
											$postrr[ NEW_OUTCAL_TRG ], $postrr[ SWITCH_ID ], $postrr[ SWITCH_COLETTER ] );
					
					$doit->outputCall( 'new_outcall' );
					
				}
				else if( $postrr['action'] == OUTCAL_LINK_OVAR ){ // -------------------------------------------------- LINK OVAR
					
					
					$doit->data = array( $postrr[ OUTCAL_VARID ], $postrr[ OUTCAL_LINK_TXT ], $postrr[ OUTCAL_LINK_PARSE ], 
					
										 $postrr[ OUTCAL_OVAR_NUM ] );
					
					$doit->outputCall( 'outvar_link' );
					
				}
				else if( $postrr['action'] == OUTPUT_CALL_RESET ){ // ------------------------------------------ RESET OUTPUT CALL
					
					$doit->data = $postrr[ OUTPUT_CALL_ID ];
					
					$doit->outputCall( 'reset_outcall' );
					
				}
				else if( $postrr['action'] == NEW_VAR_PROC ){ // -------------------------------------------------- VAR PROCESSING
					
					if( !is_numeric( $postrr[ STEM_ID ] ) ){
						
						return false;
					}
					
					$doit->data = array( $postrr[ STEM_ID ], $postrr[ SWITCH_ID ], $postrr[ SWITCH_COLETTER ] );
					
					$doit->varProcessing( 'new_varproc' );
					
				}
				else if( $postrr['action'] == VPROC_ADD_INVAR ){ // --------------------------------------------------- ADD IN-VAR
					
					if( !is_numeric( $postrr[ STEM_ID ] ) ){
						
						return false;
					}
					
					$doit->data = array( $postrr[ STEM_ID ],$postrr[ VPROC_INVAR_TXT ] );
					
					$doit->varProcessing( 'varproc_addinvar' );
					
				}
				else if( $postrr['action'] == VPROC_ADD_OUTVAR ){ // ------------------------------------------------- ADD OUT-VAR
					
					if( !is_numeric( $postrr[ STEM_ID ] ) ){
						
						return false;
					}
					
					$doit->data = array( $postrr[ STEM_ID ], $postrr[ MONOINPUT_DATA ] );
					
					$doit->varProcessing( 'varproc_addoutvar' );
					
				}
				else if( $postrr['action'] == VPROC_ADD_OPER ){ // ------------------------------------------------- ADD OPERATION
					
					if( !is_numeric( $postrr[ STEM_ID ] ) ){
						
						return false;
					}
					
					if( $postrr[ VPROC_OPER_TYPE ] == 'count' ){
						
						if( in_array( $postrr[ VPROC_OPER_HEADER ],array('allchar') ) ){
							
							$doit->data = array(	'opertype' => $postrr[ VPROC_OPER_TYPE ],
													'operheader' => $postrr[ VPROC_OPER_HEADER ],
													'stmid' => $postrr[ STEM_ID ],
													'uservinp' => $postrr[ MONOINPUT_DATA ]		);
						}
					}
					else if( $postrr[ VPROC_OPER_TYPE ] == 'math' ){
						
						if( in_array( $postrr[ VPROC_OPER_HEADER ],
								array('log10','lnep','sqroot','sinus','cosinus','tan','exp','absolute') ) ){
							// One variable
							
							$doit->data = array(	'opertype' => $postrr[ VPROC_OPER_TYPE ],
													'operheader' => $postrr[ VPROC_OPER_HEADER ],
													'stmid' => $postrr[ STEM_ID ],
													'uservinp' => $postrr[ VPROC_OPER_VAR ]		);
						}
						else if( in_array( $postrr[ VPROC_OPER_HEADER ],array('plus','minus','multiply','divide','power','root') ) ){
							
							$doit->data = array(	'opertype' => $postrr[ VPROC_OPER_TYPE ],
													'operheader' => $postrr[ VPROC_OPER_HEADER ],
													'stmid' => $postrr[ STEM_ID ],
													'uservinp' => array( $postrr[ VPROC_OPER_XVAR ],
																		 $postrr[ VPROC_OPER_YVAR ] ));
							
							
						}
					}
					
					$doit->varProcessing( 'varproc_addoper' );
					
				}
				else if( $postrr['action'] == VPROC_BIND_OUTVAR ){ // ---------------------------------------------- BIND OUTVAR
					
					if( !is_numeric($postrr[ VPROC_OUTVAR ]) || !is_numeric($postrr[ MONOINPUT_DATA ]) ){
						
						return false;
					}
					
					$doit->data = array( 
											//$postrr[ VPROC_ID ], // Not needed 
											$postrr[ VPROC_OUTVAR ],	$postrr[ MONOINPUT_DATA ]
										);
					
					$doit->varProcessing( 'bindoutvar' );
				}
				else{
					
					$this->error = "Unknown action";
					$this->lasterror = "action";
				}
				
				$this->lasterror = $doit->lasterror;
					
				$this->message = $doit->message;
				
			}
			else{
				
				$this->error = "No form data";
				echo "Nothing";
			}
			
			
		}
		
		public function checkInput( $inprr ){ // ----------------------------------------------------- CHECK INPUT
			
			
		}
	
	}
	
	
	
?>
