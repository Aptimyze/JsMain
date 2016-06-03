<?PHP

/*****************************************************bms_region.php********************************************************/
  /*
	*	Created By         :	Abhinav Katiyar
	*	Last Modified By   :	Abhinav Katiyar
	*	Description        : 	used for adding/editing a region
	*	Includes/Libraries : 	./includes/bms_connect.php
****************************************************************************************************************************/

include ("./includes/bms_connect.php");
$ip=FetchClientIP();
$data=authenticatedBms($id,$ip,"banadmin");
global $dbbms;

/*****************************************************************
   	checks if the details entered are valid or not
	input	: region name, region desc, region id
	output	: true - if details correct
	    	: false - if details incorrect
******************************************************************/
function checkForm($regname,$regdesc,$ismailer,$regid="",$sitename)
{
	global $smarty,$_TPLPATH;
	global $dbbms;
	$check=1;
	$regname=trim(addslashes($regname));
	$regdesc=trim(addslashes($regdesc));
	if(!$sitename)
	{
		$errormsg="To continue, please chosse site name.";
                $check= 0;
	}
	if($regname=="")
	{
		$errormsg="To continue, please enter a region name.";
		$check= 0;
	}
	elseif($regdesc=="")
	{
		$errormsg="To continue, please enter the description of the region.";
		$check=0;
	}
	else
	{
		if($ismailer)
		{
			//if($regid)
				$q="select RegId from bms2.REGION where RegMailer='Y' and RegId<>'$regid'" ;
			//else
				$q="select RegId from bms2.REGION where RegMailer='Y'" ;
			//$res=mysql_query($q,$dbbms);
			//if(mysql_num_rows($res))
			{
				//$errormsg="A mailer region already exists.You cannot select two mailer regions. ";
				//$check= 0;
			}
		}
		if($check)
		{
			if($regid)
			{
				$q="select RegId from bms2.REGION where RegName='$regname' and RegId<>'$regid'" ;
				$res=mysql_query($q,$dbbms);
				if(mysql_num_rows($res))
				{
						$errormsg="A region already exists with the name \"".$regname."\" . Please name this region differently. ";
						$check= 0;
				}
			}
			else
			{
				$q="select RegId from bms2.REGION where RegName='$regname'" ;
				$res=mysql_query($q,$dbbms);
				if(mysql_num_rows($res))
				{
						$errormsg="A region alredy exists with the name \"".$regname."\" . Please name this region differently. ";
						$check= 0;
				}
			}
		}
	}
	if($check==0)
	{
		$smarty->assign("errormsg",$errormsg);
	 	return 0;
	}
	else 
		return 1;
}

/******************************************************************************	
	Add/edit a region 
	input	: region name, region desc , is region a mailer , region id(for 		
		  editing a region, action(region to be added or edited)
	output	: none
********************************************************************************/
function AddEditRegion($regname,$regdesc,$ismailer,$regid,$action,$sitename)//action can be add or edit
{
	global $dbbms;
	$curdate=date("Y-m-d");
	if($action=="add")
	{
		$sql="insert into bms2.REGION(RegId,RegName,RegEntryDate,RegDesc,RegMailer,SITE) values('','$regname','$curdate','$regdesc','$ismailer','$sitename')";
	}
	elseif($action=="edit"&& $regid)
	{
		$sql="update bms2.REGION set RegName='$regname',RegDesc='$regdesc',RegMailer='$ismailer',SITE='$sitename',RegModDate='$curdate' where RegId='$regid'";
	}
	else
		echo "check query";
	$result=mysql_query($sql,$dbbms) or logErrorBms("bms_region.php: AddEditRegion :1: Could not add/edit region <br>	<!--$sql<br>". mysql_error()."-->: ". mysql_errno(), $sql, "ShowErrTemplate");
	
}

