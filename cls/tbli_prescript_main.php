<?php

// Table Interface - Prescript Main [$tbi_ps_main]

// Copyright 2015-2016 Amir Hachaichi

/*
# Init list
#	
#	$tbi_cdt->newTable()
#	$doit->cdtSwitch()
#
#
# INDEX
#
#			240 Position Setters
#
#			414 mainInsert
#
#			517 STEM
#
#
#
#
*/


	class TBIPrescriptMain {
		
		public $error;
		
		
		public $position;
		
		public $prescript;
		
		public $substart = false;
		
		
		public $stacksub; // Stack status ( Set by getStack() )
		
		public $stackcdt; // Stack status ( Set by getStack() )
		
		public $stmtype; // Stem Line Type
		
		
		public $nxtsubpos = 0;
		
		public $nxtcdtpos = 0;
		
		public $nxtinpos = 0;
		
		public $nxtinsubpos = 0;
		
		public $stminside; // Stem cmdlink
		
		
		public $lastid; // Last Insert ID
		
		
		public $lasterror = false;
		
		
		
		public function __construct( $psid ){
			
			$this->prescript = $psid;
		}
		
		
		public function shiftPosition( $pos ){ // -----------------------------------------------------------< CLEARPOS
			
			global $dbz;
			
			if( $this->lasterror !== false ){ return false; }
			
			if( !isset( $this->prescript ) ){ return false; }
			
			if( !is_numeric( $pos )  ){ return false; }
			
			$cpq = $dbz->query(
								"UPDATE `str_prescript_body` "
								
								. "SET `pos`=`pos`+1 WHERE `prescript`='" . $this->prescript . "' AND `pos`>=" . $pos . ";"
								
								);	
			if( !$cpq ){
				
				$this->lasterror = 'clearpos';
				return false;
			}
			else{
				//$this->position = $pos;
				return true;
			}
		}
		
		
		public function shiftSubPosition( $subpos ){ // -----------------------------------------------< CLEAR_SUBPOS
			
			global $dbz;
			
			if( $this->lasterror !== false ){ return false; }
			
			//if( !is_numeric($pos) || !is_numeric($subpos) ){ return false; }
			
			$cspq = $dbz->query(
								"UPDATE `str_prescript_body` "
								
								. "SET `subpos`=`subpos`+1 WHERE `prescript`='" . $this->prescript . "' "
								
								. "AND `pos`=" . $this->position . " AND `subpos`>=" . $subpos . ";"
								);	
			if( !$cspq ){
				
				$this->lasterror = 'clearsubpos';
				return false;
			}
			else{
				//$this->position = $pos;
				//$this->nxtsubpos = $subpos;
				return true;
			}
		}
		
		
		public function shiftCdtPosition( $cdtpos ){ // -----------------------------------------------< CLEAR_CDTPOS
			
			global $dbz;
			
			if( $this->lasterror !== false ){ return false; }
			
	
			$scdtpq = $dbz->query(
									"UPDATE `str_prescript_body` "
								
									. "SET `cdtpos`=`cdtpos`+1 WHERE `prescript`='" . $this->prescript . "' "
								
									. "AND `pos`=" . $this->position . " AND `cdtpos`>=" . $cdtpos . ";"
								);	
								
			if( !$scdtpq ){
				
				$this->lasterror = 'shiftcdtpos';
				return false;
			}
			else{
				//$this->position = $pos;
				//$this->nxtsubpos = $subpos;
				return true;
			}
		}
		
		
		public function shiftCdtInPos( $cdtinpos ){ // -----------------------------------------------< CLEAR_CDT_INPOS
			
			global $dbz;
			
			if( $this->lasterror !== false ){ return false; }
			
	
			$sinpq = $dbz->query(
			
									"UPDATE `str_prescript_body` "
								
									. "SET `inpos`=`inpos`+1 WHERE `prescript`='" . $this->prescript . "' "
								
									. "AND `pos`=" . $this->position . " AND `cdtpos`=" . $this->nxtcdtpos . " "
									
									. "AND `inpos`>=" . $cdtinpos . ";"
									
								);	
								
			if( !$sinpq ){
				
				$this->lasterror = 'shiftinpos';
				return false;
			}
			else{
				
				return true;
			}
		}
		
		
		public function shiftCdtInSubPos( $cdtinsubpos ){ // -------------------------------------------< CLEAR_CDT_INSUBPOS
			
			global $dbz;
			
			if( $this->lasterror !== false ){ return false; }
			
	
			$sinpq = $dbz->query(
									"UPDATE `str_prescript_body` "
								
									. "SET `insubpos`=`insubpos`+1 WHERE `prescript`='" . $this->prescript . "' "
								
									. "AND `pos`=" . $this->position . " AND `cdtpos`=" . $this->nxtcdtpos . " "
									
									. "AND `inpos`=" . $this->nxtinpos . " AND `insubpos`>=" . $cdtinsubpos . ";"
								);	
								
			if( !$sinpq ){
				
				$this->lasterror = 'shiftinsubpos';
				return false;
			}
			else{
				
				return true;
			}
		}
		
		
		public function nextPosition(){ // -------------------------------------------------------------------- NEXT POSITION
			
			$this->position++;
		}
		
		public function nextSubPos(){ // ---------------------------------------------------------------------- NEXT SUB_POS
			
			$this->nxtsubpos++;
		}
		
		
		public function nextCdtPos(){ // ---------------------------------------------------------------------- NEXT CDT_POS
			
			$this->nxtcdtpos++;
		}
		
		
		public function nextCdtInPos(){ // ------------------------------------------------------------------ NEXT CDT_INPOS
			
			$this->nxtinpos++;
		}
		
		
		public function nextCdtInSubPos(){ // ------------------------------------------------------------ NEXT CDT_INSUBPOS
			
			$this->nxtinsubpos++;
		}
		
		
		public function setNextCdtPos( $cdtpos ){ // ----------------------------------------------------- SET NXT CDT_POS
			
			$this->nxtcdtpos = $cdtpos;
		}
		
		
		public function setNextCdtInPos( $inpos ){ // ---------------------------------------------------- SET NXT CDT_INPOS
			
			$this->nxtinpos = $inpos;
		}
		
		
		public function setNextCdtInSubPos( $insubpos ){ // ------------------------------------------- SET NXT CDT_INSUBPOS
			
			$this->nxtinsubpos = $insubpos;
		}
		
		
		public function nextSubInsert(){ // ------------------------------------------------------------------- NEXT SUB INSERT
			
			// Updates positions
			
			if( $this->stackcdt ){ // CDT
				
				$this->nextCdtInSubPos();
			}
			else{ // NO CDT
			
				$this->nextSubPos();
			}
			
		}
		
		
		public function startNewSub(){ // ---------------------------------------------------------------------- START NEW SUB
			
			$this->stacksub = true;
		}
		
		
		public function exitSub(){ // -------------------------------------------------------------------------- EXIT SUB
			
			$this->stacksub = false;
		}
		
		
		public function startNewCdt(){
			
			$this->stackcdt = true;
		}
		
		
		public function startFromZero(){ // ------------------------------------------------------------------- START FROM ZERO
			
			$this->position = 0;
			
			
			$this->stackcdt = false;
			$this->startNewSub();
			
			
		}
		
		
		// #=================================================================================================================#
		
		
		public function addSubStart( $cmd,$id ){
			
			$this->mainInsert( 'substart',$cmd,'','',$id );
		}
		
		
		public function addSubEnd( $cmd ){
			
			$this->mainInsert( 'subend',$cmd,'','','' );
		}
		
		
		public function addStem( $cmd,$inside ){ // ------------------------------------------------------------------------ ADD STEM
			
			global $dbz;
			
			if( $this->stacksub ){
				
				$this->mainInsert( 'substm',$cmd,'','',$inside );
			}
			else{
				
				$this->mainInsert( 'stm',$cmd,'','',$inside );
			}
			
			$dbz->lastInsertId();
		}
		
		
		public function addSwitchStart( $tblid ){
			
			$this->mainInsert( 'swstart','',$tblid,'','' );
		}
		
		
		public function addSwitchEnd( $tblid ){
			
			$this->mainInsert( 'swend','',$tblid,'','' );
		}
		
		
		public function addColStart( $tblid,$letter ){
			
			$this->mainInsert( 'cdtstart','',$tblid,$letter,'' );
		}
		
		
		public function addColEnd( $tblid,$letter ){
			
			$this->mainInsert( 'cdtend','',$tblid,$letter,'' );
		}
		
		
		public function addColPrestart( $tblid,$letter ){
			
			$this->mainInsert( 'cdtprestart','',$tblid,$letter,'' );
		}
		
		
		public function addColpreend( $tblid,$letter ){
			
			$this->mainInsert( 'cdtpreend','',$tblid,$letter,'' );
		}
		
		
		public function addPreStem( $tblid,$letter ){
			
			$this->mainInsert( 'prestm','newinpos','',$letter,'' );
		}
		
		
		public function addSubCmd( $cmd,$id ){
			
			$this->mainInsert( 'subcmd',$cmd,'','',$id );
		}
		
		
		public function addCdtHeader( $tblid ){
			
			$this->stackcdt = true;
			
			$this->mainInsert( 'header','',$tblid,'','' );
		}
		
		
		public function addSectionTag( $sectag ){
			
			$this->mainInsert( 'cmd',$sectag,'','','' );
		}
		
		
		public function addOutCall( $outype,$callid ){ // ----------------------------------------------------------- OUTPUT CALL 
			
			$this->mainInsert( 'cmd','outcall_'.$outype,'','',$callid );
		}
		
		
		public function addVarProc( $procid ){ // ------------------------------------------------------------------ VAR PROCESSING
			
			$this->mainInsert( 'cmd','varproc','','',$procid );
		}
		
		
		public function mainInsert( $target,$cmd,$cdtblid,$cdtlink,$link ){ // -------------------------------------- MAIN INSERT
			
			global $dbz;
			global $userps;
			
			if( $this->lasterror !== false ){ return false; }
			
			
			if( $this->stackcdt ){ // CDT
				
				
				$tag = 'cdt'; // target
				
				$cdtfunc = $target; // cdtfunc
				
				$subpos = 0;
		
				$cdtpos = $this->nxtcdtpos;
		
				$inpos = $this->nxtinpos;
		
				$insubpos = $this->nxtinsubpos;
				
				$cmdlink = $link;
				
			}
			else{ // Not CDT
				
				$tag = $target; // target
				
				$cdtpos = $inpos = $insubpos = 0;
				
				$cdtfunc = '';
				
				$cmdlink = $link;
				
				
				if( $this->stacksub ){ // Sub
				
					$subpos = $this->nxtsubpos;
				}
				else{ // Not Sub
				
					$subpos = 0;
				}
				
			}
			
			$minsq = $dbz->insertInto( 'str_prescript_body',
			
									array(
											
											'', // id (AI)
											
											$userps->usrid, // user
											
											$this->prescript, // Prescript
											
											0, // Devpos
											
											$this->position, // pos
											
											$subpos, // subpos
											
											$inpos, // inpos
											
											$insubpos, // insubpos
											
											$tag, // Target
											
											$cdtblid, // Table ID
											
											$cdtpos, // CDT pos
											
											$cdtfunc, // cdtfunc
											
											0, // CDT level (not used)
											
											$cdtlink, // CDT link (Col letter)
											
											$cmd, // cmd
											
											$cmdlink, // cmdlink
											
											time() // time
										)
									);
									
			if( !$minsq ){
				
				$this->lasterror = 'maininsert';
				return false;
			}
			else{
				
				return true;
			}
		}
		
		
		// #====================================================================================================
		
		
		public function getStem( $stmid ){ // ------------------------------------------------------------- GET STEM
			
			global $dbz;
			global $userps;
			
			if( $this->lasterror !== false ){ return false; }
			
			$gstmq = $dbz->prepSelectAll( 'str_prescript_body', array( 
			
															[ 'id', '=', $stmid, 'i' ],
																	[ 'prescript', '=', $this->prescript, 'i' ],
																			[ 'user', '=', $userps->usrid, 's' ]
																		
																	), false );
								
			if( $row = $dbz->fetch_array( $gstmq ) ){
				
				
				if( $row['target'] == 'cdt' ){ // CDT
					
					$this->stackcdt = true;
					
					if( $row['cdtfunc'] == 'stm' ){ // Not Sub
						
						$this->stacksub = false;
					}
					else if( $row['cdtfunc'] == 'substm' ){ // Sub
						
						$this->stacksub = true;
					}
					else{ // Error
						
						return false;
					}
				}
				else{ // NOT CDT
					
					$this->stackcdt = false;
					
					if( $row['target'] == 'stm' ){ // Not Sub
						
						$this->stacksub = false;
					}
					else if( $row['target'] == 'substm' ){ // Sub
						
						$this->stacksub = true;
					}
					else{ // Error
						
						$this->lasterror = 'getstem';
						return false;
					}
				}
				
				$this->stmtype = $row['cmd'];
				
				// Setting positions
				
				$this->position = $row['pos'];
				
				$this->nxtsubpos = $row['subpos'];
		
				$this->nxtcdtpos = $row['cdtpos'];
		
				$this->nxtinpos = $row['inpos'];
		
				$this->nxtinsubpos = $row['insubpos'];
				
				$this->stminside = $row['cmdlink'];
				
				
			}
			else{
				
				$this->lasterror = 'getstem';
				return false;
			}
			
		}
		
		
		public function stemClear(){ // --------------------------------------------------------------------------- STEM CLEAR
			
			if( $this->lasterror !== false ){ return false; }
			
			
			if( $this->stackcdt ){ // CDT
				
				if( $this->stacksub ){ // SUB: insubpos++
					
					$this->shiftCdtInSubPos( $this->nxtinsubpos );
				}
				else{ // Not Sub: inpos++, insubpos=0
					
					$this->shiftCdtInPos( $this->nxtinpos );
				}
			}
			else{ // NO CDT
				
				if( $this->stacksub ){ // SUB: subpos++
					
					$this->shiftSubPosition( $this->nxtsubpos );
				}
				else{ // Not Sub: pos++, subpos=0
					
					$this->shiftPosition( $this->position );
				}
				
			}
			
			
		}
		
		
	}
	
	
	
?>