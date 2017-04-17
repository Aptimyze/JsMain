<?php

function getItemDetail($page, $filter)
{
    $item = array();
    $item = array(
        "accept" => array(
            "A" => array(
                "self" => "SENDER_RECEIVER",
                "contact" => "SENDER_RECEIVER",
                "type" => "RM",
                "flag" => "A",
                "navigation_type" => "ACC",
                "contactFlag" => "'A'"
            ),
            "M" => array(
                "self" => "RECEIVER",
                "contact" => "SENDER",
                "type" => "M",
                "flag" => "A",
                "navigation_type" => "ACC_M",
                "contactFlag" => "'A'"
            ),
            "R" => array(
                "self" => "SENDER",
                "contact" => "RECEIVER",
                "type" => "R",
                "flag" => "A",
                "navigation_type" => "ACC_R",
                "contactFlag" => "'A'"
            )
        ),
        "favorite" => array(
            "M" => array(
                "self" => "BOOKMARKER",
                "contact" => "BOOKMARKEE",
                "type" => "M",
                "flag" => "F",
                "stype" => "7",
                "stype_mobile"=>"WS",
                "navigation_type" => "FAV",
                "CHECKBOX" => 1,
                "table_name" => "newjs.BOOKMARKS",
                "time_field" => "BKDATE AS TIME,BKNOTE",
                "get_contact_field" => "RECEIVER"
            )
        ),
        "photo" => array(
            "R" => array(
                "self" => "PROFILEID_REQ_BY",
                "contact" => "PROFILEID",
                "type" => "R",
                "flag" => "P",
                "stype" => "20",
                "stype_mobile"=>"20",
                "navigation_type" => "PHO_R",
                "table_name" => "newjs.PHOTO_REQUEST",
                "get_contact_field" => "SENDER"
            ),
            "M" => array(
                "self" => "PROFILEID",
                "contact" => "PROFILEID_REQ_BY",
                "type" => "M",
                "flag" => "P",
                "stype" => "21",
                "stype_mobile"=>"21",
                "navigation_type" => "PHO_M",
                "table_name" => "newjs.PHOTO_REQUEST",
                "get_contact_field" => "RECEIVER"
            )
        ),
        "horoscope" => array(
            "R" => array(
                "self" => "PROFILEID_REQUEST_BY",
                "contact" => "PROFILEID",
                "type" => "R",
                "flag" => "H",
                "stype" => "22",
                "stype_mobile"=>"22",
                "navigation_type" => "HOR_R",
                "table_name" => "newjs.HOROSCOPE_REQUEST",
                "get_contact_field" => "SENDER"
            ),
            "M" => array(
                "self" => "PROFILEID",
                "contact" => "PROFILEID_REQUEST_BY",
                "type" => "M",
                "flag" => "H",
                "stype" => "23",
                "stype_mobile"=>"23",
                "navigation_type" => "HOR_M",
                "table_name" => "newjs.HOROSCOPE_REQUEST",
                "get_contact_field" => "RECEIVER"
            )
        ),
        "chat" => array(
            "A" => array(
                "self" => "SENDER_RECEIVER",
                "contact" => "SENDER_RECEIVER",
                "type" => "RM",
                "flag" => "C",
                "stype" => "24",
                "stype_mobile"=>"24",
                "navigation_type" => "CHAT"
            )
        ),
        "ignore" => array(
            "M" => array(
                "self" => "PROFILEID",
                "contact" => "IGNORED_PROFILEID",
                "type" => "M",
                "flag" => "IG",
                "stype" => "8",
                "navigation_type" => "IGN",
                "table_name" => "newjs.IGNORE_PROFILE",
                "time_field" => "DATE AS TIME",
                "get_contact_field" => "RECEIVER",
                "CHECKBOX" => 1
            )
        ),
        "decline" => array(
            "R" => array(
                "self" => "SENDER",
                "contact" => "RECEIVER",
                "type" => "R",
                "flag" => "D",
                "navigation_type" => "DEC_R",
                "contactFlag" => "'D'"
            ),
            "M" => array(
                "self" => "RECEIVER",
                "contact" => "SENDER",
                "type" => "M",
                "flag" => "D",
                "navigation_type" => "DEC_S",
                "SHOW_CATEGORY_SEARCH" => 1,
                "contactFlag" => "'D'"
            )
        ),
        "matches" => array(
            "R" => array(
                "self" => "RECEIVER",
                "contact" => "USER",
                "type" => "R",
                "flag" => "M",
                "stype" => "25",
                "stype_mobile"=>"WM",
                "navigation_type" => "MAT",
                "SHOW_DATE_SEARCH" => 1,
                "get_contact_field" => "RECEIVER",
                "show_all_results" => "1",
                "CHECKBOX" => 1
            )
        ),
        "kundli" => array(
            "R" => array(
                "self" => "RECEIVER",
                "contact" => "USER",
                "type" => "R",
                "flag" => "K",
                "stype" => "32",
                "navigation_type" => "KUN",
                "SHOW_DATE_SEARCH" => 0,
                "get_contact_field" => "RECEIVER",
                "show_all_results" => "1",
                "CHECKBOX" => 1
            )
        ),
        "visitors" => array(
            "R" => array(
                "self" => "VIEWED",
                "contact" => "VIEWER",
                "type" => "R",
                "flag" => "V",
                "stype" => "5",
                "stype_mobile"=>"WV",
                "navigation_type" => "VIS",
                "SHOW_DATE_SEARCH" => 1,
                "CHECKBOX" => 1
            )
        ),
        "eoi" => array(
            "R" => array(
                "self" => "RECEIVER",
                "contact" => "SENDER",
                "type" => "R",
                "flag" => "I",
                "navigation_type" => "EOI",
                "SHOW_CATEGORY_SEARCH" => 1,
                "CHECKBOX" => 1,
                "contactFlag" => "'I'",
                "filteredNotIn" => "'Y'"
            ),
            "M" => array(
                "self" => "SENDER",
                "contact" => "RECEIVER",
                "type" => "M",
                "flag" => "I",
                "navigation_type" => "REM",
                "contactFlag" => "'I'"
            )
        ),
        "eeoi" => array(
            "R" => array(
                "self" => "RECEIVER",
                "contact" => "SENDER",
                "type" => "R",
                "flag" => "I",
                "navigation_type" => "EEOI",
                "SHOW_CATEGORY_SEARCH" => 1,
                "CHECKBOX" => 1,
                "contactFlag" => "'I'",
                "filteredNotIn" => "'Y'"
            ),
        ),
        /*"archive_eoi"=>array(
        "R"=>array(
        "self"=>"RECEIVER",
        "contact"=>"SENDER",
        "type"=>"R",
        "flag"=>"I",
        "navigation_type"=>"ARC",
        "SHOW_CATEGORY_SEARCH"=>1,
        "archive"=>1,
        "CHECKBOX"=>1,
        "contactFlag"=>"'I'",
        ),
        ),*/
        "aeoi" => array(
            "R" => array(
                "self" => "RECEIVER",
                "contact" => "SENDER",
                "type" => "R",
                "flag" => "I",
                "navigation_type" => "EOI",
                "SHOW_CATEGORY_SEARCH" => 1,
                "CHECKBOX" => 1,
                "contactFlag" => "'I'",
                "filteredNotIn" => "'Y'"
            ),
        ),
        "messages" => array(
            "R" => array(
                "self" => "RECEIVER",
                "contact" => "SENDER",
                "type" => "R",
                "flag" => "MSG",
                "navigation_type" => "MES_R",
                "table_name" => "newjs.MESSAGE_LOG",
                "time_field" => "DATE AS TIME",
                "get_contact_field" => "SENDER"
            ),
            "M" => array(
                "self" => "SENDER",
                "contact" => "RECEIVER",
                "type" => "M",
                "flag" => "MSG",
                "navigation_type" => "MES_M",
                "table_name" => "newjs.MESSAGE_LOG",
                "time_field" => "DATE AS TIME",
                "get_contact_field" => "RECEIVER"
            )
        ),
        "filtered_eoi" => array(
            "R" => array(
                "self" => "RECEIVER",
                "contact" => "SENDER",
                "type" => "R",
                "flag" => "FI",
                "navigation_type" => "FIL",
                "CHECKBOX" => 1,
                "contactFlag" => "'I'",
                "SHOW_CATEGORY_SEARCH" => 1,
                "filteredIn" => "'Y'"
            )
        ),
        "callnow" => array(
            "A" => array(
                "self" => "SENDER_RECEIVER",
                "contact" => "SENDER_RECEIVER",
                "type" => "RMI",
                "flag" => "CL",
                "navigation_type" => "CALL",
                "contactFlag" => "'CL'"
            ),
            "R" => array(
                "self" => "RECEIVER_PID",
                "contact" => "CALLER_PID",
                "type" => "R",
                "flag" => "CL",
                "navigation_type" => "CALL",
                "contactFlag" => "'CL'"
            ),
            "M" => array(
                "self" => "RECEIVER_PID",
                "contact" => "CALLER_PID",
                "type" => "M",
                "flag" => "CL",
                "navigation_type" => "CALL",
                "contactFlag" => "'CL'"
            ),
            "I" => array(
                "self" => "CALLER_PID",
                "contact" => "RECEIVER_PID",
                "type" => "I",
                "flag" => "CL",
                "navigation_type" => "CALL",
                "contactFlag" => "'CL'"
            )
        ),
        "viewed_contacts" => array(
            "R" => array(
                "self" => "VIEWER",
                "contact" => "VIEWED",
                "type" => "VC",
                "flag" => "VC",
                "stype" => "26",
                "stype_mobile"=>"M26",
                "navigation_type" => "VC",
                "show_all_results" => "1",
                "contactFlag" => "'VC'"
            )
        ),
        "intro_call" => array(
            "R" => array(
                "self" => "SENDER_RECEIVER",
                "contact" => "SENDER_RECEIVER",
                "type" => "IC",
                "flag" => "IC",
                "stype" => "28",
                "navigation_type" => "IC",
                "contactFlag" => "'IC'"
            )
        ),
        "viewed_contacts_by" => array(
            "R" => array(
                "self" => "VIEWED",
                "contact" => "VIEWER",
                "type" => "VCB",
                "flag" => "VCB",
                "stype" => "27",
                "stype_mobile"=>"M27",
                "navigation_type" => "VCB",
                "show_all_results" => "1",
                "contactFlag" => "'VCB'"
            )
        ),
        "phonebook_contacts_viewed" => array(
            "M" => array(
                "self" => "VIEWED",
                "contact" => "VIEWER",
                "type" => "PCV",
                "flag" => "PCV",
                "stype" => "27",
                "stype_mobile"=>"M27",
                "navigation_type" => "PCV",
                "show_all_results" => "1",
                "contactFlag" => "'PCV'"
            )
            ),
        "contact_viewers" => array(
            "R" => array(
                "self" => "VIEWED",
                "contact" => "VIEWER",
                "type" => "CVS",
                "flag" => "CVS",
                "stype" => "CVS",
                "stype_mobile"=>"",
                "navigation_type" => "CVSM",
                "show_all_results" => "1",
                "contactFlag" => "'CVS'"
            )
        )

        
    );
    return $item[$page][$filter];
}

function sortByTime($pageDetail)
{
    $sort_array = $pageDetail["ALLOW_PROFILES"];
    $ARC_SAX    = $pageDetail["ARC_SAX"];
    //global $ARC_SAX;
    {
        
        if (count($sort_array) >= 1) {
            foreach ($sort_array as $key => $val) {
                $order[$val] = $ARC_SAX[$val]['TIME'];
            }
            
            arsort($order);
            
            
            foreach ($order as $key => $val) {
                $sort_a[] = $key;
            }
            return $sort_a;
        }
    }
    return array();
    
}

function sortByPhotoLogicMatchAlert($pageDetail)
{
	$sort_array = $pageDetail["ALLOW_PROFILES"];
	$ARC_SAX    = $pageDetail["ARC_SAX"];
	$str = "PROFILEID IN (";
	foreach($sort_array as $key=>$value)
	{
		$str = $str.$value.",";
		
	}
	$str = substr($str, 0, -1);
	$str = $str.")";
	$sql = "SELECT PROFILEID, HAVEPHOTO,PHOTO_DISPLAY FROM newjs.JPROFILE WHERE ".$str;
	$res = mysql_query_decide($sql) or logError("Due to a temporary problem, your request could not be processed. Please try again after a couple of minutes", $sql, "ShowErrTemplate");
	if (mysql_num_rows($res)) {
		while ($row = mysql_fetch_assoc($res)) {
			$result[$row["PROFILEID"]] = $row;
		}
	}
	foreach ($result as $key=>$value)
	{
		$result[$key]["TIME"] = $ARC_SAX[$key]["TIME"];
	}
	usort($result, function ($a, $b) {
		   $sort = -1;
			if ($a['TIME'] < $b['TIME'])
			{
				$sort = 1;
			}
			if ($a['TIME'] == $b['TIME'])
			{
				if($b['HAVEPHOTO'] == "Y" && ($a["HAVEPHOTO"] == "U" || $a["HAVEPHOTO"] == "N" || $a["HAVEPHOTO"] == ""))
				{
					$sort = 1;
				}
				if($b['HAVEPHOTO'] == "U" && ( $a["HAVEPHOTO"] == "N" || $a["HAVEPHOTO"] == ""))
				{
					$sort = 1;
				}
				if($a['HAVEPHOTO'] == $b['HAVEPHOTO'])
				{
					if($b["PHOTO_DISPLAY"] == "A" && $a["PHOTO_DISPLAY"] == "C")
					{
						$sort = 1;
					}
					if($a["PHOTO_DISPLAY"] == $b["PHOTO_DISPLAY"])
					{
						if($a["PROFILEID"] < $b["PROFILEID"])
							$sort = 1;
					}
				}
			}
			return $sort;});
			
		foreach ($result as $key=>$value)
			$return[] = $value["PROFILEID"];
    return $return;
	
	
}


function getStampDate($date)
{
    $date_time_split = explode(" ", $date);
    $date_split      = explode("-", $date_time_split[0]);
    $stamp_start     = mktime(0, 0, 0, $date_split[1], $date_split[2], $date_split[0]);
    return $stamp_start;
}