/****************************************************************************
	delete an existing region
	input	: region id
	output	: none
****************************************************************************/
function deleteRegion($regid)
{
	global $dbbms;

        //added by lavesh
        $sql="INSERT IGNORE INTO bms2.DELETED_REGION (SELECT * FROM bms2.REGION where RegId='$regid')";
        $res=mysql_query($sql,$dbbms) or logErrorBms("bms_zone.php: deleteZone:1: Could not create backup for delete region   <!--$sql<br>". mysql_error()."-->: ". mysql_errno(), $sql, "ShowErrTemplate");
        //Ends Here

	$sql="delete from bms2.REGION where RegId='$regid'";
	$res=mysql_query($sql,$dbbms) or logErrorBms("bms_region.php: deleteRegion :1: Could not delete region <br>	<!--$sql<br>". mysql_error()."-->: ". mysql_errno(), $sql, "ShowErrTemplate");

	//added by lavesh
	$sql="SELECT ZoneId FROM bms2.ZONE where RegId='$regid'";
	$res=mysql_query($sql,$dbbms) or logErrorBms("bms_region.php: Could not select data from bms2.ZONE <br>     <!--$sql<br>". mysql_error()."-->: ". mysql_errno(), $sql, "ShowErrTemplate");
	while($row=mysql_fetch_array($res))
	{
		$zoneid=$row["ZoneId"];

		$sql_1="INSERT IGNORE INTO bms2.DELETED_ZONE (SELECT * FROM bms2.ZONE where ZoneId='$zoneid')";         
		$res_1=mysql_query($sql_1,$dbbms) or logErrorBms("bms_zone.php: deleteZone:1(1): Could not delete zone <br>    <!--$sql_1<br>". mysql_error()."-->: ". mysql_errno(), $sql_1, "ShowErrTemplate");

		$sql_1="delete from bms2.ZONE where ZoneId='$zoneid'";
		$res_1=mysql_query($sql_1,$dbbms) or logErrorBms("bms_zone.php: deleteZone:1(1): Could not delete zone <br>  <!--$sql_1<br>". mysql_error()."-->: ". mysql_errno(), $sql_1, "ShowErrTemplate");

	}
	//Ends Here.
}
if($data)
{
	$id=$data["ID"];
	$bmsheader=fetchHeaderBms($data);
	$bmsfooter=fetchFooterBms();
	$smarty->assign("bmsheader",$bmsheader);
	$smarty->assign("bmsfooter",$bmsfooter);
	if($add_x)
	{
		if(checkForm($regname,$regdesc,$ismailer,"",$sitename))
		{	//echo "is mailer is".$ismailer;
			AddEditRegion($regname,$regdesc,$ismailer,"","add",$sitename);
			$cnfrmmsg="New region \"".stripslashes($regname)."\" has been saved";
			$smarty->assign("cnfrmmsg",$cnfrmmsg);
			$regions=getRegions();
			$smarty->assign("regions",$regions);
			$smarty->assign("id",$id);
			$smarty->display("./$_TPLPATH/bms_region.htm");
		}
		else
		{	
			$regions=getRegions();
			$smarty->assign("regions",$regions);
			$smarty->assign("regname",stripslashes($regname));
			$smarty->assign("regdesc",stripslashes($regdesc));
			$smarty->assign("sitename",$sitename);
			$smarty->assign("ismailer",$ismailer);
			$smarty->assign("id",$id);
			$smarty->display("./$_TPLPATH/bms_region.htm");
		}
	}
	elseif($edit_x)
	{
		list($regid)=explode("|X|",$region);
		if(checkForm($regname,$regdesc,$ismailer,$regid,$sitename))
		{	
			AddEditRegion($regname,$regdesc,$ismailer,$regid,"edit",$sitename);
			$cnfrmmsg="The region \"".stripslashes($regname)."\" has been edited";
			$smarty->assign("cnfrmmsg",$cnfrmmsg);
			$regions=getRegions();
			$smarty->assign("regions",$regions);
			$smarty->assign("id",$id);
			$smarty->display("./$_TPLPATH/bms_region.htm");
		}
		else
		{	
			//echo "1";
			$regions=getRegions();
			$smarty->assign("regions",$regions);
			$smarty->assign("regname",stripslashes($regname));
			$smarty->assign("regdesc",stripslashes($regdesc));
			$smarty->assign("ismailer",$ismailer);
			$smarty->assign("regid",$regid);
			$smarty->assign("id",$id);
			$smarty->display("./$_TPLPATH/bms_region.htm");
		}
	}
	elseif($deletee_x)
	{
		list($regid)=explode("|X|",$region);
		deleteRegion($regid);
		$cnfrmmsg="The region \"".stripslashes($regname)."\" has been deleted";
		$smarty->assign("cnfrmmsg",$cnfrmmsg);
		$regions=getRegions();
		$smarty->assign("regions",$regions);
		$smarty->assign("id",$id);
		$smarty->display("./$_TPLPATH/bms_region.htm");
	}
	elseif($showzone_x)
	{
		list($regid)=explode("|X|",$region);
		$zones=getZoneDetails($regid);
		$criteria=getCriteria($sitename);//print_r($criteria);
		$smarty->assign("sitename",$sitename);
		$smarty->assign("criteria",$criteria);
		$smarty->assign("regionid",$regid);
		$smarty->assign("zones",$zones);
		$smarty->assign("id",$id);
		$smarty->display("./$_TPLPATH/bms_zone.htm");
	}
	else
	{
		$regions=getRegions();
		$smarty->assign("regions",$regions);
		$smarty->assign("id",$id);
		$smarty->display("./$_TPLPATH/bms_region.htm");
	}
}
else
	TimedOutBms();
