<?php
//@author Rohit
$socialRoot=realpath(dirname(__FILE__)."/../..");
$fp=fopen($socialRoot."/lib/model/lib/ContactEngineMap.class.php","w");
$now=date("Y-m-d");
include_once($socialRoot."/web/profile/connect.inc");
fwrite($fp,"<?php\n /*
	This is auto-generated class by running lib/utils/ContactEngineCreater.php
	This class should not be updated manually.
	Created on $now
 */
	class ContactEngineMap
	{
		/*This will return label corresponding to value*/
    	public static function getFieldLabel(\$label,\$value,\$returnArr='')
    	{
			switch(\$label){
				//contact engine template data starts from here
				case \"template_data\":\n
				\$arr=array(\n");
    $db=connect_db();
	$sql ="SELECT * FROM CONTACT_ENGINE.TEMPLATE_NAME";
	$result=mysql_query($sql);
	$i=0;
	while($row=mysql_fetch_array($result))
	{
		fwrite($fp,"$i=>array(\"CONTACT_TYPE\"=>'".$row['CONTACT_TYPE']."',\"PROFILE_STATE\"=>'".$row['PROFILE_STATE']."',\"TO_BE_STATUS\"=>'".$row['TO_BE_STATUS']."',\"ENGINE_TYPE\"=>'".$row['ENGINE_TYPE']."',\"PAGE\"=>'".$row['PAGE']."',\"TEMPLATE_NAME\"=>'".$row['TEMPLATE_NAME']."',\"SENDER_RECEIVER\"=>'".$row['SENDER_RECEIVER']."',\"ACTION_TYPE\"=>'".$row['ACTION_TYPE']."'),\r\n");
		$i++;
	}
	mysql_free_result($result);
	fwrite($fp,");\n
	break;\n
	
	//Button response data starts from here
	case \"BUTTON_RESPONSE\":\n
		\$arr=array(\n");

	$sql = "SELECT * FROM CONTACT_ENGINE.BUTTON_RESPONSE ORDER BY ID";
	$result = mysql_query_decide($sql) or die(mysql_error());
	$i=0;
	while ($myrow = mysql_fetch_array($result))
	{
		fwrite($fp,"$i=>array(\"ID\"=>'".$myrow["ID"]."',\"CHANNEL\"=>'".$myrow["CHANNEL"]."',\"SOURCE\"=>'".$myrow["SOURCE"]."',\"VIEWER\"=>'".$myrow["VIEWER"]."',\"CONTACT_TYPE\"=>'".$myrow["TYPE"]."',\"BUTTONS\"=>'".$myrow["BUTTONS"]."'),\r\n");
		$i++;
	}
	mysql_free_result($result);
	fwrite($fp,");\n
	break;\n
	
	//contact engine Error data starts from here
	case \"error_data\":\n
		\$arr=array(\n");

	$sql="SELECT * FROM CONTACT_ENGINE.CONTACT_ERROR ORDER BY ID";
	$result= mysql_query_decide($sql) or die(mysql_error());
	$i=0;
	while($myrow=mysql_fetch_array($result))
	{
		
		fwrite($fp,"$i=>array(\"ID\"=>'".$myrow["ID"]."',\"SENDER_RECEIVER\"=>'".$myrow["SENDER_RECEIVER"]."',\"CONTACT_TYPE\"=>'".$myrow["CONTACT_TYPE"]."',\"ENGINE_TYPE\"=>'".$myrow["ENGINE_TYPE"]."',\"ERROR\"=>'".$myrow["ERROR"]."'),\r\n");
		$i++;
		
	}
	mysql_free_result($result);
	
		
	fwrite($fp,");\n
	break;\n
	
	//contact engine Privilege data starts from here
	case \"privilege_data\":\n
		\$arr=array(\n");	
	//To genrate array of Prrivileges 

$sql="SELECT * FROM CONTACT_ENGINE.CONTACT_PRIVILEGE ORDER BY ID";
$result= mysql_query_decide($sql) or die(mysql_error());

while($myrow=mysql_fetch_array($result))
	{
		
		fwrite($fp,"'".$myrow["ID"]."'=>array('LOGGEDINPROFILE'=>'".$myrow["LOGGEDINPROFILE"]."','OTHERPROFILE'=>'".$myrow["OTHERPROFILE"]."','SENDER_RECIEVER'=>'".$myrow["SENDER_RECIEVER"]."','CONTACT_STATUS'=>'".$myrow["CONTACT_STATUS"]."','CONTACT_TYPE'=>'".$myrow["CONTACT_TYPE"]."','ACTION_TYPE'=>'".$myrow["ACTION_TYPE"]."','PRIVILEGE'=>'".$myrow["PRIVILEGE"]."','ALLOWED'=>'".$myrow["ALLOWED"]."'),\n");
		
	}	
	mysql_free_result($result);	
		
	fwrite($fp,");\n
	break;\n");	
			
	fwrite($fp,"\ndefault:\n
				break;\n
			}\n
			if(\$returnArr)\n
				return \$arr;\n
			else\n
				return \$arr[\$value];\n
			}\n
		}\n
?>\n");

fclose($fp);	
		
