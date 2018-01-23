<?php

// Table Interface - Input Text [$tbi_inp_txt] - Table `cmd_input_text` PROTECTED

// Copyright 2015-2016 Amir Hachaichi



	class TBIInputText {
		
		
		public $prescript;
		
		public $error;
		
		public $newid; // Lastinsert ID
		
		public $nxtvarnum; // New VarNum
		
		public $lasterror = false;
		
		
		public function setPrescript( $psid ){ // -------------------------------------------------------- SET PRESCRIPT
			
			$this->prescript = $psid;
		}
		
		
		public function getNextVarNum(){ // -------------------------------------------------------------- NEXT VAR NUM
			
			global $dbz;
			
			$nwinpq = $dbz->prepSelectMax( 'cmd_input_text', 'vnum', 'lastvnum', array(
											
																			[ 'prescript', '=', $this->prescript, 'i' ]
																														) );
			
			/*$nwinpq = $dbz->query("SELECT max(vnum) AS lastvnum FROM `cmd_input_text` "
								. "WHERE `prescript`='" . $this->prescript . "';");*/
			
			if( $row = $dbz->fetch_array( $nwinpq ) ){
				
				if( !empty( $row['lastvnum'] )){
													$this->nxtvarnum = $row['lastvnum']+1;
				}
				else{
						$this->nxtvarnum = 1;
				}
			}
			else{
					$this->lasterror = 'varnum';	return false;
			}
		}
		
		
		public function getInputData( $id ){ // -------------------------------------------------------- GET INPUT DATA
			
			global $dbz;
			
			return $dbz->getVal('cmd_input_text', 'id', $id, 'all', 'array');
		}
		
		
		public function addInputText( $type,$rows,$title,$desc,$array ){ // -------------------------- ADD INPUT TEXT
			
			global $dbz;
			global $userps;
			
			if( $this->lasterror !== false ){ return false; }
			
			
			$nwinq = $dbz->prepInsertInto( 'cmd_input_text',
			
									array(	[ '',false ], // id (AI)
											
											[ $userps->usrid,false ], // user
											
											[ $this->prescript,'i' ], // Prescript
											
											[ 'use',false ], // Status
											
											[ $type,'s' ], // type
											
											[ $this->nxtvarnum,false ], // Var Num
											
											[ $title,'s' ], // Title
											
											[ $desc,'s' ], // Description
											
											[ $rows,'i' ], // nrows
											
											[ $array,'i' ], // array
											
											[ time(),false ]  // time
										));
			if( !$nwinq ){
							$this->lasterror = 'newinput';	return false;
			}
			else{
				
				$dbz->lastInsertId();
				$this->newid = $dbz->lastinsid;
				
				return true;
			}
		}
		
		
		
		
	
	}
	
	
	
	$tbi_inp_txt = new TBIInputText();
	
	
?>