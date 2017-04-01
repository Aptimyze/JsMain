<?php
/**Script written by Aman Sharma for Qc-Screening Module**/
include("connect.inc");
include ("time.php");
include(JsConstants::$docRoot."/commonFiles/flag.php");
include_once(JsConstants::$docRoot."/classes/JProfileUpdateLib.php");
global $screen_time;
if(authenticated($cid))
{
    $user=getname($cid);
    $smarty->assign("user",$user);
    $smarty->assign("pid",$pid);
    $fields_array=array('SUBCASTE','CITY_BIRTH','GOTHRA','NAKSHATRA','MESSENGER_ID','YOURINFO','FAMILYINFO','SPOUSE','CONTACT','EDUCATION','PHONE_RES','PHONE_MOB','EMAIL','JOB_INFO','FATHER_INFO','SIBLING_INFO','PARENTS_CONTACT');
    $fields_str=implode(",",$fields_array);
    if($submit)
    {
        $sql="INSERT into jsadmin.SCREENING_GRADES(REF_ID,PROFILEID,SCREENED_BY,ERRORS,GRADED_BY,ENTRY_DT,FIELDS_SCREENED) VALUES('$ref_id','$pid','$screened_by','$errors','$user',now(),'$fields_screened')";
        mysql_query_decide($sql) or die("$sql".mysql_error_js());
        $sql="UPDATE jsadmin.SCREENING_LOG SET GRADED='Y' where REF_ID='$ref_id'";
        mysql_query_decide($sql) or die("$sql".mysql_error_js());
        $message="This user's profile has been graded successfully.<br>";
        if($screen_again=='Y')
        {
            $sql="SELECT count(*) as cnt from jsadmin.MAIN_ADMIN where PROFILEID='$pid'";
            $result=mysql_query_decide($sql) or die("$sql".mysql_error_js());
            $row=mysql_fetch_array($result);
            if($row["cnt"]>0)
            {
                $message.=" But it is already under screening. ";
            }
            else
            {
                $screen=4094303;
                foreach($fields_array as $fld)
                {
                    if($$fld=='Y')
                    {
                        if($fld=='CITY_BIRTH')
                            $screen=removeflag("CITYBIRTH",$screen);
                        elseif($fld=='MESSENGER_ID')
                            $screen=removeflag("MESSENGER",$screen);
                        elseif($fld=='PHONE_RES')
                        {
                            $screen=removeflag("PHONERES",$screen);
                        }
                        elseif($fld=='PHONE_MOB')
                            $screen=removeflag("PHONEMOB",$screen);
                        else
                            $screen=removeflag($fld,$screen);
                    }
                }
                $sql="select USERNAME,SUBSCRIPTION from newjs.JPROFILE where PROFILEID='$pid'";
                $result=mysql_query_decide($sql) or die("$sql".mysql_error_js());
                $myrow=mysql_fetch_array($result);
                if(mysql_num_rows($result)>0)
                {
                    $RECV_DT=date("Y-m-d H:i:s");
                    $SUBMIT_DT=newtime($RECV_DT,0,$screen_time,0);
                    $subs=$myrow['SUBSCRIPTION'];
                    $username=$myrow['USERNAME'];
                    $sql_assign="REPLACE INTO jsadmin.MAIN_ADMIN(PROFILEID,USERNAME,SCREENING_TYPE,RECEIVE_TIME,SUBMIT_TIME,ALLOT_TIME,ALLOTED_TO,SUBSCRIPTION_TYPE) VALUES('$pid','$username','O','$RECV_DT','$SUBMIT_DT',NOW(),'$screened_by','$subs')";
                    mysql_query_decide($sql_assign) or die("$sql_assign".mysql_error_js());

                    //wrapping update query
                    $jprofileUpdateObj = JProfileUpdateLib::getInstance(); 
                    $arrFields = array('SCREENING'=>$screen);
                    $jprofileUpdateObj->editJPROFILE($arrFields,$pid,"PROFILEID");
                    // $sql_update="update newjs.JPROFILE set SCREENING='$screen' where PROFILEID='$pid'";
                    // mysql_query_decide($sql_update) or die("$sql_update".mysql_error_js());
                    $sql="select EMAIL from jsadmin.PSWRDS where USERNAME='$screened_by'";
                    $result=mysql_query_decide($sql) or die("$sql".mysql_error_js());
                    $row=mysql_fetch_array($result);
                    $email=$row["EMAIL"];
                    $cc="mahesh@jeevansathi.com";
                    $bcc="aman.sharma@jeevansathi.com";
                    $sub="Profile reassigned by QC";  
                    $msg1="QC-person has assigned you the profile of user- ".$username." for rescreening as he has found some errors left in its screening.";    
                    $from="webmaster@jeevansathi.com";
                    mail($email, $sub, $msg1,"From: $from\r\n"."Cc: $cc\r\n"."Bcc: $bcc\r\n"."X-Mailer: PHP/" . phpversion());
                    $message.="This profile has also been marked for screening .<br>";
                }
            }
        }
        $smarty->assign("MSG",$message);
        $smarty->display("jsadmin_msg.tpl");
    }		
    else
    {
        $sql_1="SELECT count(*) as cnt from jsadmin.SCREENING_GRADES where REF_ID='$id'";
        $result_1=mysql_query_decide($sql_1) or die("$sql_1".mysql_error_js());
        $row_1=mysql_fetch_array($result_1);
        if($row_1["cnt"]>0)
        {
            $graded='Y';
            $smarty->assign("GRADED",$graded);
        }
        $sql="SELECT * from jsadmin.SCREENING_LOG where REF_ID='$id'";
        $result=mysql_query_decide($sql) or die("$sql".mysql_error_js());
        if(mysql_num_rows($result)>0)
        {

            /***** Type = T denotes input type is Text, Type = A denotes the box will be specified as TextArea *****/
            $QCScreening['SUBCASTE']['LABEL'] = "Subcaste";
            $QCScreening['SUBCASTE']['TYPE'] = "T";
            $QCScreening['CITY_BIRTH']['LABEL'] = "City of Birth";
            $QCScreening['CITY_BIRTH']['TYPE'] = "T";
            $QCScreening['GOTHRA']['LABEL'] = "Gothra";
            $QCScreening['GOTHRA']['TYPE'] = "T";
            $QCScreening['NAKSHATRA']['LABEL'] = "Nakshatra";
            $QCScreening['NAKSHATRA']['TYPE'] = "T";
            $QCScreening['MESSENGER_ID']['LABEL'] = "Messenger ID";
            $QCScreening['MESSENGER_ID']['TYPE'] = "T";
            $QCScreening['YOURINFO']['LABEL'] = "Your Info";
            $QCScreening['YOURINFO']['TYPE'] = "A";
            $QCScreening['FAMILYINFO']['LABEL'] = "Family Info";
            $QCScreening['FAMILYINFO']['TYPE'] = "A";
            $QCScreening['SPOUSE']['LABEL'] = "Spouse";
            $QCScreening['SPOUSE']['TYPE'] = "A";
            $QCScreening['CONTACT']['LABEL'] = "Contact";
            $QCScreening['CONTACT']['TYPE'] = "A";
            $QCScreening['EDUCATION']['LABEL'] = "Education";
            $QCScreening['EDUCATION']['TYPE'] = "T";
            $QCScreening['PHONE_RES']['LABEL'] = "Phone Residence";
            $QCScreening['PHONE_RES']['TYPE'] = "T";
            $QCScreening['PHONE_MOB']['LABEL'] = "Phone Mobile";
            $QCScreening['PHONE_MOB']['TYPE'] = "T";
            $QCScreening['EMAIL']['LABEL'] = "Email";
            $QCScreening['EMAIL']['TYPE'] = "T";
            $QCScreening['JOB_INFO']['LABEL'] = "Job Info";
            $QCScreening['JOB_INFO']['TYPE'] = "A";
            $QCScreening['FATHER_INFO']['LABEL'] = "Father Info";
            $QCScreening['FATHER_INFO']['TYPE'] = "A";
            $QCScreening['SIBLING_INFO']['LABEL'] = "Sibling Info";
            $QCScreening['SIBLING_INFO']['TYPE'] = "A";
            $QCScreening['PARENTS_CONTACT']['LABEL'] = "Parents Contact";
            $QCScreening['PARENTS_CONTACT']['TYPE'] = "A";
            $QCScreening['NAME']['LABEL'] = "Name";
            $QCScreening['NAME']['TYPE'] = "T";
            $QCScreening['MSTATUS']['LABEL'] = "Marital Status";
            $QCScreening['MSTATUS']['TYPE'] = "T";
            $QCScreening['DTOFBIRTH']['LABEL'] = "Date of Birth";
            $QCScreening['DTOFBIRTH']['TYPE'] = "T";
            $QCScreening['PROFILE_HANDLER_NAME']['LABEL'] = "Name of the Person Handling Profile";
            $QCScreening['PROFILE_HANDLER_NAME']['TYPE'] = "T";
            $QCScreening['GOTHRA_MATERNAL']['LABEL'] = "Gothra (Maternal)";
            $QCScreening['GOTHRA_MATERNAL']['TYPE'] = "T";
            $QCScreening['COMPANY_NAME']['LABEL'] = "Name of Organization";
            $QCScreening['COMPANY_NAME']['TYPE'] = "T";
            $QCScreening['FAV_MOVIE']['LABEL'] = "Favourite Movies";
            $QCScreening['FAV_MOVIE']['TYPE'] = "A";
            $QCScreening['FAV_TVSHOW']['LABEL'] = "Favourite TV Shows";
            $QCScreening['FAV_TVSHOW']['TYPE'] = "A";
            $QCScreening['FAV_FOOD']['LABEL'] = "Food I Cook";
            $QCScreening['FAV_FOOD']['TYPE'] = "A";
            $QCScreening['FAV_BOOK']['LABEL'] = "Favourite Books";
            $QCScreening['FAV_BOOK']['TYPE'] = "A";
            $QCScreening['FAV_VAC_DEST']['LABEL'] = "Favourite Vacation Destination";
            $QCScreening['FAV_VAC_DEST']['TYPE'] = "A";
            $QCScreening['BLACKBERRY']['LABEL'] = "Blackberry PIN";
            $QCScreening['BLACKBERRY']['TYPE'] = "T";
            $QCScreening['LINKEDIN_URL']['LABEL'] = "LinkedIn URL/ID";
            $QCScreening['LINKEDIN_URL']['TYPE'] = "T";
            $QCScreening['FB_URL']['LABEL'] = "Facebook URL/ID";
            $QCScreening['FB_URL']['TYPE'] = "T";
            $QCScreening['ALT_MOBILE_OWNER_NAME']['LABEL'] = "Alternate Mobile Owner Name";
            $QCScreening['ALT_MOBILE_OWNER_NAME']['TYPE'] = "T";
            $QCScreening['ALT_MESSENGER_ID']['LABEL'] = "Alternate Messenger ID";
            $QCScreening['ALT_MESSENGER_ID']['TYPE'] = "T";
            $QCScreening['PG_COLLEGE']['LABEL'] = "PG College Name";
            $QCScreening['PG_COLLEGE']['TYPE'] = "T";
            $QCScreening['OTHER_UG_DEGREE']['LABEL'] = "Other Graduation Degree";
            $QCScreening['OTHER_UG_DEGREE']['TYPE'] = "T";
            $QCScreening['OTHER_PG_DEGREE']['LABEL'] = "Other PG Degree";
            $QCScreening['OTHER_PG_DEGREE']['TYPE'] = "T";
            $QCScreening['SCHOOL']['LABEL'] = "Name of School";
            $QCScreening['SCHOOL']['TYPE'] = "T";
            $QCScreening['COLLEGE']['LABEL'] = "Name of College";
            $QCScreening['COLLEGE']['TYPE'] = "T";
            while($myrow=mysql_fetch_array($result))
            {
                $smarty->assign("USERNAME", $myrow['USERNAME']);
                if($myrow["ENTRY_TYPE"]=='P')
                {
                    foreach ($QCScreening as $key => $value) {
                        $QCScreening[$key]['P'] = $myrow[$key];
                    }


                }
                elseif($myrow["ENTRY_TYPE"]=='M')
                {
                    foreach($QCScreening as $key => $value) {
                        $QCScreening[$key]['M'] = $myrow[$key];
                    }

                    if($graded!='Y')
                        $smarty->assign("SCREENED_BY",$myrow['SCREENED_BY']);
                    $smarty->assign("FIELDS_SCREENED", $myrow['FIELDS_SCREENED']);
                }	
                $smarty->assign("ref_id",$id);	
            }
            $smarty->assign("QCScreening", $QCScreening);
        }
        $smarty->assign("cid",$cid);
        $smarty->display("qc_view.htm");
    }
}
else
{
    $msg="Your session has been timed out<br>";
    $msg .="<a href=\"index.htm\">";
    $msg .="Login again </a>";
    $smarty->assign("MSG",$msg);
    $smarty->display("jsadmin_msg.tpl");

}


?>
