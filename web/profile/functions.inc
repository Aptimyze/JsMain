<?php
include_once(JsConstants::$docRoot."/classes/Jpartner.class.php");
include_once(JsConstants::$docRoot."/classes/shardingRelated.php");
include_once(JsConstants::$docRoot."/ivr/jsivrFunctions.php");
function user_details($pid,$mysqlObj="",$jpartnerObj="")      //This function is used in JSADMIN to populate details acc. to confirmation mail.
{
    global $smarty,$r2;

    $photo_exist=1;

    if($r2['HAVEPHOTO']=="N")
        $photo_exist=0;
    $smarty->assign("PHOTO_EXIST",$photo_exist);      //show the upload photo option is no photo is uploaded by user

    //Branch code
    $my_city=$r2['CITY_RES'];                                                                                                                          
    $sql="SELECT SQL_CACHE NEAR_BRANCH FROM incentive.BRANCH_CITY WHERE VALUE='$my_city'"; 
    $result=mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");

    if(mysql_num_rows($result)>0)
    {
        $myrow=mysql_fetch_array($result);
        $sql="SELECT SQL_CACHE ADDRESS,CONTACT_PERSON,PHONE,ID FROM newjs.BRANCHES WHERE VALUE='$myrow[NEAR_BRANCH]'";
    }
    else
        $sql="SELECT SQL_CACHE ADDRESS,CONTACT_PERSON,PHONE,ID FROM newjs.BRANCHES WHERE VALUE='UP25'";

    $result=mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
    $myrow=mysql_fetch_array($result);

    $smarty->assign("BRANCH_PHONE",$myrow['PHONE']);
    $smarty->assign("BRANCH_ADDRESS",$myrow['ADDRESS']);
    $smarty->assign("MAILTO","mailto:anamika.singh@jeevansathi.com");

    mysql_free_result($result);

    //branch code ends
    if(!$mysqlObj)
        $mysqlObj=new Mysql;
    if(!$jpartnerObj)
        $jpartnerObj=new Jpartner;

    $profile_percent=profile_percent($pid,'','',$mysqlObj,$jpartnerObj);
    $smarty->assign("PROFILE_PERCENT",$profile_percent);

    //code to check whether user has uploaded horoscope or not//
    $horoscope_exist=0;
    $sSQL="select PROFILEID from newjs.`ASTRO_DETAILS` where PROFILEID='$pid'";
    $hor_res=mysql_query_decide($sSQL) or die("$sql".mysql_error_js());
    if(mysql_fetch_row($hor_res))
        $horoscope_exist=1;
    $smarty->assign("HOROSCOPE",$horoscope_exist);
    /////horoscope code endes here//

    //to check partner profile is filled by the user or not //
    $partner_exist=0;

    //Sharding Concept added by Vibhor Garg on table JPARTNER
    $myDbName=getProfileDatabaseConnectionName($pid,'',$mysqlObj);
    $myDb=$mysqlObj->connect("$myDbName");
    $jpartnerObj->setPartnerDetails($pid,$myDb,$mysqlObj,"DPP");
    $dpp = $jpartnerObj->getDPP();
    if(strstr($dpp,'S')||strstr($dpp,'O'))
        $partner_exist = 1;
    //Sharding Concept added by Vibhor Garg on table JPARTNER

    $smarty->assign("PARTNER_EXIST",$partner_exist);
    //////// Partner profile checking ends here//////////////
}

