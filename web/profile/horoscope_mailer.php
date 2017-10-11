<?php
ini_set("max_execution_time","0");
                                                                                                                             
/************************************************************************************************************************
*    FILENAME           : horoscope_mailer.php
*    INCLUDED           : connect.inc
*    DESCRIPTION        : list horoscope request for a user.
*    CREATED BY         : lavesh
***********************************************************************************************************************/
$flag_using_php5=1;
include_once(JsConstants::$docRoot."/profile/connect.inc");
include_once(JsConstants::$docRoot."/commonFiles/flag.php");
include_once(JsConstants::$docRoot."/commonFiles/dropdowns.php");
include_once(JsConstants::$docRoot."/commonFiles/SymfonyPictureFunctions.class.php");

$dbSlave=connect_slave();
//temp
/*
$smarty=new Smarty;
*/
//temp

$mysqlObj=new Mysql;

$today=date("Y-m-d",mktime(0, 0, 0, date("m"),date("d")-1,date("Y")));

//Sharding Concept added by Lavesh Rawat on table HOROSCOPE_REQUEST
for($activeServerId=0;$activeServerId<$noOfActiveServers;$activeServerId++)
{
        //Sharding
        $myDbName=getActiveServerName($activeServerId,'slave');
        $myDb=$mysqlObj->connect("$myDbName");
        //Sharding

	$sql="SELECT DISTINCT A.PROFILEID_REQUEST_BY FROM HOROSCOPE_REQUEST A ,newjs.PROFILEID_SERVER_MAPPING B where DATE='$today' AND A.PROFILEID_REQUEST_BY = B.PROFILEID AND B.SERVERID=$activeServerId";
        $result = $mysqlObj->executeQuery($sql,$myDb);
        while($row=$mysqlObj->fetchArray($result))
	{
		@mysql_ping($dbSlave);
		@mysql_ping($myDb);

		$profileid=$row["PROFILEID_REQUEST_BY"];//receiver of the mail.
		//if(getProfileDatabaseId($profileid,$dbSlave)==$activeServerId)
		{
			$sql_mail="SELECT GENDER,EMAIL,SERVICE_MESSAGES,USERNAME,SHOW_HOROSCOPE FROM JPROFILE WHERE  activatedKey=1 and PROFILEID='$profileid' and ACTIVATED='Y'";
			$result_mail=mysql_query($sql_mail,$dbSlave) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql_mail,"ShowErrTemplate");

			if(mysql_num_rows($result_mail)>0)
			{
				$row_mail=mysql_fetch_array($result_mail);
				//Receiver need to have email subscription.
				if($row_mail['SERVICE_MESSAGES']=='S')
				{
					$email=$row_mail['EMAIL'];			
					$smarty->assign("USERNAME_REC",$row_mail['USERNAME']);

					if($row_mail['GENDER']=='M')
						$smarty->assign("GENDER_REC","M");
					else
						$smarty->assign("GENDER_REC","F");

					if($row_mail['SHOW_HOROSCOPE']=='Y')
						$show_horocope=1;

					//Receiver has submitted astro detail.
			                @mysql_ping($dbSlave);
			                @mysql_ping($myDb);

					$sql_horo = "SELECT COUNT(*) as cnt FROM newjs.ASTRO_DETAILS WHERE PROFILEID='$profileid'";
					$result_horo=mysql_query($sql_horo,$dbSlave) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql_horo,"ShowErrTemplate");
					if($row_horo=mysql_fetch_array($result_horo))
						$cnt=$row_horo["cnt"];
					$i=0;

					if(!$show_horocope || !$cnt)
					{
						$sql_req="SELECT PROFILEID FROM HOROSCOPE_REQUEST WHERE PROFILEID_REQUEST_BY='$profileid' AND DATE='$today'";
						$result_req = $mysqlObj->executeQuery($sql_req,$myDb);
																	     
						while($row_req=$mysqlObj->fetchArray($result_req))
						{
							$user=$row_req['PROFILEID'];

							//Sharding On Contacts done by Lavesh Rawat
							$sql_contact="SELECT TYPE FROM newjs.CONTACTS WHERE SENDER='$user' AND RECEIVER=$profileid";
							$result_contact=mysql_query($sql_contact,$myDb) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql1,"ShowErrTemplate");
							$myrow_contact=mysql_fetch_array($result_contact);
																	     
							if(!in_array($myrow_contact['TYPE'],array('D','C')))
							{	
								$sql_contact="SELECT TYPE FROM newjs.CONTACTS WHERE SENDER='$profileid' AND RECEIVER='$user'";
								$result_contact=mysql_query($sql_contact,$myDb) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql1,"ShowErrTemplate");
								$myrow_contact=mysql_fetch_array($result_contact);
																	     
								if(!in_array($myrow_contact['TYPE'],array('D','C')))
								{
								       $sql1="SELECT USERNAME,AGE,HEIGHT,RELIGION,CASTE,SUBCASTE,OCCUPATION,COUNTRY_RES,CITY_RES,HAVEPHOTO,YOURINFO,FAMILYINFO,SPOUSE,SCREENING,PRIVACY,PHOTO_DISPLAY,INCOME,EDU_LEVEL_NEW,ACTIVATED,NAKSHATRA,MTONGUE FROM newjs.JPROFILE WHERE  activatedKey=1 and PROFILEID='$user'";
									$result1=mysql_query($sql1,$dbSlave) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql1,"ShowErrTemplate");
									$myrow1= mysql_fetch_array($result1);

									if($myrow1["ACTIVATED"]=='Y')
									{
									        $loopDb=connect_slave();
										$arr[$i]['photochecksum']=md5($user + 5)."i".($user + 5);
										$arr[$i]['profilechecksum']=md5($user)."i".$user;
										$arr[$i]['username']=$myrow1['USERNAME'];
										$arr[$i]['yourinfo']=$myrow1["YOURINFO"];
										$arr[$i]['familyinfo']=$myrow1["FAMILYINFO"];
										$arr[$i]['spouseinfo']=$myrow1["SPOUSE"];
										$arr[$i]['screening']=$myrow1["SCREENING"];
										if($myrow1["RELIGION"] && $myrow1["RELIGION"]!=8)
											$arr[$i]['religion']=$RELIGIONS[$myrow1['RELIGION']];
										$arr[$i]['age']=$myrow1['AGE'];
										$height=label_select("HEIGHT",$myrow1['HEIGHT']);
										$arr[$i]['height']=$height[0];
										$caste=label_select("CASTE",$myrow1['CASTE']);
										$arr[$i]['caste']=$caste[0];

										if(isFlagSet("SUBCASTE",$arr[$i]['screening']))
											$arr[$i]['subcaste']=trim($myrow1["SUBCASTE"]);
										else
											$arr[$i]['subcaste']="";

										$nakshatra=$myrow1['NAKSHATRA'];
										if($nakshatra && $nakshatra!="i don't know")
											$arr[$i]['nakshatra']=$nakshatra;
										else
											$arr[$i]['nakshatra']='';					                                
										$mtongue1 = label_select("MTONGUE",$myrow1['MTONGUE']);
										$arr[$i]['mtongue']=$mtongue1[0];
										$edu_leveln=label_select("EDUCATION_LEVEL_NEW",$myrow1['EDU_LEVEL_NEW']);
										$arr[$i]['edu_level']=$edu_leveln[0];
										$occupation=label_select("OCCUPATION",$myrow1['OCCUPATION']);
										$arr[$i]['occupation']=$occupation[0];

										if($myrow1["CITY_RES"]!="")
										{
											$city_res1=$myrow1["CITY_RES"];
																	     
											if($myrow1["COUNTRY_RES"]=="51")
												$residence=$CITY_INDIA_DROP["$city_res1"];
											else
												$residence=$CITY_USA_DROP["$city_res1"];
										}
										else
										{
											$country1=$myrow1["COUNTRY_RES"];
											$residence=$COUNTRY_DROP["$country1"];	
										}
										$arr[$i]['city_res']=$residence;

										$arr[$i]["PROFILECHECKSUM"]=md5($user) . "i" . $user;
										$havephoto=$myrow1['HAVEPHOTO'];
										if($havephoto=='Y')
										{                              
											$photo_display=$myrow1['PHOTO_DISPLAY'];
											if($photo_display=='H')
												$arr[$i]['havephoto']="H";
											else
											{
												//Symfony Photo Modification
                                                                                		$profilePicObjs = SymfonyPictureFunctions::getProfilePicObjs($user);
												$profilePicObj = $profilePicObjs[$user];
                                                                                		if ($profilePicObj)
                                                                                		{
                                                                                        		$arr[$i]["ThumbnailUrl"]=$profilePicObj->getThumbailUrl();
                                                                                		}
                                                                                		else
                                                                                		{
                                                                                        		$arr[$i]["ThumbnailUrl"]=null;
                                                                                		}
												//Symfony Photo Modification

												//$photochecksum_new=intval(intval($user)/1000) . "/" . md5($user+5);
												$photochecksum =md5($user+5)."i".md5($user+5);			
												$arr[$i]["PHOTOCHECKSUM"]=$photochecksum;
												$arr[$i]['havephoto']="Y";
											}
										}
										unset($havephoto);
										unset($photo_display);

										if(!isFlagSet("YOURINFO",$arr[$i]['screening']))
											$arr[$i]['yourinfo']="";
										if(!isFlagSet("FAMILYINFO",$arr[$i]['screening']))
											$arr[$i]['familyinfo']="";
										if(!isFlagSet("SPOUSE",$arr[$i]['screening']))
											$arr[$i]['spouseinfo']="";
										if($arr[$i]['spouseinfo']!="")
											$arr[$i]['spouseinfo']="Looking for: " . $arr[$i]['spouseinfo'];
										$arr[$i]['yourinfo']=substr($arr[$i]['yourinfo'] . " " . $arr[$i]['familyinfo'] . " " . $arr[$i]['spouseinfo'],0,300);
										$i++;
									}
								}
							}
						}
					}
					$count=$i;

					if($count>0)
					{
						if(!$show_horocope && !$cnt)
							$smarty->assign("show_horoscope",'N');
			
						@mysql_ping($myDb);
						$sql_horo="SELECT COUNT(*) AS cnt FROM HOROSCOPE_REQUEST WHERE PROFILEID_REQUEST_BY='$profileid'";
						$result_horo = $mysqlObj->executeQuery($sql_horo,$myDb);
						if($row_horo=$mysqlObj->fetchArray($result_horo))
							$cnt=$row_horo["cnt"];

						$smarty->assign("OTHER_REQUEST",$cnt-$count);

						$smarty->assign("COUNT",$count);
						$smarty->assign("DATA",$arr);
																	     
						$msg=$smarty->fetch("horoscope_mailer.htm");

						if($count==1)
							$subject="A JS Member has asked for your horoscope";
						else
							$subject=$count." JS Members have asked for your horoscope";
						send_email($email,$msg,$subject,"info@jeevansathi.com");
					}
					unset($count);
					unset($arr);
				}
			}
		}
	}
}

?>
