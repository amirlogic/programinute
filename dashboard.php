<?php

// USER MY PRESCRIPTS

	define( 'INCLUDE_BASE', $_SERVER['DOCUMENT_ROOT'].'/' );

require_once( INCLUDE_BASE . 'cls/database.php' );
require_once( INCLUDE_BASE . 'cls/userps.php' );

require_once( INCLUDE_BASE . 'cls/tbli_prescript_main.php' );
require_once( INCLUDE_BASE . 'cls/highlevel_main.php' );

$dbz = new db(); // Connect to db

$userps = new UserPrescript();

if( !$userps->usrid ){
						header('Location: gate.php');
						exit();
}

if( isset( $_POST['newpstitle'] ) && isset( $_POST['pstype'] ) ){
	
	$userps->insertPrescript( $_POST['newpstitle'],$_POST['pstype'] );
}	

?>
<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">

<title>Programinute - My Prescripts</title>

<meta name="description" content="">

<link href="style.css" rel="stylesheet" type="text/css" media="all" />

<?php //require_once('js/scripts.php'); ?>

<script type="text/javascript">

</script>

<style type="text/css">


#main
{
	padding-top:80px;
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
}

.pstitle
{
	display:inline-block;
	vertical-align:top;
	width:55%;
}

.psinfos
{
	display:inline-block;
	vertical-align:top;
	width:44%;
}

</style>

</head>

<body>

<?php require_once('parts/before_content.php'); ?>


    <div id="main">
		
		<h1>My Prescripts</h1>
		
		<div id="newpswrp">
			<form action="" method="post">
				<input type="text" id="newpstitle" name="newpstitle" size="60" maxlength="80" placeholder="New Prescript Title" />
				<input type="hidden" name="pstype" value="straight" />
				<input type="submit" value="Create" />
			</form>
		</div>
		
		<?php
		
		$pslsq = $dbz->prepSelectAll( 'prescript_header',
			
											array(	[ 'user', '=', $userps->usrid, 's' ]	),
											
											array(	[ 'time', 'DESC' ]	) );
		
		while( $row = $dbz->fetch_array( $pslsq ) ){
			
			$pstitle = ( empty( $row['title'] ) ) ? "no title" : $row['title'];
			
			echo "<div class=\"psline\">"
			
					. "<div class=\"pstitle\"><strong><a href=\"pseditor.php?id=" . $row['id'] . "\">" 
					
						. $pstitle
						
					. "</a></strong></div> "
					
					. "<div class=\"psinfos\" title=\"Created\">" . date( "M j Y g:i a", $row['time'] ) . "</div>"
					
				. "</div>";
		}
		
		?>
		
		
    
    	
    	
    	
    
    </div>


<?php require_once('parts/after_content.php'); ?>

</body>
</html>
