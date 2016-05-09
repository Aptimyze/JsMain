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
$sql="update newjs.JPROFILE set PHOTO_DISPLAY = '$photo_display', MOD_DT = NOW() where newjs.JPROFILE.PROFILEID='$profileid'";
$result=mysql_query_decide($sql) or logError($errorMsg,$sql,"ShowErrTemplate");

?>
