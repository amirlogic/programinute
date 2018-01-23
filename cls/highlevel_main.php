<?php

// Prescript Main High Level Functions - CDT Switch [$hgmain] 

// Copyright 2015 Amir Hachaichi

/*
# Init list
#	
#	
#
#
#
#
*/
	

	class HighLevelMain extends TBIPrescriptMain {
		
		
		private $switchid;
		
		public $newstemid;
		
		
		public function __construct( $psid ){
			
			$this->prescript = $psid;
			$this->lasterror = false;
			
		}
		
		
		public function addOutputText( $stmid,$oid,$blockid ){ // ------------------------------------------------ ADD OUTPUT TEXT
			
			global $dbz;
			
			$this->getStem( $stmid );
			
			$this->stemClear();
			
			$this->startNewSub(); // Output text is sub
			
			$this->addSubStart( 'output_text',$oid );
			
			$this->nextSubInsert();
			
			$this->addSubCmd( 'output_block',$blockid );
			
			$this->nextSubInsert();
			
			$this->addStem( 'output_block',$oid ); // Sub Stem
			
			$this->newstemid = $dbz->lastInsertId(); // Storing stem id
			
			$this->nextSubInsert();
			
			$this->addSubEnd('output_text');
			
			
		}
		
		
		public function addOutputBlock( $blockid ){ // --------------------------------------------------------------- ADD OUTPUT BLOCK
			
			//$this->getStem( $stmid );
			
			$this->stemClear();
			
			$this->addSubCmd( 'output_block',$blockid );
			
			
		}
		
		
		public function addInputText( $stmid,$oid ){ // -------------------------------------------------------------- ADD INPUT TEXT
			
			$this->getStem( $stmid ); 
			
			$this->stemClear();
			
			$this->addSubCmd( 'input_text',$oid );
			
		}
		
		
		public function addConditionTable( $stmid,$tblid ){ // ------------------------------------------------------ CDT HEADER ( TABLE )
			
			$this->getStem( $stmid ); 
			
			$this->stemClear();
			
			$this->addCdtHeader( $tblid );
			
		}
		
		
		public function newPrescript(){ // --------------------------------------------------------------------------- NEW PRESCRIPT
			
			
			// ADD INPUT SEC
			
			$this->startFromZero(); // Set positions and starts sub
			
			$this->addSubStart( 'input_sec','' );
			
			$this->nextSubInsert();
			
			$this->addStem( 'input','' );
			
			$this->nextSubInsert();
			
			$this->addSubEnd( 'input_sec' );
			
			$this->exitSub();
			
			
			// ADD OUTPUT SEC
			
			$this->nextPosition();
			
			$this->addSectionTag( 'secstart_output' );
			
			$this->nextPosition();
			
			$this->addStem( 'output','' );
			
			$this->nextPosition();
			
			$this->addSectionTag( 'secend_output' );
			
			
			// ADD FLOW SEC
			
			$this->nextPosition();
			
			$this->addSectionTag( 'secstart_flow' );
			
			$this->nextPosition();
			
			$this->addStem( 'flow','' );
			
			$this->nextPosition();
			
			$this->addSectionTag( 'secend_flow' );
			
			
		}
		
		
		public function addOutputCall( $stmid,$outype,$callid ){ // ---------------------------------------------------- OUTPUT CALL
			
			$this->getStem( $stmid ); 
			
			$this->stemClear();
			
			$this->addOutCall( $outype,$callid );
			
		}
		
		
		public function addProcessing( $stmid,$prid ){ // -------------------------------------------------------------- PROCESSING
			
			$this->getStem( $stmid ); 
			
			$this->stemClear();
			
			$this->addVarProc( $prid );
			
		}
	
	
	
	}
	
	
?>