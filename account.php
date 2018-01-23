<?php

// USER ACCOUNT

	define( 'INCLUDE_BASE', $_SERVER['DOCUMENT_ROOT'].'/pg/' );

require_once( INCLUDE_BASE . 'cls/database.php' );
require_once( INCLUDE_BASE . 'cls/userps.php' );

$dbz = new db(); // Connect to db

$userps = new UserPrescript();

	if( !$userps->usrid ){
							header('Location: gate.php');		exit();
	}

	if( isset( $_POST[ 'action' ] ) ){
		
		if( $_POST[ 'action' ] == 'emailup' ){
			
			$userps->changeEmail( $_POST[ 'upemail' ] );
		}
		else if( $_POST[ 'action' ] == 'passup' ){
			
			$userps->changePassword( $_POST[ 'curpass' ], $_POST[ 'newpass' ], $_POST[ 'npverif' ] );
		}
	}	

	$userps->loadUserArray();

?>
<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">

<title>Programinute - My Account</title>

<meta name="description" content="">

<link href="style.css" rel="stylesheet" type="text/css" media="all" />

<?php //require_once('js/scripts.php'); ?>

<script type="text/javascript">

</script>

<style type="text/css">


#main
{
	padding-top:30px;
	padding-bottom:80px;
}

#newpswrp
{
	padding:10px 2%;
	margin:40px 0;
	background-color:#EDEDED;
}

.psline
{
	padding:10px;
}


.formheader
{
	font-size:20px;
	padding:10px 10px 20px 10px;
}


.inputwrp
{
	padding:10px;
}

input
{
	font-size:16px;
	color:#333;
	padding:5px;
}

</style>

</head>

<body>

<?php require_once('parts/before_content.php'); ?>


    <div id="main">
		
		<h1>My Account</h1>
		
		<?php
		
			if( $userps->lasterror !== false ){
				
				echo "<div style=\"padding:20px;\">";
				
				if( $userps->lasterror == 'emailup' ){
					
					echo "Error: email could not be updated";
				}
				else if( $userps->lasterror == 'invalidmail' ){
					
					echo "Error: invalid email";
				}
				else if( $userps->lasterror == 'mailnotuniq' ){
					
					echo "Error: this email is already in use";
				}
				else if( $userps->lasterror == 'wrongpass' ){
					
					echo "Error: Incorrect password";
				}
				else if( $userps->lasterror == 'invalidpass' ){
					
					echo "Error: The password must be at least 6 characters";
				}
				else if( $userps->lasterror == 'diffpass' ){
					
					echo "Error: You typed two different passwords";
				}
				else if( $userps->lasterror == 'passup' ){
					
					echo "Error: password could not be updated";
				}
				
				echo "</div>";
			}
			else if( $userps->lastsuccess !== false ){
				
				echo "<div style=\"padding:20px;\">";
				
				if( $userps->lastsuccess == 'emailup' ){
					
					echo "Your email have been successfully updated";
				}
				else if( $userps->lastsuccess == 'passup' ){
					
					echo "Your password has been successfully updated";
				}
				
				echo "</div>";
			}
		?>
		
		<div style="padding:40px 20px;"><form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
			<input type="hidden" name="action" value="emailup" />
			<input type="email" id="upemail" name="upemail" placeholder="email" value="<?php echo $userps->userr['email']; ?>" />
			<input type="submit" value="Update email" />
			</form>
		</div>
		
		<div style="padding:20px;">
		
			<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
			
				<input type="hidden" name="action" value="passup" />
				
				<div style="padding:10px 0;">
					<input type="password" id="curpass" name="curpass" placeholder="Current password" />
				</div>
				
				<div style="padding:10px 0;">
					<input type="password" id="newpass" name="newpass" placeholder="New password" />
				</div>
				
				<div style="padding:10px 0;">
					<input type="password" id="npverif" name="npverif" placeholder="Retype new password" />
				</div>
				
				<div style="padding:10px 0;">
					<input type="submit" value="Change password" />
				</div>
				
			</form>
			
		</div>
		
		<?php
		
		/*$pslsq = $dbz->prepSelectAll( 'prescript_header',
				array(	[ 'user', '=', $userps->usrid, 's' ]	),array(	[ 'time', 'DESC' ]	) );
		while( $row = $dbz->fetch_array( $pslsq ) ){	echo "<div class=\"psline\">"	
					. "<strong><a href=\"viewps.php?id=".$row['id']."\">" . $row['title'] . "</a></strong> "			
					. "<span>" . date( "M j Y g:i a", $row['time'] ) . "</span>". "</div>";}*/
		
		?>
		
		
    
    	
    	
    	
    
    </div>


<?php require_once('parts/after_content.php'); ?>

</body>
</html>