function profile_percent($profileid,$editprofile="",$from_jsadmin="",$mysqlObj="",$jpartnerObj="",$topthree="")
{
    global $smarty;
    global $PROFILE_3D;	//Declared in mainmenu.php, used to check whether data required already present or not.
    global $jprofile_result;	//Declared in reduce_jprofile query.
    if(!$mysqlObj)
        $mysqlObj=new Mysql;
    if(!$jpartnerObj)
        $jpartnerObj=new Jpartner;

    $slv_db=$mysqlObj->connect("737_ro");

    for($i=0;$i<12;$i++)
    {
        $percent_arr[$i]='N';
        $cnt[$i]=0;
    }

    $phoneVer = false;
    $infoStrCnt = 0;
    $count=0;
    $percent=0;
    $page1_completion_percent=30;  // (2% added by default for city living in)  
    
    if($jprofile_result)
    {
        if($jprofile['viewer']['PROFILEID']==$profileid)
            $row1=$jprofile['viewer'];
        elseif($jprofile['viewed']['PROFILEID']==$profileid)
            $row1=$jprofile['viewed'];
    }
    if($row1)
    {
        $pass=1;
    }
    elseif($PROFILE_3D['GENDER']=="")
    {

        $sql1="SELECT PROFILEID,GENDER,HAVEPHOTO,PHOTO_DISPLAY,RELIGION,EDUCATION,JOB_INFO,WORK_STATUS,BTYPE,COMPLEXION,DIET,SMOKE,DRINK,SHOW_HOROSCOPE,HOROSCOPE_MATCH,MANGLIK,CITY_RES,FAMILYINFO,FAMILY_STATUS,FAMILY_TYPE,FAMILY_BACK,MOTHER_OCC,T_BROTHER,T_SISTER,M_BROTHER,M_SISTER,PARENT_CITY_SAME,YOURINFO,PHONE_RES,PHONE_MOB,SUBCASTE,CASTE,EDU_LEVEL_NEW,EDU_LEVEL,OCCUPATION,INCOME,PRIVACY,MOB_STATUS,LANDL_STATUS,PHONE_FLAG FROM newjs.JPROFILE WHERE  activatedKey=1 and PROFILEID='$profileid'";
        $res1=mysql_query($sql1,$slv_db) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql1,"ShowErrTemplate");

        if($row1=@mysql_fetch_array($res1))
            $pass=1;
    }
    else
    {
        $row1=$PROFILE_3D;
        $pass=1;
    }
	
    $sql4="SELECT COUNT(*) AS cntfilter FROM FILTERS WHERE PROFILEID='$profileid'";
    $res4=mysql_query_decide($sql4);
    if($pass)
    {
        $percent = $page1_completion_percent;
        // Photo	
        $gender = $row1['GENDER'];
        $havephoto=$row1['HAVEPHOTO'];
        $photo_display=$row1['PHOTO_DISPLAY'];

        // YourInfo
        $yourInfo = $row1['YOURINFO'];

        // Education
        $education = $row1['EDUCATION'];
        $workStatus = $row1['WORK_STATUS'];
        $job_info = $row1['JOB_INFO'];

        // Personal
        //$spoken_lang = $row1['SPOKEN_LANGUAGES'];	
        $bodytype=$row1['BTYPE'];
        $complexion=$row1['COMPLEXION'];
        $diet=$row1['DIET'];
        $smoke=$row1['SMOKE'];
        $drink=$row1['DRINK'];

        // Religion sp.(Hindu) 
        $horoscope_match = $row1['HOROSCOPE_MATCH'];
        $show_horoscope=$row1['SHOW_HOROSCOPE'];
        $manglik=$row1['MANGLIK'];
        $religion=$row1['RELIGION'];
        $caste =$row1['CASTE'];
        // Family
        $family_type = $row1['FAMILY_TYPE'];
        $family_status = $row1['FAMILY_STATUS'];
        $father_occ = $row1['FAMILY_BACK'];
        $mother_occ = $row1['MOTHER_OCC'];
        $brothersT = $row1['T_BROTHER'];
        $brothersM = $row1['M_BROTHER'];
        $sistersT = $row1['T_SISTER'];
        $sistersM = $row1['M_SISTER'];
        $live_with_parents = $row1['PARENT_CITY_SAME'];
        $familyinfo=$row1['FAMILYINFO'];

        // Contacts
        $phone_res=$row1['PHONE_RES'];
        $phone_mob=$row1['PHONE_MOB'];
//alternate number addition start added by esha
	$sqlAlt="";
	$sqlAlt ="SELECT ALT_MOBILE FROM newjs.JPROFILE_CONTACT WHERE PROFILEID='".$profileid."'";
	$resAlt =mysql_query($sqlAlt,$slv_db) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sqlAlt,"ShowErrTemplate");

        if($rowAlt=@mysql_fetch_array($resAlt))
		$phone_alt=$rowAlt['ALT_MOBILE'];////