function getting_profiles_based_on_type($item,$offlineCallCountArr)
{
    $subscription      = $item["SUBSCRIPTION"];
    $NUDGES            = array();
    $ALLOW_PROFILES    = array();
    $ARC_SAX           = array();
    $eoi_viewed_date   = array();
    $contact           = $item["contact"];
    $self              = $item["self"];
    $self_profileid    = $item["self_profileid"];
    $flag              = $item["flag"];
    $type              = $item["type"];
    //$archive = $item["archive"];
    $filterBy          = $item["filterBy"];
    $date_search       = $item["date_search"];
    $start_date        = $item["start_date"];
    $end_date          = $item["end_date"];
    $contactFlag       = $item["contactFlag"];
    $filteredNotIn     = $item["filteredNotIn"];
    $filteredIn        = $item["filteredIn"];
    $table_name        = $item["table_name"];
    $time_field        = $item["time_field"];
    $get_contact_field = $item["get_contact_field"];
    //Archive time clause removed
    $time_clause       = "";
    $day_90            = mktime(0, 0, 0, date("m"), date("d") - 90, date("Y")); // To get the time for back 90 days
    $back_90_days      = date("Y-m-d", $day_90);
    if ($date_search == 1) {
        $stamp_start = getStampDate($start_date);
        $stamp_end   = getStampDate($end_date);
    }
    //Sharding done on CONTACTS by Neha Verma
    if ($flag == "A" || $flag == "I" || $flag == "FI") {
        if ($flag == "I") {
            /*****90 days functionality added****/
            if ($type == "R")
                $time_clause = "TIME>='$back_90_days 00:00:00'";
            /*elseif($type=='M')
            {
            $eoi_date = "";
            $mysqlObj=new Mysql;
            $myDbName=getProfileDatabaseConnectionName($self_profileid,'',$mysqlObj);
            $myDb=$mysqlObj->connect("$myDbName");
            $sql = "SELECT VIEWER, DATE FROM newjs.EOI_VIEWED_LOG WHERE VIEWED = '$self_profileid'";
            $res = mysql_query($sql, $myDb);
            while($row=mysql_fetch_array($res))
            {
            $year=substr($row['DATE'],0,4);
            $month=substr($row['DATE'],5,2);
            $day=substr($row['DATE'],8,2);
            $eoi_viewed_date[$row['VIEWER']]=my_format_date($day,$month,$year,1);
            }
            }*/
        } elseif ($flag == "FI" && $type = "R")
            $time_clause = "TIME>='$back_90_days 00:00:00'";
        if ($self == "RECEIVER" || $self == "SENDER") {
            if ($self == 'RECEIVER') {
                $contactResult = getResultSet("$contact,CONTACTID,MSG_DEL,TIME,COUNT,SEEN", "", "", $self_profileid, "", $contactFlag, '', $time_clause, "", "", "", "", "", "", "", "", "", $filteredIn, $filteredNotIn);
            } elseif ($self == 'SENDER') {
                $contactResult = getResultSet("$contact,CONTACTID,MSG_DEL,TIME,COUNT,SEEN", $self_profileid, "", "", "", $contactFlag, '', $time_clause);
                
            }
            if (is_array($contactResult)) {
                $titleCount = 0;
                foreach ($contactResult as $key => $value) {
                    $contact_value = $contactResult[$key][$contact];
                    if ($filterBy == "viewed") {
                        if ($contactResult[$key]["SEEN"] == 'Y') {
                            $ALLOW_PROFILES[]                = $contact_value;
                            $ARC_SAX[$contact_value]         = $contactResult[$key];
                            $eoi_viewed_date[$contact_value] = $contactResult[$key]["TIME"];
                        }
                    } elseif ($filterBy == "unViewed") {
                        if ($contactResult[$key]["SEEN"] != 'Y') {
                            $ALLOW_PROFILES[]        = $contact_value;
                            $ARC_SAX[$contact_value] = $contactResult[$key];
                        }
                    } else {
                        $ALLOW_PROFILES[]        = $contact_value;
                        $ARC_SAX[$contact_value] = $contactResult[$key];
                        if ($type == "M" && $contactResult[$key]["SEEN"] == 'Y')
                            $eoi_viewed_date[$contact_value] = $contactResult[$key]["TIME"];
                        if ($type == "R" && $contactResult[$key]["SEEN"] != 'Y')
                            $new_count++;
                    }
                    $titleCount++;
                }
            }
            if ($filterBy)
                $item["titleCount"] = $titleCount;
        } elseif ($self == "SENDER_RECEIVER") {
            $contactResult = getResultSet("SENDER,CONTACTID,MSG_DEL,TIME,COUNT,SEEN", "", "", $self_profileid, "", "'$flag'", '', $time_clause);
            if (is_array($contactResult)) {
                foreach ($contactResult as $key => $value) {
                    $contact_value           = $contactResult[$key]["SENDER"];
                    $ALLOW_PROFILES[]        = $contact_value;
                    $ARC_SAX[$contact_value] = $contactResult[$key];
                }
            }
            $contactResult = getResultSet("RECEIVER,CONTACTID,MSG_DEL,TIME,COUNT,SEEN", $self_profileid, "", "", "", "'$flag'", '', $time_clause);
            if (is_array($contactResult)) {
                foreach ($contactResult as $key => $value) {
                    $contact_value           = $contactResult[$key]["RECEIVER"];
                    $ALLOW_PROFILES[]        = $contact_value;
                    $ARC_SAX[$contact_value] = $contactResult[$key];
                    if ($contactResult[$key]["SEEN"] != 'Y' && $flag == "A")
                        $new_count++;
                }
            }
        }
    } elseif ($flag == "IC") {
        $sql = "SELECT DISTINCT(MATCH_ID) AS RECEIVER, REQUEST_DATE AS TIME,CALL_STATUS,CALL_DATE,TELECALLER,CALL_STATUS FROM Assisted_Product.AP_CALL_HISTORY WHERE PROFILEID='$self_profileid' AND CALL_STATUS IN ('Y','N') ORDER BY REQUEST_DATE";
        $res = mysql_query_decide($sql) or logError("Due to a temporary problem, your request could not be processed. Please try again after a couple of minutes", $sql, "ShowErrTemplate");
        if (mysql_num_rows($res)) {
            while ($row = mysql_fetch_assoc($res)) {
		$contact_value                   = $row["RECEIVER"];
		$ALLOW_PROFILES[]                = $contact_value;
		$ARC_SAX[$contact_value]         = $row;
		$ARC_SAX[$contact_value]["SEEN"] = "Y";
            }
        }
    } elseif ($flag == "C") {
        $ALLOW_PROFILES = array();
        $sql            = "SELECT SENDER,TIMEOFINSERTION AS TIME,SEEN FROM userplane.CHAT_REQUESTS WHERE RECEIVER='$self_profileid'";
        $res = mysql_query_decide($sql) or logError("Due to a temporary problem, your request could not be processed. Please try again after a couple of minutes", $sql, "ShowErrTemplate");
        if (mysql_num_rows($res)) {
            while ($row = mysql_fetch_assoc($res)) {
                $contact_value = $row["SENDER"];
                if (!in_array($contact_value, $ALLOW_PROFILES)) {
                    $ALLOW_PROFILES[]        = $contact_value;
                    $ARC_SAX[$contact_value] = $row;
                    if ($row["SEEN"] != 'Y')
                        $new_count++;
                }
            }
        }
        /*$ALLOW_PROFILES_TEMP = array();
        $sql                 = "SELECT RECEIVER,TIMEOFINSERTION AS TIME,SEEN FROM userplane.CHAT_REQUESTS WHERE SENDER='$self_profileid'";
        $res = mysql_query_decide($sql) or logError("Due to a temporary problem, your request could not be processed. Please try again after a couple of minutes", $sql, "ShowErrTemplate");
        if (mysql_num_rows($res)) {
            while ($row = mysql_fetch_assoc($res)) {
                $contact_value = $row["RECEIVER"];
                if (!in_array($contact_value, $ALLOW_PROFILES_TEMP)) {
                    $row["SEEN"]             = "Y";
                    $ALLOW_PROFILES_TEMP[]   = $contact_value;
                    $ALLOW_PROFILES[]        = $contact_value;
                    $ARC_SAX[$contact_value] = $row;
                }
            }
        }*/
    } elseif ($flag == "VC") {
        $sql = "SELECT VIEWED AS RECEIVER, DATE AS TIME FROM jsadmin.VIEW_CONTACTS_LOG WHERE VIEWER='$self_profileid' AND SOURCE='".CONTACT_ELEMENTS::CALL_DIRECTLY_TRACKING."'";
        $res = mysql_query_decide($sql) or logError("Due to a temporary problem, your request could not be processed. Please try again after a couple of minutes", $sql, "ShowErrTemplate");
        if (mysql_num_rows($res)) {
            while ($row = mysql_fetch_assoc($res)) {
                $row["SEEN"]             = "Y";
                $contact_value           = $row["RECEIVER"];
                $ALLOW_PROFILES[]        = $contact_value;
                $ARC_SAX[$contact_value] = $row;
            }
        }
    } elseif ($flag == "VCB") {
         $mysqlObj = new Mysql;
            $myDbName = getProfileDatabaseConnectionName($self_profileid, '', $mysqlObj);
            $myDb     = $mysqlObj->connect("$myDbName");
        $DECLINED_PROFILES = getCancelDeclinedContacts($self_profileid, '', $mysqlObj, $myDb);
                
        $sql = "SELECT VIEWER AS SENDER, SEEN,DATE AS TIME FROM jsadmin.VIEW_CONTACTS_LOG WHERE VIEWED='$self_profileid' AND SOURCE='".CONTACT_ELEMENTS::CALL_DIRECTLY_TRACKING."'";
        $res = mysql_query_decide($sql) or logError("Due to a temporary problem, your request could not be processed. Please try again after a couple of minutes", $sql, "ShowErrTemplate");
        if (mysql_num_rows($res)) {
            while ($row = mysql_fetch_assoc($res)) {
                $contact_value           = $row["SENDER"];
                $ALLOW_PROFILES_ALL[]        = $contact_value;
                $ARC_SAX_ALL[$contact_value] = $row;
                
            }

  foreach ($ALLOW_PROFILES_ALL as $key => $val) {
                        if (!in_array($val, $DECLINED_PROFILES)) {
                            $ALLOW_PROFILES[] = $val;
                            $ARC_SAX[$val]    = $ARC_SAX_ALL[$val];
                            if ($ARC_SAX[$val]["SEEN"] != "Y")
                                $new_count++;
                        }
                    }


        }
    }
    //elseif($flag=="F" || $flag=="C" || $flag=="IG" || $flag=="MSG")
        elseif ($flag == "F" || $flag == "IG" || $flag == "MSG") {
        $myDbName = '';
        /*
        if($flag=="F")
        {
        $table_name="newjs.BOOKMARKS";
        $time_field="BKDATE AS TIME,BKNOTE";
        }
        if($flag=="C")
        {
        $time_field="TIMEOFINSERTION AS TIME";
        $table_name="userplane.CHAT_REQUESTS";
        }
        if($flag=="IG")
        {
        $time_field="DATE AS TIME";
        $table_name="newjs.IGNORE_PROFILE";
        }
        */
        if ($flag == "MSG") {
            $mysqlObj = new Mysql;
            $myDbName = getProfileDatabaseConnectionName($self_profileid, '', $mysqlObj);
            $myDb     = $mysqlObj->connect("$myDbName");
            /*
            if($flag=="MSG")
            {
            $table_name="newjs.MESSAGE_LOG";
            $time_field="DATE AS TIME";
            }
            */
        }
        $self_field    = $self;
        /*if($type=="M")
        $get_contact_field="RECEIVER";
        elseif($type=="R")
        $get_contact_field="SENDER";*/
        $contact_field = $contact . " AS " . $get_contact_field;
        $sql           = "SELECT $contact_field,$time_field,SEEN FROM $table_name WHERE $self_field='$self_profileid'";
        if ($flag == "MSG")
            $sql .= " AND IS_MSG='Y' AND TYPE='R' ORDER BY ID DESC";
        if ($myDbName) {
            $res = $mysqlObj->executeQuery($sql, $myDb);
            if ($mysqlObj->numRows($res)) {
                $ALLOW_PROFILES_ALL = array();
                while ($row = $mysqlObj->fetchAssoc($res)) {
                    $contact_value = $row[$get_contact_field];
                    if ($flag == "MSG") {
                        if ($type != "R") {
                            if (!in_array($contact_value, $ALLOW_PROFILES)) {
                                $ALLOW_PROFILES[]        = $contact_value;
                                $ARC_SAX[$contact_value] = $row;
                            }
                        } else {
                            if (!in_array($contact_value, $ALLOW_PROFILES_ALL)) {
                                $ALLOW_PROFILES_ALL[]        = $contact_value;
                                $ARC_SAX_ALL[$contact_value] = $row;
                            }
                        }
                    } else {
                        $ALLOW_PROFILES[]        = $contact_value;
                        $ARC_SAX[$contact_value] = $row;
                        if ($type == "R" && $row["SEEN"] != 'Y')
                            $new_count++;
                    }
                }
                if ($flag == "MSG" && $type == "R") {
                    $DECLINED_PROFILES = getCancelDeclinedContacts($self_profileid, $ALLOW_PROFILES_ALL, $mysqlObj, $myDb);
                    foreach ($ALLOW_PROFILES_ALL as $key => $val) {
                        if (!in_array($val, $DECLINED_PROFILES)) {
                            $ALLOW_PROFILES[] = $val;
                            $ARC_SAX[$val]    = $ARC_SAX_ALL[$val];
                            if ($ARC_SAX[$val]["SEEN"] != "Y")
                                $new_count++;
                        }
                    }
                }
            }
            
        } else {
            $ALLOW_PROFILES = array();
            $res = mysql_query_decide($sql) or logError("Due to a temporary problem, your request could not be processed. Please try again after a couple of minutes", $sql, "ShowErrTemplate");
            if (mysql_num_rows($res)) {
                while ($row = mysql_fetch_assoc($res)) {
                    $contact_value = $row[$get_contact_field];
                    if (!in_array($contact_value, $ALLOW_PROFILES)) {
                        $ALLOW_PROFILES[]        = $contact_value;
                        $ARC_SAX[$contact_value] = $row;
                        if ($type == "R" && $row["SEEN"] != 'Y')
                            $new_count++;
                    }
                }
            }
        }
        
    }
    if ($flag == "M") {
	if(JsConstants::$alertServerEnable) {
        $dbslave       = connect_slave81();
        /*
        if($type=="R")
        $get_contact_field="SENDER";
        elseif($type=="M")
        $get_contact_field="RECEIVER";
        */
        $contact_field = $contact . " AS RECEIVER";
        $sql           = "SELECT $contact_field,DATE AS TIME FROM matchalerts.LOG USE INDEX(RECEIVER) WHERE $self ='$self_profileid' ORDER BY DATE DESC";
        $res = mysql_query_decide($sql, $dbslave) or logError("Due to a temporary problem, your request could not be processed. Please try again after a couple of minutes", $sql, "continue");
        $ALLOW_PROFILES_ALL = array();
        if (mysql_num_rows($res)) {
            $i = 0;
            while ($row = mysql_fetch_assoc($res)) {
                if ($i == 0) {
                    $maxTime = get_date_from_timestamp($row["TIME"], 2005);
                    $i++;
                }
                $row["TIME"] = get_date_from_timestamp($row["TIME"], 2005);
                $row['SEEN'] = 'Y';
                if ($maxTime == $row["TIME"])
                    $row['SEEN'] = "";
                if ($date_search == 1) {
                    $date_time_split = explode(" ", $row["TIME"]);
                    $date_split      = explode("-", $date_time_split[0]);
                    $stamp           = mktime(0, 0, 0, $date_split[1], $date_split[2], $date_split[0]);
                    if ($stamp >= $stamp_start && $stamp <= $stamp_end) {
                        $contact_value               = $row[$get_contact_field];
                        $ALLOW_PROFILES_ALL[]        = $contact_value;
                        $ARC_SAX_ALL[$contact_value] = $row;
                    }
                } else {
                    $contact_value               = $row[$get_contact_field];
                    $ALLOW_PROFILES_ALL[]        = $contact_value;
                    $ARC_SAX_ALL[$contact_value] = $row;
                }
            }
            $contactedProfiles = getContactedProfiles($self_profileid, $ALLOW_PROFILES_ALL);
            foreach ($ALLOW_PROFILES_ALL as $key => $val) {
                if (!in_array($val, $contactedProfiles)) {
                    $ALLOW_PROFILES[] = $val;
                    $ARC_SAX[$val]    = $ARC_SAX_ALL[$val];
                    if ($ARC_SAX[$val]["SEEN"] != "Y")
                        $new_count++;
                }
            }
        }
      }
     } else if ($flag == "K") {
	if(JsConstants::$alertServerEnable) {
        $dbslave = connect_slave81();
        
        $sql = "SELECT MAX(MAIL_DT) AS DATE FROM kundli_alert.KUNDLI_CONTACT_CENTER WHERE PROFILEID = " . $self_profileid;
        
        $result = mysql_query_decide($sql, $dbslave) or logError("Due to a temporary problem, your request could not be processed. Please try again after a couple of minutes", $sql, "continue");
        $row            = mysql_fetch_assoc($result);
        $last_mailer_dt = $row["DATE"];
        
        $ALLOW_PROFILES_ALL = array();
        $statement          = "SELECT MATCHID AS RECEIVER,MAIL_DT AS TIME FROM kundli_alert.KUNDLI_CONTACT_CENTER WHERE PROFILEID = " . $self_profileid . " ORDER BY MAIL_DT DESC, VENUS DESC , MARS DESC , GUNA DESC, ENTRY_DT DESC";
        $result = mysql_query_decide($statement, $dbslave) or logError("Due to a temporary problem, your request could not be processed. Please try again after a couple of minutes", $statement, "continue");
        
        while ($row = mysql_fetch_assoc($result)) {
            if ($last_mailer_dt == $row["TIME"])
                $row["SEEN"] = "";
            else
                $row["SEEN"] = "Y";
            $contact_value               = $row["RECEIVER"];
            $ALLOW_PROFILES_ALL[]        = $contact_value;
            $ARC_SAX_ALL[$contact_value] = $row;
        }
        $contactedProfiles = getContactedProfiles($self_profileid, $ALLOW_PROFILES_ALL);
        foreach ($ALLOW_PROFILES_ALL as $key => $val) {
            if (!in_array($val, $contactedProfiles)) {
                $ALLOW_PROFILES[] = $val;
                $ARC_SAX[$val]    = $ARC_SAX_ALL[$val];
                if ($ARC_SAX[$val]["SEEN"] != "Y")
                    $new_count++;
            }
        }
      }
    } elseif ($flag == "V") {
        include_once "../classes/Jpartner.class.php";
        $result = visitors($self_profileid, $item["GENDER"], 1);
        /*if(@mysql_num_rows($result))
        {
        while($row=mysql_fetch_assoc($result))
        {
        if($date_search==1)
        {
        $date_time_split=explode(" ",$row["TIME"]);
        $date_split=explode("-",$date_time_split[0]);
        $stamp=mktime(0,0,0,$date_split[1],$date_split[2],$date_split[0]);
        if($stamp>=$stamp_start && $stamp<=$stamp_end)
        {
        $contact_value=$row["VISITORS"];
        $ALLOW_PROFILES[]=$contact_value;
        if($type=="R")
        $row["SENDER"]=$contact_value;
        $ARC_SAX[$contact_value]=$row;
        }			
        }
        else
        {
        $contact_value=$row["VISITORS"];
        $ALLOW_PROFILES[]=$contact_value;
        if($type=="R")
        $row["SENDER"]=$contact_value;
        $ARC_SAX[$contact_value]=$row;
        }
        if($row["SEEN"]!='Y')
        {
        $new_visitor++;
        $new_count++;
        }
        }
        }*/
        if (is_array($result)) {
            foreach ($result as $key => $val) {
                $row             = explode(",", $val);
                $row["VISITORS"] = $row[0];
                $row["TIME"]     = $row[1];
                $row["SEEN"]     = $row[2];
                if ($date_search == 1) {
                    $date_time_split = explode(" ", $row["TIME"]);
                    $date_split      = explode("-", $date_time_split[0]);
                    $stamp           = mktime(0, 0, 0, $date_split[1], $date_split[2], $date_split[0]);
                    if ($stamp >= $stamp_start && $stamp <= $stamp_end) {
                        $contact_value    = $row["VISITORS"];
                        $ALLOW_PROFILES[] = $contact_value;
                        if ($type == "R")
                            $row["SENDER"] = $contact_value;
                        $ARC_SAX[$contact_value] = $row;
                    }
                } else {
                    $contact_value    = $row["VISITORS"];
                    $ALLOW_PROFILES[] = $contact_value;
                    if ($type == "R")
                        $row["SENDER"] = $contact_value;
                    $ARC_SAX[$contact_value] = $row;
                }
                if ($row["SEEN"] != 'Y') {
                    $new_count++;
                }
            }
        }
    } elseif ($flag == "P" || $flag == "H") {
        $contact_field = $contact . " AS " . $get_contact_field;
        $mysqlObj      = new Mysql;
        $myDbName      = getProfileDatabaseConnectionName($self_profileid, '', $mysqlObj);
        $myDb          = $mysqlObj->connect("$myDbName");
        if ($type == "R") {
            $sql = "SELECT $contact_field,DATE AS TIME,SEEN FROM $table_name WHERE $self='$self_profileid'";
            $res = $mysqlObj->executeQuery($sql, $myDb);
            if ($mysqlObj->numRows($res)) {
                while ($row = $mysqlObj->fetchAssoc($res)) {
                    $contact_value               = $row[$get_contact_field];
                    $ALLOW_PROFILES_ALL[]        = $contact_value;
                    $ARC_SAX_ALL[$contact_value] = $row;
                }
                $DECLINED_PROFILES = getCancelDeclinedContacts($self_profileid, $ALLOW_PROFILES_ALL, $mysqlObj, $myDb);
                foreach ($ALLOW_PROFILES_ALL as $key => $val) {
                    if (!in_array($val, $DECLINED_PROFILES)) {
                        $ALLOW_PROFILES[] = $val;
                        $ARC_SAX[$val]    = $ARC_SAX_ALL[$val];
                        if ($ARC_SAX[$val]["SEEN"] != "Y")
                            $new_count++;
                    }
                }
            }
        } elseif ($type == "M") {
            $sql = "SELECT IF(UPLOAD_SEEN='',DATE,UPLOAD_DATE) AS TIME,UPLOAD_SEEN,$contact_field FROM $table_name WHERE $self='$self_profileid'";
            $res = $mysqlObj->executeQuery($sql, $myDb);
            if ($mysqlObj->numRows($res)) {
                while ($row = $mysqlObj->fetchAssoc($res)) {
                    unset($row_to_assign);
                    $contact_value               = $row[$get_contact_field];
                    $ALLOW_PROFILES_ALL[]        = $contact_value;
                    $row_to_assign               = array(
                        "$get_contact_field" => $row[$get_contact_field],
                        "TIME" => $row["TIME"],
                        "SEEN" => $row["UPLOAD_SEEN"]
                    );
                    $ARC_SAX_ALL[$contact_value] = $row_to_assign;
                }
                if ($flag == "P")
                    $photoUploadDate = getPhotoUploadDate($ALLOW_PROFILES_ALL);
                $DECLINED_PROFILES = getCancelDeclinedContacts($self_profileid, $ALLOW_PROFILES_ALL, $mysqlObj, $myDb);
                foreach ($ALLOW_PROFILES_ALL as $key => $val) {
                    if (!in_array($val, $DECLINED_PROFILES)) {
                        $ALLOW_PROFILES[] = $val;
                        $ARC_SAX[$val]    = $ARC_SAX_ALL[$val];
                        if ($flag == "P") {
                            if ($ARC_SAX[$val]["SEEN"] != "" && $photoUploadDate[$val])
                                $ARC_SAX[$val]["TIME"] = $photoUploadDate[$val];
                        }
                    }
                }
            }
        }
        
        
    } /* IVR-Callnow feature added, Starts 
     * callnow flag set:CL
     * callnow types set:
     R- Rceived calls 
     M- Calls made,
     A- Include all types of call,
     U- Calls missed(not picked up) 
     */ elseif ($flag == "CL") {
        $select_fields1 = "RECEIVER_PID,SEEN,CALL_DT";
        $select_fields2 = "CALLER_PID,SEEN,CALL_DT";
        if ($type == 'RMI') {
            // Calls missed
            $callnowResultSet2 = callnowResultSet($self_profileid, $select_fields2, 'RECEIVER_PID', 'RM');
            if (is_array($callnowResultSet2)) {
                foreach ($callnowResultSet2 as $key => $value) {
                    $contact_value = $callnowResultSet2[$key]['SENDER'];
                    if (!in_array("$contact_value", $ALLOW_PROFILES)) {
                        $ALLOW_PROFILES[]        = $contact_value;
                        $ARC_SAX[$contact_value] = $callnowResultSet2[$key];
                        if ($callnowResultSet2[$key]["SEEN"] != 'Y')
                            $new_count++;
                    }
                }
            }
            // Calls made   
            $callnowResultSet1 = callnowResultSet($self_profileid, $select_fields1, 'CALLER_PID', 'I');
            if (is_array($callnowResultSet1)) {
                foreach ($callnowResultSet1 as $key => $value) {
                    $contact_value = $callnowResultSet1[$key]['RECEIVER'];
                    if (!in_array("$contact_value", $ALLOW_PROFILES)) {
                        $ALLOW_PROFILES[]        = $contact_value;
                        $ARC_SAX[$contact_value] = $callnowResultSet1[$key];
                    }
                }
            }
        } else {
            if ($type == 'I') {
                $select_fields = $select_fields1;
                $contact       = "RECEIVER";
            } else {
                $select_fields = $select_fields2;
                $contact       = "SENDER";
            }
            $callnowResultSet = callnowResultSet($self_profileid, $select_fields, $self, $type);
            if (is_array($callnowResultSet)) {
                foreach ($callnowResultSet as $key => $value) {
                    $contact_value = $callnowResultSet[$key][$contact];
                    if (!in_array("$contact_value", $ALLOW_PROFILES)) {
                        $ALLOW_PROFILES[]        = $contact_value;
                        $ARC_SAX[$contact_value] = $callnowResultSet[$key];
                        if ($callnowResultSet[$key]["SEEN"] != 'Y' && $type != 'I')
                            $new_count++;
                    }
                }
            }
        }
        
    } elseif ($flag == 'D') {
        if ($self == 'RECEIVER') {
            $new_count     = 0;
            $contactResult = getResultSet("RECEIVER,CONTACTID,MSG_DEL,TIME,COUNT,SEEN", $self_profileid, "", "", "", "'E','C'", '', $time_clause);
            if (is_array($contactResult)) {
                foreach ($contactResult as $key => $value) {
                    $contact_value           = $contactResult[$key]["RECEIVER"];
                    $ALLOW_PROFILES[]        = $contact_value;
                    $ARC_SAX[$contact_value] = $contactResult[$key];
                }
            }
            $contactResult = getResultSet("SENDER,CONTACTID,MSG_DEL,TIME,COUNT,SEEN", "", "", $self_profileid, "", "'D'", '', $time_clause);
            if (is_array($contactResult)) {
                foreach ($contactResult as $key => $value) {
                    $contact_value           = $contactResult[$key]["SENDER"];
                    $ALLOW_PROFILES[]        = $contact_value;
                    $ARC_SAX[$contact_value] = $contactResult[$key];
                }
            }
        } elseif ($self == 'SENDER') {
            $contactResult = getResultSet("SENDER,CONTACTID,MSG_DEL,TIME,COUNT,SEEN", "", "", $self_profileid, "", "'E','C'", '', $time_clause);
            if (is_array($contactResult)) {
                foreach ($contactResult as $key => $value) {
                    $contact_value           = $contactResult[$key]["SENDER"];
                    $ALLOW_PROFILES[]        = $contact_value;
                    $ARC_SAX[$contact_value] = $contactResult[$key];
                    if ($contactResult[$key]["SEEN"] != 'Y')
                        $new_count++;
                }
            }
            $contactResult = getResultSet("RECEIVER,CONTACTID,MSG_DEL,TIME,COUNT,SEEN", $self_profileid, "", "", "", "'D'", '', $time_clause);
            if (is_array($contactResult)) {
                foreach ($contactResult as $key => $value) {
                    $contact_value           = $contactResult[$key]["RECEIVER"];
                    $ALLOW_PROFILES[]        = $contact_value;
                    $ARC_SAX[$contact_value] = $contactResult[$key];
                    if ($contactResult[$key]["SEEN"] != 'Y')
                        $new_count++;
                }
            }
        }
        
    }
    /* IVR-Callnow feature, Ends */
    
    $sqlOffline = getOfflineMatchSql($item);
    if ($sqlOffline) {
        $res = mysql_query_decide($sqlOffline) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes", $sql, "ShowErrTemplate");
        
        while ($row = mysql_fetch_array($res)) {
            $NUDGES[] = $row[0];
            if ($filterBy == "viewed") {
                if ($row["SEEN"] == "Y") {
                    $ALLOW_PROFILES[]         = $row[0];
                    $arc_sax_id               = $row[0];
                    $ARC_SAX[$arc_sax_id]     = $row;
                    $eoi_viewed_date[$row[0]] = $row["TIME"];
                }
            }
            if ($filterBy == "unViewed") {
                if ($row["SEEN"] != "Y") {
                    $ALLOW_PROFILES[]     = $row[0];
                    $arc_sax_id           = $row[0];
                    $ARC_SAX[$arc_sax_id] = $row;
                }
            } else {
                $ALLOW_PROFILES[]     = $row[0];
                $arc_sax_id           = $row[0];
                $ARC_SAX[$arc_sax_id] = $row;
            }
            //$INSIDE_30[]=$row['SENDER'];
            //$ARCHIVES[]=$row['SENDER'];
            if ($flag == 'A' && $type != 'M' && $row["SEEN"] != 'Y')
                $new_count++;
            elseif ($flag == 'D' && $type == 'R' && $row["SEEN"] != 'Y')
                $new_count++;
            elseif ($type == "R" && $flag == "I" && $row["SEEN"] != 'Y')
                $new_count++;
        }
        if (count($NUDGES))
            $item["titleCount"] = $item["titleCount"] ? ($item["titleCount"] + count($NUDGES)) : $item["titleCount"];
    }
    $item["NUDGES"]          = $NUDGES;
    $item["ALLOW_PROFILES"]  = $ALLOW_PROFILES;
    $item["ARC_SAX"]         = $ARC_SAX;
    $item["new_count"]       = $new_count;
    $item["eoi_viewed_date"] = $eoi_viewed_date;
    $item["titleCount"]      = $item["titleCount"] ? $item["titleCount"] : count($ALLOW_PROFILES);
    if($item['page'] != 'ignore')
    {
        $ignoreProfiles = ignore_profile($item);
        if(is_array($ignoreProfiles))
        foreach($ignoreProfiles as $value)
        {
            unset($item["ALLOW_PROFILES"][array_search($value,$item["ALLOW_PROFILES"])]);
            unset($item['ARC_SAX'][$value]);
        }
        $count = 0;
        foreach($item['ARC_SAX'] as $value)
        {
            if($item['page'] == 'accept' && $item['filter'] == 'A')
            {
                if(isset($value["RECEIVER"]) && $value['SEEN']!='Y')
                    $count++;
            }
            else
            {   
                if($value['SEEN']!='Y') 
                    $count++;
            }
        } 
        $item["new_count"] = $count;
        $item["titleCount"]      = count($item['ALLOW_PROFILES']);
    }
    return $item;
    //echo "<br>allow profiles   ".count($ALLOW_PROFILES)."<br>";print_r($ALLOW_PROFILES);
    //echo "<br>arc sax ".count($ARC_SAX)."<br>";print_r($ARC_SAX);
}

function getTeleCallerComments($self_profileid, $matchIdArr)
{
    $callerComments = array();
    if ($self_profileid && $matchIdArr) {
        $matchIdStr = implode(",", $matchIdArr);
        $sql        = "SELECT DISTINCT(MATCH_ID),COMMENTS,ADDED_ON FROM Assisted_Product.AP_MATCH_COMMENTS WHERE PROFILEID='$self_profileid' AND MATCH_ID IN ($matchIdStr)";
        $res = mysql_query_decide($sql) or logError("Due to a temporary problem, your request could not be processed. Please try again after a couple of minutes", $sql, "ShowErrTemplate");
        while ($row = mysql_fetch_array($res)) {
            $callerComments[$row["MATCH_ID"]] = "<b>Comments:</b> " . stripslashes($row["COMMENTS"]);
	    $callerComments['lastCallDate']=$row['ADDED_ON'];
        }
    }
    return $callerComments;
}

function getExDetailForm($profileIdArr)
{
    $profileIdNewArr = array();
    if ($profileIdArr) {
        $profileIdStr = implode(",", $profileIdArr);
        $sql          = "SELECT `PROFILEID` from Assisted_Product.AP_EFORM_DETAILS WHERE `PROFILEID` IN ($profileIdStr)";
        $res = mysql_query_decide($sql) or logError("Due to a temporary problem, your request could not be processed. Please try again after a couple of minutes", $sql, "ShowErrTemplate");
        while ($row = mysql_fetch_array($res)) {
            $profileIdNewArr[] = $row["PROFILEID"];
        }
    }
    return $profileIdNewArr;
}

function getPhotoUploadDate($receiverProfiles)
{
    $photoUpload = array();
    $profileids  = implode(",", $receiverProfiles);
    $sql         = "select PROFILEID,PHOTODATE from newjs.JPROFILE where  activatedKey=1 and PROFILEID IN ($profileids)";
    $res         = mysql_query_decide($sql);
    while ($row = mysql_fetch_array($res)) {
        $photoUpload[$row["PROFILEID"]] = $row["PHOTODATE"];
    }
    return $photoUpload;
}

function getOfflineMatchSql($item)
{
    $day_90         = mktime(0, 0, 0, date("m"), date("d") - 90, date("Y")); // To get the time for previous day
    $back_90_days   = date("Y-m-d", $day_90);
    $type           = $item["type"];
    $flag           = $item["flag"];
    $self_profileid = $item["self_profileid"];
    //$archive = $item["archive"];
    $contact        = $item["contact"];
    if ($type == "R" && $flag == "I") // && $archive!=1)
        $sql = "select `PROFILEID` as `SENDER`,'blank','blank',`MATCH_DATE` as `TIME`,'blank','15',SEEN from jsadmin.OFFLINE_MATCHES where MATCH_ID='$self_profileid' and STATUS IN('N','NNOW') AND MATCH_DATE>='$back_90_days' AND MATCH_DATE<=NOW() and SHOW_ONLINE='Y'";
    if ($type == 'M' && $flag == 'I') {
        $sql = "select `PROFILEID` as $contact,'blank','blank',`MATCH_DATE` as `TIME`,'blank','15',SEEN from jsadmin.OFFLINE_MATCHES where MATCH_ID='$self_profileid' and STATUS IN('NACC','SL') and SHOW_ONLINE='Y'";
    }
    if ($type == "M" && $flag == 'D')
        $sql = "select `PROFILEID` as $contact,'blank','blank',`MATCH_DATE` as `TIME`,'blank','15',SEEN from jsadmin.OFFLINE_MATCHES where MATCH_ID='$self_profileid' and STATUS IN ('NREJ','NNREJ') and SHOW_ONLINE='Y'";
    if ($type != "M" && $flag == 'A') {
        $offline_contact = "RECEIVER";
        $sql             = "select `PROFILEID` as $offline_contact,'blank','blank',`MATCH_DATE` as `TIME`,'blank','15',SEEN from jsadmin.OFFLINE_MATCHES where MATCH_ID='$self_profileid' and STATUS='ACC' and SHOW_ONLINE='Y'";
    }
    if ($type == 'R' && $flag == 'D')
        $sql = "select `PROFILEID` as $contact,'blank','blank',`MATCH_DATE` as `TIME`,'blank','15',SEEN from jsadmin.OFFLINE_MATCHES where MATCH_ID='$self_profileid' and STATUS='REJ' and SHOW_ONLINE='Y'";
    return $sql;
}

function sort_profiles($pageDetail)
{
    $ALLOW_PROFILES = $pageDetail["ALLOW_PROFILES"];
    $ARC_SAX        = $pageDetail["ARC_SAX"];
    $self_profileid = $pageDetail["self_profileid"];
    $flag           = $pageDetail["flag"];
    $DATA_3D        = array();
    
    
    //Ordering is not needed for match alerts for keepng in sync with my JS page
    if ( $flag == "K")
        $DATA_3D = $ARC_SAX;
	elseif($flag == "M")
	{
		$PROFILEID = sortByPhotoLogicMatchAlert($pageDetail);
		$ALLOW_PROFILES = array();
        foreach ($PROFILEID as $key => $val) {
            $ALLOW_PROFILES[] = $val;
            $DATA_3D[$val]    = $ARC_SAX[$val];
        }
        $total_profiles = count($ALLOW_PROFILES);
	}
    else {
        $PROFILEID      = sortByTime($pageDetail);
        $ALLOW_PROFILES = array();
        foreach ($PROFILEID as $key => $val) {
            $ALLOW_PROFILES[] = $val;
            $DATA_3D[$val]    = $ARC_SAX[$val];
        }
        $total_profiles = count($ALLOW_PROFILES);
    }
    unset($ARC_SAX);
    //}
    $pageDetail["DATA_3D"] = $DATA_3D;
    return $pageDetail;
    
}
function multiarray_search($arrayVet, $campo, $value)
{
    while (isset($arrayVet[key($arrayVet)])) {
        if ($arrayVet[key($arrayVet)][$campo] == $value) {
            return key($arrayVet);
        }
        next($arrayVet);
    }
    return -1;
}
function DayDiff($StartDate, $StopDate)
{
    // converting the dates to epoch and dividing the difference
    // to the approriate days using 86400 seconds for a day
    return (date('U', JSstrToTime($StopDate)) - date('U', JSstrToTime($StartDate))) / 86400; //seconds a day
}
//function show_hide_search($searched_caste,$searched_religion,$searched_lage,$searched_hage,$searched_mstatus,$searched_mtongue,$searched_city,$searched_havephoto)
function show_hide_search($pageDetail)
{
    $type = $pageDetail["type"];
    $flag = $pageDetail["flag"];
    //$archive = $pageDetail["archive"];
    
    if (($flag == "I" && $type == "R") || $flag == "FI" || ($flag == "D" && $type == "M")) //($flag=="I" && $archive==1))
        {
        //search_pending_records($self_profileid,$flag,$type,$searched_caste,$searched_religion,$searched_lage,$searched_hage,$searched_mstatus,$searched_mtongue,$searched_city,$searched_havephoto);
        search_pending_records($pageDetail);
    }
}


