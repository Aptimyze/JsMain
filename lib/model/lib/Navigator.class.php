<?php
/**
* Navigation class
* Creates dynamic text out of constant declared in messages class
 * @package   jeevansathi
 * @subpackage   default
* 
Below is the demonstration on how to use this class
 * <code>
 * <br />
  * 
  * <Br/>
  * return true is exist<BR/>
  * throws JSException is const doesn't exist
 * <br />
 * </code>
 * </p>
 * PHP versions 4 and 5

 * @package   jeevansathi
 * @subpackage   default
 * @author    Nikhil Dhiman <nikhil.dhiman@jeevansathi.com>
 * @copyright 2012 Nikhil Dhiman
 * @version   SVN: 9619
 * @link      http://devjs.infoedge.com/mediawiki/index.php/FTO
  */
class Navigator{
	private $memcacheObj;
	public function __construct()
	{
		 $this->memcacheObj = JsMemcache::getInstance();
	}
function get_navigation_link($navi_id)
{
	if($navi_id)
	{
		
		
			return $this->memcacheObj->get($navi_id);
		
	}
}
function navigation_inserted_id($navigation)
{
	$key=md5($navigation);
	$this->memcacheObj->set($key,$navigation,0,3600);
	return $key;	
		
	

}
function develop_url()
{
	foreach($_GET as $key=>$val)
                {
                        if( $key!='overwrite' && $key!='MESSAGE' && $key!='CALL_ME' && $key!='ID_CHECKED' && $key!='google_kwd' && $key!='after_login_call' && $key!='ajax_error' && $key!='from_viewprofile' && $key!='nextViewSim' && $key!='draft_id' && $key!='type_of_con' && $key!="MESSAGE" && $key!='draft_name' && $key!='from_search' && $key!='AllPhotos')
                                $link.=$key."__".$val."@";
                        if($key=='nextViewSim')
                                $link.='nextViewSim1__1@';
                }
	return $link;
}
function navigation($type,$param,$username='',$symfony='')
{
        global $smarty;
	global $cc_navigator;
        $navi_id=$_GET["NAVIGATOR"];
	
	if(!$_GET['NAVIGATOR'] && ($type=="CVS"|| $type=='VS' || $type == 'CVS_NEW')  && $param=="")
		$param=$this->develop_url();

	$pass_navig=0;
	//Checking if NAVIGATOR value contains combination of navigation id and table part id
	if($_GET['NAVIGATOR'])
	{
		list($naviid,$part)=explode("_",$_GET["NAVIGATOR"]);
		if(intval($part) && intval($naviid) && $part<=10)
			$pass_navig=1;
	}

	//Generating navi_id if NAVIGATOR is string , usually string value comes from mailer or my jeevansathi page
	if(strlen($_GET["NAVIGATOR"])>0 && $pass_navig==0 && strlen($_GET['NAVIGATOR'])!=32)
	{
		$navi_id=$this->navigation_inserted_id($_GET["NAVIGATOR"]);
		$_GET["NAVIGATOR"]=$navi_id;
	}
	if($navi_id)
	{
		$navigator=$this->get_navigation_link($navi_id);
	}
	if(!$navigator)
		$navigator="";

        //If script is called by open_tab layer from viewprofile.
        if(strstr($param,"SIM_USERNAME"))
                $coming_from_dp=1;
	
		if($navigator=="" && $param=="")
			return 0;
		$arrAllowedSearchPage_NavType = array('SR','MA','KM');	
        //$navigator="SR:searchid_6402/Back to Search Results;VS:PQ6500(686500)/View Similar results to PQ6500(686500);";
        $max_allowed=3;
        //Type is SR , DP, VS, CVS
        if(!in_array($type,$arrAllowedSearchPage_NavType) && $param!="" && $navigator=="")
        {
		if($type=="ACC")
			$to_show="Accepted Members";
		else
		if($type=="CVSM")
			$to_show="Contact Viewers Mobile";
		else
		if($type=="ACC_M")
			$to_show="People I Accepted";
		else
		if($type=="MES_A")
			$to_show="All Messages";
		else
		if($type=="ACC_R")
			$to_show="People Accepted Me";
		else
		if($type=="PHO_R")
			$to_show="Photo Requests Received";
		else
		if($type=="PHO_M")
			$to_show="Photo Requests Sent";
		else
		if($type=="FAV")
                        $to_show="Shortlisted Members";
                else
		if($type=="PHO")
                        $to_show="Photo Requests";
                else
		if($type=="HOR")
                        $to_show="Horoscope Requests";
                else
        if($type=="HOR_R")
                        $to_show="Horoscope Requests Received";
                else
        if($type=="HOR_M")
                        $to_show="Horoscope Requests Sent";
                else
		if($type=="CHAT")
                        $to_show="Chat Requests";
                else
		if($type=="IGN")
                        $to_show="Blocked Members";
                else
		if($type=="DEC_R")
                        $to_show="Members not interested in me";
                else
		if($type=="DEC_S")
                        $to_show="Members I was not interested in";
                else
		if($type=="MAT")
                        $to_show="Match Alerts";
                else
		if($type=="VIS")
                        $to_show="Profile Visitors";
                else
		if($type=="EOI")
			$to_show="Members awaiting my response";
                else
                if($type=="REM")
                        $to_show="Members yet to respond to me";
		else
		if($type=="ARC")
                        $to_show="Archived Interests";
                else
		if($type=="MES")
                        $to_show="Messages";
                else
		if($type=="FIL")
                        $to_show="Filtered members/Spam";
                else
		if($type=="INB")
                        $to_show="All New Items";
		else
		if($type=="CALL")
			$to_show="Call history";
		else
		if($type=="IC")
			$to_show="Members to be called";
		else
		if($type=="VC")
			$to_show="Viewed Contacts";
		else
		if($type=="VCB")
			$to_show="People who viewed my contacts";
		elseif($type=="PCV")
			$to_show="Phonebook";
		elseif($type=='CVS_NEW')
		        $to_show="Profile of $username";
        else if($type=='JVS')//Jsms View Similar
		        $to_show="Profile similar to $username";
		else
		        $to_show="Profiles similar to $username";
		
                $link=$param;
                $actual=$link."/".$to_show;
                $link_developed="$type:$actual;;";
        }
        elseif(in_array($type,$arrAllowedSearchPage_NavType) && $param!="")
        {
            if($type=='SR')
			{
				$to_show="Search Results";
			}
			else if($type=='MA')
			{
				$to_show="Match Alerts";
			}
			else if($type=='KM')
			{
				$to_show="Kundli Alerts";
			}
		$param1=explode(":",$param);
		$param=$param1[0];
                $link="searchId__$param";
                $actual=$link."/".$to_show;
                $link_developed="SR:$actual;;";
		$sr_navig_id=$this->navigation_inserted_id($link_developed);
		if($param1[1])
			$actual=$link."@currentPage__".$param1[1]."@NAVIGATOR__$sr_navig_id/$to_show";
		else
			$actual=$link."@currentPage__1@NAVIGATOR__$sr_navig_id/$to_show";
/*
		if($param1[1])
			$actual=$link."@offset__".$param1[1]."@NAVIGATOR__$sr_navig_id@j__1/$to_show";
		else
			$actual=$link."@NAVIGATOR__$sr_navig_id@j__1/$to_show";
*/
                $link_developed="SR:$actual;;";
		
		
                //return $link_developed;
        }
	elseif($navigator)
        {
                $link_arr=explode(";",$navigator);
                $total=count($link_arr);
                $first=$link_arr[0];
                $second=$link_arr[1];
                $third=$link_arr[2];
                $link='';
                if(isset($_GET['overwrite']))
                        $not_allow=1;
		$link=$this->develop_url();

                //Overwrite the link that to be developed ..
                if($coming_from_dp==1)
                        $link=$param;

                if($link)
                {
                        $link=substr($link,0,strlen($link)-1);
                }

                $actual='';
                $link_developed='';
                if($type=='SR')
                {
                        $to_show="Search Results";
                }
                if($type=='MA')
				{
					$to_show="Match Alerts";
				}
				if($type=='KM')
				{
					$to_show="Kundli Alerts";
				}
                if($type=='DP')
                {
                        $to_show="Detailed profile of $username";
                }
                if($type=='DP_NEW')
                {
                        $to_show="Profile of $username";
                }
		if($type=='VS')
                {
                        $to_show="Profiles similar to $username";
                }
                if($type=='CVS_NEW')
                {
                        $to_show="Profile of $username";
                }
                if($type=='PCV')
                {
                        $to_show="Phonebook";
                }
                if($type=='CVS')
                {
                        $to_show="Profiles similar to $username";
                }
		if($type=="ACC")
		{
                        $to_show="Accepted Members";
		}
                if($type=="FAV")
		{
                        $to_show="Shortlisted Members";
		}
                if($type=="PHO")
		{
                        $to_show="Photo Requests";
		}
                if($type=="HOR")
		{
                        $to_show="Horoscope Requests";
		}
                if($type=="CHAT")
		{
                        $to_show="Chat Requests";
		}
                if($type=="IGN")
		{
                        $to_show="Blocked Members";
		}
                if($type=="DEC_R")
		{
                        $to_show="Members not interested in me";
		}
                if($type=="DEC_S")
		{
                        $to_show="Members I was not interested in";
		}
                if($type=="MAT")
		{
                        $to_show="Match Alerts";
		}
                if($type=="VIS")
		{
                        $to_show="Profile Visitors";
		}
                if($type=="EOI")
		{
                        $to_show="Members awaiting my response";
		}
                if($type=="REM")
		{
                        $to_show="Members yet to respond to me";
		}
                if($type=="ARC")
		{
                        $to_show="Archived Interests";
		}
                if($type=="MES")
		{
                        $to_show="Messages";
		}
                if($type=="FIL")
		{
                        $to_show="Filtered members/Spam";
		}
                if($type=="INB")
		{
                        $to_show="All New Items";
		}
		if($type=='CALL')
		{
			$to_show="Call history";
		}
		if($type=='IC')
		{
			$to_show="Members to be called";
		}
                if($type=='VC')
                {
                        $to_show="Viewed Contacts";
                }
                if($type=='VCB')
                {
                        $to_show="People who viewed my contacts";
                }
        if($type=="MVS")
        {
			$to_show="Profiles similar to $username";
		}
                if($type=="MMH")//Mobile Message Handler
                {
                        $to_show="Mobile message handler";
                }
                if($type=="RVS")//Mobile Message Handler
                {
                        $to_show="Mobile send reminder";
                }
                if($type=="JVS")//Jsms View Similar
                {
                        $to_show="Jsms View Similar";
                }
                $actual=$link."/".$to_show;
                $link_developed="$type:$actual";
                if($_GET['overwrite'])
                {
                        if($third!="")
                                $third=$link_developed;
                        elseif($second!="")
                                $second=$link_developed;
                        elseif($first!="")
                                $first=$link_developed;
                }
                else
                {
                        if($second=="")
                        {
                                $second=$link_developed;
                                $total--;
                        }
                        elseif($third=="")
                        {

				if(substr($second,0,2)=='DP' && $type=='DP_NEW')
                                {
                                        $third=$link_developed;

                                        $total--;
                                }
				elseif(substr($second,0,2)=='DP' && $type=='DP')
                                {
                                        $second=$link_developed;

                                        $total--;
                                }
                                else
                                {
                                        $total--;
                                        $third=$link_developed;
                                }
                        }
                        
                        //JSMS Ecp Page, Coming from Level 2 Ecp page to DP
                        if ($type=="DP"                     && 
                            stripos($third,'DP')!==false    && 
                            stripos($second,'JVS')!==false)
                        {
                            $third = $link_developed;
                        }
                        else if($type=="JVS"                && 
                            stripos($third,'JVS')!==false   && 
                            stripos($second,'DP')!==false)
                        {//Case in which hard refresh happening on JSMS ECP Page
                            $third = $link_developed;
                        }
                        else if($total>=3)
                        {
                                $second=$third;
                                $third=$link_developed;
                          	$show_all=1;
                        }
                        //$link_developed="$first;$second;$third";
                }
                $link_developed="$first;$second;$third";
        }
//echo $link_developed;
	
	$ins_row=$this->navigation_inserted_id($link_developed);
	$cc_navigator="NAVIGATOR=$ins_row";	
	$this->NAVIGATOR=$cc_navigator;
	$this->NAVIGATOR_LINK=$ins_row;
	$showNavigator=$this->show_navigation($link_developed,$show_all,$ins_row);
	if($symfony==2)
		return $showNavigator;
	if($symfony)
		return $cc_navigator;
        return $link_developed;
}
function show_navigation($navigator,$show_all,$navig_id)
{	
	global $SITE_URL;
	$isMobile = MobileCommon::isMobile();
	$type_arr=explode(";",$navigator);
	$TYPE['SR']="/search/perform";
	$TYPE['VS']="/profile/simprofile_search.php";
	$TYPE['DP']="/profile/viewprofile.php";
	$TYPE['DP_NEW']="/profile/viewprofile.php";
	$TYPE["CVS_NEW"]="/profile/viewprofile.php";
        if($isPc)
        {
                $TYPE["PCV"]="/inbox/index";
                $TYPE["MES_A"]="/inbox/index";
                $TYPE["ACC_R"]="/inbox/index";
                $TYPE["ACC_M"] = "/inbox/index";
                $TYPE["PHO_R"]="/inbox/index";
                $TYPE["PHO_M"]="/inbox/index";
                $TYPE["HOR_R"]="/inbox/index";
                $TYPE["IGN"]="/inbox/index";
                $TYPE["DEC_R"]="/inbox/index";
                $TYPE["DEC_S"]="/inbox/index";
                $TYPE["EOI"]= "/inbox/index";
                $TYPE["REM"]= "/inbox/index";
                $TYPE["MES"]="/inbox/index";
                $TYPE["FIL"]="/inbox/index";
                $TYPE["VCB"]="/inbox/index";
                $TYPE["HOR_M"]="/inbox/index";
        }
	else
        {
                $TYPE["PCV"]="/profile/contacts_made_received.php";
                $TYPE["ACC_R"]="/profile/contacts_made_received.php";
                $TYPE["ACC_M"]="/profile/contacts_made_received.php";
                $TYPE["PHO_R"]="/profile/contacts_made_received.php";
                $TYPE["PHO_M"]="/profile/contacts_made_received.php";
                $TYPE["HOR_R"]="/profile/contacts_made_received.php";
                $TYPE["IGN"]="/profile/contacts_made_received.php";
                $TYPE["DEC_R"]="/profile/contacts_made_received.php";
                $TYPE["DEC_S"]="/profile/contacts_made_received.php";
                $TYPE["EOI"]="/profile/contacts_made_received.php";
                $TYPE["REM"]="/profile/contacts_made_received.php";
                $TYPE["MES"]="/profile/contacts_made_received.php";
                $TYPE["FIL"]="/profile/contacts_made_received.php";
                $TYPE["VCB"]="/profile/contacts_made_received.php";
		$TYPE["HOR_M"]="/profile/contacts_made_received.php";
        }
	$TYPE["CVS"]="search/viewSimilarProfile";
	$TYPE["ACC"]="/profile/contacts_made_received.php";
	$TYPE["FAV"]="/profile/contacts_made_received.php";
	$TYPE["PHO"]="/profile/contacts_made_received.php";
	$TYPE["HOR"]="/profile/contacts_made_received.php";
	$TYPE["CHAT"]="/profile/contacts_made_received.php";
	$TYPE["CVSM"]="/profile/contacts_made_received.php";
	$TYPE["MAT"]="/profile/contacts_made_received.php";
	$TYPE["KUN"]="/profile/contacts_made_received.php";
	$TYPE["VIS"]="/profile/contacts_made_received.php";
	$TYPE['DP_NEW']="/profile/viewprofile.php";
	$TYPE["CVS_NEW"]="/profile/viewprofile.php";
	$TYPE["CVS"]="/profile/view_similar_profile.php";
	$TYPE["FAV"]="/profile/contacts_made_received.php";
	$TYPE["PHO"]="/profile/contacts_made_received.php";
	$TYPE["HOR"]="/profile/contacts_made_received.php";
	$TYPE["CHAT"]="/profile/contacts_made_received.php";
	$TYPE["IGN"]="/profile/contacts_made_received.php";
	$TYPE["DEC_R"]="/profile/contacts_made_received.php";
	$TYPE["ARC"]="/profile/contacts_made_received.php";
	$TYPE["INB"]="/profile/contacts_made_received.php";
	$TYPE["CALL"]="/profile/contacts_made_received.php";
	$TYPE["IC"]="/profile/contacts_made_received.php";
	$TYPE["VC"]="/profile/contacts_made_received.php";
	$TYPE["FP"]="/search/perform";
	$TYPE["MVS"] = "/contacts/PostEOI";//Mobile View Similar
    $TYPE["MMH"] = "/contacts/MessageHandle";//Mobile Message Handler
    $TYPE["RVS"] = "/contacts/PostSendReminder";//Mobile Send Reminder View Similar
    $TYPE["MA"] = "/search/matchalerts";//Match Alerts
    $TYPE["KM"] = "/search/kundlialerts";//Kundli Alerts
    $TYPE["JVS"] = "/search/MobSimilarProfiles";//Jsms View Similar
    
	$minus=0;
	if($type_arr[1]=="")
		$minus=+2;
	elseif($type_arr[2]=="")
		$minus=+1;
	if($show_all)
		$upto=count($type_arr)-$minus;
	else
		$upto=count($type_arr)-$minus;

	$last=$upto-1;
	for($i=0;$i<$upto;$i++)
	{
		if($type_arr[$i]=="")
			break;
		$sep_arr=explode(":",$type_arr[$i]);
		$type=$sep_arr[0];
		$type_text=$sep_arr[1];
		$sep_arr=explode("/",$type_text);
		$link=$sep_arr[0];
		$text=$sep_arr[1];
		$posted_arr=explode("@",$link);
		$j=0;
		for($j=0;$j<count($posted_arr);$j++)
		{
			$key_val=explode("__",$posted_arr[$j]);
			if($j==0)
				$params="?$key_val[0]=$key_val[1]";
			else
				$params.="&$key_val[0]=$key_val[1]";
		}
		if($i==$last)
		{
			//$navigation.=" <span class=b>&gt; $text</span> ";
		}
		elseif($i==0)
		{
			$data[$i][HREF]="$TYPE[$type]$params&overwrite=1";
			$data[$i][TEXT]=$text;
			
			$navigation="<a  class=\"blink \" href=\"$TYPE[$type]$params&overwrite=1\">Back to $text</a>";
			
		}
		elseif(!$isMobile)
		{
			if($i==1)
			{
				$data[$i][HREF]="$TYPE[$type]$params&overwrite=1";
				$data[$i][TEXT]=$text;
				$navigation.=" | <a href=\"$TYPE[$type]$params\" class=\"blink \">Back to $text</a>";
			}
			else
			{
				$data[$i][HREF]="$TYPE[$type]$params&overwrite=1";
				$data[$i][TEXT]=$text;
				$navigation.=" | <a href=\"$TYPE[$type]$params\"  class=\"blink \">Back to $text</a>";
			}		
		}
		
		if($isMobile  && $_GET['fmConfirm'] && $i==1 && !MobileCommon::isNewMobileSite())
		{
			$data[$i][HREF]="$TYPE[$type]$params&fmBack=1";
			$data[$i][TEXT]=$text;
			$navigation="<a href=\"$TYPE[$type]$params\"  class=\"pull-right btn pre-next-btn \">Go back</a>";
		}
        //JSMS ECP View 
        if ($isMobile                               && 
            $type =='JVS'                           && 
            MobileCommon::isNewMobileSite()         && 
            stripos($type_arr[2],'DP')!==false//Current Page is Detailed Page and Previous Page is ECP/DP
        ) {
			$navigation="<a href=\"$TYPE[$type]$params\"  class=\"pull-right btn pre-next-btn \">Go back</a>";
		}

        //JSMS ECP View 
        if ($isMobile                               && 
            $type =='DP'                            && 
            MobileCommon::isNewMobileSite()         && 
            stripos($type_arr[2],'JVS')!==false//Current Page is ECP and previous page is DP
        ) {
			$navigation="<a href=\"$TYPE[$type]$params\"  class=\"pull-right btn pre-next-btn \">Go back</a>";
		}        
        
		if($i!=$last)
		{
			$this->BACK_TO_SEARCH="<a href=\"$TYPE[$type]$params\" class=\"blink rf\">&lt;&lt; Go back to $text</a>";
		}
		
	}
	if($i>1)
	{
		if(is_array($data))
		{
			if(MobileCommon::isMobile())
			{
				$iIndex = ($_GET['fmConfirm']) ? 1: 0 ;
				$links="<a href=\"".$data[$iIndex][HREF]."\"  class=\"pull-right btn pre-next-btn \">Go back</a>";
			}
			else
			{
				$links="<a href=\"".$data[0][HREF]."\"  class=\"blink \">&lt;&lt; Back</a>";
			}
		}
		$this->onlyBackBreadCrumb=$links; 
		if(strstr($_SERVER['PHP_SELF'],'view_similar_profile.php'))
			$this->BREADCRUMB="<span class='b blink'> &lt;&lt; $navigation</span>";
		else
	    {
			$navigation="<span class='b blink'>&lt;&lt; $navigation</span>";			
			$this->BREADCRUMB=$navigation;
		}

	}
    	return $navigation;
}
}
?>