//alternate number additon end
        // Photo	
        //if($havephoto!='N')
        $tot_photo_percent = $percent;
        if($havephoto!='N' && $havephoto!='')
        {
            if($photo_display=='A')
            {
                $percent+=16;
            }
            else
            {
                $photo_percent=1;
                $percent+=8;
            }
        }
        $tot_photo_percent = $percent-$tot_photo_percent;
        $tot_photo_percent = 16-$tot_photo_percent;
        $smarty->assign("TOT_PHOTO_PERCENT_LEFT",$tot_photo_percent);

        // YourInfo
        $total_yourInfo = $percent;
        if($yourInfo)
        {
            $infoStrCnt =strlen(trim($yourInfo));
            if($infoStrCnt >=200)
                $percent +=10;
            else{
                $yourInfoPercent =1;
                $percent +=5;
            }
        }
        $total_yourInfo = $percent-$total_yourInfo;
        $total_yourInfo = 10-$total_yourInfo;
        $smarty->assign("YOUR_INFO_PERCENT_LEFT",$total_yourInfo);

        // Education		
        $total_education_percent=$percent;
        if($education)
        {
            $percent+=4;
        }
        if($workStatus)
        {
            $percent+=1;
        }
        if($job_info)
        {
            $percent+=4;
        }
        $total_education_percent=$percent-$total_education_percent;
        $total_education_percent=9-$total_education_percent;
        $smarty->assign("EDUCATION_PERCENT_LEFT",$total_education_percent);

        // Personal traits                
        $total_physical_percent=$percent;
        if($bodytype)
        {
            $percent+=1;
        }
        if($complexion)
        {
            $percent+=1;
        }
        if($diet)
        {
            $percent+=1;
        }
        if($smoke)
        {
            $percent+=1;
        }
        if($drink)
        {
            $percent+=1;
        }
        $total_physical_percent=$percent-$total_physical_percent;
        $total_physical_percent=5-$total_physical_percent;
        $smarty->assign("PHYSICAL_PERCENT_LEFT",$total_physical_percent);

        // spoken language from hobbies (separated from personal traits)
        $total_spoken_lang_percent = $percent;
        $sql_hobby = "SELECT * FROM newjs.JHOBBY WHERE PROFILEID='$profileid'";
        $res_hobby = mysql_query_decide($sql_hobby) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql_hobby,"ShowErrTemplate");
        $row_hobby = mysql_fetch_array($res_hobby);
        $hobby = $row_hobby['HOBBY'];
        $hobbyArr = explode(",",$hobby);

        $sql_hobby_MAP = "SELECT SQL_CACHE * FROM newjs.HOBBIES";
        $res_hobby_MAP = mysql_query_decide($sql_hobby_MAP) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql_hobby_MAP,"ShowErrTemplate");
        $map_array =array();
        while($row_hobby_MAP = mysql_fetch_array($res_hobby_MAP))
        {
            $value = $row_hobby_MAP['VALUE'];
            $type  = $row_hobby_MAP['TYPE'];
            $map_array[$value] = $type;
        }
        $i=0;
        foreach($hobbyArr as $key=>$val){
            $typeVal[$i] = $map_array[$val];
            $i++;
        }
        $typeVal_iniq = array_unique($typeVal);
        foreach($typeVal_iniq as $key1=>$val1){
            if($val1 =='LANGUAGE')
                $spoken_lang =1;
        }
        if($spoken_lang)
        {
            $percent+=1;
        }
        $total_spoken_lang_percent=$percent-$total_spoken_lang_percent;
        $total_spoken_lang_percent=1-$total_spoken_lang_percent;
        $smarty->assign("SPOKEN_LANG_PERCENT_LEFT",$total_spoken_lang_percent);

        // Horoscope		
        if($religion==1 || $religion==9)
        {
            $total_horoscope_percent=$percent;
            $sql_horo_screend = "SELECT COUNT(*) AS COUNT FROM newjs.HOROSCOPE WHERE PROFILEID='$profileid'";
            $res_horo_screend = mysql_query_decide($sql_horo_screend) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql_horo_screend,"ShowErrTemplate");
            $row_horo_screend = mysql_fetch_array($res_horo_screend);
            if($row_horo_screend['COUNT'] > 0){
                $horoscope_screend = 1;
            }
            else{	
                $sql_screen_horo = "SELECT COUNT(*) AS COUNT FROM newjs.HOROSCOPE_FOR_SCREEN WHERE PROFILEID='$profileid'";
                $res_screen_horo = mysql_query_decide($sql_screen_horo) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql_screen_horo,"ShowErrTemplate");
                $row_screen_horo = mysql_fetch_array($res_screen_horo);
                if($row_screen_horo['COUNT'] > 0){
                    $screen_horoscope_filled = 1;
                }
                else{

                    $sql_horo = "SELECT COUNT(*) AS COUNT FROM newjs.ASTRO_DETAILS WHERE PROFILEID='$profileid'";
                    $res_horo = mysql_query_decide($sql_horo) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql_horo,"ShowErrTemplate");           
                    $row_horo = mysql_fetch_array($res_horo);
                    if($row_horo['COUNT'] > 0){
                        $horoscope_filled = 1;
                    }
                }
            }
            if($horoscope_filled || $screen_horoscope_filled || $horoscope_screend)
                $percent += 5;
            $total_horoscope_percent=$percent-$total_horoscope_percent;
            $total_horoscope_percent=5-$total_horoscope_percent;
            $smarty->assign("HOROSCOPE_PERCENT_LEFT",$total_horoscope_percent); 
        }

        // Family Info
        $total_family_percent=$percent;
        if($family_type)
        {
            $percent+= 1;
        }
        if($family_status)
        {
            $percent+= 1;
        }
        if($father_occ)
        {
            $percent+= 1;
        }
        if($mother_occ)
        {
            $percent+= 1;
        }
        $brothers = false;
        if($brothersT || $brothersT==='0' || $brothersM || $brothersM==='0')
        {
            $percent+= 1;
        }
        if($sistersT || $sistersT==='0' || $sistersM || $sistersM==='0')
        {
            $percent+= 1;
        }
        if($live_with_parents)
        {
            $percent+= 1;
        }
        $total_family_percent=$percent-$total_family_percent;
        $total_family_percent=7-$total_family_percent;
        $smarty->assign("FAMILY_PERCENT_LEFT",$total_family_percent);

        // Family Info
        $total_familyinfo_percent = $percent;
        if($familyinfo)
        {
            if(strlen($familyinfo)>=100)
            {
                $percent+=3;
            }
            else
            {
                $family_info_left=1;
                $percent+=1;
            }
        }
        $total_familyinfo_percent=$percent-$total_familyinfo_percent;
        $total_familyinfo_percent=3-$total_familyinfo_percent;
        $smarty->assign("FAMILYINFO_PERCENT_LEFT",$total_familyinfo_percent);

        // Contacts                
        $total_contact_percent=$percent;

