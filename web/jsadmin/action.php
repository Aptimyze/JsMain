<?php

/***************************************************************************************************************************
* FILE NAME     : action2.php
* DESCRIPTION   : Performs action according to whether the affiliate has been approved or disapproved
* INCLUDES	: connect.inc,mail_aff.inc
*		: functions:
*		:	mailap():Sends an email to the affiliate after he is approved.
*		:	maildisap():Sends an email to the affiliate after he is dis-approved
* CREATION DATE : 4 May, 2005
* CREATED BY	: Shakti Srivastava
* Copyright  2005, InfoEdge India Pvt. Ltd.
*********************************************************************************************/
include("connect.inc");
//$db=connect_db();
include("mail_aff.inc");

$data=authenticated($cid);
$smarty->assign("cid",$cid);
if(isset($data))
{
	$iserror=0;

	if($submit)
	{
		if($pay_model=='1')
		{
			$iserror++;
			$smarty->assign("PAY_MODEL","1");
		}

		if($pay_model=='C')
		{
			if($click_rate=="")
			{
				$iserror++;
				$smarty->assign("CLICK_RATE","1");
			}
		}
		else if($pay_model=='F')
		{
			if($free_reg_rate=='')
			{
				$iserror++;
				$smarty->assign("FREE_REG_RATE","1");
			}
		}
		else if($pay_model=='CP')
		{
			if($CPclick_rate=="")
			{
				$iserror++;
				$smarty->assign("CPCLICK_RATE","1");
			}
			if($CPpaid_rate=="O" && ($CP1to50==""||$CP51to200==""||$CP201==""))
			{
				$iserror++;
				$smarty->assign("CPCLICK_RATE","1");
			}
		}
		else if($pay_model=='FP')
                {
                        if($FPfree_rate=="")
                        {
                                $iserror++;
                                $smarty->assign("FPFREE_RATE","1");
                        }
                        if($FPpaid_rate=="O" && ($FP1to50==""||$FP51to200==""||$FP201==""))
                        {
                                $iserror++;
                                $smarty->assign("FPFREE_RATE","1");
                        }
                }

		
		if($act=='app')
		{	
			if($iserror>0)
			{
				$sql_aff="select * from affiliate.AFFILIATE_DET where AFFILIATEID=$ID";
	        		$res_aff=mysql_query_decide($sql_aff) or logError("Error while selecting details from AFFILIATE_DET. ".mysql_error_js(),$sql_aff);
			        $row=mysql_fetch_array($res_aff);
                                                                                                                            
			        $sql_cat="select LABEL from affiliate.SITE_CATEGORY where VALUE=$row[10]";
	        		$catres=mysql_query_decide($sql_cat) or logError("Error while selecting details from SITE_CATEGORY. ".mysql_error_js(),$sql_cat);
		        	$cat=mysql_fetch_array($catres);
                                                                                                                            
			        $sql_ctry="select LABEL from newjs.COUNTRY where VALUE=$row[18]";
        			$ctryres=mysql_query_decide($sql_ctry) or logError("Error while selecting details from COUNTRY. ".mysql_error_js(),$sql_ctry);
			        $ctry=mysql_fetch_array($ctryres);
                                                                                                                            
        	        	$det[]=array("ID"=>$row[0],"uname"=>$row[1],"name"=>$row[3],"email"=>$row[4],"phone"=>$row[5],"has_site"=>$row[6],"sitename"=>$row[7],"url"=>$row[8],"desc"=>$row[9],"cat"=>$cat[0],"payee"=>$row[11],"pname"=>$row[12],"mname"=>$row[13],"company"=>$row[14],"add"=>$row[15],"city"=>$row[16],"state"=>$row[17],"ctry"=>$ctry[0],"pin"=>$row[19],"pan"=>$row[20],"pay_model"=>$pay_model,"click_rate"=>$click_rate,"free_reg_rate"=>$free_reg_rate,"CPclick_rate"=>$CPclick_rate,"CPpaid_rate"=>$CPpaid_rate,"FPfree_rate"=>$FPfree_rate,"FPpaid_rate"=>$FPpaid_rate);
                                                                                                                            
			        $smarty->assign("det",$det);
		        	$smarty->display("action.html");
			}
			else
			{
				if($pay_model=='C')
				{
					$sql_cpc="INSERT INTO affiliate.AFF_RECORDS VALUES('','$ID','$UNAME','C','$click_rate','','N','','','',now())";
					$res_cpc=mysql_query_decide($sql_cpc) or logError("Error while inserting into AFF_RECORDS. ".mysql_error_js(),$sql_cpc);
				}
				else if($pay_model=='F')
				{
					$sql_free="INSERT INTO affiliate.AFF_RECORDS VALUES('','$ID','$UNAME','F','','$free_reg_rate','N','','','',now())";
					$res_free=mysql_query_decide($sql_free) or logError("Error while inserting into AFF_RECORDS.".mysql_error_js(),$sql_free);
				}
				else if($pay_model=='CP')
				{
					if($CPpaid_rate=='D')
					{
						$sql_cp="INSERT INTO affiliate.AFF_RECORDS VALUES('','$ID','$UNAME','CP','$CPclick_rate','','D','25','30','40',now())";
						$res_cp=mysql_query_decide($sql_cp) or logError("Error while inserting into AFF_RECORDS.".mysql_error_js(),$sql_cp);
					}
					else if($CPpaid_rate=='O')
					{
						$sql_cp="INSERT INTO affiliate.AFF_RECORDS VALUES('','$ID','$UNAME','CP','$CPclick_rate','','O','$CP1to50','$CP51to200','$CP201',now())";
						$res_cp=mysql_query_decide($sql_cp) or logError("Error while inserting into AFF_RECORDS.".mysql_error_js(),$sql_cp);
					}
				}
				else if($pay_model=='FP')
				{
					if($FPpaid_rate=='D')
					{
						$sql_fp="INSERT INTO affiliate.AFF_RECORDS VALUES('','$ID','$UNAME','FP','','$FPfree_rate','D','25','30','40',now())";
						$res_fp=mysql_query_decide($sql_fp) or logError("Error while inserting into AFF_RECORDS.".mysql_error_js(),$sql_fp);
					}
					else if($FPpaid_rate=='O')
					{
						$sql_fp="INSERT INTO affiliate.AFF_RECORDS VALUES('','$ID','$UNAME','FP','','$FPfree_rate','O','$FP1to50','$FP51to200','$FP201',now())";
						$res_fp=mysql_query_decide($sql_fp) or logError("Error while inserting into AFF_RECORDS.".mysql_error_js(),$sql_fp);
					}
				}
				

				$sql_updt="update affiliate.AFFILIATE_DET set STATUS='A' where AFFILIATEID=$ID";
				mysql_query_decide($sql_updt) or logError("Error while updating. ".mysql_error_js(),$sql_updt);	
				mailap($ID);
				$msg="The Affiliate has been Approved<br>  ";
	                        $msg .="<a href=\"maingate.php?cid=$cid\">";
        	                $msg .="Go to Main Page </a>";
                	        $smarty->assign("MSG",$msg);
                        	$smarty->display("jsadmin_msg.tpl");
			}
		}
			
		else if($act=="disap")
		{
			$sql_updt2="update affiliate.AFFILIATE_DET set STATUS='D' where AFFILIATEID=$ID";
			mysql_query_decide($sql_updt2) or logError("Error while updating. ".mysql_error_js(),$sql_updt2);	
			maildisap($ID);
			$msg="The Affiliate has been disapproved<br>  ";
		        $msg .="<a href=\"maingate.php?cid=$cid\">";
		        $msg .="Go to Main Page </a>";
		        $smarty->assign("MSG",$msg);
		        $smarty->display("jsadmin_msg.tpl");
		}
		else
		{
			echo "An error has occurred";
		}
	}	
	else
	{
		$sql_aff="select * from affiliate.AFFILIATE_DET where AFFILIATEID=$ID";
		$res_aff=mysql_query_decide($sql_aff) or logError("Error while selecting details from AFFILIATE_DET. ".mysql_error_js(),$sql_aff);
		$row=mysql_fetch_array($res_aff);

		$sql_cat="select LABEL from affiliate.SITE_CATEGORY where VALUE=$row[10]";
		$catres=mysql_query_decide($sql_cat) or logError("Error while selecting details from SITE_CATEGORY. ".mysql_error_js(),$sql_cat);
		$cat=mysql_fetch_array($catres);

		$sql_ctry="select LABEL from newjs.COUNTRY where VALUE=$row[18]";
		$ctryres=mysql_query_decide($sql_ctry) or logError("Error while selecting details from COUNTRY. ".mysql_error_js(),$sql_ctry);
		$ctry=mysql_fetch_array($ctryres);

		$det[]=array("ID"=>$row[0],"uname"=>$row[1],"name"=>$row[3],"email"=>$row[4],"phone"=>$row[5],"has_site"=>$row[6],"sitename"=>$row[7],"url"=>$row[8],"desc"=>$row[9],"cat"=>$cat[0],"payee"=>$row[11],"pname"=>$row[12],"mname"=>$row[13],"company"=>$row[14],"add"=>$row[15],"city"=>$row[16],"state"=>$row[17],"ctry"=>$ctry[0],"pin"=>$row[19],"pan"=>$row[20]);
	
		$smarty->assign("det",$det);
		$smarty->display("action.html");
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
