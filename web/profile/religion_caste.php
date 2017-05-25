<?php
$symfonyFilePath=realpath($_SERVER['DOCUMENT_ROOT']."/../");
include_once($symfonyFilePath."/lib/model/lib/FieldMapLib.class.php");
if($_GET['religion'])
{
	$religion = $_GET['religion'];
	$casteString = FieldMap::getFieldLabel("religion_caste",$religion);
	if($casteString)
	{
		$valArr=explode(",",$casteString);
		foreach($valArr as $kk=>$vv)
		{
			$caste=FieldMap::getFieldLabel("caste",$vv);
			
			$caste=preg_replace('/[A-Z][a-z]{3,10}[:][ ]/',"",$caste);
			echo '<option value="'.$kk.'">'.$caste.'</option>';
		}	
	}
}
?>
