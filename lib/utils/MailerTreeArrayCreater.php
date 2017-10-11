<?php
//This will generate class MailerTreeArray for all MailerTrypes in the mailer architecture 
//that will be difined as consts.
//@author Nitesh
$socialRoot=realpath(dirname(__FILE__)."/../..");

$fhobby=fopen($socialRoot."/lib/model/lib/MailerTreeArray.class.php","w");
$now=date("Y-m-d");
include_once($socialRoot."/web/profile/connect.inc");
fwrite($fhobby,"<?php\n /*
	This is auto-generated class by running lib/utils/MailerTreeArray.class.php
	This class should not be updated manually.
	Created on $now
	unit test of this class is test/unit/mailer/MailerTreeArrayTest.php
 */
	class MailerTypesArray{
		public static \$type_array=array();\npublic static function fillArray(){\n");
	
$db=connect_db();
// Entries for LinkArray having Id as key and values as Name,Url,ReqAutoLogin
$sql="select * from jeevansathi_mailer.EMAIL_TYPE_TST order by MAIL_GROUP";
$res=mysql_query_decide($sql);
while($row=mysql_fetch_assoc($res)){
fwrite($fhobby,"self::\$type_array[".$row[MAIL_GROUP]."]['".$row[GENDER]."']['".$row[MEMBERSHIP_TYPE]."']['".$row[PHOTO_PROFILE]."']['".$row[SOURCE]."']['".$row[RELATION]."']['".$row[CUSTOM_CRITERIA]."']['".$row[FTO_FLAG]."']=".$row[MAIL_ID].";\n");
}
	fwrite($fhobby,"print_r(self::\$type_array);}\n}\nMailerTypesArray::fillArray();");
		//ftruncate($fhobby, rand(1, filesize($socialRoot."/lib/model/lib/LinkArray.class.php")));
		//rewind($fhobby);
		//rtrim($string);
		//$string=substr($string, 0, -2);
//		echo $string;
/**
fwrite($fhobby,");\npublic static function TraverseMail(\$ConditionalArray){


foreach(MailerTypesArray::\$MailerArray as \$k =>\$val){

if(sizeof(array_diff(\$val,\$ConditionalArray))===0){
	\$flag=\$k;
}
}
return \$flag;
}

}
");
mysql_free_result($result);*/
