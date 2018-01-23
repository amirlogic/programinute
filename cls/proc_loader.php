<?php

// Processing Loader [ $loadproc ] PROTECTED

// Copyright 2015-2016 Amir Hachaichi

/*
# Init list
#	
#	viewps.php
#	
#	
#	
#	
*/

	class ProcessingLoader {
		
		
		public $procid; // Processing ID
		
		public $invarr = array(); // [procid][]
		
		public $outvarr = array(); // [procid][]
		
		public $instmid = array(); // In-Stem ID
		
		public $opstmid = array(); // Oper-Stem ID
		
		public $outstmid = array(); // Out-Stem ID
		
		
		private $opbufferhdr; // Operation buffer header

		private $opbufferbody; // Operation buffer body
		
		
		public $lasterror = false;
		
		public $error;
		
		
		public function __construct( $psid,$mode ){
			
			global $dbz;
			global $userps;
			global $pywrt;
			global $disproc;
			global $jswrt;
			
			
			if( $mode == 'html' ){
				
				$disproc->html = '';
			}
			else if( $mode == 'python' ){
				
				// Initialize Procecced variables list
			}
			
			$procrr = $dbz->prepSelectAll( 'str_processing', array( 	[ 'prescript', '=', $psid, 'i' ],
																		
																		[ 'user', '=', $userps->usrid, false ]
																),
																array( 	[ 'proc','ASC' ], [ 'pos','ASC' ],
																
																		[ 'resnum','ASC' ], [ 'respos','ASC' ]	) 	);
			
			while( $row = $dbz->fetch_array( $procrr ) ){ // #=============================================================
				
				
				if( $row['target'] == 'start' ){ // ------------------------------------------------------------- START
					
					$this->procid = $row['proc'];
					
					$this->outvarr[ $row['proc'] ] = array(); // Needed in python too
					$this->invarr[ $row['proc'] ] = array();
					
					if( $mode == 'html' ){
						
						// Flow
						
						$this->instmid[ $row['proc'] ] = array();
						$this->opstmid[ $row['proc'] ] = array();
						$this->outstmid[ $row['proc'] ] = array();
						
						// Processing section
						
						$disproc->htmlProcStart( $row['proc'],true );
						
					}
					else if( $mode == 'python' ){
						
						$pywrt->startFunction( 'processing_'.$this->procid,'invarr' );
					}
					else if( $mode == 'javascript' ){
						
						$jswrt->procdata[ $row['proc'] ] = '';
					}
					
				}
				else if( $row['target'] == 'invar' ){ // -------------------------------------------------------- INVAR
					
					// Flow
					$this->invarr[ $row['proc'] ][] = array( $row['varid'],$row['vartxt']  );
					
					if( $mode == 'html' ){
						
						// ProcSec
						$disproc->htmlInVar( $row['vartxt'],true );
						
					}
					else if( $mode == 'python' ){
						
						//$disproc->html .= "<div class=\"flow_proc_wrp\">";
						
					}
					else if( $mode == 'javascript' ){
						
						$jswrt->procdata[ $row['proc'] ] .= "var pr" . $row['proc'] . $row['vartxt']
						
														  . " = " . $row['vartxt'] . "; ";
					}
					
					
				}
				else if( $row['target'] == 'instm' ){ // -------------------------------------------------------- INSTEM
					
					if( $mode == 'html' ){
						
						$this->instmid[ $row['proc'] ] = $row['id'];
														
						// No stem in ProcSec		
					}
					else if( $mode == 'python' ){
						
						
						
					}
					
				}
				else if( $row['target'] == 'operstart' ){ // -------------------------------------------------------- OPER START
					
					
					if( $mode == 'html' ){
						
						$disproc->htmlStartOperCont( $row['proc'] );
						
					}
					else if( $mode == 'python' ){
						
					}
				}
				else if( $row['target'] == 'operline' ){ // -------------------------------------------------------- OPER LINE
					
					if( empty( $row['operfunc'] ) ){ // ------------------------------------< Single Row <<<<<<<<<<<<<<<<<<<<<
						
						if( $mode == 'html' ){
		
							$disproc->startOperation( $row['proc'],$row['resnum'] );
							
							$disproc->singleRowOperation( $row['resnum'],$row['opertype'],$row['operheader'],
							
															$row['vartxt'],$row['operpin'] );
							
							$disproc->closeOperation();
							
						}
						else{ // -------------------------------------------------< Code
						
							if( $row['opertype'] == 'count' ){
							
								if( $row['operheader'] == 'allchar' ){ // ----------------------------------------- LENGTH
								
									if( $mode == 'python' ){
									
										$pywrt->newline( "r" . $row['resnum'] . " = len(str(invarr['" . $row['vartxt'] . "']))" );
									
									}
									else if( $mode == 'javascript' ){
									
										$jswrt->procdata[ $row['proc'] ] .= "var pr" . $row['proc'] . "r" . $row['resnum'] . " = "
									
																			. $row['vartxt'] . ".length; ";
									
									}
								
								}
								/*else if( $row['operheader'] == '' ){ // -------------------------------------/
								
								
								}*/
							}
							else if( $row['opertype'] == 'math' ){
							
								if( empty( $row['operfunc'] ) ){
								
									$inum = empty( $row['vartxt'] ) ? 
									
															$row['operpin'] 
																
																: $this->mathSource( true, $row['proc'], $row['vartxt'] );
								}
								
								$jswrt->procdata[ $row['proc'] ] .= "var pr" . $row['proc'] . "r" . $row['resnum'] . " = ";
								
								if( $row['operheader'] == 'log10' ){
								
									if( $mode == 'javascript' ){
									
										$jswrt->procdata[ $row['proc'] ] .= "Math.log(" . $inum . ")/Math.LN10; ";
									}
								}
								else if( $row['operheader'] == 'lnep' ){
									
									if( $mode == 'javascript' ){
										
										$jswrt->procdata[ $row['proc'] ] .= "Math.log(" . $inum . "); ";
									}
								}
								else if( $row['operheader'] == 'sqroot' ){
									
									if( $mode == 'javascript' ){
										
										$jswrt->procdata[ $row['proc'] ] .= "Math.sqrt(" . $inum . "); ";
									}
								}
								else if( $row['operheader'] == 'sinus' ){
									
									if( $mode == 'javascript' ){
										
										$jswrt->procdata[ $row['proc'] ] .= "Math.sin(" . $inum . "); ";
									}
								}
								else if( $row['operheader'] == 'cosinus' ){
									
									if( $mode == 'javascript' ){
										
										$jswrt->procdata[ $row['proc'] ] .= "Math.cos(" . $inum . "); ";
									}
								}
								else if( $row['operheader'] == 'tang' ){
									
									if( $mode == 'javascript' ){
										
										$jswrt->procdata[ $row['proc'] ] .= "Math.tan(" . $inum . "); ";
									}
								}
								else if( $row['operheader'] == 'exp' ){
									
									if( $mode == 'javascript' ){
										
										$jswrt->procdata[ $row['proc'] ] .= "Math.exp(" . $inum . "); ";
									}
								}
								else if( $row['operheader'] == 'round' ){
									
									if( $mode == 'javascript' ){
										
										$jswrt->procdata[ $row['proc'] ] .= "Math.round(" . $inum . "); ";
									}
								}
								else if( $row['operheader'] == 'absolute' ){
									
									if( $mode == 'javascript' ){
										
										$jswrt->procdata[ $row['proc'] ] .= "Math.abs(" . $inum . "); ";
									}
								}
								/*else if( $row['operheader'] == 'minus' ){
									
									if( $mode == 'javascript' ){
										
										$jswrt->procdata[ $row['proc'] ] .= "-" . $inum . "; ";
									}
								}*/
							
							}
							else if( $row['opertype'] == 'string' ){
							
								if( $row['operheader'] == 'substr' ){ // --------------------------------------/
								
									if( $row['operfunc'] == 'first' ){
									
						
										/*$disproc->html .= "<div><span class=\"inline_var\">r" . $row['resnum'] . "</span> = "
												
														. "First <strong>" . $row['operpin'] . "</strong> characters "
												
														. "of <span class=\"inline_var\">" . $row['vartxt'] . "</span></div>";*/
										
									
										if( $mode == 'python' ){
										
											$pywrt->newline( "r" . $row['resnum'] . " = invarr['" 
										
															. $row['vartxt'] . "'][:" . $row['operpin'] . "]" );
										
										}
										else if( $mode == 'javascript' ){
									
											$jswrt->procdata[ $row['proc'] ] .= "var pr" . $row['proc'] . "r" . $row['resnum'] 
										
																			. " = pr" . $row['proc'] . $row['vartxt'] 
																		  
																			. ".substr(0," . $row['operpin'] . "); ";
										}
									
												
									}
									else if( $row['operfunc'] == 'last' ){
									
									
											/*$disproc->html .= "<div><span class=\"inline_var\">r" . $row['resnum'] . "</span> = "
												. "Last <strong>" . $row['operpin'] . "</strong> characters "
												. "of <span class=\"inline_var\">" . $row['vartxt'] . "</span></div>";*/
										
									
										if( $mode == 'python' ){
										
											$pywrt->newline( "r" . $row['resnum'] . " = invarr['" 
										
															. $row['vartxt'] . "'][-" . $row['operpin'] . ":]" );
										}
										else if( $mode == 'javascript' ){
									
											$jswrt->procdata[ $row['proc'] ] .= "var pr" . $row['proc'] . "r" . $row['resnum'] 
										
																			. " = pr" . $row['proc'] . $row['vartxt'] 
																		  
																			. ".substr(-" . $row['operpin'] . "); ";
										}
									
									
									}
								
								}
							
							
							}
						
						}
					}
					else{ // --------------------------------------------------------------< MultiRow <<<<<<<<<<<<<<<<<<<<<<<<
						
						if( $row['operfunc'] == 'start' ){
							
							if( $mode == 'html' ){
													$disproc->startOperation( $row['proc'],$row['resnum'] );
							}
							
							$opmap = array( 					// true = fixed (assoc)
											'count' => array(),
											
											'math' => array(
																'plus' => true, 'minus' => true, 'multiply' => true,
																'divide' => true, 'power' => true, 'root' => true
														)
										);
							
							$this->opbufferhdr = array( $row['proc'],$row['resnum'],$row['opertype'],$row['operheader'],
							
														$opmap[ $row['opertype'] ][ $row['operheader'] ] );
						}
						else if( $row['operfunc'] == 'end' ){
							
							$this->flushOperationBuffer( $mode );
							
							if( $mode == 'html' ){
													$disproc->closeOperation(); // <!> Must be after buffer flush
							}
						}
						else{
								if( !empty( $row['vartxt'] ) && empty( $row['operpin'] ) ){	// True Var
									
									$tv = true;	$vtxt = $row['vartxt'];
								}
								else if( empty( $row['vartxt'] ) && !empty( $row['operpin'] ) ){ // Constant
									
									$tv = false;	$vtxt = $row['operpin'];
								}
							
							$this->addToOperationBuffer( $row['operfunc'],$vtxt,$tv );
						}
					}
				}
				else if( $row['target'] == 'opstm' ){ // -------------------------------------------------------- OPER STEM
					
					if( $mode == 'html' ){
						
						$this->opstmid[ $row['proc'] ] = $row['id'];
						
						$disproc->htmlOperStem( $row['id'] );
						
					}
					
					
				}
				else if( $row['target'] == 'operend' ){ // -------------------------------------------------------- OPER END
					
					if( $mode == 'html' ){
						
						$disproc->htmlEndOperCont( $row['proc'] );
						
					}
					else if( $mode == 'python' ){
						
						
						
					}
					
				}
				else if( $row['target'] == 'outvar' ){ // ------------------------------------------------------- OUT VAR
					
					$this->outvarr[ $row['proc'] ][] = array( $row['varid'], $row['outnum'], $row['ctxt'] );
					
					if( $mode == 'html' ){
						
						$disproc->htmlOutVar( $row['outnum'],$row['resnum'],$row['ctxt'],true,false );
						
					}
					else if( $mode == 'python' ){
						
						if( $row['resnum'] != 0 ){
							
							$pywrt->newline( "pvarr['" . $row['outnum'] . "'] = r" . $row['resnum'] );
						}
						
					}
					else if( $mode == 'javascript' ){
						
						if( !empty( $row['resnum'] ) ){
							
							$jswrt->procdata[ $row['proc'] ] .= "var p" . $row['outnum'] 
						
														      . " = pr" . $row['proc'] . "r" . $row['resnum'] . "; ";
						}
						
					}
					
				}
				else if( $row['target'] == 'outstm' ){ // ------------------------------------------------------- OUT STM
					
					if( $mode == 'html' ){
						
						$this->outstmid[ $row['proc'] ] = $row['id'];
						
						// No outvar stem in ProcSec
						
					}
					else if( $mode == 'python' ){
						
						
						
					}
					
				}
				else if( $row['target'] == 'end' ){ // ---------------------------------------------------------- END
					
					if( $mode == 'html' ){
						
						$disproc->htmlProcEnd( true );
						
					}
					else if( $mode == 'python' ){
						
						$pywrt->leveldec(); // End processing function
						
					}
					
				}
				
				
			}
			
		}
		
		
		/*public function initOperationBuffer( $rnum,$type,$header,$fixed ){ // ----------------------- INIT OPERATION BUFFER
			
			$this->opbufferhdr = array( $rnum,$type,$header,$fixed );
		}*/


		public function addToOperationBuffer( $opfunc,$val,$truevar ){ // ------------------------- ADD TO OPERATION BUFFER 
			
			if( $this->opbufferhdr[4] ){ // Fixed number: Associative
				
				$this->opbufferbody[ $opfunc ] = array( $truevar,$val );
				
			}
			else{ // Numeric
				
				$this->opbufferbody[] = array( $opfunc,$truevar,$val );
				
			}
			
			
			
		}

		
		public function mathSource( $isvar,$prnum,$invar ){ // -------------------------------------------------------- MATH SOURCE
			
			return ( $isvar ) ? "getNumber(pr".$prnum.$invar.")" : $invar ; // ? Var : Cst_Num
		}
		
		
		public function flushOperationBuffer( $mode ){ // ------------------------------------------- FLUSH OPERATION BUFFER
			
			global $disproc;
			global $jswrt;
			
			if( $mode == 'html' ){
				
				$disproc->multiRowOperation( $this->opbufferhdr[1],$this->opbufferhdr[2],$this->opbufferhdr[3],

										 	 $this->opbufferbody );
			}
			else{ // Code
				
				if( $this->opbufferhdr[2] == 'count' ){
					
					
				}
				else if( $this->opbufferhdr[2] == 'math' ){
					
					if( in_array( $this->opbufferhdr[3],array('plus','minus','multiply','divide') ) ){ // ----- PLUS (+)
						
						$operatorr = array( 'plus'=>'+', 'minus'=>'-', 'multiply'=>'*', 'divide'=>'/' );
					
						if( $mode == 'javascript' ){
					
							$x = $this->mathSource( $this->opbufferbody['x'][0], $this->opbufferhdr[0], $this->opbufferbody['x'][1] );
						
							$y = $this->mathSource( $this->opbufferbody['y'][0], $this->opbufferhdr[0], $this->opbufferbody['y'][1] );
					
							$jswrt->procdata[ $this->opbufferhdr[0] ] .= "var pr" . $this->opbufferhdr[0] 
						
																	   . "r" . $this->opbufferhdr[1] . " = "
						
																	   . $x . $operatorr[ $this->opbufferhdr[3] ] . $y . "; ";
						}
					}
					else if( $this->opbufferhdr[3] == 'power' ){
						
						if( $mode == 'javascript' ){
							
							$x = $this->mathSource( $this->opbufferbody['x'][0], $this->opbufferhdr[0], $this->opbufferbody['x'][1] );
							
							$y = $this->mathSource( $this->opbufferbody['y'][0], $this->opbufferhdr[0], $this->opbufferbody['y'][1] );
							
							$jswrt->procdata[ $this->opbufferhdr[0] ] .= "var pr" . $this->opbufferhdr[0] 
						
																	   . "r" . $this->opbufferhdr[1] . " = "
																	   
																	   . "Math.pow(".$x.",".$y."); ";
						}
						
					}
					else if( $this->opbufferhdr[3] == 'root' ){
						
						if( $mode == 'javascript' ){
							
							$x = $this->mathSource( $this->opbufferbody['x'][0], $this->opbufferhdr[0], $this->opbufferbody['x'][1] );
							
							$y = $this->mathSource( $this->opbufferbody['y'][0], $this->opbufferhdr[0], $this->opbufferbody['y'][1] );
							
							$jswrt->procdata[ $this->opbufferhdr[0] ] .= "var pr" . $this->opbufferhdr[0] 
						
																	   . "r" . $this->opbufferhdr[1] . " = "
																	   
																	   . "Math.pow(".$x.",1/".$y."); ";
						}
						
					}
					
				}
				
				
				
			}
			
			
				
		}
		
	}
	
	
	
?>
