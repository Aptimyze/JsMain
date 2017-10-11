<?php
/***********************************************bms_checkavailability.php***************************************************/
  /*
	*	Created By         :	Abhinav Katiyar
	*	Last Modified By   :	Abhinav Katiyar
	*	Description        :	This file is for checking availability by the admin
	*	Includes/Libraries :	./includes/bms_connect.php,bms_checkavail.php , bms_changepriorities.php
****************************************************************************************************************************/
include("./includes/bms_connect.php");
include("bms_checkavail.php");	
include("bms_changepriorities.php");

$ipaddr=FetchClientIP();
$data=authenticatedBms($id,$ipaddr,"banadmin");
$smarty->assign('zone',$zone);
/*
print_r($_POST);
print_r($_GET);
*/

if($data)
{
   	if($changepri)
	{
		$zonearr=explode("|",$zone);
	        $zoneid=$zonearr[0];
		$res=change_priorities($banners,$zoneid);
		if($res=="false")
		{
			$err[0]["pri"]="true";
			$err[1]["pri"]="The priorities selected violate the availability constraints.";
			$smarty->assign('err',$err);
		}
		else 
		{
			$cnfm[0]="true";
			$cnfm[1]="The priorities have been changed."; 
			$smarty->assign('cnfm',$cnfm);
		}
	}
	if($changewt)
	{
		$zonearr=explode("|",$zone);
	        $zoneid=$zonearr[0];
		$res=change_weight($banners,$zoneid);
		if($res=="true")
                {
			$cnfm[0]="true";
                        $cnfm[1]="The weightages have been changed.";
                        $smarty->assign('cnfm',$cnfm);
		}
	}
	if($submit1)
	{
		if($showall)
		{
			$submit2="submit";
		}
		else
		{
			if(!$criteria)
			{
		 	  	$submit2="submit";
			}
			else
			{
		    		$id=$data["ID"];
				$bmsheader=fetchHeaderBms($data);
				$bmsfooter=fetchFooterBms();
				$smarty->assign("bmsheader",$bmsheader);
				$smarty->assign("bmsfooter",$bmsfooter);
				$smarty->assign("id",$id);
				makeselection($criteria);
				$smarty->assign('startdt',$startdt);
				$smarty->assign('enddt',$enddt);
				$smarty->assign('dowhat',$dowhat);
				$smarty->display("./$_TPLPATH/bms_checkcritsel.htm");
			}
		}
	}
	if($submit2)
	{     
		if($submit1)
		{
			$zonearr=explode("|",$zone);
			$zoneid=$zonearr[0];
			if($dowhat=="check")
				checkavail($zoneid,$criteria,$selectedvalues,$startdt,$enddt,$showdefault);
			elseif($dowhat=="view")	
				viewavail($zoneid,$criteria,$selectedvalues,$startdt,$enddt,$showall,$showdefault);			
		}
		else
		{
			$selstr=urldecode($selstr);
			$sellist=unserialize($selstr);
	                $critstr=urldecode($critstr);
			$criteria=unserialize($critstr);
			$isvalid=validate($sellist);
		        if($isvalid=="false")
	       		{	
				$bmsheader=fetchHeaderBms($data);
				$bmsfooter=fetchFooterBms();
				$smarty->assign("bmsheader",$bmsheader);
				$smarty->assign("bmsfooter",$bmsfooter);
				$smarty->assign("id",$id);
				makeselection($criteria);
				$smarty->assign('startdt',$startdt);
				$smarty->assign('enddt',$enddt);
				$smarty->assign('zone',$zone);
		    		$smarty->assign('dowhat',$dowhat);
				$smarty->display("./$_TPLPATH/bms_checkcritsel.htm");
	       		} 		
			else
			{
			    	$selectedvalues["ip"]=$ip;	
				$selectedvalues["gender"]=$gender;

				if (count($bannerloc_country) >= 1)
				$cntrystr = implode(" , ",$bannerloc_country);
                                if (count($bannerloc_incity) >= 1)
                                        $incitystr = implode(" , ",$bannerloc_incity);
                                if (count($bannerloc_uscity) >= 1)
                                        $uscitystr = implode(" , ",$bannerloc_uscity);
                                $location = "# ".$cntrystr." |X| "." $incitystr"." $ ".$uscitystr." #";
				$selectedvalues["location"]=$location;
				$selectedvalues["ctc"]=$ctc;
				$selectedvalues["mem"]=$mem;
				$selectedvalues["mstatus"]=$mstatus;
				$selectedvalues["rel"]=$rel;
				$selectedvalues["occ"]=$occ;
				$selectedvalues["edu"]=$edu;
				$selectedvalues["com"]=$com;
				$selectedvalues["agemin"]=$agemin;
				$selectedvalues["agemax"]=$agemax;
				$selectedvalues["propcity"]=$propcity;
				$selectedvalues["propinr"] =$propinr;
				$selectedvalues["proprentinr"]=$proprentinr;
				$selectedvalues["propcat"]= $propcat;
				$selectedvalues["proptype"] =$proptype;
				$selectedvalues["categories"]=$categories;

				//added by lavesh rawat
				$selectedvalues["vd"]=$vd;
				$selectedvalues["profileStatus"]=$profileStatus;
				$selectedvalues["jsMailID"]=$jsMailID;
				$selectedvalues["jsEoiStatus"]=$jsEoiStatus;
				$selectedvalues["jsRegistrationStatus"]=$jsRegistrationStatus;
				$selectedvalues["jsFtoStatus"]=$jsFtoStatus;
				$selectedvalues["jsFtoExpiry"]=$jsFtoExpiry;
				$selectedvalues["jsProfileCompletionState"]=$jsProfileCompletionState;
				//added by lavesh rawat

				$zonearr=explode("|",$zone);
				$zoneid=$zonearr[0];

				//print_r($selectedvalues);

				if($dowhat=="check") 
					checkavail($zoneid,$criteria,$selectedvalues,$startdt,$enddt);
				elseif($dowhat=="view")
					viewavail($zoneid,$criteria,$selectedvalues,$startdt,$enddt);
			}
		}
	}

	if(!$submit1 && !$submit2)
	{
		$id=$data["ID"];
		$bmsheader=fetchHeaderBms($data);
		$bmsfooter=fetchFooterBms();
		$smarty->assign("bmsheader",$bmsheader);
		$smarty->assign("bmsfooter",$bmsfooter);
		$smarty->assign("id",$id);
		assignRegionZoneDropDowns("","showcriteria");
		$startdt=date("Y-m-d");
		$enddt=date("Y-m-d",mktime(0,0,0,date("m"),date("d")+10,date("Y")));
		$smarty->assign('startdt',$startdt);
		$smarty->assign('enddt',$enddt);
		$smarty->assign('dowhat',$dowhat);
		$smarty->display("./$_TPLPATH/bms_checkavailsel.htm");
	}
	
}
else
{
	TimedOutBms();	
}			


 /*Function that fills up the criterias*/  
