<?php

$flag_using_php5=1;
include("connect.inc");
include("ap_common.php");
include("display_common.php");
include_once("ap_functions.php");

$db=connect_db();
$name=getname($cid);
$role=fetchRole($cid);

global $listMainArray;
$pagination=1;
$PAGELEN=12;
if(!$j)
	$j=1;
$start=$j-1;

/* get the total count of profileids to be displayed in dashboard 
 * $active - flag for active and inactive profiles	
 * $active=1: active profile, $active=0: inactive profiles
*/
$totalCount=fetchDashboard($name,'',$PAGELEN,$start,$active,$role,1);
if($totalCount)
{
	$start=($j)*$PAGELEN-$PAGELEN;
	if($start+11<$totalCount)
		$end=$start+11;
	else
		$end=$totalCount;
}

// fetch the required set of profileids to be shown in dashboard
$res=fetchDashboard($name,$pagination,$PAGELEN,$start,$active,$role,'');
$profileArray =array();
while($row=mysql_fetch_assoc($res))
	$profileArray[]=$row["PROFILEID"];
$profiles=implode("','",$profileArray);

if($profiles){
	$profileStatusArr =getProfileStatus($profileArray);
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
		/*  Main service bought */	
		if(in_array("F",$sub) && in_array("D",$sub))
			$packageArray[]="eValue Pack";
		elseif(in_array("F",$sub))
			$packageArray[]="eRishta";
		elseif(in_array("D",$sub))
			$packageArray[]="eClassified";
		/*  Ends */

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
		/*  Ends */
	}
	else
		$package='';
	$list_packageArray[$row["PROFILEID"]] =$package;	// added for sorting on package
	if($row["HAVEPHOTO"]=="Y")
		$photo="Yes";
	else
		$photo="No";
	$list_photoArray[$row["PROFILEID"]] =$photo;
	$username="<a href=\"ap_dpp.php?cid=$cid&profile=$row[PROFILEID]\">".$row["USERNAME"]."</a>";
		$displayData[$row["PROFILEID"]]=array("SNO"=>$i,
				"STATUS"=>$profileStatusArr[$row["PROFILEID"]],
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


// Sorting of the profile in asc/desc order
if($sort =='')
	$sort='desc';
if($sortVar =='')
	$sortVar ='DT';

$new_profileArray =array();
for($i=0;$i<count($profileArray);$i++){
        $pid =$profileArray[$i];
	if($sortVar =='TBD' || $sortVar =='SL') {
		$value =$profileListNumber[$pid][$sortVar];
	}
	elseif($sortVar=='TBC'){
		$value =$profileListCalled[$pid][$sortVar];
	}
	elseif($sortVar =='DT'){
        	$value =$serviceArray[$pid];   
	}
	elseif($sortVar =='PKG'){
		$value =$list_packageArray[$pid];	
	}    
	if($sortVar =='PHOTO'){
		$value =$list_photoArray[$pid];
	} 
	$new_profileArray[$pid] =$value;
}

if($sort =='desc'){
	arsort($new_profileArray);	// sorting in descending order
	$sort ='asc';
}
else{	
	asort($new_profileArray);	// sorting in ascending order	
	$sort ='desc';
}

$i=0;
foreach($new_profileArray as $key=>$val){
        $sorted_profileArray[$i] =$key;
        $i++;
}
$profiles ="";
if(is_array($sorted_profileArray)){
	$profiles=implode("','",$sorted_profileArray);
	foreach($sorted_profileArray as $key=>$val)
		$new_displayData[$val] =$displayData[$val];
}

unset($profileArray);
unset($new_profileArray);
unset($sorted_profileArray);
unset($serviceArray);
unset($profileListNumber);

// Sorting ends

// pagination check added
if($pagination)
{
        pagination($j,$totalCount, $PAGELEN,"");
        $curPage="ap_dashboard.php?cid=$cid&sort=$sort&sortVar=$sortVar'&active=$active";
        $smarty->assign("CUR_PAGE",$curPage);
        $smarty->assign("pagination",$pagination);
	$smarty->assign("start",$start);
}
// ends pagination check

$PHOTO_link	="<a href='ap_dashboard.php?cid=$cid&sort=$sort&sortVar=PHOTO&j=$j&active=$active'>Photo</a>";
$SL_link        ="<a href='ap_dashboard.php?cid=$cid&sort=$sort&sortVar=SL&j=$j&active=$active'>Profiles in shortlist</a>";
$TBD_link 	="<a href='ap_dashboard.php?cid=$cid&sort=$sort&sortVar=TBD&j=$j&active=$active'>Profiles in 'To be Dispatched'</a>";
$CALLED		="<a href='ap_dashboard.php?cid=$cid&sort=$sort&sortVar=TBC&j=$j&active=$active'>Profiles Called since last service</a>";
$DATE		="<a href='ap_dashboard.php?cid=$cid&sort=$sort&sortVar=DT&j=$j&active=$active'>Next Service Date</a>";
$PACKAGE	="<a href='ap_dashboard.php?cid=$cid&sort=$sort&sortVar=PKG&j=$j&active=$active'>Package bought</a>";
$displayHeaders=array("SNO","Username","Password","$PHOTO_link","$SL_link","$TBD_link","$CALLED","$DATE","$PACKAGE","Contact Number");
$smarty->assign("cid",$cid);
$smarty->assign("name",$name);
$smarty->assign("displayHeaders",$displayHeaders);
$smarty->assign("displayData",$new_displayData);
$smarty->assign("sort",$sort);
$smarty->assign("sortVar",$sortVar);
$smarty->assign("active",$active);
$smarty->display("ap_dashboard.htm");


?>