//        if($phone_res && $phone_mob)
	if(($phone_mob && $phone_res) || ($phone_mob && $phone_alt) || ($phone_res && $phone_alt))//any 2 numbers if present increase the profile completeness by 2%
//        if($phone_res && $phone_mob && $phone_alt)//alternate number added by esha
        {
            $percent+=2;
        }
        $total_contact_percent=$percent-$total_contact_percent;
        $total_contact_percent=2-$total_contact_percent;
        $smarty->assign("CONTACT_PERCENT_LEFT",$total_contact_percent);

        // IVR - Phone verification status
        $contact_no_verify = $percent;		
        $phoneStatus =getPhoneStatus($row1);
        if($phoneStatus =='Y'){
            $percent += 10;
            $phoneVer = true;
        }
        $contact_no_verify = $percent-$contact_no_verify;
        $contact_no_verify = 10-$contact_no_verify;
        $smarty->assign("CONTACT_NO_VERIFY_LEFT",$contact_no_verify);
        // IVR - Phone verification status ends

    }

    $total_religion_percent=$percent;
    /* Start Religion specific weight allotment
       Total religion weight=7
     */

    /* Hindu religion specific, weight(7)
       added Horoscope needed  and manglik/ percentages.
     */
    if($religion==1)
    {
        $total_manglik_nakshatram_percent=$percent;
        if($horoscope_match && $religion==1)
        {       
            $percent+=1;
        }
        if($manglik && $religion==1)
        { 
            $percent+=1;
        }
        //if($horoscope_match!='Y' || !$manglik)
        $total_manglik_nakshatram_percent=$percent-$total_manglik_nakshatram_percent;
        $total_manglik_nakshatram_percent=2-$total_manglik_nakshatram_percent;
        $smarty->assign("MANGLIK_PERCENT_LEFT",$total_manglik_nakshatram_percent);
        /* End Hindu Religion Specific
         */
    }

    //------ Christianity Start ------
    if($religion==3)
    {
        $sql_christian = "SELECT * FROM newjs.JP_CHRISTIAN WHERE PROFILEID='$profileid'";
        $res_christian = mysql_query_decide($sql_christian) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql_christian,"ShowErrTemplate");
        $row_christian = mysql_fetch_array($res_christian);

        $diocese = $row_christian['DIOCESE'];
        $baptised = $row_christian['BAPTISED'];
        $read_bible = $row_christian['READ_BIBLE'];
        $offer_tithe = $row_christian['OFFER_TITHE'];
        $spread_gospel = $row_christian['SPREADING_GOSPEL'];

        if($diocese)
            $percent += 2;

        if($baptised)
            $percent += 2;

        if($read_bible)
            $percent += 1;

        if($offer_tithe)
            $percent += 1;

        if($spread_gospel)
            $percent += 1;

        if((!$diocese || !$baptised || !$read_bible || !$offer_tithe || !$spread_gospel) && $religion==3)
            $religion_christian = 1;

    }
    //------ Islam Start ---------
    if($religion==2)
    {
        $sql_muslim = "SELECT * FROM newjs.JP_MUSLIM WHERE PROFILEID='$profileid'";
        $res_muslim = mysql_query_decide($sql_muslim) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql_muslim,"ShowErrTemplate");
        $row_muslim = mysql_fetch_array($res_muslim);

        $namaz = $row_muslim['NAMAZ'];
        $zakat = $row_muslim['ZAKAT'];
        $fasting = $row_muslim['FASTING'];
        $umrah_hajj = $row_muslim['UMRAH_HAJJ'];
        $quran = $row_muslim['QURAN'];
        $sunnah_beard = $row_muslim['SUNNAH_BEARD'];
        $sunnah_cap = $row_muslim['SUNNAH_CAP'];

        if($namaz)
            $percent += 1;
        if($zakat)
            $percent += 1;
        if($fasting)
            $percent += 1;
        if($umrah_hajj)
            $percent += 1;
        if($quran)
            $percent += 1;
        if($sunnah_beard)
            $percent += 1;
        if($sunnah_cap)
            $percent += 1;
        if($gender =='F'){
            $percent += 2;
            if((!$namaz || !$zakat || !$fasting || !$umrah_hajj || !$quran) && $religion==2)
                $religion_islam =1;
        }
        else{
            if((!$namaz || !$zakat || !$fasting || !$umrah_hajj || !$quran || !$sunnah_beard || !$sunnah_cap) && $religion==2)
                $religion_islam =1;
        }
    }	
    //------- Jainism Start ------
    if($religion==9)
    {
        if($caste ==175){
            $sql_jain = "SELECT SAMPRADAY FROM newjs.JP_JAIN WHERE PROFILEID='$profileid'";
            $res_jain = mysql_query_decide($sql_jain) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql_jain,"ShowErrTemplate");
            $row_jain = mysql_fetch_array($res_jain);
            $smapraday = $row_jain['SAMPRADAY'];
            if($smapraday)
                $percent += 2;
            else
                $religion_jain =1;
        }
        else{
            $percent += 2;
        }
    }

    // ------ Sikh Start -------
    if($religion==4)
    {	
        $sql_sikh = "SELECT * FROM newjs.JP_SIKH WHERE PROFILEID='$profileid'";
        $res_sikh = mysql_query_decide($sql_sikh) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql_muslim,"ShowErrTemplate");
        $row_sikh = mysql_fetch_array($res_sikh);

        $amritdhari = $row_sikh['AMRITDHARI'];
        $cut_hair = $row_sikh['CUT_HAIR'];
        $trim_beard = $row_sikh['TRIM_BEARD'];
        $wear_turban = $row_sikh['WEAR_TURBAN'];
        $clean_shaven = $row_sikh['CLEAN_SHAVEN'];

        if($gender == "M")
        {
            if($amritdhari == "Y")
            {
                $percent += 7;
            }
            else if($amritdhari == "N")
            {
                $percent += 3;
                if($cut_hair)
                    $percent += 1;
                if($trim_beard)
                    $percent += 1;
                if($wear_turban)
                    $percent += 1;
                if($clean_shaven)
                    $percent += 1;

                if((!$cut_hair || !$trim_beard || !$wear_turban || !$clean_shaven) && $religion==4 )
                    $religion_sikh =1;
            }
            if(!$amritdhari && $religion==4)
                $religion_sikh =1;
        }
        elseif($gender == "F")
        {
            if($amritdhari == "Y")
            {
                $percent += 7;
            }
            elseif($amritdhari == "N")
            {
                $percent += 3;
                if($cut_hair)
                    $percent += 4;

                if(!$cut_hair && $religion==4)
                    $religion_sikh =1;
            }
            if(!$amritdhari && $religion==4)
                $religion_sikh =1;
        }
    }

    //----- Paris Start -----
    if($religion==5)
    {
        $sql_parsi = "SELECT * FROM newjs.JP_PARSI WHERE PROFILEID='$profileid'";
        $res_parsi = mysql_query_decide($sql_parsi) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql_parsi,"ShowErrTemplate");
        $row_parsi = mysql_fetch_array($res_parsi);

        $zarathushtri = $row_parsi['ZARATHUSHTRI'];
        $parents_zarathushtri = $row_parsi['PARENTS_ZARATHUSHTRI'];

        if($zarathushtri)
            $percent += 4;
        if($parents_zarathushtri)
            $percent += 3;

        if((!$zarathushtri || !$parents_zarathushtri) && $religion==5)
            $religion_paris=1;

    }
    /* End Religion specific weight allotment
     */
    if($religion==6 || $religion==7 || $religion==8){
        $percent +=7;
    }
    else if($religion==2 || $religion==3 || $religion==4 || $religion==5){
        $total_religion_percent=$percent-$total_religion_percent;
        $total_religion_percent=7-$total_religion_percent;
        $smarty->assign("RELIGION_PERCENT_LEFT",$total_religion_percent);
    }
    else if($religion ==9){
        $total_religion_percent=$percent-$total_religion_percent;
        $total_religion_percent=2-$total_religion_percent;
        $smarty->assign("RELIGION_PERCENT_LEFT",$total_religion_percent);
    }	

    if($row4=@mysql_fetch_array($res4))
    {
        $cntfilter=$row4['cntfilter'];
    }

    /*following code added to check which profile elements are incomplete.
      if percent_arr[] is 'Y' than that particular profile element is incomplete.
     */

    //photo
    if($havephoto=='N' || $havephoto=='')
    {
        $percent_arr[0]='Y';
        $count++;
        $cnt[0]=$count;	
    }
    //personal Info
    if(!$yourInfo)
    {
        $percent_arr[1]='Y';
        $count++;
        $cnt[1]=$count;
    }
    // Phone Verification
    if(!$phoneVer)
    {
        $percent_arr[2]='Y';
        $count++;
        $cnt[2]=$count;
    }
    //education and career
    if(!$education || !$workStatus || !$job_info)
    {
        $percent_arr[3]='Y';
        $count++;
        $cnt[3]=$count;
    }       
    //family_values
    if(!$family_type || !$family_status || !$father_occ || !$mother_occ || !$live_with_parents || (!$brothersT && !$brothersM) || (!$sistersT && !$sistersM) ) 
    {
        if($total_family_percent==0){}
        else{
            $percent_arr[4]='Y';
            $count++;
            $cnt[4]=$count;
        }
    }
    //Specific Religion
    if($religion_christian || $religion_islam || $religion_jain || $religion_paris || $religion_sikh)
    {
        $percent_arr[5]='Y';
        $count++;
        $cnt[5]=$count;
    }
    //personal traits
    if(!$bodytype || !$complexion || !$diet || !$smoke || !$drink)
    {
        $percent_arr[6]='Y';
        $count++;
        $cnt[6]=$count;
    }
    //horoscope
    if($religion==1 || $religion==9)
    {
        if($horoscope_filled || $screen_horoscope_filled || $horoscope_screend)
        {}
        else	
        {
            $percent_arr[7]='Y';
            $count++;
            $cnt[7]=$count;
        }
    }
    // Family Info 
    if(!$familyinfo)
    {
        $percent_arr[8]='Y';
        $count++;
        $cnt[8]=$count;
    }
    // Contact Details
    if((!$phone_res && !$phone_mob) || (!$phone_mob && !!$phone_alt) || (!$phone_alt && !$phone_res))//alternate number added by esha
    {
        $percent_arr[9]='Y';
        $count++;
        $cnt[9]=$count;
    }
    //To separate manglik percentages from horoscope.
    if( (!$manglik || !$horoscope_match) && $religion==1)
    {
        $percent_arr[10]='Y';
        $count++;
        $cnt[10]=$count;
    }
    // spoken lang (separated from personal traits)
    if(!$spoken_lang)
    {
        $percent_arr[11]='Y';
        $count++;
        $cnt[11]=$count;
    }
    //added by lavesh
    if($from_jsadmin)
        $count=0;
    //ends here

    if($photo_percent && $count<4)
    {
        $count++;
        $smarty->assign("PHOTO_PERCENT",'Y');
    }
    if($yourInfoPercent && $count<4)
    {
        $count++;
        $smarty->assign("YOUR_INFO",'Y');
    }
    if($family_info_left && $count<4)
    {
        $count++;
        $smarty->assign("FAMILY_INFO",'Y');
    }

    if($topthree)
    {
        /*
           $flag=0;
           foreach($percent_arr as $k=>$v)
           {
           if($v=='Y')
           $flag++;
           if($flag>$topthree)
           $percent_arr[$k]='N';
           }
         */
        //print_r($percent_arr);
        $smarty->assign("PERCENT_ARR_MYJS_CNT",$flag);	
        $smarty->assign("PERCENT_ARR_MYJS",$percent_arr);
    }

    $smarty->assign("PERCENT_ARR",$percent_arr);
    $smarty->assign("COUNT_ARR",$cnt);
    $smarty->assign("COUNT",$count);
    $smarty->assign("MOBILE_NO",$phone_mob);
    return $percent;
}

