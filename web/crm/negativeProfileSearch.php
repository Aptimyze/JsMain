<?php
include ("connect.inc");
include ("negativeListFlagArray.php");
$empty=1;
$db =connect_db();

if(authenticated($cid))
{
	$name=getname($cid);
	if(!$whichType)
		$whichType ='O';
        if($submit)
	{
		if (($whichType=='T') && trim($selectTypeDropdown)=="")
		{	
			$empty=0;
                        $smarty->assign('empty_err_msg','1');
		}
		if($whichType=='O') 
		{
			if(trim($mobile)=='' && trim($landline)=='' && trim($website)=='' && trim($email)=='')
                	{
        	       	        $empty=0;
                	        $smarty->assign('empty_err_msg','1');
                	}
			if(trim($email))
			{
			 	if(checkemail($email)){
                	        	$empty=0;
                	        	$smarty->assign('check_email',1);
				}     
           		}
	                if($mobile)
	                {
	                        $mobile =checkPhoneNumber($mobile);
	                        if(!$mobile){
	                                $empty=0;
	                                $smarty->assign('check_mobile','1');
	                        }       
	                }       
	                if($landline)
	                {
	                        $landline =checkPhoneNumber($landline);
	                        if(!$landline){
	                                $empty=0;
	                                $smarty->assign('check_landline','1');
	                        }
			}
			if($std){
	                       	$std =checkPhoneNumber($std);
	                       	if(!$std){
	                       	        $empty=0;
	                       	        $smarty->assign('check_std','1');
	                       	}
	                }

		}
		if($empty==0)
		{
			$typeDropdown =create_dd("$selectTypeDropdown","competition_type");
			$smarty->assign("typeDropdown",$typeDropdown);	
			$smarty->assign('mobile',$mobile);
        		$smarty->assign('landline',$landline);
			$smarty->assign("std",$std);
                        $smarty->assign('website',$website);
			$smarty->assign('email',$email);
			$smarty->assign('cid',$cid);
			$smarty->assign("whichType",$whichType);
                        $smarty->display('negativeProfileSearch.htm');
		}
                else
		{
			$searchString ='';
			if($selectTypeDropdown && $whichType=='T')
				$searchString .=" TYPE='$selectTypeDropdown'";
			elseif($whichType=='O'){
				if($mobile)
					$searchString .=" MOBILE IN('$mobile',0$mobile)";
				if($landline){
					if($mobile && $landline)
						$searchString .=" OR ";
					$searchString .=" (LANDLINE IN('$landline','0$landline') AND STD_CODE IN('$std','0$std'))";
				}
				if($email){
					if(($mobile || $landline) && $email)
						$searchString .=" OR ";
					$searchString .=" EMAIL='$email'";
				}
				if($website){
					if(($mobile || $landline || $email) && $website)
						$searchString .=" OR ";
					$searchString .=" WEBSITE='$website'";
				}
     			}
			$sql ="SELECT * FROM incentive.NEGATIVE_PROFILE_LIST WHERE".$searchString." ORDER BY ID ASC";
			$result =mysql_query_decide($sql,$db) or die("$sql".mysql_error_js());
			$i=0;
			while($row=mysql_fetch_array($result))
			{
				$resultSet[$i]['ID']		=$row['ID'];
				$resultSet[$i]['TYPE']	 	=$row['TYPE'];
				$resultSet[$i]['USERNAME'] 	=$row['USERNAME'];
				$resultSet[$i]['PROFILEID'] 	=$row['PROFILEID'];
				$resultSet[$i]['COMPANY']	=$row['COMPANY'];
				$resultSet[$i]['WEBSITE']	=$row['WEBSITE'];
				$resultSet[$i]['DOMAIN']	=$row['DOMAIN'];
				$resultSet[$i]['NAME'] 		=$row['NAME'];
				$resultSet[$i]['EMAIL']		=$row['EMAIL'];
				$resultSet[$i]['MOBILE'] 	=$row['MOBILE'];					
                                $resultSet[$i]['ISD'] 		=$row['ISD'];    
                                $resultSet[$i]['STD_CODE']	=$row['STD_CODE'];
                                $resultSet[$i]['LANDLINE'] 	=$row['LANDLINE'];
                                $resultSet[$i]['LOCATION']	=$row['LOCATION'];
                                $resultSet[$i]['ADDRESS']	=$row['ADDRESS'];
                                $resultSet[$i]['ACTIVATED']	=$row['ACTIVATED'];
                                $resultSet[$i]['MATCHED_BY']	=$row['MATCHED_BY'];
                                $resultSet[$i]['MESSENGER_ID']	=$row['MESSENGER_ID'];
                                $resultSet[$i]['IP_ADDRESS']	=$row['IP_ADDRESS'];
                                $resultSet[$i]['COMMENTS'] 	=$row['COMMENTS'];
				$resultSet[$i]['ENTRY_BY'] 	=$row['ENTRY_BY'];
				$resultSet[$i]['ENTRY_DT'] 	=$row['ENTRY_DT'];
				$i++;
			}
			if(count($resultSet)==0)
				$smarty->assign("noRecords",'1');
			$smarty->assign("resultSet",$resultSet);	
			$smarty->assign('cid',$cid);
			$smarty->display("negativeProfileList.htm");
		}
	}
	else
        {
		$typeDropdown =create_dd('',"competition_type");
                $smarty->assign("typeDropdown",$typeDropdown);
		$smarty->assign('cid',$cid);
		$smarty->assign("whichType",$whichType);
		$smarty->display("negativeProfileSearch.htm");

	}  
}
else
{
        $msg="Your session has been timed out<br>  ";
        $msg .="<a href=\"index.htm\">";
        $msg .="Login again </a>";
        $smarty->assign("MSG",$msg);
        $smarty->display("jsadmin_msg.tpl");
}
?>
