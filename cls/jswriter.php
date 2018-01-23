<?php

// Javascript File Writer [ $jswrt ] < AUTO INIT >

// Functions are directly called from codeswitch class

// Copyright 2016 Amir Hachaichi



	class JavascriptWriter {
		
			
			
			
			//public $writeoutput;
			
			public $inputs = '';
			
			public $inpvar = '';
			
			public $outsec = '';
			
			public $cdtlevel = array();
			
			public $procdata = array(); // Processing data [procid] => data
			
			public $flow = '';
			
			private $ocalldata = array(); // Output Call Buffer
			
			public $finaljs;
			
			
			public $error;
			
			public $test;
			
			
			/*public function __construct(){ 
				// Start
			}*/
			
			// -------------------------------------------------------------------- Basic functions (Low level)
			
			
			/*public function output( $out ){ // Print text
				$this->newline("print(\"".addslashes($out)."\")");}
			public function outvar( $var ){ // Print variable
				$this->newline( "print(".$out.")" );}*/
			/*public function outseg( $sgmts ){ // Prints segments ($sgmts = array[0]: txt/var, array[1]: value)
				$outrr = array();if( !is_array($sgmts) ){$this->error = "Not an array";return false;}
				for( $g=0; $g<count($sgmts); $g++ ){
					if( $sgmts[$g][0] == 'txt' ){$outrr[] = "\"" . addslashes( $sgmts[$g][1] ) . "\"";}
					else if ( $sgmts[$g][0] == 'var' ){$outrr[] = "str(".$sgmts[$g][1].")";
						/*if( !array_key_exists( $sgmts[$g][1],$this->varlist ) ){
							$this->error = "Variable not found";return false;}
						if( $this->varlist[$sgmts[$g][1]] == false ){ // Not Str
							$outrr[] = "str(".$sgmts[$g][1].")";}else{ // Str
							$outrr[] = $sgmts[$g][1];}}}
				//$out = implode('+',$outrr);$this->newline( "print(".implode('+',$outrr).")" );}*/
			/*public function setVar( $name,$val,$str ){ // Set a variable (testing function)
				if( $str === true ){$this->newline($name . " = \"" . addslashes($val) . "\"");}
				else{$this->newline($name . " = " . $val);}
				$this->varlist[$name] = $str;}
			public function addInput( $var ){ // (varname,string: true or false)
				$this->setVar('in'.$var,"form['in".$var."'].value",false);}*/
			
			
			
			// -------------------------------------------------------------- Conditions
			
			
			public function cdtCompiler( $prefunc,$target,$oper,$val,$vparse ){ // ---------------------- CDT COMPILER
				
				$cpdct = '';
				
				$oprr = array( 	// Operators
				
									'=' => '==',	'>' => '>', 	'>=' => '>=',
									'<' => '<',		'<=' => '<=', 	'#' => '!=',
									'!=' => '!='
								);
				
				if( empty( $prefunc ) ){
					
					$cpdct .= $target;
				}
				else{
						if( $prefunc == 'length' ){
							
							$cpdct .= $target . '.length';
						}
				}
				
				$cpdct .= ' ' . $oprr[ $oper ] . ' ';
				
				if( $vparse == 'var' ){
											$cpdct .= $val;
				}
				else if( $vparse == 'cst' ){
												if( is_numeric( $val ) ){
													
													$cpdct .= $val;
												}
												else{
														$cpdct .= '"' . $val . '"';
												}
				}
				
				return $cpdct;
			}
			
			
			public function load_cdt_table( $table,$cdtlist,$col_letter ){ // ---------------------------- LOAD CDT TABLE
				
				
				if( !is_array($cdtlist) || !is_array($col_letter) ){
					
					$this->error = "Invalid Condition input";
					return false;
				}
				
				$operr = array( 	// Operators
				
								'=' => '==',
								'>' => '>',
								'<' => '<',
								'#' => '!=',
								'!=' => '!=',
								'or' => '||',
								'and' => '&&'
								
								);
				
				$varpfx = "cdt" . $table . "_";
				
				$cvars = array(); // Preparing final array
				
				$cols = array_keys($cdtlist); // Columns position
				
				
				for( $c=0; $c<count($cols); $c++ ) // Column looping
				{
					
					$cvars[$c] = array();
					
					
					for( $l=0; $l<count( $cdtlist[$cols[$c]] ); $l++ ) // Level looping
					{
						
						//$cvars[$l] = array();
						
						
						if( $l == 0 ){ // --------------------------------------------------------------- Top level
							
							$vadd = "";
							
							for( $i=0; $i<count( $cdtlist[$cols[$c]][0] ); $i++ ) // InLevel Position
							{
							
								if( $cdtlist[ $cols[$c] ][0][$i][0] == 'cdt' )
								{
									$vadd .= $this->cdtCompiler( $cdtlist[$cols[$c]][0][$i][1],$cdtlist[$cols[$c]][0][$i][2],
									
																	$cdtlist[$cols[$c]][0][$i][3],$cdtlist[$cols[$c]][0][$i][4],
																	
																	$cdtlist[$cols[$c]][0][$i][5] );
								}
								else if ( $cdtlist[$cols[$c]][0][$i][0] == 'oper' ){
								
								
									$vadd .= " " . $operr[	$cdtlist[$cols[$c]][0][$i][1]	] . " ";
								
								}
								else if ( $cdtlist[$cols[$c]][0][$i][0] == 'grp' ){
									
									
									$vadd .= $varpfx.$col_letter[$cols[$c]].$cdtlist[$cols[$c]][0][$i][1];
									
								}
								else{ // Error
								
									return false;
								
								}
							
							}
							
							//
							
							$cvars[$c][ $varpfx.$col_letter[$cols[$c]].'top' ] = $vadd;
							
							unset($vadd);
							
						}
						else{ // -------------------------------------------------------------------------- Nested
							
							
							for( $g=0; $g<count( $cdtlist[$cols[$c]][$l] ); $g++ ) // Groups
							{
								
								$vadd = "";
							
								for( $i=0; $i<count($cdtlist[$cols[$c]][$l][$g][1]); $i++ ) // InLevel Position
								{
							
									if( $cdtlist[$cols[$c]][$l][$g][1][$i][0] == 'cdt' )
									{
								
										$vadd .= $this->cdtCompiler( $cdtlist[$cols[$c]][$l][$g][1][$i][1],
																		$cdtlist[$cols[$c]][$l][$g][1][$i][2],
																		$cdtlist[$cols[$c]][$l][$g][1][$i][3],
																		$cdtlist[$cols[$c]][$l][$g][1][$i][4],
																		$cdtlist[$cols[$c]][$l][$g][1][$i][5] );
									}
									else if ( $cdtlist[$cols[$c]][$l][$g][1][$i][0] == 'oper' ){
								
								
										$vadd .= " " . $operr[	$cdtlist[$cols[$c]][$l][$g][1][$i][1]	] . " ";
								
									}
									else if ( $cdtlist[$cols[$c]][$l][$g][1][$i][0] == 'grp' ){
									
									
										$vadd .= $varpfx.$col_letter[$cols[$c]].$cdtlist[$cols[$c]][$l][$g][1][$i][1];
									
									}
									else{ // Error
								
										return false;
								
									}
							
								} // InPos
								
								$cvars[$c][ $varpfx.$col_letter[$cols[$c]].$cdtlist[$cols[$c]][$l][$g][0] ] = $vadd;
								
								unset($vadd);
								
								
							} // Groups
							
								
						} // Level 0 or not
						
						

					} // Level
					
					// Defining variables in Js
					
					$varr = array_keys( $cvars[$c] );
					
					$mxn = count($varr) - 1;
					
					for( $v=$mxn; $v>=0; $v-- )
					{
						$this->flow .= "var " . $varr[$v] . " = " . $cvars[$c][ $varr[$v] ] . "; ";
					}
					
					unset($varr);
					
				} // Column
				
				
			}
			
			
			// Conditions (Reminder: Level decrease at the end)
			
			
			public function startCdtSwitch( $tblid ){ // ------------------------------------------------------- CDT SWITCH
				
				$this->cdtlevel[ $tblid ] = array();
			}
			
			
			public function startCondition( $tblid,$tblnum,$letter,$ctype,$level ){ // --------------------------- CDT CONDITION
				
				global $pywrt;
				
				if( $ctype == 'if' ){
					
					if( in_array( $level,$this->cdtlevel[ $tblid ] ) ){ // Same level: ELSE IF
						
						$this->startElseIf( 'cdt'.$tblnum.'_'.$letter.'top' );
					}
					else{ // IF
						
						$this->startIf( 'cdt'.$tblnum.'_'.$letter.'top' );
						
						$this->cdtlevel[ $tblid ][] = $level;
					}
					
				}
				else if( $ctype == 'else' ){
					
					$this->startElse();
				}
				
				
			}
			
			
			public function startIf( $st ){ // ------------------------------------------------------ CONDITION IF
				
				$this->flow .= "if(".$st."){ ";
				
			}
			
			
			public function startElseIf($st){ // -------------------------------------------------- CONDITION ELSE IF
				
				$this->flow .= "else if(".$st."){ ";
				
			}
			
			
			public function startElse(){ // ----------------------------------------------------------------- CONDITION ELSE
				
				$this->flow .= "else{ ";
				
			}
			
			
			public function endCondition(){ // ------------------------------------------------------ END CONDITION
				
				$this->flow .= ' }';
			}
			
			
			public function startFunction( $fname,$fparm ){ // ---------------------------------------------- START FUNCTION
			
				$this->newline( "def " . $fname . "(" . $fparm . "):" );
				
				$this->curlevel++;
			}
			
			
			public function addTextInput( $dbdata ){ // ---------------------------------------------------------- ADD INPUT
				
				$this->inputs .= "<div class=\"formline\">"
									
									. "<input type=\"text\" id=\"in" . $dbdata['vnum'] . "\" " 
									
										. "placeholder=\"" . $dbdata['title'] . "\" />"
									
							   . "</div>";
							   
				$this->inpvar .= " var in" . $dbdata['vnum'] . " = document.getElementById('in" . $dbdata['vnum'] . "').value; ";
				
			}
			
			
			public function startOutputText( $num ){ // -------------------------------------------------- START OUTPUT TEXT
				
				$this->outsec .= "function text_output_" . $num . "(ovarr){ ";
				
			}
			
			
			public function startOutputBlock(){ // ------------------------------------------------------- START OUTPUT BLOCK
				
				$this->outsec .= "xoutput += \"<p>";
			}
			
			
			public function addBrick( $type,$data ){ // ------------------------------------------------------- ADD BRICK
				
				if( $type == 'text' ){
					
					$this->outsec .= addslashes( $data );
				}
				else if( $type == 'var' ){
					
					$this->outsec .= "\"+ovarr.ov" . $data . "+\"";
				}
			}
			
			
			public function endOutputBlock(){ // ----------------------------------------------------------- END OUTPUT BLOCK
				
				$this->outsec .= "</p>\"; ";
			}
			
			
			public function endOutputText(){ // ----------------------------------------------------------- CLOSE OUTPUT TEXT
				
				$this->outsec .= " } ";
			}
			
			
			public function initOutCall( $callid,$onum ){ // --------------------------------------------------- INIT OCALL
				
				$this->ocalldata[ $callid ] = array( $onum,array() );
			}
			
			
			public function linkedOvar( $callid,$onum,$ovnum,$vparse,$vtxt ){ // -------------------------------------- LINKED OVAR
				
				if( !isset( $this->ocalldata[ $callid ] ) ){
																$this->initOutCall( $callid,$onum );
				}
				
				if( !empty( $vtxt ) ){
										
					if( $vparse == 'var' ){
												$this->ocalldata[ $callid ][1][] = "ov" . $ovnum . ":" . $vtxt;
					}
					else if( $vparse == 'cst' ){
													$this->ocalldata[ $callid ][1][] = "ov" . $ovnum . ":" . "\"" . $vtxt . "\"";
					}
				}
				else{
						$this->ocalldata[ $callid ][1][] = "ov" . $ovnum . ":\"\"";
				}
			}
			
			
			public function outputCall( $otype,$callid ){ // --------------------------------------------------- OUTPUT CALL
				
				if( $otype == 'text' ){
					
					$this->flow .= "text_output_" . $this->ocalldata[ $callid ][0] 
					
								 . "({" . implode( ',',$this->ocalldata[ $callid ][1] ) . "}); ";
				}
				
				unset( $this->ocalldata[ $callid ] );
			}
			
			
			public function insertProcData( $procid ){ // ------------------------------------------------- INSERT PROC DATA
				
				$this->flow .= " " . $this->procdata[ $procid ] . " ";
				
				$this->procdata[ $procid ] = '';
			}
			
			
			public function writeFinalFile( $title, $instructions, $author ){ // ----------------------------------------- WRITE FINAL FILE
				
				$this->finaljs = "<!DOCTYPE html>"
							   . "<html>"
							   
								. "<head>"
								. "<meta charset=\"UTF-8\">"
								. "<title>Programinute</title>"
								. "<style type=\"text/css\">"
								
									. "body{ margin:0; padding:0; min-width:900px; "
									
										  . "font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif; "
										  
										  . "background-color:#EFEFEF; } "
									
									. "header{ padding:20px 10px 20px 50px; min-height:18px; "
									
											. "font-family:Arial, 'Helvetica Neue', Helvetica, sans-serif; font-size:18px; "
											
											. "background-color:#FFFFFF; } "
									
									. "#main{ padding:50px 5%; min-height:400px;} "
									
									. "#form{ padding:20px 2%; background-color:#FFFFFF; border:1px solid #CCCCCC; } "
									
									. "#inputs{ display:inline-block; vertical-align:top; width:40%;  } "
									
									. "#instructions{ display:inline-block; vertical-align:top; width:40%; color:#333333; } "
									
									. "#author{ margin-top:20px; } "
									
									. "#xoutput{ padding:20px; font-size:14px; margin-top:20px; min-height:100px;"
									
											  . "background-color:#FFFFFF; border:1px solid #CCCCCC; } "
									
									. "h2{ font-size:18px; } "
									
									. "input{ padding:5px; } " 
									
									. "footer{ padding:50px; text-align:center; font-size:14px; } "
									
									. ".errorwrp{ padding:10px 20px; color:red; } "
									
									. ".formline{ padding:10px; } "
								
								. "</style>"
								. "<script type=\"text/javascript\"> "
								
									. "var xoutput = ''; "
								
									. "function getNumber(inum){ " // 0
									
										. "if( typeof(inum) == 'number' ){ return inum; }else{ " // 1
										
											. "if( isNaN(inum) ){ " // 2
											
												. "if( inum.indexOf(\",\") == -1 ){ " // 3
												
													. "return NaN; "
												
												. " }else{ var topt = inum.replace(\",\",\".\"); "
												
													. "if( isNaN(topt) ){ return NaN; " // 4
													
													. " }else{ return parseFloat(topt); } "
												
												. " } " // 3
											
											. " }else{ "
											
												. "return parseFloat(inum);"
											
											. " } " // 2
										
										. " } " // 1
									
									. " } " // 0
									
									. $this->outsec
									
									. "function exeCode(){ try{ "
									
										. $this->inpvar
									
										. $this->flow
										
										. " }catch(err){ var wrong; "
									
										. "if(err.name == 'ReferenceError'){ wrong = \"the program\"; }else{ wrong = \"your input or the program itself\"; } "
										
										. "xoutput = \"<div class=\\\"errorwrp\\\">ERROR: Something is wrong with \"+wrong+\"</div>\"; } "
										
										. "if( xoutput.length == 0 ){ "
										
											. "xoutput = \"<div class=\\\"errorwrp\\\">ERROR: No output</div>\"; } "
										
										. "document.getElementById('xoutput').innerHTML = xoutput; "
										
										. "xoutput = ''; "
									
									. " } "
									
								. " </script>"
								. "</head>"
								
								. "<body>"
								
									. "<header>" . $title . "</header>"
									
									. "<div id=\"main\">"
									
										. "<div id=\"form\">"
										
											. "<div id=\"inputs\">"
											
												. $this->inputs
											
												. "<div class=\"formline\"><input type=\"button\" value=\"Run\" onclick=\"exeCode();\" /></div>"
												
											. "</div>"
											
											. "<div id=\"instructions\">"
												
												. "<h2>Instructions</h2>"
												
												. "<div>" . $instructions . "</div>"
												
												. "<div id=\"author\" title=\"Author\">" . $author . "</div>"
											
											. "</div>"
										
										. "</div>"
										
										. "<div id=\"xoutput\"></div>"
									
									. "</div>"
									
									
									. "<footer style=\"padding-top:50px;\">"
									
										. "<strong>Made with programinute.com</strong> - "
										
										. "Compiled on " . date( "M j Y g:i a T" ) . " - Copyright 2016"
									
									. "</footer>"
								
								
								. "</body>"
								
							   . "</html>";
							   
			}
			
	}
	
	$jswrt = new JavascriptWriter();

?>