function getCategorySearchQuery($pageDetail)
{
    global $smarty;
    $religion      = $pageDetail["religion"];
    $caste         = $pageDetail["caste"];
    $lage          = $pageDetail["lage"];
    $hage          = $pageDetail["hage"];
    $city_Res      = $pageDetail["city_Res"];
    $mstatus       = $pageDetail["mstatus"];
    $mtongue       = $pageDetail["mtongue"];
    $havephoto     = $pageDetail["havephoto"];
    $profileid_str = implode("','", $pageDetail["ALLOW_PROFILES"]);
    $sql           = "SELECT PROFILEID FROM newjs.JPROFILE WHERE  activatedKey=1 and PROFILEID IN('$profileid_str')";
    if ($religion) {
        $sql .= " AND RELIGION='" . addslashes(stripslashes($religion)) . "'";
        $smarty->assign("RELIGION", $religion);
    }
    if ($caste) {
        //REVAMP JS_DB_CASTE
include_once(JsConstants::$docRoot."/commonFiles/dropdowns.php");
        $sql_group = "SELECT VALUE FROM newjs.CASTE WHERE VALUE='" . addslashes(stripslashes($caste)) . "' AND ISGROUP='Y'";
        //REVAMP JS_DB_CASTE
        $res_group = mysql_query_decide($sql_group) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes", $sql_group, "ShowErrTemplate");
        if ($row_group = mysql_fetch_array($res_group)) {
            //REVAMP JS_DB_CASTE
            $caste_group_str = $CASTE_GROUP_ARRAY[$row_group["VALUE"]];
            //REVAMP JS_DB_CASTE
            
            $sql .= " AND CASTE IN ('$caste_group_str')";
        } else
            $sql .= " AND CASTE='" . addslashes(stripslashes($caste)) . "'";
        $smarty->assign("CASTE", $caste);
    }
    if ($lage && $hage) {
        $sql .= " AND AGE BETWEEN '" . addslashes(stripslashes($lage)) . "' AND '" . addslashes(stripslashes($hage)) . "'";
        $smarty->assign("LAGE", $lage);
        $smarty->assign("HAGE", $hage);
    }
    if ($mtongue) {
        $sql .= " AND MTONGUE ='" . addslashes(stripslashes($mtongue)) . "'";
        $smarty->assign("MTONGUE", $mtongue);
    }
    if ($city_Res) {
        $sql .= " AND CITY_RES='" . addslashes(stripslashes($city_Res)) . "'";
        $smarty->assign("CITY_RES", $city_Res);
    }
    if ($mstatus) {
        $sql .= " AND MSTATUS='" . addslashes(stripslashes($mstatus)) . "'";
        $smarty->assign("MSTATUS", $mstatus);
    }
    if ($havephoto) {
        $sql .= " AND HAVEPHOTO='Y'";
        $smarty->assign("HAVEPHOTO", $havephoto);
    }
    return $sql;
}
function getLeadSearchQuery($pageDetail)
{
    global $smarty;
    $religion = $pageDetail["religion"];
    $caste    = $pageDetail["caste"];
    $lage     = $pageDetail["lage"];
    $hage     = $pageDetail["hage"];
    $city_Res = $pageDetail["city_Res"];
    $mstatus  = $pageDetail["mstatus"];
    $mtongue  = $pageDetail["mtongue"];
    $lead_str = implode("','", $pageDetail["LEADS"]);
    $sql      = "SELECT id_c FROM sugarcrm.leads_cstm WHERE id_c IN('$lead_str')";
    if ($religion) {
        $sql .= " AND religion_c='" . addslashes(stripslashes($religion)) . "'";
        $smarty->assign("RELIGION", $religion);
    }
    if ($caste) {
        //REVAMP JS_DB_CASTE
include_once(JsConstants::$docRoot."/commonFiles/dropdowns.php");
        $sql_group = "SELECT VALUE FROM newjs.CASTE WHERE VALUE='" . addslashes(stripslashes($caste)) . "' AND ISGROUP='Y'";
        //REVAMP JS_DB_CASTE
        $res_group = mysql_query_decide($sql_group) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes", $sql_group, "ShowErrTemplate");
        if ($row_group = mysql_fetch_array($res_group)) {
            //REVAMP JS_DB_CASTE
            $temp_str     = $CASTE_GROUP_ARRAY[$row_group["VALUE"]];
            $temp_str_arr = explode(",", $temp_str);
            foreach ($temp_str_arr as $k => $v) {
                $caste_group_arr[] = "CONCAT(religion_c,'_','$v')";
            }
            //REVAMP JS_DB_CASTE
            
            $caste_group_str = @implode(",", $caste_group_arr);
            
            $sql .= " AND caste_c IN ($caste_group_str)";
        } else
            $sql .= " AND caste_c=CONCAT(religion_c,'_','" . addslashes(stripslashes($caste)) . "')";
        $smarty->assign("CASTE", $caste);
    }
    if ($lage && $hage) {
        $sql .= " AND age_c BETWEEN '" . addslashes(stripslashes($lage)) . "' AND '" . addslashes(stripslashes($hage)) . "'";
        $smarty->assign("LAGE", $lage);
        $smarty->assign("HAGE", $hage);
    }
    if ($mtongue) {
        $sql .= " AND mother_tongue_c ='" . addslashes(stripslashes($mtongue)) . "'";
        $smarty->assign("MTONGUE", $mtongue);
    }
    if ($city_Res) {
        $sql .= " AND city_c='" . addslashes(stripslashes($city_Res)) . "'";
        $smarty->assign("CITY_RES", $city_Res);
    }
    if ($mstatus) {
        $sql .= " AND marital_status_c='" . addslashes(stripslashes($mstatus)) . "'";
        $smarty->assign("MSTATUS", $mstatus);
    }
    return $sql;
}
function getCategorySearchResults($pageDetail)
{
    global $smarty;
    global $useSlave;
    if (is_array($pageDetail["ALL_MATCHES"])) {
        foreach ($pageDetail["ALL_MATCHES"] as $key => $value) {
            if ($value["PROFILEID"]) {
                if ($pageDetail["match_type"]) {
                    if ($pageDetail["match_type"] == $value["MATCH_TYPE"]) {
                        $pageDetail["ALLOW_PROFILES"][]                     = $value["PROFILEID"];
                        $pageDetail["PROFILE_DETAILS"][$value["PROFILEID"]] = $value;
                    }
                } else {
                    $pageDetail["ALLOW_PROFILES"][]                     = $value["PROFILEID"];
                    $pageDetail["PROFILE_DETAILS"][$value["PROFILEID"]] = $value;
                }
            }
            if ($value["LEAD_ID"]) {
                if ($pageDetail["match_type"]) {
                    if ($pageDetail["match_type"] == $value["MATCH_TYPE"]) {
                        $pageDetail["LEADS"][]                         = $value["LEAD_ID"];
                        $pageDetail["LEAD_DETAILS"][$value["LEAD_ID"]] = $value;
                    }
                } else {
                    $pageDetail["LEADS"][]                         = $value["LEAD_ID"];
                    $pageDetail["LEAD_DETAILS"][$value["LEAD_ID"]] = $value;
                }
            }
        }
    }
    $ALLOW_PROFILES = $pageDetail["ALLOW_PROFILES"];
    $ARC_SAX        = $pageDetail["ARC_SAX"];
    $NUDGES         = $pageDetail["NUDGES"];
    $LEADS          = $pageDetail["LEADS"];
    $smarty->assign("SEARCH_SUBMIT", 1);
    if ($more_or_less == "more")
        $smarty->assign("more_or_less", "more");
    elseif ($more_or_less == "less")
        $smarty->assign("more_or_less", "less");
    if (is_array($ALLOW_PROFILES)) {
        $sql = getCategorySearchQuery($pageDetail);
        if ($useSlave) {
            $dbslave = connect_slave();
            $res = mysql_query($sql, $dbslave) or die("Error while fetching results  " . mysql_error($dbslave));
            $db = connect_db();
        } else
            $res = mysql_query_decide($sql) or logError("Due to a temporary problem your problem could not be resolved. Please try again after a couple of minutes", $sql, "ShowErrTemplate");
    }
    $ALLOW_PROFILES = array();
    $NEW_ARC_SAX    = array();
    $NEW_NUDGES     = array();
    if (@mysql_num_rows($res)) {
        while ($row = mysql_fetch_assoc($res)) {
            $pid = $row["PROFILEID"]; {
                $ALLOW_PROFILES[]  = $pid;
                $NEW_ARC_SAX[$pid] = $ARC_SAX[$pid];
            }
            if (in_array($pid, $NUDGES))
                $NEW_NUDGES[] = $pid;
        }
    }
    if ($pageDetail["havephoto"])
        $LEADS = array();
    if (is_array($LEADS) && count($LEADS)) {
	$dbslave = connect_slave();
        $sql = getLeadSearchQuery($pageDetail);
        $res = mysql_query_decide($sql,$dbslave) or logError("Due to a temporary problem your problem could not be resolved. Please try again after a couple of minutes", $sql, "ShowErrTemplate");
        $LEADS = array();
        if (mysql_num_rows($res)) {
            while ($row = mysql_fetch_assoc($res))
                $LEADS[] = $row["id_c"];
        }
    }
    
    $ARC_SAX                      = $NEW_ARC_SAX;
    $NUDGES                       = $NEW_NUDGES;
    $SHOW_CATEGORY_SEARCH         = 1;
    $pageDetail["ALLOW_PROFILES"] = $ALLOW_PROFILES;
    $pageDetail["ARC_SAX"]        = $ARC_SAX;
    $pageDetail["NUDGES"]         = $NUDGES;
    $pageDetail["LEADS"]          = $LEADS;
    return $pageDetail;
}

function getOnlineUsersDetail($pageDetail)
{
    $onlineArr      = $pageDetail["onlineArr"];
    $page           = $pageDetail["page"];
    $filter         = $pageDetail["filter"];
    $chatBar        = $pageDetail["chatBar"];
    $ALLOW_PROFILES = $pageDetail["ALLOW_PROFILES"];
    if ($onlineArr == 1 && $page == "favorite" && $filter == "M" && $chatBar == 1) {
        $online_array       = array();
        $gtalk_online_array = array();
        $ONLINE_PROFILES    = array();
        $strcontact         = implode("','", $ALLOW_PROFILES);
        get_bookmark_and_online_users();
        for ($x = 0; $x < count($ALLOW_PROFILES); $x++) {
            $pro_id = $ALLOW_PROFILES[$x];
            
            if (in_array($pro_id, $online_array) || in_array($pro_id, $gtalk_online_array)) {
                array_push($ONLINE_PROFILES, $pro_id);
            }
        }
        $ALLOW_PROFILES = $ONLINE_PROFILES;
        $smarty->assign("FAV_ONLINE", 1);
        $smarty->assign("TOTAL_ONLINE_ARRAY", count($ALLOW_PROFILES));
    }
    $pageDetail["ALLOW_PROFILES"] = $ALLOW_PROFILES;
    return $pageDetail;
}

function show_chat_statistics()
{
    global $flag;
    global $contact;
    global $self;
    global $self_profileid;
    global $type;
    global $total_cnt;
    global $ALLOW_PROFILES;
    global $profile_start;
    global $PAGELEN;
    if ($flag == 'I') {
        $sql = "SELECT DISTINCT C." . $contact . " as x FROM userplane.CHAT_REQUESTS AS C LEFT JOIN userplane.LOG_AD AS L ON C." . $contact . " = L." . $contact . " AND C." . $self . " = L." . $self . " WHERE C." . $self . " ='$self_profileid' AND L." . $contact . " IS NULL";
        $result = mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes", $sql, "ShowErrTemplate");
        while ($myrow = mysql_fetch_array($result))
            $chat_arr[] = $myrow['x'];
        if (count($chat_arr) == 0)
            $chat_arr[0] = '';
        $chat_str = "'" . implode("','", $chat_arr) . "'";
    }
    
    if ($flag == 'A' || $flag == 'D') {
        $sql = "SELECT " . $contact . " ,STATUS FROM userplane.LOG_AD WHERE " . $self . "='$self_profileid' ORDER BY TIMEOFINSERTION desc";
        $result = mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes", $sql, "ShowErrTemplate");
        $chat_arr = Array();
        while ($row = mysql_fetch_array($result)) {
            if (!in_array($row[$contact], $chat_arr)) {
                $chat_arr[] = $row[$contact];
                if ($row['STATUS'] == 'a')
                    $chat_arr_acc[] = $row[$contact];
                else
                    $chat_arr_dec[] = $row[$contact];
            }
        }
        if (count($chat_arr_acc) == 0)
            $chat_arr_acc[0] = '';
        if (count($chat_arr_dec) == 0)
            $chat_arr_dec[0] = '';
        
        if ($flag == 'A' && count($chat_arr_acc) > 0)
            $chat_str = "'" . implode("','", $chat_arr_acc) . "'";
        if ($flag == 'D' && count($chat_arr_dec) > 0)
            $chat_str = "'" . implode("','", $chat_arr_dec) . "'";
    }
    
    
    $contact = "PROFILEID";
    $sql     = "select SQL_CALC_FOUND_ROWS " . $contact . " from JPROFILE where  activatedKey=1 and PROFILEID IN ($chat_str) ";
    $result = mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes", $sql, "ShowErrTemplate");
    while ($row = mysql_fetch_row($result)) {
        $ALLOW_PROFILES[] = $row[0];
    }
    $sql = "select FOUND_ROWS() as cnt";
    $resultcount = mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes", $sql, "ShowErrTemplate");
    $countrow  = mysql_fetch_row($resultcount);
    $total_cnt = $countrow[0];
    
    
}
function get_bookmark_and_online_users($pageDetail)
{
    //print_r($pageDetail);
    $self_profileid = $pageDetail["self_profileid"];
    $ALLOW_PROFILES = $pageDetail["ALLOW_PROFILES"];
    $strcontact     = implode("','", $ALLOW_PROFILES);
    global $bookmarkee;
    global $bookmark_array;
    global $online_array;
    global $gtalk_online_array; //added by manoranjan for gtalk
    $page = $pageDetail["page"];
    connect_db();
    if ($page != "favorite") {
        $sql1 = "select BOOKMARKEE,BKNOTE from newjs.BOOKMARKS where BOOKMARKER='$self_profileid' and BOOKMARKEE in ('$strcontact')";
        $result1 = mysql_query_decide($sql1) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes", $sql1, "ShowErrTemplate");
        while ($myrow1 = mysql_fetch_array($result1)) {
            $bookmarkee                  = $myrow1["BOOKMARKEE"];
            $bookmark_array[$bookmarkee] = htmlspecialchars($myrow1["BKNOTE"], ENT_QUOTES);
        }
        mysql_free_result($result1);
    }
    
    $sql1 = "select userID from userplane.users where userID in ('$strcontact')";
    $result1 = mysql_query_decide($sql1) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes", $sql1, "ShowErrTemplate");
    while ($myrow1 = mysql_fetch_array($result1)) {
        $online_array[] = $myrow1["userID"];
    }
    mysql_free_result($result1);
    
    //added by manoranjan for including gtalk onlie user also
    
    $online_gtalk_bookmark = "select USER as profileID from bot_jeevansathi.user_online where USER in('$strcontact')";
    $online_gtalk_result = mysql_query_decide($online_gtalk_bookmark) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes", $online_gtalk_bookmark, "ShowErrTemplate");
    while ($row1 = mysql_fetch_array($online_gtalk_result)) {
        $gtalk_online_array[] = $row1['profileID'];
    }
    mysql_free_result($online_gtalk_result);
}

function getResultArray($pageDetail)
{
    $DATA_3D        = $pageDetail["DATA_3D"];
    $flag           = $pageDetail["flag"];
    $ALLOW_PROFILES = $pageDetail["ALLOW_PROFILES"];
    $filter         = $pageDetail["filter"];
    $page           = $pageDetail["page"];
    if (is_array($DATA_3D)) {
        $i = 0; {
            foreach ($DATA_3D as $key => $val) {
                if ($DATA_3D[$key]["SENDER"]) {
                    $data_3d[$i]['PROFILEID']          = $DATA_3D[$key]["SENDER"];
                    $ACTION[$data_3d[$i]['PROFILEID']] = "SENDER";
                } else {
                    $data_3d[$i]['PROFILEID']          = $DATA_3D[$key]["RECEIVER"];
                    $ACTION[$data_3d[$i]['PROFILEID']] = "RECEIVER";
                }
                $data_3d[$i]['POINTS']    = $DATA_3D[$key]['POINTS'];
                $data_3d[$i]['CONTACTID'] = $DATA_3D[$key]['CONTACTID'];
                $data_3d[$i]['TIME']      = $DATA_3D[$key]['TIME'];
                $data_3d[$i]['COUNT']     = $DATA_3D[$key]['COUNT'];
                $data_3d[$i]['MSG_DEL']   = $DATA_3D[$key]['MSG_DEL'];
                if ($filter == "A" && $page == "accept") {
                    if ($DATA_3D[$key]["SENDER"])
                        $data_3d[$i]['SEEN'] = "Y";
                    else
                        $data_3d[$i]['SEEN'] = $DATA_3D[$key]['SEEN'];
                } elseif ($filter == "M")
                    $data_3d[$i]['SEEN'] = "Y";
                elseif ($filter == 'I') // IVR-Callnow filter added
                    $data_3d[$i]['SEEN'] = "Y";
                else
                    $data_3d[$i]['SEEN'] = $DATA_3D[$key]['SEEN'];
                if (($flag == "P" || $flag == "H") && $filter == "M") {
                    $data_3d[$i]['UPLOADED'] = $DATA_3D[$key]['SEEN'];
                    if ($DATA_3D[$key]['SEEN'] == 'U')
                        $data_3d[$i]['SEEN'] = '';
                }
                if ($flag == "F")
                    $data_3d[$i]['BKNOTE'] = $DATA_3D[$key]['BKNOTE'];
                $CON_TIME[$key] = $DATA_3D[$key]['TIME'];
                $i++;
            }
        }
    } else {
        $i = 0;
        if (is_array($ALLOW_PROFILES)) {
            foreach ($ALLOW_PROFILES as $key => $val) {
                $data_3d[$i]['PROFILEID'] = $val;
                $i++;
            }
        }
    }
    unset($DATA_3D);
    $pageDetail["ACTION"]   = $ACTION;
    $pageDetail["CON_TIME"] = $CON_TIME;
    $pageDetail["data_3d"]  = $data_3d;
    return $pageDetail;
}

function get_invalid_phone_users($pageDetail)
{
    $self_profileid = $pageDetail["self_profileid"];
    $ALLOW_PROFILES = $pageDetail["ALLOW_PROFILES"];
    $strcontact     = implode("','", $ALLOW_PROFILES);
    global $invalid_phone_array;
    $sql_inv = "Select PROFILEID from incentive.INVALID_PHONE where PROFILEID IN('$strcontact')";
    $res_inv = mysql_query_decide($sql_inv) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes", $sql_inv, "ShowErrTemplate");
    while ($myrow_inv = mysql_fetch_array($res_inv)) {
        $invalid_phone_array[] = $myrow_inv['PROFILEID'];
    }
}
function photo_request($data_3d, $start)
{
    global $data, $PAGELEN;
    $profileid = $data['PROFILEID'];
    //Checks if array is passed
    $total     = 0;
    $i         = 0;
    if (is_array($data_3d))
        foreach ($data_3d as $key => $val) {
            if ($i >= $start && $total < $PAGELEN) {
                $profileids .= "'$val[PROFILEID]',";
                $total++;
            }
            $i++;
        }
    
    $profileids = substr($profileids, 0, strlen($profileids) - 1);
    
    if ($profileids == '')
        $profileids = "''";
    
    //Sharding Concept added by Lavesh Rawat on table PHOTO_REQUEST
    $mysqlObj = new Mysql;
    $myDbName = getProfileDatabaseConnectionName($profileid, '', $mysqlObj);
    $myDb     = $mysqlObj->connect("$myDbName");
    
    $sql_1    = "SELECT PROFILEID_REQ_BY FROM PHOTO_REQUEST WHERE PROFILEID='$profileid' AND PROFILEID_REQ_BY IN($profileids)";
    $result_1 = $mysqlObj->executeQuery($sql_1, $myDb);
    while ($myrow_1 = $mysqlObj->fetchArray($result_1)) {
        $PHOTO_REQ[$myrow_1[0]] = $myrow_1[0];
    }
    return $PHOTO_REQ;
    //Sharding Concept added by Lavesh Rawat on table PHOTO_REQUEST
    
}
function ignore_profile($pageDetail)
{
    /*
    global $data,$PAGELEN;
    $profileid=$data['PROFILEID'];
    //Checks if array is passed
    $total=0;
    $i=0;
    if(is_array($data_3d))
    {
    foreach($data_3d as $key=>$val)
    {
    if($i>=$start && $total<$PAGELEN)
    {
    $profileids.="'$val[PROFILEID]',";
    $total++;
    }
    $i++;
    }
    }
    $profileids=substr($profileids,0,strlen($profileids)-1);
    */
    $ALLOW_PROFILES = $pageDetail["ALLOW_PROFILES"];
    if ($ALLOW_PROFILES)
        $profileids = implode(",", $ALLOW_PROFILES);
    $profileid = $pageDetail["self_profileid"];
    $COUNT     = array();
    if ($profileids) {
        $sql_1 = "SELECT IGNORED_PROFILEID FROM newjs.IGNORE_PROFILE WHERE PROFILEID='$profileid' AND IGNORED_PROFILEID IN($profileids) UNION SELECT PROFILEID FROM newjs.IGNORE_PROFILE WHERE PROFILEID IN($profileids) AND IGNORED_PROFILEID ='$profileid'";
        $result_1 = mysql_query_decide($sql_1) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes", $sql_1, "ShowErrTemplate");
        while ($myrow_1 = mysql_fetch_array($result_1)) {
            $COUNT[$myrow_1[0]] = $myrow_1[0];
        }
    }
    return $COUNT;
    
}
function getIgnoredProfile($profileid)
{
    $sql = "SELECT IGNORED_PROFILEID FROM newjs.IGNORE_PROFILE WHERE PROFILEID = '$profileid'";
    $result = mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes", $sql, "ShowErrTemplate");
    while($myrow = mysql_fetch_assoc($result))
        $profiles[]=$myrow["IGNORED_PROFILEID"];
    return $profiles;
}
function check_message($pageDetail, $start)
{
    $data_3d        = $pageDetail["data_3d"];
    $self           = $pageDetail["self"];
    $contact        = $pageDetail["contact"];
    $self_profileid = $pageDetail["self_profileid"];
    $PAGELEN        = $pageDetail["PAGELEN"];
    $flag           = $pageDetail["flag"];
    $ACTION         = $pageDetail["ACTION"];
    $page           = $pageDetail["page"];
    
    if ($page == "intro_call") {
        //$MESSAGE = getTeleCallerComments($self_profileid, $calledProfiles);
        $MESSAGE = $pageDetail["CALL_COMMENTS"];
        return $MESSAGE;
    }
    //global $self,$contact,$self_profileid,$PAGELEN,$flag,$ACTION,$page;
    
    if ($flag == "FI")
        $msg_type = 'I';
    elseif ($flag == "MSG")
        $msg_type = '';
    elseif ($flag)
        $msg_type = $flag;
    else
        $msg_type = '';
    
    //Getting connection on sharded server.		
    $mysql    = new Mysql;
    $myDbName = getProfileDatabaseConnectionName($self_profileid);
    $myDb     = $mysql->connect("$myDbName");
    {
        //Checks if array is passed
        $total = 0;
        $i     = 0;
        if (is_array($data_3d))
            foreach ($data_3d as $key => $val) {
                if ($i >= $start && $total < $PAGELEN) {
                    if ($contact != "SENDER" && $contact != "RECEIVER") {
                        if ($ACTION[$val["PROFILEID"]] == "SENDER")
                            $sender_profileids .= "'$val[PROFILEID]',";
                        elseif ($ACTION[$val["PROFILEID"]] == "RECEIVER")
                            $receiver_profileids .= "'$val[PROFILEID]',";
                    } else
                        $profileids .= "'$val[PROFILEID]',";
                    
                    $total++;
                }
                $i++;
            }
        if ($contact != "SENDER" && $contact != "RECEIVER") {
            $sender_profileids = substr($sender_profileids, 0, strlen($sender_profileids) - 1);
            if ($sender_profileids == '')
                $sender_profileids = "''";
            $receiver_profileids = substr($receiver_profileids, 0, strlen($receiver_profileids) - 1);
            if ($receiver_profileids == '')
                $receiver_profileids = "''";
        } else {
            $profileids = substr($profileids, 0, strlen($profileids) - 1);
            if ($profileids == '')
                $profileids = "''";
        }
        //Getting the message id from message log table.
        if ($contact !== "SENDER" && $contact != "RECEIVER") {
            if ($flag == "A" || $flag == "D")
                $sql_msg = "select ID,RECEIVER from MESSAGE_LOG where RECEIVER in($sender_profileids) and SENDER='$self_profileid' and IS_MSG='Y' and TYPE='$msg_type' UNION select ID,SENDER from MESSAGE_LOG where SENDER IN($receiver_profileids) AND RECEIVER='$self_profileid' and TYPE='$msg_type' order by ID desc ";
            elseif ($msg_type)
                $sql_msg = "select ID,SENDER from MESSAGE_LOG where SENDER in($sender_profileids) and RECEIVER='$self_profileid' and IS_MSG='Y' and TYPE='$msg_type' UNION select ID,RECEIVER from MESSAGE_LOG where RECEIVER IN($receiver_profileids) AND SENDER='$self_profileid' AND TYPE='$msg_type' order by ID desc ";
            else
                $sql_msg = "select ID,SENDER from MESSAGE_LOG where SENDER in($sender_profileids) and RECEIVER='$self_profileid' and IS_MSG='Y' UNION select ID,RECEIVER from MESSAGE_LOG where RECEIVER IN($receiver_profileids) AND SENDER='$self_profileid' order by ID desc ";
        } elseif ($self == 'SENDER') {
            if ($flag == "A" || $flag == "D")
                $sql_msg = "select ID,SENDER from MESSAGE_LOG where SENDER in ($profileids) and RECEIVER='$self_profileid'  and IS_MSG='Y' and TYPE='$msg_type' order by ID desc ";
            elseif ($msg_type)
                $sql_msg = "select ID,RECEIVER from MESSAGE_LOG where RECEIVER in($profileids) and $self='$self_profileid' and IS_MSG='Y' and TYPE='$msg_type' order by ID desc ";
            else
                $sql_msg = "select ID,RECEIVER from MESSAGE_LOG where RECEIVER in($profileids) and $self='$self_profileid' and IS_MSG='Y' order by ID desc ";
        } elseif ($self == 'RECEIVER') {
            if ($flag == "A" || $flag == "D")
                $sql_msg = "select ID,RECEIVER from MESSAGE_LOG where RECEIVER in($profileids) and SENDER='$self_profileid' and IS_MSG='Y' AND TYPE='$msg_type' order by ID desc ";
            elseif ($msg_type)
                $sql_msg = "select ID,SENDER from MESSAGE_LOG where SENDER in ($profileids) and $self='$self_profileid'  and IS_MSG='Y' AND TYPE='$msg_type' order by ID desc ";
            else
                $sql_msg = "select ID,SENDER from MESSAGE_LOG where SENDER in ($profileids) and $self='$self_profileid'  and IS_MSG='Y' order by ID desc ";
        }
        //$result_msg=mysql_query($sql_msg,$myDb) or die(mysql_error($myDb));
        $result_msg = $mysql->executeQuery($sql_msg, $myDb) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes", $sql_msg, "ShowErrTemplate");
        
        $MESSAGE_ID = array();
        while ($myrow_msg = $mysql->fetchArray($result_msg)) {
            if (!array_key_exists($myrow_msg[1], $MESSAGE_ID))
                $MESSAGE_ID[$myrow_msg[1]] = $myrow_msg['ID'];
        }
        //Getting the message from the message table by using the message id which we get from above
        if (is_array($MESSAGE_ID) && count($MESSAGE_ID) > 0) {
            $messages = implode("','", $MESSAGE_ID);
            $sql_msg  = "SELECT ID,MESSAGE FROM newjs.MESSAGES WHERE ID IN('$messages')";
            $res_msg = $mysql->executeQuery($sql_msg, $myDb) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes", $sql_msg, "ShowErrTemplate");
            
            while ($myrow_msg = $mysql->fetchArray($res_msg)) {
                $msg_3d = $myrow_msg['MESSAGE'];
                $key    = array_search($myrow_msg['ID'], $MESSAGE_ID);
                if ($key)
                    $profileid = $key;
                else
                    $profileid = '';
                $MESSAGE[$profileid] = $msg_3d;
            }
        }
    }
    
    if (is_array($MESSAGE))
        return $MESSAGE;
    else
        return '';
}

