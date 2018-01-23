<?php

// HTML Writer [ $guiwrt ]

// Copyright 2015 Amir Hachaichi


/*
# Final .py file:
#
#	$pywrt->before
#
#		$this->before
#
#		$this->inform
#
#		$this->after
#		
#	$pywrt->after
#
#	getpy.php->footer
*/

	define(	'GUI_PAGE_HEADER',
	
			"<header style=\"font-size:36px; height:90px; letter-spacing:2px; background-color:#444;\">"
			
		  . "Programinute</header>"
			
		);


	class PreHTML {
		
			
			public $before;
			
			public $inform; // Inputs HTML
			
			public $after;
			
			public $pstitle; // Prescript Title
			
			public $error;
			
			
			// -------------------------------------------------------------------- 
			
			public function __construct(){
				
				
				$this->before = "<html><header><title>"
				
								. "Testing GUI"
								
							  . "</title></header><body>"
							  
								. "<header style=\"font-size:25px;\">Programinute</header>"
				
							  . "<div style=\"padding:50px;\">"
							  
								. "<form action=\"\" method=\"post\">";
								
								
				
				$this->after = 			 "<div style=\"padding:10px 20px 10px 50px;\">"
				
											. "<input type=\"submit\" /></div>"
				
									. "</form>"
								
								. "</div>"
								
							. "</body></html>";
							
			}
			
			public function inputText($num,$title){ // ------------------------------------------------------ INPUT TEXT
				
				
				$this->inform .= "<div style=\"padding:10px 20px 10px 50px;\">"
				
							. "<input type=\"text\" name=\"in".$num."\" placeholder=\"".$title."\" />"
				
						 . "</div>";
				
			}
			
			
			
			
			
			
			
	}
	
	$guiwrt = new PreHTML();

	
	

?>