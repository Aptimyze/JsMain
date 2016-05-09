<?php
//~ require_once("lib/model/lib/ProfileCompletion/ProfileCompletionFactory.class.php");
//~ require_once("lib/model/lib/ProfileCompletion/ApiProfileCompletionScore.class.php");
/**
 * Funcation Test Class For Profile Completion Score
 * profileCompletionScoreTest
 * 
 * @author Kunal Verma
 * @created 24th March 2015
 */
class profileCompletionScoreTest extends PHPUnit_Framework_TestCase
{
	public function testScore(){
		//Create score Object
		$cScoreObject = ProfileCompletionFactory::getInstance("API",null,7610737); 
		$iPCS = $cScoreObject->getProfileCompletionScore();
		$this->assertTrue(is_numeric($iPCS));		
	}
}
?>