function getPhotoImage($havephoto, $gender)
{
    if ($havephoto == 'L') {
        if ($gender == 'M')
            $image_file = "ic_login_to_view_b_100.gif";
        else
            $image_file = "ic_login_to_view_g_100.gif";
    } elseif ($havephoto == 'C') {
        if ($gender == 'M')
            $image_file = "ic_photo_vis_if_b_100.gif";
        else
            $image_file = "ic_photo_vis_if_g_100.gif";
    } elseif ($havephoto == 'F') {
        if ($gender == 'M')
            $image_file = "ic_filtered_b_100.gif";
        else
            $image_file = "ic_filtered_g_100.gif";
    } elseif ($havephoto == 'H') {
        if ($gender == 'M')
            $image_file = "ic_hidden_b_100.gif";
        else
            $image_file = "ic_hidden_g_100.gif";
    } elseif ($havephoto == 'P') {
        if ($gender == 'M')
            $image_file = "photo_fil_sm_b.gif";
        else
            $image_file = "photo_fil_sm_g.gif";
    } elseif ($havephoto == 'U') {
        if ($gender == 'M')
            $image_file = "ic_photo_coming_b_100.gif";
        else
            $image_file = "ic_photo_coming_g_100.gif";
    } elseif ($havephoto == 'N') {
        if ($gender == 'M')
            $image_file = "ic_request_photo_b_100.gif";
        else
            $image_file = "ic_request_photo_g_100.gif";
    }
    return $image_file;
}

function isNudgeProfile($profileid, $NUDGES)
{
    if (in_array($profileid, $NUDGES))
        return true;
    else
        return false;
}

function getContactMessage($pageDetail, $index, $MESSAGE_EACH)
{
    $data_3d        = $pageDetail["data_3d"];
    $page           = $pageDetail["page"];
    $self_profileid = $pageDetail["self_profileid"];
    $NUDGES         = $pageDetail["NUDGES"];
    $isMobile       = $GLOBALS['isMobile'];
    if ($isMobile)
        $charLimit = 2000;
    else
        $charLimit = 250;
    $k = $index;
    
    if ($page != "favorite") {
        if (isNudgeProfile($data_3d[$k]['PROFILEID'], $NUDGES)) {
            $op_msg_sql = "SELECT MESSAGE FROM jsadmin.OFFLINE_OPERATOR_MESSAGES WHERE PROFILEID='" . $data_3d[$k]['PROFILEID'] . "' AND MATCH_ID='$self_profileid'";
            $op_msg_res = mysql_query_decide($op_msg_sql) or logError("Due to a temporary problem, your request could not be processed. Please try after a couple of minutes", $op_msg_sql, "ShowErrTemplate");
            if (mysql_num_rows($op_msg_res)) {
                $op_msg_row = mysql_fetch_assoc($op_msg_res);
                $msg_3d     = html_entity_decode($op_msg_row["MESSAGE"]);
                $msg_3d     = str_replace("<br />", "", $msg_3d);
                if (strlen($msg_3d) > $charLimit) {
                    $message_org = substr($msg_3d, 0, $charLimit);
                    $message_org .= "...";
                } else
                    $message_org = $msg_3d;
            } else {
                $message_org = htmlspecialchars("Your desired partner profiles matched each...");
            }
            mysql_free_result($op_msg_res);
        } else {
            if ($data_3d[$k]["MSG_DEL"] == "D")
                $message_org = "";
            else {
                $checkid = $data_3d[$k]["PROFILEID"];
                $msg_3d  = $MESSAGE_EACH[$data_3d[$k]["PROFILEID"]];
                if ($page == "intro_call")
		    if($pageDetail["ARC_SAX"][$checkid]["CALL_STATUS"]=="C") $msg_3d="";
                    return array(
                        "MESSAGE" => $msg_3d,
                        "READ_MORE" => false
                    );
                if (strlen($msg_3d) > $charLimit) {
                    $message_org = substr($msg_3d, 0, $charLimit);
                    $message_org = nl2br($message_org);
                    $message_org .= "...";
                } else
                    $message_org = nl2br($msg_3d);
            }
        }
    } else {
        $msg_3d      = html_entity_decode($data_3d[$k]['BKNOTE']);
        $msg_3d      = str_replace("<br />", "", $msg_3d);
        $message_org = $msg_3d;
    }
    if (strlen($msg_3d) >= $charLimit)
        $readMore = true;
    $message = array(
        "MESSAGE" => stripslashes($message_org),
        "READ_MORE" => $readMore
    );
    return $message;
}

/* IVR-Callnow function added
 * This function gets all calls(received/missed/called ones) between the user and receiver
 */
function get_contact_line_calls($page, $viewed_action, $viewed_gender, $gender, $CONTACT_STATUS, $viewed_pid, $uploaded = '', $pageDetail, $callDataArray)
{
    $missedCallType   = $callDataArray['M']['CALL_STATUS'];
    $receivedCallType = $callDataArray['R']['CALL_STATUS'];
    $calledCallType   = $callDataArray['I']['CALL_STATUS'];
    $missedCallDate   = $callDataArray['M']['CALL_DT'];
    $receivedCallDate = $callDataArray['R']['CALL_DT'];
    $calledCallDate   = $callDataArray['I']['CALL_DT'];
    
    if ($receivedCallType == 'R') {
        $dateTimeArr = datetime_format($receivedCallDate);
        $calldate    = $dateTimeArr[0];
        $calltime    = $dateTimeArr[1];
        $TOI         = " on " . $calldate . " at " . $calltime;
        if ($viewed_gender == 'M')
            $contact_line = "He";
        else
            $contact_line = "She";
        $contact_line .= " called you ";
        $contact_line1 = "<b>" . $contact_line . "</b>" . $TOI;
        $in_out1       = "cntc_rcved_icon fl sprt_cn_ctr";
        $call_status1  = 'R';
    }
    if ($missedCallType == 'M') {
        $dateTimeArr  = datetime_format($missedCallDate);
        $calldate     = $dateTimeArr[0];
        $calltime     = $dateTimeArr[1];
        $TOI          = " on " . $calldate . " at " . $calltime;
        $contact_line = "You missed a call from ";
        if ($viewed_gender == 'M')
            $contact_line .= "him";
        else
            $contact_line .= "her";
        $contact_line2 = "<b>" . $contact_line . "</b>" . $TOI;
        $in_out2       = "cntc_rcved_icon fl sprt_cn_ctr";
        $call_status2  = 'M';
    }
    if ($calledCallType == 'I') {
        $dateTimeArr  = datetime_format($calledCallDate);
        $calldate     = $dateTimeArr[0];
        $calltime     = $dateTimeArr[1];
        $TOI          = " on " . $calldate . " at " . $calltime;
        $contact_line = "You called ";
        if ($viewed_gender == 'M')
            $contact_line .= "him";
        else
            $contact_line .= "her";
        $contact_line3 = "<b>" . $contact_line . "</b>" . $TOI;
        $in_out3       = "cl_mde_icon fl sprt_cn_ctr";
        $call_status3  = 'I';
    }
    $contact_line_arr = array(
        $contact_line1,
        $contact_line2,
        $contact_line3
    );
    $in_out_arr       = array(
        $in_out1,
        $in_out2,
        $in_out3
    );
    $call_status_arr  = array(
        $call_status1,
        $call_status2,
        $call_status3
    );
    return array(
        $contact_line_arr,
        $in_out_arr,
        $call_status_arr
    );
}

function get_contact_line($page, $viewed_action, $viewed_gender, $gender, $CONTACT_STATUS, $viewed_pid, $uploaded = '', $pageDetail,$isMobile='')
{
    $TOI          = $pageDetail["CON_TIME"][$viewed_pid];
    $contact_line = "<b>&nbsp;";
    if ($page == 'callnow') // IVR-Callnow feature added
        {
        $type = $pageDetail['type'];
        if ($viewed_action == 'SENDER') {
            
            if ($type == 'R') {
                if ($viewed_gender == 'M')
                    $contact_line .= "He";
                else
                    $contact_line .= "She";
                $contact_line .= " called you";
            }
            if ($type == 'M') {
                $contact_line .= "You missed a call from ";
                if ($viewed_gender == 'M')
                    $contact_line .= "him";
                else
                    $contact_line .= "her";
                
            }
            $in_out = "in";
        } elseif ($viewed_action == 'RECEIVER') {
            $contact_line = "You called ";
            if ($viewed_gender == 'M')
                $contact_line .= "him";
            else
                $contact_line .= "her";
            $in_out = "out";
        }
    }
    if ($page == "accept") {
        if ($viewed_action == "SENDER") {
            $contact_line .= "You ";
            $in_out = "out";
        } elseif ($viewed_action == "RECEIVER") {
            if ($viewed_gender == "M")
                $contact_line .= "He";
            else
                $contact_line .= "She";
            $in_out = "in";
        }
        $contact_line .= " accepted interest";
    } elseif ($page == "favorite") {
        if ($viewed_action == "RECEIVER")
			if($isMobile)
			{
				$contact_line .= "Added to shortlist ";
			}else
            $contact_line .= "You added to shortlist ";
        if ($viewed_action == "RECEIVER")
            $in_out = "out";
        elseif ($viewed_action == "SENDER")
            $in_out = "in";
    } /*********Direct call changes******/ 
    elseif ($page == "viewed_contacts") {
        $contact_line .= "You saw contact details ";
        $in_out = "out";
    } elseif ($page == "viewed_contacts_by" || $page == "contact_viewers") {
        $contact_line .= "Your contact details viewed ";
        $in_out = "in";
    } /*****Ends here******/ 
    elseif ($page == "photo" || $page == "horoscope" || $page == "chat" || $page == "messages") {
        if ($viewed_action == "SENDER" || $uploaded != '') {
            if ($viewed_gender == "M")
                $contact_line .= "He ";
            elseif ($viewed_gender == "F")
                $contact_line .= "She";
        } elseif ($viewed_action == "RECEIVER") {
            $contact_line .= "You";
        }
        if ($page == "photo" || $page == "horoscope" || $page == "chat") {
            if ($uploaded == "Y" || $uploaded == "U")
                $contact_line .= " uploaded";
            else
                $contact_line .= " requested";
            if ($page == "photo")
                $contact_line .= " photo";
            elseif ($page == "horoscope")
                $contact_line .= " horoscope";
            elseif ($page == "chat")
                $contact_line .= " for chat";
        } 
        elseif ($page == "messages")
            $contact_line .= " wrote message";
        if ($viewed_action == "SENDER")
            $in_out = "in";
        elseif ($viewed_action == "RECEIVER")
            $in_out = "out";
    } elseif ($page == "intro_call") {
        if ($pageDetail["ARC_SAX"][$viewed_pid]["CALL_STATUS"] == "C") {
            $TOI = ""; 
            if($pageDetail["CALL_COMMENTS"][$viewed_pid]) $contact_line .= "Communication with this user is not complete";
            else $contact_line .= "Profile yet to be called";
        } else {
            if(!$pageDetail["CALL_COMMENTS"][$viewed_pid])
             { $contact_line .= "Communication with this user is not complete"; //corresponds to the case when the communication started but comments updated... added by Palash C.
            $TOI="";
            }
            else{
            $TOI = $pageDetail["CALL_COMMENTS"]["lastCallDate"];
            $contact_line .= "Profile called";
            }
            
            }
        $in_out = '';
    } elseif ($page == "ignore") {
        if ($viewed_action == "RECEIVER") {
            $contact_line .= "You blocked ";
            if ($viewed_gender == "M")
                $contact_line .= "him";
            else
                $contact_line .= "her";
        } elseif ($viewed_action == "SENDER") {
            if ($viewed_gender == "M")
                $contact_line .= "He";
            else
                $contact_line .= "She";
            $contact_line .= " has blocked you";
        }
        $in_out = '';
    } elseif ($page == "decline") {
        if ($viewed_action == "RECEIVER" && $pageDetail['self'] == 'SENDER') {
            if ($viewed_gender == "M")
                $contact_line .= "He ";
            else
                $contact_line .= "She ";
            $in_out = "in";
            $contact_line .= " declined your interest";
        } elseif ($viewed_action == "SENDER" && $pageDetail['self'] == 'RECEIVER') {
            $contact_line .= "You";
            $in_out = "out";
            $contact_line .= " declined";
            if ($viewed_gender == "M")
                $contact_line .= " his interest";
            else
                $contact_line .= " her interest";
        } elseif ($viewed_action == "RECEIVER" && $pageDetail['self'] == 'RECEIVER') {
            $contact_line .= "You cancelled interest";
        } elseif ($viewed_action == "SENDER" && $pageDetail['self'] == 'SENDER') {
            if ($viewed_gender == "M")
                $contact_line .= "He ";
            else
                $contact_line .= "She ";
            $in_out = "out";
            $contact_line .= " cancelled interest";
        }
        
        
    } 
    elseif ($page == "matches" || $page == "visitors" || $page == "kundli") {
        if ($page == "matches" || $page == "kundli")
            $contact_line .= "Sent to ";
        elseif ($page == "visitors") {
            if ($viewed_gender == "M")
                $contact_line .= "He ";
            else
                $contact_line .= "She ";
            $contact_line .= "visited ";
        }
        if ($viewed_action == "RECEIVER") {
            if ($page == "matches" || $page == "kundli")
                $contact_line .= "you ";
            else {
                if ($viewed_gender == "M")
                    $contact_line .= "his ";
                elseif ($viewed_gender == "F")
                    $contact_line .= "her ";
            }
        } elseif ($viewed_action == "SENDER") {
            if ($page == "matches" || $page == "kundli")
                $contact_line .= "you";
            elseif ($page == "visitors")
                $contact_line .= "your profile";
            
        }
        if ($viewed_action == "SENDER")
            $in_out = "in";
        elseif ($viewed_action == "RECEIVER")
            $in_out = "out";
    } elseif ($page == "eoi" || $page == "filtered_eoi") //||$page=="archive_eoi")
        {
        $NUDGES = $pageDetail["NUDGES"];
        ;
        $type = $pageDetail["type"];
        
        if ($type == "R") {
            if (in_array($viewed_pid, $NUDGES)) {
                $contact_line .= "Jeevansathi recommended ";
                if ($viewed_gender == "M")
                    $contact_line .= "him";
                else
                    $contact_line .= "her";
            } else {
                if ($viewed_gender == "M")
                    $contact_line .= "He ";
                else
                    $contact_line .= "She ";
                if ($pageDetail["ARC_SAX"][$viewed_pid]["COUNT"] > 1)
					$contact_line .= "sent reminder";
				else
					$contact_line .= "expressed interest";
            }
            $in_out = "in";
        } elseif ($type == "M" ) {
            if ($pageDetail["ARC_SAX"][$viewed_pid]["COUNT"] > 1)
                $contact_line .= "You sent reminder";
            else
                $contact_line .= "You expressed interest";
            $in_out = "out";
        }
        elseif($isMobile)
        {
			$contact_line .= "You Wrote";
		}
    }
    $contact_line .= "</b>";
    if ($TOI  ) {
		$dateTimeArr = datetime_format($TOI);
        $calltime    = $dateTimeArr[1];
        $year        = substr($TOI, 0, 4);
        $month       = substr($TOI, 5, 2);
        $day         = substr($TOI, 8, 2);
        $TOI         = my_format_date($day, $month, $year, 1);
        $contact_line .= " on $TOI";
	}
    return array(
        $contact_line,
        $in_out
    );
}

/*function get_viewed_line($page,$viewed_date,$viewed_gender)
{
global $type;
if($viewed_date)
{
if($page=='eoi')
{
if($type=="M")
{
if($viewed_gender=="M")
$viewed_line = 'He';
else
$viewed_line = 'She';	
$viewed_line.=" viewed your Expression of interest";
$in_out="in";
}
}
}
return array($viewed_line,$in_out);
}*/

function getCallNowLink($page, $viewed_subscription, $contact_locked)
{
    $callNow = false;
    if ($page == "eoi")
        $callNow = true;
    if ($page == "accept") {
        if ($contact_locked)
            $callNow = true;
    }
    
}

function displayContactGadget($page, $CONTACT_STATUS, $pageDetail, $callDataArray, $called, $eValueMember = "")
{
    // IVR-Callnow
    $missedCallType   = $callDataArray['M']['CALL_STATUS'];
    $receivedCallType = $callDataArray['R']['CALL_STATUS'];
    $calledCallType   = $callDataArray['I']['CALL_STATUS'];
    // Ends
    
    $contactGadget = false;
    /*********Direct call changes******/
    //if($called || $page=="accept" || $page=="viewed_contacts" || $page=="viewed_contacts_by" || $eValueMember)
    //if ($page == "accept" || $page == "viewed_contacts" || $eValueMember || $page == "messages")
    if ($page == "accept" || $page == "viewed_contacts" || $page == "messages")
        $contactGadget = true;
    //Removed with FTO after Product discussion
    /*
    elseif($page=="favorite" || $page=="photo" || $page=="horoscope" || $page=="chat" || $page=="matches" || $page=="visitors" || $page=="intro_call")
    {
    if(is_array($CONTACT_STATUS))
    {
    if($CONTACT_STATUS["TYPE"]=="A" || $CONTACT_STATUS["TYPE"]=="ACC")
    {
    $contactGadget = true;
    }
    }
    }
    */
    /*elseif($page =='callnow' && ($pageDetail['type']=='R' || $pageDetail['type']=='M'))// IVR-Callnow - Contact Details
    $contactGadget = true;
    elseif($page=='callnow' && ($missedCallType=='M' || $receivedCallType=='R'))
    $contactGadget = true;*/
    return $contactGadget;
}

