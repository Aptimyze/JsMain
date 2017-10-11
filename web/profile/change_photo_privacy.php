<?php
include("connect.inc");
connect_db();
$data=authenticated($checksum);
if($json == 1)
{
	if($data){
		$profileid=$data['PROFILEID'];
		$respObj = ApiResponseHandler::getInstance();
		$respObj->setHttpArray(ResponseHandlerConfig::$SUCCESS);
		$respObj->setResponseBody(array("output"=>$photo_display));
		$respObj->generateResponse();
	}
	else{
		$respObj = ApiResponseHandler::getInstance();
          $respObj->setHttpArray(ResponseHandlerConfig::$FAILURE);
          $respObj->generateResponse();
	}

}
else{
if($data)
	$profileid=$data['PROFILEID'];  //getting the profileid
else
{
	echo 'X';
	die;
}
echo $photo_display;
}
$ajax_error=2;


include_once(JsConstants::$docRoot."/classes/JProfileUpdateLib.php");
$objUpdate = JProfileUpdateLib::getInstance();
$nowDate = date('Y-m-d H:i:s');
$result = $objUpdate->editJPROFILE(array('PHOTO_DISPLAY'=>$photo_display,'MOD_DT'=>$nowDate), $profileid, 'PROFILEID');
if(false === $result) {
	$sql="update newjs.JPROFILE set PHOTO_DISPLAY = '$photo_display', MOD_DT = NOW() where newjs.JPROFILE.PROFILEID='$profileid'";
	logError($errorMsg,$sql,"ShowErrTemplate");
}
//$sql="update newjs.JPROFILE set PHOTO_DISPLAY = '$photo_display', MOD_DT = NOW() where newjs.JPROFILE.PROFILEID='$profileid'";
//$result=mysql_query_decide($sql) or logError($errorMsg,$sql,"ShowErrTemplate");
$now = date("Y-m-d H:i:s");
$editArray = array("PHOTO_DISPLAY"=>$photo_display,"PROFILEID"=>$profileid,"MOD_DT"=>$now);
$editLogObj = new EDIT_LOG();
$editLogObj->log_edit($editArray, $profileid);

?>
