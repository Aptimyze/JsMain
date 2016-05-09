<?php
/**
 *	Author:Sanyam Chopra
 *	This file will check the capistranoChangeLog and search for the given array to files to check if those files have been modified.
 *  If any of those files have been modified, then a localStorageRevision.txt file is opened and the revision number is updated.
 */

$realPath=dirname(__FILE__);
$path= dirname($realPath);
$clearStorageFlag = false;
$intialLocalStorageValue = "1";
$textFilePath="/capistrano/localStorageRevision.txt";
$filecontent = file_get_contents("/tmp/capistranochangelog.txt");
$mappingFilesModifyTest = Array("lib/model/lib/CoverPhotoMap.class.php","lib/model/lib/FieldMapLib.class.php","lib/model/lib/forms/RegFields.class.php","lib/model/lib/HobbyLib.class.php","lib/model/lib/search/TopSearchBandPopulate.class.php");

//The loop takes one array value at a time and finds the substring in the capistranoChangeLog.txt. If found,it sets $clearStorageFlag=true and breaks

foreach($mappingFilesModifyTest as $value)
{
	if(strpos($filecontent,$value))
	{
		$clearStorageFlag = true;
		break;
	}

}

/* If the $clearStorageFlag is set to true, localStorageRevision.txt file is created/opened and the file contents
 * are read and updated back to the file and the result is accordingly echoed.
 */
if($clearStorageFlag == true)
{	
	if(!file_exists($path.$textFilePath))
	{
		$createTextFile = fopen($path.$textFilePath,'w');
		$writeResult = fwrite($createTextFile,$intialLocalStorageValue);
		fclose($createTextFile);
	}
	$storageFileRead = fopen($path.$textFilePath,'r');
	$fileContents= fread($storageFileRead,filesize($path.$textFilePath));
	$fileContents=$fileContents+1;
	fclose($storageFileRead);
	$storageFileWrite = fopen($path.$textFilePath,'w');
	$writeResult = fwrite($storageFileWrite,$fileContents);
	fclose($storageFileWrite);
	if($writeResult == false){
		echo("NA");
	}
	else
		echo("Commit");
}
else
{
	echo("\n Given files not modified \n");
}
?>