function get_links($page, $viewed_action, $viewed_sub_array, $subscription, $viewed_profileid, $checksum, $viewed_profile_checksum, $CONTACT_STATUS, $index, $contact_details, $show_contacts, $pageDetail, $tempContacted, $contact_locked, $callDataArray)
{  
    if ($contact_details["ACTIVATED"] == "D")
        return false;
    $viewed_username     = $contact_details["NAME"];
    $viewed_subscription = $contact_details["SUBSCRIPTION"];
    /*if($viewed_subscription || $subscription)
    $acceptCaption = "Accept to View Contact Details";
    else*/
    $acceptCaption       = "Accept Interest";
    $acceptWidth         = "110px";
    global $data, $cc_navigator, $upto;
    $evalue           = isEvalueMember($viewed_subscription);
    $removeMemberLink = "";
    $addMemberLinkFr  = "";
    if ($pageDetail["apMember"]) {
        $removeAddMemberLink = $pageDetail["removeAddMemberLink"];
        if ($page == "eoi" || $page == "accept" || $page == "intro_call") {
            if ($page == "eoi" || $page == "accept") {
                if (in_array($viewed_profileid, $pageDetail["addedToIntroProfiles"]))
                    $addMemberLinkFr = "<div  class=\"clr\"></div><span class=\"fr b mar_top_10\">Added to 'Members to be called' list</span>";
                else {
                    if (!$removeAddMemberLink)
                        $addMemberLinkFr = "<div class=\"clr\"></div><span class=\"fr mar_top_10\"><i class=\"sprt_cn_ctr fl plus_round mr_5\"></i><a href=\"#\" class=\"blink\" onclick=\"return check_checkbox('PROFILE_$index',$index,'add_intro','$show_contacts');\">Add to Members to be called</a></span>";
                }
            } else {
                if ($pageDetail["ARC_SAX"][$viewed_profileid]["CALL_STATUS"] != "Y") {
                    if($pageDetail["CALL_COMMENTS"][$viewed_profileid])
                    $removeMemberLink = "<div  class=\"clr\"></div><span class=\"fr b mar_top_10\">Communication with user in progress</span>";
                    else
                    $removeMemberLink = "<div  class=\"clr\"></div><span class=\"fr mar_top_10\"><i class=\"sprt_cn_ctr fl min_round mr_5\"></i><a href=\"#\" class=\"blink fl\" onclick=\"return check_checkbox('PROFILE_$index',$index,'remove_intro','$show_contacts');\">Remove from 'Members to be called' list</a></span>";
                } else
                    $removeMemberLink = "<div  class=\"clr\"></div><span class=\"fr b mar_top_10\">Communication with user done</span>";
		}
            }
    }
    $acceptLeft = "-350px";
    if ($index == $upto - 1) {
        $top  = "-170px";
        $left = "-580px";
    } else {
        $top  = "-6px";
        $left = "-580px";
    }
    $callNow = false;
    if (!$contact_locked)
        $callNow = true;
    $viewSimilarLink   = /*"<i class=\"sprt_cn_ctr fl vw_smlr_icon mr_5\" style=\"margin-left: 15px;\"></i>*/"<a style=\"margin-left: 15px;\"class=\"blink fl mr_15\" href=\"$SITE_URL/search/viewSimilarProfile?Stype=V&checksum=$checksum&contact=$viewed_profileid&SIM_USERNAME=$viewed_username&$cc_navigator\"><strong>People Similar to ".$contact_details["NAME"]."</strong></a>";
    $viewContactLink   = "";
    $viewContactLinkFr = "";
    $viewContactLinkFl = "";
    $viewContactParam  = "view_contact";
    if ($page == "messages")
        $viewContactParam = "view_contact_message";
    /*********Direct call changes******/
    if (($evalue) || ($pageDetail["CALL_DIRECT"])) {
        /*$validPhone = false;
        if($pageDetail["validPhone"][$viewed_profileid] == "Y")
        $validPhone = true;
        if($validPhone)
        {
        $viewContactLink = "<i class=\"phone_vrfd sprt_cn_ctr fl mr_5\" title=\"Verified phone number\"></i><i class=\"sprt_cn_ctr fl vw_cnct_icon mr_5\" style=\"margin-left: 15px;\"></i><a  class=\"blink b fl\" onclick=\"javascript:{check_window('hide_exp_layer()');check_checkbox('PROFILE_$index',$index,'$viewContactParam','$show_contacts');}\">View Contact Details</a>";
        $viewContactLinkFr = "<a  class=\"blink b fr\" onclick=\"javascript:{check_window('hide_exp_layer()');check_checkbox('PROFILE_$index',$index,'$viewContactParam','$show_contacts');}\">View Contact Details</a><i class=\"sprt_cn_ctr fr vw_cnct_icon mr_5\" style=\"margin-left: 15px;\"></i><i class=\"phone_vrfd sprt_cn_ctr fr mr_5\" title=\"Verified phone number\"></i>";
        }
        else
        {*/
        $viewContactLink   = "<div class=\"layerce\" id=\"detailsLayer_$viewed_profile_checksum\"><a  style=\"margin-left: 15px;\" class=\"blink fl \">Contact Details</a></div>";
        $viewContactLinkFr = "<div class=\"layerce\" id=\"detailsLayer_$viewed_profile_checksum\"><a style=\"margin-left: 15px;\" class=\"blink fr \">Contact Details</a></div>";
        $viewContactLinkFl = "<div class=\"layerce\" id=\"detailsLayer_$viewed_profile_checksum\"><a  class=\"blink fr\" style=\"margin-right:7px\">Contact Details</a></div>";
        //}
        
        if($evalue)
        {
			$link2 = "<div style=\"background-color: lightgray;margin: 9px; padding:8px;font-weight: bold;\"><div class=\"layerce\" id=\"detailsLayer_$viewed_profile_checksum\"><a class=\"blink b fl\" style=\"margin-right: 2px;\">Click to View&nbsp;</a></div> Contact Details of $contact_details[NAME] for <b class=\"red_c\">FREE</b><i class=\"e_vlu_icon sprt_cn_ctr fr f_2\" ></i></div>";
		}
    }
    /*********Direct call changes******/
    if ($page == "viewed_contacts")
        $viewContactLink = "";
    $acceptCaption = "Accept Interest";
    $acceptWidth   = "110px";
    global $data, $cc_navigator, $upto;
    $acceptLeft = "-150px";
    if ($index == $upto - 1) {
        $top  = "-170px";
        $left = "-580px";
    } else {
        $top  = "-6px";
        $left = "-580px";
    }
    $callNow = false;
    if (!$contact_locked)
        $callNow = true;
    if ($page == "accept") {
        $links[0] .= $viewSimilarLink;
        $links[0] .= "<span id=\"SPAN_$index\" class=\"fr\" style='position:relative'><div class=\"layerce\" id=\"writeLayer_$viewed_profile_checksum\"><a  class=\"blink fl\">Send Message</a></div>";
        $links[1] = $viewContactLink . "</span>" . $addMemberLinkFr . "<span style=\"position:relative\"><div id=\"EXP_LAYER_$index\" style=\"z-index: 1000;position:absolute;display:inline;left:-140px;top:$top\" onclick=\"javascript:check_window('hide_exp_layer()')\"></DIV></span>";
    } elseif ($page == "favorite" || $page == "photo" || $page == "horoscope" || $page == "chat" || $page == "matches" || $page == "visitors" || $page == 'callnow' || $page == "viewed_contacts" || $page == "intro_call" || $page == "viewed_contacts_by" || $page == "contact_viewers" || $page == "kundli") {
        $callNow = true;
        if (is_array($CONTACT_STATUS)) {
            //Links in case of acceptance
            if ($CONTACT_STATUS["TYPE"] == "A" || $CONTACT_STATUS["TYPE"] == "ACC") {
                $links[0] .= $viewSimilarLink;
                $links[0] .= "<span id=\"SPAN_$index\" class=\"fr\" style='position:relative'><div class=\"layerce\" id=\"writeLayer_$viewed_profile_checksum\"><a  class=\"blink fl \">Send Message</a></div>";
                $links[1] = $viewContactLink . "</span>" . $removeMemberLink . "<span style=\"position:relative\"><div id=\"EXP_LAYER_$index\" style=\"z-index: 1000;position:absolute;display:inline;left:-140px;top:$top\" onclick=\"javascript:check_window('hide_exp_layer()')\"></DIV></span>";
            }
            //Links in case of EOI
            elseif ($CONTACT_STATUS["TYPE"] == "I" || $CONTACT_STATUS["TYPE"] == "NACC") {
                //EOI received
                if ($CONTACT_STATUS["TYPE"] == "I" && $CONTACT_STATUS["ACTION"] == "SENDER") {
                    $callNow = true;
                    $links[0] .= "<span id=\"SPAN_$index\" ><div class=\"layerce\" id=\"notinterestLayer_$viewed_profile_checksum\"><input type=\"button\" style=\"width:104px; margin:0 5px;\" class=\"gray_btn b fr\" value=\"Not Interested\"></div><div class=\"layerce\" id=\"acceptLayer_$viewed_profile_checksum\"><input type=\"button\" class=\"green_btn b fr\" value=\"" . $acceptCaption . "\" style=\"width:" . $acceptWidth . "; margin:0 5px;\" ></div>";
                    $links[0] .= $viewContactLinkFr;
                    $links[2] = "</span>" . $removeMemberLink . "<span class=\"lf\" style='position:relative'><div id=\"EXP_LAYER_$index\" style=\"z-index: 1000;position:absolute;display:inline;left:$acceptLeft;top:$top\" onclick=\"javascript:check_window('hide_exp_layer()')\"></DIV></span>";
                }
                //EOI sent or nudge accepted
                elseif (($CONTACT_STATUS["TYPE"] == "I" && $CONTACT_STATUS["ACTION"] == "RECEIVER") || $CONTACT_STATUS["TYPE"] == "NACC") {
                    $callNow  = true;
                    $links[0] = $viewSimilarLink ;
                    $links[0] .= "<span id=\"SPAN_$index\" style='position:relative' class=\"fr\"><div class=\"layerce\" id=\"reminderLayer_$viewed_profile_checksum\"><a  class=\"blink fl\">Send Reminder</a></div>";
                    $links[1] = $viewContactLink. "</span>" . $removeMemberLink . "<span style=\"position:relative\"><div id=\"EXP_LAYER_$index\" style=\"z-index: 1000;position:absolute;display:inline;left:-140px;top:$top\" onclick=\"javascript:check_window('hide_exp_layer()')\"></DIV></span>";
                }
            }
            //Nudged
                elseif ($CONTACT_STATUS["TYPE"] == "N" || $CONTACT_STATUS["TYPE"] == "NNOW") {
                $callNow = true;
                if ($tempContacted)
                    $links[0] = "<span id=\"SPAN_$index\" style='position:relative'><i class=\"btn greynew-btn\">Interest Expressed</i>";
                else
                    $links[0] .=$viewSimilarLink;
                $links[0] .=  "<span id=\"SPAN_$index\" style='position:relative'><a  class=\"blink fl\" onclick=\"javascript:{check_window('hide_exp_layer()');check_checkbox('PROFILE_$index',$index,'eoi','$show_contacts');}\">Express Interest | <a  class=\"blink fl\" onclick=\"javascript:{check_window('hide_exp_layer()');check_checkbox('PROFILE_$index',$index,'decline','$show_contacts');}\">Not Interested</a>";
                $links[1] = $viewContactLink. "</span>" . $removeMemberLink . "<span style=\"position:relative\"><div id=\"EXP_LAYER_$index\" style=\"z-index: 1000;position:absolute;display:inline;left:-140px;top:$top\" onclick=\"javascript:check_window('hide_exp_layer()')\"></DIV></span>";
            }
            //Decline or offline profile rejects profile
                elseif ($CONTACT_STATUS["TYPE"] == "D" || $CONTACT_STATUS["TYPE"] == "REJ") {
                $callNow = true;
                //I decline
                if ($CONTACT_STATUS["ACTION"] == "SENDER") {
                    $links[0] = "<span id=\"SPAN_$index\" class=\"fr\"><div class=\"layerce\" id=\"acceptLayer_$viewed_profile_checksum\"><input type=\"button\" class=\"green_btn fl\" value=\"Accept this member\" style=\"width:150px; margin:0 5px;\" ></div>";
                    $links[0] .= $viewContactLink;
                    $links[1] = "</span>" . $removeMemberLink . "<span class=\"fr\" style='position:relative'><div id=\"EXP_LAYER_$index\" style=\"z-index: 1000;position:absolute;display:inline;left:$acceptLeft;top:$top\" onclick=\"javascript:check_window('hide_exp_layer()')\"></DIV></span>";
                }
                //User declines or offline profile declines
                elseif ($CONTACT_STATUS["ACTION"] == "RECEIVER" || $CONTACT_STATUS["TYPE"] == "REJ")
                    $links[0] = "<span class=\"fr\">" . $viewSimilarLink . "</span>" . $removeMemberLink;
            } elseif ($CONTACT_STATUS["TYPE"] == "E") {
                $callNow = true;
                //I cancelled my own interest
                $links[0] .= "<span id=\"SPAN_$index\" class=\"fr\"><div class=\"layerce\" id=\"expressLayer_$viewed_profile_checksum\"><a  class=\"blink fl cp\">Express Interest</a></div>";
                
            }
        }
        //No contact at all
        else {
            $callNow = true;
            $links[0] .= $viewSimilarLink;
            if ($tempContacted)
                $links[0] .= "<span id=\"SPAN_$index\" style='position:relative' class=\"fr\"><a class=\"btn greynew-btn\">Interest Expressed</a>";
            else {
                //	$links[0].="<span id=\"SPAN_$index\" class=\"fr\"><i class=\"sprt_cn_ctr fl smly_icon mr_5\"></i><a  class=\"blink fl\" onclick=\"javascript:{check_window('hide_exp_layer()');check_checkbox('PROFILE_$index',$index,'eoi','$show_contacts');}\">Express Interest</a>";
                $links[0] .= "<span id=\"SPAN_$index\" class=\"fr \"><div class=\"layerce\" id=\"expressLayer_$viewed_profile_checksum\"><a  class=\"blink fl cp\">Express Interest</a></div>";
            }
            
            $links[1] = $viewContactLink . "</span>" . $removeMemberLink . "<span style=\"position:relative\"><div id=\"EXP_LAYER_$index\" style=\"z-index: 1000;position:absolute;display:inline;left:-140px;top:$top\" onclick=\"javascript:check_window('hide_exp_layer()')\"></DIV></span>";
        }
        
        // IVR-Callnow
        if ($page == 'callnow') {
            $missedCallType   = $callDataArray['M']['CALL_STATUS'];
            $receivedCallType = $callDataArray['R']['CALL_STATUS'];
            $calledCallType   = $callDataArray['I']['CALL_STATUS'];
            
            $type = $pageDetail["type"];
            if (($type == "R" || $type == 'M' || $type == 'I') || $missedCallType == 'M' || $receivedCallType == 'R' || $calledCallType == 'I') {
                $callNow = true;
            }
        }
        // End IVR-Callnow
        
    } elseif ($page == "ignore") {
        $callNow  = true;
        $links[0] = $viewSimilarLink;
        $links[0] .= "<a class=\"blink fl\" onClick=\"stop_ignoring('$checksum','$viewed_profileid','$viewed_username')\" >Unblock</a>";
        $links[1] = $viewContactLink . "<span style=\"position:relative\"><div id=\"EXP_LAYER_$index\" style=\"z-index: 1000;position:absolute;display:inline;left:$left;top:$top\" onclick=\"javascript:check_window('hide_exp_layer()')\"></DIV></span>";
        
    } elseif ($page == "decline") {
        $NUDGES = $pageDetail["NUDGES"];
        $type   = $pageDetail["type"];
        if ($viewed_action == "RECEIVER" && $pageDetail['flag'] != 'D')
            $links[0] = $viewSimilarLink;
        elseif ($viewed_action == "SENDER") {
            if ($CONTACT_STATUS['TYPE'] == 'D') {
                $callNow = true;
                if (is_array($NUDGES) && in_array($viewed_profileid, $NUDGES))
                    $links[0] = "<span id=\"SPAN_$index\" ><div class=\"layerce\" id=\"expressLayer_$viewed_profile_checksum\"><input type=\"button\" class=\"green_btn fr b\" value=\"Express Interest\" style=\"width:150px; margin:0 5px;\"></div>";
                else
                    $links[0] = "<span id=\"SPAN_$index\" ><div class=\"layerce\" id=\"acceptLayer_$viewed_profile_checksum\"><input type=\"button\" class=\"green_btn fr b\" value=\"Accept this member\" style=\"width:150px; margin:0 5px;\"></div>";
                $links[2] = "</span><span style=\"position:relative\" class=\"fr\"><div id=\"EXP_LAYER_$index\" style=\"z-index: 1000;position:absolute;display:inline;left:-400px;top:$top\" onclick=\"javascript:check_window('hide_exp_layer()')\"></DIV></span>";
            }
        } elseif ($viewed_action == "RECEIVER" && $pageDetail['flag'] = 'D') {
            if ($CONTACT_STATUS['TYPE'] == 'E') {
                $links[0] = "<span id=\"SPAN_$index\" ><div class=\"layerce\" id=\"expressLayer_$viewed_profile_checksum\"><input type=\"button\" class=\"green_btn fr b\" value=\"Express Interest\" style=\"width:150px; margin:0 5px;\"></div>";
            } elseif ($CONTACT_STATUS['TYPE'] == 'C') {
                $links[0] = "<span id=\"SPAN_$index\" ><div class=\"layerce\" id=\"acceptLayer_$viewed_profile_checksum\"><input type=\"button\" class=\"green_btn fr b\" value=\"Accept this member\" style=\"width:150px; margin:0 5px;\"></div>";
            }
            $links[2] = "</span><span style=\"position:relative\" class=\"fr\"><div id=\"EXP_LAYER_$index\" style=\"z-index: 1000;position:absolute;display:inline;left:-400px;top:$top\" onclick=\"javascript:check_window('hide_exp_layer()')\"></DIV></span>";
        }
    } elseif ($page == "eoi" || $page == "filtered_eoi") //|| $page=="archive_eoi")
        {
        $links[0] = "";
        $NUDGES   = $pageDetail["NUDGES"];
        $type     = $pageDetail["type"];
        $callNow  = true;
        if ($type == "R") {
            $links[0] .= "<span id=\"SPAN_$index\" class=\"fr\"><div class=\"layerce\" id=\"notinterestLayer_$viewed_profile_checksum\"><input type=\"button\" style=\"width:104px; margin:0 5px;\" class=\"gray_btn fr b\" value=\"Not Interested\"></div>";
            if (is_array($NUDGES) && in_array($viewed_profileid, $NUDGES)) {
                $links[0] .= "<input type=\"button\" class=\"green_btn fr b\" value=\"Express Interest\" style=\"width:120px; margin:0 5px;\" onclick=\"javascript:{check_window('hide_exp_layer()');check_checkbox('PROFILE_$index',$index,'eoi','$show_contacts');}\">";
            } else {
                $links[0] .= "<div class=\"layerce\" id=\"acceptLayer_$viewed_profile_checksum\"><input type=\"button\" class=\"green_btn fr b\" value=\"" . $acceptCaption . "\" style=\"width:" . $acceptWidth . "; margin:0 5px;\"></div>";
            }
            $links[0] .= "<b class=\"mr_5 fl\" style=\"width:110px;padding-top:4px;\">" . $viewContactLinkFl . "</b></span>";
            $links[1] = $addMemberLinkFr . "<span class=\"fr\" style='position:relative'><div id=\"EXP_LAYER_$index\" style=\"z-index: 1000;position:absolute;display:inline;left:$acceptLeft;top:$top\" onclick=\"javascript:check_window('hide_exp_layer()')\"></DIV></span>";
        } elseif ($type == "M") {
            $links[0] = $viewSimilarLink;
            $links[0] .="<span id=\"SPAN_$index\" style='position:relative' class=\"fr\"><div class=\"layerce\" id=\"reminderLayer_$viewed_profile_checksum\"><a  class=\"blink fl\">Send Reminder</a></div>"; 
            $links[1] = $viewContactLink . "</span>" . $addMemberLinkFr . "<span style=\"position:relative\"><div id=\"EXP_LAYER_$index\" style=\"z-index: 1000;position:absolute;display:inline;left:-140px;top:$top\" onclick=\"javascript:check_window('hide_exp_layer()')\"></DIV></span>";
            
            
        }
    } elseif ($page == "messages") {
        $links[0] = $viewSimilarLink;
        $links[0] .="<span id=\"SPAN_$index\" style='position:relative'><div class=\"layerce\" id=\"writeLayer_$viewed_profile_checksum\"><a  class=\"blink fl\">Send Message</a></div>";
        $links[1] = $viewContactLink . "<div id=\"EXP_LAYER_$index\" style=\"z-index: 1000;position:absolute;display:inline;left:$left;top:$top\" onclick=\"javascript:check_window('hide_exp_layer()')\"></DIV></span>";
    }
    $linksLabel = array(
        "links" => $links,
        "callNow" => $callNow,
        "evalue" => $link2
    );
    return $linksLabel;
}
function get_buttons($pageDetail,$countOfPhotosUploaded='')
{
    global $smarty;
    global $checksum;
    global $cc_navigator;
    global $SITE_URL;
    global $data;
    $page            = $pageDetail["page"];
    $filter          = $pageDetail["filter"];
    $totalrec        = count($pageDetail["ALLOW_PROFILES"]);
    $profilechecksum = md5($data["PROFILEID"]) . "i" . $data["PROFILEID"];
    $subscription    = $data["SUBSCRIPTION"];
    $acceptCaption   = "Accept Interest";
    $acceptWidth     = "100px";
    /*if($subscription)
    {
    $acceptCaption = "Accept to View Contact Details";
    $acceptWidth = "172px";
    }*/
    if ($totalrec > 0) {
        if ($page == "favorite") {
            $buttons1 = "<span id=\"SPAN_10\" style='position:relative'><img src=\"images/dwn_arr.gif\" alt=\"jeevansathi\" align=\"absmiddle\" title=\"jeevansathi\"><a href=\"#\" onclick=\"return check_checkbox('PROFILE_10',10,'remove_favourite','$show_contacts');\"><input id=\"top_button1\" type=\"button\" style=\"width:156px;\" value=\"Remove from Shortlist\" class=\"green_btn\"/></a></span>";
            $buttons2 = "<span id=\"SPAN_11\" style='position:relative'><img src=\"images/rgt_arr.gif\" alt=\"jeevansathi\" align=\"absmiddle\" title=\"jeevansathi\"><a href=\"#\" onclick=\"return check_checkbox('PROFILE_11',11,'remove_favourite','$show_contacts');\"><input id=\"bot_button1\" type=\"button\" style=\"width:156px;\" value=\"Remove from Shortlist\" class=\"green_btn\"/></a></span>";
        }
        if ($page == "ignore") {
            $buttons1 = "<span id=\"SPAN_10\" style='position:relative'><img src=\"images/dwn_arr.gif\" alt=\"jeevansathi\" align=\"absmiddle\" title=\"jeevansathi\"><a href=\"#\" onclick=\"return check_checkbox('PROFILE_10',10,'stop_ignoring','$show_contacts');\"><input id=\"top_button1\" type=\"button\" style=\"width:120px;\" value=\"Unblock\" class=\"green_btn\"/></a></span>";
            $buttons2 = "<span id=\"SPAN_11\" style='position:relative'><img src=\"images/rgt_arr.gif\" alt=\"jeevansathi\" align=\"absmiddle\" title=\"jeevansathi\"><a href=\"#\" onclick=\"return check_checkbox('PROFILE_11',11,'stop_ignoring','$show_contacts');\"><input id=\"bot_button1\" type=\"button\" style=\"width:120px;\" value=\"Unblock\" class=\"green_btn\"/></a></span>";
        }
        if ($page == "matches" || $page == "visitors" || $page == "kundli") {
            $buttons1 = "<span style='position:relative'><img src=\"images/dwn_arr.gif\" alt=\"jeevansathi\" align=\"absmiddle\" title=\"jeevansathi\" ><div class=\"layerce\" id=\"expressLayer_multi\"><input id=\"top_button1\" type=\"button\" class=\"green_btn\" value=\"Express Interest\" style=\"width:102px;\"></span></div>";
            $buttons2 = "<span style='position:relative'><img src=\"images/rgt_arr.gif\" alt=\"jeevansathi\" align=\"absmiddle\" title=\"jeevansathi\" ><div class=\"layerce\" id=\"expressLayer_bottom\"><input id=\"bot_button1\" type=\"button\" style=\"width:102px;\" class=\"green_btn\" value=\"Express Interest\"></span></div>";
        }
        if ($page == "photo") {
            if ($filter == "R") {
                if ($data['PROFILEID']) {
                    
                    //Symfony Photo Modification - start
include_once(JsConstants::$docRoot."/commonFiles/SymfonyPictureFunctions.class.php");
                    global $appDotYml;
                    $maxPhotosAllowed      = $appDotYml['all']['max_no_of_photos'];
                    if (!$countOfPhotosUploaded)
                    $countOfPhotosUploaded = SymfonyPictureFunctions::getUserUploadedPictureCount($data['PROFILEID']);
                    $countOfPhotosAllowed  = $maxPhotosAllowed - $countOfPhotosUploaded;
                    
                }
                
                $subMess = "";
                if ($countOfPhotosUploaded == 0)
                    $caption = "Upload your Photo";
                elseif ($countOfPhotosUploaded < $maxPhotosAllowed) {
                    $subMess = "<p style=\"margin-bottom:6px;\">You can upload upto $countOfPhotosAllowed more photos</p>";
                    $caption = "Upload more Photos";
                    
                }
                if ($countOfPhotosUploaded != $maxPhotosAllowed) {
                    $buttons1 = $subMess;
                    $buttons1 .= "<span id=\"SPAN_10\" style='position:relative'><a href=\"$SITE_URL/social/addPhotos?checksum=$checksum&profilechecksum=$profilechecksum&EditWhatNew=FocusPhoto&$cc_navigator\" class=\"green_btn g_btn\">" . $caption . " </a></span>";
                    $buttons2 .= "<span id=\"SPAN_11\" style='position:relative'><a href=\"$SITE_URL/social/addPhotos?checksum=$checksum&profilechecksum=$profilechecksum&EditWhatNew=FocusPhoto&$cc_navigator\" class=\"green_btn g_btn\">" . $caption . " </a></span>";
                    $uploadPhotoLink = "<a href=\"$SITE_URL/social/addPhotos?checksum=$checksum&profilechecksum=$profilechecksum&EditWhatNew=FocusPhoto&$cc_navigator\" class=\"fr blink b\">" . $caption . "</a>";
                    
                }
                //Symfony Photo Modification - end
                
            }
        }
        if ($page == "horoscope") {
            if ($filter == "R") {
                $horoUploaded = false;
                $sql_ast_det  = "SELECT COUNT(*) AS COUNT FROM newjs.ASTRO_DETAILS WHERE PROFILEID = '$data[PROFILEID]'";
                $res_ast_det = mysql_query_optimizer($sql_ast_det) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes", $sql_ast_det, "ShowErrTemplate");
                $row_ast_det = mysql_fetch_array($res_ast_det);
                if ($row_ast_det['COUNT'] > 0)
                    $horoUploaded = true;
                else {
                    $sql_ast_det1 = "SELECT COUNT(*) AS COUNT FROM newjs.HOROSCOPE WHERE PROFILEID = '$data[PROFILEID]'";
                    $res_ast_det1 = mysql_query_optimizer($sql_ast_det1) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes", $sql_ast_det1, "ShowErrTemplate");
                    $row_ast_det1 = mysql_fetch_array($res_ast_det1);
                    if ($row_ast_det1['COUNT'] > 0)
                        $horoUploaded = true;
                }
                if (!$horoUploaded) {
                    $buttons1       = "<span id=\"SPAN_10\" style='position:relative'><input id=\"bot_button1\" type=\"button\" style=\"width:120px;\" value=\"Upload Horoscope\" class=\"green_btn\" onClick=\"window.location.href='$SITE_URL/profile/viewprofile.php?checksum=$checksum&profilechecksum=$profilechecksum&EditWhatNew=AstroData&$cc_navigator'\"/></span>";
                    $buttons2       = "<span id=\"SPAN_11\" style='position:relative'><input id=\"bot_button2\" type=\"button\" style=\"width:120px;\" value=\"Upload Horoscope\" class=\"green_btn\" onClick=\"window.location.href='$SITE_URL/profile/viewprofile.php?checksum=$checksum&profilechecksum=$profilechecksum&EditWhatNew=AstroData&$cc_navigator'\"/></span>";
                    $uploadHoroLink = "<a href=\"$SITE_URL/profile/viewprofile.php?checksum=$checksum&profilechecksum=$profilechecksum&EditWhatNew=AstroData&$cc_navigator\" class=\"fr blink b\">Upload Horoscope</a>";
                }
            }
        }
        if ($page == "eoi" || $page == "filtered_eoi") //$page=="archive_eoi" )
            {
            if ($filter == "R") {
                $buttons1 = "<span id=\"SPAN_10\" style='position:relative'><img src=\"images/dwn_arr.gif\" alt=\"jeevansathi\" align=\"absmiddle\" title=\"jeevansathi\" ><div class=\"layerce\" id=\"acceptLayer_multi\"><input id=\"top_button1\" type=\"button\" class=\"green_btn\" value=\"" . $acceptCaption . "\" style=\"width:" . $acceptWidth . "; margin:0 5px;\" /></div><div class=\"layerce\" id=\"notinterestLayer_multi\"><input id=\"top_button2\" type=\"button\" class=\"gray_btn\" value=\"Not Interested\" style=\"width:104px;\"></div></span>";
                $buttons2 = "<span id=\"SPAN_11\" style='position:relative'><img src=\"images/rgt_arr.gif\" alt=\"jeevansathi\" align=\"absmiddle\" title=\"jeevansathi\" ><div class=\"layerce\" id=\"acceptLayer_bottom\"><input id=\"bot_button1\" type=\"button\" style=\"width:" . $acceptWidth . "; margin:0 5px;\" class=\"green_btn\" value=\"" . $acceptCaption . "\" /></div><div class=\"layerce\" id=\"notinterestLayer_bottom\"><input id=\"bot_button2\" type=\"button\" class=\"gray_btn\" value=\"Not Interested\" style=\"width:104px;\" /></span></div>";
            }
        }
    }
    $smarty->assign("buttons1", $buttons1);
    $smarty->assign("buttons2", $buttons2);
    $pageDetail["uploadPhotoLink"] = $uploadPhotoLink;
    $pageDetail["uploadHoroLink"]  = $uploadHoroLink;
    return $pageDetail;
}

