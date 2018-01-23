<?php

// Programinute - Action Return - Form Data Handling [$areturn]

// Copyright 2015-2016 Amir Hachaichi

/*
#--Includes
#	
#	dish.php
#
#
#
#
#--Init
#
#	postgate
#
#	action_do
#
#
#
*/	
	


	class ActionReturn {
		
		
		
		public $prescript;
		
		public $message;
		
		
		public $lasterror = false;
		
		public $error;
		
		
		
		public function __construct( $psid ){
			
			$this->prescript = $psid;
			
		}
		
		
		/*public function formNewInput( $type ){ // --------------------------------------------------- FORM NEW INPUT
			global $json;
			if( $type == 'text' ){
				$newinner = "<div id=\"newinput_form_0\" class=\"pad10\">"
								. "<div id=\"newinput_typed\" " // ----------------------- TYPED
										. "data-stack=\"newinput\" "
										. "data-multikey=\"" . urlencode(json_encode(array(
															array( 'ky' =>NEW_INPTXT_TYPE, 'val' => 'text' ),	
															array( 'ky' =>NEW_INPTXT_ROWS, 'val' => 1 )
											))) . "\" "
										. "data-switch-hide=\"newinput_form_0\" "
										. "data-switch-show=\"newinput_form_1\" "
										. "style=\"padding:10px 20px;\" "
										. "onclick=\"attReader(this.id);\">"
									. "Typed</div>"
								. "<div id\"newinput_select\" style=\"padding:10px 20px;\">" // ---------------------- SELECT
									. "Select</div>"
						  . "</div>"
						  . "<div id=\"newinput_form_1\" class=\"pad10\" style=\"display:none;\">"
								. "<div style=\"padding:10px;\">New text input (typed)</div>"
								. "<div style=\"padding:10px;\">"
									. "<input type=\"text\" id=\"newinput_title\" " // ------------------ TITLE
										. "data-stack=\"newinput\" "
										. "data-key=\"" . NEW_INPTXT_TTL . "\" "
										. "data-getfrom=\"value\" "
									. "placeholder=\"Title\" onblur=\"attReader(this.id);\" />"
								. "</div>"
								. "<div style=\"padding:10px;\">"
									. "<input type=\"text\" id=\"newinput_description\" " // ------------ DESCRIPTION
										. "data-stack=\"newinput\" "
										. "data-key=\"" . NEW_INPTXT_DSC . "\" "
										. "data-getfrom=\"value\" "
									. "placeholder=\"Description (for yourself)\" onblur=\"attReader(this.id);\" />"
								. "</div>"
								. "<div id=\"newinput_next_0\" class=\"p10\" " // ---------------------------- NEXT
									. "data-switch-hide=\"newinput_form_1\" "
									. "data-switch-show=\"newinput_form_2\" "
									. "onclick=\"attReader(this.id);\">"
								. "Next</div>"
						  . "</div>"
						  . "<div id=\"newinput_form_2\" class=\"pad10\" style=\"display:none;\">"
								. "<div style=\"padding:10px;\">"
									. "<input type=\"button\" id=\"newinput_text_typed_submit\" " // ------------- CREATE
										. "data-stack=\"newinput\" "
										. "data-flush=\"value\" "
									. "value=\"Create\" onclick=\"attReader(this.id)\" /> "
								. "</div>"
								//. "<span onclick=\"switchDiv(this.parentNode.id,'newinput_form_0');\">Back</span>"
						  . "</div>";
			}
			else{
			}
			$json->setContent( "input_stem",$newinner ); // $codedisplay->stemLine()
			echo $json->finalOutput();
		}*/
		/*public function formNewOutput( $type ){ // --------------------------------------------------------- FORM NEW OUTPUT
			global $json;
			if( $type == 'text' ){
				$newinner = "<div id=\"newoutput_form_0\" class=\"pad10\">"
								. "<div style=\"padding:10px;\">New text output</div>". "<div style=\"padding:10px;\">"
									. "<input type=\"text\" id=\"newoutput_title\" " // ------------------ TITLE
										. "data-stack=\"newoutput\" ". "data-key=\"" . NEW_OUTTXT_TTL . "\" ". "data-getfrom=\"value\" "
									. "placeholder=\"Title\" onblur=\"attReader(this.id);\" />"
								. "</div>"
								. "<div style=\"padding:10px;\">"
									. "<input type=\"text\" id=\"newoutput_description\" " // ------------ DESCRIPTION
										. "data-stack=\"newoutput\" ". "data-key=\"" . NEW_OUTTXT_DSC . "\" "
									. "data-getfrom=\"value\" "
									. "placeholder=\"Description (for yourself)\" onblur=\"attReader(this.id);\" />"
								. "</div>"
								. "<div id=\"newoutput_next_0\" class=\"p10\" " // ---------------------------- NEXT
									. "data-switch-hide=\"newoutput_form_0\" ". "data-switch-show=\"newoutput_form_1\" "
									. "onclick=\"attReader(this.id);\">"
								. "Next</div>"
						  . "</div>"
						  . "<div id=\"newoutput_form_1\" class=\"pad10\" style=\"display:none;\">"
								. "<div style=\"padding:10px;\">"
									. "<input type=\"button\" id=\"newoutput_text_submit\" " // ------------- CREATE
										. "data-stack=\"newoutput\" ". "data-flush=\"value\" "
									. "value=\"Create\" onclick=\"attReader(this.id)\" /> ". "</div>"
								//. "<span onclick=\"switchDiv(this.parentNode.id,'newinput_form_0');\">Back</span>"
						  . "</div>";
			}
			$json->setContent( "output_stem",$newinner );	echo $json->finalOutput();
		}*/
		
		
		public function formNewOutextBrick( $type,$stmid ){ // ------------------------------------------ NEW OUTPUT TEXT BRICK
			
			global $json;
			
			
			if( $type == 'text' ){ // ----------------------------------------------------------- Text
				
				$newinner = "<div id=\"newotbrick_" . $stmid . "_form\" class=\"pad10\">"
				
								. "<div style=\"padding:10px;\">New text brick</div>"
								
								. "<div style=\"padding:10px;\">"
								
									. "<select id=\"newotbrick_" . $stmid . "_newline\" " // --------------------- New line
									
									. "data-stack=\"newotbrick" . $stmid . "\" "
										
									. "data-key=\"" . NEW_OUTBRK_BR . "\" "
									
									. "data-getfrom=\"value\" "
									
									."onchange=\"attReader(this.id);\">"
									
										. "<option value=\"0\">Same line</option>"
										
										. "<option value=\"1\">New line</option>"
									
									. "</select>"
								
								. "</div>"
								
								. "<div style=\"padding:10px;\">"
								
									. "<input type=\"text\" id=\"newotbrick_" . $stmid . "_txt\" " // -------------- Content
									
										. "style=\"width:200px;\" "
										
										. "data-stack=\"newotbrick" . $stmid . "\" "
										
										. "data-key=\"" . NEW_OUTBRK_TXT . "\" "
										
										. "data-getfrom=\"value\" "
									
									. "placeholder=\"Content\" onblur=\"attReader(this.id);\" />"
									
								. "</div>"
								
								. "<div style=\"padding:10px;\">"
								
									. "<input type=\"button\" id=\"newotbrick_" . $stmid . "_submit\" " // ------------- CREATE
									
										. "data-stack=\"newotbrick" . $stmid . "\" "
										
										. "data-flush=\"value\" "
									
									. "value=\"Add\" onclick=\"attReader(this.id)\" /> "
								
								. "</div>"
				
						  . "</div>";
				
			}
			else if( $type == 'var' ){ // ------------------------------------------------------- Var
				
				$newinner = "<div id=\"newotbrick_" . $stmid . "_form\" class=\"pad10\">"
				
								. "<div style=\"padding:10px;\">New variable brick</div>"
								
								. "<div style=\"padding:10px;\">"
								
									. "<select id=\"newotbrick_" . $stmid . "_newline\" " // --------------------- New line
									
									. "data-stack=\"newotbrick" . $stmid . "\" "
										
									. "data-key=\"" . NEW_OUTBRK_BR . "\" "
									
									. "data-getfrom=\"value\" "
									
									."onchange=\"attReader(this.id);\">"
									
										. "<option value=\"0\">Same line</option>"
										
										. "<option value=\"1\">New line</option>"
									
									. "</select>"
								
								. "</div>"
								
								
								. "<div style=\"padding:10px;\">"
								
									. "<input type=\"button\" id=\"newotbrick_" . $stmid . "_submit\" " // ------------- CREATE
									
										. "data-stack=\"newotbrick" . $stmid . "\" "
										
										. "data-flush=\"value\" "
									
									. "value=\"Add\" onclick=\"attReader(this.id)\" /> "
								
								. "</div>"
				
						  . "</div>";
				
			}
			
			
			$json->setContent( "output_text_brick_" . $stmid . "_stem",$newinner );
			
			echo $json->finalOutput();
			
		}
		
		
		// ######################################################################################################### SUCCESS
		
		
		public function prescriptHeaderUpdate( $success, $target ){
			
			global $json;
			
			if( $target == 'title' ){
				
				$json->setValue( 'pshdup_title_submit', 'Update title' );
				$json->makeEnabled( 'pshdup_title_submit' );
				$tartxt = "Title";
			}
			else if( $target == 'instr' ){
				
				$json->setValue( 'pshdup_instr_submit', 'Update instructions' );
				$json->makeEnabled( 'pshdup_instr_submit' );
				$tartxt = "Instructions";
			}
			else if( $target == 'author' ){
				
				$json->setValue( 'pshdup_author_submit', 'Update author' );
				$json->makeEnabled( 'pshdup_author_submit' );
				$tartxt = "Author";
			}
			
			if( $success ){
				
				$json->setContent( 'pshdupfeedback', $tartxt . " successfully updated" );
			}
			else{
				
				$json->setContent( 'pshdupfeedback', "Error: Could not update " . $tartxt );
			}
			
			echo $json->finalOutput();
		}
		
		
		public function successInputText( $ddata ){ // ----------------------------------------------------- Input text
			
			global $json;
			
			$code_display = new CodeDisplayer( $this->prescript );
			
			$code_display->stemid['input'] = $ddata['stmid'];
			
			$code_display->inputText( array(
			
											'title' => $ddata['title'],
											
											'vnum' => $ddata['vnum']
											
											),false );
			
			$json->newElement( 'DIV','input_sec_main',array( 
																'class' => 'input_text_wrp'
																
															),$code_display->html );
			
			$json->setValue( 'newinput_text_typed_title','' );
			$json->setValue( 'newinput_text_typed_submit','Add' );
			$json->makeEnabled( 'newinput_text_typed_submit' );
			
			$json->setDisplay( 'newinput_text_typed_form','none' );
			$json->setDisplay( 'newinput_select_cont_0','block' );
			
			echo $json->finalOutput();
		}
		
		
		public function successOutputText( $ddata ){ // ----------------------------------------------------- SUCCESS INPUT TEXT
			
			global $json;
			
			$code_display = new CodeDisplayer( $this->prescript );
			
			$code_display->stemid['output'] = $ddata['stmid'];
			
			// ---------------------------------------------------------------------- START
			$code_display->startOutputText( array(
													'otnum' => $ddata['otnum'],
													
													'title' => $ddata['title']
													
												),
													false );
			
			
			$code_display->startOutputBlock( $ddata['blockid'],true );
			
			$code_display->outputTextBrick( array(
													'target' => 'stm',
													
													'bid' => $ddata['brickstmid']
													
													) );
			
			$code_display->endOutputBlock();
			
			$code_display->stemLine( $ddata['blockstmid'],false,true,'output_block' );
			
			// ----------------------------------------------------------------------- END
			
			$json->newElement( 'DIV','output_sec_main',array(
																'class' => 'output_text_wrp'
															
															),$code_display->html );
			
			$json->setValue( 'newoutput_text_title','' );
			$json->setValue( 'newoutput_text_submit','Add' );
			$json->makeEnabled( 'newoutput_text_submit' );
			
			$json->setDisplay( 'newoutput_text_form','none' );
			$json->setDisplay( 'newoutput_select_cont_0','block' );
			
			echo $json->finalOutput();
		}
		
		
		public function successOutputBlock( $ddata ){ // ----------------------------------------------------- SUCCESS OUTPUT BLOCK
			
			global $json;
			
			$code_display = new CodeDisplayer( $this->prescript );
			
			
			$code_display->startOutputBlock( $ddata['blockid'],false );
			
			$code_display->outputTextBrick( array(	'target' => 'stm',
													
													'bid' => $ddata['brickstmid']
																					) );
			
			$json->newElement( 'DIV', 'output_text_' . $ddata['otnum'] . '_main', array(
																						 'class' => 'output_text_block_wrp'
																					),								
								$code_display->html );
			
			$json->setDisplay( 'output_text_' . $ddata['stemid'] . '_action_proc', 'none' );
			$json->setDisplay( 'output_text_' . $ddata['otnum'] . '_block_addnew', 'block' );
			$json->setDisplay( 'output_text_' . $ddata['otnum'] . '_block_stem', 'none' );
					
			echo $json->finalOutput();
		}
		
		
		public function successOutextBrick( $ddata ){ // ------------------------------------------------ OUTPUT TEXT BRICK
			
			global $json;
			
			$code_display = new CodeDisplayer( $this->prescript );
			
			if( !empty( $ddata['newline'] ) ){
				
				$json->addLineBreak( 'output_text_block_' . $ddata['blockid'] . '_main' );
			}
			
			if( $ddata['type'] == 'text' ){
				
				$json->newElement( 'SPAN','output_text_block_' . $ddata['blockid'] . '_main',
				
									array(
											'class' => ''
											
											//'contenteditable' => 'true'
															
											),
																						
									$ddata['txt'] );
									
				$json->setValue( 'newotbrick_' . $ddata['stemid'] . '_txt', '' );
				
				$btype = 'txt';
			}
			else if( $ddata['type'] == 'var' ){
				
				$json->newElement( 'SPAN','output_text_block_' . $ddata['blockid'] . '_main',
				
									array(
											'class' => 'output_text_var'
															
											),
																						
									'ov' . $ddata['vnum'] );
				$btype = 'var';
			}
			
			$json->setValue( 'newotbrick_' . $ddata['stemid'] . '_' . $btype . '_newline', 0 );
			
			$json->setValue( 'newotbrick_' . $ddata['stemid'] . '_' . $btype . '_submit', 'Add' );
			$json->makeEnabled( 'newotbrick_' . $ddata['stemid'] . '_' . $btype . '_submit' );
			
			$json->setDisplay( 'newoutput_brick_' . $ddata['stemid'] . '_' . $btype . '_cont', 'none' );
			$json->setDisplay( 'newoutput_brick_select_' . $ddata['stemid'] . '_cont_1', 'block' );
			$json->setDisplay( 'output_text_brick_' . $ddata['stemid'] . '_stem', 'none' );
			
			echo $json->finalOutput();
		}
		
		
		public function successFlowProcessing( $ddata ){ // ----------------------------------------------------- PROCESSING
			
			global $json;
			
			$code_display = new CodeDisplayer( $this->prescript );
			
			if( $ddata['switchid'] == false ){
				
				$inside = 'flow_sec_main';
				$flowid = 'main';
			}
			else{
					$inside = 'cdt_switch_' . $ddata['switchid'] . '_hcol_' . $ddata['coletter'] . '_body';
					$flowid = $ddata['switchid'] . $ddata['coletter'];
			}
			
			$code_display->startVarProc( $ddata['procid'],false );
			
			$code_display->varProcInStem( $ddata['procid'],$ddata['invstem'] );
			
			$code_display->varProcOutStem( $ddata['outvstem'] );
			
			
			$json->newElement( 'DIV', $inside, array(
																'class' => 'flow_step_wrp'		
														),									
								$code_display->html );
			
			
			$json->setDisplay( 'newflow_'.$flowid.'_select_loading','none' );
			$json->setDisplay( 'newflow_'.$flowid.'_select_cont_0','block' );
			
			
			// Processing section
			
			$disproc = new ProcessingDisplayer( $this->prescript );
			
			$disproc->htmlProcStart( $ddata['procid'],false );
				
				$disproc->htmlStartOperCont( $ddata['procid'] );
				
				$disproc->htmlOperStem( $ddata['operstem'] );
				
				$disproc->htmlEndOperCont( $ddata['procid'] );
				
			$disproc->htmlProcEnd( false );
			
			
			$json->newElement( 'DIV','proc_sec_main',array(
																'class' => 'proc_wrp'
															
														),
																						
								$disproc->html );
			
			
			echo $json->finalOutput();
			
		}
		
		
		public function successVarProcNewInvar( $ddata ){ // -------------------------------------------- SUCCESS ADD INVAR
			
			global $json;
			
			$code_display = new CodeDisplayer( $this->prescript );
			
			// Flow Sec
			
			$json->setValue( 'flowproc_instem_' . $ddata['stemid'] . '_newinvar_input','' );
			
			$json->setValue( 'flowproc_instem_' . $ddata['stemid'] . '_newinvar_submit','Add' );
			$json->makeEnabled( 'flowproc_instem_' . $ddata['stemid'] . '_newinvar_submit' );
			
			$json->setDisplay( 'flowproc_instem_' . $ddata['stemid'] . '_newinvar_form','none' );
			$json->setDisplay( 'flowproc_instem_' . $ddata['stemid'] . '_newinvar_start','block' );
			
			$json->newElement( 'SPAN','flow_proc_' . $ddata['procid'] . '_invar_cont',array(
			
																'class' => 'proc_invar'
														),									
								$ddata['vtxt'] );
			
			// Proc Sec
			
			$json->newElement( 'SPAN','procsec_' . $ddata['procid'] . '_invar_cont',array(
			
																'style' => 'margin-left:5px;'
														),									
								$ddata['vtxt'] );
			
			echo $json->finalOutput();
		}
		
		
		public function successAddVarProcOperation( $ddata ){ // --------------------------------- SUCCESS ADD NEW OPERATION
			
			global $json;
			
			$disproc = new ProcessingDisplayer( $this->prescript );
			
			if( $ddata['multirow'] ){ // Multi Row
				
				$disproc->multiRowOperation( $ddata['rnum'],$ddata['opertype'],$ddata['operheader'],

											 $ddata['operdata'] );
				
			}
			else{ // Single Row
				
				$disproc->singleRowOperation( $ddata['rnum'], $ddata['opertype'], $ddata['operheader'],
				
												$ddata['operdata']['vartxt'], $ddata['operdata']['operpin'] );
			}
			
			$json->newElement( 'DIV','procsec_' . $ddata['procid'] . '_oper_cont',array(
			
																	'id' => 'procsec_operation_' . $ddata['procid'] 
																	
																			. 'r' . $ddata['rnum'] . '_wrp',
			
																	'class' => 'procsec_operline'
															
																						),
								$disproc->html );
			
			if( $ddata['opertype'] == 'count' ){
				
				if( $ddata['operheader'] == 'allchar' ){
					 
					$json->setValue( 'flowproc_newop_count_all_' . $ddata['stemid'] . '_input','' );
					$json->setValue( 'flowproc_newop_count_all_' . $ddata['stemid'] . '_submit','OK' );
			
					$json->setDisplay( 'proc_sec_opstem_' . $ddata['stemid'] . '_count','none' );
				}
				
			}
			else if( $ddata['opertype'] == 'math' ){
				
				if( $ddata['opmap']['math'][ $ddata['operheader'] ][ 2 ] == 1 ){ // Single Var
					 
					$json->setValue( 'procsec_opstem_' . $ddata['stemid'] . '_math_onevar_input','' );
					$json->setValue( 'procsec_opstem_' . $ddata['stemid'] . '_math_onevar_submit','OK' );
					$json->makeEnabled( 'procsec_opstem_' . $ddata['stemid'] . '_math_onevar_submit' );
				
					$json->setDisplay( 'proc_sec_opstem_' . $ddata['stemid'] . '_math','none' );
				}
				else if( $ddata['opmap']['math'][ $ddata['operheader'] ][ 2 ] == 2 ){ // Two vars
					
					$json->setValue( 'procsec_opstem_' . $ddata['stemid'] . '_math_twovar_inputx','' );
					$json->setValue( 'procsec_opstem_' . $ddata['stemid'] . '_math_twovar_inputy','' );
					$json->setValue( 'procsec_opstem_' . $ddata['stemid'] . '_math_twovar_submit','OK' );
					$json->makeEnabled( 'procsec_opstem_' . $ddata['stemid'] . '_math_twovar_submit' );
				
					$json->setDisplay( 'proc_sec_opstem_' . $ddata['stemid'] . '_math','none' );
				}
			}
			
			$json->setDisplay( 'proc_sec_opstem_' . $ddata['stemid'] . '_start','block' );
			$json->setDisplay( 'procsec_opinput_' . $ddata['stemid'] . '_wrp','none' );
			$json->setDisplay( 'procsec_opstm_' . $ddata['stemid'] . '_show','block' );
			
			echo $json->finalOutput();
		}
		
		
		public function successAddProcOutVar( $ddata ){ // ---------------------------------------------- SUCCESS ADD OUTVAR
			
			global $json;
			
			$code_display = new CodeDisplayer( $this->prescript );
			
			$code_display->varProcOutVar( $ddata['outnum'], $ddata['ctxt'], false );
			
			$json->setDisplay( 'proc_outvar_stem_' . $ddata['stemid'] . '_form','none' );
			$json->setDisplay( 'proc_outvar_stem_' . $ddata['stemid'] . '_start','block' );
			
			$json->newElement( 'DIV','flow_proc_' . $ddata['procid'] . '_outvar_cont',array(	'class' => 'flow_proc_outvar'
															
																							  ),	$code_display->html 	);
			$json->setValue( 'proc_outvar_stem_' . $ddata['stemid'] . '_txtinput', '' );
			
			$json->setValue( 'proc_outvar_stem_' . $ddata['stemid'] . '_submit', 'Add' );
			$json->makeEnabled( 'proc_outvar_stem_' . $ddata['stemid'] . '_submit' );
			
			$disproc = new ProcessingDisplayer( $this->prescript );
			
			$disproc->htmlOutVar( $ddata['outnum'], 0, $ddata['ctxt'], false, false );
			
			$json->newElement( 'DIV','procsec_' . $ddata['procid'] . '_outvar_cont',array(
			
																	'id' => 'procsec_outvar_' . $ddata['outnum'] . '_wrp',
			
																	'class' => 'procsec_outvar_wrp'
															
																						),		$disproc->html 				);
			echo $json->finalOutput();
		}
		
		
		public function successBindOutvar( $ddata ){ // ------------------------------------------------- SUCCESS BIND OUTVAR
			
			global $json;
			
			$disproc = new ProcessingDisplayer( $this->prescript );
			
			$disproc->htmlOutVar( $ddata['outnum'],$ddata['resnum'],null,false,true );
			
			$json->setContent( 'procsec_outvar_' . $ddata['outnum'] . '_link',
			
								$disproc->html );
			
			echo $json->finalOutput();
			
		}
		
		
		public function successNewOutputCall( $ddata ){ // ---------------------------------------------- SUCCESS OUTPUT CALL
			
			global $json;
			
			$code_display = new CodeDisplayer( $this->prescript );
			
			
			if( $ddata['switchid'] == false ){
				
				$inside = 'flow_sec_main';
				$flowid = 'main';
			}
			else{
					$inside = 'cdt_switch_' . $ddata['switchid'] . '_hcol_' . $ddata['coletter'] . '_body';
					$flowid = $ddata['switchid'] . $ddata['coletter'];
			}
			
			$json->setValue( 'newflow_'.$flowid.'_outcall_form_onum','' );
			$json->setValue( 'newflow_'.$flowid.'_outcall_form_submit','Add' );
			
			$json->setDisplay( 'newflow_'.$flowid.'_select_ocal_1','none' );
			$json->setDisplay( 'newflow_'.$flowid.'_select_cont_0','block' );
			
			$code_display->retro = $ddata['outputnum'];
			
			foreach( $ddata['ovarr'] as $pos => $ovar ){
				
				$code_display->linkedOvar( array(	'onum' => $ddata['outputnum'],
													
													'ovnum' => $ovar[0],	'srcvar' => '',
													
													'id' => $ovar[1]
												),

												true );
			}
			
			unset( $pos ); unset( $ovar );
			
			
			$code_display->outputCall( $ddata['outcallid'], $ddata['outype'], false );
			
			$json->newElement( 'DIV', $inside, array(
																'class' => 'flow_step_wrp'
															
															),		
								$code_display->html );
			
			
			echo $json->finalOutput();
		}
		
		
		public function successLinkOutputCallVar( $ddata ){ // --------------------------------------- SUCCESS OUTCALL VAR BIND
			
			global $json;
			
			$code_display = new CodeDisplayer( $this->prescript );
			
			$code_display->linkedOvar( array( 	'id' => $ddata['ovid'],
												
												'ovnum' => $ddata['ovnum'],
												
												'vparse' => $ddata['vparse'],
												
												'vtxt' => $ddata['vtxt']
											),
							false );
										
			$code_display->flushBuffer();
			
			$json->setContent( 'outcall_ovar_' . $ddata['ovid'] . '_wrp',
			
								$code_display->html );
			
			echo $json->finalOutput();
		}
		
		
		public function successResetOutputCall( $ocid, $onum, $newovars ){ // ------------------ SUCCESS RESET OUTPUT CALL
			
			global $json;
			
			$code_display = new CodeDisplayer( $this->prescript );
			
			foreach( $newovars as $pos => $ovar ){
				
				$code_display->linkedOvar( array(	'onum' => $onum,
													
													'ovnum' => $ovar[0],	'srcvar' => '',
													
													'id' => $ovar[1]						),true );
			}
			
			unset( $pos ); unset( $ovar );
			
			$code_display->flushBuffer();
			
			$json->setContent( 'outcall_' . $ocid . '_ovar_cont', $code_display->html );
			
			$json->setDisplay( 'flowstep_menu_outcall_' . $ocid . '_processing', 'none' );
			$json->setDisplay( 'flowstep_menu_outcall_' . $ocid . '_start', 'block' );
			
			echo $json->finalOutput();
		}
		
		
		public function successNewCdtHeader( $ddata ){ // --------------------------------------------- SUCCESS NEW CDT HEADER
			
			global $json;
			
			$code_display = new CodeDisplayer( $this->prescript );

			$json->setDisplay( 'newflow_main_select_loading','none' );
			$json->setDisplay( 'newflow_main_select_cont_0','block' );

			$tablehtml = $code_display->cdtHeaderStartTable( $ddata[ 'tableid' ] )

					   	. $code_display->cdtHeaderStartColumn( $ddata[ 'tableid' ],'a' ) 
					   	
							. $code_display->cdtHeaderEndColumn()

					   	. $code_display->cdtHeaderStartColumn( $ddata[ 'tableid' ],'z' ) 
					   	
							. 'Else' .  $code_display->cdtHeaderEndColumn()
						
						. $code_display->cdtHeaderEndTable();

			$code_display->conditionTable( $ddata[ 'tableid' ],$ddata['tablenum'],$tablehtml,false );
			
			
			$json->newElement( 'DIV','flow_sec_main',array(
																'class' => 'flow_step_wrp'
															
															),		
								$code_display->html );
			
			echo $json->finalOutput();
		}
		
		
		public function cdtHeaderAddColumn( $success,$topcol,$tblid,$tblnum ){ //----------------------- CDT HEADER COLUMN EDIT
			
			global $json;
			global $code_display; // Needed in cdt class
			
			$code_display = new CodeDisplayer( $this->prescript );
			$cdtrefresh = new ConditionTable( $this->prescript,$tblid,true );
			
			$json->setContent( 'cdt_hdr_' . $tblid . '_toprow', $cdtrefresh->tbl_disp );
			
			if( $topcol ){
							$json->setValue( 'cdt_table_actions_' . $tblnum . '_newcol_toplevel', 'Top level' );
			
							$json->makeEnabled( 'cdt_table_actions_' . $tblnum . '_newcol_toplevel' );
			}
			else{
					$json->setValue( 'cdt_table_actions_' . $tblnum . '_newcol_inside', '' );
				
					$json->setValue( 'cdt_table_actions_' . $tblnum . '_newcol_submit', 'Add' );
			
					$json->makeEnabled( 'cdt_table_actions_' . $tblnum . '_newcol_submit' );
			}
			
			$json->setDisplay( 'cdt_table_actions_' . $tblnum . '_newcol_cont', 'none' );
			
			$json->setDisplay( 'cdt_table_' . $tblnum . '_actions_0', 'block' );
			
			echo $json->finalOutput();
		}
		
		
		public function reloadCdtHeader( $tblid ){ // ------------------------------------- RELOAD CDT HEADER
			
			global $json;
			global $code_display; // Needed in cdt class
			
			$code_display = new CodeDisplayer( $this->prescript );
			
			$cdtrefresh = new ConditionTable( $this->prescript,$tblid,true );
			
			$json->setContent( 'cdt_hdr_' . $tblid . '_toprow', $cdtrefresh->tbl_disp );
			
			echo $json->finalOutput();
		}
		
		
		public function successCdtHeaderColEdit( $tblid,$letter,$edhtml,$tbhtml,$tbrefresh ){ // -------- CDT HEADER COLUMN EDIT
			
			global $json;
			
			$json->setContent( 'cdt_table_' . $tblid . '_loaded_column', $edhtml );
			
			if( $tbrefresh ){
								$json->setContent( 'cdt_table_' . $tblid . '_col_' . $letter . '_body', $tbhtml );
			}
			
			echo $json->finalOutput();
		}
		
		
		public function successCdtHeaderClearColumn( $tblid,$tblnum,$letter ){ // ------------------------------------ CLEAR COLUMN
			
			global $json;
			
			
			$json->setContent( 'cdt_table_' . $tblid . '_col_' . $letter . '_body', '' );
			
			// PROBLEM: Subcolumns will disappear !!!
			
			
			$json->setContent( 'cdt_table_' . $tblid . '_loaded_column', "Column " . strtoupper($letter) . " cleared" );
			
			$json->setValue( 'cdt_table_actions_' . $tblnum . '_clearcol_colinp', '' );
			
			$json->setValue( 'cdt_table_actions_' . $tblnum . '_clearcol_submit', 'Clear' );
			
			$json->makeEnabled( 'cdt_table_actions_' . $tblnum . '_clearcol_submit' );
			
			$json->setDisplay( 'cdt_table_actions_' . $tblnum . '_clearcol_cont', 'none' );
			
			$json->setDisplay( 'cdt_table_' . $tblnum . '_actions_0', 'block' );

			
			echo $json->finalOutput();
		}
		
		
		public function successCdtHeaderDeleteColumn( $tblid,$tblnum,$letter ){ // ------------------------------------ DELETE COLUMN
			
			global $json;
			global $code_display;
			
			$code_display = new CodeDisplayer( $this->prescript );
			
			$cdtrefresh = new ConditionTable( $this->prescript, $tblid, true );
			
			$json->setContent( 'cdt_hdr_' . $tblid . '_toprow', $cdtrefresh->tbl_disp );
			
			
			$json->setContent( 'cdt_table_' . $tblid . '_loaded_column', "Column " . strtoupper($letter) . " deleted" );
			
			$json->setValue( 'cdt_table_actions_' . $tblnum . '_deletecol_colinp', '' );
			
			$json->setValue( 'cdt_table_actions_' . $tblnum . '_deletecol_submit', 'Delete' );
			
			$json->makeEnabled( 'cdt_table_actions_' . $tblnum . '_deletecol_submit' );
			
			$json->setDisplay( 'cdt_table_actions_' . $tblnum . '_deletecol_cont', 'none' );
			
			$json->setDisplay( 'cdt_table_' . $tblnum . '_actions_0', 'block' );

			
			echo $json->finalOutput();
		}
		
		
		public function cdtHeaderError( $tblid,$tblnum,$action,$txt ){ // ------------------------------------- CDT HEADER ERROR
			
			global $json;
			
			$json->setContent( 'cdt_table_' . $tblid . '_loaded_column', $txt );
			
			if( $action == 'delete_column' ){
				
				$json->setValue( 'cdt_table_actions_' . $tblnum . '_deletecol_colinp', '' );
			
				$json->setValue( 'cdt_table_actions_' . $tblnum . '_deletecol_submit', 'Clear' );
			
				$json->makeEnabled( 'cdt_table_actions_' . $tblnum . '_deletecol_submit' );
			
				$json->setDisplay( 'cdt_table_actions_' . $tblnum . '_deletecol_cont', 'none' );
			
				$json->setDisplay( 'cdt_table_' . $tblnum . '_actions_0', 'block' );
			}
			
			echo $json->finalOutput();
		}
		
		
		public function successNewSwitch( $swid,$innerhtml ){ // --------------------------------------- SUCCESS NEW SWITCH
			
			global $json;
			
			$json->setDisplay( 'newflow_main_select_switch_1', 'none' );
			$json->setValue( 'newflow_main_switch_cdthdr_num', '' );
			$json->setValue( 'newflow_main_switch_submit', 'Add' );
			$json->makeEnabled( 'newflow_main_switch_submit' );
			$json->setDisplay( 'newflow_main_select_cont_0', 'block' );
			
			// HTML is generated by switchorg
			
			$json->newElement( 'DIV', 'flow_sec_main', array(
																'class' => 'cdt_switch_wrp'
															
															),		
								$innerhtml );
			
			echo $json->finalOutput();
		}
		
	}
	
	
?>
