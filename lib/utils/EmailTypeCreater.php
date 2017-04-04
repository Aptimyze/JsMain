<?php
//This will generate class MailerSubject for all Links in the mailer architecture 
//that will be difined as consts.
//@author Ayush
$socialRoot=realpath(dirname(__FILE__)."/../..");

$subMailer=fopen($socialRoot."/lib/model/lib/MailerType.class.php","w");
$now=date("Y-m-d");
include_once($socialRoot."/web/profile/connect.inc");
fwrite($subMailer,"<?php\n /*
	This is auto-generated class by running lib/utils/MailerSubjectCreater.php
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
		fwrite($subMailer,"'".$myrow["ID"]."'=>array('ID'=>'".$myrow["ID"]."','MAIL_ID'=>'".$myrow["MAIL_ID"]."','TPL_LOCATION'=>'".$myrow["TPL_LOCATION"]."','HEADER_TPL'=>'".$myrow["HEADER_TPL"]."','FOOTER_TPL'=>'".$myrow["FOOTER_TPL"]."','TEMPLATE_EX_LOCATION'=>'".$myrow["TEMPLATE_EX_LOCATION"]."','MAIL_GROUP'=>'".$myrow["MAIL_GROUP"]."','CUSTOM_CRITERIA'=>'".$myrow["CUSTOM_CRITERIA"]."','SENDER_EMAILID'=>'".$myrow["SENDER_EMAILID"]."','DESCRIPTION'=>'".$myrow["DESCRIPTION"]."','MEMBERSHIP_TYPE'=>'".$myrow["MEMBERSHIP_TYPE"]."','GENDER'=>'".$myrow["GENDER"]."','PHOTO_PROFILE'=>'".$myrow["PHOTO_PROFILE"]."','REPLY_TO_ENABLED'=>'".$myrow["REPLY_TO_ENABLED"]."','FROM_NAME'=>'".$myrow["FROM_NAME"]."','REPLY_TO_ADDRESS'=>'".$myrow["REPLY_TO_ADDRESS"]."','MAX_COUNT_TO_BE_SENT'=>'".$myrow["MAX_COUNT_TO_BE_SENT"]."','REQUIRE_AUTOLOGIN'=>'".$myrow["REQUIRE_AUTOLOGIN"]."','FTO_FLAG'=>'".$myrow["FTO_FLAG"]."','PRE_HEADER'=>'".$myrow["PRE_HEADER"]."','PARTIALS'=>'".$myrow["PARTIALS"]."'),\n");
	}

fwrite($subMailer,');');
fwrite($subMailer,"\n \n \n public static function getemailTypeArray(\$mailId){
        return MailerType::\$emailTypeArray[\$mailId];}\n
}");
	mysql_free_result($result);
