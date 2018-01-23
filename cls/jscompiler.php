<?php

// Python Compiler - [ $py_comp ]

// Copyright 2015 Amir Hachaichi


	class PyCompiler {
		
		
			public $prescript;
			
			public $cdtlevel = array(); // [tblnum] Current CDT Level (Reset to false when condition ends)
			
			
			public $ocallcnt = 0; // Output call count
			
			public $curoutnum = false; // Current output called
			
			
			public $error;
			
			public $test;
			
			
			public function __construct( $psid ){ // Set inputs
				
				$this->prescript = $psid;
				
				
			}
			
			
			public function inputText( $dbdata ){ // --------------------------------------------------------------- INPUT TEXT
				
				global $pywrt;
				global $guiwrt;
				
				$pywrt->addInput( $dbdata['vnum'] );
				
				$guiwrt->inputText( $dbdata['vnum'],$dbdata['title'] );
				
				
				
			}
			
			
			public function startOutputText( $num ){ // ----------------------------------------------------- START OUTPUT TEXT
				
				global $tbi_out_txt;
				global $pywrt;
				
				$pywrt->startFunction( 'text_output_'.$num,'ovarr' );
				
				$pywrt->output( "<div style=\"padding:20px 10px;\">" ); // Output call ?
			}
			
			
			public function startOutputBlock(){ // ---------------------------------------------------------- START OUTPUT BLOCK
				
				global $pywrt;
				
				$pywrt->output( "<p>" );
				
			}
			
			
			public function endOutputBlock(){ // -------------------------------------------------------------- END OUTPUT BLOCK
				
				global $pywrt;
				
				$pywrt->output( "</p>" );
			}
			
			
			public function endOutputText(){ // ---------------------------------------------------------------- END OUTPUT TEXT
				
				global $pywrt;
				
				$pywrt->output( "</div>" );
				
				$pywrt->leveldec();
			}
			
			
			/*public function initProcOutvarDict(){ // ----------------------------------------------------------------- INIT PVARR
				global $pywrt;
				$pywrt->newline( 'pvarr = {}' );
			}*/
			
			
			public function linkedOvar( $onum,$ovnum,$srcvar ){ // --------------------------------------------------- LINKED OVAR
				
				global $pywrt;
				
				if( $this->curoutnum === false ){
					
					$this->curoutnum = $onum;
					
					$pywrt->newline( "outcall_" . $this->ocallcnt . " = ['']" );
					
				}
				
				// Building the ovar array
				$pywrt->newline( 'outcall_' . $this->ocallcnt . '.insert(' . $ovnum . ',' . $srcvar . ')' );
				
			}
			
			
			public function outputCall(){ // ------------------------------------------------------------------ OUTPUT CALL
				
				global $pywrt;
				
				
				// Call the function
				$pywrt->newline( 'text_output_'.$this->curoutnum.'(outcall_'.$this->ocallcnt.')' );
				
				// Next
				$this->ocallcnt++;
				$this->curoutnum = false;
			}
			
			
			public function conditionTable( $tblid,$num ){ // ------------------------------------------------------- CDT HEADER
				
				global $pywrt;
				
				$cdtable = new ConditionTable( $this->prescript,$tblid );
				
				$pywrt->load_cdt_table( $num,$cdtable->ifvar_data,$cdtable->col_letter );
				
			}
			
			
			public function startCdtSwitch( $tblid ){ // ------------------------------------------------------------ CDT SWITCH
				
				
				$this->cdtlevel[$tblid] = array();
				
			}
			
			
			public function startCondition( $tblid,$tblnum,$letter,$ctype,$level ){ // --------------------------- CDT CONDITION
				
				global $pywrt;
				
				if( $ctype == 'if' ){
					
					if( in_array( $level,$this->cdtlevel[$tblid] ) ){ // Same level: ELSE IF
						
						$pywrt->startElseIf( 'cdt'.$tblnum.'_'.$letter.'top' );
					}
					else{ // IF
						
						$pywrt->startIf( 'cdt'.$tblnum.'_'.$letter.'top' );
						
						$this->cdtlevel[$tblid][] = $level;
					}
					
				}
				else if( $ctype == 'else' ){
					
					$pywrt->startElse();
				}
				
				
			}
			
			
			public function endCondition( $level ){ // ---------------------------------------------------------- END CONDITION
				
				global $pywrt;
				
				$pywrt->newline('pass'); // in case empty
				$pywrt->leveldec();
			}
			
			
			public function procInVar( $pid,$invarr ){ // ------------------------------------------------------- PROC INVAR
				
				global $pywrt;
				
				$invflat = "pinvarr_" . $pid . " = {";
				
				foreach( $invarr as $inv )
				{
					$invflat .= "'" . $inv[1] . "':" . $inv[1] . "," ;
				}
				
				$invflat .= "}";
				
				$pywrt->newline( $invflat );
				
			}
			
			
			public function callProcessing( $pid ){ // -------------------------------------------------------- CALL PROCESSING
				
				global $pywrt;
				
				$pywrt->newline( "processing_" . $pid . "(pinvarr_" . $pid . ")" );
			}
			
			
			public function procNewPvar( $pvnum ){ // -------------------------------------------------------------- NEW PVARR
				
				global $pywrt;
				
				$pywrt->newline("p" . $pvnum . " = pvarr['" . $pvnum . "']" );
				
			}
			
	}



?>