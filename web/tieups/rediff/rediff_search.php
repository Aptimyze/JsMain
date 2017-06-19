<?php
/***************************************************************************************************************
* FILE NAME     : search.php
* DESCRIPTION   : Generates an XML file according to a given query string.
* CREATION DATE : 6 August, 2005
* CREATED BY    : Shakti Srivastava
* Copyright  2005, InfoEdge India Pvt. Ltd.
*****************************************************************************************************************/
	include "../../profile/connect.inc";
	include "rediff_search.inc";
	include("rediff_array.php");
		
	$PAGELEN=10;

//	$lang=array(2,8,9,11,14,15,18,26);

	$check_rel=array(	"Hindu",
				"Muslim",
				"Christian",
				"Jain",
				"Sikh",
				"Buddhist",
				"Parsi",
				"Jewish");

	$check_lang=array(	"Hindi",
				"Telugu",
				"Tamil",
				"Marathi",
				"Malayalam",
				"Gujarati",
				"Bengali",
				"Punjabi",
				"Kannada",
				"Urdu",
				"English",
				"Oriya",
				"Konkani",
				"Marwari",
				"Sindhi",
				"Assamese",
				"North Eastern State Languages");

        $ip=FetchClientIP();
	$iserror=0;
	
	if($Gender=="Male"||$Gender=="male")
	{
		$Gender='M';
	}
	else if($Gender=="Female"||$Gender=="female")
	{
		$Gender='F';
	}
	else
	{
		$iserror++;
	}
	
	if(!in_array($Religion,$check_rel) && $Religion!='')
	{
		$iserror++;
	}

	if(!in_array($Language,$check_lang) && $Language!='')
	{
		$iserror++;
	}

	$qs=$_SERVER['REQUEST_URI'];


	$db=connect_db();

	$sql_log="INSERT INTO MIS.REDIFF_SEARCH VALUES('','$lage','$hage','$Gender','$Religion','$Language',now(),'$ip','$qs')";
	mysql_query_decide($sql_log) or logError("Error while logging search query",$sql_log);
	
	//mysql_close($db);
	$db=connect_slave();
	
	if($Religion!='')
	{
		$sql="SELECT VALUE FROM newjs.CASTE WHERE LABEL='$Religion'";
		$res=mysql_query_decide($sql) or logError("Error while selecting data from newjs.RELIGION",$sql);
		$row=mysql_fetch_array($res);
		$rel=$row['VALUE'];
	}

	if($Language!='')
	{
		$lan=$REV_LANG[$Language];
	}
	
	if(count($lan)>=1)
		$lan=implode(",",$lan);
	else
		$lan="";
	

	$sql="SELECT PROFILEID FROM ";

	if($Gender=='M')
	{
		$sql.=" newjs.SEARCH_MALE WHERE";
	}
	else if($Gender=='F')
	{
		$sql.=" newjs.SEARCH_FEMALE WHERE";
	}

	if($lage!='' && $hage!='')
	{
		$sql.=" AGE BETWEEN $lage AND $hage AND";
	}
	else if($lage=='' && $hage!='')
	{
		$sql.=" AGE < $hage AND";
	}
	else if($lage!='' && $hage=='')
	{
		$sql.=" AGE > $lage AND";
	}

	if($rel!='')
	{
		$sql.=" CASTE=$rel AND";
	}

	if($lan!='')
	{
		$sql.=" MTONGUE IN (".$lan.") AND";
	}

	$sql=rtrim($sql,"AND");

/*************************************************************************************************************************
CHANGE DATE	: 30 AUGUST, 2005
CHANGED BY	: SHAKTI SRIVASTAVA
REASON		: Two new parameters were requested by people at Rediff so that the results obtained can be divided into
		: parts. These are 
				Start		:Start parameter for start of result.
				noOfResults	:for number of results to be returned starting from Start parameter. 
**************************************************************************************************************************/

//	if(!$Start || $Start=='' || $Start>=10)			//The 3rd condition is only while testing
	if(!$Start || $Start=='')
	{
		$strt=0;
	}
	else
	{
		$strt=$Start;
	}

/*
	if($noOfResults<=10)
	{
		$end=$noOfResults-$strt;
	}
	else
	{
		$end=10-$strt;
	}
*/

//Added By Shakti for removing the default limit of 10 results
	if($noOfResults)
	{
		if($noOfResults>1000)
		{
			die("noOfResults cannot be greater than 1000");
		}
		else
		{
			$end=$noOfResults;
		}
	}
	else
	{
		$end=10;
	}
