<?php

// Table Interface - Output Text [$tbi_out_txt]

// Copyright 2015-2016 Amir Hachaichi



	class TBIOutputText {
		
		public $prescript;
		
		public $oid; // Current
		
		public $onum; // Current
		
		//public $dbdata = array(); // [oid][]
		
		private $dbquery; // Must be built
		
		public $oidtonum = array(); // oid to onum ( must be loaded )
		
		
		public $nxtotnum; // Next Output Text Number
		
		public $lastid; // Last Inserted ID
		
		public $lasterror = false;
		
		public $error;
		
		
		
		public function setPrescript( $psid ){ // ---------------------------------------------------------------- SET PSID
			
			$this->prescript = $psid;
		}
		
		
		public function buildDbQuery(){ // ---------------------------------------------------------------------- BUILD DB QUERY
			
			// Will be cached
			$this->dbquery = "SELECT * FROM `cmd_output_text` WHERE `prescript` = '" . $this->prescript . "';";
		}
		
		
		public function loadAllONums(){ // ---------------------------------------------------------------------- LOAD ALL ONUMS
			
			global $dbz;
					
			$outrr = $dbz->query( $this->dbquery );
			
			while( $row = $dbz->fetch_array( $outrr ) ){
				
				$this->oidtonum[$row['oid']] = $row['otnum'];
			}
		}
		
		
		public function displayReady( $psid ){ // ------------------------------------------------------------------ HTML READY
			
			// Get everything ready for html
			$this->setPrescript( $psid );
			//$this->buildDbQuery();
		}
		
		
		public function compileReady( $psid ){ // ------------------------------------------------------------------ PYTHON READY
			
			// Get everything ready for python
			$this->setPrescript( $psid );
			//$this->buildDbQuery();
			//$this->loadAllONums();
		}
		
		
		public function getOutputData( $id ){ // --------------------------------------------------------------- GET OUTPUT DATA
			
			global $dbz;
			
			$outrr = $dbz->query( $this->dbquery );
			
			$match = false;
			
			while( $row = $dbz->fetch_array( $outrr ) ){
				
				if( $row['oid'] == $id ){
					
					$match = true;
					return $row;
				}
			}
			
			if( !$match ){
				
				return false;
			}
		}
		
		
		public function loadOutputByNum( $onum ){ // --------------------------------------------------------- NUM TO ID
			
			global $dbz;
			global $userps;
			
			$otnq = $dbz->prepSelectAll( 'cmd_output_text', array( 		[ 'prescript', '=', $this->prescript, 'i' ],
																		
																		[ 'user', '=', $userps->usrid, false ],
																		
																		[ 'otnum', '=', $onum, 'i' ] ),
																	
																false 	);
			
			/*$otnq = $dbz->query("SELECT * FROM `cmd_output_text` "
								. "WHERE `prescript`='" . $this->prescript . "' AND `otnum`='" . $onum . "';");*/
			
			if( $row = $dbz->fetch_array( $otnq ) ){
				
				$this->oid = $row['oid'];
			
				$this->onum = $row['otnum'];
				
			}
			else{
					$this->lasterror = 'loadbynum';		return false;
			}
		}
		
		
		public function setOutputId( $id ){ // -------------------------------------------------------------- SET OUTPUT ID
			
			$this->oid = $id;
		}
		
		
		/*public function getVarCount(){ // -------------------------------------------------------------- GET & SET VARCOUNT
			$outrr = $this->getOutputData( $this->oid );
			$this->varcount = $outrr['varcount'];
		}*/
		
		
		public function getNextOtNum(){ // -------------------------------------------------------------- NEXT OT NUM
			
			global $dbz;
			global $userps;
			
			$nwotnq = $dbz->prepSelectMax( 'cmd_output_text', 'otnum', 'lastotnum', array(
											
																			[ 'prescript', '=', $this->prescript, 'i' ]
																														) );
			/*$nwotnq = $dbz->query("SELECT max(otnum) AS lastotnum FROM `cmd_output_text` "
							  . "WHERE `prescript`='" . $this->prescript . "';");*/
			
			if( $row = $dbz->fetch_array( $nwotnq ) ){
				
				if( !empty( $row['lastotnum'] ) ){
					
					$this->nxtotnum = $row['lastotnum']+1;
				}
				else{
						$this->nxtotnum = 1;
				}
			}
			else{
					$this->lasterror = 'otnum';		return false;
			}
		}
		
		
		public function addOutput( $title,$desc ){ // ---------------------------------------------------------- ADD OUTPUT
			
			global $dbz;
			global $userps;
			
			$this->getNextOtNum();
			
			if( $this->lasterror !== false ){
												return false;
			}
			
			$nwoq = $dbz->prepInsertInto( 'cmd_output_text',array(	[ '',false ], // id (AI)
											
																	[ $this->prescript,'i' ], // Prescript
											
																	[ $userps->usrid,false ], // user
											
																	[ $this->nxtotnum,false ], // otnum
											
																	[ $title,'s' ], // title
											
																	[ $desc,'s' ], // description
											
																	[ time(),false ] // time
																)
														);
			if( !$nwoq ){
							$this->lasterror = 'newoutput';		return false;
			}
			else{
				
				$dbz->lastInsertId();
				$this->lastid = $dbz->lastinsid;
				return true;
			}
		}
		
		
		
		
		
	
	}
	
	
	$tbi_out_txt = new TBIOutputText();
	
?>