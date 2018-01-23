<?php

// PROGRAMINUTE HOMEPAGE - July 2016

	define( 'INCLUDE_BASE', $_SERVER['DOCUMENT_ROOT'].'/' );

//require_once( INCLUDE_BASE . 'cls/database.php' );
require_once( INCLUDE_BASE . 'cls/userps.php' );



$userps = new UserPrescript();


/*if( $userps->usrid != false ){
								header( 'Location: dashboard.php' ); // Already logged
								exit();
}*/





/*if( isset( $_COOKIE['autologin'] ) ){
	
	if( $userps->cookieLogin( $_COOKIE['autologin'] ) ){
		
		header( 'Location: dashboard.php' ); 	exit();	// Autologin successfull
	}
}*/


?>
<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">

<title>Programinute - Build your own computer tools</title>

<meta name="description" content="Create computer programs without having to learn a programming language.">

<link href="style.css" rel="stylesheet" type="text/css" media="all" />

<link href='https://fonts.googleapis.com/css?family=Open+Sans:400,300' rel='stylesheet' type='text/css'>

<?php //require_once('js/scripts.php'); ?>

<script type="text/javascript">

	

</script>

<style type="text/css">

#main
{
	width:1000px;
	font-family: 'Open Sans', sans-serif;
}

#homeleft
{
	display:inline-block;
	vertical-align:top;
	width:63%;
	padding:10px 2% 10px 0;
}

#homeright
{
	display:inline-block;
	vertical-align:top;
	width:33%;
	padding:10px 0 10px 2%;
}

.hdrtxt
{
	font-size:22px;
	padding:20px 0;
}

.homeblock
{
	padding-bottom:30px;
}

.errwrp
{
	padding:10px 20px;
	color:red;
	margin:20px 0;
}

#startnow
{
	display:inline-block;
	padding:10px 40px;
	font-size:18px;
	color:#FFFFFF;
	background-color:#E05353;
}

</style>

</head>

<body>

<?php require_once('parts/before_content.php'); ?>


    <div id="main">
		
		<div id="homeleft">
		
			<div class="homeblock">
		
				<div class="hdrtxt">Your computer can do so much more for you</div>
		
				<p>Some tasks are so specific to your work that you are the only person that can tell the machine how to handle it properly. 
				
				With Programinute, you can create small computer programs without having to learn any programming language.</p>
		
			</div>
			
			<div class="homeblock">
		
				<div class="hdrtxt">Made for non-coders</div>
		
				<p>The interface has been specifically made for users that don't . 
				
				With Programinute, you can create small computer programs without having to learn any programming language.</p>
		
			</div>
			
			<div class="homeblock">
		
				<div class="hdrtxt">If you can automate, you can replicate</div>
		
				<p>You can't clone yourself, but if you can authomatize a task, you can make it run an unlimited number of times, 
				
				and for an unlimited number of users.</p>
				
			</div>
			
			<div class="homeblock">
		
				<div class="hdrtxt">It really takes only minutes</div>
		
				<p>There is nothing to install: The interface is web based and very easy to use. The only thing that you have to do is opening an account, 
		
				which takes less than a minute. As you will use your mouse more than your keyboard, you will be surprised how fast you can build.</p>
			
			</div>
		
			<a href="gate.php"><div id="startnow">Start Now</div></a>
    
    	</div>
		
		<div id="homeright"></div>
    
    </div>


<?php require_once('parts/after_content.php'); ?>



</body>
</html>