function makeselection($criteria)
{
	global $smarty;	//print_r($criteria);
	if(in_array("IP",$criteria))
   	{
     		$sellist["ip"]="true";
     		$ip=getIpCity("");
     		$smarty->assign('ip',$ip);	
   	}
   	if(in_array("LOCATION",$criteria))
   	{
     		$sellist["location"]="true";
		$loc_ctryarr = getLocCountry("");
		//print_r($loc_ctryarr);
		$smarty->assign("loc_ctryarr",getLocCountry(""));
        	$smarty->assign("loc_Incityarr",getLocInCity(""));
        	$smarty->assign("loc_Uscityarr",getLocUsCity(""));
   	}
        if(in_array("SUBSCRIPTION",$criteria))
        {
                $sellist["mem"]="true";
                $mem=getMEM("");//print_r($mem);
                $smarty->assign('mem',$mem);
        }
	if(in_array("MARITALSTATUS",$criteria))
        {
                $sellist["mstatus"]="true";
                $mstatus=getMStatus("");//print_r($mem);
                $smarty->assign('mstatus',$mstatus);
        }
	if(in_array("RELIGION",$criteria))
        {
                $sellist["rel"]="true";
                $rel=getREL("");//print_r($mem);
                $smarty->assign('rel',$rel);
        }
	if(in_array("EDUCATION",$criteria))
        {
                $sellist["edu"]="true";
                $edu=getEDU("");//print_r($mem);
                $smarty->assign('edu',$edu);
        }
	if(in_array("COMMUNITY",$criteria))
        {
                $sellist["com"]="true";
                $com=getCOM("");//print_r($mem);
                $smarty->assign('com',$com);
        }
	if(in_array("OCCUPATION",$criteria))
        {
                $sellist["occ"]="true";
                $occ=getOCC("");//print_r($mem);
                $smarty->assign('occ',$occ);
        }

   	if(in_array("INCOME",$criteria))
   	{
   		$sellist["ctc"]="true";
     		$ctc=getCtc("");
     		$smarty->assign('ctc',$ctc);
   	}
	//added by lavesh rawat
        global $vdArray,$profileStatus,$mailID,$eoiStatus,$registrationStatus,$ftoState_array,$ftoExpiry_array,$profileCompletionState_array;

   	if(in_array("VARIABLE_DISCOUNT",$criteria))
   	{
   		$sellist["vd"]="true";
     		$smarty->assign('vd',getDropDownsArr("",$vdArray));
   	}
   	if(in_array("PROFILE_STATUS",$criteria))
   	{
   		$sellist["profileStatus"]="true";
     		$smarty->assign('profileStatus',getDropDownsArr("",$profileStatus));
   	}
   	if(in_array("GMAIL_ID",$criteria))
   	{
   		$sellist["jsMailID"]="true";
     		$smarty->assign('jsMailID',getDropDownsArr("",$mailID));
   	}
   	if(in_array("EOI_STATUS",$criteria))
   	{
   		$sellist["jsEoiStatus"]="true";
     		$smarty->assign('jsEoiStatus',getDropDownsArr("",$eoiStatus));
   	}
   	if(in_array("REGISTRATION_STATUS",$criteria))
   	{
   		$sellist["jsRegistrationStatus"]="true";
     		$smarty->assign('jsRegistrationStatus',getDropDownsArr("",$registrationStatus));
   	}
   	if(in_array("FTO_STATE",$criteria))
   	{
   		$sellist["jsFtoStatus"]="true";
     		$smarty->assign('jsFtoStatus',getDropDownsArr("",$ftoState_array));
   	}
   	if(in_array("FTO_EXPIRY",$criteria))
   	{
   		$sellist["jsFtoExpiry"]="true";
     		$smarty->assign('jsFtoExpiry',getDropDownsArr("",$ftoExpiry_array));
   	}
   	if(in_array("PROFILE_COMPLETE_STATE",$criteria))
   	{
   		$sellist["jsProfileCompletionState"]="true";
     		$smarty->assign('jsProfileCompletionState',getDropDownsArr("",$profileCompletionState_array));
   	}
	//added by lavesh rawat

   	if(in_array("AGE",$criteria))
   	{
     		$sellist["age"]="true";
     		$age=getAge();
		$smarty->assign('age',$age);
   	}
   	if(in_array("GENDER",$criteria))
   	{
     		$sellist["gender"]="true";
   	}
   	if(in_array("PROPCITY",$criteria))
        {
                $sellist["propcity"]="true";
                $propcity=getPROPCITY("");//print_r($mem);
                $smarty->assign('propcity',$propcity);
        }
	if(in_array("PROPINR",$criteria))
        {
                $sellist["propinr"]="true";
		$sellist["propcat"]="true";
                $propinr=getPROPBUDGET("");//print_r($mem);
		$proprentinr = getPROPINRRENT("");
		$smarty->assign('proprentinr',$proprentinr);
                $smarty->assign('propinr',$propinr);
		$smarty->assign('propcat',$propcat);
        }
	if(in_array("PROPTYPE",$criteria))
        {
                $sellist["proptype"]="true";
                //$proptype=getPROPTYPE("");
                $smarty->assign('proptype',$proptype);
        }
	if(in_array("PROPCAT",$criteria))
        {
                $sellist["propcat"]="true";
                $smarty->assign('propcat',$propcat);
        }
	
	$selstr=serialize($sellist);
   	$selstr=urlencode($selstr);   	   
	
   	$critstr=serialize($criteria);
   	$critstr=urlencode($critstr);		
	$smarty->assign('critstr',$critstr);		
	$smarty->assign('selstr',$selstr);	
	$smarty->assign('sellist',$sellist);	
}


  /*Function that validates the input*/
