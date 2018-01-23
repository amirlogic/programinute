<?php

// Table Interface - Output Call [ $tbi_out_call ] PROTECTED

// Copyright 2015-2016 Amir Hachaichi

/*
# Init List
#
#	viewps.php
#	$action_do->outputCall()
#	
#
#
#
*/

	class TBIOutputCall {
		
		
		public $callid;
		
		public $outype;
		
		public $outputId;
		
		public $onum;
		
		public $nxtoclnum; // Next Output Call Number
		
		public $lastid; // Last Inserted ID
		
		public $newulovars = array(); // New unlinked ovars: $newulovars[ pos ] = ovnum
		
		
		public $lasterror = false;
		
		public $error;
		
		
		
		public function __construct( $psid ){ // ------------------------------------------------------------ CONSTRUCT
			
			$this->prescript = $psid;
		}
		
		
		public function setOutputId( $oid ){ // ------------------------------------------------------------ SET OUTPUT ID
			
			$this->outputId = $oid;
		}
		
		
		public function setOutputNum( $onum ){ // ------------------------------------------------------------ SET OUTPUT NUM
			
			$this->onum = $onum;
		}
		
		
		public function setOutputType( $otype ){ // ----------------------------------------------------------- SET OUTPUT TYPE
			
			$this->outype = $otype;
		}
		
		
		public function getOutCallData( $id ){ // ------------------------------------------------------ GET OUTPUT DATA
			
			global $dbz;
			
			return $dbz->getVal( 'cmd_output_call', 'id', $id, 'all', 'array' );
		}
		
		
		public function setOutputCallId( $id ){ // ------------------------------------------------------- SET OUTPUT ID
			
			$this->callid = $id;
		}
		
		
		public function setNewOutcallId(){ // ----------------------------------------------------------- GEN OUTCALL ID
			
			global $dbz;
			
			$nclidq = $dbz->prepSelectMax( 'str_output_call', 'callid', 'lastcallid', array(
											
																			[ 'prescript', '=', $this->prescript, 'i' ]
											
																														) );
			if( $row = $dbz->fetch_array( $nclidq ) ){
				
				if( empty( $row['lastcallid'] ) ){
					
					$this->callid = 1; // NULL or 0
				}
				else{
						$this->callid = $row['lastcallid']+1;
				}
			}
			else{
					$this->lasterror = 'newcallid';		return false;
			}
		}
		
		
		public function addUnlinkedOvar( $otype,$oid,$pos,$ovnum,$ovid  ){ // ------------------------------- ADD UNLINKED OVARS
			
			global $dbz;
			global $userps;
			
			$nwuovq = $dbz->prepInsertInto( 'str_output_call',
			
									array(	[ '',false ], // id ( AI )
											
											[ $this->prescript,'i' ], // Prescript
											
											[ $userps->usrid,false ], // user
											
											[ $otype,false ], // target
											
											[ $this->callid,false ], // title
											
											[ $oid,false ], // oid
											
											[ $this->onum,'i' ], // onum
											
											[ $pos,false ], // pos
											
											[ $ovnum,false ], // ovnum
											
											[ $ovid,false ], // ovid
											
											[ '',false ], // vparse
											
											[ '',false ], // vtxt
											
											[ '',false ], // srcvid
										
											[ '',false ] // pre
										)
									);
			if( !$nwuovq ){
				
				$this->lasterror = 'newunlinkedovar';
				return false;
			}
			else{
					$dbz->lastInsertId();
				
					if( $ovnum > 0 ){
										$this->newulovars[ $pos ] = array( $ovnum,$dbz->lastinsid );
					}
				
					return true;
			}
		}
		
		
		public function sortNewUlOvars(){ // -------------------------------------------------------------- SORT NEW OVARS
			
			ksort( $this->newulovars );
		}
		
		
		public function sendLinkedOvarsTo( $mode ){ // ---------------------------------------------------- SEND LINKED OVARS TO
			
			global $dbz;
			global $userps;
			global $code_display;
			global $py_comp;
			global $pywrt;
			global $jswrt;
			
			$lovq = $dbz->prepSelectAll( 'str_output_call', array( 	[ 'prescript', '=', $this->prescript, 'i' ],
																		
																		[ 'user', '=', $userps->usrid, false ],
																		
																		[ 'target', '=', $this->outype, 's' ],

																		[ 'callid', '=', $this->callid, 'i' ] ),
																	
																array( 	[ 'pos','ASC' ] ) 	);
			$i = 0;
			
			while( $row = $dbz->fetch_array( $lovq ) ){
				
				if( $mode == 'html' ){
						
					if( $row['ovnum'] > 0 ){
							
						$code_display->linkedOvar( $row,true );
					}
					else{
							
						$code_display->retro = $row['onum'];
					}
				}
				else if( $mode == 'python' ){
					
						/*if( $i == 0 ){
											$py_comp->curoutnum = $row['onum'];	$py_comp->initOutcall();
						}*/
					
					$py_comp->linkedOvar( $row['onum'], $row['ovnum'], $row['vparse'], $row['vtxt'] );
				}
				else if( $mode == 'javascript' ){
						
					if( $row['ovnum'] > 0 ){
						
						$jswrt->linkedOvar( $row['callid'],$row['onum'],$row['ovnum'],$row['vparse'],$row['vtxt'] );
					}
					else{
						
						$jswrt->initOutCall( $row['callid'],$row['onum'] );
					}
				}
				
				$i++;
			}
		}
		
		
		public function linkOvarTo( $uovid,$lnkvtxt,$vparse,$lnkvid ){ // ---------------------------------------------- LINK OVAR
			
			global $dbz;
			global $userps;
			
			$lnkvq = $dbz->prepUpdate( 'str_output_call', array(
																	[ 'vparse', $vparse, 's' ], [ 'vtxt', $lnkvtxt, 's' ],
																	
																	[ 'srcvid', $lnkvid, false ]
																	
															), array(
																		[ 'prescript', '=', $this->prescript, 'i' ],
																		
																		[ 'id', '=', $uovid, 'i' ],
																		
																		[ 'user', '=', $userps->usrid, false ]
																	));
			if( !$lnkvq ){
							$this->lasterror = 'linkuovar';		return false;
			}
			else{
				
				return true;
			}					
		}
		
		
		public function pingLoad( $ocallid ){ // ----------------------------------------------------------------- PING LOAD
			
			global $dbz;
			global $userps;
			
			$pingq = $dbz->prepSelectAll( 'str_output_call', array( 	[ 'prescript', '=', $this->prescript, 'i' ],
																		
																		[ 'user', '=', $userps->usrid, false ],

																		[ 'callid', '=', $ocallid, 'i' ],

																		[ 'ovnum', '=', 0, false ] ),
																											false 	);
			if( $row = $dbz->fetch_array( $pingq ) ){
				
				$this->setOutputCallId( $ocallid );		$this->outputId = $row['oid'];	
				
				$this->onum = $row['onum']; 	$this->outype = $row['target'];
			}
			else{
					$this->lasterror = 'load';		return false;
			}
		}
		
		
		public function deleteAllOvars(){ // --------------------------------------------------------- DELETE OVARS
			
			global $dbz;
			global $userps;
			
			$delq = $dbz->prepDelete( 'str_output_call', array(	[ 'prescript', '=', $this->prescript, 'i' ],
															
																[ 'user', '=', $userps->usrid, false ],
															
																[ 'callid', '=', $this->callid, 'i' ]
																											) );		
			if( !$delq ){
							$this->lasterror = 'delete';		return false;
			}
		}
	
	}
	
	
?>