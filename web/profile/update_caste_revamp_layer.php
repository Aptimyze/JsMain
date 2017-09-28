<?php
        include("connect.inc");
        $db=connect_db();
        $data=authenticated();
        if(!$data)
        {
		include_once("include_file_for_login_layer.php");
                $smarty->display("login_layer.htm");
                die;
        }

	$profileid = $data["PROFILEID"];
	if($caste)
	{
		$paramArr[] = "CASTE = ".$caste;

		$duplication_fields[]="CASTE";
	}
	else
		$caste = 0;
	if($occ)
	{
		$paramArr[] = "OCCUPATION = ".$occ;

		$duplication_fields[]="OCCUPATION";
	}
	else
		$occ = 0;
	if($edu)
	{
		$paramArr[] = "EDU_LEVEL_NEW = ".$edu;
		$statement = "SELECT OLD_VALUE FROM newjs.EDUCATION_LEVEL_NEW WHERE VALUE = ".$edu;
		$result = mysql_query($statement,$db) or logError("due to some temporary problem your request could not be processed. please try after some time.",$statement,"ShowErrTemplate");
		$row = mysql_fetch_array($result);
		$edu_level = $row["OLD_VALUE"];
		$paramArr[] = "EDU_LEVEL = ".$edu_level;

		$duplication_fields[]="EDU_LEVEL_NEW";
	}
	else
	{
		$edu = 0;
		$edu_level = 0;
	}
	$paramStr = implode(",",$paramArr);
	$statement = "UPDATE newjs.JPROFILE SET ".$paramStr." WHERE PROFILEID = ".$profileid;
	mysql_query($statement,$db) or logError("due to some temporary problem your request could not be processed. please try after some time.",$statement,"ShowErrTemplate");
	$statement = "INSERT newjs.EDIT_LOG(PROFILEID,CASTE,OCCUPATION,EDU_LEVEL_NEW,EDU_LEVEL,MOD_DT) VALUES (".$profileid.",".$caste.",".$occ.",".$edu.",".$edu_level.",NOW())";
	mysql_query($statement,$db) or logError("due to some temporary problem your request could not be processed. please try after some time.",$statement,"ShowErrTemplate");
	$statement = "UPDATE MIS.REVAMP_LAYER_CHECK SET CASTE_REVAMP_FLAG = 0 WHERE PROFILEID = ".$profileid;
	mysql_query($statement,$db) or logError("due to some temporary problem your request could not be processed. please try after some time.",$statement,"ShowErrTemplate");

      ///Duplication fields update on edit///////
	///////////////////////////////////////////

        if(is_array($duplication_fields))
        {
		duplication_fields_insertion($duplication_fields,$profileid);
	}
	///////////////////////////////////////////
	///////////////////////////////////////////

	echo "CASTE_DONE";
?>