function validate($sellist)
{
	global $ip,$bannerloc_country,$bannerloc_uscity , $bannerloc_incity , $smarty,$ctc,$agemax,$agemin,$mem;
	global $mstatus , $rel , $occ , $com , $edu;
	global $propcity , $propinr , $proptype , $proprentinr , $propcat;

        if($sellist["age"]=="true")
        {
	
        	if($agemin=='')
          	{
            		$err[0]["age"]="true";
            		$err[1]["age"]="You have not selected the minimum age.Please select the minimum age to continue.";
          	}
	  	if($agemax=='')
          	{
            		$err[0]["age"]="true";
            		$err[1]["age"]="You have not selected the maximum age.Please select the maximum age to continue.";
          	}
	
        }
        if($sellist["mem"]=="true")
        {
                if(count($mem)==0)
                {
                        $err[0]["mem"]="true";
                        $err[1]["mem"]="You have not selected desired  subscription.Please select subscription type to continue.";
                }
                                                                                                                            
        }
	if($sellist["mstatus"]=="true")
        {
                if(count($mstatus)==0)
                {
                        $err[0]["mstatus"]="true";
                        $err[1]["mstatus"]="You have not selected desired  marital status.Please select marital status type to continue.";
                }
        }
	if($sellist["rel"]=="true")
        {
                if(count($rel)==0)
                {
                        $err[0]["rel"]="true";
                        $err[1]["rel"]="You have not selected desired religion.Please select religion type to continue.";
                }
        }
	if($sellist["edu"]=="true")
        {
                if(count($edu)==0)
                {
                        $err[0]["edu"]="true";
                        $err[1]["edu"]="You have not selected desired  education qualifications.Please select education qualifications to continue.";
                }
        }
	if($sellist["occ"]=="true")
        {
                if(count($occ)==0)
                {
                        $err[0]["occ"]="true";
                        $err[1]["occ"]="You have not selected desired occupation.Please select occupation to continue.";
                }
        }
	if($sellist["com"]=="true")
        {
                if(count($com)==0)
                {
                        $err[0]["com"]="true";
                        $err[1]["com"]="You have not selected community.Please select community to continue.";
                }
        }
	if($sellist["propcity"]=="true")
        {
                if(count($propcity)==0)
                {
                        $err[0]["propcity"]="true";
                        $err[1]["propcity"]="You have not selected property city.Please select property city to continue.";
                }
        }
	if($sellist["propcat"]=="true")
        {
                if(!($propcat))
                {
                        $err[0]["propcat"]="true";
                        $err[1]["propcat"]="You have not selected property category.Please select property category to continue.";
                }
        }
	if($sellist["propinr"]=="true")
        {
                if($propcat=='Rent' && count($proprentinr)==0)
                {
                        $err[0]["propinr"]="true";
                        $err[1]["propinr"]="You have not selected Rent property INR.Please select property INR continue.";
                }
		elseif ($propcat=='Buy' && count($propinr)==0)
		{
			$err[0]["propinr"]="true";
                        $err[1]["propinr"]="You have not selected Rent property INR.Please select property INR continue.";
		}

        }

	if($sellist["proptype"]=="true")
        {
                if(count($proptype)==0)
                {
                        $err[0]["proptype"]="true";
                        $err[1]["proptype"]="You have not selected property type.Please select property type to continue.";
                }
        }
        if($sellist["ctc"]=="true")
        {
	  	if(count($ctc)==0)
          	{
            		$err[0]["ctc"]="true";
            		$err[1]["ctc"]="You have not selected the  cost to company.Please select the cost to company to continue.";
          	}
        }
 
	if($sellist["location"]=="true")
        {
        	if(count($bannerloc_country) < 1)
          	{
            		$err[0]["location"]="true";
            		$err[1]["location"]="Location cannot be left blank. Kindly fill the an appropriate location to continue.";
          	}
        }

	if($sellist["ip"]=="true")
	{
		$cnt_ip=count($ip);
		if($cnt_ip==0)
		{
	    		$err[0]["ip"]="true";
        		$err[1]["ip"]="You have not selected an appropriate City. Kindly select the City to continue.";
	  	}
		else
	  	{
	    		if($cnt_ip==1 && $ip[0]=="")
	    		{
		 		$err[0]["ip"]="true";
	         		$err[1]["ip"]="You have not selected an appropriate City. Kindly select the City to continue.";
			}	 
	  	}
	}

	if(is_array($err[0]))
	{
		$smarty->assign('err',$err);
		return("false");
	}
	else
	{
		return("true");
	}

  }

  function change_priorities($banners,$zoneid)
  {
	  global $dbbms,$pri;
	  $bannarr=explode(",",$banners);
  	
  	  $sql="Select ZoneMaxBans from bms2.ZONE where ZoneId='$zoneid'";
  	  $result=mysql_query($sql,$dbbms) or	logErrorBms("bms_checkavailability.php::1:Could not retrieve the count. <br><!--$sql(".mysql_error($dbbms).")-->:".mysql_errno($dbbms),$sql,"exit","NO");  
  	
  	  if($myrow=mysql_fetch_array($result))
  	  {
  	  	$maxpri=$myrow["ZoneMaxBans"];
  	  	for($i=0;$i<count($bannarr);$i++)
  	 	{
  	 		$banner=$bannarr[$i];
  	 		$prival=$pri[$banner];
  	 		if($prival>$maxpri)
  	 		{
  	 			return "false";
  	 		}
  	 	}
  	  }
	
  	  
  	 if($banners && trim($banners)!='')
  	 {
  	 	$sql="Create Table bms2.BANNERCOPY Select * from BANNER where ZoneId='$zoneid'";
  	 	mysql_query($sql,$dbbms) or logErrorBms("bms_checkavailability.php::1:Could not retrieve the count. <br><!--$sql(".mysql_error($dbbms).")-->:".mysql_errno($dbbms),$sql,"exit","NO"); 
  	 	
  	 	for($i=0;$i<count($bannarr);$i++)
  	 	{
  	 		$banner=$bannarr[$i];
  	 		$prival=$pri[$banner];
  	 		$sql="Update bms2.BANNERCOPY set BannerPriority='$prival' where BannerId='".$bannarr[$i]."'";
 	 		mysql_query($sql,$dbbms) or logErrorBms("bms_checkavailability.php::2:Could not retrieve the count. <br><!--$sql(".mysql_error($dbbms).")-->:".mysql_errno($dbbms),$sql,"exit","NO"); 
  	 	}
  	 	
  	 	$sql="Select BannerId from bms2.BANNERCOPY";
		$result=mysql_query($sql,$dbbms) or logErrorBms("bms_checkavailability.php::3:Could not retrieve the count. <br><!--$sql(".mysql_error($dbbms).")-->:".mysql_errno($dbbms),$sql,"exit","NO");   	 	

		if($myrow=mysql_fetch_array($result))
		{
			do 
			{
				$bannerid=$myrow["BannerId"];
				$critarr=getCritArray($bannerid);
				$rescrit=checkIfCorrect($critarr);
				if($rescrit==0)
				{
					$sql="Drop TABLE bms2.BANNERCOPY";
					mysql_query($sql,$dbbms) or logErrorBms("bms_checkavailability.php::4:Could not retrieve the count. <br><!--$sql(".mysql_error($dbbms).")-->:".mysql_errno($dbbms),$sql,"exit","NO"); 
					return "false";
				}
			}while($myrow=mysql_fetch_array($result));
		}
		
  	 	for($i=0;$i<count($bannarr);$i++)
  	 	{
  	 		$banner=$bannarr[$i];
  	 		$prival=$pri[$banner];
			$bannestring="bnrstr".$zoneid."p".$prival;
  	 		$sql="Update bms2.BANNER set BannerPriority='$prival',BannerString='$bannestring' where BannerId='".$bannarr[$i]."'";
 	 		mysql_query($sql,$dbbms) or logErrorBms("bms_checkavailability.php::5:Could not retrieve the count. <br><!--$sql(".mysql_error($dbbms).")-->:".mysql_errno($dbbms),$sql,"exit","NO"); 
  	 	}
  	 	$sql="Drop TABLE bms2.BANNERCOPY";
		mysql_query($sql,$dbbms) or logErrorBms("bms_checkavailability.php::6:Could not retrieve the count. <br><!--$sql(".mysql_error($dbbms).")-->:".mysql_errno($dbbms),$sql,"exit","NO"); 
	 }
  	 
  } 
  
  function change_weight($banners,$zoneid)
  {
	global $dbbms,$weight;
  	$bannarr=explode(",",$banners);
  	 
  	 if($banners && trim($banners)!='')
  	 {
  	 	 	
  	 	for($i=0;$i<count($bannarr);$i++)
  	 	{
  	 		$banner=$bannarr[$i];
  	 		$weightval=$weight[$banner];
  	 		$sql="Update bms2.BANNER set BannerWeightage='$weightval' where BannerId='".$bannarr[$i]."'";
 	 		mysql_query($sql,$dbbms) or logErrorBms("bms_checkavailability.php::2:Could not retrieve the count. <br><!--$sql(".mysql_error($dbbms).")-->:".mysql_errno($dbbms),$sql,"exit","NO"); 
  	 	}
		return("true");
  	 }
	else
		return("false");
  	 
  }
?>
