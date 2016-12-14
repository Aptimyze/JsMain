<?php
/**
 *
 * @package    jeevansathi
 * @author     Mohammad Shahjahan
 */
class VSPLoadCheckV1Action extends sfAction
{
 /**
  * Executes index action
  *
  * @param sfRequest $request A request object
  */

 function execute($request){
 	$decryptObj= new Encrypt_Decrypt();
 	$jsAuthentication = new jsAuthentication();
 	$viewer = $request->getParameter("viewer");
 	$viewed =  $request->getParameter("viewed");

 	if ( isset($viewer) )
 	{
 		$viewerProfileId = 0;
 		if ( $viewer != "0")
 		{
 			$decryptedAuthChecksumViewer=$decryptObj->decrypt($viewer);
 			$loginDataViewer=$this->fetchLoginData($decryptedAuthChecksumViewer);
 			$viewerProfileId = $loginDataViewer['PROFILEID'];	
 		}
 	}
 	if ( isset($viewed))
 	{
 		$viewedProfileId = $jsAuthentication->jsDecryptProfilechecksum($viewed);
 	}
 	

 	if ( isset($viewerProfileId) && isset($viewedProfileId))
 	{
 		$vspLoadCheck =new VSPLoadCheck();
 		$result = $vspLoadCheck->set($viewerProfileId,$viewedProfileId);
 	}
 	die();
 }

 public function fetchLoginData($checksum)
 {
 	if($checksum)
 	{
 		$temp=$this->explode_assoc('=',':',$checksum);
 		
 		$data["PROFILEID"]=$temp['PR'];
 		return $data;
 	}
 	return null;
 }
 public function explode_assoc($glue1, $glue2, $array)
 {
 	$array2=explode($glue2, $array);
 	foreach($array2 as  $val)
 	{
 		$pos=strpos($val,$glue1);
 		$key=substr($val,0,$pos);
 		$array3[$key] =substr($val,$pos+1,strlen($val));
 	}
 	return $array3;
 }
}