//End of addition

	if($end<0)
	{
		$iserror++;
	}

	$sql.=" LIMIT ".$strt.",".$end;

	if($iserror==0)					//if the query is not well formed
	{
		if($res=mysql_query_decide($sql))		//in order to avoid "not a valid result resource"
		{
			header('Content-type: text/xml');
			if(mysql_num_rows($res)>0)	//if there is no result satisfying the criterea
			{
				$results=displayresult($res,0,"rediff_search.php","","",1,"","","");
					
				$Ret = "<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>\n";
				$Ret .= "<rss version=\"2.0\">\n";
				$Ret.="<ProfileList>\n";	
				$Ret.="\t<Source>Jeevansathi.com</Source>\n";	
				$Ret.="\t<Date>".date('Y-m-d')."</Date>\n";	
				$Ret.="\t<BatchId></BatchId>\n";	
				$Ret.="\t<ProfileCount>".count($results)."</ProfileCount>\n";
				echo $Ret;
				unset($Ret);

				$arr_search=array('&','<','>',"'",'"');
				$arr_repl=array('&amp;','&lt;','&gt;','&apos;','&quot;');

				for($a=0;$a<count($results);$a++)
				{
					$Ret="\t<Profile>\n";
					$Ret.="\t\t<identifier>".$results[$a]['PROFILEID']."</identifier>\n";
					$Ret.="\t\t<Fname>".str_replace($arr_search,$arr_repl,$results[$a]['USERNAME'])."</Fname>\n";
//					$Ret.="\t\t<Lname></Lname>\n";
					if($results[$a]['HAVEPHOTO']=='Y')
					{
						$Ret.="\t\t<PhotoUrl>".str_replace($arr_search,$arr_repl,"http://ser4.jeevansathi.com/profile/photo_serve.php?profileid=".$results[$a]['PHOTOCHECKSUM']."&photo=THUMBNAIL&source=afflrediff")."</PhotoUrl>\n";
					}
					elseif($results[$a]['HAVEPHOTO']=='P')
					{
						$Ret.="\t\t<PhotoUrl>http://www.jeevansathi.com/P/I/photo_protected.jpg</PhotoUrl>\n";
					}
					else
                                        {
                                                $Ret.="\t\t<PhotoUrl>http://www.jeevansathi.com/P/I/no_photo.jpg</PhotoUrl>\n";
                                        }
					if($results[$a]['AGE'])
						$Ret.="\t\t<Age>".$results[$a]['AGE']."</Age>\n";

					if($results[$a]['SEX'])
						$Ret.="\t\t<Sex>".$GENDER[$results[$a]['SEX']]."</Sex>\n";

					if($results[$a]['MSTATUS'])
						$Ret.="\t\t<MaritalStatus>".$MSTATUS[$results[$a]['MSTATUS']]."</MaritalStatus>\n";

					if($results[$a]['HEIGHT'])
						$Ret.="\t\t<Hieght>".$results[$a]['HEIGHT']."</Hieght>\n";

					if($results[$a]['BODY'])
						$Ret.="\t\t<BodyType>".$BODYTYPE[$results[$a]['BODY']]."</BodyType>\n";

					if($results[$a]['COMPLEXION'])
						$Ret.="\t\t<Complexion>".$COMPLEXION[$results[$a]['COMPLEXION']]."</Complexion>\n";

					if($results[$a]['RELIGION'])
						$Ret.="\t\t<Religion>".str_replace($arr_search,$arr_repl,$results[$a]['RELIGION'])."</Religion>\n";

					if($results[$a]['MTONGUE'])
						$Ret.="\t\t<Language>".$LANGUAGE[$results[$a]['MTONGUE']]."</Language>\n";

					if($results[$a]['DEGREE'])
						$Ret.="\t\t<Education>".str_replace($arr_search,$arr_repl,$results[$a]['DEGREE'])."</Education>\n";
		
					if($results[$a]['OCCUPATION'])
						$Ret.="\t\t<Occupation>".str_replace($arr_search,$arr_relp,$results[$a]['OCCUPATION'])."</Occupation>\n";

					if($results[$a]['INCOME'])
						$Ret.="\t\t<AnnualIncome>".str_replace($arr_search,$arr_repl,$results[$a]['INCOME'])."</AnnualIncome>\n";

					if($results[$a]['RESIDENCE'])
						$Ret.="\t\t<Residence>".str_replace($arr_search,$arr_repl,$results[$a]['RESIDENCE'])."</Residence>\n";

					if($results[$a]['YOURINFO'])
						$Ret.="\t\t<OpenDesc><![CDATA[".str_replace($arr_search,$arr_repl,$results[$a]['YOURINFO'])."]]></OpenDesc>\n";

					if($results[$a]['PROFILEURLCHECKSUM'])
						$Ret.="\t\t<ProfileUrl>http://www.jeevansathi.com/profile/viewprofile.php?source=afflrediff&amp;profileurlchecksum=".$results[$a]['PROFILEURLCHECKSUM']."</ProfileUrl>\n";

					if($results[$a]['ENTRY_DT'])
						$Ret.="\t\t<CreationDate>".$results[$a]['ENTRY_DT']."</CreationDate>\n";

					if($results[$a]['MOD_DT'])
						$Ret.="\t\t<UpdationDate>".$results[$a]['MOD_DT']."</UpdationDate>\n";

					if($results[$a]['LL_DT'])
						$Ret.="\t\t<LastLoginDate>".$results[$a]['LL_DT']."</LastLoginDate>\n";

					if($results[$a]['RELATION'])
						$Ret.="\t\t<ProfileCreatedBy>".$RELATIONSHIP[$results[$a]['RELATION']]."</ProfileCreatedBy>\n";
					$Ret.="\t</Profile>\n";
					echo $Ret;
					unset($Ret);
				}
				$Ret="</ProfileList>\n";
				$Ret.= "</rss>";

				echo $Ret;
			}
			else
			{
				echo "No Results were found";
			}
		}
		else
		{
			logError("Error while fetching data",$sql);
		}
	}
	else
	{
		echo "The given Query String contains errors";
	}
?>
