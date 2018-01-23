<?php

// Variables Reference  [$varef] - DB Tables: var_ref and var_flow

// Copyright 2015 Amir Hachaichi

/*
# Init list
#	
#	
#
#
#
#
*/
	

	class VariablesReference {
		
		
		public $prescript;
	
		
		public $varid;
		
		public $varpfx;
		
		public $varnum;
		
		public $varbackid;
		
		public $varforcenum;
		
		public $maxlen;
		
		public $minlen;
		
		public $vartxt;
		
		
		public $lasterror = false;
		
		
		public function __construct( $psid ){
			
			$this->prescript = $psid;
			
			
		}
		
		
		public function loadVar( $var ){ // ---------------------------------------------------------------- LOAD VARIABLE ARRAY
			
			global $dbz;
			
			$vrrq = $dbz->query(
								
								"SELECT * FROM `var_ref` WHERE "
								
							  . "`prescript` = '".$this->prescript."' AND CONCAT(`prefix`,`num`) = '".$var."';"
								
								);
			
			
			if( $row = $dbz->fetch_array( $vrrq ) ){
				
				
				$this->varid = $row['id'];
		
				$this->varpfx = $row['prefix'];
		
				$this->varnum = $row['num'];
		
				$this->varbackid = $row['backid'];
		
				$this->varforcenum = $row['forcenum'];
		
				$this->maxlen = $row['maxlen'];
		
				$this->minlen = $row['minlen'];
		
				$this->vartxt = $row['txt'];
				
			}
			else{
				
				return false;
			}
			
			
		}
		
		
		public function addNew( $pfx,$num,$id,$txt,$forcenum,$maxlen,$minlen ){ // ---------------------------- ADD NEW VARIABLE
			
			global $dbz;
			
			$nwvarq = $dbz->insertInto( 'var_ref',
			
													array(
											
															'', // id (AI)
											
															$this->prescript, // Prescript
											
															$pfx, // Prefix
											
															$num, // Number
											
															$id, // Back ID
											
															$forcenum, // ForceNum
											
															$maxlen, // MaxLen
											
															$minlen, // MinLen
											
															$txt // Text
											
														)
										);
									
			if( !$nwvarq ){
				
				$this->lasterror = 'newvar';
				return false;
			}
			else{
				
				return true;
			}
			
			
		}
		
		
		
		
		
		public function addConditionTable(  ){ // ------------------------------------------------------------ CDT HEADER
			
			
		}
		
		
		
	
	}
	
	
?>