<?php

$flag_using_php5=1;
include("connect.inc");
include("ap_common.php");
include("display_common.php");
include_once("ap_functions.php");

$db=connect_db();

$name=getname($cid);
/*$role=fetchRole($cid);
if($role=='MGR')
	$roleSet ='OP_HEAD';*/
$privilage=getprivilage($cid);
if($privilage)
{
	$priv=explode("+",$privilage);
	if(in_array("MGR",$priv))
		$roleSet='OP_HEAD';
}

global $listMainArray;
$pagination=1;
$PAGELEN=12;
if(!$j)
	$j=1;
$start=$j-1;
$active=1;

// get the total count of profileids to be displayed in dashboard 
$totalCount=fetchDashboard($name,'',$PAGELEN,$start,$active,$roleSet,1);
if($totalCount)
{
	$start=($j)*$PAGELEN-$PAGELEN;
	if($start+11<$totalCount)
		$end=$start+11;
	else
		$end=$totalCount;
}

// fetch the required set of profileids to be shown in dashboard
$res=fetchDashboard($name,$pagination,$PAGELEN,$start,$active,$roleSet,'');
$profileArray =array();
while($row=mysql_fetch_assoc($res))
	$profileArray[]=$row["PROFILEID"];
$profiles=implode("','",$profileArray);

if($profiles){
	$serviceArray=getNextServiceDate($profiles);
	$profileListNumber=getNumberInList($profileArray,$listMainArray);
	$profileListCalled=calledProfiles($profileArray);
}

// Fetch the details of the profile from newjs.JPROFILE table
$i=1;
$sql="SELECT PROFILEID,USERNAME,PASSWORD,HAVEPHOTO,SUBSCRIPTION,PHONE_MOB,STD,PHONE_RES FROM newjs.JPROFILE WHERE PROFILEID IN('$profiles')";
$res=mysql_query_decide($sql) or die("Error while fetching info from JPROFILE  ".mysql_error_js());
while($row=mysql_fetch_assoc($res))
{
	$username=$row["USERNAME"];
	$password=$row["PASSWORD"];
	if($row["PHONE_RES"])
	{
		if($row["STD"])
			$phone_res=$row["STD"]."-";
		$phone_res.=$row["PHONE_RES"];
		$contactArray[]=$phone_res;
	}
	$contactArray[]=$row["PHONE_MOB"];
	$contact=implode(",",$contactArray);
	if($row["SUBSCRIPTION"]!='')
	{
		$sub=explode(",",$row["SUBSCRIPTION"]);
		/*
		if(in_array("AA",$sub))
			$packageArray[]="Auto Apply";
		if(in_array("HD",$sub))
			$packageArray[]="Profile Home Delivery";
		if(in_array("IC",$sub))
			$packageArray[]="Intro Calls";
		if(is_array($packageArray))
			$package=implode(",",$packageArray);
		*/

                /*  Main service bought */
                if(in_array("F",$sub) && in_array("D",$sub))
                        $packageArray[]="eValue Pack";
                elseif(in_array("F",$sub))
                        $packageArray[]="eRishta";
                elseif(in_array("D",$sub))
                        $packageArray[]="eClassified";
		/* Ends  */

		/*  Addon - bought */
                if(in_array("T",$sub))
                        $packageArray[]="Auto Apply";
                if(in_array("L",$sub))
                        $packageArray[]="Profile Home Delivery";
                if(in_array("I",$sub))
                        $packageArray[]="Intro Calls";
                if(is_array($packageArray))
                        $package=implode(", ",$packageArray);
		else
			$package='';
		/* Ends  */
	}
	else
		$package='';
	if($row["HAVEPHOTO"]=="Y")
		$photo="Yes";
	else
		$photo="No";
	$username="<a href=\"ap_dpp.php?cid=$cid&profile=$row[PROFILEID]\">".$row["USERNAME"]."</a>";
		$displayData[$row["PROFILEID"]]=array("SNO"=>$i,
				"USERNAME"=>$username,
				"PASSWORD"=>$row["PASSWORD"],
				"PHOTO"=>$photo,
				"SHORTLIST"=>$profileListNumber[$row["PROFILEID"]]["SL"],
				"TBD"=>$profileListNumber[$row["PROFILEID"]]["TBD"],
				"CALLED"=>$profileListCalled[$row["PROFILEID"]]["TBC"],
				"NEXT_SERVICE"=>$serviceArray[$row["PROFILEID"]],
				"PACKAGE"=>$package,
				"CONTACT"=>$contact);
	$i++;
	unset($phone_res);
	unset($contactArray);	
	unset($contact);
	unset($packageArray);
	unset($package);
	unset($photo);
	unset($username);
}


// Sorting of the profile in asc/desc order based on the service date
$sort='asc';
$new_profileArray =array();
for($i=0;$i<count($profileArray);$i++){
        $pid =$profileArray[$i];
       	$value =$serviceArray[$pid];   
	$new_profileArray[$pid] =$value;
}
asort($new_profileArray);	

$i=0;
foreach($new_profileArray as $key=>$val){
        $sorted_profileArray[$i] =$key;
        $i++;
}
$profiles ="";
if(is_array($sorted_profileArray)){
	foreach($sorted_profileArray as $key=>$val)
		$new_displayData[$val] =$displayData[$val];
}

unset($profileArray);
unset($new_profileArray);
unset($sorted_profileArray);
unset($serviceArray);
unset($profileListNumber);
unset($profileListCalled);

// Sorting ends

// pagination check added
if($pagination)
{
        pagination($j,$totalCount, $PAGELEN,"");
        $curPage="ap_dashboard_oh.php?cid=$cid";
        $smarty->assign("CUR_PAGE",$curPage);
        $smarty->assign("pagination",$pagination);
	$smarty->assign("start",$start);
}
// ends pagination check


$SL_link        ="Profiles in shortlist";
$TBD_link 	="Profiles in 'To be Dispatched'";
$CALLED		="Profiles Called since last service";
$DATE		="Next Service Date";
$PACKAGE	="Package bought";
$displayHeaders=array("SNO","Username","Password","Photo","$SL_link","$TBD_link","$CALLED","$DATE","$PACKAGE","Contact Number");
$smarty->assign("cid",$cid);
$smarty->assign("name",$name);
$smarty->assign("displayHeaders",$displayHeaders);
$smarty->assign("displayData",$new_displayData);
$smarty->display("ap_dashboard_oh.htm");

?>

