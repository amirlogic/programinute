<?php

// Switch Organizer - CDT Switch [ $sworg ] 

// Copyright 2015-2016 Amir Hachaichi

/*
# Init list
#	
#	$doit->cdtSwitch()
#
#
#
#
*/
	

	class SwitchOrganizer extends TBIPrescriptMain {
		
		
		
		public $switchid;
		
		
		public $tblid;
		
		public $tblnum;
			
		public $col_orig;
			
		public $col_parent;
			
		public $col_letter;
			
		public $col_sub;
			
		public $col_endpos;
		
		public $col_ifelse;
		
		
		public $verticols;
		
		
		public $sw_pos = array(); // Switch position [swid][pos]
		
		public $swcol_cdtpos = array(); // [swid][letter]
		
		public $swcol_stack = array(); // Inside column
		
		public $swcol_sub = array(); // [pos][cdtpos][inpos]
		
		public $swcol_lastinpos = array(); // [swid][cdtpos][inpos]
		
		public $swcol_lastinsubpos = array(); // [swid][cdtpos][inpos] (Not ready)
		
		public $html; // To be used in action_return
		
		//public $lasterror = false;
		//public $error;
		
		
		
		public function __construct($psid){
			
			$this->prescript = $psid;
			$this->lasterror = false;
			
		}
		
		
		public function setSwitch( $swid ){ // ---------------------------------------------------------- SET SWITCH
			
			$this->switchid = $swid;
		}
		
		
		public function setTable( $tbnum ){ // ---------------------------------------------------------- SET TABLE
			
			global $dbz;
			global $userps;
			
			// LATER: Must use prepared query!!!
			
			$tbidq = $dbz->prepSelectAll( 'cdt_header', array( 	[ 'prescript', '=', $this->prescript, 'i' ],
																		
																[ 'user', '=', $userps->usrid, false ],
																		
																[ 'num', '=', $tbnum, 'i' ]				 ),
																	
																									false 	);
			
			/*$tbidq = $dbz->query( "SELECT `id` FROM `cdt_header` WHERE `prescript` = '" . $this->prescript . "' "
			
								. "AND `num` = '" . $tbnum . "' LIMIT 1;" );*/
								
			if( $row = $dbz->fetch_array( $tbidq ) ){
				
				$this->tblnum = $tbnum;
				$this->tblid = $row['id'];
			}
			else{
				
				$this->lasterror = 'tablenum';
			}
			
		}
		
		
		public function loadTable(){ // ------------------------------------------------------------- LOADS TABLE DATA
			
			if( $this->lasterror !== false ){ return false; }
			
			$cdt = new ConditionTable( $this->prescript, $this->tblid, false );
			
			
			$this->col_orig = $cdt->col_orig;
			
			$this->col_parent = $cdt->col_parent;
			
			$this->col_letter = $cdt->col_letter;
			
			$this->col_sub = $cdt->col_sub;
			
			$this->col_endpos = $cdt->col_endpos;
			
			$this->col_ifelse = $cdt->col_ifelse;
			
			$this->verticols = $cdt->verticols;
		}
		
		
		public function newSwitchColumns(){ // -------------------------------------- NEW SWITCH COLUMNS ( TABLE MUST BE LOADED )
			
			global $dbz; // For IDs
			
			if( $this->lasterror !== false ){ return false; }
			
			$code_display = new CodeDisplayer( $this->prescript );
			
			// HTML: Starting switch
			
			$this->switchid = $dbz->lastInsertId();
			
			$code_display->startCdtSwitch( $this->switchid, $this->tblnum, false );
			
			
			for( $s=0; $s<count( $this->verticols ); $s++ )
			{
				
				if( in_array( $this->verticols[$s][1],$this->col_orig ) ){ // Top level col
					
					if( $this->verticols[$s][0] == 'start' ){
						
						$this->addColStart( $this->tblid, $this->verticols[$s][2] );
						
						$this->nextCdtPos();
						
						$this->addStem( 'newinpos', $this->switchid );
						
						// HTML
						
						$code_display->startCondition( $this->tblnum, $this->verticols[$s][2], 0 );
						
						$code_display->html .= "</div><div class=\"master_stem_wrp\">";
						
						$code_display->html .= $code_display->newFlowInner( $dbz->lastInsertId(), $this->switchid, 
						
																			$this->verticols[$s][2] );
						$code_display->html .= "</div>";
						
					}
					else if( $this->verticols[$s][0] == 'end' ){
						
						$this->addColEnd( $this->tblid, $this->verticols[$s][2] );
						
						// HTML
						
						$code_display->endCondition( 0 );
					}
					
				}
				else{ // Nested (Not activated): Pre-cdts
					
					if( $this->verticols[$s][0] == 'start' ){
						
						$this->addColPrestart( $this->tblid, $this->verticols[$s][2] );
						
						$this->nextCdtPos();
						
						$this->addPreStem( $this->tblid, $this->verticols[$s][2] );
						
					}
					else if( $this->verticols[$s][0] == 'end' ){
						
						$this->addColpreend( $this->tblid,$this->verticols[$s][2] );
						
					}
				}
				
				$this->nextCdtPos();
			}
			
			// HTML: Ending switch and sending to $this->html
			
			$code_display->endCdtSwitch( $this->tblnum );
			
			$this->html = $code_display->html;
		}
		
		
		public function newSwitch( $stmid ){ // ----------------------------------------------------------------- NEW CDT SWITCH
			
			if( $this->lasterror !== false ){ return false; }
			
			$this->getStem( $stmid ); // Sets positions
			
			$this->stemClear();
			
			$this->startNewCdt(); // Switch is a cdt
			
			$this->addSwitchStart( $this->tblid );
			
			$this->nextCdtPos();
			
			$this->newSwitchColumns();
			
			//$this->nextCdtPos();
			
			$this->addSwitchEnd( $this->tblid );
			
			
			
			//$this->lasterror = $tbi_ps_main->lasterror;
		}
		
		
		public function loadSwitch( $swid ){ // ------------------------------------------------------------------- LOAD SWITCH
			
			global $dbz;
			global $userps;
			
			$this->setSwitch( $swid );
			
			// Getting switch position
			$swpos = $dbz->getVal('str_prescript_body', 'id', $this->switchid, 'pos', 'string');
			
			if($swpos === false){
				
				$this->lasterror = 'swpos';
				return false;
			}
			
			$this->sw_pos[$this->switchid] = $swpos;
			
			
			$lswq = $dbz->query(
			
								"SELECT * FROM `str_prescript_body` WHERE "
								
							  . "`pos` = '".$swpos."' ORDER BY `cdtpos` ASC, `inpos` ASC, `insubpos` ASC;"
								
								);
			
			/* $lswq = $dbz->prepSelectAll( 'str_prescript_body', array( 	[ 'prescript', '=', $this->prescript, 'i' ],
																		
																		[ 'user', '=', $userps->usrid, false ],
																		
																		[ 'target', '=', $this->outype, 's' ],

																		[ 'callid', '=', $this->callid, 'i' ] ),
																	
																array( 	[ 'pos','ASC' ] ) 	); */
			
			$curcol = false;
			
			$curcdtpos = false;
			
			$curinpos = false;
			
			$curinsubpos = false;
			
			$this->swcol_cdtpos[$this->switchid] = array();
			
			$this->swcol_stack[$this->switchid] = array();
			
			$this->swcol_lastinpos[$this->switchid] = array(); // [swid][cdtpos]
			
			$this->swcol_lastinsubpos[$this->switchid] = array(); // [swid][cdtpos][inpos]
			
			
			while( $row = $dbz->fetch_array( $lswq ) ){
				
				if( $row['cdtfunc'] == 'swstart' ){ // ---------------------------------------------------- SWITCH START
					 
					$lastcdtinpos = false;
					
				}
				else if( $row['cdtfunc'] == 'cdtstart' ){ // ------------------------------------------------- COL START
					
					$curcol = $row['cdtlink'];
					
					
					
					$this->swcol_cdtpos[$this->switchid][$row['cdtlink']] = $row['cdtpos']; // letter to cdtpos
					
					$this->swcol_stack[$this->switchid][$row['cdtpos']+1] = array();
					
					
					
					//$this->swcol_sub[$swpos]
					
				}
				else if( $row['cdtfunc'] == 'cdtprestart' ){ // -------------------------------------------
					
					
					
				}
				else if( $row['cdtfunc'] == 'cdtend' ){ // ---------------------------------------------------
					
					
					
				}
				else if( $row['cdtfunc'] == 'cdtpreend' ){ // ---------------------------------------------------
					
					
					
				}
				else if( $row['cdtfunc'] == 'swend' ){ // --------------------------------------------------- SWITCH END
					
					$this->swcol_lastinpos[$this->switchid][$curcdtpos] = $curinpos;
					
				}
				else if( in_array( $row['cdtfunc'],array( 'cmd','substart','subcmd','subend' ) ) ){ // ---
					
					$this->swcol_stack[$this->switchid][$row['cdtpos']][] = array(
																					$row['inpos'],
																			
																					$row['insubpos'],
																			
																					$row['cmd'],
																			
																					$row['cmdlink'] // ID
																			
																				);
					
					$curcdtpos = $row['cdtpos'];
					
					$curinpos = $row['inpos'];
					
					
					if( $row['cdtfunc'] == 'substart' ){
						
						$this->swcol_lastinsubpos[$this->switchid][$curcdtpos] = array();
						
					}
					else if( $row['cdtfunc'] == 'subcmd' ){
						
						
						
					}
					else if( $row['cdtfunc'] == 'subend' ){
						
						$this->swcol_lastinsubpos[$this->switchid][$curcdtpos][$curinpos] = $curinsubpos;
					}
					
					$curinsubpos = $row['insubpos'];
					
				}
				else{ // Error
					
				}
				
			}
			
		}
		
		
		
		
		public function addToSwitchColumn( $inside,$after,$cmd,$lid ){ // -------------------------- INSERT INTO SW COLUMN
			
			if( $this->lasterror !== false ){ return false; }
			
			
			
			
			// NE PAS OUBLIER D'INCREMENTER LE SUBEND§§
			
			
			
		}
		
		
		public function activateSubColumns( $inside ){ // ----------------------------------------------- ACTIVATE SUBCOLUMNS
			
			
		}
		
		
		
		
		
		public function rebuild( $tblid,$swid ){ // ---------------------------------------------------------- REBUILD SWITCH
			
			
		}
	
	}
	
	
	
	
?>
