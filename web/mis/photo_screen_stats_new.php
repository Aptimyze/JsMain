<?php
include_once("connect.inc");
$db=connect_misdb();
$data=authenticated($checksum);

if(isset($data)|| $JSIndicator)
{
        $searchMonth='';
        $searchYear='';
        $monthDays=0;
	if(!$today)
        $today=date("Y-m-d");
        list($todYear,$todMonth,$todDay)=explode("-",$today);
        if($outside)
        {
                $phogo="Y";
                $month=$todMonth;
                $year=$todYear;
                $monthDays=$todDay-1;
        }

	if($phogo)
	{
		$searchFlag=1;
		$searchMonth=$month;
		$searchYear=$year;
                
		  if(($searchMonth=='01')||($searchMonth=='03')||($searchMonth=='05')||($searchMonth=='07')||($searchMonth=='08')||($searchMonth=='10')||($searchMonth=='12'))
                        $monthDays=31;
                elseif(($searchMonth=='04')||($searchMonth=='06')||($searchMonth=='09')||($searchMonth=='11'))
                                $monthDays=30;
                        elseif(($searchYear%4==0)&&($searchYear%100!=0)||($searchYear%400==0))
                                $monthDays=29;
                                else
                                $monthDays=28;
		$k=1;
                while($k<=$monthDays)
                {
                        $monthDaysArray[]=$k;
                        $k++;
                }
		
			$data=array();	
			$sql="SELECT DAY(DATE) AS DAYNO,SCREENED_BY,NEW,EDIT,NEW_PHOTOS,EDIT_PHOTOS,NEW_DEL,EDIT_DEL,NEW_PHOTOS,EDIT_PHOTOS,NEW_DEL_PHOTOS,EDIT_DEL_PHOTOS,EDIT_DEL_PHOTOS,NEW_DEL_PHOTOS,NEW_MARKED_FOR_EDITING,EDIT_MARKED_FOR_EDITING,INTERFACE_NAME FROM MIS.PHOTO_SCREEN_STATS WHERE DATE BETWEEN '$searchYear-$searchMonth-01' AND '$searchYear-$searchMonth-$monthDays' AND DATE>'2014-12-14'";
			$result=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js($db));
			while($row=mysql_fetch_assoc($result))
			{
                          $statArr[$row["INTERFACE_NAME"]][$row["SCREENED_BY"]][$row["DAYNO"]]["NEW_PROFILE"]=$row["NEW"];
                          $statArr[$row["INTERFACE_NAME"]][$row["SCREENED_BY"]][$row["DAYNO"]]["EDIT_PROFILE"]=$row["EDIT"];
                          $statArr[$row["INTERFACE_NAME"]][$row["SCREENED_BY"]][$row["DAYNO"]]["NEW_PROFILE_DEL"]=$row["NEW_DEL"];
                          $statArr[$row["INTERFACE_NAME"]][$row["SCREENED_BY"]][$row["DAYNO"]]["EDIT_PROFILE_DEL"]=$row["EDIT_DEL"];
                          $statArr[$row["INTERFACE_NAME"]][$row["SCREENED_BY"]][$row["DAYNO"]]["NEW_PHOTO"]["APPROVE"]=$row["NEW_PHOTOS"];
                          $statArr[$row["INTERFACE_NAME"]][$row["SCREENED_BY"]][$row["DAYNO"]]["NEW_PHOTO"]["EDITING"]=$row["NEW_MARKED_FOR_EDITING"];
                          $statArr[$row["INTERFACE_NAME"]][$row["SCREENED_BY"]][$row["DAYNO"]]["NEW_PHOTO"]["DELETE"]=$row["NEW_DEL_PHOTOS"];
                          $statArr[$row["INTERFACE_NAME"]][$row["SCREENED_BY"]][$row["DAYNO"]]["EDIT_PHOTO"]["APPROVE"]=$row["EDIT_PHOTOS"];
                          $statArr[$row["INTERFACE_NAME"]][$row["SCREENED_BY"]][$row["DAYNO"]]["EDIT_PHOTO"]["EDITING"]=$row["EDIT_MARKED_FOR_EDITING"];
                          $statArr[$row["INTERFACE_NAME"]][$row["SCREENED_BY"]][$row["DAYNO"]]["EDIT_PHOTO"]["DELETE"]=$row["EDIT_DEL_PHOTOS"];
                          
                          $total["PROFILES"]["NEW"]+=$row["NEW"];
                          $total["PROFILES"]["EDIT"]+=$row["EDIT"];
                          $total["PHOTOS"]["NEW"]+=$row["NEW_PHOTOS"];
                          $total["PHOTOS"]["EDIT"]+=$row["EDIT_PHOTOS"];
                          
                          $total[$row["INTERFACE_NAME"]]["PROFILES"]["NEW"]+=$row["NEW"];
                          $total[$row["INTERFACE_NAME"]]["PROFILES"]["EDIT"]+=$row["EDIT"];
                          
                          $total[$row["INTERFACE_NAME"]]["PHOTOS"]["NEW"]+=$row["NEW_PHOTOS"];
                          $total[$row["INTERFACE_NAME"]]["PHOTOS"]["EDIT"]+=$row["EDIT_PHOTOS"];
                          
                          
                          $total["PROFILES"]["NEW_DEL"]+=$row["NEW_DEL"];
                          $total["PROFILES"]["EDIT_DEL"]+=$row["EDIT_DEL"];
                          $total["PHOTOS"]["NEW_DEL"]+=$row["NEW_DEL_PHOTOS"];
                          $total["PHOTOS"]["EDIT_DEL"]+=$row["EDIT_DEL_PHOTOS"];
                          
                          $total["PHOTOS"]["NEW_EDITING"]+=$row["NEW_MARKED_FOR_EDITING"];
                          $total["PHOTOS"]["EDIT_EDITING"]+=$row["EDIT_MARKED_FOR_EDITING"];
                          
                          
                          $statArr[$row["INTERFACE_NAME"]][$row["SCREENED_BY"]]["NEW_PROFILE"]+=$row["NEW"];
                          $statArr[$row["INTERFACE_NAME"]][$row["SCREENED_BY"]]["EDIT_PROFILE"]+=$row["EDIT"];
                          $statArr[$row["INTERFACE_NAME"]][$row["SCREENED_BY"]]["NEW_PROFILE_DEL"]+=$row["NEW_DEL"];
                          $statArr[$row["INTERFACE_NAME"]][$row["SCREENED_BY"]]["EDIT_PROFILE_DEL"]+=$row["EDIT_DEL"];
                          $statArr[$row["INTERFACE_NAME"]][$row["SCREENED_BY"]]["NEW_PHOTO"]+=$row["NEW_PHOTOS"];
                          $statArr[$row["INTERFACE_NAME"]][$row["SCREENED_BY"]]["EDIT_PHOTO"]+=$row["EDIT_PHOTOS"];
                          $statArr[$row["INTERFACE_NAME"]][$row["SCREENED_BY"]]["NEW_PHOTO_DEL"]+=$row["NEW_DEL_PHOTOS"];
                          $statArr[$row["INTERFACE_NAME"]][$row["SCREENED_BY"]]["EDIT_PHOTO_DEL"]+=$row["EDIT_DEL_PHOTOS"];
                          $statArr[$row["INTERFACE_NAME"]][$row["SCREENED_BY"]]["NEW_PHOTO_EDITING"]+=$row["NEW_MARKED_FOR_EDITING"];
                          $statArr[$row["INTERFACE_NAME"]][$row["SCREENED_BY"]]["EDIT_PHOTO_EDITING"]+=$row["EDIT_MARKED_FOR_EDITING"];
                          
                          
                          $total[$row["INTERFACE_NAME"]][$row["DAYNO"]]["NEW_PROFILE"]+=$row["NEW"];
                          $total[$row["INTERFACE_NAME"]][$row["DAYNO"]]["EDIT_PROFILE"]+=$row["EDIT"];
                          $total[$row["INTERFACE_NAME"]][$row["DAYNO"]]["NEW_PHOTO"]+=$row["NEW_PHOTOS"];
                          $total[$row["INTERFACE_NAME"]][$row["DAYNO"]]["EDIT_PHOTO"]+=$row["EDIT_PHOTOS"];
                          
                          $total[$row["INTERFACE_NAME"]][$row["DAYNO"]]["NEW_PROFILE_DEL"]+=$row["NEW_DEL"];
                          $total[$row["INTERFACE_NAME"]][$row["DAYNO"]]["EDIT_PROFILE_DEL"]+=$row["EDIT_DEL"];
                          $total[$row["INTERFACE_NAME"]][$row["DAYNO"]]["NEW_PHOTO_DEL"]+=$row["NEW_DEL_PHOTOS"];
                          $total[$row["INTERFACE_NAME"]][$row["DAYNO"]]["EDIT_PHOTO_DEL"]+=$row["EDIT_DEL_PHOTOS"];
                          
                          $total[$row["INTERFACE_NAME"]][$row["DAYNO"]]["NEW_PHOTO_EDITING"]+=$row["NEW_MARKED_FOR_EDITING"];
                          $total[$row["INTERFACE_NAME"]][$row["DAYNO"]]["EDIT_PHOTO_EDITING"]+=$row["EDIT_MARKED_FOR_EDITING"];
                          
                        }
                	$smarty->assign('monthDaysArray',$monthDaysArray);
	                $smarty->assign('searchFlag',$searchFlag);
        	        $smarty->assign('searchMonth',$searchMonth);
                	$smarty->assign('searchYear',$searchYear);
	                $smarty->assign("CHECKSUM",$checksum);
			$smarty->assign("data",$data);
			$smarty->assign("statArr",$statArr);
			$smarty->assign("total",$total);
			
        	        $smarty->display("photo_screen_stats_new.htm");

	}
	 else
        {
                $k=0;
                while($k<=5)
                {
                        $yearArray[]=$todYear-$k;
                        $k++;
                }
                $monthArray=array('01'=>'Jan','02'=>'Feb','03'=>'Mar','04'=>'Apr','05'=>'May','06'=>'Jun','07'=>'Jul','08'=>'Aug','09'=>'Sep','10'=>'Oct','11'=>'Nov','12'=>'Dec');
                $smarty->assign('yearArray',$yearArray);
                $smarty->assign('monthArray',$monthArray);
                $smarty->assign('todYear',$todYear);
                $smarty->assign('todMonth',$todMonth);
                $smarty->assign('searchFlag',$searchFlag);
                $smarty->assign('CHECKSUM',$checksum);
                $smarty->display("photo_screen_stats_new.htm");
        }


	
}
else
{
        $smarty->assign('$user',$user);
        $smarty->display("jsconnectError.tpl");
}
?>
