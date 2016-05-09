<?php
	//131071 === 4194304
$symfonyFilePath = JsConstants::$cronDocRoot;
include_once($symfonyFilePath."/lib/model/lib/FieldMapLib.class.php");
include_once($symfonyFilePath."/lib/model/lib/Flag.class.php");
	$FLAGS_VAL=array("SUBCASTE" => 1,
					"CITYBIRTH" => 2,
					"GOTHRA" => 4,
					"NAKSHATRA" => 8,
					"MESSENGER_ID" => 16,
					"YOURINFO" => 32,
					"FAMILYINFO" => 64,
					"SPOUSE" => 128,
					"CONTACT" => 256,
					"EDUCATION" => 512,
					"PHONE_RES" => 1024,
					"PHONE_MOB" => 2048,
					"EMAIL" => 4096,
					"JOB_INFO"=>8192,
					"FATHER_INFO"=>16384,
					"SIBLING_INFO"=>32768,
					"PARENTS_CONTACT"=>65536,
					"USERNAME"=>131072,//new
					"NAME"=>262144,
					"ANCESTRAL_ORIGIN"=>524288,
					"PHONE_OWNER_NAME"=>1048576,
					"MOBILE_OWNER_NAME"=>2097152,
					);

function setFlag($FLAGID,$value)
	{
		return Flag::setFlag($FLAGID,$value);
	}
	
	function removeFlag($FLAGID,$value)
	{
		return Flag::removeFlag($FLAGID,$value);
	}
	
	function setAllFlags()
	{
		return Flag::setAllFlags();
	}
	
	function setAllPhotoFlags()
	{
		return Flag::setAllPhotoFlags();
	}
	
	function isFlagSet($FLAGID,$value)
	{
		return Flag::isFlagSet($FLAGID,$value);
	}
	function areAllBitsSet($value)
	{
		return Flag::areAllBitsSet($value);
	}

?>
