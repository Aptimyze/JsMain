<?php	
/****************************************************bms_getcode.php*********************************************************/ /*    *       Created By         :    Shobha Kumari
        *       Last Modified By   :    Shobha Kumari
        *       Description        :    used to generate code of the banner templates
        *       Includes/Libraries :    none
****************************************************************************************************************************/
//include("./includes/bms_display_include.php");

function getcode($zoneid)
{
	global $_LogosZone,$smarty,$dbbms,$_HITSFILE,$_LOGIMPS,$zid;

	$sql="Select * from bms2.ZONE where ZoneId='$zoneid' and ZoneStatus='active'";
	$result=mysql_query($sql,$dbbms) or die(mysql_error());
	if($myrow=mysql_fetch_array($result))
	{
		$zoneArr["zid"]=$myrow["ZoneId"];
		$zonestr=$zoneArr["zid"];
		$zoneArr["maxbans"]=$myrow["ZoneMaxBans"];
		$zoneArr["ispop"]=$myrow["ZonePopup"];
		$zoneArr["align"]=$myrow["ZoneAlignment"];
		$zoneArr["bwidth"]=$myrow["ZoneBanWidth"];
		$zoneArr["bheight"]=$myrow["ZoneBanHeight"];

		if($zoneArr["ispop"]!='Y')
		{
			$sqlBan="Select * from bms2.BANNER where ZoneId = '$zonestr' and BannerStatus='live' Order By ZoneId,BannerPriority ASC";
			$resBan=mysql_query($sqlBan,$dbbms) or die(mysql_error());
			if($myrow=mysql_fetch_array($resBan))
			{
				$i=1;
				do
				{
					$ban[$i]["banid"]=$myrow["BannerId"];
					if($banstr)
					{
						$banstr=$banstr.",".$ban[$i]["banid"];
					} 
					else
					{
						$banstr=$ban[$i]["banid"];
					}
					$zb=$myrow["ZoneId"];

					if($banzone)
					{
						$banzone.=",".$i;
					}
					else
					{ 
						$banzone=$i;
					}

					$ban[$i]["zid"]=$myrow["ZoneId"];
					$ban[$i]["class"]=$myrow["BannerClass"];
					$ban[$i]["isstat"]=$myrow["BannerStatic"];
					$ban[$i]["pri"]=$myrow["BannnerPriority"];
					$ban[$i]["gif"]=$myrow["BannerGif"];
					$ban[$i]["url"]=$myrow["BannerUrl"];
					$ban[$i]["isdef"]=$myrow["BannerDefault"]; 
					$ban[$i]["flist"]=$myrow["BannerFeatures"];
					$ban[$i]["str"]=$myrow["BannerString"];
					$i=$i+1;
				}while($myrow=mysql_fetch_array($resBan));
			}
			$zone=$zoneArr["zid"];
			$isPop=$zoneArr["ispop"];
			$align=$zoneArr["align"];
			$banwidth=$zoneArr["bwidth"];
			$banheight=$zoneArr["bheight"];
			$maxbans=$zoneArr["maxbans"];
			$banners=$banzone;
			$banner=explode(',',$banners);
			$str="zonedisp";

			if($align=='V')
			{ 
				$echostr="<Table border=0 cellspacing=2 cellpadding=2>";
     			//	$echostr.="<TR>"
     			} 
    			elseif($align=='H')
			{
				$echostr="<Table border=0 cellspacing=2 cellpadding=2>";
				$echostr.="<TR>";
			}
     			//print_r($banner); 	     
			for($j=0;$j<count($banner);$j++)
     			{
				$bid=$banner[$j];
			        $pri=$ban[$bid]["pri"];
				$bid=$banner[$j];
				$banid=$ban[$bid]["banid"];
				$class=$ban[$bid]["class"];
				$level=$ban[$bid]["level"]; 
				$gif=$ban[$bid]["gif"];
				$url=$ban[$bid]["url"];
				$isStat=$ban[$bid]["isstat"];
				$isdef=$ban[$bid]["isdef"];
				$flist=$ban[$bid]["flist"];
     
				if($align=='V')
				{
					$echostr.="<tr><td width=$banwidth>";
			  		if($isStat=='Y')
			  		{
						$echostr.="<img src='$gif' border=0>";		 	
			  		}
			  		else
			  		{
						$echostr.="<a href='$_HITSFILE?banner=$banid' target='_blank'><img src='$gif' border=0></a>";
			  		}
			  		$echostr.="</td></tr>";   
			  		if($banlist)
			  		{
						$banlist.=",".$banid;
			  		}
			  		else
			  		{
						$banlist=$banid;
			  		}
				}
				elseif ($align=='H')
				{
		      			$echostr.="<td height=$banheight>";
			  		if($isStat=='Y')
			  		{
						$echostr.="<img src='$gif' border=0>";		 	
			  		}
			 		else
			 		{
						$echostr.="<a href='$_HITSFILE?banner=$banid'  target='_blank'><img src='$gif' border=0></a>";
			 		}
			 		$echostr.="</td>";
			 		if($banlist)
			 		{
						$banlist.=",".$banid;
			 		}
			 		else
			 		{
						$banlist=$banid;
			 		}
				} 
			}
			if($banlist) 
				$echostr.="<img src=$_LOGIMPS?banlist=$banlist>";
			if($align=='V')
			{ 
				$echostr.="</Table>";
	     		} 
     			elseif($align=='H')
			{
				$echostr.="</TR>";
				$echostr.="</Table>";
     			}
    			if($echostr!='#')
			{
     				if($banlist)
     				{
					$smarty->assign('zonestr',$echostr);	
					return "true";
     				} 
     				else 
     				{
					return "false"; 
     				}
    			}
		}
		elseif($zoneArr["ispop"]=='Y')
		{
			$popstr="\$zonedisp".$zoneid; 
			$echostr="<Body>
			<Script language=\"JavaScript\">
			function ow(theURL,winName,features)
 			{
   				window.open(theURL,winName,features);
 			}
	 		function owunder()
 			{
				win2=window.open(theURL,winName,features)
				win2.blur()
				window.focus()
	 		} 	
			</Script>
			<Form name=bmsform>
			~if $popstr`
				<Input type=hidden name=hiddenValuesFromBMS value=\"~$popstr`\">
			~/if`

			<Script language=\"JavaScript\">

			var theURL=document.bmsform.hiddenValuesFromBMS.value;
			if(theURL.length>0){
			var str=theURL.split(\",\");
			for(var i=0;i<str.length;i++)
			{
				flist=str[i].split(\"#\");
				var respstr;
				if(flist[1])
				{
					respstr=\"ScreenX=\"+flist[1];
				}  
				if(flist[2])
				{
					if(respstr)
					{
						respstr=respstr+\",ScreenY=\"+flist[2];
					}
					else
					{
						respstr=\"ScreenY=\"+flist[2];
					}
				}
				if(flist[3])
				{
					if(respstr)
					{
						respstr=respstr+\",left=\"+flist[3];
					}
					else
					{
						respstr=\"left=\"+flist[3];
					}
				}
				if(flist[4])
				{
					if(respstr)
					{
						respstr=respstr+\",height=\"+flist[4];
					}
					else
					{
						respstr=\"height=\"+flist[4];
					}
				}
				if(flist[5])
				{
					if(respstr)
					{
						respstr=respstr+\",Width=\"+flist[5];
					}
					else
					{
						respstr=\"width=\"+flist[5];
					}
				}

				if(flist[2]=='PopUp')
				{  	   
					ow(flist[0],'',respstr);
				}
				else
				{
					owunder(flist[0],'',respstr);	
				}
			} 
		}
		</Script> 
		</form>
		</Body>
		";
		if($echostr)
		{
			$smarty->assign('zonestr',$echostr);
			return "true";
		}
		else
		{
			return "false";
		}
	}	  
}
}

//getcode($regid,$zoneid);
 
?>
