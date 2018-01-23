<?php

// Table Interface - Output Blocks [ $tbi_out_blocks ]

// Copyright 2015-2016 Amir Hachaichi



	class TBIOutputBlocks {
		
		public $error;
		
		public $nxtovarnum = 0; // Next ovar num (0 if not var)
		
		public $position;
		
		public $prescript;
		
		public $outputId;
		
		public $block;
		
		public $newstem; // New brick Stem ID
		
		//public $newblockid;
		
		
		//public $substart = false;
		
		
		public $lasterror = false;
		
		
		
		public function __construct( $psid ){
			
			$this->prescript = $psid;
			//$this->block = $blockid;
		}
		
		
		public function setBlockId( $blockid ){ // ------------------------------------------------------------ SET BLOCK ID
			
			$this->block = $blockid;
		}
		
		
		public function shiftPosition( $pos ){ // -----------------------------------------------------------------< SHIFTPOS
			
			global $dbz;
			
			if( $this->lasterror != false ){ return false; }
			
			if( !is_numeric( $this->prescript ) ){ // Temporary protection
																			return false;
			}
			
			$cpq = $dbz->query(
								"UPDATE `str_output_blocks` "
								
								. "SET `pos`=`pos`+1 WHERE `prescript`='".$this->prescript
								
								. "' AND `block`='".$this->block."' AND `pos`>=".$pos.";"
								
								);	
			if(!$cpq){
				
				$this->lasterror = 'clearpos';
				return false;
			}
			else{
				$this->position = $pos;
				return true;
			}
		}
		
		
		public function setOutputId( $oid ){ // ---------------------------------------------------------------- SET OUTPUT ID
			
			$this->outputId = $oid;
		}
		
		
		public function setNewBlockId(){ // --------------------------------------------------------------------- NEW BLOCK ID
			
			global $dbz;
			
			if( !is_numeric( $this->prescript ) ){ // Temporary protection
																			return false;
			}
			
			$nbidq = $dbz->prepSelectMax( 'str_output_blocks', 'block', 'lastblockid', array(
											
																			[ 'prescript', '=', $this->prescript, 'i' ]
																														) );
			
			/*$nbidq = $dbz->query("SELECT max(block) AS lastblockid FROM `str_output_blocks` "
								. "WHERE `prescript`='" . $this->prescript . "';");*/
			
			if( $row = $dbz->fetch_array( $nbidq ) ){
				
				if( empty( $row['lastblockid'] ) ){
					
					$this->block = 1; // NULL or 0
				}
				else{
					
					$this->block = $row['lastblockid']+1;
				}
			}
			else{
				
				$this->lasterror = 'newblockid';
				return false;
			}
		}
		
		
		public function getNextVarNum(){ // ---------------------------------------------------------------------- NEXT VAR NUM
			
			global $dbz;
			
			if( !is_numeric( $this->prescript ) ){ // Temporary protection
																			return false;
			}
			
			$mxovnq = $dbz->prepSelectMax( 'str_output_blocks', 'ovar', 'lastovnum', array(
											
																			[ 'prescript', '=', $this->prescript, 'i' ],
																			
																			[ 'oid', '=', $this->outputId, 'i' ]
																														) );
			if( $row = $dbz->fetch_array( $mxovnq ) ){
				
				if( !empty( $row['lastovnum'] ) ){
													$this->nxtovarnum = $row['lastovnum']+1;
				}
				else{
						$this->nxtovarnum = 1;
				}
			}
			else{
					$this->lasterror = 'ovarnum';	return false;
			}
		}
		
		
		public function addStem(){ // -------------------------------------------------------------------------- ADD STEM
			
			if( $this->lasterror != false ){ return false; }
			
			global $dbz;
			global $userps;
			
			// New Block ID
			
			$this->setNewBlockId();
			
			
			$nwstmq = $dbz->prepInsertInto( 'str_output_blocks',
			
									array(	[ '',false ], // bid (AI)
											
											[ $userps->usrid,false ], // user
											
											[ $this->prescript,'i' ], // Prescript
											
											[ $this->outputId,'i' ], // Output ID
											
											[ $this->block,false ], // Block
											
											[ 0,false ], // pos
											
											[ 'stm',false ], // Target
											
											[ 0,false ], [ 0,false ], // cdt stuffs
											
											[ '',false ], // type
											
											[ 0,false ], // Newline (Bool)
											
											[ '',false ], // Var
											
											[ '',false ], // Content
											
											[ time(),false ] // time
										)
									);
									
			if( !$nwstmq ){
				
				$this->lasterror = 'addstem';
				return false;
			}
			else{
				
				// Store stem id
				$dbz->lastInsertId();
				$this->newstem = $dbz->lastinsid;
				return true;
			}
			
		}
		
		
		public function getStem( $stmid ){ // ---------------------------------------------------------------------- GET STEM
			
			global $dbz;
			global $userps;
			
			if( $this->lasterror != false ){ return false; }
			
			$gstmq = $dbz->prepSelectAll( 'str_output_blocks', array( 
																		[ 'bid', '=', $stmid, 'i' ],
																		
																		[ 'prescript', '=', $this->prescript, 'i' ],
																		
																		[ 'user', '=', $userps->usrid, false ]
																		
																	),false );
			
			//$gstmq = $dbz->query("SELECT * FROM `str_output_blocks` WHERE `bid` = '".$stmid."' LIMIT 1;");
								
			if( $row = $dbz->fetch_array( $gstmq ) ){
				
				if( $row['target'] != 'stm' ){ // Db Error
					
					return false;
				}
				
				$this->position = $row['pos'];
				
				$this->block = $row['block'];
				
				$this->outputId = $row['oid'];
				
			}
			else{
				
				$this->lasterror = 'getstem';
				return false;
			}
		}
		
		
		public function stemClear(){ // ------------------------------------------------------------------------- STEM CLEAR
			
			global $dbz;
			
			if( $this->lasterror != false ){ return false; }
			
			$this->shiftPosition( $this->position );
			
		}
		
		
		public function addBrick( $btype,$br,$txt ){ // -----------------------------------------------------< NEW BRICK
			
			global $dbz;
			global $userps;
			
			if( !isset( $this->block ) ){ return false; }
			
			if( $btype == 'var'){
				
				$this->getNextVarNum();
			}
			
			if( $this->lasterror != false ){ return false; }
			
			
			$nbq = $dbz->prepInsertInto( 'str_output_blocks',
			
									array(	[ '',false ], // bid (AI)
											
											[ $userps->usrid,false ], // user
											
											[ $this->prescript,'i' ], // Prescript
											
											[ $this->outputId,'i' ], // Output ID
											
											[ $this->block,'i' ], // Block
											
											[ $this->position,false ], // pos
											
											[ 'brick',false ], // Target
											
											[ 0,false ], [ 0,false ], // cdt stuffs
											
											[ $btype,'s' ], // type
											
											[ $br,'i' ], // Newline (Bool)
											
											[ $this->nxtovarnum,false ], // Var
											
											[ $txt,'s' ], // Content
											
											[ time(),false ] // time
										));
			if( !$nbq ){
				
				$this->lasterror = 'addbrick';
				return false;
			}
			else{
				
				return true;
			}
			
		}		
		
		
		public function sendBricksTo( $mode ){ // ----------------------------------------------------------- BRICK SENDER
			
			global $dbz;
			global $userps;
			global $code_display;
			global $py_comp;
			global $pywrt;
			global $jswrt;
			
			$brq = $dbz->prepSelectAll( 'str_output_blocks', array( 	[ 'prescript', '=', $this->prescript, 'i' ],
																		
																		[ 'user', '=', $userps->usrid, false ],
																		
																		[ 'block', '=', $this->block, 'i' ]	),
																	
																array( 	[ 'pos','ASC' ] ) 	);
			if( $mode == 'python' ){
				
				$brkrr = array();
			}
			
			
			while( $row = $dbz->fetch_array( $brq ) ){
				
				if( $mode == 'html' ){ // -------------------------------------------------- HTML
					
					$code_display->outputTextBrick( $row );
				}
				else if( $mode == 'python' ){ // ------------------------------------------- Python
					
					if( $row['target'] == 'brick' ){ // stem not included
						
						if( $row['newline'] == '1' ){
						
							$brkrr[] = array( 'txt',"<br />" );
						}
					
						if( $row['type'] == 'text' ){
						
							$brkrr[] = array( 'txt',$row['content'] );
						}
						else if( $row['type'] == 'var' ){
						
							$brkrr[] = array( 'var','ovarr['.$row['ovar'].']' );
						}
					}
					
				}
				else if( $mode == 'javascript' ){ // --------------------------------------- Js
					
					if( $row['target'] == 'brick' ){ // stem not included
						
						if( $row['newline'] == '1' ){
						
							$jswrt->addBrick( 'text',"<br />" );
						}
					
						if( $row['type'] == 'text' ){
						
							$jswrt->addBrick( 'text',$row['content'] );
						}
						else if( $row['type'] == 'var' ){
						
							$jswrt->addBrick( 'var',$row['ovar'] );
						}
					}
				}
			}
			
			
			if( $mode == 'python' ){ // Send to python writer
				
				$pywrt->outseg( $brkrr );
				
			}
			
			
			
		}
		
		
		public function sendOvars(){ // -------------------------------------------------------- SEND OVARS TO OUTPUT CALL
			
			global $dbz;
			global $userps;
			global $tbi_out_call;
			
			if( $this->lasterror !== false ){
				
				return false;
			}
			
			// Add ovar number 0 ( Hold the onum for varless outputs )
			
			$tbi_out_call->addUnlinkedOvar(		'text',	$this->outputId, // otype - oid
												
												0, // pos (not sure about this...)
												
												0,0 // ovnum - ovid
											);
			
									// Add ovars (if any)
			
			$sovq = $dbz->prepSelectAll( 'str_output_blocks', array( 	[ 'prescript', '=', $this->prescript, 'i' ],
																		
																		[ 'user', '=', $userps->usrid, false ],
																		
																		[ 'oid', '=', $this->outputId, 'i' ],

																		[ 'type', '=', 'var', false ]	),
																	
																array( 	[ 'block','ASC' ], [ 'pos','ASC' ] ) 	);
			$p = 0;
																
			while( $row = $dbz->fetch_array( $sovq ) ){
				
				$tbi_out_call->addUnlinkedOvar(	'text', // otype
												
												$row['oid'], // oid
												
												$p, // pos
												
												$row['ovar'], // ovnum
												
												$row['bid'] // ovid
											);
				$p++;
			}			
		}
	}
	
	
?>