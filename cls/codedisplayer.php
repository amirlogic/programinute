<?php

// Programinute - Code Displayer - [ $code_display ]

// Copyright 2015-2016 Amir Hachaichi


	class CodeDisplayer {
		
		
			public $prescript;
			
			
			public $html; // Final output
			
			public $cdtinneropen = false; // CDT Switch Inner WRP opened
			
			private $buffer;
			
			public $stemid = array();
			
			public $retro = false;
			
			
			public $curoutext; // Current output text number
			
			public $curoutblock; // Current output block ID
			
			public $curswitch = false; // Current cdt switch
			
			public $curswcol; // Current cdt switch column
			
			
			public $error;
			
			public $test;
			
			
			public function __construct( $psid ){
				
				$this->prescript = $psid;
				
				$this->html = "";
				
				$this->buffer = "";
				
			}
			
			
			public function flushBuffer(){ // ------------------------------------------------------------------ FLUSH RESET BUFFER
				
				$this->html .= $this->buffer;
				
				$this->buffer = "";
				
				$this->retro = false;
			}
			
			
			public function resetAll(){ // ---------------------------------------------------------------------------- RESET
				
				$this->html = "";
				
				$this->buffer = "";
				
			}
			
			
			public function addAttribute( $att,$content ){ // ---------------------------------------------------- ADD ATTRIBUTE
				
				$toadd = " " . $att . "=\"" . $content . "\"";
				
				return $toadd;
			}
			
			
			public function setAttributes( $attrr ){ // -------------------------------------------------------- SET ATTRIBUTES
				
				$attstr = '';
				
				foreach( $attrr as $att )
				{
					$attstr .= " " . $att[0] . "=\"" . $att[1] . "\"";
				}
				
				return $attstr;
			}
			
			
			public function printElement( $tag,$txt,$attrr ){ // --------------------------------------------------- PRINT ELEMENT
			
				if( $tag == 'div' ){
				
					$newcont = "<div" . $this->setAttributes( $attrr ) . ">" . $txt . "</div>";
				}
				else if( $tag == 'span' ){
				
					$newcont = "<span" . $this->setAttributes( $attrr ) . ">" . $txt . "</span>";
				}
				else if( $tag == 'input' ){
				
					$newcont = "<input" . $this->setAttributes( $attrr ) . " />";
				}
				else if( $tag == 'option' ){
				
					$newcont = "<option" . $this->setAttributes( $attrr ) . ">" . $txt . "</option>";
				}
			
				return $newcont;
			}
			
			
			public function startInputSection(){ // ------------------------------------------------------------ START INPUT SEC
				
				
				$this->html .= "<div class=\"sec_out_wrp\"><div id=\"input_sec_left\">"
				
							 . "<div class=\"sec_title\"><h1>Inputs</h1></div>"
							 
							 . "<div class=\"secintro\">The starting point</div></div>"
							 
							 . "<div id=\"input_sec_right\"><div id=\"input_sec_main\">";
				
			}
			
			
			public function inputText( $dbdata,$wrap ){ // ------------------------------------------------------ INPUT TEXT
				
				if( $wrap ){
					
					$this->html .= "<div class=\"input_text_wrp\">";
				}
				
					$this->html .= "<div class=\"input_text_frame\" contenteditable=\"true\">"
								
									. $dbdata['title']
								
								 . "</div>"
								
								 . "<div class=\"varbox\" style=\"display:inline-block;\">in" . $dbdata['vnum'] . "</div>";
								
				if( $wrap ){
					
					$this->html .= "</div>";
				}
							 
			}
			
			
			public function newInputInner(){ // ----------------------------###----------------------- NEW INPUT INNER ( RETURNS )
				
				$inner = "<div id=\"newinput_select_cont_0\" style=\"padding-left:10px;\">"
							
							. $this->printElement( 'div', 'New input', array( 
							
														[ 'id','newinput_select_start' ], [ 'class','inline_select' ],
														
														[ 'data-switch-hide','newinput_select_cont_0' ],
														
														[ 'data-switch-show','newinput_text_typed_form' ],
														
														[ 'onclick','attReader(this.id);' ]
													))
					   . "</div>"
					
					   . "<div id=\"newinput_select_cont_1\" style=\"padding-left:10px; display:none;\">"
					   
							. $this->printElement( 'div', 'Text', array( 
							
														[ 'id','newinput_select_text' ], [ 'class','inline_select' ],
														
														//[ 'data-stack','newinput' ],
														
														[ 'data-switch-hide','newinput_select_cont_1' ],
														
														[ 'data-switch-show','newinput_text_select_cont' ],
														
														[ 'onclick','attReader(this.id);' ]
													) )
					   
							. $this->printElement( 'div', 'File', array( 
							
														[ 'id','newinput_select_file' ], [ 'class','inline_select' ],
														
														[ 'style','color:#CCC;' ],
														
													))
							
							. $this->printElement( 'div', 'Go back',array( 
							
														[ 'id','newinput_select_goback' ], [ 'class','inline_select' ],
														
														[ 'data-switch-hide','newinput_select_cont_1' ],
														
														[ 'data-switch-show','newinput_select_cont_0' ],
														
														[ 'onclick','attReader(this.id);' ]
													))
						
					   . "</div>"
					   
					   . "<div id=\"newinput_text_select_cont\" style=\"padding-left:10px; display:none;\">"
					   
							. $this->printElement( 'div', 'Typed text', array( 
							
														[ 'id','newinput_text_select_typed_0' ], [ 'class','inline_select' ],
														
														[ 'data-switch-hide','newinput_text_select_cont' ],
														
														[ 'data-switch-show','newinput_text_typed_form' ],
														
														[ 'onclick','attReader(this.id);' ]
													))
													
							. $this->printElement( 'div', 'Select', array( 
							
														[ 'id','newinput_text_select_select_0' ], [ 'class','inline_select' ],
														
														[ 'style','color:#CCC;' ]
													))
													
							. $this->printElement( 'div', 'Go back', array( 
							
														[ 'id','newinput_text_select_cancel' ], [ 'class','inline_select' ],
														
														[ 'data-switch-hide','newinput_text_select_cont' ],
														
														[ 'data-switch-show','newinput_select_cont_1' ],
														
														[ 'onclick','attReader(this.id);' ]
													))
					   
					   . "</div>"
					   
					   . "<div id=\"newinput_text_typed_form\" style=\"padding-left:10px; display:none;\">"
					   
							. "<div style=\"padding:10px;\">New typed text input</div>"
							
							. "<div style=\"padding:10px;\">"
							
								. $this->printElement( 'input', null,array(
												
														[ 'id','newinput_text_typed_title' ],
														
														[ 'type','text' ],
														
														[ 'style','margin-left:5px;' ],
														
														[ 'placeholder','Title' ], [ 'onclick','attReader(this.id);' ]
													) )
								
								. $this->printElement( 'input', null,array(
												
														[ 'id','newinput_text_typed_submit' ],
														
														[ 'type','button' ],
														
														[ 'style','margin-left:5px;' ],
														
														[ 'data-directpost',urlencode(json_encode(array(
																	
																'senddata' => 	array(
																	
																			PRESCRIPTID => $this->prescript,
																		
																			'action' => NEW_INPUT_TEXT,	
																			
																			STEM_ID => $this->stemid['input'],
																		
																			NEW_INPTXT_TYPE => 'text',
																			
																			NEW_INPTXT_ROWS => 1,
																			
																			NEW_INPTXT_DSC => ''
																			
																			),					     
   																																
																'inputdata' => 	array(
																	
																				[ NEW_INPTXT_TTL, 'newinput_text_typed_title' ]
																			)										
																															))) ],
														
														[ 'value','Add' ], [ 'onclick','attReader(this.id);' ]
													) )
													
								. $this->printElement( 'input', null,array(
												
														[ 'id','newinput_text_typed_cancel' ],
														
														[ 'type','button' ],
														
														[ 'style','margin-left:5px;' ],
														
														[ 'data-switch-hide','newinput_text_typed_form' ],
														
														[ 'data-switch-show','newinput_select_cont_0' ],
														
														[ 'value','Cancel' ], [ 'onclick','attReader(this.id);' ]
													) )
							
							. "</div>"
					   
					   . "</div>";
				
				
				return $inner;
				
			}
			
			
			public function endInputSection(){ // -------------------------------------------------------------- END INPUT SEC
				
				$this->html .= "</div></div>"; // closed: sec_out_wrp - input_sec_right
				
			}
			
			
			/*public function startProcSection(){ // ------------------------------------------------------------ START PROC SEC
				$this->html .= "<h1>Processing</h1><div class=\"proc_sec_wrp\" id=\"processing_sec\">";
			}
			public function endProcSection(){ // ---------------------------------------------------------------- END PROC SEC
				$this->html .= "</div>";
			}*/
			
			
			public function startOutputSection(){ // ------------------------------------------------------------ START OUTPUT
				
				$this->html .= "<div class=\"sec_out_wrp\"><div class=\"sec_header\">"
				
							 . "<div class=\"sec_title\"><h1>Outputs</h1></div>" 
				
							 . "<div class=\"secintro\">Final display, made of blocks and bricks. "
							 
							 . "Output display is controlled from the Flow section</div></div>"
				
							 . "<div id=\"output_sec_main\">";
				
			}
			
			
			public function newOutputInner(){ // -----------------------------###--------------------------- NEW OUTPUT INNER
				
				$inner = "<div id=\"newoutput_select_cont_0\" style=\"padding-left:10px;\">"
				
							. "<div id=\"newoutput_select_start\" class=\"inline_select\" "
							
								. "data-switch-hide=\"newoutput_select_cont_0\" "
								
								. "data-switch-show=\"newoutput_text_form\" "
								
								//. "data-switch-show=\"newoutput_select_cont_1\" "
							
								. "onclick=\"attReader(this.id);\">New Output</div>"
				
					   . "</div>"
					   
					   . "<div id=\"newoutput_select_cont_1\" style=\"display:none; padding-left:10px;\">"
					   
							. "<div id=\"newoutput_select_text\" class=\"inline_select\" "
							
							. "onclick=\"attReader(this.id);\">Text</div>"
							
							. $this->printElement( 'div', 'File',array( 
							
														[ 'id','newoutput_select_file' ], [ 'class','inline_select' ],
														
														[ 'style','color:#CCC;' ],
														
														[ 'onclick','attReader(this.id);' ]
													))
							
							. $this->printElement( 'div', 'Go back',array( 
							
														[ 'id','newoutput_select_goback' ], [ 'class','inline_select' ],
														
														[ 'data-switch-hide','newoutput_select_cont_1' ],
														
														[ 'data-switch-show','newoutput_select_cont_0' ],
														
														[ 'onclick','attReader(this.id);' ]
													))
					   
					   . "</div>"
					   
					   . "<div id=\"newoutput_text_form\" style=\"display:none; padding-left:10px;\">"
					   
							. "<div style=\"padding:10px;\">New text output</div>"
							
							. "<div style=\"padding:10px;\">"
								
								. $this->printElement( 'input', null,array(
												
														[ 'type','text' ],
														
														[ 'id','newoutput_text_title' ],
														
														[ 'style','margin-left:5px; width:250px;' ],
														
														[ 'placeholder','Title (for yourself)' ], 
														
														[ 'onclick','attReader(this.id);' ]
													) )
								
								. $this->printElement( 'input', null,array(
												
														[ 'id','newoutput_text_submit' ],
														
														[ 'type','button' ],
														
														[ 'style','margin-left:5px;' ],
														
														[ 'data-directpost',urlencode(json_encode(array(
																	
																'senddata' => 	array(
																	
																			PRESCRIPTID => $this->prescript,
																		
																			'action' => NEW_OUTPUT_TEXT,	
																			
																			STEM_ID => $this->stemid['output'],
																		
																			NEW_OUTTXT_DSC => ''
																			
																			),					     
   																																
																'inputdata' => 	array(
																	
																				[ NEW_OUTTXT_TTL, 'newoutput_text_title' ]
																			)										
																															))) ],
														
														[ 'value','Add' ], [ 'onclick','attReader(this.id);' ]
													) )
								
								. $this->printElement( 'input', null,array(
												
														[ 'id','newoutput_text_cancel' ],
														
														[ 'type','button' ],
														
														[ 'style','margin-left:5px;' ],
														
														[ 'data-switch-hide','newoutput_text_form' ],
														
														[ 'data-switch-show','newoutput_select_cont_0' ],
														
														[ 'value','Cancel' ], [ 'onclick','attReader(this.id);' ]
													) )
								
							. "</div>"
					   
					   . "</div>";
				
				return $inner;
			}
			
			
			public function endOutputSection(){ // -------------------------------------------------------- END OUTPUT SEC
				
				$this->html .= "</div>"; // closed: sec_out_wrp
				
			}
			
			
			public function startFlowSection(){ // ----------------------------------------------------------- START FLOW SEC
				
				$this->html .= "<div class=\"sec_out_wrp\"><div class=\"sec_header\">"
				
							 . "<div class=\"sec_title\"><h1>Flow</h1></div>"
							
							 . "<div class=\"secintro\">Step by step instructions, from input to output</div></div>"
							
							 . "<div id=\"flow_sec_main\">";
				
			}
			
			
			public function endFlowSection(){ // ---------------------------------------------------------------- END FLOW SEC
				
				$this->html .= "</div>"; // closed:  - sec_out_wrp
				
			}
			
			
			// #========================================================================================================#
			
			
			public function startOutputText( $dbdata,$wrap ){ // --------------------------------------------------- OUTPUT TEXT
				
				if( $wrap ){
					
					$this->html .= "<div class=\"output_text_wrp\">";
				}
				
				$this->html .= "<div class=\"output_text_left\">"
				
								. "<div class=\"output_text_title\">Text Output " . $dbdata['otnum'] . "</div>"
								
								. "<div class=\"output_text_desc\">" . $dbdata['title'] . "</div>"
								
								. $this->printElement( 'div', 'Add block', array( 
							
														[ 'id','output_text_' . $dbdata['otnum'] . '_newblock' ], 
														
														[ 'class','output_text_blockstm' ],
														
														[ 'data-switch-show','output_text_' . $dbdata['otnum'] . '_block_stem' ],
														
														[ 'onclick','attReader(this.id);' ]
													))
							 . "</div>"
							 
							 . "<div class=\"output_text_right\"><div id=\"output_text_" . $dbdata['otnum'] . "_main\">";
							 
				$this->curoutext = $dbdata['otnum'];
				
			}
			
			
			public function startOutputBlock( $blockid,$wrap ){ // ------------------------------------------- OUTPUT BLOCK START
				
				if( $wrap ){
					
					$this->html .= "<div class=\"output_text_block_wrp\">";
				}
				
				$this->html .= 	"<div class=\"output_text_block_left\">"
				
								. "<div id=\"output_text_block_" . $blockid . "_main\" class=\"output_text_block_payload\">";
				
				$this->curoutblock = $blockid;
			}
			
			
			public function outputTextBrick( $dbdata ){ // ------------------------------------------------------ OUTPUT BRICK
				
				
				if( $dbdata['target'] == 'brick' ){
					
					if( $dbdata['newline'] ){ // Newline
					
						$this->html .= "<br />";
					}
				
					if( $dbdata['type'] == 'text' ){
					
						$this->html .= "<span id=\"\" contenteditable=\"true\">"
									
										. $dbdata['content']
										
									 . "</span>";
					}
					else if( $dbdata['type'] == 'var' ){
					
						$this->html .= "<span class=\"output_text_var\">ov" . $dbdata['ovar'] . "</span>";
					}
					
				}
				else if( $dbdata['target'] == 'stm' ){ // -------------------- STEM
					
					$this->html .= "</div>" // closed: output_text_block_payload (main)
					
								 . "<div id=\"output_text_brick_" . $dbdata['bid'] . "_stem\" class=\"output_text_brick_stem\">" 
					
									. $this->newBrickInner( $dbdata['bid'] ) . "</div>"
								 
								 . "</div><div class=\"output_text_block_right\">" // block_left closed
								 
									. "<div id=\"newoutput_brick_select_" . $dbdata['bid'] . "_cont_0\" class=\"output_block_action\" "
							
										. "data-switch-show=\"output_text_brick_" . $dbdata['bid'] . "_stem\" "
				
										. "onclick=\"attReader(this.id);\">Add a new brick</div>";
					
				}
				
				
			}
			
			
			public function endOutputBlock(){ // ------------------------------------------------------------ OUTPUT BLOCK END
				
				$this->html .= "</div></div>"; // closed: right - wrp # current: output_text_right
				
			}
			
			
			public function endOutputText(){ // ------------------------------------------------------------------ OUTPUT TEXT
				
				$this->html .= "</div></div>"; // closed: right - wrp
			}
			
			
			public function newBlockInner( $stmid ){ // --------------------------####------------------------- NEW BLOCK INNER
				
				$inner = "<div id=\"output_text_" . $this->curoutext . "_block_stem\" class=\"output_block_stem_wrp\">"
				
							. $this->printElement( 'span', 'Add new block', array( 
							
														[ 'id','output_text_' . $this->curoutext . '_block_addnew' ], 
														
														[ 'data-rawsend',urlencode(json_encode(array(
																	
																				PRESCRIPTID => $this->prescript,
																		
																				'action' => NEW_OUTPUT_BLOCK,
																		
																				STEM_ID => $stmid,
																		
																				OUTPUT_TXT_NUM => $this->curoutext
																													))) ],
														
														[ 'data-switch-hide','output_text_' . $this->curoutext . '_block_addnew' ],
														
														[ 'data-switch-show','output_text_' . $stmid . '_action_proc' ],
														
														[ 'style','cursor:pointer;' ],	[ 'onclick','attReader(this.id);' ]
													))
													
							. "<span id=\"output_text_" . $stmid . "_action_proc\" "
					  
								. "style=\"display:none;\">Processing...</span>"
							
						. "</div>";
				
				return $inner;
				
			}
			
			
			public function newBrickInner( $stmid ){ // ----------------------------------------------------- NEW BRICK INNER
				
				$inner = "<div id=\"newoutput_brick_select_" . $stmid . "_cont_1\">"
							
							. $this->printElement( 'div', 'Text', array( 
							
														[ 'id','newoutput_brick_select_' . $stmid . '_text' ], 
														
														[ 'class','inline_select' ],
														
														[ 'data-switch-hide','newoutput_brick_select_' . $stmid . '_cont_1' ],
														
														[ 'data-switch-show','newoutput_brick_' . $stmid . '_txt_cont' ],
														
														[ 'onclick','attReader(this.id);' ]
													))
							
							. $this->printElement( 'div', 'Variable', array( 
							
														[ 'id','newoutput_brick_select_' . $stmid . '_var' ], 
														
														[ 'class','inline_select' ],
														
														[ 'data-switch-hide','newoutput_brick_select_' . $stmid . '_cont_1' ],
														
														[ 'data-switch-show','newoutput_brick_' . $stmid . '_var_cont' ],
														
														[ 'onclick','attReader(this.id);' ]
													))
							
							. $this->printElement( 'div', 'Go back', array( 
							
														[ 'id','newoutput_brick_select_' . $stmid . '_cancel' ], 
														
														[ 'class','inline_select' ],
														
														[ 'data-switch-hide','output_text_brick_' . $stmid . '_stem' ],
														
														[ 'onclick','attReader(this.id);' ]
													))
					   . "</div>"
					   
					   . "<div id=\"newoutput_brick_" . $stmid . "_txt_cont\" style=\"display:none;\">"
							
							. "<div style=\"padding:10px;\">"
								
								. "New text brick "
								
								. "<select " . $this->setAttributes( array(
												
																		[ 'id','newotbrick_' . $stmid . '_txt_newline' ]
																		
																		
																		) )
																				. ">"
								
									. "<option value=\"0\">on the same line</option>"
										
									. "<option value=\"1\">on a new line</option>"
								
								. "</select>"
							
							. "</div>"
							
							. "<div style=\"padding:10px;\">"
							
								. $this->printElement( 'input', null, array(
												
														[ 'id','newotbrick_' . $stmid . '_txt' ],
														
														[ 'type','text' ],	[ 'style','width:400px;' ],
														
														[ 'placeholder','Content' ],
														
														[ 'onclick','attReader(this.id);' ]
													) )
													
								. $this->printElement( 'input', null, array(
												
														[ 'id','newotbrick_' . $stmid . '_txt_submit' ],
														
														[ 'type','button' ],	[ 'style','margin-left:10px;' ],
														
														[ 'data-directpost',urlencode(json_encode(array(
																	
																	'senddata' => 	array(
																	
																			PRESCRIPTID => $this->prescript,
																		
																			'action' => NEW_OUTPUT_BRICK,
																		
																			STEM_ID => $stmid,
																			
																			NEW_OUTBRK_BLOCK => $this->curoutblock,

																			NEW_OUTBRK_TYPE => 'text'
																		),					     
   																																
																	'inputdata' => 	array(
																	
																			[ NEW_OUTBRK_BR, 'newotbrick_' . $stmid . '_txt_newline' ],
																								
																			[ NEW_OUTBRK_TXT, 'newotbrick_' . $stmid . '_txt' ]
																	)										
																										))) ],	
														[ 'value','Add' ],
														
														[ 'onclick','attReader(this.id);' ]
													) )
													
								. $this->printElement( 'input', null, array(
												
														[ 'id','newotbrick_' . $stmid . '_txt_cancel' ],
														
														[ 'type','button' ],	[ 'style','margin-left:10px;' ],
														
														[ 'data-switch-hide','newoutput_brick_' . $stmid . '_txt_cont' ],
														
														[ 'data-switch-show','newoutput_brick_select_' . $stmid . '_cont_1' ],
														
														[ 'value','Cancel' ],
														
														[ 'onclick','attReader(this.id);' ]
													) )
							. "</div>"
					   
					   . "</div>"
					   
					   . "<div id=\"newoutput_brick_" . $stmid . "_var_cont\" style=\"display:none;\">"
							
							. "<div style=\"padding:10px;\">"
								
								. "New variable brick "
								
								. "<select " . $this->setAttributes( array(
												
																		[ 'id','newotbrick_' . $stmid . '_var_newline' ]
																		
																		
																		) )
																				. ">"
								
									. "<option value=\"0\">on the same line</option>"
										
									. "<option value=\"1\">on a new line</option>"
								
								. "</select>"
								
								. $this->printElement( 'input', null, array(
												
														[ 'id','newotbrick_' . $stmid . '_var_submit' ],
														
														[ 'type','button' ],	[ 'style','margin-left:10px;' ],
														
														[ 'data-directpost',urlencode(json_encode(array(
																	
																	'senddata' => 	array(
																	
																			PRESCRIPTID => $this->prescript,
																		
																			'action' => NEW_OUTPUT_BRICK,
																		
																			STEM_ID => $stmid,
																			
																			NEW_OUTBRK_BLOCK => $this->curoutblock,

																			NEW_OUTBRK_TYPE => 'var',
																			
																			NEW_OUTBRK_TXT => null
																		),					     
   																																
																	'inputdata' => 	array(
																	
																			[ NEW_OUTBRK_BR, 'newotbrick_' . $stmid . '_var_newline' ]
																	)										
																										))) ],	
														[ 'value','Add' ],
														
														[ 'onclick','attReader(this.id);' ]
													) )
													
								. $this->printElement( 'input', null, array(
												
														[ 'id','newotbrick_' . $stmid . '_var_cancel' ],
														
														[ 'type','button' ],	[ 'style','margin-left:10px;' ],
														
														[ 'data-switch-hide','newoutput_brick_' . $stmid . '_var_cont' ],
														
														[ 'data-switch-show','newoutput_brick_select_' . $stmid . '_cont_1' ],
														
														[ 'value','Cancel' ],
														
														[ 'onclick','attReader(this.id);' ]
													) )
							
							. "</div>"
							
					   . "</div>";
				
				return $inner;
			}
			
			
			public function newFlowInner( $stmid,$swid,$coletter ){ // ---------------------------------------- NEW FLOW INNER
				
				if( $swid === false ){
										$flowid = 'main';	//$inner = '';
				}
				else{
						$flowid = $swid . $coletter;
						
						//$inner = '';
				}
				
				$inner = "<div id=\"newflow_".$flowid."_select_cont_0\" class=\"flow_stem_click\" " // -------- Init
				
							. "data-switch-hide=\"newflow_".$flowid."_select_cont_0\" "
							
							. "data-switch-show=\"newflow_".$flowid."_select_cont_start\" "
							
							. "onclick=\"attReader(this.id);\">New flow"
				
					   . "</div>"
					   
					   . "<div id=\"newflow_".$flowid."_select_cont_start\" style=\"display:none;\">" // ---------------------- Start
					   
							. "<div id=\"newflow_".$flowid."_select_proc_0\" class=\"flow_stem_click\" "
							
								. "data-switch-hide=\"newflow_".$flowid."_select_cont_start\" "
							
								. "data-switch-show=\"newflow_".$flowid."_select_loading\" "
								
								. "data-rawsend=\"" . urlencode(json_encode(array(
																	
																		PRESCRIPTID => $this->prescript,
																		
																		'action' => NEW_VAR_PROC,	STEM_ID => $stmid, 
																		
																		SWITCH_ID => $swid, 	SWITCH_COLETTER => $coletter
																)))
							. "\" "
							
							. "onclick=\"attReader(this.id);\">Processing</div>"
							
							. "<div" // ------------------------------------------------------------- Output Call
							
								. $this->setAttributes( array(
																[ 'id','newflow_'.$flowid.'_select_ocal_0' ],
																
																[ 'class','flow_stem_click' ],
																
																[ 'data-switch-hide','newflow_'.$flowid.'_select_cont_start' ],
																
																[ 'data-switch-show','newflow_'.$flowid.'_select_ocal_1' ],
																
																[ 'onclick','attReader(this.id);' ]
																
															) )
							
							. ">Output call</div>";
				
				
				if( $swid === false ){ // Only if not inside a switch
					
					$inner .= $this->printElement( 'div',"Condition table",array( // --------------------- Cdtable
							
																['id','newflow_'.$flowid.'_select_cdtable_0'], 
																
																['class','flow_stem_click'],
																
																['data-switch-hide','newflow_'.$flowid.'_select_cont_start'],
							
																['data-switch-show','newflow_'.$flowid.'_select_loading'],
																
																['data-rawsend',urlencode(json_encode(array(
																	
																		PRESCRIPTID => $this->prescript,
																		
																		'action' => CDT_NEW_HEADER,	STEM_ID => $stmid, 
																		
																		SWITCH_ID => $swid, SWITCH_COLETTER => $coletter
																															)))],
																	
																['onclick','attReader(this.id);']
													) )
							
							. $this->printElement( 'div',"Condition switch",array( // --------------------- Cdt Switch
							
																['id','newflow_'.$flowid.'_select_cdtswitch_0'], 
																
																['class','flow_stem_click'],
																
																['data-switch-hide','newflow_'.$flowid.'_select_cont_start'],
							
																['data-switch-show','newflow_'.$flowid.'_select_switch_1'],
																
																['onclick','attReader(this.id);']
													) );
				}
				
				
				$inner .= "<div" // ------------------------------------------------------------------ Cancel
					   
								. $this->setAttributes( array(
																[ 'id','newflow_'.$flowid.'_select_cancel' ], 
																
																[ 'class','flow_stem_click' ],
																
																[ 'data-switch-hide','newflow_'.$flowid.'_select_cont_start' ],
																
																[ 'data-switch-show','newflow_'.$flowid.'_select_cont_0' ],
																
																[ 'onclick','attReader(this.id);' ]

																) )
							. ">Cancel</div>"
							
					   . "</div>"
					   
					   . "<div id=\"newflow_".$flowid."_select_switch_1\" style=\"padding:10px 20px; display:none;\">" // --- CDT Switch
					   
							. "New Condition Switch with table "
							
							. $this->printElement( 'input', null,array(
												
														[ 'id','newflow_'.$flowid.'_switch_cdthdr_num' ],
														
														[ 'type','number' ], ['min','1'], ['max','99'],
														
														[ 'style','margin-left:5px;' ],
														
														[ 'placeholder','Num' ], [ 'onclick','attReader(this.id);' ]
													) )
													
							. $this->printElement( 'input', null,array(
												
														[ 'id','newflow_'.$flowid.'_switch_submit' ],
														
														[ 'type','button' ],
														
														[ 'data-directpost',urlencode(json_encode(array(
																	
																'senddata' => 	array(
																	
																			PRESCRIPTID => $this->prescript,
																		
																			'action' => CDT_NEW_SWITCH,	STEM_ID => $stmid, 
																			
																			SWITCH_ID => $swid, SWITCH_COLETTER => $coletter	),					     
   																																
																'inputdata' => 	array(
																	
																			[ CDT_SW_TBLNUM, 'newflow_'.$flowid.'_switch_cdthdr_num' ]
																			
																			)										
																										))) ],
														
														//[ 'style','margin-left:20px;' ],
														
														[ 'value','Add' ], [ 'onclick','attReader(this.id);' ]
													) )
													
							. $this->printElement( 'input', null,array(
												
														[ 'id','newflow_'.$flowid.'_switch_cancel' ], [ 'type','button' ],
														
														[ 'data-switch-hide','newflow_'.$flowid.'_select_switch_1' ],
														
														[ 'data-switch-show','newflow_'.$flowid.'_select_cont_0' ],
														
														[ 'style','margin-left:20px;' ],
														
														[ 'value','Cancel' ], [ 'onclick','attReader(this.id);' ]
													) )
					   
					   . "</div>"
					   
					   . "<div id=\"newflow_".$flowid."_select_ocal_1\" style=\"padding:10px 20px; display:none;\">" // ------ Output call
					   
							. "<span>New output call</span>"
							
								. "<select" // ------------------------------------------------------------- Output Type
								
										. $this->setAttributes( array(
																		[ 'id','newflow_'.$flowid.'_outcall_form_otype' ],
																		
																		[ 'onchange','attReader(this.id);' ]
																		
																		) )
										. ">"
								
									. "<option value=\"text\">Text</option>"
									
									//. "<option value=\"file\">File</option>"
								
								. "</select>"
							
							
								. "<input" // -----------------------------------------------------------  Target ( ONUM )
								
									. $this->setAttributes( array(
																	[ 'type','number' ], ['min','1'], ['max','99'],
																	
																	[ 'id','newflow_'.$flowid.'_outcall_form_onum' ],
																	
																	[ 'placeholder',"Output Num" ],
																	
																	[ 'onblur','attReader(this.id);' ]
																) )
								
										. " />"
								
								. "<input" // -----------------------------------------------------------  Submit
								
									. $this->setAttributes( array(
																	[ 'type','button' ],
																	
																	[ 'id','newflow_'.$flowid.'_outcall_form_submit' ],
																	
																	[ 'data-directpost',urlencode(json_encode(array(
																	
																		'senddata' => 	array(
																	
																			PRESCRIPTID => $this->prescript,
																		
																			'action' => NEW_OUTPUT_CALL,	
																			
																			STEM_ID => $stmid, 
																			
																			SWITCH_ID => $swid, 
																			
																			SWITCH_COLETTER => $coletter	),					     
   																																
																		'inputdata' => 	array(
																	
																			[ NEW_OUTCAL_TYP, 'newflow_'
																			
																									. $flowid . '_outcall_form_otype' ],
																									
																			[ NEW_OUTCAL_TRG, 'newflow_'
																			
																									. $flowid . '_outcall_form_onum' ]
																			
																			)														))) ],
																	
																	[ 'value','Add' ], [ 'onclick','attReader(this.id);' ]
																) )
								
										. " />"
							
								. $this->printElement( 'input', null,array(
												
														[ 'id','newflow_'.$flowid.'_outcall_form_cancel' ], [ 'type','button' ],
														
														[ 'data-switch-hide','newflow_'.$flowid.'_select_ocal_1' ],
														
														[ 'data-switch-show','newflow_'.$flowid.'_select_cont_0' ],
														
														[ 'style','margin-left:20px;' ],
														
														[ 'value','Cancel' ], [ 'onclick','attReader(this.id);' ]
													) )
							
					   . "</div>"
					   
					   
					   
					   . "<div id=\"newflow_".$flowid."_select_loading\" class=\"pad10\" style=\"display:none;\">Loading...</div>";
				
				
				return $inner;
				
			}
			
			
			public function flowStepHeader( $id,$target,$xrr ){ // ----------------------------------------------- FLOW STEP HEADER
				
				$fsh = "<div class=\"flow_step_header\"><div class=\"flow_step_hdr_left\" style=\"";
				
				if( $target == 'outputcall' ){
					
					$fsh .= "color:#FFFFC2;\">Call " . $xrr['outype']. " Output #" . $xrr['outnum'] ;
				}
				else if( $target == 'cdt_switch' ){
					
					$fsh .= "\">Condition Switch Table #" . $xrr['tblnum'] ;
				}
				else{
					
					$fsh .= "background-color:#EEEEEE;\">???";
				}
				
				$fsh .= "</div><div class=\"flow_step_hdr_right\" style=\"";
				
				if( $target == 'outputcall' ){
					
					$fsh .= "background-color:transparent;color:#FFFFC2;\">";
				}
				else if( $target == 'cdt_switch' ){
					
					$fsh .= "color:#555555;\"> ";
				}
				else{
					
					$fsh .= "background-color:#EFEFEF;\">";
				}
				
				
				
				$fsh .= "</div></div>";
				
				return $fsh;
			}
	
	
			public function conditionTable( $tblid,$tnum,$tblhtml,$wrap ){ // ------------------------------------- CDT HEADER
				
				if( $wrap ){ 	$this->html .= "<div class=\"flow_step_wrp\">";	 }		// 	cdt_table_out_wrp
				
				// $this->flowStepHeader( null, 'cdt_header', array( 'tblnum' => $tnum ) )
				
				$this->html .=  "<div class=\"flow_step_left\">"
				
									. "<div class=\"flow_step_title\">Condition Table #" . $tnum . "</div>"
									
									. "<div class=\"flow_step_actions\">" 
									
										. $this->cdTableActionsInner( $tblid,$tnum ) . "</div>"
								
								. "</div>"
				
								. "<div class=\"flow_step_right\">"
								
									. $tblhtml

									. "<div id=\"cdt_table_" . $tblid . "_loaded_column\" "
								
									. "class=\"cdt_table_coledit\">Click on a column letter to edit</div>"
								
								. "</div>";
							
				if( $wrap ){ 	$this->html .= "</div>";	 }
							
			}


			public function cdtHeaderStartTable( $tblid ){ // --------------------------------------- CDT HEADER START TABLE
				
				$rhtml = "<div id=\"cdt_hdr_" . $tblid . "_toprow\" class=\"cdt_table_in_wrp\">";
				
				return $rhtml;
			}			
			

			public function cdtHeaderStartColumn( $tblid,$letter ){ // ----------------------------------- CDT HEADER START COLUMN
				
				$rhtml = "<div class=\"cdt_table_col_wrp\">" 
				
							. $this->printElement( 'div', '<strong>' . strtoupper( $letter ) . '</strong>',
						
												array( 
														[ 'id','cdt_table_' . $tblid . '_col_' . $letter . '_header' ],
														
														[ 'class','cdt_table_col_header' ],
														
														[ 'data-rawsend',urlencode(json_encode(array(
														
																		PRESCRIPTID => $this->prescript,
																		
																		'action' => CDT_HDR_LOADCOL,
																		
																		CDT_HEADER_ID => $tblid,
																		
																		CDT_HDR_COL_LETTER => $letter
																		
																									))) ],
														
														[ 'style','cursor:pointer;' ],
														
														[ 'title','Click to edit' ],
														
														[ 'onclick','attReader(this.id);' ]
													) )
							
							. "<div" . $this->setAttributes(array( 
																	[ 'id','cdt_table_' . $tblid . '_col_' . $letter . '_body' ],
																	
																	[ 'class','cdt_table_col_body' ] 
										)) . ">";
				
				return $rhtml;
			}

			
			public function cdtHeaderCondition( $prefunc,$target,$link,$val,$vprs ){ // ------------------- CDT HEADER CONDITION
				
				if( empty( $prefunc ) ){
					
					$htarget = "<span class=\"inline_var\">" . $target . "</span>";
				}
				else{	
					
					if( $prefunc == 'length' ){
						
						$htarget = "Length of <span class=\"inline_var\">" . $target . "</span>";
					}
				}
				
				if( $vprs == 'var' ){
					
					$htval = "<span class=\"inline_var\">" . $val . "<span>";
				}
				else if( $vprs == 'cst' ){
					
					$htval = "<span class=\"uservalue\">" . $val . "</span>";
				}
				
				$rhtml = "<p>" . $htarget . " " . $link . " " . $htval . "</p>";
				
				return $rhtml;
			}

			
			public function cdtHeaderEndColumn(){ // ----------------------------------------------------- CDT HEADER END COLUMN

				$rhtml = "</div></div>";
				
				return $rhtml;
			}


			public function cdtHeaderEndTable(){ // ------------------------------------------------------- CDT HEADER END TABLE
				
				$rhtml = "</div><!-- TABLE END -->";
				
				return $rhtml;
			}


			public function cdTableActionsInner( $tblid,$tnum ){ // ---------------------------------- CDT Table Actions Inner
				
				$inner = "<div id=\"cdt_table_" . $tnum . "_actions_wrp\" class=\"cdt_table_actions_wrp\">"
														
							. "<div id=\"cdt_table_" . $tnum . "_actions_0\" style=\"padding-left:10px;\">"
							
								. $this->printElement( 'div', 'Add Column',array( 
							
														[ 'id','cdt_table_actions_' . $tnum . '_newcol_start' ],
														
														[ 'class','cdtheader_colop_select' ],
														
														[ 'data-switch-hide','cdt_table_' . $tnum . '_actions_0' ],
														
														[ 'data-switch-show','cdt_table_actions_' . $tnum . '_newcol_cont' ],
														
														[ 'onclick','attReader(this.id);' ]
													) )
							
								. $this->printElement( 'div', 'Clear Column', array( 
							
														[ 'id','cdt_table_actions_' . $tnum . '_clearcol_start' ],
														
														[ 'class','cdtheader_colop_select' ],
														
														[ 'data-switch-hide','cdt_table_' . $tnum . '_actions_0' ],
														
														[ 'data-switch-show','cdt_table_actions_' . $tnum . '_clearcol_cont' ],
														
														[ 'onclick','attReader(this.id);' ]
													) )
								
								. $this->printElement( 'div', 'Delete Column', array( 
							
														[ 'id','cdt_table_actions_' . $tnum . '_deletecol_start' ],
														
														[ 'class','cdtheader_colop_select' ],
														
														[ 'data-switch-hide','cdt_table_' . $tnum . '_actions_0' ],
														
														[ 'data-switch-show','cdt_table_actions_' . $tnum . '_deletecol_cont' ],
														
														[ 'onclick','attReader(this.id);' ]
													) )
								
							. "</div>"
							
							. "<div id=\"cdt_table_actions_" . $tnum . "_newcol_cont\" style=\"display:none;\">"
							
								. $this->printElement( 'div',
								
												$this->printElement( 'input', null, array(
												
														[ 'id','cdt_table_actions_' . $tnum . '_newcol_toplevel' ],
														
														[ 'type','button' ],
														
														[ 'data-directpost',urlencode(json_encode(array(
																	
																	'senddata' => 	array(
																	
																			PRESCRIPTID => $this->prescript,
																		
																			'action' => CDT_HDR_NEWTOPCOL,
																		
																			CDT_HEADER_ID => $tblid,

																			CDT_HEADER_NUM => $tnum			),					     
   																																
																	'inputdata' => 	array()						))) ],
														
														[ 'value','Top level' ], [ 'onclick','attReader(this.id);' ]
													) ),
						
												array( 
														[ 'style','display:inline-block;padding:6px 20px;' ]
													) )
													
								. $this->printElement( 'div',
								
												"Inside column: "
												
												. $this->printElement( 'input', null,array(
												
														[ 'id','cdt_table_actions_' . $tnum . '_newcol_inside' ],
														
														[ 'type','text' ], [ 'size',3 ],	
														
														[ 'placeholder','col' ], [ 'disabled','disabled' ]
													) )
													
												. $this->printElement( 'input', null,array(
												
														[ 'id','cdt_table_actions_' . $tnum . '_newcol_submit' ],
														
														[ 'type','button' ], [ 'value','Add' ],
														
														[ 'data-directpost',urlencode(json_encode(array(
																	
																	'senddata' => 	array(
																	
																			PRESCRIPTID => $this->prescript,
																		
																			'action' => CDT_HDR_NEWINSCOL,
																		
																			CDT_HEADER_ID => $tblid, 
																			
																			CDT_HEADER_NUM => $tnum			),					     
   																																
																	'inputdata' => 	array(
																	
																			[ CDT_NWTBLCOL_INS, 'cdt_table_actions_' 

																								. $tnum . '_newcol_inside' ]
																	)										
																										))) ],	
																										
														[ 'onclick','attReader(this.id);' ], [ 'disabled','disabled' ]
													) )
													
												. $this->printElement( 'input', null,array(
												
														[ 'id','cdt_table_actions_' . $tnum . '_newcol_cancel' ],
														
														[ 'type','button' ],
														
														[ 'data-switch-hide','cdt_table_actions_' . $tnum . '_newcol_cont' ],
														
														[ 'data-switch-show','cdt_table_' . $tnum . '_actions_0' ],
														
														[ 'style','margin-left:20px;' ],
														
														[ 'value','Cancel' ], [ 'onclick','attReader(this.id);' ]
													) ),
						
												array( 
														[ 'style','display:inline-block;padding:6px 20px;' ]
													) )
								
							. "</div>"
							
							. "<div id=\"cdt_table_actions_" . $tnum . "_clearcol_cont\" style=\"padding:10px; display:none;\">"
							
								. "Column to clear: "
								
								. $this->printElement( 'input', null,array(
												
														[ 'id','cdt_table_actions_' . $tnum . '_clearcol_colinp' ],
														
														[ 'type','text' ], [ 'size',3 ],
														
														[ 'placeholder','Col' ],
														
														[ 'onclick','attReader(this.id);' ]
													) )
								
								. $this->printElement( 'input', null,array(
												
														[ 'id','cdt_table_actions_' . $tnum . '_clearcol_submit' ],
														
														[ 'type','button' ],
														
														[ 'data-directpost',urlencode(json_encode(array(
																	
																	'senddata' => 	array(
																	
																			PRESCRIPTID => $this->prescript,
																		
																			'action' => CDT_HDR_CLEARCOL,
																		
																			CDT_HEADER_ID => $tblid,

																			CDT_HEADER_NUM => $tnum ),					     
   																																
																	'inputdata' => 	array(
																	
																			[ CDT_HDR_COL_LETTER, 'cdt_table_actions_' 

																								. $tnum . '_clearcol_colinp' ]
																	)										
																										))) ],	
														
														[ 'value','Clear' ], [ 'onclick','attReader(this.id);' ]
													) )
								
								. $this->printElement( 'input', null,array(
												
														[ 'id','cdt_table_actions_' . $tnum . '_clearcol_cancel' ],
														
														[ 'type','button' ],
														
														[ 'data-switch-hide','cdt_table_actions_' . $tnum . '_clearcol_cont' ],
														
														[ 'data-switch-show','cdt_table_' . $tnum . '_actions_0' ],
														
														[ 'style','margin-left:20px;' ],
														
														[ 'value','Cancel' ], [ 'onclick','attReader(this.id);' ]
													) )
							. "</div>"

							. "<div id=\"cdt_table_actions_" . $tnum . "_deletecol_cont\" style=\"padding:10px; display:none;\">"
							
								. "Column to delete: "
								
								. $this->printElement( 'input', null,array(
												
														[ 'id','cdt_table_actions_' . $tnum . '_deletecol_colinp' ],
														
														[ 'type','text' ], [ 'size',3 ],
														
														[ 'placeholder','Col' ],
														
														[ 'onclick','attReader(this.id);' ]
													) )
								
								. $this->printElement( 'input', null,array(
												
														[ 'id','cdt_table_actions_' . $tnum . '_deletecol_submit' ],
														
														[ 'type','button' ],
														
														[ 'data-directpost',urlencode(json_encode(array(
																	
																	'senddata' => 	array(
																	
																			PRESCRIPTID => $this->prescript,
																		
																			'action' => CDT_HDR_DELETECOL,
																		
																			CDT_HEADER_ID => $tblid,

																			CDT_HEADER_NUM => $tnum ),					     
   																																
																	'inputdata' => 	array(
																	
																			[ CDT_HDR_COL_LETTER, 'cdt_table_actions_' 

																								. $tnum . '_deletecol_colinp' ]
																	)										
																										))) ],	
														
														[ 'value','Delete' ], [ 'onclick','attReader(this.id);' ]
													) )
								
								. $this->printElement( 'input', null,array(
												
														[ 'id','cdt_table_actions_' . $tnum . '_deletecol_cancel' ],
														
														[ 'type','button' ],
														
														[ 'data-switch-hide','cdt_table_actions_' . $tnum . '_deletecol_cont' ],
														
														[ 'data-switch-show','cdt_table_' . $tnum . '_actions_0' ],
														
														[ 'style','margin-left:20px;' ],
														
														[ 'value','Cancel' ], [ 'onclick','attReader(this.id);' ]
													) )
							. "</div>"
							
						. "</div>";
				
				return $inner;
			}
			
			
			public function startCdtSwitch( $swid,$tbnum,$wrap ){ // ------------------------------------- START CDT SWITCH
				
				if( $wrap ){
					
					$this->html .= "<div class=\"cdt_switch_wrp\">";
				}
				
				$this->html .= $this->flowStepHeader( null, 'cdt_switch', array( 'tblnum' => $tbnum ) );
							 
				$this->curswitch = $swid;
			}
			
			
			public function startCondition( $tblnum,$letter,$level ){ // ------------------------------------- START CONDITION
				
				if( $level > 0){
					
					$this->html .= "</div>"; // Close Inner WRP
					
					$this->cdtinneropen = false;
				}
				
				$lpad = 5*$level;
				
				$this->html .= "<div class=\"cdt_switch_hcol_wrp\">" // OUTER WRP [ Border Dashed ]
				
								. "<div class=\"cdt_switch_hcol_inner\" >" // INNER WRP [ Border thick ]
								
								. "<div class=\"cdt_switch_hcol_letter\">"
								
								. "<span class=\"cdt_switch_hcol_letin\">" . $tblnum . " <strong>" 
								
									. strtoupper($letter) . "</strong></span></div>"
								
								. "<div id=\"cdt_switch_" . $this->curswitch . "_hcol_" . $letter . "_body\">";
								
				$this->cdtinneropen = true;
				
				$this->curswcol = $letter;
			}
			
			
			public function endCondition( $level ){ // ------------------------------------------------------------- END CONDITION
				
				if( $this->cdtinneropen ){ // Close Inner WRP
					
					$this->html .= "</div>";
					
					$this->cdtinneropen = false;
				}
				
				$this->html .= "</div>"; // Close Outer WRP
				
				$this->curswcol = null;
				
			}
			
			
			public function endCdtSwitch( $tblid ){ // ---------------------------------------------------------------- END CDT SWITCH
				
				$this->html .= "</div>";	$this->curswitch = false;
			}
			
			
			public function stemLine( $id,$cdt,$sub,$target ){ // ------------------------------------------------------- STEM LINE
				
				if( in_array( $target,array( 'input', 'output', 'output_block', 'flow', 'newinpos' ) ) ){
					
					$this->html .= "</div>"; // end of main section
				}
				
				if( $target != 'output_block' ){
					
					$this->html .= "<div id=\"" . $target . "_stem\" " // linked in $areturn
				
									. "class=\"master_stem_wrp\">";
				}
				else if( $target == 'output_block' ){
					
					//$this->html .= "</div><div class=\"output_text_actions_cont\">"; // also closes main div
				}
				
				
					if( $target == 'input' ){
					
						$this->stemid['input'] = $id;
						$this->html .= $this->newInputInner();
					}
					else if( $target == 'output' ){
						
						$this->stemid['output'] = $id;
						$this->html .= $this->newOutputInner();
					}
					else if( $target == 'output_block' ){
						
						$this->html .= $this->newBlockInner( $id ); // 412
					}
					else if( $target == 'flow' ){
						
						$this->html .= $this->newFlowInner( $id,false,null );
					}
					else if( $target == 'newinpos' ){
						
						$this->html .= $this->newFlowInner( $id,$this->curswitch,$this->curswcol );
					}
					else{
					
						$this->html .= "STEM ID: " . $id . " [ " . $target . " ]";
					}
						
				if( $target != 'output_block' ){
					
					$this->html .= "</div>";
				}
				else if( $target == 'output_block' ){
					
					//$this->html .= "</div>"; // closed: actions_cont
				}
				
			}
			
			
			public function outputCall( $ocid,$outype,$wrap ){ // ------------------------------------------------ OUTPUT CALL
				
				if( $wrap ){
								$this->html .= "<div class=\"flow_step_wrp\">"; // output_call_wrp
				}
				
				/*$this->html .= $this->flowStepHeader( $ocid, 'outputcall', 
				
														array( 'outype' => $outype, 'outnum' => $this->retro ) );*/
				
				$this->html .= "<div class=\"flow_step_left\">"
				
									. "<div class=\"flow_step_title\">"
									
										. "Call Output " . $outype . " " . $this->retro . "</div>" 
										
									. "<div class=\"flow_step_actions\"><div id=\"flowstep_menu_outcall_" . $ocid . "_start\">"
					
										. $this->printElement( 'div', 'Reset',
						
											array(  [ 'id','flowstep_menu_outcall_' . $ocid . '_reset_0' ],
														
													[ 'class','flowstep_menu_cmd' ],
														
													[ 'data-switch-hide','flowstep_menu_outcall_' . $ocid . '_start' ],
														
													[ 'data-switch-show','flowstep_menu_outcall_' . $ocid . '_reset_conf' ],
													
													[ 'title','Click to reset' ],
														
													[ 'onclick','attReader(this.id);' ]
												) )
							
											. "</div>"
						  
											. "<div id=\"flowstep_menu_outcall_" . $ocid . "_reset_conf\" style=\"display:none;\">"
							
										. $this->printElement( 'div', 'Confirm reset?',
						
											array(  
														
													[ 'class','flowstep_menu_txt' ]
														
	
												) )
													
										. $this->printElement( 'div', 'Reset',
						
											array(  [ 'id','flowstep_menu_outcall_' . $ocid . '_reset_do' ],
													
													[ 'class','flowstep_menu_cmd' ],
														
													['data-rawsend',urlencode(json_encode(array(
																	
																		PRESCRIPTID => $this->prescript,
																		
																		'action' => OUTPUT_CALL_RESET,	
																		
																		OUTPUT_CALL_ID => $ocid
																										)))],
														
													[ 'data-switch-hide','flowstep_menu_outcall_' . $ocid . '_reset_conf' ],
														
													[ 'data-switch-show','flowstep_menu_outcall_' . $ocid . '_processing' ],
														
													[ 'onclick','attReader(this.id);' ]
												) )
													
										. $this->printElement( 'div', 'Cancel',
						
											array(  [ 'id','flowstep_menu_outcall_' . $ocid . '_reset_cancel' ],
														
													[ 'class','flowstep_menu_cmd' ],
														
													[ 'data-switch-hide','flowstep_menu_outcall_' . $ocid . '_reset_conf' ],
														
													[ 'data-switch-show','flowstep_menu_outcall_' . $ocid . '_start' ],
														
													[ 'onclick','attReader(this.id);' ]
														
	
												) )
									. "</div>"
							
									. "<div id=\"flowstep_menu_outcall_" . $ocid . "_processing\" class=\"flowstep_menu_txt\""
									
										. " style=\"display:none;\">Processing...</div>"
									
									. "</div>"
									
								. "</div>";
				
				$this->html .= "<div class=\"flow_step_right\">"
				
								. "<div id=\"outcall_" . $ocid . "_ovar_cont\" class=\"outcall_ovars_cont\">";
					
								// outcall_ovars_cont
								
								if( empty( $this->buffer ) ){
									
									$this->html .= "No output variables";
								}
								else{
										$this->flushBuffer();
								}
				
				$this->html .= "</div></div>";
				
				if( $wrap ){
								$this->html .= "</div>";
				}
			}
			
			
			public function linkedOvar( $dbdata,$wrap ){ // ------------------------------------------------------------ LINKED OVAR
				
				if( $wrap ){
					
					if( $this->retro === false ){
					
						$this->retro = $dbdata['onum'];
					}
					
					$this->buffer .= "<div id=\"outcall_ovar_" . $dbdata['id'] . "_wrp\" class=\"outcall_ovar_wrp\">";
				}
				
				$this->buffer .= "<span class=\"output_text_var\">ov" . $dbdata['ovnum'] . "</span> = ";
				
				if( empty( $dbdata['vtxt'] ) ){
					
					$this->buffer .= "<input"	. $this->setAttributes( array(
														
															[ 'type','text' ],
															
															[ 'id','outcall_ovar_' . $dbdata['id'] . '_bind_inputxt' ]
															
															//[ 'placeholder','Var' ],
															//[ 'onblur','attReader(this.id);' ]
														) )
										. " />"
								   
								   . "<select"
												. $this->setAttributes( array(
															
															[ 'id','outcall_ovar_' . $dbdata['id'] . '_bind_parse' ]
															
														) )
										. ">"
									
									. "<option value=\"var\">Variable</option><option value=\"cst\">Value</option>"
								   
								   . "</select>"
								   
								   . "<input"
												. $this->setAttributes( array(
														
															[ 'type','button' ],
															
															[ 'id','outcall_ovar_' . $dbdata['id'] . '_bind_submit' ],
														
															[ 'value','Link' ],
															
															[ 'data-directpost',urlencode(json_encode(array(
																	
																	'senddata' => 	array(
																	
																			PRESCRIPTID => $this->prescript,
																		
																			'action' => OUTCAL_LINK_OVAR,
																			
																			OUTCAL_VARID => $dbdata[ 'id' ],
																		
																			OUTCAL_OVAR_NUM => $dbdata[ 'ovnum' ]		),					     
   																																
																	'inputdata' => 	array(
																	
														[ OUTCAL_LINK_TXT, 'outcall_ovar_' . $dbdata[ 'id' ] . '_bind_inputxt' ],
																								
														[ OUTCAL_LINK_PARSE, 'outcall_ovar_' . $dbdata[ 'id' ] . '_bind_parse' ] )										
																																))) ],	
															
															[ 'onclick','attReader(this.id);' ]
														) )
								. " />";
					
				}
				else{
						if( $dbdata['vparse'] == 'var' ){ // Variable
						
							$this->buffer .= "<span class=\"inline_var\">" . $dbdata['vtxt'] . "</span>";
						}
						else if( $dbdata['vparse'] == 'cst' ){ // Constant
						
							$this->buffer .= "<span class=\"uservalue\">" . $dbdata['vtxt'] . "</span>";
						}
				}
				
				if( $wrap ){
								$this->buffer .= "</div>";
				}
						
			}
			
			
			public function startVarProc( $procid,$wrap ){ // -------------------------######------------------------ START VAR PROC
				
				if( $wrap ){
					
					$this->html .= "<div class=\"flow_step_wrp\">"; // varproc_wrp
				}
				
				// $this->flowStepHeader( null, 'varproc', array( 'procnum' => $procid ) )
				
				$this->html .= "<div class=\"flow_step_left\">"
				
								. "<div class=\"flow_step_title\">Processing #" . $procid . "</div>" 
								
								. "</div>"
				
							 . "<div class=\"flow_step_right\"><div class=\"flow_proc_invar_otr\">" // LAST
				
								. "<div class=\"flow_proc_in_hdr\">IN-Vars</div>"
				
							 . "<div id=\"flow_proc_" . $procid . "_invar_cont\" class=\"flow_proc_invar_cont\">"; // In-var container
			}
			
			
			public function endVarProc(){ // ---------------------------------------------------------------------------- END VAR PROC
				
				$this->html .= "</div></div></div>";
			}
			
			
			public function varProcInVars( $invr,$vartxt ){ // ---------------------------------------------------------- IN-VAR
				
				$this->html .= "<span class=\"proc_invar\">" . $invr . "</span>";
			}
			
			
			public function varProcOutVar( $outvr, $ctxt, $wrap ){ // ------------------------------------------------- OUT-VAR
				
				if( $wrap ){
					
					$this->html .= "<div class=\"flow_proc_outvar\">";
				}
				
				$this->html .= "<span class=\"procvar\">p" . $outvr . "</span> <span class=\"flow_proc_ptxt\">" . $ctxt . "</span>";
				
				if( $wrap ){
					
					$this->html .= "</div>";
				}
			}
			
			
			public function varProcInStem( $procid,$stmid ){ // --------------------------------------------------------- IN-STEM
				
				$this->html .= "</div>" // 
				
							 . "<div class=\"varproc_stem_wrp\">"
							 
								. "<div"
											. $this->setAttributes( array( 
							
														[ 'id','flowproc_instem_' . $stmid . '_newinvar_start' ],
														
														[ 'class','varproc_stem_showform' ],
														
														[ 'data-switch-hide','flowproc_instem_' . $stmid . '_newinvar_start' ],
														
														[ 'data-switch-show','flowproc_instem_' . $stmid . '_newinvar_form' ],
														
														[ 'onclick','attReader(this.id);' ]
													) )
								
									. ">Add in-var" // <<<<<<<<<<<<<<<<<<<<< ADD IN VAR
								
								. "</div>"
								
								. "<div id=\"flowproc_instem_" . $stmid . "_newinvar_form\" "
								
									. "class=\"pad10\" style=\"display:none;\">"
								
									. "<input"
												. $this->setAttributes( array( 
										
														[ 'type','text' ],
							
														[ 'id','flowproc_instem_' . $stmid . '_newinvar_input' ],
														
														[ 'class','flowproc_instem_input' ],
														
														[ 'data-stack','newprocinvar'.$stmid ],
														
														[ 'data-key',VPROC_INVAR_TXT ],
																	
														[ 'data-getfrom','value' ],
																	
														[ 'placeholder',"Var" ],
														
														[ 'onblur','attReader(this.id);' ]
													) )
										. " />"
									
									. "<input"
												. $this->setAttributes( array( 
						
														[ 'id','flowproc_instem_' . $stmid . '_newinvar_submit' ],
														
														[ 'type','button' ], [ 'value','Add' ],
														
														[ 'data-directpost',urlencode(json_encode(array(
																	
																	'senddata' => 	array(
																	
																			PRESCRIPTID => $this->prescript,
																		
																			'action' => VPROC_ADD_INVAR,
																		
																			STEM_ID => $stmid				),
																			
																	'inputdata' => 	array(
																	
																			[ VPROC_INVAR_TXT, 'flowproc_instem_' 
																			
																									. $stmid . '_newinvar_input' ]
																					)										
																																))) ],	
														[ 'onclick','attReader(this.id);' ]
													) )
										. " />"
										
									. $this->printElement( 'input', null,array(
												
														[ 'id','flowproc_instem_' . $stmid . '_newinvar_cancel' ],
														
														[ 'type','button' ],
														
														[ 'data-switch-hide','flowproc_instem_' . $stmid . '_newinvar_form' ],
														
														[ 'data-switch-show','flowproc_instem_' . $stmid . '_newinvar_start' ],
														
														[ 'value','Cancel' ], [ 'onclick','attReader(this.id);' ]
													) )
								
								. "</div>"
								
							 . "</div>"
							 
							 . "</div><div class=\"flow_proc_outvar_otr\">" // LAST
							 
								. "<div class=\"flow_proc_out_hdr\">OUT-Vars</div>"
							 
								. "<div id=\"flow_proc_" . $procid . "_outvar_cont\" class=\"flow_proc_outvar_cont\">";
			}
			
			
			public function varProcOutStem( $stmid ){ // ---------------------------------------------------------------- OUT-STEM
				
				$this->html .= "</div>" // Closing outvar container
				
							 . "<div class=\"varproc_stem_wrp\">"
							 
								. "<div id=\"proc_outvar_stem_" . $stmid . "_start\" "
								
									. "class=\"varproc_stem_showform\" "
									
									. "data-switch-hide=\"proc_outvar_stem_" . $stmid . "_start\" "
							
									. "data-switch-show=\"proc_outvar_stem_" . $stmid . "_form\" " // Change to form
									
									/*. "data-rawsend=\"" . // Temporary
							
													urlencode(json_encode(array(
																	
																		PRESCRIPTID => $this->prescript,
																		
																		'action' => VPROC_ADD_OUTVAR,
																		
																		STEM_ID => $stmid
																)))
							
									. "\" "*/
									
								. "onclick=\"attReader(this.id);\">Add out-var</div>"
								
								. "<div id=\"proc_outvar_stem_" . $stmid . "_form\" style=\"display:none; padding:5px;\">"
								
									. $this->printElement( 'input', null,array(
												
														[ 'id','proc_outvar_stem_' . $stmid . '_txtinput' ],
														
														[ 'type','text' ], [ 'maxlength',150 ], [ 'style','width:250px;' ], 
														
														[ 'placeholder','Description (for yourself)' ]
													) )
									
									. $this->printElement( 'input', null,array(
												
														[ 'id','proc_outvar_stem_' . $stmid . '_submit' ],
														
														[ 'type','button' ],
														
														[ 'data-directpost',urlencode(json_encode(array(
																	
																	'senddata' => 	array(
																	
																			PRESCRIPTID => $this->prescript,
																		
																			'action' => VPROC_ADD_OUTVAR,
																		
																			STEM_ID => $stmid				),
																			
																	'inputdata' => 	array(
																	
																			[ MONOINPUT_DATA, 'proc_outvar_stem_' 
																			
																								. $stmid . '_txtinput' ]
																					)										
																														))) ],	
														
														[ 'value','Add' ], [ 'onclick','attReader(this.id);' ]
													) )
												
									. $this->printElement( 'input', null,array(
												
														[ 'id','proc_outvar_stem_' . $stmid . '_cancel' ],
														
														[ 'type','button' ],	[ 'value','Cancel' ], 
														
														[ 'data-switch-hide','proc_outvar_stem_' . $stmid . '_form' ],
														
														[ 'data-switch-show','proc_outvar_stem_' . $stmid . '_start' ],
														
														[ 'onclick','attReader(this.id);' ]
													) )
									
								. "</div>"
								
								. "<div id=\"proc_outvar_stem_" . $stmid . "_wait\" "
								
									. "class=\"\" style=\"display:none; padding:5px;\">"
								
								. "Processing...</div>"
									
								//. "OUT-STEM ID: " . $stmid 
							 
							 . "</div>";
			}
			
	}
	


?>