/***********************************************************************
 *    DESCRIPTION   :    Find the days difference between two dates
 *    RETURNS       :    number of days, date2 > date1 ;
 0,if date1= date2
 -1, if date1 > date2
 ***********************************************************************/
if (!function_exists('getTimeDiff'))
{
    function getTimeDiff($date1,$date2)
    {
        if($date2 > $date1)
        {
            list($yy1,$mm1,$dd1)= explode("-",$date1);
            list($yy2,$mm2,$dd2)= explode("-",$date2);
            $date1_timestamp= mktime(0,0,0,$mm1,$dd1,$yy1);
            $date2_timestamp= mktime(0,0,0,$mm2,$dd2,$yy2);
            $timestamp_diff= $date2_timestamp - $date1_timestamp;
            $days_diff= $timestamp_diff / (24*60*60);
            return $days_diff;
        }
        elseif($date2 == $date1)
            return 0;
        else
            return -1;
    }
}

//function to populate LABELS and values in clustering layers 
function cluster_layers()
{
    global $smarty;
    $sql_edu = "select SQL_CACHE ID,LABEL from newjs.EDUCATION_LEVEL_NEW_CLUSTER where VISIBLE = 'Y' order by SORTBY";
    $res_edu = mysql_query_decide($sql_edu) or logError("error",$sql_edu);
    while($myrow_edu = mysql_fetch_array($res_edu))
    {
        $edu_label.="'"."$myrow_edu[LABEL]"."',";
        $edu_value.="'"."$myrow_edu[ID]"."',";//value is replaced by ID
    }

    $sql_income = "select SQL_CACHE ID, LABEL from newjs.INCOME_CLUSTER where VISIBLE = 'Y' order by SORTBY";
    $res_income = mysql_query_decide($sql_income) or logError("error",$sql_income);
    while($myrow_income = mysql_fetch_array($res_income))
    {
        $income_label.="'"."$myrow_income[LABEL]"."',";
        $income_value.="'"."$myrow_income[ID]"."',";//value is replaced by ID
    }

    $sql_occ = "select SQL_CACHE ID,LABEL from newjs.OCCUPATION_CLUSTER where VISIBLE = 'Y' order by SORTBY";
    $res_occ = mysql_query_decide($sql_occ) or logError("error",$sql_occ);
    while($myrow_occ = mysql_fetch_array($res_occ))
    {
        $occ_label.="'"."$myrow_occ[LABEL]"."',";
        $occ_value.="'"."$myrow_occ[ID]"."',";//value is replaced by ID
    }

    $edu_label=substr($edu_label,0,-1);
    $edu_value=substr($edu_value,0,-1);
    $occ_label=substr($occ_label,0,-1);
    $occ_value=substr($occ_value,0,-1);
    $income_label=substr($income_label,0,-1);
    $income_value=substr($income_value,0,-1);
    $edu_value="new Array($edu_value)";
    $edu_label="new Array($edu_label)";
    $occ_value="new Array($occ_value)";         
    $occ_label="new Array($occ_label)";
    $income_value="new Array($income_value)";
    $income_label="new Array($income_label)";
    $smarty->assign("EDU_LABEL",$edu_label);
    $smarty->assign("OCC_VALUE",$occ_value);
    $smarty->assign("OCC_LABEL",$occ_label);
    $smarty->assign("INCOME_VALUE",$income_value);
    $smarty->assign("INCOME_LABEL",$income_label);
    $smarty->assign("EDU_VALUE",$edu_value);
}
if (!function_exists('getMonthName'))
{
    function getMonthName($month)
    {
        switch ($month)
        {
            case "01":
            case "1":
                return "Jan";
            case "02":
            case "2":
                return "Feb";
            case "03":
            case "3":
                return "Mar";
            case "04":
            case "4":
                return "Apr";
            case "05":
            case "5":
                return "May";
            case "06":
            case "6":
                return "Jun";
            case "07":
            case "7":
                return "Jul";
            case "08":
            case "8":
                return "Aug";
            case "09":
            case "9":
                return "Sep";
            case "10":
                return "Oct";
            case "11":
                return "Nov";
            case "12":
                return "Dec";
        }
    }
}
if(!function_exists('delete_picture')){
    function delete_picture($profileid,$case)
    {
        global $_SERVER;
        $http_msg=print_r($_SERVER,true);
        $str="echo \"$http_msg\" >> /tmp/test/log_photoModule.txt";
        passthru($str);
    }
}


