<?php

/*
# PM
# Database class [$dbz] [ Updated mysqli procedural ]
# Copyright 2012-2016 Amir Hachaichi
*/

/*
#  Database functions reference
#
#  
#
#  
*/

require_once('pass.php');


class db {
	
	public $lnk;
	
	public $lastinsid;
	
	public $affrows; // Affected rows
	
	public $error;
	
	public $lasterror = false;
	
	public $test;
	
	
	public function __construct(){
		
		$this->lnk = mysqli_connect( DB_LINK, DB_USER, DB_PASS, DB_NAME );
		
		if( mysqli_connect_errno() ){
			exit("Database error, please retry after few minutes, we apologize for the inconvenience.");
		}
			   
	}
	
	
	public function getVal($table, $mark, $markval, $look, $type){
  
        $req = "SELECT * FROM `" . $table . "` WHERE `" . $mark . "`='" . $markval . "' LIMIT 1";
        $res = mysqli_query( $this->lnk, $req );
  
        if($row = mysqli_fetch_assoc( $res )){
			
          if($type == "string"){
			  
			return $row[$look]; /* Returns the value */
		   
          }
          else{
			  
			return $row; /* Returns an array with all the row values */
		   
          }
        }
        else{
			return false;
        }
    }
	

        return mysql_query($addq, $this->lnk);}*/
	
	
	
	public function insertInto( $table,$values ){ // ---------------------------------------- INSERT INTO UNPREPARED
		
		if(!is_array($values)){ return false; }
		
		for( $i=0; $i<count( $values ); $i++ ){
			
			if($i == 0){
							$flatvals = "'".$values[0]."'";
			}
			else{
					$flatvals .= ",'" . $values[$i] . "'";
			}
		}
		
		$insq = "INSERT INTO `".$table."` VALUES(".$flatvals.");";
		
		return mysqli_query( $this->lnk,$insq );
	}
	
	
	public function prepSelectAll( $table,$wherr,$orderr ){ // ------------------------- SELECT * FROM WHERE PREPARED
		
		$stmt = mysqli_stmt_init( $this->lnk );
		
		$pprr = '';		$pcount = 0;	$bindrr = array();
		
		for( $i=0; $i<count( $wherr ); $i++ ){ // WHERE [0]:col [1]:op [2]:value [3]:prep
			
			if( $wherr[$i][3] === false ){ // Not prepared
				
				$add = "`" . $wherr[$i][0] . "`" . $wherr[$i][1] . "'" . $wherr[$i][2] . "'";
			}
			else{ // Prepared
				
				$add = "`" . $wherr[$i][0] . "`" . $wherr[$i][1] . '?'; 
				
				$pprr .= $wherr[$i][3];			$pcount++;		$bindrr[] = $i;
			}
			
			if( $i == 0 ){
							$flatwhere = $add;
			}
			else{
					$flatwhere .= ' AND ' . $add;
			}
			
			unset( $add );
		}
		
		$qraw = "SELECT * FROM `".$table."` WHERE " . $flatwhere;
		
		if( $orderr !== false ){
			
			for( $j=0; $j<count( $orderr ); $j++ ){ // ORDER BY [0]:col [1]:ASC/DESC
				
				$add = "`" . $orderr[$j][0] . "` " . $orderr[$j][1];
				
				if( $j == 0 ){
								$flatorder = $add;
				}
				else{
						$flatorder .= ", " . $add;
				}
			
				unset( $add );
			}
			
			$qraw .= " ORDER BY " . $flatorder;
		}
		
		if( $pcount > 0 ){
			
			if( mysqli_stmt_prepare( $stmt, $qraw ) ){
				
				if( $pcount == 1 ){
					
					mysqli_stmt_bind_param( $stmt, $pprr, $wherr[$bindrr[0]][2] );
				}
				else if( $pcount == 2 ){
					
					mysqli_stmt_bind_param( $stmt, $pprr, $wherr[$bindrr[0]][2], $wherr[$bindrr[1]][2] );
				}
				else if( $pcount == 3 ){
					
					mysqli_stmt_bind_param( $stmt, $pprr, $wherr[$bindrr[0]][2], $wherr[$bindrr[1]][2], $wherr[$bindrr[2]][2] );
				}
				
				mysqli_stmt_execute( $stmt );
			}
			else{
					$this->lasterror = 'prepare';	return false;
			}
		}
		
		$reslines = mysqli_stmt_get_result( $stmt );	mysqli_stmt_close( $stmt );
		
		return $reslines;
	}
	
	
	public function prepSelectMax( $table, $col, $as, $wherr ){ // ----------------------------------- SELECT MAX PREPARED
		
		$stmt = mysqli_stmt_init( $this->lnk );
		
		$pprr = '';		$pcount = 0;	$bindrr = array();
		
		for( $i=0; $i<count( $wherr ); $i++ ){ // WHERE [0]:col [1]:op [2]:value [3]:prep
			
			if( $wherr[$i][3] === false ){ // Not prepared
				
				$add = "`" . $wherr[$i][0] . "`" . $wherr[$i][1] . "'" . $wherr[$i][2] . "'";
			}
			else{ // Prepared
				
				$add = "`" . $wherr[$i][0] . "`" . $wherr[$i][1] . '?'; 
				
				$pprr .= $wherr[$i][3];			$pcount++;		$bindrr[] = $i;
			}
			
			if( $i == 0 ){
							$flatwhere = $add;
			}
			else{
					$flatwhere .= ' AND ' . $add;
			}
			
			unset( $add );
		}
		
		$qraw = "SELECT max(" . $col . ") AS " . $as . " FROM `" . $table . "` WHERE " . $flatwhere;
		
		if( $pcount > 0 ){
			
			if( mysqli_stmt_prepare( $stmt, $qraw ) ){
				
				if( $pcount == 1 ){
					
					mysqli_stmt_bind_param( $stmt, $pprr, $wherr[$bindrr[0]][2] );
				}
				else if( $pcount == 2 ){
					
					mysqli_stmt_bind_param( $stmt, $pprr, $wherr[$bindrr[0]][2], $wherr[$bindrr[1]][2] );
				}
				else if( $pcount == 3 ){
					
					mysqli_stmt_bind_param( $stmt, $pprr, $wherr[$bindrr[0]][2], $wherr[$bindrr[1]][2], $wherr[$bindrr[2]][2] );
				}
				
				mysqli_stmt_execute( $stmt );
			}
			else{
					$this->lasterror = 'prepare';	return false;
			}
		}
		
		$reslines = mysqli_stmt_get_result( $stmt );	mysqli_stmt_close( $stmt );
		
		return $reslines;
	}
	
	
	public function prepInsertInto( $table,$values ){ // ------------------------------------------------- INSERT INTO PREPARED
		
		$stmt = mysqli_stmt_init( $this->lnk );
		
		$pprr = '';		$pcount = 0;	$bindrr = array();
		
		for( $i=0; $i<count( $values ); $i++ ){
			
			if( $values[$i][1] === false ){ // Not prepared
				
				$add = "'".$values[$i][0]."'";
			}
			else{ // Prepared
				
				$pprr .= $values[$i][1];	$add = '?'; 	$pcount++;		$bindrr[] = $i;
			}
			
			if( $i == 0 ){
							$flatvals = $add;
			}
			else{
					$flatvals .= ',' . $add;
			}
			
			unset( $add );
		}
		
		if( $pcount > 0 ){
			
			if( mysqli_stmt_prepare( $stmt, "INSERT INTO `".$table."` VALUES ( " . $flatvals . " )" ) ){
				
				if( $pcount == 1 ){
					
					mysqli_stmt_bind_param( $stmt, $pprr, $values[$bindrr[0]][0] );
				}
				else if( $pcount == 2 ){
					
					mysqli_stmt_bind_param( $stmt, $pprr, $values[$bindrr[0]][0], $values[$bindrr[1]][0] );
				}
				else if( $pcount == 3 ){
					
					mysqli_stmt_bind_param( $stmt, $pprr, $values[$bindrr[0]][0], $values[$bindrr[1]][0], $values[$bindrr[2]][0] );
				}
				else if( $pcount == 4 ){
					
					mysqli_stmt_bind_param( $stmt, $pprr, $values[$bindrr[0]][0], $values[$bindrr[1]][0], 
					
											$values[$bindrr[2]][0], $values[$bindrr[3]][0] );
				}
				else if( $pcount == 5 ){
					
					mysqli_stmt_bind_param( $stmt, $pprr, $values[ $bindrr[0] ][0], $values[ $bindrr[1] ][0], 
					
											$values[ $bindrr[2] ][0], $values[ $bindrr[3] ][0], $values[ $bindrr[4] ][0] );
				}
				else if( $pcount == 6 ){
					
					mysqli_stmt_bind_param( $stmt, $pprr, $values[ $bindrr[0] ][0], $values[ $bindrr[1] ][0], 
					
											$values[ $bindrr[2] ][0], $values[ $bindrr[3] ][0], $values[ $bindrr[4] ][0],

											$values[ $bindrr[5] ][0] );
				}
				else if( $pcount == 7 ){
					
					mysqli_stmt_bind_param( $stmt, $pprr, $values[ $bindrr[0] ][0], $values[ $bindrr[1] ][0], 
					
											$values[ $bindrr[2] ][0], $values[ $bindrr[3] ][0], $values[ $bindrr[4] ][0],

											$values[ $bindrr[5] ][0],	$values[ $bindrr[6] ][0] );
				}
				
				mysqli_stmt_execute( $stmt );	$insafrw = mysqli_affected_rows( $this->lnk );
				
				//$this->error = mysqli_error( $this->lnk );
				
				mysqli_stmt_close( $stmt );
				
				if( $insafrw > 0 ){
																return true;
				}
				else{
						$this->lasterror = 'insert';	return false;
				}	
			}
			else{
					$this->lasterror = 'prepare';	return false;
			}
		}
		else{
				$this->lasterror = 'noprep';	return false;
		}
	}
	
	
	public function prepUpdate( $table, $setrr, $wherr ){ // ------------------------------------------ UPDATE PREPARED
		
		$stmt = mysqli_stmt_init( $this->lnk );
		
		$pprr = '';		$pcount = 0;	$bindrr = array();
		
		for( $i=0; $i<count( $setrr ); $i++ ){ //---------------------------------------------<
			
			if( $setrr[$i][2] === false ){ // Not prepared
				
				$add = "`" . $setrr[$i][0] . "` = '" . $setrr[$i][1] . "'";
			}
			else{ // Prepared
				
				$add = "`" . $setrr[$i][0] . "` = ?";
				
				$pprr .= $setrr[$i][2];	 	$pcount++;		$bindrr[] = $setrr[$i][1];
			}
			
			if( $i == 0 ){
							$flatsets = $add;
			}
			else{
					$flatsets .= ', ' . $add;
			}
			
			unset( $add );
		}
		
		for( $j=0; $j<count( $wherr ); $j++ ){ // --------------------------------------------<
			
			if( $wherr[$j][3] === false ){ // Not prepared
				
				$add = "`" . $wherr[$j][0] . "` " . $wherr[$j][1] . " '" . $wherr[$j][2] . "'";
			}
			else{ // Prepared
				
				$add = "`" . $wherr[$j][0] . "` " . $wherr[$j][1] . " ?";
				
				$pprr .= $wherr[$j][3];	 	$pcount++;		$bindrr[] = $wherr[$j][2];
			}
			
			if( $j == 0 ){
							$flatwhere = $add;
			}
			else{
					$flatwhere .= " AND " . $add;
			}
			
			unset( $add );
		}
		
		$qraw = "UPDATE `" . $table . "` SET " . $flatsets . " WHERE " . $flatwhere;
		
		if( $pcount > 0 ){
			
			if( mysqli_stmt_prepare( $stmt, $qraw ) ){
				
				if( $pcount == 1 ){
					
					mysqli_stmt_bind_param( $stmt, $pprr, $bindrr[0] );
				}
				else if( $pcount == 2 ){
					
					mysqli_stmt_bind_param( $stmt, $pprr, $bindrr[0], $bindrr[1] );
				}
				else if( $pcount == 3 ){
					
					mysqli_stmt_bind_param( $stmt, $pprr, $bindrr[0], $bindrr[1], $bindrr[2] );
				}
				else if( $pcount == 4 ){
					
					mysqli_stmt_bind_param( $stmt, $pprr, $bindrr[0], $bindrr[1], $bindrr[2], $bindrr[3] );
				}
				
				mysqli_stmt_execute( $stmt );	$uprwcnt = mysqli_affected_rows( $this->lnk );		mysqli_stmt_close( $stmt );
				
				if( $uprwcnt > 0 ){
										return true;
				}
				else{
						$this->lasterror = 'update';	return false;
				}	
			}
			else{
					$this->lasterror = 'prepare';	return false;
			}
		}
		else{
				$this->lasterror = 'noprep';	return false;
		}
	}
	
	
	public function prepNumUpdate( $table, $setrr, $wherr ){ // ------------------------------------ NUMERIC UPDATE PREPARED
		
		$stmt = mysqli_stmt_init( $this->lnk );
		
		$pprr = '';		$pcount = 0;	$bindrr = array();
		
		for( $i=0; $i<count( $setrr ); $i++ ){ //---------------------------------------------<
			
			// Sets are Not prepared ! [  0:col  1:oper  2:Num  ]
				
			$add = "`" . $setrr[$i][0] . "` = `" . $setrr[$i][0] . "`" . $setrr[$i][1] . $setrr[$i][2];
			
			if( $i == 0 ){
							$flatsets = $add;
			}
			else{
					$flatsets .= ', ' . $add;
			}
			
			unset( $add );
		}
		
		for( $j=0; $j<count( $wherr ); $j++ ){ // --------------------------------------------<
			
			if( $wherr[$j][3] === false ){ // Not prepared
				
				$add = "`" . $wherr[$j][0] . "` " . $wherr[$j][1] . " '" . $wherr[$j][2] . "'";
			}
			else{ // Prepared
				
				$add = "`" . $wherr[$j][0] . "` " . $wherr[$j][1] . " ?";
				
				$pprr .= $wherr[$j][3];	 	$pcount++;		$bindrr[] = $j;
			}
			
			if( $j == 0 ){
							$flatwhere = $add;
			}
			else{
					$flatwhere .= " AND " . $add;
			}
			
			unset( $add );
		}
		
		$qraw = "UPDATE `" . $table . "` SET " . $flatsets . " WHERE " . $flatwhere;
		
		if( $pcount > 0 ){
			
			if( mysqli_stmt_prepare( $stmt, $qraw ) ){
				
				if( $pcount == 1 ){
					
					mysqli_stmt_bind_param( $stmt, $pprr, $wherr[$bindrr[0]][2] );
				}
				else if( $pcount == 2 ){
					
					mysqli_stmt_bind_param( $stmt, $pprr, $wherr[$bindrr[0]][2], $wherr[$bindrr[1]][2] );
				}
				else if( $pcount == 3 ){
					
					mysqli_stmt_bind_param( $stmt, $pprr, $wherr[$bindrr[0]][2], $wherr[$bindrr[1]][2], $wherr[$bindrr[2]][2] );
				}
				else if( $pcount == 4 ){
					
					mysqli_stmt_bind_param( $stmt, $pprr, $wherr[$bindrr[0]][2], $wherr[$bindrr[1]][2], $wherr[$bindrr[2]][2],

											$wherr[$bindrr[3]][2] );
				}
				
				mysqli_stmt_execute( $stmt );	$uprwcnt = mysqli_affected_rows( $this->lnk );		mysqli_stmt_close( $stmt );
				
				if( $uprwcnt > 0 ){
										return true;
				}
				else{
						$this->lasterror = 'numupdate';		return false;
				}	
			}
			else{
					$this->lasterror = 'prepare';	return false;
			}
		}
		else{
				$this->lasterror = 'noprep';	return false;
		}
	}
	
	
	public function prepDelete( $table, $wherr ){ // ----------------------------------------------------- PREP DELETE
		
		$stmt = mysqli_stmt_init( $this->lnk );
		
		$pprr = '';		$pcount = 0;	$bindrr = array();
		
		for( $i=0; $i<count( $wherr ); $i++ ){ // WHERE [0]:col [1]:op [2]:value [3]:prep
			
			if( $wherr[$i][3] === false ){ // Not prepared
				
				$add = "`" . $wherr[$i][0] . "`" . $wherr[$i][1] . "'" . $wherr[$i][2] . "'";
			}
			else{ // Prepared
				
				$add = "`" . $wherr[$i][0] . "`" . $wherr[$i][1] . '?'; 
				
				$pprr .= $wherr[$i][3];			$pcount++;		$bindrr[] = $i;
			}
			
			if( $i == 0 ){
							$flatwhere = $add;
			}
			else{
					$flatwhere .= ' AND ' . $add;
			}
			
			unset( $add );
		}
		
		
		if( $pcount > 0 ){
			
			if( mysqli_stmt_prepare( $stmt, "DELETE FROM `".$table."` WHERE " . $flatwhere ) ){
				
				if( $pcount == 1 ){
					
					mysqli_stmt_bind_param( $stmt, $pprr, $wherr[ $bindrr[0] ][2] );
				}
				else if( $pcount == 2 ){
					
					mysqli_stmt_bind_param( $stmt, $pprr, $wherr[ $bindrr[0] ][2], $wherr[ $bindrr[1] ][2] );
				}
				else if( $pcount == 3 ){
					
					mysqli_stmt_bind_param( $stmt, $pprr, $wherr[$bindrr[0]][2], $wherr[$bindrr[1]][2], $wherr[$bindrr[2]][2] );
				}
				else if( $pcount == 4 ){
					
					mysqli_stmt_bind_param( $stmt, $pprr, $wherr[$bindrr[0]][2], $wherr[$bindrr[1]][2], 
					
											$wherr[$bindrr[2]][2], $wherr[$bindrr[3]][2] );
				}
				else if( $pcount == 7 ){
					
					mysqli_stmt_bind_param( $stmt, $pprr, $wherr[ $bindrr[0] ][2], $wherr[ $bindrr[1] ][2], 
					
											$wherr[ $bindrr[2] ][2], $wherr[ $bindrr[3] ][2], $wherr[ $bindrr[4] ][2],

											$wherr[ $bindrr[5] ][2],	$wherr[ $bindrr[6] ][2] );
				}
				
				mysqli_stmt_execute( $stmt );	$this->affrows = mysqli_affected_rows( $this->lnk );
				
				mysqli_stmt_close( $stmt );
				
				if( $this->affrows > 0 ){
																return true;
				}
				else{
						$this->lasterror = 'delete';	return false;
				}	
			}
			else{
					$this->lasterror = 'prepare';	return false;
			}
		}
		else{
				$this->lasterror = 'noprep';	return false;
		}
	}
	
	
	public function prepQuery( $rawq, $pprr, $values ){ // -------------------------------------------------- PREPARED QUERY
		
		$stmt = mysqli_stmt_init( $this->lnk );
		
		$pcount = count( $values );
		
		if( $pcount > 0 ){
			
			if( mysqli_stmt_prepare( $stmt, $rawq ) ){
				
				if( $pcount == 1 ){
					
					mysqli_stmt_bind_param( $stmt, $pprr, $values[0] );
				}
				else if( $pcount == 2 ){
					
					mysqli_stmt_bind_param( $stmt, $pprr, $values[0], $values[1] );
				}
				else if( $pcount == 3 ){
					
					mysqli_stmt_bind_param( $stmt, $pprr, $values[0], $values[1], $values[2] );
				}
				else if( $pcount == 4 ){
					
					mysqli_stmt_bind_param( $stmt, $pprr, $values[0], $values[1], $values[2], $values[3] );
				}
				else if( $pcount == 7 ){
					
					mysqli_stmt_bind_param( $stmt, $pprr, $values[0], $values[1], $values[2], $values[3], $values[4],

											$values[5],	$values[6] );
				}
				
				mysqli_stmt_execute( $stmt );	$this->affrows = mysqli_affected_rows( $this->lnk );
				
				mysqli_stmt_close( $stmt );
				
				if( $this->affrows > 0 ){
																return true;
				}
				else{
						$this->lasterror = 'exec';	return false;
				}	
			}
			else{
					$this->lasterror = 'prepare';	return false;
			}
		}
		else{
				$this->lasterror = 'noprep';	return false;
		}
	}
	
	

    // General purpose

    public function query( $query ){ // ----------------------------------------------------------------- QUERY
 
        return mysqli_query( $this->lnk, $query );
    }
	
	public function fetch_array( $results ){ // ---------------------------------------------------- FETCH ARRAY
 
        return mysqli_fetch_assoc( $results );
    }
	
	
	
	public function lastInsertId(){ // ------------------------------------------------------------ LAST INSERT ID
		
		$this->lastinsid = mysqli_insert_id( $this->lnk );
		
		return $this->lastinsid;
	}
	
	
	public function affectedRows(){ // ----------------------------------------------------------- AFFECTED ROWS
		
		return mysqli_affected_rows( $this->lnk );
	}
	
	
}


?>
