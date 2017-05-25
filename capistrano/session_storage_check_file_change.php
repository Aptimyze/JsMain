<?php
$realPath=dirname(__FILE__);
$path= dirname($realPath);
$clearStorageFlag = FALSE;
$filecontent = file_get_contents("/tmp/capistranochangelog.txt");
$splitFileContent = explode("\n",$filecontent);
$mappingFilesModifyTest = Array("lib/model/lib/CoverPhotoMap.class.php","lib/model/lib/FieldMapLib.class.php","lib/model/lib/forms/RegFields.class.php","lib/model/lib/HobbyLib.class.php","lib/model/lib/search/TopSearchBandPopulate.class.php");
foreach($splitFileContent as $k=>$v)
{
	foreach($mappingFilesModifyTest as $i=>$filePath)
	{
		if(strpos($v,$filePath))
		{
			$clearStorageFlag = TRUE;
			break;
		}
	}


}

if($clearStorageFlag == TRUE)
{
	$storageFileRead = fopen($path.'/capistrano/localStorageRevision.txt','r');
	$fileContents= fread($storageFileRead,filesize($path.'/capistrano/localStorageRevision.txt'));
	$fileContents=$fileContents+1;
	fclose($storageFileRead);
	$storageFileWrite = fopen($path.'/capistrano/localStorageRevision.txt','w');
	$writeResult = fwrite($storageFileWrite,$fileContents);
	fclose($storageFileWrite);
	if($writeResult == false){
		echo("NA");
	}
	else
		echo("Commit");
}
else{
	echo("\n Given files not modified \n");
}
?>