//User having intro calls option
function isApMember($subscription)
{
    $offline = false;
    if (strstr($subscription, "I"))
        $offline = true;
    return $offline;
}

function get_title($pageDetail,$isMobile)
{
    $page                = $pageDetail["page"];
    $filter              = $pageDetail["filter"];
    $totalrec            = $pageDetail["titleCount"];
    $new_count           = $pageDetail["new_count"];
    $search_submit       = $pageDetail["search_submit"];
    $date_search_submit  = $pageDetail["date_search_submit"];
    $offlineCallCountArr = $pageDetail["offlineCallCountArr"];
    $introCallDetail 	 = $pageDetail["introCallDetail"];
    global $smarty;
    /*if($search_submit || $date_search_submit)
    {
    if(!$totalrec)
    $title="0 results";
    else
    $title=$totalrec." result";
    if($totalrec>1)
    $title.="s";
    $title.=" in search";
    $smarty->assign("TITLE",$title);
    return 1;
    }*/
    if ($totalrec != 1)
        $plural = "s";
    $title .= $newCountString . "<span style=\"color:#000\">";
    if($page == "eoi" && $filter == "R") // || $page == "archive_eoi")
        $title .= "People I Have to Respond to";
    elseif ($page == "eoi" && $filter == "M")
        $title .= "People yet to Respond";
    elseif ($page == "accept" && $filter == "A")
        $title .= "All Acceptances";
    elseif ($page == "accept" && $filter == "R")
        $title .= "People who Accepted me";
    elseif ($page == "accept" && $filter == "M")
        $title .= "People I Accepted";
    elseif ($page == "decline" && $filter == "R")
        $title .= "People Not Interested";
    elseif ($page == "decline" && $filter == "M")
        $title .= "People I Declined";
    elseif ($page == "messages" && $filter == "R")
		if($isMobile)
			$title = "My Messages";
		else
			$title .= "Message" . $plural . " Received";
    elseif ($page == "messages" && $filter == "M")
        if($isMobile)
			$title = "My Messages";
		else
			$title .= "Message" . $plural . " Sent";
    elseif ($page == "filtered_eoi" && $filter == "R")
        $title .= "Filtered Interests Received";
    elseif ($page == "photo" && $filter == "R")
        $title .= "Photo Request" . $plural . " Received";
    elseif ($page == "photo" && $filter == "M")
        $title .= "Photo Request" . $plural . " Sent";
    elseif ($page == "horoscope" && $filter == "R")
        $title .= "Horoscope Request" . $plural . " Received";
    elseif ($page == "horoscope" && $filter == "M")
        $title .= "Horoscope Request" . $plural . " Sent";
    elseif ($page == "chat")
        $title .= "Chat Request" . $plural . " Received";
    elseif ($page == "favorite")
        $title .= "People I have Shortlisted";
    elseif ($page == "ignore")
        $title .= "Blocked Members";
    elseif ($page == "matches")
        $title .= "Matches Sent to you on Email";
    elseif ($page == "kundli")
        $title .= "People who Match my Kundli";
    elseif ($page == "visitors")
        $title .= "People who Visited my Profile";
    elseif ($page == 'callnow' && $filter == 'R')
        $title .= "People who have called me";
    elseif ($page == 'callnow' && $filter == 'I')
        $title .= "People whom I have called"; /*********Direct call changes******/ 
    elseif ($page == "viewed_contacts" && $filter == "R")
        $title .= "People Whose Phone/Emails I Viewed";
    elseif ($page == 'intro_call' && $filter == 'R')
        $title .= "People to be Called";
    elseif (($page == "viewed_contacts_by" || $page == "contact_viewers") && $filter == "R")
        $title .= "People who Viewed my Phone/Email";
    $title .= "&nbsp;";
    if ($totalrec == 0 )
	{
		if(!$isMobile)
			$title .= "0 ";
	}
    else{
		if($isMobile && $page == "messages"){
			$title = $title;$title .= "</span>";
		}
		else{
			if($page != 'intro_call' && $filter != 'R'){
				$title .= " - ".$totalrec . " ";$title .= "</span>";
			}
		}
	}
    /*********Direct call changes******/
    if ($page == "viewed_contacts"){
	if($pageDetail["viewedLeft"])
        $title .= "<span style='color:#000'>(" . $pageDetail["viewedLeft"] . " left to be viewed)</span>";
    }
    if ($page =='messages'){
        $memcacheObj=new ProfileMemcacheService($pageDetail["self_profileid"]);
        $new_count=$memcacheObj->get('MESSAGE_NEW');
    }
    if ($new_count )
        $newCountString = "<span class=\"no_b\" style=\"color:#ab0906\">(" . $new_count . " New)</span> ";
    else
        $newCountString = "";
    if ($page == 'intro_call' && $filter == 'R'){
	$avail = $totalrec-$introCallDetail[calledCount];
        $newCountString = " <span class=\"t14 no_b\">(Members already called:$introCallDetail[calledCount], Members still to be called:$avail, Total purchased:$offlineCallCountArr[TOTAL])</span> ";
	$del_intro = $offlineCallCountArr[TOTAL]-$totalrec;
	if($del_intro>0 && $pageDetail["removeAddMemberLink"]){
		$total_intro = $totalrec+$del_intro;
		$title .= " - ".$total_intro." ";
		$title .= "</span>";
		$newCountString = " <span class=\"t14 no_b\">(Members already called:$introCallDetail[calledCount], Members still to be called:$avail, Members deleted: $del_intro, Total purchased:$offlineCallCountArr[TOTAL])</span> ";
	}		
	else {$title .= " - ".$totalrec . " ";$title .= "</span>";}
    }
    if(!$isMobile || $isMobile && $page != "messages")
    $title .= $newCountString;
    $smarty->assign("TITLE", $title);
}
function get_contact_status_profiles($data_3d, $start)
{
    global $data;
    global $smarty;
    global $PAGELEN;
    $profileid      = $data['PROFILEID'];
    $CONTACT_STATUS = array();
    //Checks if array is passed
    $total          = 0;
    $i              = 0;
    if (is_array($data_3d))
        foreach ($data_3d as $key => $val) {
            if ($i >= $start && $total < $PAGELEN) {
                $profileids .= "'$val[PROFILEID]',";
                $total++;
            }
            $i++;
        }
    $profileids    = trim($profileids, ",");
    $sendersIn     = $profileid;
    $receiversIn   = $profileids;
    $contactResult = getResultSet("TYPE,RECEIVER,MSG_DEL,SEEN", $sendersIn, '', $receiversIn);
    if (is_array($contactResult)) {
        foreach ($contactResult as $key => $value) {
            $CONTACT_STATUS[$value["RECEIVER"]] = array(
                "TYPE" => $value["TYPE"],
                "ACTION" => "RECEIVER",
                "SEEN" => $value["SEEN"],
                "MSG_DEL" => $value["MSG_DEL"],
                "RECEIVER" => $value["RECEIVER"]
            );
        }
    }
    $receiversIn   = $profileid;
    $sendersIn     = $profileids;
    $contactResult = getResultSet("TYPE,SENDER,MSG_DEL,SEEN", $sendersIn, '', $receiversIn);
    if (is_array($contactResult)) {
        foreach ($contactResult as $key => $value)
            $CONTACT_STATUS[$value["SENDER"]] = array(
                "TYPE" => $value["TYPE"],
                "ACTION" => "SENDER",
                "SEEN" => $value["SEEN"],
                "MSG_DEL" => $value["MSG_DEL"],
                "SENDER" => $value["SENDER"]
            );
    }
    if ($profileids) {
        $sql = "SELECT STATUS,PROFILEID FROM jsadmin.OFFLINE_MATCHES WHERE MATCH_ID='$profileid' AND PROFILEID IN($profileids)";
        $res = mysql_query_decide($sql) or logError("Due to a temporary problem, your request could not be processed. Please try again after a couple of minutes", $sql, "ShowErrTemplate");
        if (mysql_num_rows($res)) {
            while ($row = mysql_fetch_assoc($res)) {
                $CONTACT_STATUS[$row["PROFILEID"]] = array(
                    "TYPE" => $row["STATUS"],
                    "ACTION" => "OFFLINE"
                );
            }
        }
    }
    return $CONTACT_STATUS;
}

function getAcceptancesReceivedCount($self_profileid, $mysqlObj, $myDb)
{
    $memcacheObj=new ProfileMemcacheService($self_profileid);
    $action["ACCEPT_RECEIVED"]["TOTAL"]=$memcacheObj->get('ACC_ME');
    $action["ACCEPT_RECEIVED"]["NEW"]=$memcacheObj->get('ACC_ME_NEW');
    $action["ACCEPT_RECEIVED"]["SEEN"]=$action["ACCEPT_RECEIVED"]["TOTAL"]-$action["ACCEPT_RECEIVED"]["NEW"];


    /*$sql = "SELECT COUNT(*) AS COUNT,SEEN FROM CONTACTS WHERE SENDER='$self_profileid' AND TYPE='A' GROUP BY SEEN";
    $res = $mysqlObj->executeQuery($sql, $myDb);
    while ($row = $mysqlObj->fetchAssoc($res)) {
        if ($row["SEEN"] == "Y")
            $action["ACCEPT_RECEIVED"]["SEEN"] = $row["COUNT"];
        else
            $action["ACCEPT_RECEIVED"]["NEW"] += $row["COUNT"];
    }
      */
 
/*
    $sql                                = "select COUNT(*) AS COUNT,SEEN from jsadmin.OFFLINE_MATCHES where MATCH_ID='$self_profileid' and STATUS = 'ACC' and SHOW_ONLINE='Y' GROUP BY SEEN";
    $res = mysql_query_decide($sql) or logError("Due to a temporary problem, your request could not be processed. Please try again after a couple of minutes", $sql, "ShowErrTemplate");
    while ($row = mysql_fetch_assoc($res)) {
        if ($row["SEEN"] == "Y")
            $action["ACCEPT_OFFLINE_RECEIVED"]["SEEN"] = $row["COUNT"];
        else
            $action["ACCEPT_OFFLINE_RECEIVED"]["NEW"] += $row["COUNT"];
    }
    $action["ACCEPT_OFFLINE_RECEIVED"]["TOTAL"] = $action["ACCEPT_OFFLINE_RECEIVED"]["SEEN"] + $action["ACCEPT_OFFLINE_RECEIVED"]["NEW"];
    */


    $action["accept"]["R"]["NEW"]               = $action["ACCEPT_RECEIVED"]["NEW"] ;//+ $action["ACCEPT_OFFLINE_RECEIVED"]["NEW"];
    $action["accept"]["R"]["TOTAL"]             = $action["ACCEPT_RECEIVED"]["TOTAL"];// + $action["ACCEPT_OFFLINE_RECEIVED"]["TOTAL"];
    return $action;
}

function getAcceptancesSentCount($self_profileid, $mysqlObj, $myDb)
{
    $memcacheObj=new ProfileMemcacheService($self_profileid);
   /* $sql = "SELECT COUNT(*) AS COUNT,SEEN FROM CONTACTS WHERE RECEIVER='$self_profileid' AND TYPE='A' GROUP BY SEEN";
    $res = $mysqlObj->executeQuery($sql, $myDb);
    while ($row = $mysqlObj->fetchAssoc($res)) {
        if ($row["SEEN"] == "Y")
            $action["accept"]["M"]["SEEN"] = $row["COUNT"];
        else
            $action["accept"]["M"]["NEW"] += $row["COUNT"];
    }*/
    $action["accept"]["M"]["TOTAL"] = $memcacheObj->get("ACC_BY_ME");
    $action["accept"]["M"]["SEEN"] = $action["accept"]["M"]["TOTAL"];
//    $action["accept"]["M"]["SEEN"] + $action["accept"]["M"]["NEW"];
    /*$sql="select COUNT(*) AS COUNT,SEEN from jsadmin.OFFLINE_MATCHES where MATCH_ID='$self_profileid' and STATUS IN('ACC') and SHOW_ONLINE='Y' GROUP BY SEEN";
    $res=mysql_query_decide($sql) or logError("Due to a temporary problem, your request could not be processed. Please try again after a couple of minutes",$sql,"ShowErrTemplate");
    while($row=mysql_fetch_assoc($res))
    {
    if($row["SEEN"] == "Y")
    $action["ACCEPT_OFFLINE_SENT"]["SEEN"] = $row["COUNT"];
    else
    $action["ACCEPT_OFFLINE_SENT"]["NEW"] = $row["COUNT"];
    }
    $action["ACCEPT_OFFLINE_SENT"]["TOTAL"] = $action["ACCEPT_OFFLINE_SENT"]["SEEN"] + $action["ACCEPT_OFFLINE_SENT"]["SEEN"];
    $action["accept"]["M"]["NEW"] = $action["ACCEPT_SENT"]["NEW"] + $action["ACCEPT_OFFLINE_SENT"]["NEW"];
    $action["accept"]["M"]["TOTAL"] = $action["ACCEPT_SENT"]["TOTAL"] + $action["ACCEPT_OFFLINE_SENT"]["TOTAL"];*/
    return $action;
}

function getLandingPage($self_profileid)
{
    $mysqlObj                                        = new Mysql;
    $myDbName                                        = getProfileDatabaseConnectionName($self_profileid, '', $mysqlObj);
    $myDb                                            = $mysqlObj->connect("$myDbName");
    $action                                          = getEoiReceivedCount($self_profileid, $mysqlObj, $myDb);
    $pageDetail["eoiReceivedCount"]["CALC"]          = true;
    $pageDetail["eoiReceivedCount"]["TOTAL"]         = $action["eoi"]["R"]["TOTAL"];
    $pageDetail["eoiReceivedCount"]["NEW"]           = $action["eoi"]["R"]["NEW"];
    $pageDetail["eoiFilteredReceivedCount"]["TOTAL"] = $action["filtered_eoi"]["R"]["TOTAL"];
    $pageDetail["eoiFilteredReceivedCount"]["NEW"]   = $action["filtered_eoi"]["R"]["NEW"];
    $pageDetail["eoiFilteredReceivedCount"]["CALC"]  = true;
    if ($action["eoi"]["R"]["NEW"]) {
        $page   = "eoi";
        $filter = "R";
    } else {
        $action                                     = getAcceptancesReceivedCount($self_profileid, $mysqlObj, $myDb);
        $acceptRcount                               = $action["accept"]["R"]["TOTAL"];
        $pageDetail["acceptReceivedCount"]["CALC"]  = true;
        $pageDetail["acceptReceivedCount"]["TOTAL"] = $action["accept"]["R"]["TOTAL"];
        $pageDetail["acceptReceivedCount"]["NEW"]   = $action["accept"]["R"]["NEW"];
        if ($action["accept"]["R"]["NEW"]) {
            $page   = "accept";
            $filter = "R";
        } else {
            $action                                 = getAcceptancesSentCount($self_profileid, $mysqlObj, $myDb);
            $pageDetail["acceptSentCount"]["CALC"]  = true;
            $pageDetail["acceptSentCount"]["TOTAL"] = $action["accept"]["M"]["TOTAL"];
            $totalAcceptences                       = $acceptRcount + $action["accept"]["M"]["TOTAL"];
            if ($totalAcceptences) {
                $page   = "accept";
                $filter = "A";
            } else {
                $page   = "eoi";
                $filter = "R";
            }
        }
    }
    $pageDetail["page"]     = $page;
    $pageDetail["filter"]   = $filter;
    $pageDetail["action"]   = $action;
    $pageDetail["mysqlObj"] = $mysqlObj;
    $pageDetail["myDb"]     = $myDb;
    return $pageDetail;
}
function getEoiReceivedCount($self_profileid, $mysqlObj, $myDb)
{
    $day_90       = mktime(0, 0, 0, date("m"), date("d") - 90, date("Y")); // To get the time for back 90 days
    $back_90_days = date("Y-m-d", $day_90);
    $time_clause  = "AND TIME>='$back_90_days 00:00:00'";
    $sql          = "SELECT COUNT(*) AS COUNT,SEEN,FILTERED FROM CONTACTS WHERE RECEIVER='$self_profileid' AND TYPE='I' " . $time_clause . " GROUP BY SEEN,FILTERED";
    $res          = $mysqlObj->executeQuery($sql, $myDb);
    while ($row = $mysqlObj->fetchAssoc($res)) {
        if ($row["FILTERED"] == "Y") {
            if ($row["SEEN"] == "Y")
                $action["EOI_FILTERED_RECEIVED"]["SEEN"] = $row["COUNT"];
            else
                $action["EOI_FILTERED_RECEIVED"]["NEW"] += $row["COUNT"];
        } else {
            if ($row["SEEN"] == "Y")
                $action["EOI_RECEIVED"]["SEEN"] = $row["COUNT"];
            else
                $action["EOI_RECEIVED"]["NEW"] += $row["COUNT"];
        }
    }
    $action["EOI_RECEIVED"]["TOTAL"] = $action["EOI_RECEIVED"]["SEEN"] + $action["EOI_RECEIVED"]["NEW"];
  /*  
   $sql = "select COUNT(*) AS COUNT,SEEN from jsadmin.OFFLINE_MATCHES where MATCH_ID='$self_profileid' and STATUS IN('N','NNOW') and MATCH_DATE>='$back_90_days' AND SHOW_ONLINE='Y' GROUP BY SEEN";
    $res = mysql_query_decide($sql) or logError("Due to a temporary problem, your request could not be processed. Please try again after a couple of minutes", $sql, "ShowErrTemplate");
    while ($row = mysql_fetch_assoc($res)) {
        if ($row["SEEN"] == "Y")
            $action["EOI_OFFLINE_RECEIVED"]["SEEN"] = $row["COUNT"];
        else
            $action["EOI_OFFLINE_RECEIVED"]["NEW"] += $row["COUNT"];
    }*/

   // $action["EOI_OFFLINE_RECEIVED"]["TOTAL"] = $action["EOI_OFFLINE_RECEIVED"]["SEEN"] + $action["EOI_OFFLINE_RECEIVED"]["NEW"];
    $action["eoi"]["R"]["NEW"]               = $action["EOI_RECEIVED"]["NEW"] ;//+ $action["EOI_OFFLINE_RECEIVED"]["NEW"];
    $action["eoi"]["R"]["TOTAL"]             = $action["EOI_RECEIVED"]["TOTAL"];// + $action["EOI_OFFLINE_RECEIVED"]["TOTAL"];
    $action["filtered_eoi"]["R"]["TOTAL"]    = $action["EOI_FILTERED_RECEIVED"]["SEEN"] + $action["EOI_FILTERED_RECEIVED"]["NEW"];
    $action["filtered_eoi"]["R"]["NEW"]      = $action["EOI_FILTERED_RECEIVED"]["NEW"];
    return $action;
}

function getPhotoSentCount($self_profileid, $mysqlObj, $myDb, $DECLINED_PROFILES)
{
    $photoSentCount = 0;
    if ($DECLINED_PROFILES) {
        $sql = "SELECT PROFILEID_REQ_BY photoSent FROM newjs.PHOTO_REQUEST WHERE PROFILEID='$self_profileid'";
        $res = $mysqlObj->executeQuery($sql, $myDb);
        while ($row = $mysqlObj->fetchAssoc($res)) {
            if (!in_array($photoSent, $DECLINED_PROFILES))
                $photoSentCount = $photoSentCount + 1;
        }
    } else {
        $sql            = "SELECT PROFILEID_REQ_BY photoSent FROM newjs.PHOTO_REQUEST WHERE PROFILEID='$self_profileid'";
        $res            = $mysqlObj->executeQuery($sql, $myDb);
        $row            = $mysqlObj->fetchAssoc($res);
        $photoSentCount = $row["photoSent"];
    }
    $action["photo"]["M"]["TOTAL"] = $photoSentCount;
    return $action;
}

function getHoroscopeSentCount($self_profileid, $mysqlObj, $myDb, $DECLINED_PROFILES)
{
    $horoSentCount = 0;
    if ($DECLINED_PROFILES) {
        $sql = "SELECT PROFILEID_REQUEST_BY horoSent FROM newjs.HOROSCOPE_REQUEST WHERE PROFILEID='$self_profileid'";
        $res = $mysqlObj->executeQuery($sql, $myDb);
        while ($row = $mysqlObj->fetchAssoc($res)) {
            if (!in_array($row["horoSent"], $DECLINED_PROFILES))
                $horoSentCount = $horoSentCount + 1;
        }
    } else {
        $sql           = "SELECT count(*) horoSent FROM newjs.HOROSCOPE_REQUEST WHERE PROFILEID='$self_profileid'";
        $res           = $mysqlObj->executeQuery($sql, $myDb);
        $row           = $mysqlObj->fetchAssoc($res);
        $horoSentCount = $row["horoSent"];
    }
    $action["horoscope"]["M"]["TOTAL"] = $horoSentCount;
    return $action;
}

function getMessageSentCount($self_profileid, $mysqlObj, $myDb)
{
    $sql = "SELECT COUNT(DISTINCT RECEIVER) AS COUNT FROM newjs.MESSAGE_LOG WHERE SENDER='$self_profileid' AND IS_MSG='Y' AND TYPE='R'";
    $res = $mysqlObj->executeQuery($sql, $myDb);
    while ($row = $mysqlObj->fetchAssoc($res)) {
        $action["messages"]["M"]["TOTAL"] = $row["COUNT"];
    }
    return $action;
}


function getEoiSentCount($self_profileid, $mysqlObj, $myDb)
{
   $memcacheObj=new ProfileMemcacheService($self_profileid);
/*
    $sql = "SELECT COUNT(*) AS COUNT FROM CONTACTS WHERE SENDER='$self_profileid' AND TYPE='I'";
    $res = $mysqlObj->executeQuery($sql, $myDb);
    while ($row = $mysqlObj->fetchAssoc($res)) {
        $action["EOI_SENT"]["TOTAL"] = $row["COUNT"];
    }
    
    $sql = "select COUNT(*) AS COUNT from jsadmin.OFFLINE_MATCHES where MATCH_ID='$self_profileid' and STATUS IN('NACC','SL') and SHOW_ONLINE='Y'";
    $res = mysql_query_decide($sql) or logError("Due to a temporary problem, your request could not be processed. Please try again after a couple of minutes", $sql, "ShowErrTemplate");
    while ($row = mysql_fetch_assoc($res)) {
        $action["EOI_OFFLINE_SENT"]["TOTAL"] = $row["COUNT"];
    }
    */
    $action["eoi"]["M"]["TOTAL"] = $memcacheObj->get("NOT_REP");//$action["EOI_SENT"]["TOTAL"] + $action["EOI_OFFLINE_SENT"]["TOTAL"];
    return $action;
}

