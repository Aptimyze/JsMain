<?php
$manglik=array("31"=>"/Chevvai Dosham","17"=>"/Chovva Dosham","16"=>"/Kuja Dosham","3"=>"/Kuja Dosham");
function manglik($profileid,$viewer_viewed="")
{
	global $manglik;
	global $jprofile_result;
	//added by lavesh on 9 aug. If jprofile_result global variable is prevent , query on JPROFILE is saved.
	if(!is_array($jprofile_result) || $viewer_viewed=='')
	{
        	$sSQL="select MTONGUE,MANGLIK from newjs.JPROFILE where  activatedKey=1 and PROFILEID='$profileid'";
	        $res=mysql_query_optimizer($sSQL) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sSql,"ShowErrTemplate");;
        	if($row=mysql_fetch_object($res))
                $mtongue=$row->MTONGUE;
		$m_stat=$row->MANGLIK;
		$flag=1;
	}
	else
	{
		if($viewer_viewed=='viewer')
		{
			$mtongue=$jprofile_result["viewer"]["MTONGUE"];	
			$m_stat=$jprofile_result["viewer"]["MANGLIK"];
			$flag=1;
		}
		if($viewer_viewed=='viewed')
		{
			$mtongue=$jprofile_result["viewed"]["MTONGUE"];	
			$m_stat=$jprofile_result["viewed"]["MANGLIK"];
			$flag=1;
		}
	}

	if($flag)
	{
		if($m_stat=='D') 
			$m_status="Don't Know";
		elseif($m_stat=="")
			$m_status=" - ";
		elseif($m_stat=="M")
		 	$m_status="Yes";
		elseif($m_stat=="N")
                        $m_status="No";
        }
	else
		$m_status=" - ";
        $Manglik="Manglik".$manglik[$mtongue];
	return $m_status."+".$Manglik;
}
function partnermanglik($p_mtongue,$p_manglik)
{
	include("arrays.php");
        global $manglik;
	$m_stat=$p_manglik;
	$m_stat_arr=explode("','",$m_stat);
	for($i=0;$i<count($m_stat_arr);$i++)
	{
		$mang=$m_stat_arr[$i];
		$m_status_arr[]=$MANGLIK_LABEL[$mang];
	}
	$m_status=implode(", ",$m_status_arr);
	$Manglik="Manglik".$manglik[$p_mtongue];
	  return $m_status."+".$Manglik;
}
function set_value_in_js()
{
 global $manglik,$smarty;
 $i=0;
 foreach($manglik as $key=>$val)
 {
	$js.="js_manglik[$key]='".$val."';";
	
 }
$smarty->assign("JS_ARRAY",$js);
}  

                                                                                                                             
 ?>
