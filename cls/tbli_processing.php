<?php

// Table Interface - Processing [ $tbi_proc ]	PROTECTED

// Copyright 2015-2016 Amir Hachaichi

/*
# Init list
#	
#	$action_do->varProcessing()
#	
#	
#	
#	
*/

	class TBIVarProcessing {
		
		
		public $prescript;
		
		public $procid; // Processing db ID
		
		
		public $position;
		
		public $resnum; // resnum
		
		public $respos; // respos
		
		public $outnum; // Next outnum
		
		
		public $opertype; // OperType
		
		public $posgap; // ResPos gap
		
		
		public $invarstem; // New proc invar stem
		
		public $operstem; // New proc operation stem
		
		public $outvarstem; // New proc outvar stem
		
	
		
		public $lasterror = false;
		
		public $error;
		
		
		public function __construct( $psid ){
			
			$this->prescript = $psid;
			
		}
		
		
		public function setPosition( $pos ){ // ------------------------------------------------------------------- SET POSITION
			
			$this->position = $pos;
		}
		
		
		public function nextPosition(){ // ----------------------------------------------------------------------- NEXT POSITION
			
			$this->position++;
		}
		
		
		public function nextResPos(){ // --------------------------------------------------------------------------- NEXT RESPOS
			
			$this->respos++;
		}
		
		
		public function shiftPosition( $pos ){ // --------------------------------------------------------------< SHIFTPOS
			
			global $dbz;
			
			if( $this->lasterror != false ){ return false; }
			
			$cpq = $dbz->prepNumUpdate( 'str_processing', array( [ 'pos', '+', 1 ] ), 
			
																	array(	[ 'prescript', '=', $this->prescript, 'i' ],
																			
																			[ 'proc', '=', $this->procid, 'i' ],
																			
																			[ 'pos', '>=', $pos, false ]
																														) );
			/*$cpq = $dbz->query("UPDATE `str_processing` ". "SET `pos`=`pos`+1 WHERE `prescript`='" . $this->prescript
								. "' AND `proc`='" . $this->procid . "' AND `pos`>=" . $pos . ";");*/
			if( !$cpq ){
				
				$this->lasterror = 'clearpos';
				return false;
			}
			else{
				
				return true;
			}
		}
		
		
		public function shiftResPos( $respos,$gap ){ // --------------------------------------------------------- SHIFT RESPOS
			
			global $dbz;
			
			if( $this->lasterror != false ){ return false; }
			
			$cpq = $dbz->prepNumUpdate( 'str_processing', array( [ 'pos', '+', 1 ] ), 
			
																	array(	[ 'prescript', '=', $this->prescript, 'i' ],
																			
																			[ 'proc', '=', $this->procid, 'i' ],
																			
																			[ 'pos', '=', $this->position, false ],
																			
																			[ 'respos', '>=', $respos, false ]
																														) );
			/*$cpq = $dbz->query("UPDATE `str_processing` "
								. "SET `pos`=`pos`+1 WHERE `prescript`='" . $this->prescript
								. "' AND `proc`='" . $this->procid . "' AND `pos`=" . $this->position
								. " AND `respos`>=" . $respos . ";");*/
			if( !$cpq ){
				
				$this->lasterror = 'clearpos';
				return false;
			}
			else{
				
				return true;
			}
			
		}
		
		
		public function setNewProcId(){ // --------------------------------------------------------------------- NEW PROC ID
			
			global $dbz;
			
			$nbidq = $dbz->prepSelectMax( 'str_processing', 'proc', 'lastprocid', array(
											
																			[ 'prescript', '=', $this->prescript, 'i' ]
																														) );
			/*$nbidq = $dbz->query("SELECT max(proc) AS lastprocid FROM `str_processing` "
								. "WHERE `prescript`='" . $this->prescript . "';");*/	
			
			if( $row = $dbz->fetch_array( $nbidq ) ){
				
				if( empty( $row['lastprocid'] ) ){
					
					$this->procid = 1; // NULL or 0
				}
				else{
					
					$this->procid = $row['lastprocid']+1;
				}
			}
			else{
				
				$this->lasterror = 'newprocid';
				return false;
			}
		}
		
		
		public function setNextOutVarNum(){ // ------------------------------------------------------------------ SET NEXT OUTVAR NUM
			
			if( $this->lasterror != false ){ return false; }
			
			global $dbz;
			
			$nxtonq = $dbz->prepSelectMax( 'str_processing', 'outnum', 'lastoutnum', array(
											
																			[ 'prescript', '=', $this->prescript, 'i' ]
																														) );
			/*$nxtonq = $dbz->query("SELECT max(outnum) AS lastoutnum FROM `str_processing` "
								. "WHERE `prescript`='" . $this->prescript . "';");*/
			
			if( $row = $dbz->fetch_array( $nxtonq ) ){
				
				if( empty( $row['lastoutnum'] ) ){
					
					$this->outnum = 1; // NULL or 0
				}
				else{
					
					$this->outnum = $row['lastoutnum']+1;
				}
			}
			else{
				
				$this->lasterror = 'nxtoutnum';
				return false;
			}
			
		}
		
		
		public function setNextResNum(){ // ------------------------------------------------------------------ SET NEXT RES NUM
			
			if( $this->lasterror != false ){ return false; }
			
			global $dbz;
			
			$nxtrnq = $dbz->prepSelectMax( 'str_processing', 'resnum', 'lastresnum', array(
											
																			[ 'prescript', '=', $this->prescript, 'i' ],
																			
																			[ 'proc', '=', $this->procid, 'i' ]
																														) );
			/*$nxtrnq = $dbz->query("SELECT max(resnum) AS lastresnum FROM `str_processing` "
								. "WHERE `prescript`='" . $this->prescript . "' AND `proc`='" . $this->procid . "';");*/
			
			if( $row = $dbz->fetch_array( $nxtrnq ) ){
				
				if( empty( $row['lastresnum'] ) ){
					
					$this->resnum = 1; // NULL or 0
				}
				else{
					
					$this->resnum = $row['lastresnum']+1;
				}
			}
			else{
				
				$this->lasterror = 'nxtresnum';
				return false;
			}
			
		}
		
		
		public function startFromZero(){ // ---------------------------------------------------------------- START FROM ZERO
			
			$this->position = 0;
			
			$this->resnum = 0;
			
			$this->respos = 0;
			
			$this->outnum = 0;
			
			$this->setNewProcId();
			
			$this->opertype = '';
			
		}
		
		
		public function addNewProc(){ // ------------------------------------------------------------------ ADD NEW PROC
			
			global $dbz;
			
			$this->startFromZero();
			
			if( $this->lasterror === false ){ // No error
				
				// Start
				$this->mainInsert( 'start','','','','','',null );
				$this->nextPosition();
				
				
				// InVarStem
				$this->mainInsert( 'instm','','','','','',null );
				$this->nextPosition();
				
				$dbz->lastInsertId();
				$this->invarstem = $dbz->lastinsid; // Storing stemid
				
				// OperStart
				$this->mainInsert( 'operstart','','','','','',null );
				$this->nextPosition();
				
				
				// OperStem
				$this->mainInsert( 'opstm','','','','','',null );
				$this->nextPosition();
				
				$dbz->lastInsertId();
				$this->operstem = $dbz->lastinsid; // Storing stemid
				
				
				// OperEnd
				$this->mainInsert( 'operend','','','','','',null );
				$this->nextPosition();
				
				
				// OutVarStem
				$this->mainInsert( 'outstm','','','','','',null );
				$this->nextPosition();
				
				$dbz->lastInsertId();
				$this->outvarstem = $dbz->lastinsid; // Storing stemid
				
				
				// End
				$this->mainInsert( 'end','','','','','',null );
				$this->nextPosition();
				
				
				
			}
			
		}
		
		
		public function addInVar( $stmid,$txt,$id ){ // --------------------------------------------------------------------- ADD IN-VAR
			
			$this->getStem( $stmid,'instm' );
			
			$this->shiftPosition( $this->position );
			
			$this->mainInsert( 'invar',$txt,$id,'','','',null );
			
		}
		
		
		public function addOutVar( $stmid, $id, $ctxt ){ // --------------------------------------------------------- ADD OUT-VAR
			
			$this->getStem( $stmid,'outstm' );
			
			$this->setNextOutVarNum();
			
			$this->shiftPosition( $this->position );
			
			$this->mainInsert( 'outvar','',$id,'','','',$ctxt );
		}
		
		
		public function addSingleVarOperation( $stmid,$optype,$ophdr,$uinp,$truevar,$vid ){ // ----------- ADD SINGLEVAR OPERATION
			
			if( $truevar ){
							$operpin = '';
							$vartxt = $uinp;
							$varid = $vid;
			}
			else{
							$operpin = $uinp;
							$vartxt = '';
							$varid = 0;
			}
			
			$this->insertOperation( $stmid,$optype,$vartxt,$varid,$ophdr,'',$operpin );
		}
		
		
		public function addMultiVarOperation( $stmid,$optype,$ophdr,$rowrr ){ // ----------------- ADD MULTIVAR OPERATION
			
			$this->getStem( $stmid,'opstm' );
			
			$this->setNextResNum();
			
			$this->shiftPosition( $this->position );

			$this->opertype = $optype;

			$this->mainInsert( 'operline','',0,$ophdr,'start','',null ); // Start
			
			for( $r=0; $r<count($rowrr); $r++ )
			{
				if( $rowrr[$r][2] ){ // TrueVar
					
					$vartxt = $rowrr[$r][1];
					$operpin = '';
					$varid = $rowrr[$r][3];
				}
				else{ // NonVar
					
					$vartxt = '';
					$operpin = $rowrr[$r][1];
					$varid = 0;
				}
				
				
				$this->nextResPos();
				
				$this->mainInsert( 'operline',$vartxt,$varid,$ophdr,$rowrr[$r][0],$operpin,null );
			}

			$this->nextResPos();
			
			$this->mainInsert( 'operline','',0,$ophdr,'end','',null ); // End
		}
		
		
		public function insertOperation( $stmid,$optype,$opvartxt,$opvarid,$opheader,$opfunc,$opin ){ // ---------- INSERT OPERATION
			
			$this->getStem( $stmid,'opstm' );
			
			$this->setNextResNum();
			
			$this->shiftPosition( $this->position );
			
			$this->opertype = $optype;
			
			$this->mainInsert( 'operline',$opvartxt,$opvarid,$opheader,$opfunc,$opin,null );
			
		}
		
		
		public function mainInsert( $target,$vtxt,$vid,$operheader,$operfunc,$operpin,$ctxt ){ // ------------------------ MAIN INSERT
			
			if( $this->lasterror !== false ){ return false; }
			
			global $dbz;
			global $userps;
			
			$nlineq = $dbz->prepInsertInto( 'str_processing', array(
			
											[ '',false ], // bid (AI)
											
											[ $this->prescript,'i' ], // Prescript
											
											[ $userps->usrid,false ], // user
											
											[ $this->procid,false ], // ProcID
											
											[ $this->position,false ], // pos
											
											[ $this->resnum,false ], // resnum
											
											[ $this->respos,false ], // respos
											
											[ $target,false ],
											
											[ $vtxt,'s' ], // vartxt
											
											[ $this->outnum,false ], // Out Num
											
											[ $vid,false ], // varid
											
											[ $this->opertype,'s' ], // operation type (optype)
											
											[ $operheader,'s' ], // Oper header
											
											[ $operfunc,false ], // operfunc
											
											[ $operpin,'s' ], // Oper pin (programmer input)
											
											[ $ctxt,'s' ] // Comment text
										)
									);
									
			if( $dbz->lasterror !== false ){
												$this->lasterror = 'maininsert';	return false;
			}
			else{
				
					return true;
			}
		}
		
		
		public function getStem( $stmid,$check ){ // ---------------------------------------------------------------------- GET STEM
			
			global $dbz;
			global $userps;
			
			if( $this->lasterror !== false ){ return false; }
			
			$gstmq = $dbz->prepSelectAll( 'str_processing', array( 	[ 'id', '=', $stmid, 'i' ],
																
																	[ 'prescript', '=', $this->prescript, 'i' ],
																
																	[ 'user', '=', $userps->usrid, false ]
														), false );			
			if( $row = $dbz->fetch_array( $gstmq ) ){
				
				if( $row['target'] != $check ){ // Db Error
					
					return false;
				}
				
				$this->position = $row['pos'];
				
				$this->procid = $row['proc'];
				
				$this->resnum = $row['resnum'];
			
				$this->respos = $row['respos'];
			
				$this->outnum = $row['outnum'];
				
				$this->opertype = $row['opertype'];
				
			}
			else{
				
				$this->lasterror = 'getstem';
				return false;
			}
		}
		
		
		public function bindOutvar( $pnum,$rnum ){ // ----------------------------------------------------- BIND OUTVAR
			
			global $dbz;
			global $userps;
			
			if( $this->lasterror !== false ){ return false; }
			
			$bpvq = $dbz->prepUpdate( 'str_processing',
				
											array(
													[ 'resnum', $rnum, 'i' ]
												),
											
											array(
													[ 'prescript', '=', $this->prescript, 'i' ],
													
													[ 'target', '=', 'outvar', false ],
													
													[ 'outnum', '=', $pnum, 'i' ],
													
													[ 'user', '=', $userps->usrid, false ]
												) );
			if( !$bpvq ){ // Error
				
				$this->lasterror = 'bindpvar'; 	return false; // Check db lasterror 
			}
			
		}
		
		
		
		
		
	}
	
	
	
?>