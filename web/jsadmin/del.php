<?php
/*********************************************************************************************
* FILE NAME     : del.php
* DESCRIPTION	: Deletes the Affiliate's records
* CREATION DATE : 3 May, 2005
* CREATED BY	: Shakti Srivastava
* Copyright  2005, InfoEdge India Pvt. Ltd.
*********************************************************************************************/


include("connect.inc");
//$db=connect_db();

$smarty->assign("cid",$cid);
$data=authenticated($cid);

if(isset($data))
{
	$iserror=0;

	if($submit)
	{
		if($pay_model=="1")
		{
			$iserror++;
			$smarty->assign("PAY_MODEL_ERR","1");
		}
		if($pay_model=='C')
		{
			if($click_rate=="")
			{
				$iserror++;
				$smarty->assign("CLICK_RATE_ERR","1");
			}
		}
		else if($pay_model=='F' && $free_reg_rate=="")
		{
			$iserror++;
			$smarty->assign("FREE_REG_ERR","1");
		}
		else if($pay_model=='CP')
		{
			if($CPclick_rate=="")
			{
				$iserror++;
				$smarty->assign("CP_ERR","1");
			}
			
			if($CPpaid_model=='O' && ($CP1to50==""||$CP51to200==""||$CP201==""))
			{
				$iserror++;
				$smarty->assign("CP_ERR","1");
			}
		}	
		else if($pay_model=='FP')
		{
			if($FPfree_rate=="")
			{
				$iserror++;
				$smarty->assign("FP_ERR","1");
			}
			else if($FPpaid_model=='O' && ($FP1to50==""||$FP51to200==""||$FP201==""))
			{
				$iserror++;
				$smarty->assign("FP_ERR","1");
			}
		}
	
		if($iserror>0)
		{
			$smarty->assign("AID",$aid);
                	$smarty->assign("UNAME",$uname);
	                $smarty->assign("pay_model",$pay_model);
        	        $smarty->assign("click_rate",$click_rate);
                	$smarty->assign("free_reg_rate",$free_reg_rate);
	                $smarty->assign("CPclick_rate",$CPclick_rate);
	                $smarty->assign("CPpaid_model",$CPpaid_model);
			$smarty->assign("CP1to50",$CP1to50);
			$smarty->assign("CP51to200",$CP51to200);
			$smarty->assign("CP201",$CP201);
                	$smarty->assign("FPfree_rate",$FPfree_rate);
	                $smarty->assign("FPpaid_model",$FPpaid_model);
	                $smarty->assign("FP1to50",$FP1to50);
	                $smarty->assign("FP51to200",$FP51to200);
	                $smarty->assign("FP201",$FP201);			
	                $smarty->assign("error",$iserror);			
			$smarty->display("modiAff.html");
		}
		else
		{
			if($pay_model=='C')
			{
				$sql_insert="INSERT INTO affiliate.AFF_RECORDS VALUES('','$aid','$uname','C','$click_rate','','N','','','',now())";
				$res_insert=mysql_query_decide($sql_insert) or logError("Error while inserting into AFF_RECORDS. ".mysql_error_js(),$sql_insert);
			}

			if($pay_model=='F')
			{
				$sql_insert="INSERT INTO affiliate.AFF_RECORDS VALUES('','$aid','$uname','F','','$free_reg_rate','N','','','',now())";
				$res_insert=mysql_query_decide($sql_insert) or logError("Error while inserting into AFF_RECORDS. ".mysql_error_js(),$sql_insert);
			}

			if($pay_model=='CP')
			{	
				if($CPpaid_model=='D')
				{
					$sql_insert2="INSERT INTO affiliate.AFF_RECORDS VALUES('','$aid','$uname','CP','$CPclick_rate','','D',25,30,40,now())";
					$res_insert2=mysql_query_decide($sql_insert2) or logError("Error while inserting into AFF_RECORDS. ".mysql_error_js(),$sql_insert2);
				}
				else if($CPpaid_model=='O')
				{
					$sql_insert3="INSERT INTO affiliate.AFF_RECORDS VALUES('','$aid','$uname','CP','$CPclick_rate','','O','$CP1to50','$CP51to200','$CP201',now())";
					$res_insert3=mysql_query_decide($sql_insert3) or logError("Error while inserting into AFF_RECORDS. ".mysql_error_js(),$sql_insert3);
				}
			}

			if($pay_model=='FP')
                        {
                                if($FPpaid_model=='D')
                                {
                                        $sql_insert2="INSERT INTO affiliate.AFF_RECORDS VALUES('','$aid','$uname','FP','','$FPfree_rate','D',25,30,40,now())";
                                        $res_insert2=mysql_query_decide($sql_insert2) or logError("Error while inserting into AFF_RECORDS. ".mysql_error_js(),$sql_insert2);
                                }
                                else if($FPpaid_model=='O')
                                {
                                        $sql_insert3="INSERT INTO affiliate.AFF_RECORDS VALUES('','$aid','$uname','FP','','$FPfree_rate','O','$FP1to50','$FP51to200','$FP201',now())";
                                        $res_insert3=mysql_query_decide($sql_insert3) or logError("Error while inserting into AFF_RECORDS. ".mysql_error_js(),$sql_insert3);
                                }
                        }

			
			$msg="The Record has been updated<br>  ";
                        $msg .="<a href=\"maingate.php?cid=$cid\">";
                        $msg .="Go to Main Page </a>";
                        $smarty->assign("MSG",$msg);
                        $smarty->display("jsadmin_msg.tpl");
		}
	}
	else
	{
		if($act=='D')
		{
			$sql_del="UPDATE affiliate.AFFILIATE_DET SET STATUS='X' WHERE AFFILIATEID=$ID";
			$res_del=mysql_query_decide($sql_del) or logError("Error while Deleting from affiliate.AFFILIATE_DET. ".mysql_error_js());

			$msg="The Affiliate has been Deleted<br>  ";
                        $msg .="<a href=\"maingate.php?cid=$cid\">";
                        $msg .="Go to Main Page </a>";
                        $smarty->assign("MSG",$msg);
                        $smarty->display("jsadmin_msg.tpl");
		}
		else
		{
			$sql_get_data="SELECT * FROM affiliate.AFF_RECORDS WHERE AFFILIATEID=$ID ORDER BY ID DESC";
			$res_get_data=mysql_query_decide($sql_get_data) or logError("Error while fetching data. ".mysql_error_js(),$sql_get_data);
		
			if((mysql_num_rows($res_get_data))>0)
			{
				$row_data=mysql_fetch_array($res_get_data);
				$smarty->assign("AID",$row_data["AFFILIATEID"]);
				$smarty->assign("UNAME",$row_data["USERNAME"]);
				$smarty->assign("pay_model",$row_data["PAYMENT_MODEL"]);
				$smarty->assign("click_rate",$row_data["CLICKS_RATE"]);
				$smarty->assign("free_reg_rate",$row_data["FREE_RATE"]);
				$smarty->assign("paid_reg_model",$row_data["PAID_REG_MODEL"]);
				$smarty->assign("paid_1_to_50",$row_data["PAID_1_TO_50"]);
				$smarty->assign("paid_51_to_200",$row_data["PAID_51_TO_200"]);
				$smarty->assign("paid_201",$row_data["PAID_201"]);
				$smarty->display("modiAff.html");
			}
		}
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
