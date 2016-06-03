<?php

/*************************************************************************
	Partner Profile section start here
*************************************************************************/
function showPartnerProfile($profileid='')
{
	if($profileid=='')
		return;

	include($_SERVER['DOCUMENT_ROOT']."/profile/arrays.php");
include(JsConstants::$docRoot."/commonFiles/dropdowns.php");

	global $smarty;
        $mysqlObj=new Mysql;
        $jpartnerObj=new Jpartner;

	if($profileid)//profileid is viewed profileid
	{
		$viewedDbName=getProfileDatabaseConnectionName($profileid,'',$mysqlObj);
		$viewedDb=$mysqlObj->connect("$viewedDbName");
	}
	$smarty->assign("PARTNER_MSTATUS","   - ");
	$smarty->assign("PARTNER_HANDICAPPED","   -");
        $smarty->assign("PARTNER_BTYPE","   - ");
        $smarty->assign("PARTNER_COMP","   - ");
        $smarty->assign("PARTNER_DIET","   - ");
        $smarty->assign("PARTNER_DRINK","   - ");
        $smarty->assign("PARTNER_MANGLIK","");
        $smarty->assign("PARTNER_MSTATUS","   - ");
        $smarty->assign("PARTNER_RES_STATUS","   - ");
        $smarty->assign("PARTNER_SMOKE","   - ");
        $smarty->assign("PARTNER_CASTE","  -");
        $smarty->assign("PARTNER_RELIGION","  -");
	$smarty->assign("PARTNER_ELEVEL_NEW","  -");
        $smarty->assign("PARTNER_MTONGUE","  -");
        $smarty->assign("PARTNER_OCC","  -");
        $smarty->assign("PARTNER_COUNTRYRES", "  -");
        $smarty->assign("PARTNER_INCOME","  -");
	$smarty->assign("PARTNER_CITYRES",'  -');
        $smarty->assign("PARTNER_HEIGHT","-");
        $smarty->assign("PARTNER_AGE","-");

        $jpartnerObj->setPartnerDetails($profileid,$viewedDb,$mysqlObj);
        if($jpartnerObj->isPartnerProfileExist($viewedDb,$mysqlObj,$profileid))
        {
	
		if($member_101 && $jprofile_result["viewer"]["SUBSCRIPTION"]!='')
			$member_101_details=member_101_details_show($jprofile_result["viewer"],$jpartnerObj);
		else
			$member_101_details='';

                $other_user_activated=$jprofile_result["viewed"]["ACTIVATED"];

                if($jpartnerObj->getLAGE()!="" && $jpartnerObj->getHAGE()!="")
                {
                        $FILTER_LAGE=$jpartnerObj->getLAGE();
                        $FILTER_HAGE=$jpartnerObj->getHAGE();
                        $smarty->assign("PARTNER_AGE",$jpartnerObj->getLAGE() . " to " . $jpartnerObj->getHAGE());
                }
                else
                        $smarty->assign("PARTNER_AGE",21 . " to " . 70);

		
                if($jpartnerObj->getLHEIGHT()!="" && $jpartnerObj->getHHEIGHT()!="")
                {
                        $FILTER_LHEIGHT=$lheight=$jpartnerObj->getLHEIGHT();
			if($lheight)
                        	$lheight=$HEIGHT_DROP["$lheight"];
			else
				$lheight=$HEIGHT_DROP["1"];
                        $FILTER_HHEIGHT=$hheight=$jpartnerObj->getHHEIGHT();
			if($hheight)
                        	$hheight=$HEIGHT_DROP["$hheight"];
			else
				$hheight=$HEIGHT_DROP["32"];
                        $lheight1=explode("(",$lheight);
                        $hheight1=explode("(",$hheight);

                        $smarty->assign("PARTNER_HEIGHT",$lheight1[0] . " to " . $hheight1[0]);
                }
                else
                        $smarty->assign("PARTNER_HEIGHT",$HEIGHT_DROP["1"] . " to " . $HEIGHT_DROP["32"]);

                if($jpartnerObj->getCHILDREN()=="")
                	$smarty->assign("PARTNER_CHILDREN","");
                elseif($jpartnerObj->getCHILDREN()=="N")
                	$smarty->assign("PARTNER_CHILDREN","No");
                elseif($jpartnerObj->getCHILDREN()=="Y")
                	$smarty->assign("PARTNER_CHILDREN","Yes");

                if($jpartnerObj->getHANDICAPPED()!="")
		{
			$ph_str = substr($jpartnerObj->getHANDICAPPED(),1,strlen($jpartnerObj->getHANDICAPPED())-2);
			$ph_val_arr = explode("','",$ph_str);
			for($i=0;$i<count($ph_val_arr);$i++)
			{
				$ph_val=$ph_val_arr[$i];
				$ph_arr[$i]=$HANDICAPPED[$ph_val];
			}
			if(count($ph_arr)>1)
				$ph_fstr = implode(",",$ph_arr);
			elseif(count($ph_arr)==1)
				$ph_fstr = $ph_arr[0];
			else
				$ph_fstr = "";
			if(strstr($ph_fstr,'Physically Handicapped from birth')||strstr($ph_fstr,'Physically Handicapped due to accident'))
				$showit=1;
		     	$smarty->assign("PARTNER_HANDICAPPED",$ph_fstr);
		}
		if($jpartnerObj->getNHANDICAPPED()!="")
                {
                        $nph_str = substr($jpartnerObj->getNHANDICAPPED(),1,strlen($jpartnerObj->getNHANDICAPPED())-2);
                        $nph_val_arr = explode("','",$nph_str);
                        for($i=0;$i<count($nph_val_arr);$i++)
                        {
                                $nph_val=$nph_val_arr[$i];
                                $nph_arr[$i]=$NATURE_HANDICAP[$nph_val];
                        }
			if(count($nph_arr)>1)
                                $nph_fstr = implode(",",$nph_arr);
                        elseif(count($nph_arr)==1)
                                $nph_fstr = $nph_arr[0];
                        else
                                $nph_fstr = "";
			if($showit)
				$smarty->assign("showit",1);
			else
				$smarty->assign("showit",0);
                        $smarty->assign("PARTNER_NHANDICAPPED",$nph_fstr);
                }
		else
		{
			if($showit)
                                $smarty->assign("showit",1);
                        else
                                $smarty->assign("showit",0);
		}
                $p_manglik=trim($jpartnerObj->getPARTNER_MANGLIK(),"'");
                $p_mtongue=trim($jpartnerObj->getPARTNER_MANGLIK(),"'");

			$temp=display_format($jpartnerObj->getPARTNER_BTYPE());
			for($ll=0;$ll<count($temp);$ll++)
				$PARTNER_BTYPE[]=$BODYTYPE[$temp[$ll]];		
			unset($temp);

			$temp=display_format($jpartnerObj->getPARTNER_COMP());
			for($ll=0;$ll<count($temp);$ll++)
				$PARTNER_COMP[]=$COMPLEXION[$temp[$ll]];
			unset($temp);

			$temp=display_format($jpartnerObj->getPARTNER_DIET());
			for($ll=0;$ll<count($temp);$ll++)
				$PARTNER_DIET[]=$DIET[$temp[$ll]];	
			unset($temp);

			$temp=display_format($jpartnerObj->getPARTNER_DRINK());
			for($ll=0;$ll<count($temp);$ll++)
				$PARTNER_DRINK[]=$DRINK[$temp[$ll]];
			unset($temp);

			$temp=display_format($jpartnerObj->getPARTNER_MANGLIK());
			for($ll=0;$ll<count($temp);$ll++)
				$PARTNER_MANGLIK[]=$MANGLIK[$temp[$ll]];	
			unset($temp);

			$temp=display_format($jpartnerObj->getPARTNER_MSTATUS());
                        $FILTER_MSTATUS=$temp;
			for($ll=0;$ll<count($temp);$ll++)
			{
				$PARTNER_MSTATUS[]=$MSTATUS[$temp[$ll]];		
			}
			unset($temp);

			$temp=display_format($jpartnerObj->getPARTNER_RES_STATUS());
			for($ll=0;$ll<count($temp);$ll++)
				$PARTNER_RES_STATUS[]=$RSTATUS[$temp[$ll]];
			unset($temp);

			$temp=display_format($jpartnerObj->getPARTNER_SMOKE());
			for($ll=0;$ll<count($temp);$ll++)
				$PARTNER_SMOKE[]=$SMOKE[$temp[$ll]];
			unset($temp);

                        $PARTNER_CASTE=display_format($jpartnerObj->getPARTNER_CASTE());
			$PARTNER_RELIGION=display_format($jpartnerObj->getPARTNER_RELIGION());
			$PARTNER_ELEVEL_NEW=display_format($jpartnerObj->getPARTNER_ELEVEL_NEW());
                        $PARTNER_MTONGUE=display_format($jpartnerObj->getPARTNER_MTONGUE());
                        $PARTNER_OCC=display_format($jpartnerObj->getPARTNER_OCC());
                        $PARTNER_COUNTRYRES=display_format($jpartnerObj->getPARTNER_COUNTRYRES());
                        $PARTNER_INCOME=display_format($jpartnerObj->getPARTNER_INCOME());

                $return_data1=partnermanglik($p_mtongue,$p_manglik);
                $manglik_data1=explode("+",$return_data1);
                $smarty->assign("Partner_Manglik_Status",$manglik_data1[0]);
                $smarty->assign("Partner_Manglik",$manglik_data1[1]);
                                                                                                                             
		if(is_array($PARTNER_BTYPE))
			$smarty->assign("PARTNER_BTYPE",implode(", ",$PARTNER_BTYPE));
		if(is_array($PARTNER_COMP))
			$smarty->assign("PARTNER_COMP",implode(", ",$PARTNER_COMP));
		if(is_array($PARTNER_DIET))
			$smarty->assign("PARTNER_DIET",implode(", ",$PARTNER_DIET));
		if(is_array($PARTNER_DRINK))
			$smarty->assign("PARTNER_DRINK",implode(", ",$PARTNER_DRINK));
		if(is_array($PARTNER_MANGLIK))
			$smarty->assign("PARTNER_MANGLIK",implode(", ",$PARTNER_MANGLIK));
		if(is_array($PARTNER_MSTATUS))
			$smarty->assign("PARTNER_MSTATUS",implode(", ",$PARTNER_MSTATUS));
		if(is_array($PARTNER_RES_STATUS))
			$smarty->assign("PARTNER_RES_STATUS",implode(", ",$PARTNER_RES_STATUS));
		if(is_array($PARTNER_SMOKE))
			$smarty->assign("PARTNER_SMOKE",implode(", ",$PARTNER_SMOKE));

		$smarty->assign("PARTNER_CASTE",get_partner_string_from_array($PARTNER_CASTE,"CASTE"));
		$smarty->assign("PARTNER_RELIGION",get_partner_string_from_array($PARTNER_RELIGION,"RELIGION"));
		$smarty->assign("PARTNER_ELEVEL_NEW",get_partner_string_from_array($PARTNER_ELEVEL_NEW,"EDUCATION_LEVEL_NEW"));
		$smarty->assign("PARTNER_MTONGUE",get_partner_string_from_array($PARTNER_MTONGUE,"MTONGUE"));
		$smarty->assign("PARTNER_OCC",get_partner_string_from_array($PARTNER_OCC,"OCCUPATION"));
		$smarty->assign("PARTNER_COUNTRYRES",get_partner_string_from_array($PARTNER_COUNTRYRES,"COUNTRY"));
               	$smarty->assign("PARTNER_INCOME",get_partner_string_from_array($PARTNER_INCOME,"INCOME"));

		$PARTNER_CITYRES =array();	
		$PARTNER_CITYRES=display_format($jpartnerObj->getPARTNER_CITYRES());
		if(is_array($PARTNER_CITYRES))
		{
			$str ="";
			$partner_city_str="";
			$str=implode("','",$PARTNER_CITYRES);

			$sql="select SQL_CACHE LABEL from newjs.CITY_NEW where VALUE in ('$str')";
                        $dropresult=mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");

                        while($droprow=mysql_fetch_array($dropresult))
                        {
                                $partner_city_str.=$droprow["LABEL"] . ", ";
                        }

                        mysql_free_result($dropresult);
			
			$partner_city_str=substr($partner_city_str,0,strlen($partner_city_str)-2);
			$smarty->assign("PARTNER_CITYRES",$partner_city_str);
		}
		else
			$smarty->assign("PARTNER_CITYRES",'  -');

	}
	else 
	{
		if($member_101)
			$member_101_details=1;
		$smarty->assign("NOPARTNER","1");
	}

	if($member_101 && $member_101_details)
	{
		$CONTACTDETAILS=1;
		$smarty->assign("CONTACTDETAILS",1);
	}
	elseif($member_101)
	{
		$CONTACTDETAILS='';;
		$smarty->assign("CONTACTDETAILS","");
	}
}
/*************************************************************************
	Partner Profile section ends here
*************************************************************************/
?>
