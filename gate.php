<?php

// USER GATE

	define( 'INCLUDE_BASE', $_SERVER['DOCUMENT_ROOT'].'/' );

require_once( INCLUDE_BASE . 'cls/database.php' );
require_once( INCLUDE_BASE . 'cls/userps.php' );

$dbz = new db(); // Connect to db

$userps = new UserPrescript();


if( $userps->usrid != false ){
								header( 'Location: dashboard.php' ); // Already logged
								exit();
}


if( isset( $_POST['action'] ) ){
	
	if( $_POST['action'] == 'login' ){ // -------------------------------------------------------------------- LOGIN
		
		if( isset($_POST['email']) && isset($_POST['password']) ){
			
			if( $userps->userLogin( $_POST['email'], $_POST['password'], isset( $_POST['keeplogin'] )  ) ){
				
				header('Location: dashboard.php'); // Success
				exit();
				
			}
		}
		
	}
	else if( $_POST['action'] == 'register' ){ // ------------------------------------------------------------ REGISTER
		
		if( isset($_POST['email']) && isset($_POST['password']) && isset($_POST['passcheck']) && isset($_POST['formdelay']) ){
			
			if( $_POST['formdelay'] > 5 ){
				
				
				if( $userps->insertUser( $_POST['email'],$_POST['password'],$_POST['passcheck'],isset( $_POST['tosok'] ) ) ){ 
					
					header('Location: dashboard.php'); // Success
					exit();
				}
			}	
		}
	}
}


if( isset( $_COOKIE['autologin'] ) ){
	
	if( $userps->cookieLogin( $_COOKIE['autologin'] ) ){
		
		header( 'Location: dashboard.php' ); 	exit();	// Autologin successfull
	}
}


?>
<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">

<title>Programinute - User Gate</title>

<meta name="description" content="">

<link href="style.css" rel="stylesheet" type="text/css" media="all" />

<?php //require_once('js/scripts.php'); ?>

<script type="text/javascript">

	var formdelay = false;
	
	var intvl;


	function startRegFormInput()
	{
		if( formdelay === false ){
									formdelay = 0;
									
									intvl = setInterval( function(){ formdelay++; }, 1000 );
		}
	}
	
	
	function endRegFormInput()
	{
		document.getElementById('formdelay').value = formdelay;
	}


</script>

<style type="text/css">


#main
{
	padding-top:80px;
	padding-bottom:80px;
}

#loginwrp
{
	display:inline-block;
	vertical-align:top;
	width:41%;
	padding:20px 2%;
	margin:30px 2%;
	background-color:#EDEDED;
}

#registerwrp
{
	display:inline-block;
	vertical-align:top;
	width:42%;
	padding:20px 2%;
	margin:30px 2%;
	background-color:#EDEDED;
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

#topmsg
{
	font-size:18px;
	padding:20px 0;
}

.errwrp
{
	padding:10px 20px;
	color:red;
	margin:20px 0;
}

input
{
	padding:5px;
	font-size:16px;
	color:#333;
}

</style>

</head>

<body>

<?php require_once('parts/before_content.php'); ?>


    <div id="main">
		
		<div id="topmsg">If you are a new user, please open an account</div>
		
		<?php 
	
		if( !empty( $userps->regerr ) ){
		
			echo "<div class=\"errwrp\">ERROR: "; 
			
			if( count( $userps->regerr ) > 1 ){
				
				echo "<br />";
				
				foreach( $userps->regerr as $regerror ){
					 
					 echo "<p>" . $regerror . "</p>";
				 }
			}
			else{
					echo $userps->regerr[0];
			}
			
			echo "</div>";
		}
	
		if( !empty( $userps->logerr ) ){
		
			echo "<div class=\"errwrp\">ERROR: ";
			
			 if( count( $userps->logerr ) > 1 ){
				
				 echo "<br />";
				
				 foreach( $userps->logerr as $logerror ){
					 
					 echo "<p>" . $logerror . "</p>";
				 }
			 }
			 else{
					echo $userps->logerr[0];
			 }
			 
			echo "</div>";
		}
	
		?>
	
    	<div id="loginwrp">
    
		<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
    
    		<div class="formheader">Login</div>
    		
    		<input type="hidden" name="action" value="login" />
    		
    		<div class="inputwrp">
    		
				<input type="email" name="email" placeholder="Your email" required="required" />
    		
    		</div>
    		
    		<div class="inputwrp">
    		
				<input type="password" name="password" placeholder="Password" required="required" />
    		
    		</div>
    		
    		<div class="inputwrp">
				
				<input type="checkbox" id="keeplogin" name="keeplogin" /> 
				<label for="keeplogin">Keep me logged in</label>
			
			</div>
    		
    		<div class="inputwrp">
    		
				<input type="submit" value="login" />
    		
    		</div>
            
        </form>
        
    	</div>
    
    	<div id="registerwrp">
    
		<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
			
    		<div class="formheader">Create a new account</div>
    		
    		<input type="hidden" name="action" value="register" />
    		
    		<div class="inputwrp">
    		
				<input type="email" name="email" placeholder="Your email" required="required"
				
					onfocus="startRegFormInput();" onblur="endRegFormInput();" />
    		
    		</div>
    		
    		<div class="inputwrp">
    		
				<input type="password" name="password" placeholder="Password" required="required"
				
					onfocus="startRegFormInput();" onblur="endRegFormInput();" />
    		
    		</div>
    		
    		<div class="inputwrp">
    		
				<input type="password" name="passcheck" placeholder="Password again" required="required"
				
					onfocus="startRegFormInput();" onblur="endRegFormInput();" />
    		
    		</div>
    		
    		<div class="inputwrp">
				
				<input type="checkbox" id="tosok" name="tosok" value="agree" /> 
					<label for="tosok">I have read and agree with Terms Of Service</label>
			
			</div>
			
			<input type="hidden" id="formdelay" name="formdelay" value="0" />
    		
    		<div class="inputwrp">
    		
				<input type="submit" value="Register" />
    		
    		</div>
        
    	</form>
    	
    	</div>
    
    </div>


<?php require_once('parts/after_content.php'); ?>


</body>
</html>
