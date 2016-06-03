<?php
/***************************************************************************************************************
* FILE NAME     : search.php
* DESCRIPTION   : Generates an XML file according to a given query string.
* CREATION DATE : 6 August, 2005
* CREATED BY    : Shakti Srivastava
* Copyright  2005, InfoEdge India Pvt. Ltd.
*****************************************************************************************************************/
	include "../../profile/connect.inc";
	include "google_search.inc";
	include("google_array.php");
	ini_set("max_execution_time","0");
	ini_set("memory_limit","100M");

	$test=0;
		
	$db=connect_slave();

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

					
	$Ret = "<?xml version=\"1.0\"?>\n";
	$Ret.= "<rss version=\"2.0\">\n";
	$Ret.="<channel xmlns:g=\"http://base.google.com/ns/1.0\" xmlns:c=\"http://base.google.com/cns/1.0\">\n";
	$Ret.="<title>Matrimonial, Indian Matrimonials - JeevanSathi.com</title>\n";
	$Ret.="<link>http://www.jeevansathi.com</link>\n";
	$Ret.="<description>Jeevansathi Matrimonials - Indian Matrimonial - No.1 matrimonial site with all matrimonials. Add your matrimonial profile NOW! &amp; Contact Partners for FREE!</description>\n";

	echo $Ret;

	$sql="SELECT DISTINCT PROFILEID AS CNT FROM newjs.LOGIN_HISTORY WHERE LOGIN_DT BETWEEN DATE_SUB(CURDATE(), INTERVAL 1 DAY) AND CURDATE()";
	$res=mysql_query_decide($sql) or logError("Error while fetching data",$sql);
	while($row=mysql_fetch_array($res))
	{
				$results=displayresult($row['CNT'],0,"google_search.php",10,"","","","","");

				$arr_search=array('&','<','>',"'",'"');
				$arr_repl=array('&amp;','&lt;','&gt;','&apos;','&quot;');

				for($a=0;$a<count($results);$a++)
				{
					unset($Ret);
					$mtongue=label_select("newjs.MTONGUE",$results[$a]['MTONGUE']);

					$title=str_replace($arr_search,$arr_repl,$results[$a]['USERNAME']) . " - " . $results[$a]['AGE'].", ".str_replace("'",'&apos;',$results[$a]['HEIGHT']).", ".str_replace($arr_search,$arr_repl,$results[$a]['CASTE']).", " . str_replace($arr_search,$arr_repl,$results[$a]['MTONGUE']) . ", " .str_replace($arr_search,$arr_repl,$results[$a]['OCCUPATION']) . ", " . str_replace($arr_search,$arr_repl,$results[$a]['RESIDENCE']);

					$Ret.="\t<item>\n";
					$Ret.="\t\t<title>".$title."</title>\n";	
					$Ret.="\t\t<link>".str_replace($arr_search,$arr_repl,"http://www.jeevansathi.com/profile/viewprofile.php?profilechecksum=".$results[$a]['PROFILECHECKSUM']."&source=gglbaserss")."</link>\n";

					$x=substr(str_replace($arr_search,$arr_repl,$results[$a]['YOURINFO']),0,1000);
					$xy=strrpos($x," ");

					$Ret.="\t\t<description>".substr($x,0,$xy)."</description>\n";

					if($results[$a]['AGE'])
						$Ret.="\t\t<g:age>".$results[$a]['AGE']."</g:age>\n";

					if($results[$a]['DEGREE'])
						$Ret.="\t\t<g:education>".str_replace($arr_search,$arr_repl,$results[$a]['DEGREE'])."</g:education>\n";

					if($mtongue[0])
						$Ret.="\t\t<g:ethnicity>".$mtongue[0]."</g:ethnicity>\n";

					if($results[$a]['CITY_BIRTH'])
						$Ret.="\t\t<g:from_location>".str_replace($arr_search,$arr_repl,$results[$a]['CITY_BIRTH'])."</g:from_location>\n";

					if($results[$a]['SEX'])
						$Ret.="\t\t<g:gender>".$results[$a]['SEX']."</g:gender>\n";

					if($results[$a]['USERNAME'])
						$Ret.="\t\t<g:id>".str_replace($arr_search,$arr_repl,$results[$a]['USERNAME'])."</g:id>\n";

					if($results[$a]['HAVEPHOTO']=='Y')
                                        {
                                                $Ret.="\t\t<g:image_link>".str_replace($arr_search,$arr_repl,"http://ser4.jeevansathi.com/profile/photo_serve.php?profileid=".$results[$a]['PHOTOCHECKSUM']."&photo=THUMBNAIL")."</g:image_link>\n";
                                        }

					if($results[$a]['SPOUSEINFO'])
					{
						$x=substr(str_replace($arr_search,$arr_repl,$results[$a]['SPOUSEINFO']),0,1000);
						$xy=strrpos($x," ");
						$Ret.="\t\t<g:interested_in>".substr($x,0,$xy)."</g:interested_in>\n";
					}

					if($results[$a]['KEYWORDS'])
						$Ret.="\t\t<g:label>".str_replace($arr_search,$arr_repl,$results[$a]['KEYWORDS'])."</g:label>\n";

					if($results[$a]['MSTATUS'])
						$Ret.="\t\t<g:marital_status>".$MSTATUS[$results[$a]['MSTATUS']]."</g:marital_status>\n";

					if($results[$a]['OCCUPATION'])
						$Ret.="\t\t<g:occupation>".str_replace($arr_search,$arr_repl,$results[$a]['OCCUPATION'])."</g:occupation>\n";

					if($results[$a]['RELATION'])
						$Ret.="\t\t<c:Profile_Posted_By type=\"string\">".$RELATIONSHIP[$results[$a]['RELATION']]."</c:Profile_Posted_By>\n";

					if($results[$a]['LL_DT'])
						$Ret.="\t\t<c:Last_Online type=\"date\">".$results[$a]['LL_DT']."</c:Last_Online>\n";

					if($results[$a]['HEIGHT'])
						$Ret.="\t\t<c:Height type=\"string\">".$results[$a]['HEIGHT']."</c:Height>\n";

					if($results[$a]['RELIGION'])
						$Ret.="\t\t<c:Religion type=\"string\">".str_replace($arr_search,$arr_repl,$results[$a]['RELIGION'])."</c:Religion>\n";

					if($results[$a]['CASTE'])
						$Ret.="\t\t<c:Caste type=\"string\">".str_replace($arr_search,$arr_repl,$results[$a]['CASTE'])."</c:Caste>\n";
					$Ret.="\t</item>\n";


					echo $Ret;
				}
	}

	echo "</channel>\n</rss>";
	

	function check_ascii($contents)
        {
                $len=strlen($contents);
                                                                                                                            
                $str="";
                $i=0;
                while($i<$len)
                {
                        $ch=$contents{$i};
                        if((ord($ch)<127 && ord($ch)>31) || ord($ch)==9 || ord($ch)==10)
                                $str.=$contents{$i};
                        $i++;
                }
                                                                                                                            
                return $str;
        }
?>
