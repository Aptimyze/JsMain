<?php

/************************************************************************************************************************
*    FILENAME           : voucher_successstory.php
*    INCLUDED           : connect.inc
*    DESCRIPTION        : Issue vouchers to the users, coming from success story
*    CREATED BY         : Tanu Gupta
*    CHANGED ON         : 19' March, 07
***********************************************************************************************************************/


include ("connect.inc");
include(JsConstants::$docRoot."/commonFiles/comfunc.inc");
include ("../crm/func_sky.php");
if (authenticated($cid))
{
        $name=getname($cid);
        $smarty->assign("CID",$cid);
	$ts=time();
	$today=date('Y-m-d G:i:s',$ts);
	if($Action)
	{
		$sql="UPDATE billing.VOUCHER_SUCCESSSTORY SET SELECTED=";
		if($Action=='Send Vouchers')
		{
			$sql_detail="SELECT NAME_H,NAME_W,EMAIL,PROFILEID,CITY_RES FROM billing.VOUCHER_SUCCESSSTORY WHERE STORYID='$storyid'";
			$res_detail=mysql_query_decide($sql_detail) or die("$sql_detail".mysql_error_js());
			$row_detail=mysql_fetch_array($res_detail);
			$ss_email=$row_detail['EMAIL'];

			
			//Getting the name from the SUCCESS story table to fetch the correct name.
			$sql_jp="select USERNAME,GENDER from newjs.JPROFILE where EMAIL='$ss_email'";
			$res_jp=mysql_query_decide($sql_jp);
			if($row_jp=mysql_fetch_assoc($res_jp))
			{
				$username_jp=$row_jp['USERNAME'];
				if($row_jp['GENDER']=='F')
				{
					$Name=$row_detail['NAME_W'];
				}
				else
				{
					$Name=$row_detail['NAME_H'];
				}

			}

			//If email address does not provide name then using either name of husband or wife as available
			if(!$Name)
			{
				if($row_detail['NAME_H'])
	                        $Name=$row_detail['NAME_H'];
        	                else
                	        $Name=$row_detail["NAME_W"];
			}

			if($row_detail["PROFILEID"])
			{
				$sqlnri="SELECT COUNTRY_RES FROM newjs.JPROFILE WHERE PROFILEID='$row_detail[PROFILEID]'";
				$resnri=mysql_query_decide($sqlnri) or die("$sqlnri".mysql_error_js());
				$rownri=mysql_fetch_assoc($resnri);
				if($rownri["COUNTRY_RES"]!='51')
				$option=options_available($row_detail["CITY_RES"],"1");
				else
				$option=options_available($row_detail["CITY_RES"]);
			}
			else
			$option=options_available($row_detail["CITY_RES"]);
			$options=explode(',',$option);//print_r($options);
			
			for($i=0;$i<count($options);$i++)
			{			
				$sql_voucher="SELECT VOUCHER_NO,TYPE,ID FROM billing.VOUCHER_NUMBER WHERE ID=(SELECT MIN(ID) min FROM billing.VOUCHER_NUMBER WHERE ISSUED='' AND CLIENTID='$options[$i]')";
				$res_voucher=mysql_query_decide($sql_voucher) or die("$sql_voucher".mysql_error_js());
				$row_voucher=mysql_fetch_array($res_voucher);
				$voucher_no=$row_voucher['VOUCHER_NO'];
				if($voucher_no)//If Voucher No. exists for the particular Client
				{
					if($row_voucher['TYPE']=='E')//For the case of E-Vouchers
					{
						$smarty->assign('voucher_no',"$voucher_no");
						$smarty->assign('Name',"$Name");
						$sql_gv="SELECT TEMPLATE,CLIENT_NAME,HYPERLINK FROM billing.VOUCHER_CLIENTS WHERE CLIENTID='$options[$i]'";
						$res_gv=mysql_query_decide($sql_gv) or die("$sql_gv".mysql_error_js());//logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql_gv,"ShowErrTemplate");
						$row_gv=mysql_fetch_array($res_gv);
						$profileid=$row_detail['PROFILEID'];
						$smarty->assign("client_name",$row_gv["CLIENT_NAME"]);
						$smarty->assign("clientid",$options[$i]);
						if($row_gv["HYPERLINK"])
						$smarty->assign("hyperlink",$hyperlink);
						else
						$smarty->assign("hyperlink","");
						$path=$_SERVER["DOCUMENT_ROOT"];
						if($row_gv["TEMPLATE"])
						$msg=$smarty->fetch($path."/smarty/templates/jeevansathi/".$row_gv['TEMPLATE']);
						else
						$msg=$smarty->fetch($path."/smarty/templates/jeevansathi/evoucher.htm");
												
						send_mail($row_detail['EMAIL'],'','',$msg,'Gift Vouchers','Promotions@jeevansathi.com');
						
						$sql_update="UPDATE billing.VOUCHER_NUMBER SET ISSUED='Y',PROFILEID='$profileid',STORYID='$storyid',ISSUE_DATE='$today',SOURCE='SUCCESS' WHERE ID='$row_voucher[ID]'";//Mark Issued for the E-Vouchers.
						mysql_query_decide($sql_update) or die("$sql_update".mysql_error_js());//logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql_update,"ShowErrTemplate");
					}
				}
			}
			$sql.="'Y',OPTIONS_AVAILABLE='$option',DISPATCH_DATE='$today'";
			$smarty->assign("msg","e-Vouchers are successfully sent to user $Name");
		
		}
		elseif($Action=='Reject')
			$sql.="'N'";
		$sql.=" WHERE STORYID='$storyid'";//echo $sql;
		mysql_query_decide($sql) or die("$sql".mysql_error_js());
	}
	$sql="SELECT ID,STORYID,USERNAME_H,USERNAME_W,NAME_H,NAME_W,PHONE_RES,PHONE_MOB,CONTACT,EMAIL,CITY_RES FROM billing.VOUCHER_SUCCESSSTORY WHERE SELECTED='' ORDER BY STORYID DESC";
	$res=mysql_query_decide($sql) or die("$sql".mysql_error_js());
	$i=0;
	while($row=mysql_fetch_array($res))
	{
		$id=$row["STORYID"];
		$flag=1;
		$sqldet="SELECT UPLOADED,COMMENTS,PIC_URL FROM newjs.SUCCESS_STORIES WHERE ID='$id'";
                $resultdet=mysql_query_decide($sqldet) or die("$sqldet".mysql_error_js());
		if(mysql_num_rows($resultdet))
		{
                	$rowdet=mysql_fetch_assoc($resultdet);
			if($rowdet["UPLOADED"]!="X")
			{
				$voucher[$i]['COMMENTS']=$rowdet["COMMENTS"];
				if($rowdet['PIC_URL'])
				{
					$voucher[$i]['ID']=$id;
				}
			}
			else
			{
				$sql2="UPDATE billing.VOUCHER_SUCCESSSTORY SET SELECTED='X' WHERE ID='$row[ID]'";
				mysql_query_decide($sql2) or die("$sql2".mysql_error_js());
				$flag=0;
			}
		}
		if($flag)
		{
			$voucher[$i]['SNO']=$i+1;
			$voucher[$i]['STORYID']=$row['STORYID'];
			$voucher[$i]['USERNAME_H']=$row['USERNAME_H'];
			$voucher[$i]['USERNAME_W']=$row['USERNAME_W'];
			$voucher[$i]['NAME_H']=$row['NAME_H'];
			$voucher[$i]['NAME_W']=$row['NAME_W'];
			$voucher[$i]['EMAIL']=$row['EMAIL'];
			$voucher[$i]['CONTACT']=$row['CONTACT'];
			$city_res=$row["CITY_RES"];
			$sqldet="SELECT LABEL FROM newjs.CITY_NEW WHERE VALUE='$city_res'";
			$resultdet=mysql_query_decide($sqldet) or die("$sqldet".mysql_error_js());
			$rowdet=mysql_fetch_assoc($resultdet);
			$voucher[$i]['CITY']=$rowdet['LABEL'];
			$voucher[$i]['PHONE_RES']=$row["PHONE_RES"];
			$voucher[$i]['PHONE_MOB']=$row["PHONE_MOB"];
			$i++;
		}
	}

	$sql="DELETE FROM billing.VOUCHER_SUCCESSSTORY WHERE SELECTED='X'";
	$res=mysql_query_decide($sql) or die("$sql".mysql_error_js());
	$smarty->assign("voucher",$voucher);
	$smarty->display("voucher_successstory.htm");
}
else
{
        $msg="Your session has been timed out<br>  ";
        $msg .="<a href=\"index.htm\">";
        $msg .="Login again </a>";
        $smarty->assign("MSG",$msg);
        $smarty->display("jsadmin_msg.tpl");
}
function options_available($city_india,$nri=0)//To fetch the available options for the user
{
        $sql="SELECT CLIENTID,AVAILABLE_IN,SLABS FROM billing.VOUCHER_CLIENTS WHERE SERVICE='Y' AND CLIENTID!='VLCC01'";         
	$res=mysql_query_decide($sql) or die("$sql".mysql_error_js());//logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
        while($row=mysql_fetch_array($res))
        {
		if($city_india)
		{
			$place=explode(':',$row['AVAILABLE_IN']);
			$city=explode(',',$place[0]);
			$state=explode(',',$place[1]);
			if($row["AVAILABLE_IN"]=='')
			{
				$option_available[]=$row['CLIENTID'];
			}
			else
			{
				if($city)
				{
					if(in_array($city_india,$city))
					$option_available[]=$row['CLIENTID'];
					elseif($state)
					{
						if(in_array(substr($city_india,0,2),$state))
						$option_available[]=$row["CLIENTID"];
						elseif(in_array("NRI",$state) && $nri)
						$option_available[]=$row["CLIENTID"];
						else
						continue;
					}
					else
					continue;
				}	
				elseif($state)
				{
					if(in_array(substr($city_india,0,2),$state))
					$option_available[]=$row['CLIENTID'];
					elseif(in_array("NRI",$state) && $nri)
					$option_available[]=$row["CLIENTID"];
					else
					continue;
				}
			}
		}
		else
		{
			if($row["AVAILABLE_IN"]=='')
			$option_available[]=$row['CLIENTID'];
		}

        }//print_r($option_available);die;
        if(count($option_available)>0)
                return $options_available=implode(',',$option_available);
}
?>
