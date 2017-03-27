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
			$sql="SELECT DAY(DATE) AS DAYNO,SCREENED_BY,NEW,EDIT,NEW_DEL,EDIT_DEL,NEW_PHOTOS,EDIT_PHOTOS,NEW_DEL_PHOTOS,EDIT_DEL_PHOTOS,APP_MAIL,APP_MAIL_PHOTOS,DEL_MAIL,DEL_MAIL_PHOTOS,APP_PIC,APP_PIC_APPROVE,APP_PIC_EDITED FROM MIS.PHOTO_SCREEN_STATS WHERE DATE BETWEEN '$searchYear-$searchMonth-01' AND '$searchYear-$searchMonth-$monthDays'";
			$result=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js($db));
			while($row=mysql_fetch_assoc($result))
			{
				$user=$row["SCREENED_BY"];
				 $day=$row["DAYNO"];
				if(!array_key_exists($user,$data))
				$data[$user]=array();
				$delMailPhotos=$row["DEL_MAIL_PHOTOS"];

				$data[$user][$day]["APPPROF"]=$row["NEW"]+$row["EDIT"]+$row["APP_MAIL"]+$row["APP_PIC"];
				$data[$user][$day]["DELPROF"]=$row["NEW_DEL"]+$row["EDIT_DEL"]+$row["DEL_MAIL"];
				$usertotal[$user]["APPPROF"]+=$row["NEW"]+$row["EDIT"]+$row["APP_MAIL"]+$row["APP_PIC"];
				$usertotal[$user]["DELPROF"]+=$row["NEW_DEL"]+$row["EDIT_DEL"]+$row["DEL_MAIL"];
				$daytotal[$day]["APPPROF"]+=$row["NEW"]+$row["EDIT"]+$row["APP_MAIL"]+$row["APP_PIC"];
				$daytotal[$day]["DELPROF"]+=$row["NEW_DEL"]+$row["EDIT_DEL"]+$row["DEL_MAIL"];	
				$total["PROFAPP"]+=$row["NEW"]+$row["EDIT"]+$row["APP_MAIL"]+$row["APP_PIC"];
				$total["PROFDEL"]+=$row["NEW_DEL"]+$row["EDIT_DEL"]+$row["DEL_MAIL"];

                                $data[$user][$day]["APPPROFNEW"]=$row["NEW"];
                                $data[$user][$day]["DELPROFNEW"]=$row["NEW_DEL"];
                                $usertotal[$user]["APPPROFNEW"]+=$row["NEW"];
                                $usertotal[$user]["DELPROFNEW"]+=$row["NEW_DEL"];
                                $daytotal[$day]["APPPROFNEW"]+=$row["NEW"];
                                $daytotal[$day]["DELPROFNEW"]+=$row["NEW_DEL"];
                                $total["PROFAPPNEW"]+=$row["NEW"];
                                $total["PROFDELNEW"]+=$row["NEW_DEL"];

                                $data[$user][$day]["APPPROFAPP_PIC"]=$row["APP_PIC"];
                                $usertotal[$user]["APPPROFAPP_PIC"]+=$row["APP_PIC"];
                                $daytotal[$day]["APPPROFAPP_PIC"]+=$row["APP_PIC"];
                                $total["PROFAPPAPP_PIC"]+=$row["APP_PIC"];

                                $data[$user][$day]["APPPROFEDIT"]=$row["EDIT"];
                                $data[$user][$day]["DELPROFEDIT"]=$row["EDIT_DEL"];
                                $usertotal[$user]["APPPROFEDIT"]+=$row["EDIT"];
                                $usertotal[$user]["DELPROFEDIT"]+=$row["EDIT_DEL"];
                                $daytotal[$day]["APPPROFEDIT"]+=$row["EDIT"];
                                $daytotal[$day]["DELPROFEDIT"]+=$row["EDIT_DEL"];
                                $total["PROFAPPEDIT"]+=$row["EDIT"];
                                $total["PROFDELEDIT"]+=$row["EDIT_DEL"];

                	        $data[$user][$day]["APPPROFMAIL"]=$row["APP_MAIL"];
	                        $data[$user][$day]["APPPHOMAIL"]=$row["APP_MAIL_PHOTOS"];
        	                $data[$user][$day]["DELPROFMAIL"]=$row["DEL_MAIL"];
                	        $usertotal[$user]["APPPROFMAIL"]+=$row["APP_MAIL"];
	                        $usertotal[$user]["DELPROFMAIL"]+=$row["DEL_MAIL"];
        	                $usertotal[$user]["APPPHOMAIL"]+=$row["APP_MAIL_PHOTOS"];
				$daytotal[$day]["APPPROFMAIL"]+=$row["APP_MAIL"];
				$daytotal[$day]["APPPHOMAIL"]+=$row["APP_MAIL_PHOTOS"];
				$daytotal[$day]["DELPROFMAIL"]+=$row["DEL_MAIL"];
				$total["PROFAPPMAIL"]+=$row["APP_MAIL"];
				$total["PHOAPPMAIL"]+=$row["APP_MAIL_PHOTOS"];
				$total["PROFDELMAIL"]+=$row["DEL_MAIL"];

				 $data[$user][$day]["APPPHO"]=$row["NEW_PHOTOS"]+$row["EDIT_PHOTOS"]+$row["APP_MAIL_PHOTOS"]+$row["APP_PIC_APPROVE"]+$row["APP_PIC_EDITED"];
                                $data[$user][$day]["DELPHO"]=$row["NEW_DEL_PHOTOS"]+$row["EDIT_DEL_PHOTOS"]+$delMailPhotos;
                                $usertotal[$user]["APPPHO"]+=$row["NEW_PHOTOS"]+$row["EDIT_PHOTOS"]+$row["APP_MAIL_PHOTOS"]+$row["APP_PIC_APPROVE"]+$row["APP_PIC_EDITED"];
                                $usertotal[$user]["DELPHO"]+=$row["NEW_DEL_PHOTOS"]+$row["EDIT_DEL_PHOTOS"]+$delMailPhotos;
                                $daytotal[$day]["APPPHO"]+=$row["NEW_PHOTOS"]+$row["EDIT_PHOTOS"]+$row["APP_MAIL_PHOTOS"]+$row["APP_PIC_APPROVE"]+$row["APP_PIC_EDITED"];
                                $daytotal[$day]["DELPHO"]+=$row["NEW_DEL_PHOTOS"]+$row["EDIT_DEL_PHOTOS"]+$delMailPhotos;
                                $total["PHOAPP"]+=$row["NEW_PHOTOS"]+$row["EDIT_PHOTOS"]+$row["APP_MAIL_PHOTOS"]+$row["APP_PIC_APPROVE"]+$row["APP_PIC_EDITED"];
				$total["PHODEL"]+=$row["NEW_DEL_PHOTOS"]+$row["EDIT_DEL_PHOTOS"]+$delMailPhotos;

                                $data[$user][$day]["APPPHONEW"]=$row["NEW_PHOTOS"];
                                $data[$user][$day]["DELPHONEW"]=$row["NEW_DEL_PHOTOS"];
				$data[$user][$day]["DELPHOMAIL"]+=$delMailPhotos;
                                $usertotal[$user]["APPPHONEW"]+=$row["NEW_PHOTOS"];
                                $usertotal[$user]["DELPHONEW"]+=$row["NEW_DEL_PHOTOS"];
				$usertotal[$user]["DELPHOMAIL"]+=$delMailPhotos;
                                $daytotal[$day]["APPPHONEW"]+=$row["NEW_PHOTOS"];
                                $daytotal[$day]["DELPHONEW"]+=$row["NEW_DEL_PHOTOS"];
				$daytotal[$day]["DELPHOMAIL"]+=$delMailPhotos;
                                $total["PHOAPPNEW"]+=$row["NEW_PHOTOS"];
                                $total["PHODELNEW"]+=$row["NEW_DEL_PHOTOS"];
				$total["PHODELMAIL"]+=$delMailPhotos;
                                $data[$user][$day]["APPPHOEDIT"]=$row["EDIT_PHOTOS"];
                                $data[$user][$day]["DELPHOEDIT"]=$row["EDIT_DEL_PHOTOS"];
                                $usertotal[$user]["APPPHOEDIT"]+=$row["EDIT_PHOTOS"];
                                $usertotal[$user]["DELPHOEDIT"]+=$row["EDIT_DEL_PHOTOS"];
                                $daytotal[$day]["APPPHOEDIT"]+=$row["EDIT_PHOTOS"];
                                $daytotal[$day]["DELPHOEDIT"]+=$row["EDIT_DEL_PHOTOS"];
                                $total["PHOAPPEDIT"]+=$row["EDIT_PHOTOS"];
                                $total["PHODELEDIT"]+=$row["EDIT_DEL_PHOTOS"];                        
							
                                $data[$user][$day]["APPPHOAPP_PIC"]=$row["APP_PIC_APPROVE"];
                                $data[$user][$day]["EDITPHOAPP_PIC"]=$row["APP_PIC_EDITED"];
                                $usertotal[$user]["APPPHOAPP_PIC"]+=$row["APP_PIC_APPROVE"];
                                $usertotal[$user]["EDITPHOAPP_PIC"]+=$row["APP_PIC_EDITED"];
                                $daytotal[$day]["APPPHOAPP_PIC"]+=$row["APP_PIC_APPROVE"];
                                $daytotal[$day]["EDITPHOAPP_PIC"]+=$row["APP_PIC_EDITED"];
                                $total["PHOAPPAPP_PIC"]+=$row["APP_PIC_APPROVE"];
                                $total["PHOEDITAPP_PIC"]+=$row["APP_PIC_EDITED"];                        
			}
			$grand["PROF"]=$total["PROFAPP"]+$total["PROFDEL"];
			$grand["PROFNEW"]=$total["PROFAPPNEW"]+$total["PROFDELNEW"];
			$grand["PROFMAIL"]=$total["PROFAPPMAIL"]+$total["PROFDELMAIL"];
			$grand["PROFEDIT"]=$total["PROFAPPEDIT"]+$total["PROFDELEDIT"];
			$grand["PROFAPP_PIC"]=$total["PROFAPPAPP_PIC"];
                        $grand["PHO"]=$total["PHOAPP"]+$total["PHODEL"];
			$grand["PHONEW"]=$total["PHOAPPNEW"]+$total["PHODELNEW"];
  		    	$grand["PHOEDIT"]=$total["PHOAPPEDIT"]+$total["PHODELEDIT"];
  		    	$grand["PHOAPP_PIC"]=$total["PHOAPPAPP_PIC"]+$total["PHOEDITAPP_PIC"];

                	$smarty->assign('monthDaysArray',$monthDaysArray);
	                $smarty->assign('searchFlag',$searchFlag);
        	        $smarty->assign('searchMonth',$searchMonth);
                	$smarty->assign('searchYear',$searchYear);
	                $smarty->assign("CHECKSUM",$checksum);
			$smarty->assign("data",$data);
			$smarty->assign("usertotal",$usertotal);
			$smarty->assign("daytotal",$daytotal);
			$smarty->assign("total",$total);
			$smarty->assign("grand",$grand);
        	        $smarty->display("photo_screen_stats.htm");

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
                $smarty->display("photo_screen_stats.htm");
        }


	
}
else
{
        $smarty->assign('$user',$user);
        $smarty->display("jsconnectError.tpl");
}
?>
