<?php

/** 
* Functions related to  banner display are here.
* @author Lavesh Rawat
* @copyright Copyright 2008, Infoedge India Ltd.
*/


/**
* @param string $liveBanners comma seperated live/active banner string.
* @param array $userData user(jeevansathi) related info.
* @param array $CriteriaInuse banner of a given zone booked maximum on which all criteria eg.AGE,INCOME,GENDER,IP-ADDRESS....
* @param string $searchcriteria search criteria of user.Criteria are sepertaed by symbol '|'.
*/
function filterCriterias($liveBanners,$userData="",$CriteriaInuse="",$searchcriteria="")
{
	if($liveBanners)
		$filterCriteria[]="BannerId in ($liveBanners)";
	if($CriteriaInuse)
	{
		if(is_array($userData))
		{
			if(in_array("GENDER",$CriteriaInuse))
				$filterCriteria[]="(BannerGender='' OR  BannerGender='$userData[GENDER]')";
	
			if(in_array("LOCATION",$CriteriaInuse))
			{
				$tempCriteria="(BannerLocation='' OR ";

				if($userData["CITY_INDIA"])
					$tempCriteria.=" BannerInCity REGEXP ' $userData[CITY_INDIA] '";
				elseif($userData["CITY_USA"])
					$tempCriteria.=" BannerUsCity REGEXP ' $userData[CITY_USA] '";
				else
				{	
					if($userData["COUNTRY_RES"])
						$tempCriteria.="BannerCountry REGEXP ' $userData[COUNTRY_RES] '";
					else
						$tempCriteria.="BannerInCity='' OR BannerUsCity=''";
				}
				$tempCriteria.=" )";
				$filterCriteria[]=$tempCriteria;
				unset($tempCriteria);
			}
			if(in_array("IP",$CriteriaInuse))
			{
				$tempCriteria=getIPLocation();			
				$filterCriteria[]="(BannerIP='' OR BannerIP REGEXP ' $tempCriteria ')";
				unset($tempCriteria);
			}
			if(in_array("AGE",$CriteriaInuse))
				$filterCriteria[]="(( BannerAgeMin='' OR BannerAgeMin='-1') OR (BannerAgeMin <='$userData[AGE]' AND BannerAgeMax>='$userData[AGE]'))";	
			if(in_array("INCOME",$CriteriaInuse))
				$filterCriteria[]="( BannerCTC='' OR BannerCTC REGEXP ' $userData[INCOME] ')";
			if(in_array("SUBSCRIPTION",$CriteriaInuse))
			{
				$tempCriteria=explode(",",$userData["SUBSCRIPTION"]);
				$size=count($tempCriteria);
				$j=0;
				
				foreach ($tempCriteria as $key => $value)
					$tempstr[]="(BannerMEM REGEXP ' $value ')";

				$temp_str=implode(" OR ",$tempstr);
				$filterCriteria[]="(BannerMEM='' OR BannerMEM REGEXP ' R ' OR ($temp_str))";
			}

			if(in_array("MARITALSTATUS",$CriteriaInuse))
				$filterCriteria[]="(BannerMARITALSTATUS='' OR BannerMARITALSTATUS REGEXP ' $userData[MSTATUS] ')";
			if(in_array("EDUCATION",$CriteriaInuse))
				$filterCriteria[]="(BannerEDU='' OR BannerEDU REGEXP ' $userData[EDU_LEVEL] ')";
			if(in_array("OCCUPATION",$CriteriaInuse))
				$filterCriteria[]="(BannerOCC='' OR BannerOCC REGEXP ' $userData[OCCUPATION] ')";
			if(in_array("COMMUNITY",$CriteriaInuse))
				$filterCriteria[]="(BannerCOM='' OR BannerCOM REGEXP ' $userData[MTONGUE] ')";
			if(in_array("RELIGION",$CriteriaInuse))
			{
				$filterCriteria[]="(BannerREL='' OR BannerREL REGEXP ' $userData[RELIGION] ')";
			}
		}
		else
		{

			if($searchcriteria!='')
			{
				$searchstr = explode("|",$searchcriteria);

				if(in_array("PROPCAT",$CriteriaInuse))
				{
					$acres99=1;
					if ($searchstr[1] != '' )                
					{
						$tempPropcat = $searchstr[1];
						if($tempPropcat=='S')
							$filterCriteria[]="(BannerPROPCAT=' ' OR BannerPROPCAT REGEXP 'Buy')";
						elseif($tempPropcat=='R')
							$filterCriteria[]="(BannerPROPCAT=' ' OR BannerPROPCAT REGEXP 'Rent')";
					}
					else
						$filterCriteria[]="(BannerPROPCAT=' ')";
				}

				if(in_array("PROPCITY",$CriteriaInuse))
				{
					$acres99=1;
					if ($searchstr[2] != '' )           
						 $filterCriteria[]="(BannerPROPCITY=' ' OR BannerPROPCITY REGEXP ' $searchstr[2] ')";
					else
						 $filterCriteria[]="(BannerPROPCITY=' ')";
				}
	
				if(in_array("PROPINR",$CriteriaInuse))
				{
					$acres99=1;
					if ($searchstr[3] != '' )                 
						 $filterCriteria[]="(BannerPROPINR=' ' OR BannerPROPINR REGEXP ' $searchstr[3] ')";
					else
						$filterCriteria[]="(BannerPROPINR=' '')";			
				}

				if(in_array("PROPTYPE",$CriteriaInuse))
				{
					$acres99=1;
					if ($searchstr[4] != '' )                 
						$filterCriteria[]="(BannerPROPTYPE=' ' OR BannerPROPTYPE REGEXP ' $searchstr[4] ')";
					else
						$filterCriteria[]="(BannerPROPTYPE=' ')";
				}
	
				if ($searchstr[5] != '' )//jeevansathi                 
				{
					if(in_array("GENDER",$CriteriaInuse))
					{
						if($searchstr[5] == 'F')
							$filterCriteria[]="(BannerGender=' ' OR  BannerGender='M')";
						elseif($searchstr[5] == 'M')
							$filterCriteria[]="(BannerGender=' ' OR  BannerGender='F')";
					}	
				}
				else
				{
					if(in_array("GENDER",$CriteriaInuse))
						$filterCriteria[]="(BannerGender='')";
				}
			}

			if(!$acres99)
			{
				//logout case.
				if(in_array("LOCATION",$CriteriaInuse))
					$filterCriteria[]="(BannerLocation='')";
				if(in_array("IP",$CriteriaInuse))
				{
					$tempCriteria=getIPLocation();			
					$filterCriteria[]="(BannerIP='' OR BannerIP REGEXP ' $tempCriteria ')";
					unset($tempCriteria);
				}
				if(in_array("AGE",$CriteriaInuse))
					$filterCriteria[]="(BannerAgeMin='' OR BannerAgeMin='-1')";
				if(in_array("INCOME",$CriteriaInuse))
					$filterCriteria[]="(BannerCTC='')";	
				if(in_array("SUBSCRIPTION",$CriteriaInuse))
					$filterCriteria[]="(BannerMEM='')";
				if(in_array("MARITALSTATUS",$CriteriaInuse))
					$filterCriteria[]="(BannerMARITALSTATUS='')";
				if(in_array("EDUCATION",$CriteriaInuse))
					$filterCriteria[]="(BannerEDU='')";
				if(in_array("OCCUPATION",$CriteriaInuse))
					$filterCriteria[]="(BannerOCC='')";
				if(in_array("COMMUNITY",$CriteriaInuse))
					$filterCriteria[]="(BannerCOM='')";
				if(in_array("RELIGION",$CriteriaInuse)) 
					$filterCriteria[]="(BannerREL='')";
				//logout case.
			}
		}
	}
	return($filterCriteria);
}

