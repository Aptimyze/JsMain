<?php

include("connect.inc");
include(JsConstants::$docRoot."/commonFiles/flag.php");

$db = connect_rep();

if(authenticated($cid))
{
    $operator_name=getname($cid);
    $smarty->assign("operator_name",$operator_name);
    if($CMDSubmit)
    {
        $smarty->assign("FLAG","1");
        $sql="SELECT PROFILEID FROM newjs.JPROFILE WHERE USERNAME='$username'";
        $res=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js());
        $row=mysql_fetch_array($res);
        $profileid=$row['PROFILEID'];
        $sql1="SELECT USERNAME,SCREENING_TYPE,RECEIVE_TIME,SUBMIT_TIME,ALLOT_TIME,SUBMITED_TIME,ALLOTED_TO,STATUS, SUBSCRIPTION_TYPE, SCREENING_VAL FROM MAIN_ADMIN WHERE PROFILEID='$profileid' ORDER BY SCREENING_TYPE,SUBMITED_TIME DESC";
        $result=mysql_query_decide($sql1,$db) or die("$sql 1".mysql_error_js());
        $sql="SELECT USERNAME,SCREENING_TYPE,RECEIVE_TIME,SUBMIT_TIME,ALLOT_TIME,SUBMITED_TIME,ALLOTED_TO,STATUS, SUBSCRIPTION_TYPE, SCREENING_VAL FROM MAIN_ADMIN_LOG WHERE PROFILEID='$profileid' ORDER BY SCREENING_TYPE,SUBMITED_TIME DESC";
        $res=mysql_query_decide($sql,$db) or die("$sql ".mysql_error_js());
        if($row=mysql_fetch_array($res))
        {
            $i=0;
            do
            {
                /** Defining Labels **/
                $LABELS['USERNAME'] = "Username";
                $LABELS['SUBCASTE'] = "Subcaste";
                $LABELS['CITY_BIRTH'] = "City of Birth";
                $LABELS['GOTHRA'] = "Gothra";
                $LABELS['NAKSHATRA'] = "Nakshatra";
                $LABELS['MESSENGER_ID'] = "Messenger ID";
                $LABELS['YOUR_INFO'] = "Your Info";
                $LABELS['FAMILYINFO'] = "Family Info";
                $LABELS['SPOUSE'] = "Spouse";
                $LABELS['CONTACT'] = "Contact";
                $LABELS['EDUCATION'] = "Education";
                $LABELS['PHONE_RES'] = "Phone Residence";
                $LABELS['PHONE_MOB'] = "Phone Mobile";
                $LABELS['EMAIL'] = "Email";
                $LABELS['JOB_INFO'] = "Job Info";
                $LABELS['FATHER_INFO'] = "Father Info";
                $LABELS['SIBLING_INFO'] = "Sibling Info";
                $LABELS['PARENTS_CONTACT'] = "Parents Contact";
                $LABELS['NAME'] = "Name";
                $LABELS['MSTATUS'] = "Marital Status";
                $LABELS['DTOFBIRTH'] = "Date of Birth";
                $LABELS['PROFILE_HANDLER_NAME'] = "Name of the Person Handling Profile";
                $LABELS['GOTHRA_MATERNAL'] = "Gothra (Maternal)";
                $LABELS['COMPANY_NAME'] = "Name of Organization";
                $LABELS['FAV_MOVIE'] = "Favourite Movies";
                $LABELS['FAV_TVSHOW'] = "Favourite TV Shows";
                $LABELS['FAV_FOOD'] = "Food I Cook";
                $LABELS['FAV_BOOK'] = "Favourite Books";
                $LABELS['FAV_VAC_DEST'] = "Favourite Vacation Destination";
                $LABELS['BLACKBERRY'] = "Blackberry PIN";
                $LABELS['LINKEDIN_URL'] = "LinkedIn URL/ID";
                $LABELS['FB_URL'] = "Facebook URL/ID";
                $LABELS['ALT_MOBILE_OWNER_NAME'] = "Alternate Mobile Owner Name";
                $LABELS['ALT_MESSENGER_ID'] = "Alternate Messenger ID";
                $LABELS['PG_COLLEGE'] = "PG College Name";
                $LABELS['OTHER_UG_DEGREE'] = "Other Graduation Degree";
                $LABELS['OTHER_PG_DEGREE'] = "Other PG Degree";
                $LABELS['SCHOOL'] = "Name of School";
                $LABELS['COLLEGE'] = "Name of College";


                $rec[$i]["username"]=$row['USERNAME'];
                $scr_type=$row['SCREENING_TYPE'];
                $rec[$i]["rcv_time"]=$row['RECEIVE_TIME'];
                $rec[$i]["submit_time"]=$row['SUBMIT_TIME'];
                $rec[$i]["allot_time"]=$row['ALLOT_TIME'];
                $rec[$i]["submitted_time"]=$row['SUBMITED_TIME'];
                $rec[$i]["alloted_to"]=$row['ALLOTED_TO'];
                $rec[$i]["status"]=$row['STATUS'];
                $subscription=$row['SUBSCRIPTION_TYPE'];
                $scr_val=$row['SCREENING_VAL'];

                if($scr_type=='O')
                {
                    $rec[$i]["scr_type"]="Normal Screening";
                    foreach($LABELS as $key => $value) {
                        if (!isFlagSet($key, $scr_val))
                            $rec[$i]["edited"] .= "<br>$value";
                    }
                }
                elseif($scr_type=='P')
                {
                    $rec[$i]["scr_type"]="Photo Screening";
                    if(!isFlagSet("MAINPHOTO",$scr_val))
                        $rec[$i]["edited"]="Main Photo";
                    if(!isFlagSet("ALBUMPHOTO1",$scr_val))
                        $rec[$i]["edited"].="<br>Album Photo 1";
                    if(!isFlagSet("ALBUMPHOTO2",$scr_val))
                        $rec[$i]["edited"].="<br>Album Photo 2";
                }

                if($subscription)
                    $rec[$i]["bandcolor"]="fieldsnewgreen";
                else
                    $rec[$i]["bandcolor"]="fieldsnew";

                $i++;
            }while($row=mysql_fetch_array($res));
        }

        if($row1=mysql_fetch_array($result))
        {
            $i=0;
            do
            {
                $record[$i]["username"]=$row1['USERNAME'];
                $scr_type=$row1['SCREENING_TYPE'];
                $record[$i]["rcv_time"]=$row1['RECEIVE_TIME'];
                $record[$i]["submit_time"]=$row1['SUBMIT_TIME'];
                $record[$i]["allot_time"]=$row1['ALLOT_TIME'];
                $record[$i]["submitted_time"]=$row1['SUBMITED_TIME'];
                $record[$i]["alloted_to"]=$row1['ALLOTED_TO'];
                $record[$i]["status"]=$row1['STATUS'];
                $subscription=$row1['SUBSCRIPTION_TYPE'];
                $scr_val=$row1['SCREENING_VAL'];

                if($scr_type=='O')
                {
                    $record[$i]["scr_type"]="Normal Screening";
                    foreach($LABELS as $key => $value) {
                        if(!isFlagSet($key, $scr_val))
                            $rec[$i]["edited"] .= "<br>$value";
                    }
                }
                elseif($scr_type=='P')
                {
                    $record[$i]["scr_type"]="Photo Screening";
                    if(!isFlagSet("MAINPHOTO",$scr_val))
                        $record[$i]["edited"]="Main Photo";
                    if(!isFlagSet("ALBUMPHOTO1",$scr_val))
                        $record[$i]["edited"].="<br>Album Photo 1";
                    if(!isFlagSet("ALBUMPHOTO2",$scr_val))
                        $record[$i]["edited"].="<br>Album Photo 2";
                }
                elseif($scr_type=='H')
                {
                    $record[$i]["scr_type"]="Horoscope Screening";
                    $record[$i]["edited"]="Uploaded Horoscope";
                }

                if($subscription)
                    $record[$i]["bandcolor"]="fieldsnewgreen";
                else
                    $record[$i]["bandcolor"]="fieldsnew";

                $i++;
            }while($row=mysql_fetch_array($res));
        }
        $smarty->assign("REC",$rec);
        $smarty->assign("RECORD",$record);
        $smarty->assign("cid",$cid);
        $smarty->assign("username",$username);
        $smarty->display("show_user_record.htm");
    }
    else
    {
        $smarty->assign("cid",$cid);
        $smarty->display("show_user_record.htm");
    }

}

else
{
    $msg="Your session has been timed out<br>  ";
    $msg .="<a href=\"index.htm\">";
    $msg .="Login again </a>";
    $smarty->assign("MSG",$msg);
    $smarty->display("jsadmin_msg.tpl");
}
?>