function contact_center_leftpanel($pageDetail)
{
    $memcacheObj=new ProfileMemcacheService($pageDetail["self_profileid"]);
    $current_page   = $pageDetail["page"];
    $new_count      = $pageDetail["new_count"];
    $filter         = $pageDetail["filter"];
    $ARC_SAX        = $pageDetail["ARC_SAX"];
    $self_profileid = $pageDetail["self_profileid"];
    $GENDER         = $pageDetail["GENDER"];
    global $smarty;
    $mysqlObj = $pageDetail["mysqlObj"];
    $myDb     = $pageDetail["myDb"];
    $action   = $pageDetail["action"];
    if (!$mysqlObj && !$myDb) {
        $mysqlObj = new Mysql;
        $myDbName = getProfileDatabaseConnectionName($self_profileid, '', $mysqlObj);
        $myDb     = $mysqlObj->connect("$myDbName");
    }
    $DECLINED_PROFILES = getCancelDeclinedContacts($self_profileid, "", $mysqlObj, $myDb);
    $time_clause       = "";
    
    /* IVR- Callnow feature added
     * Calculates the count for calls (made/received/missed)
     */
    /*	if($current_page=='callnow' && $filter=='A')
    {
    $action["callnow"]["A"]["NEW"] = $new_count;
    $action["callnow"]["A"]["TOTAL"] = count($ARC_SAX);
    }else{
    $calls   = getTotalCallCount($self_profileid);
    $action["callnow"]["A"]["NEW"] =$calls['NEW'];
    $action["callnow"]["A"]["TOTAL"] =$calls['TOTAL'];
    }
    */
    if ($current_page == 'callnow' && $filter == 'R') {
        $action["callnow"]["R"]["NEW"]   = $new_count;
        $action["callnow"]["R"]["TOTAL"] = count($ARC_SAX);
    } else {
        $callsReceived                   = getCallnowResultCount($self_profileid, 'RECEIVER_PID', 'R');
        $action["callnow"]["R"]["NEW"]   = $callsReceived["callnow"]["R"]["NEW"];
        $action["callnow"]["R"]["TOTAL"] = $callsReceived["callnow"]["R"]["TOTAL"];
    }
    
    /*	if($current_page=='callnow' && $filter=='M'){
    $action["callnow"]["M"]["NEW"] = $new_count;
    $action["callnow"]["M"]["TOTAL"] = count($ARC_SAX);
    }
    else{
    $callsMissed   = getCallnowResultCount($self_profileid,'RECEIVER_PID','M');
    $action["callnow"]["M"]["NEW"]   = $callsMissed["callnow"]["M"]["NEW"];
    $action["callnow"]["M"]["TOTAL"] = $callsMissed["callnow"]["M"]["TOTAL"];
    }
    */
    if ($current_page == 'callnow' && $filter == 'I') {
        $action["callnow"]["I"]["TOTAL"] = count($ARC_SAX);
    } else {
        $callsMissed                     = getCallnowResultCount($self_profileid, 'CALLER_PID', 'I');
        $action["callnow"]["I"]["TOTAL"] = $callsMissed["callnow"]["I"]["TOTAL"];
    }
    
    // Ends IVR-Callnow fetaure
    
    if ($current_page == "accept" && $filter == 'A') {
        $action["accept"]["A"]["NEW"]   = $new_count;
        $action["accept"]["A"]["TOTAL"] = count($ARC_SAX);
    } else {
        if ($pageDetail["acceptReceivedCount"]["CALC"]) {
            $action["accept"]["R"]["NEW"]   = $pageDetail["acceptReceivedCount"]["NEW"];
            $action["accept"]["R"]["TOTAL"] = $pageDetail["acceptReceivedCount"]["TOTAL"];
        } else {
            $actionA                                    = getAcceptancesReceivedCount($self_profileid, $mysqlObj, $myDb);
            $pageDetail["acceptReceivedCount"]["CALC"]  = true;
            $pageDetail["acceptReceivedCount"]["TOTAL"] = $actionA["accept"]["R"]["TOTAL"];
            $pageDetail["acceptReceivedCount"]["NEW"]   = $actionA["accept"]["R"]["NEW"];
            $action["accept"]["R"]["NEW"]               = $actionA["accept"]["R"]["NEW"];
            $action["accept"]["R"]["TOTAL"]             = $actionA["accept"]["R"]["TOTAL"];
        }
        if ($pageDetail["acceptSentCount"]["CALC"]) {
            $action["accept"]["M"]["TOTAL"] = $pageDetail["acceptSentCount"]["TOTAL"];
        } else {
            $actionAS                               = getAcceptancesSentCount($self_profileid, $mysqlObj, $myDb);
            $action["accept"]["M"]["TOTAL"]         = $actionAS["accept"]["M"]["TOTAL"];
            $pageDetail["acceptSentCount"]["CALC"]  = true;
            $pageDetail["acceptSentCount"]["TOTAL"] = $actionAS["accept"]["M"]["TOTAL"];
        }
        $action["accept"]["A"]["TOTAL"] = $action["accept"]["R"]["TOTAL"] + $action["accept"]["M"]["TOTAL"];
        $action["accept"]["A"]["NEW"]   = $action["accept"]["R"]["NEW"];
    }
    
    if ($current_page == "accept" && $filter == 'R') {
        $action["accept"]["R"]["NEW"]   = $new_count;
        $action["accept"]["R"]["TOTAL"] = count($ARC_SAX);
    } else {
        if ($pageDetail["acceptReceivedCount"]["CALC"]) {
            $action["accept"]["R"]["NEW"]   = $pageDetail["acceptReceivedCount"]["NEW"];
            $action["accept"]["R"]["TOTAL"] = $pageDetail["acceptReceivedCount"]["TOTAL"];
        } else {
            $actionA                        = getAcceptancesReceivedCount($self_profileid, $mysqlObj, $myDb);
            $action["accept"]["R"]["NEW"]   = $actionA["accept"]["R"]["NEW"];
            $action["accept"]["R"]["TOTAL"] = $actionA["accept"]["R"]["TOTAL"];
        }
    }
    
    if ($current_page == "accept" && $filter == 'M') {
        $action["accept"]["M"]["TOTAL"] = count($ARC_SAX);
    } else {
        if ($pageDetail["acceptSentCount"]["CALC"]) {
            $action["accept"]["M"]["TOTAL"] = $pageDetail["acceptSentCount"]["TOTAL"];
        } else {
            $actionS                        = getAcceptancesSentCount($self_profileid, $mysqlObj, $myDb);
            $action["accept"]["M"]["TOTAL"] = $actionS["accept"]["M"]["TOTAL"];
        }
    }
    
    if ($current_page == "eoi" && $filter == 'R') {
        $action["eoi"]["R"]["NEW"]   = $new_count;
        $action["eoi"]["R"]["TOTAL"] = count($ARC_SAX);
    } else {
        if ($pageDetail["eoiReceivedCount"]["CALC"]) {
            $action["eoi"]["R"]["NEW"]   = $pageDetail["eoiReceivedCount"]["NEW"];
            $action["eoi"]["R"]["TOTAL"] = $pageDetail["eoiReceivedCount"]["TOTAL"];
        } else {
            //$actionE                                         = getEoiReceivedCount($self_profileid, $mysqlObj, $myDb);
            $action["eoi"]["R"]["NEW"]                       = $memcacheObj->get('AWAITING_RESPONSE_NEW');
            $action["eoi"]["R"]["TOTAL"]                     = $memcacheObj->get('AWAITING_RESPONSE');
            $pageDetail["eoiFilteredReceivedCount"]["CALC"]  = true;
            $pageDetail["eoiFilteredReceivedCount"]["TOTAL"] = $memcacheObj->get('FILTERED');
            $pageDetail["eoiFilteredReceivedCount"]["NEW"]   = $memcacheObj->get('FILTERED_NEW');
        }
    }
    
    if (!($current_page == "filtered_eoi" && $filter == "R")) {
        if ($pageDetail["eoiFilteredReceivedCount"]["CALC"]) {
            $action["filtered_eoi"]["R"]["TOTAL"] = $pageDetail["eoiFilteredReceivedCount"]["TOTAL"];
            $action["filtered_eoi"]["R"]["NEW"]   = $pageDetail["eoiFilteredReceivedCount"]["NEW"];
        } else {
            $action['filtered_eoi']['R']['NEW'] = $memcacheObj->get('FILTERED_NEW');
            $action["filtered_eoi"]["R"]["TOTAL"] = $memcacheObj->get('FILTERED');
            /*$day_90       = mktime(0, 0, 0, date("m"), date("d") - 90, date("Y")); // To get the time for back 90 days
            $back_90_days = date("Y-m-d", $day_90);
            $time_clause  = "TIME>='$back_90_days 00:00:00'";
            $sql          = "SELECT COUNT(*) AS COUNT,SEEN FROM CONTACTS WHERE RECEIVER='$self_profileid' AND TYPE='I' AND " . $time_clause . " AND FILTERED='Y' GROUP BY SEEN";
            $res          = $mysqlObj->executeQuery($sql, $myDb);
            while ($row = $mysqlObj->fetchAssoc($res)) {
                if ($row["SEEN"] == "Y")
                    $action["filtered_eoi"]["R"]["SEEN"] = $row["COUNT"];
                else
                    $action["filtered_eoi"]["R"]["NEW"] += $row["COUNT"];
            }
            $action["filtered_eoi"]["R"]["TOTAL"] = $action["filtered_eoi"]["R"]["SEEN"] + $action["filtered_eoi"]["R"]["NEW"];
            */
        }
       } 
     else {
        $action["filtered_eoi"]["R"]["NEW"]   = $new_count;
        $action["filtered_eoi"]["R"]["TOTAL"] = count($ARC_SAX);
    }
    if (!($current_page == "decline" && $filter == "R")) {
        /*$sql = "SELECT COUNT(*) AS COUNT,SEEN FROM CONTACTS WHERE SENDER='$self_profileid' AND TYPE='D' GROUP BY SEEN";
        $res = $mysqlObj->executeQuery($sql, $myDb);
        while ($row = $mysqlObj->fetchAssoc($res)) {
            if ($row["SEEN"] == "Y")
                $action["DECLINE_RECEIVED"]["SEEN"] = $row["COUNT"];
            else
                $action["DECLINE_RECEIVED"]["NEW"] += $row["COUNT"];
        }*/
        
      /*  $sql = "select COUNT(*) AS COUNT,SEEN from CONTACTS WHERE RECEIVER = '$self_profileid' AND TYPE IN ('E','C') GROUP BY SEEN";
        $res = $mysqlObj->executeQuery($sql, $myDb);
        while ($row = $mysqlObj->fetchAssoc($res)) {
            if ($row["SEEN"] == "Y")
                $action["CANCEL_RECEIVED"]["SEEN"] = $row["COUNT"];
            else
                $action["CANCEL_RECEIVED"]["NEW"] += $row["COUNT"];
        }*/
        
        $action["decline"]["R"]["NEW"]   = $memcacheObj->get("DEC_ME_NEW");
        $action["decline"]["R"]["TOTAL"] = $memcacheObj->get("DEC_ME");
        $action["decline"]["R"]["SEEN"]  = $action["decline"]["R"]["TOTAL"]  - $action["decline"]["R"]["NEW"] ;

    } else {
        $action["decline"]["R"]["NEW"]  = $new_count;
        $action["decline"]["R"]["TOTAL"] = count($ARC_SAX);
    }
    
    if (!($current_page == "messages" && $filter == "R")) {
      /*  $message_count = 0;
        $mess_sender   = array();
        $i             = 0;
        $sql           = "SELECT DISTINCT(SENDER) sender,SEEN FROM newjs.MESSAGE_LOG WHERE RECEIVER='$self_profileid' AND IS_MSG='Y' AND TYPE='R'";
        $res = $mysqlObj->executeQuery($sql, $myDb) or logError("Due to a temporary problem, your request could not be processed. Please try again after a couple of minutes", $sql, "ShowErrTemplate");
        ;
        while ($row = mysql_fetch_array($res)) {
            $senders[]                    = $row['sender'];
            $mess_sender[$i]["PROFILEID"] = $row["sender"];
            $mess_sender[$i]["SEEN"]      = $row["SEEN"];
            $i++;
        }
        if ($senders) {
            $message_count = count($senders) - count($DECLINED_PROFILES);
        }
        $mess_seen = 0;
        $mess_new  = 0;
        foreach ($mess_sender as $key => $val) {
            if (!in_array($val["PROFILEID"], $DECLINED_PROFILES)) {
                if ($val["SEEN"] == "Y")
                    $mess_seen = $mess_seen + 1;
                else
                    $mess_new = $mess_new + 1;
            }
        }*/
        $action["messages"]["R"]["NEW"]   = $memcacheObj->get('MESSAGE_NEW');
        $action["messages"]["R"]["TOTAL"] = $memcacheObj->get('MESSAGE');
        
    } else {
        $action["messages"]["R"]["NEW"]   = $new_count;
        $action["messages"]["R"]["TOTAL"] = count($ARC_SAX);
    }
    
    if ($current_page == "photo" && $filter == "R") {
        $action["photo"]["R"]["TOTAL"] = count($ARC_SAX);
        $action["photo"]["R"]["NEW"]   = $new_count;
    } else {
       /* $photo_seen = 0;
        $photo_new  = 0;
        $sql        = "SELECT PROFILEID sender,SEEN FROM newjs.PHOTO_REQUEST WHERE PROFILEID_REQ_BY='$self_profileid'";
        $res        = $mysqlObj->executeQuery($sql, $myDb);
        while ($row = mysql_fetch_array($res)) {
            $senders[] = $row['sender'];
            if (!in_array($row["sender"], $DECLINED_PROFILES)) {
                if ($row["SEEN"] == "Y")
                    $photo_seen = $photo_seen + 1;
                else
                    $photo_new = $photo_new + 1;
            }
        }*/
        $action["photo"]["R"]["NEW"]   = $memcacheObj->get('PHOTO_REQUEST_NEW');
        $action["photo"]["R"]["TOTAL"] = $memcacheObj->get('PHOTO_REQUEST');
        
    }
    
    if ($current_page == "intro_call" && $filter == "R") {
        $action["intro_call"]["R"]["TOTAL"] = count($ARC_SAX);
    } else {
        $introCall                          = getIntroCallHistory($self_profileid);
        //$introCall                          = getIntroCallHistory($self_profileid,$pageDetail["offlineCallCountArr"]["EXPIRED"]);
        $action["intro_call"]["R"]["TOTAL"] = $introCall["TOTAL"];
    }
    
    
    /********Temporarily removed
    if($current_page=="eoi" && $filter=='M')
    {
    $action["eoi"]["M"]["TOTAL"] = $pageDetail["titleCount"];
    }
    else
    {
    $actionM = getEoiSentCount($self_profileid,$mysqlObj, $myDb);
    $action["eoi"]["M"]["TOTAL"] = $actionM["eoi"]["M"]["TOTAL"];
    }
    
    if($current_page=="photo" && $filter=="M")
    {
    $action["photo"]["M"]["TOTAL"] = count($ARC_SAX);
    }
    else
    {
    $actionP = getPhotoSentCount($self_profileid,$mysqlObj, $myDb, $DECLINED_PROFILES);	
    $action["photo"]["M"]["TOTAL"] = $actionP["photo"]["M"]["TOTAL"];
    }
    
    if($current_page=="horoscope" && $filter=="M")
    {
    $action["horoscope"]["M"]["TOTAL"] = count($ARC_SAX);
    }
    else
    {
    $actionH = getHoroscopeSentCount($self_profileid,$mysqlObj, $myDb, $DECLINED_PROFILES);
    $action["horoscope"]["M"]["TOTAL"] = $actionH["horoscope"]["M"]["TOTAL"];
    }
    
    if($current_page=="messages" && $filter=="M")
    {
    $action["messages"]["M"]["TOTAL"] = count($ARC_SAX);
    }
    else
    {
    $actionM = getMessageSentCount($self_profileid,$mysqlObj, $myDb);
    $action["messages"]["M"]["TOTAL"] = $actionM["messages"]["M"]["TOTAL"];
    }
    
    ********/
    
    /*********Direct call changes******/
    if ($pageDetail["CALL_DIRECT"] && $pageDetail["paid"]) {
        if ($current_page == "viewed_contacts" && $filter == "R") {
            $action["viewed_contacts"]["R"]["TOTAL"] = count($ARC_SAX);
            $action["viewed_contacts"]["R"]["LEFT"]  = $pageDetail["viewedLeft"];
        } else {
            $cnt = 0;
            $ignoredProfiles = getIgnoredProfile($self_profileid);
            $str = "";
            if(is_array($ignoredProfiles))
            {
                $ignoreStr = implode(",",$ignoredProfiles);
                $str = " AND VIEWED NOT IN ($ignoreStr)";
            }
            $sql = "SELECT COUNT(*) cnt FROM jsadmin.VIEW_CONTACTS_LOG WHERE VIEWER='$self_profileid' AND SOURCE='".CONTACT_ELEMENTS::CALL_DIRECTLY_TRACKING."'".$str;
            $res = mysql_query_decide($sql) or logError("Due to a temporary problem, your request could not be processed. Please try again after a couple of minutes", $sql, "ShowErrTemplate");
            while ($row = mysql_fetch_array($res)) {
                $cnt = $row["cnt"];
            }
            $action["viewed_contacts"]["R"]["TOTAL"] = $cnt;
            $action["viewed_contacts"]["R"]["LEFT"]  = $pageDetail["viewedLeft"];
        }
    }
    if ($pageDetail["CALL_DIRECT"]) {
        if (($current_page == "viewed_contacts_by" || $current_page == "contact_viewers") && $filter == "R") {
            $action["viewed_contacts_by"]["R"]["TOTAL"] = count($ARC_SAX);
            $action["viewed_contacts_by"]["R"]["NEW"]   = $new_count;
        } else {

$notInString='';
       if ($DECLINED_PROFILES)   {
        $ignoredProfiles = getIgnoredProfile($self_profileid);
        if(is_array($ignoredProfiles))
            $DECLINED_PROFILES = array_merge($DECLINED_PROFILES,$ignoredProfiles);
        $temp_declined = implode(",", $DECLINED_PROFILES);
        $notInString = " AND VIEWER NOT IN (".$temp_declined.") ";

}
            $sql = "SELECT COUNT(*) cnt,SEEN FROM jsadmin.VIEW_CONTACTS_LOG WHERE VIEWED='$self_profileid' ".$notInString." AND SOURCE='".CONTACT_ELEMENTS::CALL_DIRECTLY_TRACKING."' GROUP BY SEEN";
            $res = mysql_query_decide($sql) or logError("Due to a temporary problem, your request could not be processed. Please try again after a couple of minutes", $sql, "ShowErrTemplate");
          $notViewed=0;
          $viewedByUser=0;
            while ($row = mysql_fetch_array($res)) {
                
                if ($row["SEEN"] == "Y")
                    $viewedByUser = $row["cnt"];
                else
                    $notViewed = $row["cnt"];
                
            }
            $action["viewed_contacts_by"]["R"]["NEW"]   = $notViewed;
            $action["viewed_contacts_by"]["R"]["TOTAL"] = $notViewed + $viewedByUser;
        }
    }
    /********Ends here*********/
    
    if ($current_page == "horoscope" && $filter == "R") {
        $action["horoscope"]["R"]["TOTAL"] = count($ARC_SAX);
        $action["horoscope"]["R"]["NEW"]   = $new_count;
    } else {
        /*$horo_seen = 0;
        $horo_new  = 0;
        $sql       = "SELECT PROFILEID sender,SEEN FROM newjs.HOROSCOPE_REQUEST WHERE PROFILEID_REQUEST_BY='$self_profileid'";
        $res       = $mysqlObj->executeQuery($sql, $myDb);
        while ($row = mysql_fetch_array($res)) {
            if (!in_array($row["sender"], $DECLINED_PROFILES)) {
                if ($row["SEEN"] == "Y")
                    $horo_seen = $horo_seen + 1;
                else
                    $horo_new = $horo_new + 1;
            }
        }*/
        $action["horoscope"]["R"]["NEW"]   = $memcacheObj->get('HOROSCOPE_NEW');
        $action["horoscope"]["R"]["TOTAL"] = $memcacheObj->get('HOROSCOPE');
        
    }
    
    if ($current_page == "chat") {
        $action["chat"]["A"]["TOTAL"] = $pageDetail["titleCount"];
    } else {
        $chatCount = 0;
        $ignoredProfiles = getIgnoredProfile($self_profileid);
        $str = "";
        if(is_array($ignoredProfiles))
        {
            $ignoreStr = implode(",",$ignoredProfiles);
            $str = " AND SENDER NOT IN ($ignoreStr)";
        }
        $sql       = "SELECT count(DISTINCT(SENDER)) COUNT FROM userplane.CHAT_REQUESTS WHERE RECEIVER='$self_profileid'".$str;// UNION (SELECT count(DISTINCT(RECEIVER)) COUNT FROM userplane.CHAT_REQUESTS WHERE SENDER='$self_profileid')";
        $res = mysql_query_decide($sql) or logError("Due to a temporary problem, your request could not be processed. Please try again after a couple of minutes", $sql, "ShowErrTemplate");
        while ($row = mysql_fetch_array($res)) {
            $chatCount = $chatCount + $row["COUNT"];
        }
        $action["chat"]["A"]["TOTAL"] = $chatCount;
    }
    
    $smarty->assign("action", $action);
    
    connect_db();
}

function getContactedProfiles($sender_profileid, $receiver_profileid)
{
    $contactedProfiles = array();
    if ($sender_profileid && $receiver_profileid) {
        $contactStat = get_contact_status_using_in($sender_profileid, $receiver_profileid);
        if ($contactStat) {
            foreach ($contactStat as $key => $val) {
                $contactedProfiles[] = $val["PROFILEID"];
            }
        }
        
    }
    return $contactedProfiles;
}

function getCancelDeclinedContacts($self_profileid, $profilesArr = "", $mysqlObj, $db)
{
    $DECLINED_PROFILES = array();
    //if($self_profileid && $profilesArr)
    if ($self_profileid) {
        //$profileIds = implode(',',$profilesArr);
        //$sql_msg = "SELECT SENDER FROM newjs.CONTACTS WHERE newjs.CONTACTS.SENDER IN($profileIds) AND newjs.CONTACTS.RECEIVER = '$self_profileid' AND newjs.CONTACTS.TYPE IN ('D','C')";
        $sql_msg = "SELECT SENDER FROM newjs.CONTACTS WHERE newjs.CONTACTS.RECEIVER = '$self_profileid' AND newjs.CONTACTS.TYPE IN ('D','C')";
        $res_msg = $mysqlObj->executeQuery($sql_msg, $db);
        while ($row_msg = $mysqlObj->fetchAssoc($res_msg)) {
            $DECLINED_PROFILES[] = $row_msg['SENDER'];
        }
        
        //$sql_msg = "SELECT RECEIVER FROM newjs.CONTACTS WHERE newjs.CONTACTS.RECEIVER IN($profileIds) AND newjs.CONTACTS.SENDER = '$self_profileid' AND newjs.CONTACTS.TYPE IN('C','D')";
        $sql_msg = "SELECT RECEIVER FROM newjs.CONTACTS WHERE newjs.CONTACTS.SENDER = '$self_profileid' AND newjs.CONTACTS.TYPE IN('C','D')";
        $res_msg = $mysqlObj->executeQuery($sql_msg, $db);
        while ($row_msg = $mysqlObj->fetchAssoc($res_msg)) {
            $DECLINED_PROFILES[] = $row_msg['RECEIVER'];
        }
    }
    return $DECLINED_PROFILES;
}
function getSearchTitle($totalRecords, $page)
{
    if ($page == "decline")
        $title = "Search within " . $totalRecords . " Declined received";
    elseif ($page == "eoi")
        $title = "Search within " . $totalRecords . " Expression of Interests received";
    elseif ($page == "filtered_eoi")
        $title = "Search within " . $totalRecords . " Filtered Expression of Interests received"; /*elseif($page == "archive_eoi")
    $title = "Search within ".$totalRecords." Archived Expression of Interests received";*/ 
    elseif ($page == "matches")
        $title = "Filter by date in " . $totalRecords . " matches";
    elseif ($page == "visitors")
        $title = "Filter by date in " . $totalRecords . " visitors profiles";
    else
        $title = "Search within " . $totalRecords . " contacts";
    return $title;
}

function getClearSearchUrl($page, $CHECKSUM, $SITE_URL)
{
    if ($page == "eoi")
        $url = $SITE_URL . "/profile/contacts_made_received.php?checksum=" . $CHECKSUM . "&page=eoi&filter=R";
    elseif ($page == "decline")
        $url = $SITE_URL . "/profile/contacts_made_received.php?checksum=" . $CHECKSUM . "&page=decline&filter=R"; /*elseif($page == "archive_eoi")
    $url = $SITE_URL."/profile/contacts_made_received.php?checksum=".$CHECKSUM."&page=archive_eoi&filter=R";*/ 
    elseif ($page == "filtered_eoi")
        $url = $SITE_URL . "/profile/contacts_made_received.php?checksum=" . $CHECKSUM . "&page=filtered_eoi&filter=R";
    elseif ($page == "visitors")
        $url = $SITE_URL . "/profile/contacts_made_received.php?checksum=" . $CHECKSUM . "&page=visitors&filter=R";
    elseif ($page == "matches")
        $url = $SITE_URL . "/profile/contacts_made_received.php?checksum=" . $CHECKSUM . "&page=matches&filter=R";
    return $url;
}

function getCurrentKey($profileid, $profileArr)
{
    if ($profileArr) {
        if (is_array($profileArr[0])) {
            foreach ($profileArr as $key => $val) {
                $profilesArr[] = $val['PROFILEID'];
            }
            $currentKey = array_search($profileid, $profilesArr);
        } else
            $currentKey = array_search($profileid, $profileArr);
    }
    return $currentKey;
}

function getProfileString($profileArr, $currentKey)
{
    if ($profileArr) {
        $keyProfile     = array();
        $resultsPerPage = 10;
        foreach ($profileArr as $key => $value) {
            $val             = is_array($value) ? $value['PROFILEID'] : $value;
            //echo $key.' '.$val."<br>";
            $profilechecksum = md5($val) . "i" . $val;
            if (($currentKey - $key) <= $resultsPerPage)
                $keyProfile[] = $key . "|*|" . $profilechecksum;
            elseif ($currentKey == $key)
                $keyProfile[] = $key . "|*|" . $profilechecksum;
            elseif (($key - $currentKey) >= $resultsPerPage)
                $keyProfile[] = $key . "|*|" . $profilechecksum;
            if (count($keyProfile) == 20)
                break;
        }
        if ($keyProfile)
            return implode('|X|', $keyProfile);
    }
}

