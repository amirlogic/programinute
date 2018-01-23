<?php

// Table Interface - Users & Prescripts [ $userps ]	Tables: `users` & `prescript_header`	PROTECTED

// users: id - email - passhash - token - regtime - lastlogin

// Copyright 2016 Amir Hachaichi

	
	class UserPrescript {
		
		
		public $prescript;
		
		public $usrid; // USER ID
		
		public $paidend; // Premium Expiration
		
		public $lastlogin;
		
		public $lastip;
		
		public $userr;
		
		public $timenow;
		
		public $lasterror = false;
		
		public $mailreq;
		
		public $error;
		
		public $regerr = array(); // Registration errors
		
		public $logerr = array(); // Login errors
		
		public $lastsuccess = false;
		
		
		public function __construct(){
			
			session_start();
			
			if( !empty( $_SESSION['userid'] ) ){
				
				$this->usrid = $_SESSION['userid'];
			}
			else{
					$this->usrid = false;
			}
			
			if( !empty( $_SESSION['paidend'] ) ){
													$this->paidend = $_SESSION['paidend'];
			}
			
			$this->timenow = time();
		}
		
		
		public function loadUserArray(){ // -------------------------------------------------- LOAD CURRENT USER ARRAY
			
			global $dbz;
			
			$this->userr = array();	
			
			$urrq = $dbz->query( "SELECT * FROM `users` WHERE `id`='" . $this->usrid . "';" );
			
			if( $row = $dbz->fetch_array( $urrq ) ){ // No need to be protected
				
				$this->userr = $row;
			}
		}
		
		
		public function openTheDoor( $userid,$subend,$lastime,$lastrad ){ // -------------- OPEN THE DOOR TO USER
			
			$_SESSION['userid'] = $this->usrid = $userid;
			
			$_SESSION['paidend'] = $this->paidend = $subend;
			
			$_SESSION['lastlogin'] = $this->lastlogin = $lastime;
			
			$_SESSION['lastip'] = $this->lastip = $lastrad;
		}			
		
		
		public function uniqueToken(){ // ---------------------------------------------------------- UNIQUE TOKEN
			
			return uniqid( '', false ) . bin2hex( random_bytes(16) ); // PHP 7 only! Length: 13+32=45
		}
		
		
		public function deleteLoginCookie(){ // ---------------------------------------------- DELETE USER COOKIE
			
			setcookie( 'autologin', '', $this->timenow-3600, '/', 'programinute.com', false, true  ); 
			// Delete prev cookies (must use same parameters)
		}
		
		
		public function setLoginCookie( $tkn ){ // --------------------------------------------- SET LOGIN COOKIE
								
			setcookie( 'autologin', $tkn, $this->timenow+1000000, '/', 'programinute.com', false, true  ); // ~12 days
		}
		
		
		public function userLogin( $email,$password,$keeplogin ){ // ------------------------------------------------------ LOGIN
			
			global $dbz;
			
			$uvqr = $dbz->prepSelectAll( 'users', array( [ 'email', '=', $email, 's' ] ), false );
			
			if( $row = $dbz->fetch_array( $uvqr ) ){
				
				if( password_verify( $password, $row['passhash'] ) ){
					
					if( $row['access'] == 1 ){ // Access granted
						
						$this->openTheDoor( $row['id'], $row['paidexp'], $row['lastlogin'], $row['lastip'] );
						
						$dbupq = "UPDATE `users` SET `logcount`=`logcount`+1, `lastip`='".$_SERVER['REMOTE_ADDR']
						
								. "', `lastlogin`=".$this->timenow;
						
						if( $keeplogin == true ){ // Keep login
							
							$logintoken = $this->uniqueToken();		$this->setLoginCookie( $logintoken );
							
							$dbupq .= ", `logintkn`='" . $logintoken . "'";
						}
						else{ // Erase previous login tokens
								
								$this->deleteLoginCookie();
								
								$dbupq .= ", `logintkn`=NULL";
						}
						
						$dbupq .= " WHERE `id`='" . $this->usrid . "';";
						
						$dbz->query( $dbupq );
						
						return true;
					}
					else{ // Account suspended
							$this->logerr[] = "Your account has been suspended";
							return false;
					}
				}
				else{
						$this->logerr[] = "Wrong email or password";
						return false;
				}
			}
			else{
					$this->logerr[] = "Wrong email or password";
					return false;
			}
		}
		
		
		public function cookieLogin( $tkn ){ // ------------------------------------------------------------ COOKIE LOGIN
			
			global $dbz;
			
			$uvqr = $dbz->prepSelectAll( 'users', array( [ 'logintkn', '=', $tkn, 's' ] ), false );
			
			if( $row = $dbz->fetch_array( $uvqr ) ){
				
				$nologint = $this->timenow-$row['lastlogin'];
				
				if( $nologint < 1000000 ){
					
					$this->openTheDoor( $row['id'], $row['paidexp'], $row['lastlogin'], $row['lastip'] );
					
					$logintoken = $this->uniqueToken();		$this->setLoginCookie( $logintoken );
					
					$dbz->query( "UPDATE `users` SET `logcount`=`logcount`+1, `lastip`='" . $_SERVER['REMOTE_ADDR']
						
								. "', `logintkn`='" . $logintoken . "'"
								
								. ", `lastlogin`=" . $this->timenow . " WHERE `id`='" . $row['id'] . "';" );
					
					return true;
				}
				else{	// Last login too long ago
					
						$this->deleteLoginCookie();			return false;
				}
			}
			else{
					return false;
			}
		}
		
		
		public function validateEmail( $unval ){ // -------------------------------------------------------- VALIDATE EMAIL
			
			if( strlen( $unval ) > 7 ){
					// /^$/i
				return true;
			}
			else{
					return false;
			}
		}
		
		
		public function validatePassword( $unval ){ // --------------------------------------------------- VALIDATE PASSWORD
			
			if( strlen( $unval ) >= 6 ){
										return true;
			}
			else{
					return false;
			}
		}
		
		
		public function emailIsUnique( $email ){ // ------------------------------------------------------------ EMAIL IS UNIQUE
			
			global $dbz;
			
			$iuq = $dbz->prepSelectAll( 'users', array( [ 'email', '=', $email, 's' ] ), false );
			
			if( $dbz->fetch_array( $iuq ) ){
				
				return false;
			}
			else{
					return true;
			}
		}
		
		
		public function insertUser( $email,$password,$passcheck,$tosagree ){ // ------------------------------------- INSERT USER
			
			global $dbz;
			
			if( $password != $passcheck ){
											$this->regerr[] = "You entered two different passwords";
											return false;
			}
			
			if( !$tosagree ){
								$this->regerr[] = "You must agree with term of service to open an account";
								return false;
			}
			
			if( !$this->validateEmail( $email ) ){
				
				$this->regerr[] = "The email is invalid";
				return false;
			}
			
			if( !$this->emailIsUnique( $email ) ){
				
				$this->regerr[] = "This email is already in use";
				return false;
			}
			
			if( !$this->validatePassword( $password ) ){
				
				$this->regerr[] = "The password must be at least 6 characters";
				return false;
			}
			
			$newuserid = uniqid('',true);
			
			$dbz->prepInsertInto( 'users', array(	[ $newuserid,false ],	[ 1,false ],	[ $email,'s' ],
													
													[ password_hash( $password, PASSWORD_DEFAULT ),'s' ],
													
													[ null,'s' ], 	[ null,'s' ], 	[ $this->timenow,false ],	[ 0,false ],
													
													[ $_SERVER['REMOTE_ADDR'],false ], 	[ $_SERVER['REMOTE_ADDR'],false ],
													
													[ $this->timenow,false ], 	[ $this->timenow,false ], 	[ $this->timenow,false ]
												));
			
			if( $dbz->lasterror === false ){ // Success
				
				$this->openTheDoor( $newuserid,$this->timenow );
				$this->sendFirstMail( $email );
				return true;
			}
			else{
					$this->regerr[] = "Could not create the new user"; //[".$dbz->lasterror."] " . $dbz->error;
					return false;
			}
			
		}
		
		
		public function changeEmail( $upemail ){ // -------------------------------------------------------- CHANGE EMAIL
			
			global $dbz;
			
			if( $this->validateEmail( $upemail ) ){
				
				if( $this->emailIsUnique( $upemail ) ){
				
					if( $dbz->prepUpdate( 'users', array( [ 'email',$upemail,'s' ] ), array( [ 'id','=',$this->usrid,'s' ] ) ) ){
					
						$this->lastsuccess = 'emailup';		return true;
					}
					else{
							$this->lasterror = 'emailup'; 	return false;
					}
				}
				else{
						$this->lasterror = 'mailnotuniq';	return false;
				}
			}
			else{
					$this->lasterror = 'invalidmail';	return false;
			}
		}
		
		
		public function changePassword( $curpass,$newpass,$passverif ){ // ------------------------------------ CHANGE PASSWORD
			
			global $dbz;
			
			if( $this->validatePassword( $newpass ) ){
				
				$pcq = $dbz->prepSelectAll( 'users', array( [ 'id', '=', $this->usrid, 's' ] ), false );
				
				if( $row = $dbz->fetch_array( $pcq ) ){
					
					if( password_verify( $curpass, $row[ 'passhash' ] ) ){
						
						if( $newpass == $passverif ){
							
							if( $dbz->prepUpdate( 'users',array( [ 'passhash',password_hash( $newpass, PASSWORD_DEFAULT ),'s' ] ), 
							
														array( [ 'id','=',$this->usrid,'s' ] ) ) ){
								
								$this->lastsuccess = 'passup';		return true;
							}
							else{
									$this->lasterror = 'passup';		return false;
							}
						}
						else{
								$this->lasterror = 'diffpass';		return false;
						}
					}
					else{
							$this->lasterror = 'wrongpass';		return false;
					}
				}
			}
			else{
					$this->lasterror = 'invalidpass';	return false;
			}
		}
		
		
		public function logout(){ // ------------------------------------------------------------ LOGOUT
			
			$_SESSION['userid'] = $this->usrid = false;
		}
		
		
		public function sendFirstMail( $nuemail ){ // ---------------------------------------------------- SEND FIRST EMAIL
			
			//require_once( INCLUDE_BASE . 'cls/sendgrid/SendGrid.php' );
			//require_once( INCLUDE_BASE . 'cls/sendgrid/Client.php' );
			//require_once( INCLUDE_BASE . 'cls/sendgrid/Mail.php' );
			
			//$apiKey = 'xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx'; // RootKey
			//$sg = new \SendGrid( $apiKey );
			
			//$from = new SendGrid\Email( null, "no-reply@programinute.com" );
			//$subject = "Welcome to Programinute";
			//$to = new SendGrid\Email( null, $nuemail );
			//$content = new SendGrid\Content( "text/plain", "Your account has been successfully created. You can now login." );
			
			//$reqbody = new SendGrid\Mail( $from, $subject, $to, $content );
			
			//$response = $sg->client->mail()->send()->post( $reqbody );
			
			//$this->mailreq = array(		'status' => $response->statusCode(),
									
										//'headers' => $response->headers(),
									
										//'body' => $response->body()		);
			
		}
		
		
		public function insertPrescript( $title,$type ){ // -------------------------------------------- INSERT PRESCRIPT
			
			global $dbz;
			
			$dbz->prepInsertInto( 'prescript_header', array(
			
													[ '',false ],	[ $this->usrid,false ], 
													
													[ 'straight',false ],	[ 1,false ],
													
													[ $title,'s' ],	 [ null,'s' ], [ null,'s' ], [ 0,false ], [ $this->timenow,false ]
												));
												
			$dbz->lastInsertId();
				
			$tbi_ps_main = new TBIPrescriptMain( $dbz->lastinsid );
				
			$hgmain = new HighLevelMain( $dbz->lastinsid );
				
			$hgmain->newPrescript();
			
												
			if( $dbz->lasterror === false && $tbi_ps_main->lasterror === false ){ // Success
				
				return true;
			}
			else{
					return false;
			}
		}
		
		
		public function updatePrescriptHeader( $psid,$target,$uptxt ){ // --------------------------------------
			
			global $dbz;
			
			$trgtrr = array( 'title' => 'title', 'instr' => 'dsc', 'author' => 'author' );
			
			$phupq = $dbz->prepUpdate( 'prescript_header', 
			
									array( [ $trgtrr[ $target ], $uptxt, 's' ] ), 
									
											array( 	[ 'id', '=', $psid, 'i' ],
											
													[ 'user', '=', $this->usrid, false ]	) );
			if( !$phupq ){
				
				$this->lasterror = 'update'.$dbz->lasterror;	return false;
			}
			else{
					return true;
			}
		}
		
		
	}
	
?>