/* Messenger ID error Codes */

$mid_error_codes = array("0" => "Okay", 
                         "1" => "Messenger ID should be atleast 4 <br />characters long.",  
                         "2" => "Word not allowed. Please provide a valid<br />messenger ID.", 
                         "3" => "Please provide a valid messenger ID.", 
                         "4" => "Please select type of messenger - GTalk,<br />Yahoo ..etc.", 
                         "5" => "At least one alphabet should be present<br />in messenger ID");

function is_char($c) {
    return preg_match('/[a-zA-Z]/', $c);
}

function is_messenger_id_valid($messenger_id) {
    global $mid_error_codes;
    $char_count = 0;
    $pos = strpos($messenger_id, '.');
    if (preg_match('/^[a-zA-Z0-9._\-]+$/', $messenger_id))
    {
        if($pos === 0) {
            return array('3', $mid_error_codes[3]);
        }
        for($i = 0; $i < strlen($messenger_id); $i++)
        {
            $lastchar = $messenger_id[$i];
            $previousChar = "";
            if (is_char($messenger_id[$i])) {
                $char_count += 1;
            }
            if ($i > 0) $previousChar = $messenger_id[$i - 1];
            if ($lastchar == "." && $previousChar == ".") {
                return array('3', $mid_error_codes[3]);
            }
        }
        if ($char_count > 0) { // messenger id contains atleast one character.
            return array('0', $mid_error_codes[0]);
        }
        else {
            return array('5', $mid_error_codes[5]);
        }
    } else {
        return array('3', $mid_error_codes[3]);
    }
}

