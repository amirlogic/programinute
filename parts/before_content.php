<header>
 
		<div id="hdrtext">Programinute</div>
	
		<div id="hdrlnkwrp">
		
		<?php
		
				if( $userps->usrid != false ){
					
					echo "<a href=\"account.php\"><span class=\"hdrlink\">account</span></a>"
					
					   . "<a href=\"dashboard.php\"><span class=\"hdrlink\">dashboard</span></a>"
					   
					   . "<a href=\"logout.php\"><span class=\"hdrlink\">logout</span></a>";
				}
				else{
				
					echo "<a href=\"gate.php\"><span class=\"hdrlink\">login</span></a>";
				}
			
		?>
			
			
		</div>
	
</header>

