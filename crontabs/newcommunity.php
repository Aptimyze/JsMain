<?php 
  $curFilePath = dirname(__FILE__)."/"; 
 include_once("/usr/local/scripts/DocRoot.php");

	//include("connect.inc");
	
	//$db=connect_db();
	
	$sql="select * from NEW_COMMUNITY";
	$result=mysql_query($sql,$db) or die($sql);
	
	while($myrow=mysql_fetch_array($result))
	{
		$dirname=$myrow["DIRECTORY"];
		
		// check whether directory with this name already exists
		if(!file_exists("$_SERVER[DOCUMENT_ROOT]/matrimonials/$dirname"))
		{
			// create the directory, mode is specified as octal number, hence the leading zero
			mkdir("$_SERVER[DOCUMENT_ROOT]/matrimonials/$dirname",0775);
			
			$fp=fopen("$_SERVER[DOCUMENT_ROOT]/matrimonials/$dirname/index.php","w");
			if($fp)
			{
				$id=$myrow["ID"];
				$fld=$myrow["FLD"];
				$community=$myrow["VALUE"];
				
				
				fwrite($fp,"<?php
\$fld=\"$fld\";
\$community=\"$community\";
include(realpath(\"$docRoot/web/profile/community_matrimonial.php\"));

?>");
				fclose($fp);
				
				$sql="insert into MATRIMONIAL(GENDER,FLD,COMMUNITY,TITLE,DESCRIPTION,KEYWORD,LOGO_STRING,NAME,NAME_OF_COMMUNITY) values ('M','$fld','$community','" . addslashes($myrow["TITLE"]) . "','" . addslashes($myrow["DESCRIPTION"]) . "','" . addslashes($myrow["KEYWORD"]) . "','" . addslashes($myrow["LOGO_STRING"]) . "','" . addslashes($myrow["NAME"]) . "','" .addslashes($myrow["NAME_OF_COMMUNITY"]) . "')";
				mysql_query($sql,$db) or die($sql);
				
				$sql="insert into MATRIMONIAL(GENDER,FLD,COMMUNITY) values ('F','$fld','$community')";
				mysql_query($sql,$db) or die($sql);
				
				$sql="delete from NEW_COMMUNITY where ID='$id'";
				mysql_query($sql,$db) or die($sql);
			}
			else 
			{
				echo "could not open file $_SERVER[DOCUMENT_ROOT]/matrimonials/$dirname/index.php";
			}
		}
		else 
		{
			echo "directory $dirname already exists\n";
		}
	}
?>
