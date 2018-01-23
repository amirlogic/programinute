<?php

// Processing Loader [ $disproc ]

// Copyright 2015-2016 Amir Hachaichi

/*
#_Init list
#	
#	$areturn->successFlowProcessing();
#
#   $areturn->successAddProcOutVar();
#	
#	viewps.php
#
#
#_Includes
#
#	viewps.php
#
#	dish.php
#
*/

	class ProcessingDisplayer {
		
		
		public $prescript;
		
		public $html = ''; // Processing section content
	
		private $curopstem; // Current operation stem
		
		
		public function __construct( $psid ){
				
				$this->prescript = $psid;
		}
		
		
		public function addAttribute( $att,$content ){ // -------------------------------------------------------- ADD ATTRIBUTE
				
			$toadd = " " . $att . "=\"" . $content . "\"";
				
			return $toadd;
		}
		
		
		public function setAttributes( $attrr ){ // ------------------------------------------------------------- SET ATTRIBUTES
				
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
		
		
		public function htmlProcStart( $procid,$wrap ){ // -------------------------------------------------------  PROC START
			
			if( $wrap ){
				
				$this->html .= "<div id=\"procsec_proc_" . $procid . "\" class=\"proc_wrp\">";
			}
			
			$this->html .= "<div><div class=\"procsec_procnum\"># <strong>" . $procid . "</strong></div><div class=\"procsec_mid_cont\">"
			
						 . "<div id=\"procsec_" . $procid . "_invar_cont\" class=\"procsec_invar_cont\">IN: "; // Starts invar container
		}
		
		
		public function htmlInVar( $vartxt,$wrap ){ // -----------------------------------------------------------  IN-VAR
			
			if( $wrap ){
				
				$this->html .= "<span class=\"proc_invar\">";
			}
			
			$this->html .= $vartxt;
			
			if( $wrap ){
				
				$this->html .= "</span>";
			}
		}

		
		public function htmlStartOperCont( $procid ){ // ---------------------------------------------------- START OPERATIONS SEC
			
			$this->html .= "</div>" // We need to close invar container
			
						 . "<div id=\"procsec_" . $procid . "_oper_cont\" class=\"proc_sec_opwrp\">";
			
		}
		
		
		public function startOperation( $procid,$rnum ){ // ---------------------------------------------------- START OPERATION
				
			$this->html .= "<div id=\"procsec_operation_" . $procid . "r" . $rnum . "_wrp\" class=\"procsec_operline\">";
		}
		
		
		public function mathSourceSwitch( $vtxt, $unip ){ // ----------------------------------------------- MATH SOURCE SWITCH
			
			if( empty( $vtxt ) ){
				
					return $unip;
			}
			else{
					return "<span class=\"inline_var\">" . $vtxt . "</span>";
			}
		}
		
		
		public function singleRowOperation( $rnum,$optype,$ophdr,$vartxt,$ucst ){ // ---------------------- SINGLE ROW OPERATION
			
			$this->html .= "<span class=\"resnum_var\">r" . $rnum . "</span> =";
			
			if( $optype == 'count' ){
				
				if( $ophdr == 'allchar' ){ // ----------------------------------------------------- Length
					
					$this->html .= " Number of characters in <span class=\"inline_var\">" 
												
									. $vartxt . "</span>";
				}
			}
			else if( $optype == 'math' ){
				
				if( $ophdr == 'log10' ){ // ------------------------------------------------------ log
					
					$this->html .= " log " . $this->mathSourceSwitch( $vartxt, $ucst );
				}
				else if( $ophdr == 'sqroot' ){ // ---------------------------------------------- Square root
					
					$this->html .= " &radic;" . $this->mathSourceSwitch( $vartxt, $ucst );
				}
				else if( $ophdr == 'lnep' ){ // ---------------------------------------------- Natural logarithm
					
					$this->html .= " ln " . $this->mathSourceSwitch( $vartxt, $ucst );
				}
				else if( $ophdr == 'sinus' ){ // ---------------------------------------------- Sinus
					
					$this->html .= " sin " . $this->mathSourceSwitch( $vartxt, $ucst );
				}
				else if( $ophdr == 'cosinus' ){ // ---------------------------------------------- Cosinus
					
					$this->html .= " cos " . $this->mathSourceSwitch( $vartxt, $ucst );
				}
				else if( $ophdr == 'tang' ){ // ---------------------------------------------- Tan
					
					$this->html .= " tan " . $this->mathSourceSwitch( $vartxt, $ucst );
				}
				else if( $ophdr == 'exp' ){ // ---------------------------------------------- Exp
					
					$this->html .= " Exp " . $this->mathSourceSwitch( $vartxt, $ucst );
				}
				else if( $ophdr == 'round' ){ // ---------------------------------------------- round
					
					$this->html .= " round " . $this->mathSourceSwitch( $vartxt, $ucst );
				}
				else if( $ophdr == 'absolute' ){ // ---------------------------------------------- Absolute
					
					$this->html .= " | " . $this->mathSourceSwitch( $vartxt, $ucst ) ." |";
				}
			}
		}
		
		
		public function multiRowOperation( $rnum,$optype,$ophdr,$rowsrr ){ // --------------------------- MULTI ROW OPERATION
			
			$this->html .= "<span class=\"resnum_var\">r" . $rnum . "</span> = ";

			
			if( $optype == 'math' ){
				
				if( in_array($ophdr,array('plus','minus','multiply','divide','power','root')) ){ // ------- Arithmetics ( + - x / )
					
					$operatorr = array( 'plus'=>'+', 'minus'=>'-', 'multiply'=>'&times;', 'divide'=>'/', 'power'=>'^' );
					
					$this->html .= ( $rowsrr['x'][0] ) ? 
											
											"<span class=\"inline_var\">" . $rowsrr['x'][1] . "</span>" : $rowsrr['x'][1];
					
					$this->html .= " <span class=\"mathop\">" . $operatorr[ $ophdr ] . "</span> ";

					$this->html .= ( $rowsrr['y'][0] ) ? 
											
											"<span class=\"inline_var\">" . $rowsrr['y'][1] . "</span>" : $rowsrr['y'][1];
				}
				
				
			}
			
			
			
		}
		
		
		public function closeOperation(){ // --------------------------------------------------------------------- CLOSE OPERATION
				
			$this->html .= "</div>";
		}
		
		
		public function newOperationInner( $stmid ){ // ------------------------------------------------------- NEW OPERATION INNER
			
			/* "<div"
						. $this->setAttributes( array( [ 'id','proc_sec_opstem_' . $stmid . '_0'],['class','inline_select' ],
														[ 'data-switch-hide','proc_sec_opstem_' . $stmid . '_0' ],
														[ 'data-switch-show','proc_sec_opstem_' . $stmid . '_start' ],
														[ 'onclick','attReader(this.id);' ]
													) )
						. ">New operation" // <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<
			
				   . "</div>" */
			
			$inner = "<div id=\"proc_sec_opstem_" . $stmid . "_start\" style=\"\">" // _________________________
				   
						. "<div"
						
							. $this->setAttributes( array( 
							
														[ 'id','proc_sec_preform_' . $stmid . '_string'],
														
														[ 'class','inline_select' ],
														
														[ 'data-switch-hide','proc_sec_opstem_' . $stmid . '_start' ],
														
														[ 'data-switch-show','proc_sec_opstem_' . $stmid . '_count' ],
														
														[ 'onclick','attReader(this.id);' ]
													) )
						
							. ">Count" // <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<
						
						. "</div>"
						
						
						/*. "<div"
						
							. $this->setAttributes( array( 
							
														[ 'id','proc_sec_preform_' . $stmid . '_extract'],
														
														[ 'class','inline_select' ],
														
														[ 'data-switch-hide','proc_sec_opstem_' . $stmid . '_start' ],
														
														[ 'data-switch-show','proc_sec_opstem_' . $stmid . '_extract' ],
														
														[ 'onclick','attReader(this.id);' ]
													) )
						
							. ">Extract" // <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<
						
						. "</div>"*/
						
						
						/*. "<div"
						
							. $this->setAttributes( array( 
							
														[ 'id','proc_sec_preform_' . $stmid . '_insert'],
														
														[ 'class','inline_select' ],
														
														[ 'data-switch-hide','proc_sec_opstem_' . $stmid . '_start' ],
														
														[ 'data-switch-show','proc_sec_opstem_' . $stmid . '_addto' ],
														
														[ 'onclick','attReader(this.id);' ]
													) )
						
							. ">Insert &amp; Join" // <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<
						
						. "</div>"*/
						
						
						/*. "<div"
						
							. $this->setAttributes( array( 
							
														[ 'id','proc_sec_preform_' . $stmid . '_replace'],
														
														[ 'class','inline_select' ],
														
														[ 'data-switch-hide','proc_sec_opstem_' . $stmid . '_start' ],
														
														[ 'data-switch-show','proc_sec_opstem_' . $stmid . '_replace' ],
														
														[ 'onclick','attReader(this.id);' ]
													) )
						
							. ">Replace" // <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<
						
						. "</div>"*/
						
						
						. "<div"
						
							. $this->setAttributes( array( 
							
														[ 'id','proc_sec_preform_' . $stmid . '_math'],
														
														[ 'class','inline_select' ],
														
														[ 'data-switch-hide','proc_sec_opstem_' . $stmid . '_start' ],
														
														[ 'data-switch-show','proc_sec_opstem_' . $stmid . '_math' ],
														
														[ 'onclick','attReader(this.id);' ]
													) )
						
							. ">Math" // <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<
						
						. "</div>"
						
						. "<div"
						
							. $this->setAttributes( array( 
							
														[ 'id','proc_sec_preform_' . $stmid . '_cancel'],
														
														[ 'class','inline_select' ],
														
														[ 'data-switch-hide','procsec_opinput_' . $stmid . '_wrp' ],
														
														[ 'data-switch-show','procsec_opstm_' . $stmid . '_show' ],
														
														[ 'onclick','attReader(this.id);' ]
													) )
						
							. ">Cancel" // <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<
						
						. "</div>"
				   
				   . "</div>"
				   
				   . "<div id=\"proc_sec_opstem_" . $stmid . "_count\" style=\"display:none;\">" // ----------------------------
						
						. $this->printElement( 'div', 
						
												"Count the number of characters in " // <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<
												
											  . $this->printElement('input','',
											  
												array(	[ 'type','text' ],
														
														[ 'id','flowproc_newop_count_all_' . $stmid . '_input' ],
														
														[ 'placeholder','Var' ],	[ 'size','5' ]
														
														//[ 'onblur','attReader(this.id);' ]
													) )
											  
											  . $this->printElement('input','',
											  
												array(
														[ 'type','button' ],
														
														[ 'id','flowproc_newop_count_all_' . $stmid . '_submit' ],
														
														[ 'data-directpost',urlencode(json_encode(array(
																	
																	'senddata' => 	array(
																	
																			PRESCRIPTID => $this->prescript,
																		
																			'action' => VPROC_ADD_OPER,
																		
																			STEM_ID => $stmid,
																			
																			VPROC_OPERADD_MODE => 'varonly',
																		
																			VPROC_OPER_TYPE => 'count',
																		
																			VPROC_OPER_HEADER => 'allchar'	 ),					     
   																																
																	'inputdata' => 	array(
																	
																			[ MONOINPUT_DATA, 'flowproc_newop_count_all_' 
																			
																										. $stmid . '_input' ]
																	)										
																															))) ],	
														
														[ 'value','OK' ],	[ 'onclick','attReader(this.id);' ]
													) ),
						
												array( 
														//[ 'id','proc_sec_opstem_showform_' . $stmid . '_posextract' ],
														
														[ 'style','display:inline-block;padding:6px 20px;' ]
														
														
													) )

						
						/*. $this->printElement( 'div', 'More...',
						
												array( 
														//[ 'id','proc_sec_opstem_showform_' . $stmid . '_posextract' ],
														
														[ 'style','display:inline-block;padding:6px 20px;color:#AAA;' ],
														
														[ 'data-switch-hide','proc_sec_opstem_' . $stmid . '_string' ],
														
														[ 'data-switch-show','proc_sec_opstem_' . $stmid . '_formcont' ],
														
														//[ 'onclick','attReader(this.id);' ]
													) )*/
						
						. $this->printElement( 'div', 'Go back',
						
												array( 
														[ 'id','proc_sec_opstem_count_goback_' . $stmid ],
														
														[ 'class','inline_select' ],
														
														[ 'data-switch-hide','proc_sec_opstem_' . $stmid . '_count' ],
														
														[ 'data-switch-show','proc_sec_opstem_' . $stmid . '_start' ],
														
														[ 'onclick','attReader(this.id);' ]
													) )
				   
				   . "</div>"
				   
				   
				   . "<div id=\"proc_sec_opstem_" . $stmid . "_extract\" style=\"display:none;\">"
						
						. $this->printElement( 'div', 
						
												"Starting "
												
												. $this->printElement('input','',
												
													array(
															[ 'type','number' ],
															
															[ 'style','width:40px;' ],
													
															[ 'min','0' ],[ 'max','999' ],
													
															[ 'value','0' ]
														))
										
												
												. " <select><option>characters after the beginning of</option>"
												
												. "<option>characters before the end of</option></select> "
												
												. $this->printElement('input','',
												
													array(
															[ 'type','text' ],
													
															[ 'size','2' ],
															
															[ 'placeholder','Var' ]
															
														))
												
												
												
												. " extract "
												
												. $this->printElement('input','',
												
													array(
															[ 'type','number' ],
															
															[ 'style','width:40px;' ],
													
															[ 'min','0' ],[ 'max','999' ]
													
															
														))
												
												. " characters "
												
												. $this->printElement('input','',
												
													array(
															[ 'type','button' ],
													
															
															
															[ 'value','OK' ]
															
														)),
						
												array( 
														[ 'style','display:inline-block;padding:6px 20px;' ]
													) )
						
						. $this->printElement( 'div', 'More...',
						
												array( 
														//[ 'id','proc_sec_opstem_showform_' . $stmid . '_posextract' ],
														
														[ 'style','display:inline-block;padding:6px 20px;color:#AAA;' ],
														
														//[ 'data-switch-hide','proc_sec_opstem_' . $stmid . '_string' ],
														
														//[ 'data-switch-show','proc_sec_opstem_' . $stmid . '_formcont' ],
														
														//[ 'onclick','attReader(this.id);' ]
													) )
				   
						. $this->printElement( 'div', 'Go back',
						
												array( 
														[ 'id','proc_sec_opstem_extract_goback_' . $stmid ],
														
														[ 'style','display:inline-block;padding:6px 20px;' ],
														
														[ 'data-switch-hide','proc_sec_opstem_' . $stmid . '_extract' ],
														
														[ 'data-switch-show','proc_sec_opstem_' . $stmid . '_start' ],
														
														[ 'onclick','attReader(this.id);' ]
													) )
				   
						
				   
				   . "</div>"
				   
				   
				   . "<div id=\"proc_sec_opstem_" . $stmid . "_addto\" style=\"display:none;\">"
				   
						. "<div"
						
							. $this->setAttributes( array( 
							
														[ 'id','proc_sec_opstem_addto_goback_' . $stmid ],
														
														[ 'style','display:inline-block;padding:6px 20px;' ],
														
														[ 'data-switch-hide','proc_sec_opstem_' . $stmid . '_addto' ],
														
														[ 'data-switch-show','proc_sec_opstem_' . $stmid . '_start' ],
														
														[ 'onclick','attReader(this.id);' ]
													) )
						
							. ">Go back" // <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<
						
						. "</div>"
				   
				   . "</div>"
				   
				   
				   . "<div id=\"proc_sec_opstem_" . $stmid . "_replace\" style=\"display:none;\">"
				   
						. "<div"
						
							. $this->setAttributes( array( 
							
														[ 'id','proc_sec_opstem_replace_goback_' . $stmid ],
														
														[ 'style','display:inline-block;padding:6px 20px;' ],
														
														[ 'data-switch-hide','proc_sec_opstem_' . $stmid . '_replace' ],
														
														[ 'data-switch-show','proc_sec_opstem_' . $stmid . '_start' ],
														
														[ 'onclick','attReader(this.id);' ]
													) )
						
							. ">Go back" // <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<
						
						. "</div>"
				   
				   . "</div>"
				   
				   
				   . "<div id=\"proc_sec_opstem_" . $stmid . "_math\" style=\"display:none;\">"
				   
						. $this->printElement( 'div', 
							
												"<select id=\"procsec_opstem_" . $stmid . "_math_onevar_func\" >"
												
													. $this->printElement('option','Logarithm base 10 log',
												
														array(
																[ 'value','log10' ]

															))
													
													. $this->printElement('option','Neperian Logarithm ln',
												
														array(
																[ 'value','lnep' ]

															))
													
													. $this->printElement('option','exp(x)',
												
														array(
																[ 'value','exp' ]

															))
													
													. $this->printElement('option','Square root &radic;',
												
														array(
																[ 'value','sqroot' ]

															))
															
													. $this->printElement('option','Sine sin x',
												
														array(
																[ 'value','sinus' ]

															))
													
													. $this->printElement('option','Cosine cos x',
												
														array(
																[ 'value','cosinus' ]

															))
													
													. $this->printElement('option','Tangent tan x',
												
														array(
																[ 'value','tang' ]

															))
															
													. $this->printElement('option','Absolute value |x|',
												
														array(
																[ 'value','absolute' ]

															))
												
												."</select> "
												
												. $this->printElement('input','',
												
													array(
															[ 'type','text' ],
															
															[ 'id','procsec_opstem_' . $stmid . '_math_onevar_input' ],
													
															[ 'size','2' ],
															
															[ 'placeholder','x' ]
															
														))
												
												. $this->printElement('input','',
												
													array(
															[ 'type','button' ],
															
															[ 'id','procsec_opstem_' . $stmid . '_math_onevar_submit' ],
													
															[ 'data-directpost',urlencode(json_encode(array(
																	
																	'senddata' => 	array(
																	
																			PRESCRIPTID => $this->prescript,
																		
																			'action' => VPROC_ADD_OPER,
																		
																			STEM_ID => $stmid,
																			
																			VPROC_OPER_TYPE => 'math'
																	),																										
																	'inputdata' => 	array(
																	
																			[ VPROC_OPER_HEADER, 'procsec_opstem_' 
																								. $stmid . '_math_onevar_func' ],
																			
																			[ VPROC_OPER_VAR, 'procsec_opstem_' 
																							 . $stmid . '_math_onevar_input' ]

																	)										
																										))) ],
															
															[ 'onclick','attReader(this.id);' ],
															
															[ 'value','OK' ]
															
														)),
						
												array( 
														//[ 'id','proc_sec_opstem_math_goback_' . $stmid ],
														
														[ 'style','display:inline-block;padding:6px 40px;' ],
														
														
													) )
													
						. $this->printElement( 'div', 
						
												$this->printElement('input','',
												
													array(
															[ 'type','text' ],

															[ 'id','procsec_opstem_' . $stmid . '_math_twovar_inputx' ],
													
															[ 'size','2' ],
															
															[ 'placeholder','x' ]
															
														))
														
												. " <select id=\"procsec_opstem_" . $stmid . "_math_twovar_oper\">"
												
													. $this->printElement('option','Add +',
												
														array(
																[ 'value','plus' ]

															))
													
													. $this->printElement('option','Multiply x',
												
														array(
																[ 'value','multiply' ]

															))
															
													. $this->printElement('option','Minus -',
												
														array(
																[ 'value','minus' ]

															))
															
													. $this->printElement('option','Divide /',
												
														array(
																[ 'value','divide' ]

															))	
													
													. $this->printElement('option','Power ^',
												
														array(
																[ 'value','power' ]

															))	
													
												
												. "</select> "
												
												. $this->printElement('input','',
												
													array(
															[ 'type','text' ],

															[ 'id','procsec_opstem_' . $stmid . '_math_twovar_inputy' ],
													
															[ 'size','2' ],
															
															[ 'placeholder','y' ]
															
														))
												
												. $this->printElement('input','',
												
													array(
															[ 'type','button' ],
													
															[ 'id','procsec_opstem_' . $stmid . '_math_twovar_submit' ],

															[ 'data-directpost',urlencode(json_encode(array(
																	
																	'senddata' => 	array(
																	
																			PRESCRIPTID => $this->prescript,
																		
																			'action' => VPROC_ADD_OPER,
																		
																			STEM_ID => $stmid,
																			
																			VPROC_OPER_TYPE => 'math'
																	),																										
																	'inputdata' => 	array(
																	
																			[ VPROC_OPER_HEADER, 'procsec_opstem_' 
																								. $stmid . '_math_twovar_oper' ],
																			
																			[ VPROC_OPER_XVAR, 'procsec_opstem_' 
																							  . $stmid . '_math_twovar_inputx' ],

																			[ VPROC_OPER_YVAR, 'procsec_opstem_' 
																							  . $stmid . '_math_twovar_inputy' ]

																	)										
																										))) ],
														
															[ 'value','OK' ],	[ 'onclick','attReader(this.id);' ]
														)),
						
												array( 
														
														[ 'style','display:inline-block;padding:6px 40px;' ]
														
													) )
													
						/*. $this->printElement( 'div', 'More...',
						
												array( 
														//[ 'id','proc_sec_opstem_math_goback_' . $stmid ],
														
														[ 'class','inline_select' ],
														
														//[ 'data-switch-hide','proc_sec_opstem_' . $stmid . '_math' ],
														
														//[ 'data-switch-show','proc_sec_opstem_' . $stmid . '_start' ],
														
														//[ 'onclick','attReader(this.id);' ]
													) )*/
				   
						. $this->printElement( 'div', 'Go back',
						
												array( 
														[ 'id','proc_sec_opstem_math_goback_' . $stmid ],
														
														[ 'class','inline_select' ],
														
														[ 'data-switch-hide','proc_sec_opstem_' . $stmid . '_math' ],
														
														[ 'data-switch-show','proc_sec_opstem_' . $stmid . '_start' ],
														
														[ 'onclick','attReader(this.id);' ]
													) )
				   
				   . "</div>"
				   
				   
				   . "<div id=\"proc_sec_opstem_" . $stmid . "_formcont\" style=\"display:none;\">" // ---------------------------
				   
				   . "Loading...</div>";
			
			return $inner;
			
		}
		
		
		public function htmlOperStem( $stmid ){ // --------------------------------------------------------------  OPER STEM
			
			$this->html .= "</div>"; // Close operations container
			
			$this->html .= "<div class=\"proc_oper_stem_wrp\">" 
			
							. $this->printElement( 'span', 'New Operation',	array(
							
														[ 'id','procsec_opstm_' . $stmid . '_show' ],
														
														[ 'class','inline_select' ],
														
														[ 'data-switch-hide','procsec_opstm_' . $stmid . '_show' ],
														
														[ 'data-switch-show','procsec_opinput_' . $stmid . '_wrp' ],
														
														[ 'onclick','attReader(this.id);' ]
													) )
							
						 . "</div></div>"; // Also closes mid-cont procsec_opinput_
						 
			$this->curopstem = $stmid;
		}
		
		
		public function htmlEndOperCont( $procid ){ // ----------------------------------------------------------  OPER END
			
			$this->html .= "<div id=\"procsec_" . $procid . "_outvar_cont\" class=\"procsec_outvar_cont\">";
		}
		
		
		public function htmlOutVar( $pnum,$resnum,$ctxt,$wrap,$linkonly ){ // ------------------------------------------  OUT VAR
			
			if( $wrap && !$linkonly ){
				
				$this->html .= "<div id=\"procsec_outvar_" . $pnum . "_wrp\" class=\"procsec_outvar_wrp\">";
			}
			
			if( !$linkonly ){
				
				$this->html .= "<div id=\"procsec_outvar_" . $pnum . "_link\" class=\"procsec_outvar_leftcont\">";
			}
			
			$this->html .= "<span class=\"procvar\">p" . $pnum . "</span> = ";
			
			if( empty( $resnum ) ){
				
				$this->html .= "r <input"
				
													. $this->setAttributes( array(
														
															[ 'type','number' ], [ 'size',2 ], [ 'class','procsec_outvar_binput' ], 
															
															[ 'id','proc_outvar' . $pnum . '_bind_input' ],
															
															[ 'min','1' ],	[ 'max','99' ],		[ 'placeholder','Num' ]
															
															//[ 'onblur','attReader(this.id);' ]
														) )
														
														. " />"
														
												. "<input"
												
													. $this->setAttributes( array(
														
															[ 'type','button' ],
															
															[ 'id','proc_outvar' . $pnum . '_bind_submit' ],
														
															[ 'value','Link' ],
															
															[ 'data-directpost',urlencode(json_encode(array(
																	
																	'senddata' => 	array(
																	
																			PRESCRIPTID => $this->prescript,
																		
																			'action' => VPROC_BIND_OUTVAR,
																		
																			VPROC_OUTVAR => $pnum
																													),																										
																	'inputdata' => 	array(
																	
																			[ MONOINPUT_DATA, 'proc_outvar'
																			
																										. $pnum . '_bind_input' ]
																	)										
																																))) ],
															[ 'onclick','attReader(this.id);' ]
														) )
												
												. " />";
			}
			else{
				
				$this->html .= "r" . $resnum;
			}
			
			if( !$linkonly ){
				
				$this->html .= "</div><div  id=\"procsec_outvar_" . $pnum . "_txt\" class=\"procsec_outvar_rightcont\">" 
				
								. $ctxt . "</div>";
			}
			
			if( $wrap && !$linkonly ){
				
				$this->html .= "</div>";
			}
		}
		
		
		public function htmlProcEnd( $wrap ){ // ---------------------------------------------------------------------  PROC END
			
			$this->html .= "</div></div>" // closed: OutVar cont - first inner div
			
						 . "<div id=\"procsec_opinput_" . $this->curopstem . "_wrp\" class=\"procsec_opinput_wrp\">"
						 
							. $this->newOperationInner( $this->curopstem )
						 
						 . "</div>";
						 
			if( $wrap ){
				
				$this->html .= "</div>";
			}
		}
		
		
		
		
	}
	
	
	
?>
