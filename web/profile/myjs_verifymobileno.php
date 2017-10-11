<?php
	include("connect.inc");
	$db=connect_db();
	$data=authenticated($checksum);

	if($_POST['ajax']==1)
	{
		include_once($_SERVER['DOCUMENT_ROOT']."/ivr/jsPhoneVerify.php");
		include_once($_SERVER['DOCUMENT_ROOT']."/ivr/jsivrFunctions.php");
		$profileid =$_POST['profileid'];
		$phoneType =$_POST['type'];	
		$phoneNoL  =$_POST['phoneL'];
		$phoneNoM  =$_POST['phoneM'];
		
		if($phoneNoL && $phoneNoM)
			$phone =$phoneNoL.",".$phoneNoM;
		else if($phoneNoL)
			$phone =$phoneNoL;
		else if($phoneNoM)
			$phone =$phoneNoM;

		if($phoneType =='M'){
			if($ivrStatus) 
				echo "M#SENT#".$phoneNoM;
			else
				echo "M#UNVERIFY#".$phoneNoM;
		}
		else if($phoneType =='L'){
			$phoneArr 	=explode("-",$phoneNoL); 
			$std 		=$phoneArr['0'];
			$landlineNo 	= $phoneArr['1'];	
			if($ivrStatus)
				echo "L#SENT#".$phoneNoL;
			else
				echo "L#UNVERIFY#".$phoneNoL;	
		}	
		else if($phoneType=='Verify'){
			$invalidState   = getInvalidPhone($profileid);
			if($invalidState)
				echo "B#INVALID#".$phone;								
			else{
				$mobileState 	= getPhoneValidity($profileid,'M');
				$landlineState  = getPhoneValidity($profileid,'L');
				if($mobileState && $landlineState)
					echo "B#VERIFY#".$phone; 
				else if($mobileState){
					if($phoneNoM)
						echo "M#VERIFY#".$phone;
					else
						echo "M#VERIFY#".$phone."#L#UNVERIFY";
				}
				else if($landlineState){
					if($phoneNoL)		
						echo "L#VERIFY#".$phone;
					else
						echo "L#VERIFY#".$phone."#M#UNVERIFY";
				}
				else
					echo "B#UNVERIFY#$phone";
			}
		}
		else
			echo "0#FAILED#0";
	}
	else{
		/* By Default Status of the layer( Verify your phone number )
		 * POST variable required: MYMOBILE,LANDLINE,STD	
		*/
		$PROFILEID = $data["PROFILEID"];
		$smarty->assign("PROFILEID",$PROFILEID);	
		if($MYMOBILE)
			$smarty->assign("MYMOBILE",$MYMOBILE);
		if($LANDLINE){
			if($STD)
				$LANDLINE = $STD."-".$LANDLINE;
			$smarty->assign("LANDLINE",$LANDLINE);
		}
        	$smarty->display("myjs_verifymobileno.htm");
	}

// function to check Phone Verification
function getPhoneValidity($profileid,$phone_type)
{
	$phonestatus =getPhoneStatus('',$profileid,$phone_type);
	if($phoneStatus=='Y')
		return true;
	return false;
}

?>