/**
* Give the location-id corresponding to user ip-address.
* Usefull if a banner is booked on ip-address.
*/
function getIPLocation()
{
	$mysqlObj=new Mysql;
	$mysqlObj->connect();

        $ip = FetchClientIP(); // gives the IP Address of the machine from which the user is accessing the site
        $iparr = explode(".",$ip);

        $ipnum = (256*256*256)*$iparr[0] + (256*256)*$iparr[1] + 256*$iparr[2] + $iparr[3];
        $sql = "Select endIpNUM from GeoIP.Blocks  where endIpNum >= '$ipnum' ORDER BY endIpNUM LIMIT 1 ";
	if($result = $mysqlObj->Query($sql))
        {
                if ($myrow=$mysqlObj->fetchArray($result))
                {
                        $endipnum = $myrow["endIpNUM"];
                        $sql1 = "Select locid from GeoIP.Blocks where endIpNUM = '$endipnum' AND startIpNum <= '$ipnum'";
                        if($result1=$mysqlObj->Query($sql1))
                        {
                                if ($myrow1 = $mysqlObj->fetchArray($result1))
                                {
                                        $locid = $myrow1["locid"];
                                        return $locid ;
                                }
                        }
                }
        }
}


/**
* Function that finally displays the banners
* @param int-array $finlist list of banners to be dispalayed. 
* @param int-array $bannarr all info of all live banners of a current zone.
* @param int $zone zoneid
* @param ref-obj $zoneObj object refering to zone class.It is passed as value set by zone class earlier need to be used and creating object agaian will distroy all set value.
  @return string $returnzones Returns the string for display in a zone
*/
function actual_display($finlist,$bannarr,$zone,$zoneObj)
{
	global $_LogosZone,$_HITSFILE,$smarty,$othersrcp , $_SERVER , $_SITEURL,$data; 
	global $mailer , $hit , $dbbms , $flash99 , $isTextLink , $searchid;
	global $showall;

        $mysqlObj=new Mysql;
        $mysqlObj->connect();

	$maxbans = $zonearr[$zone]["maxbans"];
	$maxbans=$zoneObj->getZoneMaxBans();
	$align = $zoneObj->getZoneAlignment();
	$width = $zoneObj->getZoneBanWidth();
	$height = $zoneObj->getZoneBanHeight();
	$ispopup = $zoneObj->getZonePopup();
	$spacing=$zoneObj->getZoneSpacing();

	if(!$spacing)
		$spacing=2;

	//Ends Here
    	if($zone!=$_LogosZone)
    	{
    		if($ispopup!='Y')
    		{
			if ($showall == 1 && !$mailer)
			{
				echo ("<HTML><HEAD></HEAD>");
				echo ("<BODY style=\"margin:0px\">");
				echo ("<Table border=0 cellpadding=0 cellspacing=0 align=center style=\"border:0px\">");
                                if($align=='H')
                                {
                                        echo ("<TR>");
                                }

			}
	
   			for($i=1;$i<=$maxbans;$i++)
   			{
   				$banner=$finlist[$zone][$i];
   				if($banner)
   				{
					$isstat = $bannarr[$banner]["BannerStatic"];
					$gif = $bannarr[$banner]["BannerGif"];
					$class = $bannarr[$banner]["BannerClass"];
					$url = $bannarr[$banner]["BannerUrl"];

					if($class == 'textlink') 
					{
						logimpression($banner);	
						$captureclick 	= $_HITSFILE."?banner=".$banner;
						servetextlink($captureclick,$gif);
					}
					elseif($class=='flv' || $class=='wmv')
					{
						if($align=='H' && $showall == 1)
							echo("<TD width=\"1\">");
						elseif($align=='V' && $showall == 1)
                                                	echo("<tr><TD>");

						logimpression($banner);

						if($class=='wmv')
						{
							echo("<OBJECT ID=\"MediaPlayer\" WIDTH=$width height=$height CLASSID=\"CLSID:22D6F312-B0F6-11D0-94AB-0080C74C7E95\" STANDBY=\"Loading Video...\" TYPE=\"application/x-oleobject\"><PARAM NAME=\"FileName\" VALUE=\"".JsConstants::$bmsVideoUrl."/99acres/$gif\"><param name=\"DisplaySize\" value=\"false\"><PARAM name=\"ShowControls\" VALUE=\"false\"><param name=\"ShowStatusBar\" value=\"true\"><PARAM name=\"ShowDisplay\" VALUE=\"false\"><PARAM name=\"AutoRewind\" VALUE=\"True\"><PARAM name=\"autostart\" VALUE=\"true\"><EMBED TYPE=\"application/x-mplayer2\" SRC=\"".JsConstants::$bmsVideoUrl."/99acres/$gif\" NAME=\"MediaPlayer\" WIDTH=$width height=$height ShowControls=\"0\" ShowStatusBar=\"1\" ShowDisplay=\"0\" autostart=\"1\" loop=\"true\" DisplaySize=\"0\" pluginspage=\"http://www.microsoft.com/Windows/Downloads/Contents/Products/MediaPlayer/\"> </EMBED></OBJECT>");
						}
						else
						{
							echo("<object classid=\"clsid:D27CDB6E-AE6D-11cf-96B8-444553540000\" codebase=\"http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=7,0,19,0\" width=$width height=$height><param name=\"movie\" value=\"".JsConstants::$bmsUrl."/bmsjs/flv_videoserve.swf?_vidName=$gif&_headVid=$url\" /><param name=\"quality\" value=\"high\" /><embed src=\"".JsConstants::$bmsUrl."/bmsjs/flv_videoserve.swf?_vidName=$gif&_headVid=$url\" quality=\"high\" pluginspage=\"http://www.macromedia.com/go/getflashplayer\" type=\"application/x-shockwave-flash\" width=$width height=$height></embed></object>");
						}

						if ($showall == 1 && $align=='H')
						{
							echo ("</TD><TD>&nbsp;</TD>");
						}
						if ($showall == 1 && $align=='V')
                                                        echo ("</TD></TR><TR><TD height=1></TD></TR>");
					}
					elseif($class!= 'textlink')
					{
						if($align=='H') // in case of horizontal alignment of the banners
						{
							if ($mailer == 1 || $flash99 == 1) // for banner display in matchalert
							{
								if(!$hit)
								{
									logimpression($banner);
									if(strstr(substr($gif,-3),"BannerGif"))
										@header("Content-Type:image/gif");
									if (!is_numeric($gif))
										readfile("$gif");
								}
								else
								{
									$dt=Date('Y-m-d');
																    
									$sql="Update bms2.BANNERMIS set Clicks=Clicks+1 where BannerId='$banner' and Date='$dt'";
									$result = $mysqlObj->Query($sql);

									if($mysqlObj->affectedRows()==0)
									{
										$sql="Insert into bms2.BANNERMIS (BannerId,Date,Clicks) values ('$banner','$dt','1')";
										$result = $mysqlObj->Query($sql);
									}

									if($url)
									{
										if($othersrcp && trim($othersrcp)!='')
											$url=preg_replace("/othersrcp=[^\&]*/","othersrcp=$othersrcp",$url);
										if($url) 
											header("Location: $url");
									}
								}
							}
							else
							{
								logimpression($banner);
								if ($showall == 1)
								{
									echo("<TD width=\"1\">");
								}
								if($isstat != 'Y')	// if the banner is clickable
									echo("<a href=\"$_HITSFILE?banner=$banner\" target='_blank'>");

								if($class =='html' || strstr(substr($gif,-3),"htm") || strstr(substr($gif,-4),"html")) // if banner is an html file
								{
									readfile("$gif");
									if ($showall == 1)
									{
										echo ("</TD><TD>&nbsp;</TD>");
									}
								}
								elseif(strstr(substr($gif,-3),"swf")) // if the banner is a flash file 
								{
                                                                        //newL
                                                                        if($class=='Flash-shoshkelle')
                                                                                echo ("<script language=\"JavaScript\" type=\"text/JavaScript\">function findObj(n, d) {var p,i,x; if(!d) d=document; if((p=n.indexOf(\"?\"))>0&&parent.frames.length) {d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);}if(!(x=d[n])&&d.all) x=d.all[n]; for (i=0;!x&&i<d.forms.length;i++) x=d.forms[i][n];for(i=0;!x&&d.layers&&i<d.layers.length;i++) x=findObj(n,d.layers[i].document);\if(!x && d.getElementById) x=d.getElementById(n); return x;}function showHideLayers() { var i,p,v,obj,args=showHideLayers.arguments;for (i=0; i<(args.length-2); i+=3) if ((obj=findObj(args[i]))!=null) { v=args[i+2];if (obj.style) { obj=obj.style; v=(v==\'show\')?\'visible\':(v==\'hide\')?'hidden\':v; }obj.visibility=v; }}//--></script>");

									//Extra Parameter will be send with flas file to record hits.
									if($url)
										echo("<object classid=\"clsid:D27CDB6E-AE6D-11cf-96B8-444553540000\" codebase=\"http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=7,0,19,0\" width=$width height=$height ><param name=\"movie\" value=\"$gif?clickTAG=http://ser4.jeevansathi.com/bmsjs/bms_hits.php?banner=$banner\" /><param name=\"quality\" value=\"high\" /><embed src=\"$gif?clickTAG=http://ser4.jeevansathi.com/bmsjs/bms_hits.php?banner=$banner\" width=$width height=$height ></embed></object><SCRIPT type=text/javascript><!--objects = document.getElementsByTagName(\"object\");for (var i = 0; i < objects.length; i++){objects[i].outerHTML = objects[i].outerHTML;}--></SCRIPT>");
									else
										echo("<object classid=\"clsid:D27CDB6E-AE6D-11cf-96B8-444553540000\" codebase=\"http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=7,0,19,0\" width=$width height=$height ><param name=\"movie\" value=\"$gif\" /><param name=\"quality\" value=\"high\" /><embed src=\"$gif\" width=$width height=$height ></embed></object><SCRIPT type=text/javascript><!--objects = document.getElementsByTagName(\"object\");for (var i = 0; i < objects.length; i++){objects[i].outerHTML = objects[i].outerHTML;}--></SCRIPT>");
									//ends

									if ($showall == 1)
									{
										echo("</TD><TD>&nbsp;</TD>");
									}
								}
								elseif(strstr(substr($gif,-3),"php")) // if banner is a php file
								{
									echo "<html><body><META HTTP-EQUIV=\"refresh\" CONTENT=\"0;URL=$gif?profileid=$data&searchid=$searchid\"></body></html>";
								}
								else
								{
									echo("<img src=\"$gif\" border=0\" hspace=\"$spacing\"></a>");
									if ($showall == 1)
										echo("</TD><TD>&nbsp;</TD>");
								}	
							}
						}
						elseif($align=='V') // in case of vertical alignment of banners
						{	
							if ($mailer == 1 || $flash99 == 1) // for banner display in matchalert
							{
								if(!$hit)
								{
									logimpression($banner);
									if(strstr(substr($gif,-3),"BannerGif"))
										@header("Content-Type:image/gif");
									elseif (strstr(substr($gif,-3),"swf"))
										@header("Content-Type:application/x-shockwave-flash");

									if (!is_numeric($gif))
										readfile("$gif");
								}
								else
								{
									$dt=Date('Y-m-d');
																    
									$sql="Update bms2.BANNERMIS set Clicks=Clicks+1 where BannerId='$banner' and Date='$dt'";
									$result = $mysqlObj->Query($sql);

									if($mysqlObj->affectedRows()==0)
									{
										$sql="Insert into bms2.BANNERMIS (BannerId,Date,Clicks) values ('$banner','$dt','1')";
										$result = $mysqlObj->Query($sql);
									}

									if($url)
									{
										if($othersrcp && trim($othersrcp)!='')
											$url=preg_replace("/othersrcp=[^\&]*/","othersrcp=$othersrcp",$url);
										if($url) 
											echo "</table></html><html><META HTTP-EQUIV=\"REFRESH\" CONTENT=\"0;URL=$url\"></html>";
									}

								}
							}
							else
							{
								logimpression($banner);
								if ($showall == 1)
									echo("<tr><TD>");
								if($isstat != 'Y')	// if the banner is clickable
									echo("<a href=\"$_HITSFILE?banner=$banner\" target='_blank'>");
								if($class =='html' || strstr(substr($gif,-3),"htm") || strstr(substr($gif,-4),"html"))  // if the banner is a html file
								{
									readfile($gif);
									if ($showall == 1)
										 echo ("</TD></TR><TR><TD height=1></TD></TR>");
								}
								elseif(strstr(substr($gif,-3),"swf"))   // if the banner is a flash file
								{
                                                                        //newL
                                                                        if($class=='Flash-shoshkelle')
                                                                                echo ("<script language=\"JavaScript\" type=\"text/JavaScript\">function findObj(n, d) {var p,i,x; if(!d) d=document; if((p=n.indexOf(\"?\"))>0&&parent.frames.length) {d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);}if(!(x=d[n])&&d.all) x=d.all[n]; for (i=0;!x&&i<d.forms.length;i++) x=d.forms[i][n];for(i=0;!x&&d.layers&&i<d.layers.length;i++) x=findObj(n,d.layers[i].document);\if(!x && d.getElementById) x=d.getElementById(n); return x;}function showHideLayers() { var i,p,v,obj,args=showHideLayers.arguments;for (i=0; i<(args.length-2); i+=3) if ((obj=findObj(args[i]))!=null) { v=args[i+2];if (obj.style) { obj=obj.style; v=(v==\'show\')?\'visible\':(v==\'hide\')?'hidden\':v; }obj.visibility=v; }}//--></script>");

                                                                        if($url)
										echo("<object classid=\"clsid:D27CDB6E-AE6D-11cf-96B8-444553540000\" codebase=\"http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=7,0,19,0\" width=$width height=$height ><param name=\"movie\" value=\"$gif?clickTAG=http://ser4.jeevansathi.com/bmsjs/bms_hits.php?banner=$banner\" /><param name=\"quality\" value=\"high\" /><embed src=\"$gif?clickTAG=http://ser4.jeevansathi.com/bmsjs/bms_hits.php?banner=$banner\" width=$width height=$height ></embed></object><SCRIPT type=text/javascript><!--objects = document.getElementsByTagName(\"object\");for (var i = 0; i < objects.length; i++){objects[i].outerHTML = objects[i].outerHTML;}--></SCRIPT>");

                                                                        else
										 echo("<object classid=\"clsid:D27CDB6E-AE6D-11cf-96B8-444553540000\" codebase=\"http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=7,0,19,0\" width=$width height=$height ><param name=\"movie\" value=\"$gif\" /><param name=\"quality\" value=\"high\" /><embed src=\"$gif\" width=$width height=$height ></embed></object><SCRIPT type=text/javascript><!--objects = document.getElementsByTagName(\"object\");for (var i = 0; i < objects.length; i++){objects[i].outerHTML = objects[i].outerHTML;}--></SCRIPT>");
									//ends here

									if ($showall == 1)
										echo ("</TD></TR><TR><TD height=1></TD></TR>");
								}
								elseif(strstr(substr($gif,-3),"php")) // if banner is a php file
                                                                {
                                                                        echo "<html><body><META HTTP-EQUIV=\"refresh\" CONTENT=\"0;URL=$gif?profileid=$data&searchid=$searchid\"></body></html>";
                                                                }
								else
								{
									echo("<img src=\"$gif\" border=0\" vspace=\"$spacing\"></a>");
									if ($showall == 1)
										echo ("</TD></TR><TR><TD height=1></TD></TR>");

								}
							}
						}
					}
				}
    			}
			if ($showall == 1)
			{
				if($align=='H')
                        	{
                                	echo("</tr>");
                        	}
				$showallstr.="</TABLE></BODY></HTML>";
			}

		}
    		else  // for pop up/popunder / banner in new window
    		{
    		  	for($i=1;$i<=$maxbans;$i++)
			{
				$banner = $finlist[$zone][$i];

				if ($banner)
				{
					logimpression($banner);
					$features=$bannarr[$banner]["BannerFeatures"];
					$class=$bannarr[$banner]["BannerClass"];
					$gif=$bannarr[$banner]["BannerGif"];

					if(strstr(substr($gif,-3),"htm")) // if the popup / popunder banner is a html file
					{	
						$popstr = "$gif";
					}
					else
					{	
						// to create an html file out of .gif 
						$popstr = "$_SITEURL/bmsjs/jspopup.php?zone=$zone&banner=$banner&gif=$gif";
					}
					$echostr = $popstr."#".$features."#".$class;
					popupwin($echostr); // function for opening popup/popunder
				}
    			}
    		}
  	}
  	else
	{
		for($i=1;$i<=$maxbans;$i++)
   		{
   			$banner=$finlist[$zone][$i];
   			
   			if($banner)
   			{
				$isstat=$bannarr[$banner]["BannerStatic"];
				$gif=$bannarr[$banner]["BannerGif"];
				$class=$bannarr[$banner]["BannerClass"];
				$bannerstring=$bannarr[$banner]["BannerString"];
				if(strstr(substr($gif,-3),"htm"))
				{
					//to zip the file before sending it
														    
					$zipIt = 0;
					if (strstr($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip'))
						$zipIt = 1;
					if($zipIt)
						ob_start("ob_gzhandler");
					//end of it
				}

				if($isstat=='Y')
				{
					logimpression($banner);
					if(strstr(substr($gif,-3),"htm"))
						readfile("$gif");
					else
						echo("<img src=\"$gif\" border=0\"></a>");

  	   			}
  	   			else
  	   			{	logimpression($banner);
					echo("<a href=\"$_HITSFILE?banner=$banner\" target='_blank'>");
					if(strstr(substr($gif,-3),"htm"))
						readfile("$gif");
					else
						echo("<img src=\"$gif\" border=0\"></a>");

  	   			}
  	   			$smarty->assign($bannerstring,$echostr);
  	   			$echostr="";
   			}
   		}
   		$returnzones=$echostr;
  	}
  	return $returnzones;	
}

function servetextlink($captureclick,$gif)
{
        echo("
                   <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"95%\">
                     <tr>
                       <td>
                        <div style=\"overflow: hidden;padding-left: 0px;\" align=\"left\">
                                     <a  href=\"$captureclick\" class=\"class4\" target=\"_blank\"><b><span>$gif</span></b></a>
                       </div>
                      </td>
                    </tr>
                   </table>
        ");
}

 /*****************************************************************************************
        Function that opens a banner in a new window , creates pop up/popunder
        Input : String including url of gif alongwith the parameters (height , width etc) for pop up/popunder
   ****************************************************************************************/
function popupwin($echostr)
{
        $str = "";
        $str .= "function pop(){";
        $str .= "if(1){";//'$echostr'){";
        $str .= "var theURL=\"$echostr\";";
        $str .= "if(theURL.length>0){";
        $str .= "var str=theURL.split(\",\");";
        $str .= "for(var i=0;i < str.length;i++){";
        $str .= "if(str[i]!=\"\"){";
        $str .= "flist=str[i].split(\"#\");";
        $str .= "var respstr;";
        $str .= "if(flist[1]){respstr=\"ScreenX=\"+flist[1];}";
        $str .= "if(flist[2]){";
        $str .= "if(respstr){ respstr=respstr+\",ScreenY=\"+flist[2];}";
        $str .= "{respstr=\"ScreenY=\"+flist[2];} }";
        $str .= "if(flist[3]){ if(respstr) { respstr=respstr+\",left=\"+flist[3]; }";
        $str .= "else { respstr=\"left=\"+flist[3];} }";
        $str .= "if(flist[4]) {if(respstr){ respstr=respstr+\", Width=\"+flist[4];} else { respstr=\"width=\"+flist[4];} }";
        $str .= "if(flist[5]) {if(respstr){ respstr=respstr+\",height=\"+flist[5];}  else  { respstr=\"height=\"+flist[5];}}";
        $str .= "if(flist[6]=='PopUp'){ ow(flist[0],i,respstr);}else { owunder(flist[0],i,respstr);}}";
        $str .= " } } } }";
        $str .= "function ow(theURL,winName,features){ window.open(theURL,winName,features); }";
        $str .= "function owunder(theURL,winName,features){ var win2; win2=window.open(theURL,winName,features);";
        $str .= "if(win2){win2.blur(); window.focus();}}";
        $str .= "pop();";
        echo $str;
}

function logimpression($banlist)
{
        if($banlist != 0)
        {
		global $memcacheObj;
		//$memcacheObj=new UserMemcache;
		$memcacheObj->logBannerImpression($banlist);	

                //$sql="Update bms2.BANNERHEAP set BannerCount=(BannerCount+1) , BannerServed=(BannerServed+1) where BannerId = '$banlist'";
                //mysql_query($sql) or logErrorBms("Error in logging impressions",$sql,"continue","YES");
        }
}

function defaultbanner()
{
        global $_SITEURL;
        echo("<img src=\"http://jeevansathi.com/P/IN/zero.gif\">");
}

   
?>