function check_messenger_id($messenger_id) {
    global $mid_error_codes;
    
    // Hint text for messenger id as it appears
    $isValid = '';
    $reason = '';
    $hint_text = "e.g. raj1983, vicky1980 ";
    $banned_words = array("no", "none", "messenger id", "messenger", "gmail", "facebook", "gmail.com", "yahoo", "no id", "google", "rediffmail","rediff", "na", "nil", "any", "good", "non", "yes", "later", "hello", "hindi", "orkut", "skype", "love", "airtel", "nothing", "face book", "i love you", "google talk");
    if ($messenger_id == "" || $messenger_id == $hint_text) {
        $messenger_id = "";
        $error_code = 0;
        $error_message = $mid_error_codes[0];
        return array($error_code, $error_message);
    }

    if (in_array(strtolower($messenger_id), $banned_words)) {
        return array('2', $mid_error_codes[2]);
    }

    if (strpos($messenger_id, "@") != false) {
        $messenger_id = preg_split('@', $messenger_id);
        $messenger_id = $messenger_id[0];
    }

    if (strlen($messenger_id) < 4) {
        $error_code = 1;
        $error_message = $mid_error_codes[1];
    } else {
        list($error_code, $error_message) = is_messenger_id_valid($messenger_id);
    }
    return array($error_code, $error_message);
}

function is_messenger_channel_selected($messenger_id, $messenger_channel) {
    global $mid_error_codes;
    $error_code = '';
    $error_message = '';
    list($error_code, $error_message) = check_messenger_id($messenger_id);
    if ($error_code != '0')
        return array($error_code, $error_message);
    else if ($error_code == '0') {
        if ($messenger_id == "" || $messenger_id == "e.g. raj1983, vicky1980 ") {
            return array('0', $mid_error_codes[0]);
        }
        else if ($messenger_id && ($messenger_channel == '')) {
            return array('4', $mid_error_codes[4]);
        }
        else {
            return array('0', $mid_error_codes[0]);
        }
    }
    else 
        return array('0', $mid_error_codes[0]);
}


?>
