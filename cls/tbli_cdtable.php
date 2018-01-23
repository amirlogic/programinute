<?php

// Table Interface - Condition Table [$tbi_cdt]		PROTECTED

// Copyright 2015-2016 Amir Hachaichi

/*
# Init list
#	
#	$doit->cdtHeader()
#
#
#
#
*/


	class TBIConditions {
		
		public $error;
		
		public $prescript;
		
		public $tblid;
		
		public $posgap = 0; // Number of positions to add
		
		private $nxtpos; // Next position

		public $newcolid;
		
		public $tablenum;
		
		
		public $col_orig;
		
		public $col_parent;
		
		public $col_letter;
		
		public $col_sub;
		
		
		public $col_stack; // Linear array [colpos] => array()
		
		public $col_dbpos;
		
		
		public $col_endpos;
		
		public $col_andor;
		
		public $col_ifelse;
		
		public $grp_level;
		
		public $grp_parent;
		
		//public $grp_data;
		
		public $grp_endpos;
		
		
		public $curcolpos; // Current column db position
		
		
		public $edithtml;
		
		public $showhtml;
		
		
		private $alphabet = array( 'a','b','c','d','e','f','g','h','i','j','k','l','m','n','o',
		
									'p','q','r','s','t','u','v','w','x','y','z' );
		
		private $newcolletter;
		
		public $errortxt;
		
		public $lasterror = false;
		
		
		public function __construct( $psid ){ // ---------------------------------------------------------<
			
			$this->prescript = $psid;
			
		}
		

		public function setAttributes( $attrr ){ // ---------------------------------------------------------- SET ATTRIBUTES
				
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
		
		
		public function setCurColumn( $letter ){ // ------------------------------------- SET CURRENT COLUMN POSITION AND LETTER
			
			if( $this->lasterror !== false ){ return false; }
			
			$colid = array_search( $letter, $this->col_letter );
			
			if( $colid === false ){ // Column does not exist
				
				$this->lasterror = 'colnotfound';
				return false;
			}
			
			$this->curcolpos = $colid;
		}
		
		
		public function stackLooper( $col,$output,$stopat ){ // -------------------------------------- STACK LOOPER
			
			$curlevel = 0;
			
			$stackpos = array( 0 );
			
			$code_display = new CodeDisplayer( $this->prescript );
			
			for( $s=0; $s<count( $this->col_stack[ $col ] ); $s++ )
			{
				// ANDOR
				
				if( $stackpos[ $curlevel ] > 0 ){
					
					if( $this->col_andor[ $col ] === false ){
						
						
					}
					else{ // Show AndOr input
						
						if( $output == 'edhtml' ){
							
							$this->edithtml .= "<div>" . strtoupper( $this->col_andor[ $col ] ) . "</div>";
						}
						else if( $output == 'tbhtml' ){
							
							$this->showhtml .= "<div>" . strtoupper( $this->col_andor[ $col ] ) . "</div>";
						}
					}
				}
				
				if( $this->col_stack[ $col ][ $s ][0] == 'cdt' ){
					
					if( $output == 'edhtml' ){
						
						$this->edithtml .= $code_display->cdtHeaderCondition( 
						
																	$this->col_stack[ $col ][ $s ][1],
																	$this->col_stack[ $col ][ $s ][2],
																	$this->col_stack[ $col ][ $s ][3],
																	$this->col_stack[ $col ][ $s ][4],
																	$this->col_stack[ $col ][ $s ][5] 	);
					}
					else if( $output == 'tbhtml' ){
						
						$this->showhtml .= $code_display->cdtHeaderCondition( 
						
																	$this->col_stack[ $col ][ $s ][1],
																	$this->col_stack[ $col ][ $s ][2],
																	$this->col_stack[ $col ][ $s ][3],
																	$this->col_stack[ $col ][ $s ][4],
																	$this->col_stack[ $col ][ $s ][5] 	);
					}
					
					$stackpos[ $curlevel ]++;
				}
				else if( $this->col_stack[ $col ][ $s ][0] == 'sub' ){
					
					if( $this->col_stack[ $col ][ $s ][1] == 'start' ){
						
						$stackpos[ $curlevel ]++;
						
						$curlevel++;
						
						$stackpos[ $curlevel ] = 0;
						
					}
					else if( $this->col_stack[ $col ][ $s ][1] == 'end' ){
						
						unset( $stackpos[ $curlevel ] );
						
						$curlevel--;
						
					}
				}
				
				
			}
		}
		
		
		public function loadColumn( $letter ){ // ---------------------------------------------------------------- LOAD COLUMN
			
			// Table must be loaded
			
			$this->setCurColumn( $letter );
			
			if( $this->lasterror !== false ){ return false; }
			
			if( $this->col_ifelse[ $this->curcolpos ] == 'else' ){
				
				$this->errortxt = "else columns can't be edited";
				
				$this->lasterror = 'elsecol';		return false;
			}
			
			$this->edithtml = $this->columnEditorHeader( $letter );
			
			$this->writeColumnEditor();
			
			
			
			
		}

		
		public function columnEditorHeader( $letter ){ // ------------------------------------------------- COLUMN EDITOR HEADER
			
			return "<div class=\"cdtheader_coledit_hdr\">Column <strong>" . strtoupper( $letter ) . "</strong> editor</div>";
		}
		

		public function printCondInput( $andor,$coletter,$inpid,$action,$stack ){ // --------------------------- CONDITION INPUT
			
			$sendatarr = array(	
								PRESCRIPTID => $this->prescript,
																
								'action' => $action,
																		
								CDT_HEADER_ID => $this->tblid,

								CDT_HDR_COL_LETTER => $coletter,
								
								CDT_HDCOL_CDT_STACK => $stack
							);
			
			$inpdatarr = array(				
								[ CDT_HDCOL_CDT_PFUNC, 'cdthd_' . $this->tblid . '_coledit_' . $inpid . '_prefunc' ],
																									
								[ CDT_HDCOL_CDT_TARGET, 'cdthd_' . $this->tblid . '_coledit_' . $inpid . '_target' ],
																									
								[ CDT_HDCOL_CDT_LINK, 'cdthd_' . $this->tblid . '_coledit_' . $inpid . '_oper' ],
																			
								[ CDT_HDCOL_CDT_VALUE, 'cdthd_' . $this->tblid . '_coledit_' . $inpid . '_value' ],
																									
								[ CDT_HDCOL_CDT_VPARSE, 'cdthd_' . $this->tblid . '_coledit_' . $inpid . '_vparse' ]
							);																											
			
			if( $andor ){ // ---------------------------------------------------------------------------------------------
				
				$inpdatarr[] = [  CDT_HDCOL_CDT_ANDOR, 'cdthd_' . $this->tblid . '_coledit_' . $inpid . '_andor' ];
			}
			else{
				
				$sendatarr[ CDT_HDCOL_CDT_ANDOR ] = false;
			}
			
			$htout = '';
			
			if( $andor ){
				
				$htout .= "<div><select"
										. $this->setAttributes( array(
										
																[ 'id','cdthd_' . $this->tblid . '_coledit_' . $inpid . '_andor' ] 
															) )
										. ">" 
										
										. $this->printElement( 'option','AND',array(
																					[ 'value','and' ]
																		) )
										
										. $this->printElement( 'option','OR',array(
																					[ 'value','or' ]
																		) )
									. "</select></div>";
			}
			
			$htout .= "<div><select"
								. $this->setAttributes( array( 
																[ 'id','cdthd_' . $this->tblid . '_coledit_' . $inpid . '_prefunc' ] 
															) )
							. ">" 
												
								. $this->printElement( 'option','',array(
																			[ 'value','' ]
																		) )
																		
								. $this->printElement( 'option','Length of',array(
												
																			[ 'value','length' ]
																		) )
							. "</select>"
									
					. $this->printElement( 'input',null,array(
															
															[ 'id','cdthd_' . $this->tblid . '_coledit_' . $inpid . '_target' ],
															
															[ 'type','text' ], [ 'size','6' ],
															
															//[ 'style','margin-right:10px' ],
															
															[ 'placeholder','Check' ]
														) )
					. "<select"
								. $this->setAttributes( array( 
																	[ 'id','cdthd_' . $this->tblid . '_coledit_' . $inpid . '_oper' ],

																	[ 'style','margin:0 20px;' ]
																) )
								. ">" 
										. $this->printElement( 'option','=',array(
																						[ 'value','=' ]
																		) )
										. $this->printElement( 'option','&gt;',array(
																						[ 'value','>' ]
																		) )
										. $this->printElement( 'option','&lt;',array(
																						[ 'value','<' ]
																		) )
										. $this->printElement( 'option','&le;',array(
																						[ 'value','<=' ]
																		) )
										. $this->printElement( 'option','&ge;',array(
																						[ 'value','>=' ]
																		) )
									. "</select>"
									
					. $this->printElement( 'input',null,array(
															
															[ 'id','cdthd_' . $this->tblid . '_coledit_' . $inpid . '_value' ],
															
															[ 'type','text' ], [ 'size','15' ],
															
															[ 'placeholder','Value or Variable' ]
															
															//[ 'style','margin:0 20px' ]
															
															) )
					. "<select"
								. $this->setAttributes( array( 
																	[ 'id','cdthd_' . $this->tblid . '_coledit_' . $inpid . '_vparse' ] 
																) )
								. ">" 
												
					. $this->printElement( 'option','Value',array(
																		[ 'value','cst' ] 
																) )
																		
					. $this->printElement( 'option','Variable',array(
																		[ 'value','var' ] 
																	) )
								. "</select>"
									
					. $this->printElement( 'input',null,array(
															
															[ 'id','cdthd_' . $this->tblid . '_coledit_' . $inpid . '_submit' ],
															
															[ 'type','button' ], [ 'value','Add Condition' ],
															
															[ 'style','margin:0 20px' ],
															
															[ 'data-directpost',urlencode(json_encode(array(
																	
																	'senddata' => $sendatarr,'inputdata' => $inpdatarr	))) ],
																										
															[ 'onclick','attReader(this.id);' ]
															
															) )
					. "</div>";
			
			return $htout;
		}
		
		
		public function writeColumnEditor(){ // ------------------------------------------------- WRITE COLUMN EDITOR
			
			
			
			if( empty( $this->col_stack[ $this->curcolpos ] ) ){
				
				$this->edithtml .= "<div style=\"padding:10px 0;\">" 
				
								 . $this->printCondInput( false,$this->col_letter[ $this->curcolpos ],
										
																	'leader',CDT_HDR_ADDCDT_TOPSTK,'top' ) . "</div>";
			}
			else{
				
				$this->stackLooper( $this->curcolpos,'edhtml',null );
				
				if( $this->col_andor[ $this->curcolpos ] === false ){ // No AndOr
				
					$this->edithtml .= "<div style=\"padding:10px 0;\">" 
					
									 . $this->printCondInput( true,$this->col_letter[ $this->curcolpos ],
										
																	'leader',CDT_HDR_ADDCDT_TOPSTK,'top' ) . "</div>";
				}
				else{ // AndOr set: Input not needed
				
					$this->edithtml .= "<div>" . strtoupper( $this->col_andor[ $this->curcolpos ] ) . "</div>"
				
									 . "<div style=\"padding:10px 0;\">"
									
									 . $this->printCondInput( false,$this->col_letter[ $this->curcolpos ],
										
																	'leader',CDT_HDR_ADDCDT_TOPSTK,'top' ) . "</div>";
				}
			}
		}
		
		
		public function writeColumnContent(){ // ------------------------------------------- WRITE COLUMN EDITOR
			
			$this->showhtml = '';
			
			$this->stackLooper( $this->curcolpos,'tbhtml',null );
		}
		

		public function insertCdtLine( $coltag,$target,$prefunc,$link,$val,$vparse ){ // - Insert a condition / operator / group
			
			global $dbz;
			global $userps;
			
			if( $this->lasterror !== false ){ return false; }
			
			$nwclnq = $dbz->prepInsertInto( 'cdt_cases',
			
									array(	[ '',false ], // id (AI)
											
											[ $this->prescript,'i' ], // Prescript
											
											[ $userps->usrid,false ], // user
											
											[ $this->tblid,'i' ], // ID
											
											[ 0,false ], // devpos
											
											[ $this->nxtpos,false ], // pos
											
											[ $coltag,false ],
											
											[ 'na',false ],
											
											[ '',false ],
											
											[ $target,'s' ], [ $prefunc,'s' ], // target - prefunc
											
											[ $link,'s' ], // link
											
											[ $val,'s' ], [ $vparse,'s' ] // linkval - vparse
										)
									);
			if( !$nwclnq ){
				
				$this->lasterror = 'inscdtline';
				return false;
			}
		}
		

		public function addMainStackCdt( $andor,$prefunc,$target,$link,$val,$vparse ){ // ---------------------------- ADD CDT
		
			if( $this->lasterror !== false ){ return false; }
			
			
			// Insert Position (Must be before subcolumns)
			
			if( empty( $this->col_sub[ $this->curcolpos ] ) ){ // No subcolumns: Insert before colend
				
				$inspos = $this->col_endpos[ $this->curcolpos ];
			}
			else{ // Col has subs
				
				reset( $this->col_sub[ $this->curcolpos ] );
				$inspos = current( $this->col_sub[ $this->curcolpos ] ); // First sub colstart
				
			}
			
			if( empty( $this->col_stack[ $this->curcolpos ] ) ){
				
				$this->posgap = 1;
				
				$this->clearPos( $inspos );
			
			}
			else{
				
				$this->posgap = 2;
				
				$this->clearPos( $inspos );
				
				if( $this->col_andor[ $this->curcolpos ] === false ){
					
					$coltg = 'op' . $andor;
					
					$this->col_andor[ $this->curcolpos ] = $andor;
				}
				else{
					
					$coltg = 'op' . $this->col_andor[ $this->curcolpos ];
				}
				
				$this->insertCdtLine( $coltg,'','','','','' );
				
				$this->nxtpos++;
			}
			
			$this->insertCdtLine( 'cdt',$target,$prefunc,$link,$val,$vparse );
			
			if( $this->lasterror === false ){
					
				$this->col_stack[ $this->curcolpos ][] = array( 'cdt',$prefunc,$target,$link,$val,$vparse );
					
				$this->edithtml = $this->columnEditorHeader( $this->col_letter[ $this->curcolpos ] );
			
				$this->writeColumnEditor();
				
				$this->writeColumnContent();
			}
		}
		

		public function clearPos( $pos ){ // --------------------------------------------------------------------< CLEARPOS
			
			global $dbz;
			
			if( $this->lasterror !== false ){ return false; }
			
			//$this->newpos = $pos;
			
			//$cpq = $dbz->prepUpdate( 'cdt_cases', $setrr, $wherr );
			
			if( !is_numeric( $this->prescript ) ){ // Temporary protection
																			return false;
			}
			
			$cpq = $dbz->prepNumUpdate( 'cdt_cases', array( [ 'pos', '+', $this->posgap ] ), 
			
																	array(	[ 'prescript', '=', $this->prescript, 'i' ],
																			
																			[ 'cdtable', '=', $this->tblid, 'i' ],
																			
																			[ 'pos', '>=', $pos, false ]
																														) );
			if( !$cpq ){
				
				$this->lasterror = 'clearpos gap:'.$this->posgap.' pos:'.$pos;
				print_r($this->col_sub);
				return false;
			}
			else{
				
				$this->posgap = 0; // Reset
				$this->nxtpos = $pos;
				return true;
			}
		}
		
		
		public function loadTable( $id ){ // ------------------------------------------------------------------- LOADS TABLE DATA
			
			if( !is_numeric( $this->prescript ) ){ // Temporary protection
																			return false;
			}
			
			$cdt = new ConditionTable( $this->prescript,$id,false );
			
			
			$this->tblid = $id;
			
			$this->col_orig = $cdt->col_orig;
			
			$this->col_parent = $cdt->col_parent;
			
			$this->col_letter = $cdt->col_letter;
			
			$this->col_sub = $cdt->col_sub;
			
			
			
			$this->col_stack = $cdt->col_stack; // Linear array [colpos] => array()
		
			$this->col_dbpos = $cdt->col_dbpos;
			
			$this->col_endpos = $cdt->col_endpos;
			
			$this->grp_parent = $cdt->grp_parent;
			
			
			
			$this->grp_endpos = $cdt->grp_endpos;
			
			$this->col_andor = $cdt->col_andor;
			
			$this->grp_level = $cdt->grp_level;
			
			$this->col_ifelse = $cdt->col_ifelse;
		}
		
		
		public function insColstart( $letter,$type ){ // ----------------------------------------------------- COL START
			
			global $dbz;
			global $userps;
			
			if( $this->lasterror !== false ){ return false; }
			
			$clstq = $dbz->prepInsertInto( 'cdt_cases',
			
									array(	[ '',false ], // id (AI)
											
											[ $this->prescript, 'i' ], // Prescript
											
											[ $userps->usrid,false ], // user
											
											[ $this->tblid,'i' ], // ID
											
											[ 0,false ],
											
											[ $this->nxtpos,false ], // pos
											
											[ 'colstart',false ],
											
											[ $type,'s' ],
											
											[ $letter,'s' ],
											
											[ '',false ], [ '',false ], [ '',false ], [ '',false ], [ '',false ]
										)
									);
			if( !$clstq ){
				
				$this->lasterror = 'colstart_'.$letter;
				return false;
			}
			
			$this->nxtpos++;
			$this->newcolid = $dbz->lastInsertId();
		}
		
		
		public function insColend( $letter ){ // ---------------------------------------------------------------- COL END
			
			global $dbz;
			global $userps;
			
			if( $this->lasterror !== false ){ return false; }
			
			$cledq = $dbz->prepInsertInto( 'cdt_cases',
			
									array(	[ '',false ], // id (AI)
											
											[ $this->prescript, 'i' ], // Prescript
											
											[ $userps->usrid,false ], // user
											
											[ $this->tblid,'i' ], // ID
											
											[ 0,false ],
											
											[ $this->nxtpos,false ], // pos
											
											[ 'colend',false ],
											
											[ 'na',false ],
											
											[ $letter,'s' ],
											
											[ '',false ], [ '',false ], [ '',false ], [ '',false ], [ '',false ]
										)
									);
				
			if( !$cledq ){
				
				$this->lasterror = 'colend_'.$letter;
				return false;
			}
			
			$this->nxtpos++;
		}
		
		
		private function newColumnLetter( $rowrr ){ // --------------------------------------------------------- NEW COLUMN LETTER
			
			$lastifind = count( $rowrr )-2; // Position of last if column
			
			$lastletter = $this->col_letter[ $rowrr[$lastifind] ];

			if( strlen( $lastletter ) > 1){
				
				$ltrlook = substr( $lastletter,-1 );
			}
			else{
				
				$ltrlook = $lastletter;
			}
			
			$num = array_search( $ltrlook,$this->alphabet );
			
			$num++;
			
			$this->newcolletter = $this->alphabet[$num];
		}
		
		
		public function addNewSubColumn( $inside ){ // ----------------------------------------------------- Add New Subcolumn 
			
			if( $this->lasterror !== false ){ return false; }
			
			$colid = array_search( $inside, $this->col_letter );
			
			if( $colid === false ){ // Column does not exist
				
				$this->lasterror = 'colnotfound';
				return false;
			}
			
			if( empty( $this->col_sub[ $colid ] ) ){ // No subcols: New Row
				
				$this->newRow( $colid );
			}
			else{ // Just add a column
				
				$this->newColumn( $colid );
			}
			
			
		}		
		
		
		public function newColumn( $inside ){ // --------------------------------------------------------- Adds a column to a row
			
			global $dbz;
			
			if( $this->lasterror !== false ){ return false; }
			
			// Table must be Loaded
			
			if( $inside === false ){ // ---------------------------------------------- Top Level
				
				$this->newColumnLetter( $this->col_orig );
				
				$this->posgap = 2; // For Colstart + Colend
				
				$newpos = end( $this->col_orig ); // Z col position
				
				$this->clearPos( $newpos );
				
				$this->insColstart( $this->newcolletter,'if' );
				
				$this->insColend( $this->newcolletter );
				
			}
			else{ // ------------------------------------------------------------- Inside another column
				
				/*$parcol = array_search( $inside,$this->col_letter ); // Get parent col position
				if($parcol === false){ // Column not found
					$this->lasterror = 'parcol';return false;}*/
				
				$this->newColumnLetter( $this->col_sub[ $inside ] );
				
				$newcolltr = $this->col_letter[ $inside ] . $this->newcolletter;
				
				//$firstletter = $this->col_letter[ $this->col_parent[$colpos] ]; // Parent column letter(s)
				
				$this->posgap = 2; // For Colstart + Colend
				
				$newpos = end( $this->col_sub[ $inside ] ); // Z col pos
				
				$this->clearPos( $newpos );
				
				$this->insColstart( $newcolltr,'if' );
				
				$this->insColend( $newcolltr );
			}
		}
		
		
		public function newRow( $inside ){ // -------- Creates the two first columns ( A and Z ) -------------- NEW ROW
			
			global $dbz;
			
			if( $this->lasterror !== false ){ return false; }
			
			
			if( $inside === false ){ // New Tables
				
				$this->nxtpos = 1;
				
				$this->insColstart('a','if');
				
				$this->insColend('a');
				
				$this->insColstart('z','else');
				
				$this->insColend('z');
				
				
			}
			else{ // Sub: $inside = Column ID
				
				// First find colend position
				
				$this->posgap = 4;
				
				$this->clearPos( $this->col_endpos[ $inside ] );
				
				
				$this->insColstart( $this->col_letter[ $inside ].'a','if' );
				
				$this->insColend( $this->col_letter[ $inside ].'a' );
				
				$this->insColstart( $this->col_letter[ $inside ].'z','else' );
				
				$this->insColend( $this->col_letter[ $inside ].'z' );
				
			}
			
		}
		
		
		public function clearColumn( $letter ){ // -------------------------------------------------------- CLEAR COLUMN
			
			global $dbz;
			global $userps;
			
			if( !is_numeric( $this->prescript ) ){ // Temporary protection
																			return false;
			}
			
			$this->setCurColumn( $letter );
			
			if( $this->lasterror !== false ){ return false; }
			
			if( empty( $this->col_sub[ $this->curcolpos ] ) ){ // No subcolumns
				
				$end = $this->col_endpos[ $this->curcolpos ];
			}
			else{
					reset( $this->col_sub[ $this->curcolpos ] );
					$end = current( $this->col_sub[ $this->curcolpos ] );
			}
			
			$delq = $dbz->prepDelete( 'cdt_cases', array(	[ 'prescript', '=', $this->prescript, 'i' ],
															
															[ 'user', '=', $userps->usrid, false ],
															
															[ 'cdtable', '=', $this->tblid, 'i' ],
															
															[ 'pos', '>', $this->curcolpos, 'i' ],
															
															[ 'pos', '<', $end, 'i' ]
														) );
			
			if( !$delq ){
							$this->lasterror = 'delete';		return false;
			}
			else{
					$posupq = $dbz->prepNumUpdate( 'cdt_cases', array( [ 'pos', '-', $dbz->affrows ] ), 
			
																	array(	[ 'prescript', '=', $this->prescript, 'i' ],
																	
																			[ 'user', '=', $userps->usrid, false ],
																			
																			[ 'cdtable', '=', $this->tblid, 'i' ],
																			
																			[ 'pos', '>', $this->curcolpos, false ]
																														) );
					if( !$posupq ){
						
						$this->lasterror = 'cleargap';		return false;
					}
					else{
							return true;
					}
			}
		}
		
		
		public function deleteColumn( $letter ){ // -------------------------------------------------------- CLEAR COLUMN
			
			global $dbz;
			global $userps;
			
			if( !is_numeric( $this->prescript ) ){ // Temporary protection
																			return false;
			}
			
			$this->setCurColumn( $letter );
			
			if( $this->lasterror !== false ){ return false; }
			
			
			if( $this->col_ifelse[ $this->curcolpos ] == 'else' ){ // Is it an else column?
				
				$this->errortxt = "else columns can't be deleted";
				
				$this->lasterror = 'elsecol';		return false;
			}
			
			if( in_array( $this->curcolpos, $this->col_orig ) ){ // Top column
				
				if( count( $this->col_orig ) < 3 ){
					
					$this->errortxt = "A condition table must have at least 2 columns";
					
					$this->lasterror = 'lastnonelsecol';		return false;
				}
			}
			else{ // Subcolumn
				
				if( count( $this->col_sub[ $this->col_parent[ $this->curcolpos ] ] ) ){
					
					$this->errortxt = "A row must have at least 2 columns";
					
					$this->lasterror = 'lastnonelsecol';		return false;
				}
				
			}
			
			$delq = $dbz->prepDelete( 'cdt_cases', array(	[ 'prescript', '=', $this->prescript, 'i' ],
															
															[ 'user', '=', $userps->usrid, false ],
															
															[ 'cdtable', '=', $this->tblid, 'i' ],
															
															[ 'pos', '>=', $this->curcolpos, 'i' ],
															
															[ 'pos', '<=', $this->col_endpos[ $this->curcolpos ], 'i' ]
														) );
								
			if( !$delq ){
							$this->lasterror = 'delete';		return false;
			}
			else{
					$posupq = $dbz->prepNumUpdate( 'cdt_cases', array(  [ 'pos', '-', $dbz->affrows ]  ), 
			
																array(	[ 'prescript', '=', $this->prescript, 'i' ],
																	
																		[ 'user', '=', $userps->usrid, false ],
																			
																		[ 'cdtable', '=', $this->tblid, 'i' ],
																			
																		[ 'pos', '>', $this->col_endpos[ $this->curcolpos ], false ]
																																	) );
					if( !$posupq ){
						
						$this->lasterror = 'cleargap';		return false;
					}
					else{
							return true;
					}
			}
		}
		
		
		public function newTable(){ // -------------------------------------------------------------------- NEW TABLE
			
			global $dbz;
			global $userps;
			
			if( !is_numeric( $this->prescript ) ){ // Temporary protection
																			return false;
			}
			
			$mxnq = $dbz->prepSelectMax( 'cdt_header', 'num', 'mxn', array(
																			[ 'prescript', '=', $this->prescript, 'i' ]
																														) );
			if( $res = $dbz->fetch_array( $mxnq ) ){
				
				if( empty( $res['mxn'] ) ){
					
					$tblnum = 1;
				}
				else{
					
					$tblnum = $res['mxn'];
					$tblnum++;
				}
				
				$this->tablenum = $tblnum;
				
			}
			else{ // Error
				
				$this->lasterror = 'maxnum';	return false;
			}
			
			
			// New header
			$ntq = $dbz->prepInsertInto( 'cdt_header',
			
									array(	[ '',false ], // id (AI)
											
											[ $this->prescript,'i' ], // Prescript
											
											[ $userps->usrid,false ], // user
											
											[ 0,false ], // InUse
											
											[ $tblnum,'i' ], // num
											
											[ time(),false ] // time
										)
									);
			if( !$ntq ){
							$this->lasterror = 'newheader#'.$dbz->lasterror;	return false;
			}
			
			$this->tblid = $dbz->lastInsertId();
			
			
			// Table Start
			
			$optg = $dbz->prepInsertInto( 'cdt_cases',
			
									array(	[ '',false ], // id (AI)
											
											[ $this->prescript,'i' ], // Prescript
											
											[ $userps->usrid,false ], // user
											
											[ $this->tblid,false ], // cdtable
											
											[ 0,false ], // devpos
											
											[ 0,false ], // pos
											
											[ 'tblstart',false ],
											
											[ 'na',false ], // type
											
											[ '',false ], [ '',false ], [ '',false ], [ '',false ], [ '',false ], [ '',false ] 
											// letter-target-prefunc-link-val-vparse 
										)
									);
			if( !$optg ){
							$this->lasterror = 'tablestart';	return false;
			}
			
			
			$this->newRow(false); // Columns A & Z, pos 1,2,3 & 4
			
			if( $this->lasterror !== false ){ return false; }
			
			
			// Table End
			
			$cltg = $dbz->prepInsertInto( 'cdt_cases',
			
									array(	[ '',false ], // id (AI)
											
											[ $this->prescript,'i' ], // Prescript
											
											[ $userps->usrid,false ], // user
											
											[ $this->tblid,false ], // cdtable
											
											[ 0,false ], // devpos
											
											[ 5,false ], // pos
											
											[ 'tblend',false ],
											
											[ 'na',false ], // type
											
											[ '',false ], [ '',false ], [ '',false ], [ '',false ], [ '',false ], [ '',false ] 
											// letter-target-prefunc-link-val-vparse 
										)
									);
			if( !$cltg ){
				
				$this->lasterror = 'tableend';
				return false;
			}
			
		}
		
	}
	
	
?>