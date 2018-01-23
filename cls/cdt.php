<?php

// Condition Table Loader [ $cdt ]

// Copyright 2015-2016 Amir Hachaichi

/*
# Init list
#	
#	$tbi_cdt->loadTable()
#	$sworg->loadTable()
#	$py_comp->conditionTable()
#
#
*/



	class ConditionTable {
		
		
		public $tbid; // Table ID 
		
		public $col_orig = array();
		
		public $col_parent = array();
		
		public $col_letter = array();
		
		public $allcol_letters = array();
		
		public $col_sub = array();
		
		public $col_data = array();
		
		public $col_endpos = array();
		
		public $col_ifelse = array();
		
		
		public $col_stack = array(); // Linear array [colpos] => array()
		
		public $col_dbpos = array();
		
		
		//public $grp_str = array();
		
		public $grp_parent = array();
		
		public $grp_data = array();
		
		public $grp_endpos = array();
		
		
		public $col_andor = array();
		
		public $grp_level = array(); // [grp_pos] => level
		
		
		public $verticols = array(); // Used in sworg
		
		
		public $ifvar_data = array(); // = array( [level] => array(content) )
		
		public $ifvar_alldata = array(); // All tables
		
		
		public $tbl_disp;
		
		public $html = array();
	
		
		
		public function __construct( $psid,$onlyone,$writehtml ){ // Reads table on db (prescript_id,table_id or false)
			
			global $dbz;
			global $userps;
			global $codesw; // For cdtype if or else
			global $code_display;
			
			if( $onlyone === false ){ // Load all tables
				
				$cdtrr = $dbz->prepSelectAll( 'cdt_cases', 
															array( [ 'prescript', '=', $psid, 'i' ], 
															
																	[ 'user', '=', $userps->usrid, false ] ), 
															
															array( [ 'cdtable','ASC' ], [ 'pos','ASC' ] ) );
				
				//$cdtq="SELECT*FROM`cdt_cases`WHERE`prescript`='".$psid."'ORDERBY`cdtable`ASC,`pos`ASC;";
			}
			else{
				
				$cdtrr = $dbz->prepSelectAll( 'cdt_cases', 
															array( [ 'prescript', '=', $psid, 'i' ], [ 'cdtable', '=', $onlyone, 'i' ],

																	[ 'user', '=', $userps->usrid, false ] ), 
															
															array( [ 'pos','ASC' ] ) );
				
				//$cdtq="SELECT*FROM`cdt_cases`WHERE`prescript`='".$psid."'AND`cdtable`='".$onlyone."'ORDERBY`pos`ASC;";
			}
			
			//$cdtrr = $dbz->query( $cdtq );
			
			
			$curcol = false;
			$curgrp = false;
			
			$col_level = array(); // Column list for each level
	
			$curlevel = false; // Current level
			$prevlvlinc = false;
			$prevlvldec = false;
			
			// CVARS
			$curifvarlvl = array(); // Current grp level (for each column)
			$ifvar_addr = array(); // Current grp inside address (for each column) Links db pos to array address
			$cdtinpos = array(); // Position inside group (or column for top level) if top level: Col pos
			$ifvar_indata = array(); // If top level: Col pos

			
			while( $row = $dbz->fetch_array( $cdtrr ) ){ // -----<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<
		
		
				if( $row['coltag'] == 'tblstart' ){ // --------------------------------------------------------------- TABLE START
			
					// Check
			
					if( $writehtml && $onlyone === false ){

						$this->tbl_disp = $code_display->cdtHeaderStartTable( $row['cdtable'] );
					}
					
					$this->tbid = $row['cdtable'];
					
					if( $onlyone === false ){
						
						$this->ifvar_alldata[ $row['cdtable'] ] = array();
						
						$this->allcol_letters[ $row['cdtable'] ] = array();
					}
			
				}
				else if($row['coltag'] == 'colstart'){ // ----------------------------------------------------------- COLUMN START
			
					$this->col_ifelse[ $row['pos'] ] = $row['type'];
					
					if( $onlyone !== false ){
						
						$this->verticols[] = array( 'start',$row['pos'],$row['letter'] );
					}
					
					$this->col_sub[ $row['pos'] ] = array(); // Moved
					
					if( $curcol === false ){ // Top level
				
						$this->col_orig[] = $row['pos']; // NEW Origin column
						
						$curlevel = 0;
					}
					else{ // Nested
				
						$this->col_parent[$row['pos']] = $curcol; // Child => Parent
						$this->col_sub[$curcol][] = $row['pos']; // Subcolumn
				
						if( $prevlvlinc == $curlevel ){ // Subcolumn begining (No column closing encoutered)
					
							if( $writehtml ){ 
								
								$this->tbl_disp .= "<!-- SUBCOLUMN START -->" 
								
												 . "<div id=\"cdt_hdr_" . $row['cdtable'] . "_subrow\" " 
													
												 . "class=\"col_sub\">"; 
							}
						}
		
						$curlevel++;
						$prevlvlinc = $curlevel;
					}
			
					$curcol = $row['pos'];
					$this->col_letter[$row['pos']] = $row['letter'];
					//$col_level[$row['pos']] = $curlevel; // Level update
			
					if(!isset($col_level[$curlevel])){
				
						$col_level[$curlevel] = array();
					}
			
					$col_level[$curlevel][] = $row['pos'];
					
					if( $writehtml ){
					
						$this->tbl_disp .= $code_display->cdtHeaderStartColumn( $row['cdtable'],$row['letter'] );
					}
					
					if( $row['type'] == 'else' ){
						
						$this->tbl_disp .= "Else";
					}
					
					$curgrplvl = 0;
					$this->col_andor[ $curcol ] = false;
					
					// CVARS
					
					if( $row['type'] == 'if' ){ // Else columns have no cdts
						
						$this->ifvar_data[ $row['pos'] ] = array();
						$this->ifvar_data[ $row['pos'] ][0] = array();
					}
					
					
					//$ifvar_addr[$row['pos']] = array(); done after
					$curifvarlvl[ $row['pos'] ] = 0;
					$cdtinpos[ $row['pos'] ] = -1;
					
					// ------------------------ Column type (if/elseif or else)
					
					if( !isset( $codesw->cdtype[$this->tbid] ) ){
						
						$codesw->cdtype[ $this->tbid ] = array();
					}
					
					$codesw->cdtype[ $this->tbid ][ $row['letter'] ] = $row['type'];
					
					
					// -------------------------- Stack
					
					$this->col_stack[ $curcol ] = array();
					$this->col_dbpos[ $curcol ] = array();
					
					$stackpos = -1; // Reset
				}
				else if($row['coltag'] == 'cdt'){ //  ----------------------------------------------------------------- CONDITION LINE
			
					if( $curcol === false ){ // Check we are inside a column
					
							exit("DB Error 1");
					}
			
					if($curgrp === false){ // Not nested: Add to column data
				
						/*$this->col_data[$curcol][] = array('cdt',$row['target'],$row['link'],$row['val']);*/
														
						// CVARS: No address needed								
						
						
						$cdtinpos[$curcol]++;
						
					}
					else{ // Nested: Add to grp data
				
						/*$this->grp_data[$curgrp][] = array('cdt',$row['target'],$row['link'],$row['val']);*/
														
						// CVARS: Address needed
						
						
						$cdtinpos[$curgrp]++;
						
					}
			
					if( $writehtml ){

						$this->tbl_disp .= $code_display->cdtHeaderCondition( $row['prefunc'],$row['target'],
						
																				$row['link'],$row['val'],$row['vparse'] );
					}
					
					
					// -------------------------------------------------------------------------------- CVARS
					
					if( $curifvarlvl[$curcol] === 0 ){ // Top level [0]
						
						$this->ifvar_data[$curcol][0][] = array( // Column
						
																	'cdt',
												
																	$row['prefunc'],$row['target'],
												
																	$row['link'],
												
																	$row['val'],$row['vparse']
																);
						
						

						
					}
					else{ // Nested
						
						$ifvar_indata[$curgrp][] = array( // Group 
						
														'cdt',
												
														$row['prefunc'],$row['target'],
												
														$row['link'],
												
														$row['val'],$row['vparse']
								
														);
					}
					
					// --------------------------------------------- Stack
					
					$stackpos++;
					
					$this->col_dbpos[ $curcol ][ $stackpos ] = $row['pos'];
					
					$this->col_stack[ $curcol ][ $stackpos ] = array( 'cdt',$row['prefunc'],$row['target'],
					
																		$row['link'],$row['val'],$row['vparse'] );
			
				}
				else if($row['coltag'] == 'opor'){ //  ---------------------------------------------------------------- OR OPERATOR
			
					if($curcol === false){ // Check we are inside a column
					
						exit("DB Error 2");
					}
			
					if($curgrp === false){ // Not nested: Add to column data
				
						/*$this->col_data[$curcol][] = array('oper','or');*/
														
						$cdtinpos[$curcol]++;
						
					}
					else{ // Nested: Add to grp data
				
						/*$this->grp_data[$curgrp][] = array('oper','or');*/
														
						$cdtinpos[$curgrp]++;
						
					}
			
					if( $writehtml ){

						$this->tbl_disp .= "<div>OR</div>";
					}
					
					
					$this->setColAndOr( $curcol, 'or', $curgrplvl );
					
					
					// ----------------------------------------------------------------- CVARS
					
					if( $curifvarlvl[$curcol] === 0 ){ // Top level [0]
						
						$this->ifvar_data[$curcol][0][] = array( // Column
						
																	'oper',
												
																	'or'
																);
						
						

						
					}
					else{ // Nested
						
						$ifvar_indata[$curgrp][] = array( // Group
								
														'oper',
												
														'or'
								
														);
						
					}
			
				}
				else if($row['coltag'] == 'opand'){ // ---------------------------------------------------------------- AND OPERATOR 
			
					if($curcol === false){ // Check we are inside a column
					
							exit("DB Error 3");
					}
			
					if( $curgrp === false ){ // Not nested: Add to column data
				
						/*$this->col_data[$curcol][] = array('oper','and');*/
														
						$cdtinpos[ $curcol ]++; // >>>>> Error resolved <<<<<
						
					}
					else{ // Nested: Add to grp data
				
						/*$this->grp_data[$curgrp][] = array('oper','and');*/
														
						$cdtinpos[ $curgrp ]++;
						
					}
					
					if( $writehtml ){
					
						$this->tbl_disp .= "<div>AND</div>";
					}
					
					
					$this->setColAndOr( $curcol, 'and', $curgrplvl );
					
					
					// --------------------------------------------------------------- CVARS
					
					if( $curifvarlvl[$curcol] === 0 ){ // Top level [0]
						
						$this->ifvar_data[$curcol][0][] = array( // Column
						
																	'oper',
												
																	'and'
																);
						
						

						
					}
					else{ // Nested
						
						$ifvar_indata[$curgrp][] = array( // Group
						
														'oper',
												
														'and'
								
														);
						
					}
			
				}
				else if( $row['coltag'] == 'grpstart' ){ //  ------------------------------------------------------------ NESTING START
			
					if($curcol === false){ // Check we are inside a column
					
							exit("DB Error 4");
					}
			
					if($curgrp === false){ // Not nested: 
				
						// Add it to column data
						/*$this->col_data[$curcol][] = array('grp',$row['pos']);*/
						
						// CVARS
						
						$cdtinpos[$curcol]++; // Increase horizontal pos (Top Level)
						
						$ifvar_addr[$row['pos']] = array($cdtinpos[$curcol]);
						
					}
					else{ // Nested: 
					
						// Add to nested group data
						$this->grp_parent[ $row['pos'] ] = $curgrp;
						
						// CVARS
						
						$cdtinpos[$curgrp]++; // Increase horizontal pos
						
						$addr_pfx = $ifvar_addr[ $curgrp ];
						
						array_push($addr_pfx,$cdtinpos[ $curgrp ]);
						
						$ifvar_addr[ $row['pos'] ] = $addr_pfx;
						
						unset( $addr_pfx );
						
					}
			
					$curgrp = $row['pos'];
					
					if( $writehtml ){

						$this->tbl_disp .= "<div class=\"cdt_table_grp_wrp\">";
					}
					
					$curgrplvl++;
					
					$this->grp_level[ $curgrp ] = $curgrplvl;
					
					// ----------------------------------------------------------------------- CVARS
					
					if( $curifvarlvl[$curcol] === 0 ){ // Top level [0]
						
						$this->ifvar_data[$curcol][0][] = array( // Column
						
																	'grp',
												
																	implode('',$ifvar_addr[$row['pos']])
												
																);
						
						

						
					}
					else{ // Nested
						
						$ifvar_indata[$curgrp][] = array( // Group 
						
															'grp',
												
															implode('',$ifvar_addr[$row['pos']])
								
														);
						
					}
					
					
					// Preparing
					
					$curifvarlvl[$curcol]++; // Increase ifvar level
					
					$cdtinpos[$row['pos']] = -1; // Preparing horizontal position
					
					$ifvar_indata[$row['pos']] = array(); // Preparing horizontal data adding
					
					
					// ------------------------------------- Stack
					
					$stackpos++;
					
					$this->col_dbpos[ $curcol ][ $stackpos ] = $row['pos'];
					
					$this->col_stack[ $curcol ][ $stackpos ] = array( 'sub','start' );
			
				}
				else if( $row['coltag'] == 'grpend' ){ // -------------------------------------------------------------- NESTING END 
			
			
					if($curcol === false){ // Check we are inside a column
					
							exit("DB Error 5");
					}
					
					$curgrplvl--;
					
					// CVARS
					
					$this->ifvar_data[$curcol][$curifvarlvl[$curcol]][] = array(
					
																				implode('',$ifvar_addr[$curgrp]), // Address (must be complete)
																				$ifvar_indata[$curgrp]
																				
																				);
					
					$curifvarlvl[$curcol]--;
					
					$this->grp_endpos[$curgrp] = $row['pos'];
					
					// Curgroup update
					
					if(in_array($curgrp,array_values($this->grp_parent))){ // Nested
				
						$curgrp = $this->col_parent[$curgrp];
					}
					else{ // Not nested
				
						$curgrp = false;
					}
			
					if( $writehtml ){

						$this->tbl_disp .= "</div>";
					}
					
					
					// ------------------------------------- Stack
					
					$stackpos++;
					
					$this->col_dbpos[ $curcol ][ $stackpos ] = $row['pos'];
					
					$this->col_stack[ $curcol ][ $stackpos ] = array( 'sub','end' );
					
				}
				else if( $row['coltag'] == 'colend' ){ //  ------------------------------------------------------------- COLUMN END
			
					if($curcol === false){ // Check we are inside a column
					
							exit("DB Error 6");
					}
			
					// Same letter as colstart?
			
					if( $row['letter'] != $this->col_letter[ $curcol ] ){
				
						exit("DB Error 7");
					}
					
					$this->col_endpos[ $curcol ] = $row['pos'];
					
					if( $onlyone !== false ){
						
						$this->verticols[] = array( 'end', $curcol, $row['letter'] );
					}
			
					// Level update
					if( $curlevel === 0 ){ // Top level
				
						$curlevel = false;
						$curcol = false;
					}
					else{ // Nested
				
						$curcol = $this->col_parent[$curcol];
						$curlevel--;
					}
			
					// Subcolumn
			
					if($curlevel !== false){
				
						if(( $prevlvlinc - $curlevel ) == 2){ // It's the end of a subcolumn set
				
							if( $writehtml ){ $this->tbl_disp .= "</div><!-- SUBCOLUMN END-->"; }
				
							$prevlvlinc--;
						}
					}
					else{
				
						if( $prevlvlinc == 1 ){ // It's the end of a subcolumn set
				
							if( $writehtml ){ $this->tbl_disp .= "</div><!-- SUBCOLUMN END-->"; }
				
							$prevlvlinc--;
						}
					}
			
					// End of column (col_body then col_wrp)
					if( $writehtml ){

						$this->tbl_disp .= $code_display->cdtHeaderEndColumn();
					}
					
					
			
				}
				else if($row['coltag'] == 'tblend'){ // -------------------------------------------------------------  TABLE END
			
					if($curcol !== false){ // All columns closed?
					
							exit("DB Error 8");
					}
			
					if( $writehtml && $onlyone === false ){

						$this->tbl_disp .= $code_display->cdtHeaderEndTable();
					}
			
					// Transfert Data
					
					if( $onlyone === false ){
						
						$this->ifvar_alldata[ $row['cdtable'] ] = $this->ifvar_data;
						
						$this->ifvar_data = array(); // Reset
						
						$this->allcol_letters[ $row['cdtable'] ] = $this->col_letter;
						
						// Reset All
						$this->col_orig = array();
						$this->col_parent = array();
						$this->col_letter = array();
						$this->col_sub = array();
						$this->col_data = array();
						$this->col_endpos = array();
						$this->col_ifelse = array();
						$this->col_stack = array();
						$this->col_dbpos = array();
						$this->grp_parent = array();
						$this->grp_data = array();
						$this->grp_endpos = array();
						$this->col_andor = array();
						$this->grp_level = array();
						
						
						if( $writehtml ){
							
							$this->html[ $row['cdtable'] ] = $this->tbl_disp;
							
							$this->tbl_disp = ''; // Still contains html if only one table is loaded !
						}
						
					}
				}
				else{
			
					exit("DB Error 9");
				}
			}
			
		}
		
		
		public function setColAndOr( $col,$oper,$grplevel ){ // -------------------------------------------- SET COLUMN ANDOR
			
			if( $this->col_andor[ $col ] === false ){
				
				if( $grplevel === 0 ){
										$this->col_andor[ $col ] = $oper;
				}
				else{
					
					if( $grplevel % 2 == 0 ){ // -------------------------------------- Even level: Same as root
												$this->col_andor[ $col ] = $oper;
					}
					else{ // ----------------------- Odd level: The other oper
							if( $oper == 'and' ){
													$this->col_andor[ $col ] = 'or';
							}
							else if( $oper == 'or' ){
														$this->col_andor[ $col ] = 'and';
							}
					}
				}
			}
		}
		
	}


	
?>