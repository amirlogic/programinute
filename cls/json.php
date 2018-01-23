<?php

/*
# JSON class [$json] 
# 
# 
# Copyright 2012-2016 Amir Hachaïchi
*/

/*
# Class reference
#
# setContent($target,$newval)
# newElement($type,$target,$newid,$newclass,$newcont)
# setValue($target,$newval)
# setDisplay($target,$newstyle)
# setColor($target,$newcolor)
# setBgColor($target,$newcolor)
# makeEnabled($target)
# makeDisabled($target)
# makeChecked($target)
# makeUnchecked($target)
# setBuffer($toset)
# setLoginInfos($loginok,$status)
#
*/

class JSON {
	
	
	public $inside = array(); 
	
	
	public function finalOutput(){
		
		if( count( $this->inside ) > 1 ){
			
			$final = implode( $this->inside,',' );
		}
		else{
			
			$final = $this->inside[0];
		}
		
		return '[' . $final . ']';
	}
	
	
	public function prepare( $raw ){ // Problem with line breaks...
		
		return str_replace(array("\n","\\","\""),array(" ","\\\\","\\\""),$raw);
	}
	
	public function setContent( $target,$newval ){ // ------------------------------------------------------- SET CONTENT
		
		$newcont = $this->prepare( $newval );
		
		$this->inside[] = "{ \"action\":\"setContent\", \"target\":\"" . $target . "\", \"newval\":\"" . $newcont . "\" }";
		
	}
	
	
	public function newElement( $type,$target,$attributes,$newcont ){ // ------------------------------------ NEW ELEMENT
		
		$newinner = $this->prepare( $newcont );
		
		$atkeys = array_keys( $attributes );
		
		$attrr = array();
		
		for( $i=0; $i<count( $atkeys ); $i++ ){
			
			$attrr[] = "{ \"att\":\"" . $atkeys[$i] . "\" , \"content\":\"" . $attributes[ $atkeys[$i] ] . "\" }";
		}
		
		$this->inside[] = "{ \"action\":\"newElement\" , \"type\":\"" . $type . "\" , \"target\":\"" . $target . "\" ,"
		
						. " \"attributes\":[" . implode(',',$attrr) . "] ,"
						
						. " \"newcontent\":\"" . $newinner . "\" }";
	   
	}
	
	
	public function setValue( $target,$newval ){ // ---------------------------------------------------------- SET VALUE
		
		$this->inside[] = "{ \"action\":\"setValue\", \"target\":\"" . $target . "\", \"newval\":\"" . $newval . "\" }";
	
	}
	
	public function setDisplay( $target,$newstyle ){
		
		$this->inside[] = "{ \"action\":\"setDisplay\", \"target\":\"" . $target . "\", \"newval\":\"" . $newstyle . "\" }";
	
	}
	
	public function setBgColor( $target,$newcolor ){
		
		$this->inside[] = "{ \"action\":\"setBgColor\", \"target\":\"" . $target . "\", \"newval\":\"" . $newcolor . "\" }";
		
	}
	
	public function setColor( $target,$newcolor ){
		
		$this->inside[] = "{ \"action\":\"setColor\", \"target\":\"" . $target . "\", \"newval\":\"" . $newcolor . "\" }";
		
	}
	
	public function makeEnabled( $target ){
		
		$this->inside[] = "{ \"action\":\"makeEnabled\", \"target\":\"" . $target . "\" }";
		
	}
	
	public function makeDisabled( $target ){
		
		$this->inside[] = "{ \"action\":\"makeDisabled\", \"target\":\"" . $target . "\" }";
		
	}
	
	public function makeChecked( $target ){
		
		$this->inside[] = "{ \"action\":\"makeChecked\", \"target\":\"" . $target . "\" }";
	
	}
	
	
	public function makeUnchecked( $target ){
		
		$this->inside[] = "{ \"action\":\"makeUnchecked\", \"target\":\"" . $target . "\" }";
		
	}
	
	
	public function makeSensitive( $target,$event ){ // Adds Event Listener
		
		$this->inside[] = "{ \"action\":\"makeSensitive\", \"target\":\"" . $target . "\", \"evnt\":\"" . $event . "\" }";
	}
	
	
	public function addLineBreak( $target ){ // --------------------------------------------------------------- ADD LINE BREAK
		
		$this->inside[] = "{ \"action\":\"addLineBreak\", \"target\":\"" . $target . "\" }";
	}
	
	
	/*public function setBuffer( $toset ){ // Input must be an array
		if(!is_array($toset)){ return false; }
		$laid = "";
		for($i=0; $i<count($toset); $i++)
		{
			if($i>0){ $laid = $laid . ", "; }
			$laid = $laid . "{ \"setval\":\"" . $toset[$i] . "\" }";
		}
		$this->inside[] = "{ \"action\":\"setBuffer\", \"toset\": [ " . $laid . " ] }";
	}*/
	
	public function setLoginInfos( $loginok,$status ){
		
		$this->inside[] = "{ \"action\":\"setLoginInfos\", \"loginok\":\"" . $loginok . "\", \"userstatus\":\"" . $status . "\" }";
		
	}
	
	public function nothing(){
		
		$this->inside[] = "{ \"action\":\"nothing\" }";
		
	}
}

$json = new JSON();

?>