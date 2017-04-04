<?php
//This will generate class MailerSubject for all Links in the mailer architecture 
//that will be difined as consts.
//@author Ayush
$socialRoot=realpath(dirname(__FILE__)."/../..");

$subMailer=fopen($socialRoot."/lib/model/lib/MailerType.class.php","w");
$now=date("Y-m-d");
include_once($socialRoot."/web/profile/connect.inc");
fwrite($subMailer,"<?php\n /*
	This is auto-generated class by running lib/utils/EmailTypeCreater.php
	This class should not be updated manually.
	Created on $now
	 */
	class MailerType{
		private static \$emailTypeArray=array(\n");
	
$db=connect_db();
// Entries for LinkArray having Id as key and values as Name,Url,ReqAutoLogin
$sql="select * from jeevansathi_mailer.EMAIL_TYPE order by ID";
	$result=mysql_query_decide($sql);

	while($myrow=mysql_fetch_array($result))
	{	

		$temp["ID"] = $myrow["ID"] != 'NULL' ? "'".$myrow["ID"]."'" : "''"; 
		$temp["MAIL_ID"] = $myrow["MAIL_ID"] != 'NULL' ?  "'".$myrow["MAIL_ID"]."'"  : '';
		$temp["TPL_LOCATION"] = $myrow["TPL_LOCATION"] != 'NULL' ? "'".$myrow["TPL_LOCATION"]."'" : "''";
		$temp["HEADER_TPL"] = $myrow["HEADER_TPL"] != 'NULL' ? "'".$myrow["HEADER_TPL"]."'" : "''";
		$temp["FOOTER_TPL"] = $myrow["FOOTER_TPL"] != 'NULL' ? "'".$myrow["FOOTER_TPL"]."'" : "''";
		$temp["TEMPLATE_EX_LOCATION"] = $myrow["TEMPLATE_EX_LOCATION"] != 'NULL' ? "'".$myrow["TEMPLATE_EX_LOCATION"]."'" : "''"; 
		$temp["MAIL_GROUP"] = $myrow["MAIL_GROUP"] != 'NULL' ? "'".$myrow["MAIL_GROUP"]."'" : "''"; 
		$temp["CUSTOM_CRITERIA"] = $myrow["CUSTOM_CRITERIA"] != 'NULL' ? "'".$myrow["CUSTOM_CRITERIA"]."'" : "''";
		$temp["SENDER_EMAILID"] = $myrow["SENDER_EMAILID"]!= 'NULL' ? "'".$myrow["SENDER_EMAILID"]."'" : "''";
		$temp["DESCRIPTION"] = $myrow["DESCRIPTION"] != 'NULL' ? "'".$myrow["DESCRIPTION"]."'" : "''";
		$temp["MEMBERSHIP_TYPE"] = $myrow["MEMBERSHIP_TYPE"] != 'NULL' ? "'".$myrow["MEMBERSHIP_TYPE"]."'" : "''";
		$temp["GENDER"] = $myrow["GENDER"] != 'NULL' ? "'".$myrow["GENDER"]."'" : "''";
		$temp["PHOTO_PROFILE"] = $myrow["PHOTO_PROFILE"] != 'NULL' ? "'".$myrow["PHOTO_PROFILE"]."'" : "''";
		$temp["REPLY_TO_ENABLED"] = $myrow["REPLY_TO_ENABLED"] != 'NULL' ? "'".$myrow["REPLY_TO_ENABLED"]."'" : "''";
		$temp["FROM_NAME"] = $myrow["FROM_NAME"] != 'NULL' ? "'".$myrow["FROM_NAME"]."'" : "''";
		$temp["REPLY_TO_ADDRESS"] = $myrow["REPLY_TO_ADDRESS"] != 'NULL' ? "'".$myrow["REPLY_TO_ADDRESS"]."'" : "''"; 
		$temp["MAX_COUNT_TO_BE_SENT"] = $myrow["MAX_COUNT_TO_BE_SENT"] != 'NULL' ? "'".$myrow["MAX_COUNT_TO_BE_SENT"]."'" : "''";
		$temp["REQUIRE_AUTOLOGIN"] = $myrow["REQUIRE_AUTOLOGIN"] != 'NULL' ? "'".$myrow["REQUIRE_AUTOLOGIN"]."'" : "''";
		$temp["FTO_FLAG"] = $myrow["FTO_FLAG"] != 'NULL' ? "'".$myrow["FTO_FLAG"]."'" : "''";
		$temp["PRE_HEADER"] = $myrow["PRE_HEADER"] != 'NULL' ? "'".$myrow["PRE_HEADER"]."'" : "''";
		$temp["PARTIALS"] = $myrow["PARTIALS"] != 'NULL' ? "'".$myrow["PARTIALS"]."'": "''";

		fwrite($subMailer,"'".$myrow["ID"]."'=>array('ID'=>".$temp["ID"].",'MAIL_ID'=>".$temp["MAIL_ID"].",'TPL_LOCATION'=>".$temp["TPL_LOCATION"].",'HEADER_TPL'=>".$temp["HEADER_TPL"].",'FOOTER_TPL'=>".$temp["FOOTER_TPL"].",'TEMPLATE_EX_LOCATION'=>".$temp["TEMPLATE_EX_LOCATION"].",'MAIL_GROUP'=>".$temp["MAIL_GROUP"].",'CUSTOM_CRITERIA'=>".$temp["CUSTOM_CRITERIA"].",'SENDER_EMAILID'=>".$temp["SENDER_EMAILID"].",'DESCRIPTION'=>".$temp["DESCRIPTION"].",'MEMBERSHIP_TYPE'=>".$temp["MEMBERSHIP_TYPE"].",'GENDER'=>".$temp["GENDER"].",'PHOTO_PROFILE'=>".$temp["PHOTO_PROFILE"].",'REPLY_TO_ENABLED'=>".$temp["REPLY_TO_ENABLED"].",'FROM_NAME'=>".$temp["FROM_NAME"].",'REPLY_TO_ADDRESS'=>".$temp["REPLY_TO_ADDRESS"].",'MAX_COUNT_TO_BE_SENT'=>".$temp["MAX_COUNT_TO_BE_SENT"].",'REQUIRE_AUTOLOGIN'=>".$temp["REQUIRE_AUTOLOGIN"].",'FTO_FLAG'=>".$temp["FTO_FLAG"].",'PRE_HEADER'=>".$temp["PRE_HEADER"].",'PARTIALS'=>".$temp["PARTIALS"]."),\n");
	}

fwrite($subMailer,');');
fwrite($subMailer,"\n \n \n public static function getemailTypeArray(\$mailId){
        return MailerType::\$emailTypeArray[\$mailId];}\n
}");
	mysql_free_result($result);