function getNextProfile($profileids, $cntProfiles, $offset, $prevProfilechecksum, $contact, $self, $self_profileid, $flag, $type, $archive, $date_search, $start_date, $end_date, $other_params, $page = "")
{
    if ($profileids) {
        $profileOffsetDetail = getProfileOffsetDetail($profileids, $offset, $cntProfiles);
        if ($profileids == 1)
            $showCurrentProfile = 1;
        if (!$profileOffsetDetail['currentProfilechecksum']) {
            $ALLOW_PROFILES          = array();
            $ARC_SAX                 = array();
            $item["contact"]         = $contact;
            $item["self"]            = $self;
            $item["self_profileid"]  = $self_profileid;
            $item["flag"]            = $flag;
            $item["type"]            = $type;
            $item["ARC_SAX"]         = $ARC_SAX;
            $item["ALLOW_PROFILES"]  = $ALLOW_PROFILES;
            $item["NUDGES"]          = $NUDGES;
            $item["date_search"]     = $date_search;
            $item["start_date"]      = $start_date;
            $item["end_date"]        = $end_date;
            $item["new_count"]       = $new_count;
            $item["eoi_viewed_date"] = $eoi_viewed_date;
            $itemStats               = getItemDetail($page, $type);
            $item                    = array_merge($item, $itemStats);
            $db_master               = connect_db();
            $pageDetail              = getting_profiles_based_on_type($item);
            $pageDetail              = sort_profiles($pageDetail);
            $pageDetail              = getResultArray($pageDetail);
            $data_3d                 = $pageDetail["data_3d"];
            list($temp, $currentProfileid) = explode("i", $prevProfilechecksum);
            $currentKey  = getCurrentKey($currentProfileid, $data_3d);
            $profileids  = getProfileString($data_3d, $currentKey);
            $cntProfiles = count($data_3d);
            if ($showCurrentProfile == 1) {
                global $_GET;
                $profileOffsetDetail = getProfileOffsetDetail($profileids, ($currentKey), $cntProfiles);
                $j                   = ceil(($currentKey + 1) / 10);
                $other_params        = str_replace("&j=&", "&j=$j&", $other_params);
                $other_params        = str_replace("j__1", "j__$j", $other_params);
                if ($j)
                    $_GET['NAVIGATOR'] = str_replace("j__1", "j__$j", $_GET['NAVIGATOR']);
            } else
                $profileOffsetDetail = getProfileOffsetDetail($profileids, ($currentKey + 1), $cntProfiles);
            //$currentProfilechecksum = getNextProfile($profileids, $cntProfiles, ($currentKey+1), $profilechecksum, $contact, $self, $self_profileid, $flag, $type, $archive, $date_search, $start_date, $end_date, $other_params, $page);
        }
        $prev_offset            = $profileOffsetDetail['prev_offset'];
        $next_offset            = $profileOffsetDetail['next_offset'];
        $offset                 = $profileOffsetDetail['offset'];
        $currentProfilechecksum = $profileOffsetDetail['currentProfilechecksum'];
        $SHOW_PREV              = $profileOffsetDetail['SHOW_PREV'];
        $SHOW_NEXT              = $profileOffsetDetail['SHOW_NEXT'];
        //$link['prevLink'] = 'profilechecksum='.$currentProfilechecksum.'&total_rec='.$cntProfiles.'&actual_offset='.$prev_offset.'&contact='.$contact.'&self='.$self.'&self_profileid='.$self_profileid.'&flag='.$flag.'&type='.$type.'&archive='.$archive.'&flag='.$flag.'&date_search='.$date_search.'&start_date='.$start_date.'&end_date='.$end_date.'&profileids='.$profileids.'&page='.$page.'&fromPage=contacts&'.$other_params;
        $link['prevLink']       = 'profilechecksum=' . $currentProfilechecksum . '&total_rec=' . $cntProfiles . '&actual_offset=' . $prev_offset . '&contact=' . $contact . '&self=' . $self . '&self_profileid=' . $self_profileid . '&flag=' . $flag . '&type=' . $type . '&flag=' . $flag . '&date_search=' . $date_search . '&start_date=' . $start_date . '&end_date=' . $end_date . '&profileids=' . $profileids . '&page=' . $page . '&fromPage=contacts&' . $other_params;
        $link['nextLink']       = 'profilechecksum=' . $currentProfilechecksum . '&total_rec=' . $cntProfiles . '&actual_offset=' . $next_offset . '&contact=' . $contact . '&self=' . $self . '&self_profileid=' . $self_profileid . '&flag=' . $flag . '&type=' . $type . '&flag=' . $flag . '&date_search=' . $date_search . '&start_date=' . $start_date . '&end_date=' . $end_date . '&profileids=' . $profileids . '&page=' . $page . '&fromPage=contacts&' . $other_params;
        $link['curLink']        = 'profilechecksum=' . $currentProfilechecksum . '&total_rec=' . $cntProfiles . '&actual_offset=' . $offset . '&contact=' . $contact . '&self=' . $self . '&self_profileid=' . $self_profileid . '&flag=' . $flag . '&type=' . $type . '&flag=' . $flag . '&date_search=' . $date_search . '&start_date=' . $start_date . '&end_date=' . $end_date . '&profileids=' . $profileids . '&page=' . $page . '&fromPage=contacts&' . $other_params;
        global $smarty;
        $smarty->assign("SHOW_PREV", $SHOW_PREV);
        $smarty->assign("SHOW_NEXT", $SHOW_NEXT);
        $smarty->assign("SHOW_NEXT_PREV", 1);
        $smarty->assign("prevLink", $link['prevLink']);
        $smarty->assign("nextLink", $link['nextLink']);
        $smarty->assign("curLink", $link['curLink']);
        return $currentProfilechecksum;
    }
}

function getProfileOffsetDetail($profileids, $offset, $cntProfiles)
{
    if ($cntProfiles == 1) {
        $SHOW_PREV = false;
        $SHOW_NEXT = false;
    } elseif (($cntProfiles - 1) == $offset) {
        $SHOW_PREV = true;
        $SHOW_NEXT = false;
    } elseif ($offset == 0) {
        $SHOW_PREV = false;
        $SHOW_NEXT = true;
    } else {
        $SHOW_PREV = true;
        $SHOW_NEXT = true;
    }
    $profileOffsetDetail['offset'] = $offset;
    $profileIdsOffsetArr           = explode("|X|", $profileids);
    foreach ($profileIdsOffsetArr as $key => $val) {
        $profileDetail                 = explode("|*|", $val);
        $profileArr[$key]['offset']    = $profileDetail[0];
        $profileArr[$key]['profileid'] = $profileDetail[1];
        if (($profileArr[$key]['offset'] + 1) == $offset)
            $prev_offset = $profileArr[$key]['offset'];
        if (($profileArr[$key]['offset'] - 1) == $offset)
            $next_offset = $profileArr[$key]['offset'];
        if ($offset == $profileArr[$key]['offset'])
            $currentProfilechecksum = $profileArr[$key]['profileid'];
    }
    $profileOffsetDetail['prev_offset']            = $prev_offset;
    $profileOffsetDetail['next_offset']            = $next_offset;
    $profileOffsetDetail['currentProfilechecksum'] = $currentProfilechecksum;
    $profileOffsetDetail['SHOW_PREV']              = $SHOW_PREV;
    $profileOffsetDetail['SHOW_NEXT']              = $SHOW_NEXT;
    return $profileOffsetDetail;
}

function get_links_mobile($page, $viewed_action, $viewed_sub_array, $subscription, $viewed_profileid, $checksum, $viewed_profile_checksum, $CONTACT_STATUS, $index, $viewed_username, $show_contacts, $pageDetail, $viewed_subscription, $tempContacted, $contact_locked, $callDataArray)
{
    /*if($viewed_subscription || $subscription)
    $acceptCaption = "Accept to View Contact Details";
    else*/
    $acceptCaption = "Accept";
    global $data, $cc_navigator, $upto;
    $evalue           = isEvalueMember($viewed_subscription);
    $removeMemberLink = "";
    $addMemberLinkFr  = "";
    $callNow          = false;
    if (!$contact_locked)
        $callNow = true;
    $viewContactLink   = "";
    $viewContactLinkFr = "";
    $viewContactParam  = "view_contact";
    if (!$pageDetail['stype_mobile'])
		if($pageDetail['stype'])
			$pageDetail['stype_mobile'] = $pageDetail['stype'];
		else
        $pageDetail['stype_mobile'] = "WO";
    if ($page == "messages")
        $viewContactParam = "view_contact_message";
    /*********Direct call changes******/
    if (($evalue) || ($pageDetail["CALL_DIRECT"])) {
        $viewContactLink   = "<a href='/contacts/PreContactDetails?checksum=$checksum&profilechecksum=$viewed_profile_checksum&STYPE=" . $pageDetail['stype_mobile'] . "&index=$index&to_do=$viewContactParam&$cc_navigator&nav_type=" . $pageDetail['navigation_type'] . "' class=\"btn active-btn \">Contact Details</a>";
        $viewContactLinkFr = "<a href='/contacts/PreContactDetails?checksum=$checksum&profilechecksum=$viewed_profile_checksum&STYPE=" . $pageDetail['stype_mobile'] . "&index=$index&to_do=$viewContactParam&$cc_navigator&nav_type=" . $pageDetail['navigation_type'] . "' class=\"btn active-btn \" >Contact Details</a>";
        $viewContactLinkEoi = "<a href='/contacts/PreContactDetails?checksum=$checksum&profilechecksum=$viewed_profile_checksum&STYPE=" . $pageDetail['stype_mobile'] . "&index=$index&to_do=$viewContactParam&$cc_navigator&nav_type=" . $pageDetail['navigation_type'] . "' class=\"btn active-btn third\" >Contact Details</a>";
    }
    /*********Direct call changes******/
    if ($page == "viewed_contacts" || $page == "viewed_contacts_by" || $page == "contact_viewers")
        $viewContactLink = "";
    $acceptCaption = "Accept";
    global $data, $cc_navigator, $upto;
    if ($page == "accept") {
        $links[0] .= "<a href='/contacts/PreWrite?checksum=$checksum&profilechecksum=$viewed_profile_checksum&STYPE=" . $pageDetail['stype'] . "&index=$index&to_do=message&$cc_navigator&nav_type=" . $pageDetail['navigation_type'] . "' class=\"btn active-btn \" >Send Message</a>";
        $links[0] .= $viewContactLink;
    } elseif ($page == "favorite" || $page == "photo" || $page == "horoscope" || $page == "chat" || $page == "matches" || $page == "visitors" || $page == 'callnow' || $page == "viewed_contacts" || $page == "intro_call" || $page == "viewed_contacts_by" || $page == "contact_viewers") {
        $callNow = true;
        if (is_array($CONTACT_STATUS)) {
            //Links in case of acceptance
            if ($CONTACT_STATUS["TYPE"] == "A" || $CONTACT_STATUS["TYPE"] == "ACC") {
                //				$links[0].="<span id=\"SPAN_$index\" class=\"fr\" style='position:relative'><i class=\"sprt_cn_ctr fl wrt_msg mr_5\"></i> <a  class=\"blink fl\" onclick=\"javascript:{check_window('hide_exp_layer()');check_checkbox('PROFILE_$index',$index,'message','$show_contacts');}\">Send Message</a>";
                $links[0] .= "<a href='/contacts/PreWrite?checksum=$checksum&profilechecksum=$viewed_profile_checksum&STYPE=" . $pageDetail['stype'] . "&index=$index&to_do=message&$cc_navigator&nav_type=" . $pageDetail['navigation_type'] . "' class=\"btn active-btn \"  >Send Message</a>";
                $links[0] .= $viewContactLink;
            }
            //Links in case of EOI
            elseif ($CONTACT_STATUS["TYPE"] == "I" || $CONTACT_STATUS["TYPE"] == "NACC") {
                //EOI received
                if ($CONTACT_STATUS["TYPE"] == "I" && $CONTACT_STATUS["ACTION"] == "SENDER") {
                    $callNow = true;
                    //					$links[0].="<span id=\"SPAN_$index\" ><input type=\"button\" style=\"width:104px; margin:0 5px;\" class=\"green_btn b fr\" value=\"Not Interested\" onclick=\"javascript:{check_window('hide_exp_layer()');check_checkbox('PROFILE_$index',$index,'decline','$show_contacts');}\"><input type=\"button\" class=\"green_btn b fr\" value=\"".$acceptCaption."\" style=\"width:".$acceptWidth."; margin:0 5px;\" onclick=\"javascript:{check_window('hide_exp_layer()');check_checkbox('PROFILE_$index',$index,'accept','$show_contacts');}\">";
                    $links[0] .= "<a href='/contacts/PostAccept?checksum=$checksum&profilechecksum=$viewed_profile_checksum&STYPE=" . $pageDetail['stype_mobile'] . "&index=$index&to_do=decline&$cc_navigator&nav_type=" . $pageDetail['navigation_type'] . "' class=\"btn active-btn first\"  >$acceptCaption</a><a href='/contacts/PostNotinterest?checksum=$checksum&profilechecksum=$viewed_profile_checksum&STYPE=" . $pageDetail['stype_mobile'] . "&index=$index&to_do=decline&$cc_navigator&nav_type=" . $pageDetail['navigation_type'] . "' class=\"btn active-btn second\"  >Not Interested</a>";
                    $links[0] .= $viewContactLinkEoi;
                }
                //EOI sent or nudge accepted
                elseif (($CONTACT_STATUS["TYPE"] == "I" && $CONTACT_STATUS["ACTION"] == "RECEIVER") || $CONTACT_STATUS["TYPE"] == "NACC") {
                    $callNow = true;
                    $links[0] .= "<a href='/contacts/PostSendReminder?checksum=$checksum&profilechecksum=$viewed_profile_checksum&STYPE=" . $pageDetail['stype_mobile'] . "&index=$index&to_do=reminder&$cc_navigator&nav_type=" . $pageDetail['navigation_type'] . "' class=\"btn active-btn \"  >Send Reminder</a>";
                    //	$links[0]="<span id=\"SPAN_$index\" style='position:relative' class=\"fr\"><i class=\"sprt_cn_ctr fl snd_rmndr mr_5\"></i> <a  class=\"blink fl\" onclick=\"javascript:{check_window('hide_exp_layer()');check_checkbox('PROFILE_$index',$index,'reminder','$show_contacts');}\">Send Reminder</a>";
                    $links[0] .= $viewContactLink;
                }
            }
            //Nudged
                elseif ($CONTACT_STATUS["TYPE"] == "N" || $CONTACT_STATUS["TYPE"] == "NNOW") {
                $callNow = true;
                if ($tempContacted)
                    $links[0] = "<span id=\"SPAN_$index\" style='position:relative'><a class=\"btn greynew-btn\">Interest Expressed</a>";
                else
                    $links[0] .= "<a href='/contacts/PostEOI?checksum=$checksum&profilechecksum=$viewed_profile_checksum&STYPE=" . $pageDetail['stype_mobile'] . "&index=$index&to_do=eoi&$cc_navigator&nav_type=" . $pageDetail['navigation_type'] . "' class=\"btn active-btn \"  >Express Interest</a>";
                //		$links[0].="<span id=\"SPAN_$index\" style='position:relative'><i class=\"sprt_cn_ctr fl smly_icon mr_5\"></i> <a  class=\"blink fl\" onclick=\"javascript:{check_window('hide_exp_layer()');check_checkbox('PROFILE_$index',$index,'eoi','$show_contacts');}\">Express Interest | <a  class=\"blink fl\" onclick=\"javascript:{check_window('hide_exp_layer()');check_checkbox('PROFILE_$index',$index,'decline','$show_contacts');}\">Not Interested</a>";
                $links[0] .= $viewContactLink;
            }
            //Decline or offline profile rejects profile
                elseif ($CONTACT_STATUS["TYPE"] == "D" || $CONTACT_STATUS["TYPE"] == "REJ") {
                $callNow = true;
                //I decline
                if ($CONTACT_STATUS["ACTION"] == "SENDER") {
                    $links[0] = "<a href='/contacts/PostAccept?checksum=$checksum&profilechecksum=$viewed_profile_checksum&STYPE=" . $pageDetail['stype_mobile'] . "&index=$index&to_do=accept&$cc_navigator&nav_type=" . $pageDetail['navigation_type'] . "&responseTracking=".JSTrackingPageType::MOBILE_AWAITING."' class=\"btn active-btn \" >Accept</a>";
                    //	$links[0]="<span id=\"SPAN_$index\" class=\"fr\"><input type=\"button\" class=\"green_btn fl\" value=\"Accept this member\" style=\"width:150px; margin:0 5px;\" onclick=\"javascript:{check_window('hide_exp_layer()');check_checkbox('PROFILE_$index',$index,'accept','$show_contacts');}\">";
                    $links[0] .= $viewContactLink;
                }
                //User declines or offline profile declines
            }
        }
        //No contact at all
        else {
            $callNow = true;
            if ($tempContacted)
                $links[0] = "<a class=\"btn greynew-btn\">Interest Expressed</a>";
            else
                $links[0] .= "<a href='/contacts/PostEOI?checksum=$checksum&profilechecksum=$viewed_profile_checksum&STYPE=" . $pageDetail['stype_mobile'] . "&index=$index&to_do=eoi&$cc_navigator&nav_type=" . $pageDetail['navigation_type'] . "' class=\"btn active-btn \"  >Express Interest</a>";
            //	$links[0].="<span id=\"SPAN_$index\" class=\"fr\"><i class=\"sprt_cn_ctr fl smly_icon mr_5\"></i><a  class=\"blink fl\" onclick=\"javascript:{check_window('hide_exp_layer()');check_checkbox('PROFILE_$index',$index,'eoi','$show_contacts');}\">Express Interest</a>";
            $links[0] .= $viewContactLink;
        }
        
        // IVR-Callnow
        if ($page == 'callnow') {
            $missedCallType   = $callDataArray['M']['CALL_STATUS'];
            $receivedCallType = $callDataArray['R']['CALL_STATUS'];
            $calledCallType   = $callDataArray['I']['CALL_STATUS'];
            
            $type = $pageDetail["type"];
            if (($type == "R" || $type == 'M' || $type == 'I') || $missedCallType == 'M' || $receivedCallType == 'R' || $calledCallType == 'I') {
                $callNow = true;
            }
        }
        // End IVR-Callnow
        
    } elseif ($page == "decline") {
        $NUDGES = $pageDetail["NUDGES"];
        $type   = $pageDetail["type"];
        if ($viewed_action == "RECEIVER")
            $links[0] = $viewSimilarLink;
        elseif ($viewed_action == "SENDER") {
            $callNow = true;
            if (is_array($NUDGES) && in_array($viewed_profileid, $NUDGES))
                $links[0] .= "<a href='/contacts/PostEOI?checksum=$checksum&profilechecksum=$viewed_profile_checksum&STYPE=" . $pageDetail['stype_mobile'] . "&index=$index&to_do=eoi&$cc_navigator&nav_type=" . $pageDetail['navigation_type'] . "' class=\"btn active-btn \"  >Express Interest</a>";
            //$links[0]="<span id=\"SPAN_$index\" ><a  class=\"blink b fr\" onclick=\"javascript:{check_window('hide_exp_layer()');check_checkbox('PROFILE_$index',$index,'eoi','$show_contacts');}\">Express Interest</a><i class=\"sprt_cn_ctr fr smly_icon mr_5\"></i>";
            else
                $links[0] = "<a href='/contacts/PostAccept?checksum=$checksum&profilechecksum=$viewed_profile_checksum&STYPE=" . $pageDetail['stype_mobile'] . "&index=$index&to_do=accept&$cc_navigator&nav_type=" . $pageDetail['navigation_type'] . "' class=\"btn active-btn \" style=\" width:90px;\" >Accept</a>";
            //            $links[0]="<span id=\"SPAN_$index\" ><input type=\"button\" class=\"green_btn fr b\" value=\"Accept this member\" style=\"width:150px; margin:0 5px;\" onclick=\"javascript:{check_window('hide_exp_layer()');check_checkbox('PROFILE_$index',$index,'accept','$show_contacts');}\">";
        }
    } elseif ($page == "eoi" || $page == "filtered_eoi") //|| $page=="archive_eoi")
        {
        $links[0] = "";
        $NUDGES   = $pageDetail["NUDGES"];
        $type     = $pageDetail["type"];
        $callNow  = true;
        if ($type == "R") {
            //	$links[0].="<span id=\"SPAN_$index\" class=\"fr\"><input type=\"button\" style=\"width:104px; margin:0 5px;\" class=\"green_btn fr b\" value=\"Not Interested\" onclick=\"javascript:{check_window('hide_exp_layer()');check_checkbox('PROFILE_$index',$index,'decline','$show_contacts');}\">";
            if (is_array($NUDGES) && in_array($viewed_profileid, $NUDGES)) {
                $links[0] .= "<a href='/contacts/PostEOI?checksum=$checksum&profilechecksum=$viewed_profile_checksum&STYPE=" . $pageDetail['stype_mobile'] . "&index=$index&to_do=eoi&$cc_navigator&nav_type=" . $pageDetail['navigation_type']. "&responseTracking=".JSTrackingPageType::MOBILE_FILTER."' class=\"btn active-btn \"  >Express Interest</a>";
                //				$links[0].="<input type=\"button\" class=\"green_btn fr b\" value=\"Express Interest\" style=\"width:120px; margin:0 5px;\" onclick=\"javascript:{check_window('hide_exp_layer()');check_checkbox('PROFILE_$index',$index,'eoi','$show_contacts');}\">";
            } else {
                $links[0] .= "<a href='/contacts/PostAccept?checksum=$checksum&profilechecksum=$viewed_profile_checksum&STYPE=" . $pageDetail['stype_mobile'] . "&index=$index&to_do=accept&$cc_navigator&nav_type=" . $pageDetail['navigation_type'] . "&responseTracking=".JSTrackingPageType::MOBILE_AWAITING."' class=\"btn active-btn first\"  >$acceptCaption</a>";
                //	$links[0].="<input type=\"button\" class=\"green_btn fr b\" value=\"".$acceptCaption."\" style=\"width:".$acceptWidth."; margin:0 5px;\" onclick=\"javascript:{check_window('hide_exp_layer()');check_checkbox('PROFILE_$index',$index,'accept','$show_contacts');}\">";
            }
            $links[0] .= "<a href='/contacts/PostNotinterest?checksum=$checksum&profilechecksum=$viewed_profile_checksum&STYPE=" . $pageDetail['stype_mobile'] . "&index=$index&to_do=decline&$cc_navigator&nav_type=" . $pageDetail['navigation_type'] . "&responseTracking=".JSTrackingPageType::MOBILE_AWAITING."' class=\"btn active-btn second\"  >Not Interested</a>";
            $links[0] .= "<b>" . $viewContactLinkEoi . "</b>";
        } elseif ($type == "M") {
            $links[0] .= "<a href='/contacts/PostSendReminder?checksum=$checksum&profilechecksum=$viewed_profile_checksum&STYPE=" . $pageDetail['stype_mobile'] . "&index=$index&to_do=reminder&$cc_navigator&nav_type=" . $pageDetail['navigation_type'] . "' class=\"btn active-btn \"  >Send Reminder</a>";
            //	$links[0]="<span id=\"SPAN_$index\" style='position:relative' class=\"fr\"><i class=\"sprt_cn_ctr fl snd_rmndr mr_5\"></i><a  class=\"blink fl\" onclick=\"javascript:{check_window('hide_exp_layer()');check_checkbox('PROFILE_$index',$index,'reminder','$show_contacts');}\">Send Reminder</a>";
            $links[0] .= $viewContactLinkEoi;
            
            
        }
    } elseif ($page == "messages") {
        $links[0] = "<a href='/contacts/PreWrite?checksum=$checksum&profilechecksum=$viewed_profile_checksum&STYPE=" . $pageDetail['stype_mobile'] . "&index=$index&to_do=message&$cc_navigator&nav_type=" . $pageDetail['navigation_type'] . "' class=\"btn active-btn \"  >Send Message</a>";
        //		$links[0]="<span id=\"SPAN_$index\" style='position:relative'><i class=\"sprt_cn_ctr fl wrt_msg mr_5\"></i><a  class=\"blink fl\" onclick=\"javascript:{check_window('hide_exp_layer()');check_checkbox('PROFILE_$index',$index,'message','$show_contacts');}\">Send Message</a>";
        $links[0] .= $viewContactLink;
    }
    $linksLabel = array(
        "links" => $links,
        "callNow" => $callNow
    );
    return $linksLabel;
}
function getPhotoImage_mobile($havephoto, $gender)
{
    if ($havephoto == 'L') {
        if ($gender == 'M')
            $image_file = "ic_login_to_view_b_100.gif";
        else
            $image_file = "ic_login_to_view_g_100.gif";
    } elseif ($havephoto == 'C') {
        if ($gender == 'M')
            $image_file = "ic_photo_vis_if_b_100.gif";
        else
            $image_file = "ic_photo_vis_if_g_100.gif";
    } elseif ($havephoto == 'F') {
        if ($gender == 'M')
            $image_file = "mobilejs/ic_filtered_b_60x60.gif";
        else
            $image_file = "mobilejs/ic_filtered_g_60x60.gif";
    } elseif ($havephoto == 'H') {
        if ($gender == 'M')
            $image_file = "ic_hidden_b_100.gif";
        else
            $image_file = "ic_hidden_g_100.gif";
    } elseif ($havephoto == 'P') {
        if ($gender == 'M')
            $image_file = "mobilejs/photo_fil_sm_b_60x60.gif";
        else
            $image_file = "mobilejs/photo_fil_sm_g_60x60.gif";
    } elseif ($havephoto == 'U') {
        if ($gender == 'M')
            $image_file = "ic_photo_coming_b_100.gif";
        else
            $image_file = "ic_photo_coming_g_100.gif";
    } elseif ($havephoto == 'N') {
        if ($gender == 'M')
            $image_file = "ic_photo_notavailable_b_100.gif";
        else
            $image_file = "ic_photo_notavailable_g_100.gif";
    }
    return $image_file;
}

function getTopPanelButton($page,$filter,$profileid)
{
	global $smarty;
	$profileMemcacheObj=new ProfileMemcacheService($profileid);
	$count = $profileMemcacheObj->get("MESSAGE_NEW");
	if($count)
	{
		$countStr = " (".$count.")";
	}
    if($page == "photo")
	{
		$button = 'Photo';
		//$button = '<div><a href="javascript:void(0)" onclick="" class="pull-left btn pre-next-btn" style="width:auto">Upload Photo</a></div>';
		$photoUploadSupported = BrowserCheck::checkPhotoUploadSupport();
		$smarty->assign("photoUploadSupported", $photoUploadSupported);
	}
	if($page == "messages")
	{
		if($filter == "R")
		{
			$button = '<a href="/profile/contacts_made_received.php?&page=messages&filter=M" class="pull-right btn pre-next-btn" style="width:auto">Sent</a>';
			$button .= '<a href="/profile/contacts_made_received.php?&page=messages&filter=R" class="pull-right btn pre-next-btn mr10 active message-btn" style="width:auto">Received'.$countStr.'</a>';
		}
		if($filter == "M")
		{
			$button = '<a href="/profile/contacts_made_received.php?&page=messages&filter=M" class="pull-right btn pre-next-btn active message-btn" style="width:auto">Sent</a>';
			$button .= '<a href="/profile/contacts_made_received.php?&page=messages&filter=R" class="pull-right btn pre-next-btn mr10" style="width:auto">Received'.$countStr.'</a>';
		}
	}
	$smarty->assign("topMenuButton", $button);
	//echo $button;die;
}
?>
