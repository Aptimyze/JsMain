<?php
//This will generate class LinkArray for all Links in the mailer architecture 
//that will be difined as consts.
//@author Nitesh
$socialRoot=realpath(dirname(__FILE__)."/../..");

$fhobby=fopen($socialRoot."/lib/model/lib/MailerArray.class.php","w");
$now=date("Y-m-d");
include_once($socialRoot."/web/profile/connect.inc");
fwrite($fhobby,"<?php\n /*
	This is auto-generated class by running lib/utils/MailerArrayCreater.php
	This class should not be updated manually.
	Created on $now
	unit test of this class is test/unit/mailer/MailerArrayTest.php
 */
	class MailerArray{
		private static \$linkarray=array(\n");
	
$db=connect_db();
// Entries for LinkArray having Id as key and values as Name,Url,ReqAutoLogin
$sql="select * from jeevansathi_mailer.LINK_MAILERS order by LINKID";
	$result=mysql_query_decide($sql);

	while($myrow=mysql_fetch_array($result))
	{
		//$string.=$myrow["LINK_NAME"]."=>array('ID'=>'".$myrow["LINKID"]."','URL'=>'".$myrow["LINK_URL"]."','OTHER_GET_PARAMS'=>'".$myrow["OTHER_GET_PARAMS"]."','REQAUTOLOGIN'=>'".$myrow["REQUIRED_AUTOLOGIN"]."'),\n";
		
		fwrite($fhobby,"'".$myrow["LINKID"]."'=>array('APP_SCREEN_ID'=>'".$myrow["APP_SCREEN_ID"]."','LINK_NAME'=>'".$myrow["LINK_NAME"]."','URL'=>'".$myrow["LINK_URL"]."','OTHER_GET_PARAMS'=>'".$myrow["OTHER_GET_PARAMS"]."','REQAUTOLOGIN'=>'".$myrow["REQUIRED_AUTOLOGIN"]."','OUTER_LINK'=>'".$myrow["OUTER_LINK"]."'),\n");
	}
		//ftruncate($fhobby, rand(1, filesize($socialRoot."/lib/model/lib/LinkArray.class.php")));
		//rewind($fhobby);
		//rtrim($string);
		//$string=substr($string, 0, -2);
//		echo $string;
	fwrite($fhobby,");\nprivate static \$linkNameArray=array(\n");
mysql_data_seek($result,0); 
	while($myrow=mysql_fetch_array($result))
	{
		fwrite($fhobby,"'".$myrow["LINK_NAME"]."'=>'".$myrow["LINKID"]."',\n");
	}
		fwrite($fhobby,");\nprivate static \$variableArray=array(\n");
	$sql="select * from jeevansathi_mailer.MAILER_TEMPLATE_VARIABLES_MAP";
	mysql_free_result($result);
	$result=mysql_query_decide($sql);
	while($myrow=mysql_fetch_array($result))
	{
		fwrite($fhobby,"'".$myrow["VARIABLE_NAME"]."'=>array('VARIABLE_PROCESSING_CLASS'=>'".$myrow["VARIABLE_PROCESSING_CLASS"]."','MAX_LENGTH'=>'".$myrow["MAX_LENGTH"]."','MAX_LENGTH_SMS'=>'".$myrow["MAX_LENGTH_SMS"]."','DEFAULT_VALUE'=>'".$myrow["DEFAULT_VALUE"]."','DESCRIPTION'=>'".$myrow['DESCRIPTION']."'),\n");
	}

	fwrite($fhobby,");\nprivate static \$subjectArray=array(\n");
	$sql = "SELECT MAIL_ID , SUBJECT_TYPE , SUBJECT_CODE FROM jeevansathi_mailer.MAILER_SUBJECT";
	mysql_free_result($result);
	$result=mysql_query_decide($sql);

	while($myrow=mysql_fetch_array($result))
	{
		fwrite($fhobby,"'".$myrow["MAIL_ID"]."'=>array('MAIL_ID'=>'".$myrow["MAIL_ID"]."','SUBJECT_TYPE'=>'".$myrow["SUBJECT_TYPE"]."','SUBJECT_CODE'=>'".$myrow["SUBJECT_CODE"]."'),\n");
	}

fwrite($fhobby,");\npublic static function getLink(\$Id){

return MailerArray::\$linkarray[\$Id];}\n
");
fwrite($fhobby,"public static function getLinkId(\$Name){

return MailerArray::\$linkNameArray[\$Name];}
");
fwrite($fhobby,"public static function getVariable(\$Name){
	return MailerArray::\$variableArray[\$Name];}\n
");
fwrite($fhobby,"public static function getLinkArray(){
        return MailerArray::\$linkNameArray;}\n
");
fwrite($fhobby,"public static function getMailerSubject(\$Id){

		\$i = 0;

		foreach (MailerArray::\$subjectArray as \$key => \$value) {
				
		if(\$key == \$Id)
		{	
		\$subjectCodeArr[\$i]['SUBJECT_TYPE']=\$value['SUBJECT_TYPE'];
		\$subjectCodeArr[\$i]['SUBJECT_CODE']=\$value['SUBJECT_CODE'];
		\$i++;
		}


		}
		return \$subjectCodeArr;	

		}	

}");
	mysql_free_result($result);
