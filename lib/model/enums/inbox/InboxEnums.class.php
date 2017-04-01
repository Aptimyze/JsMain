<?php

/* This class contains the inbox related enums
 */

class InboxEnums {
    public static $messageLogInQuery = true;
        //mapping of infotype id to page and filter
        static public $INBOX_ACTION_MAPPING = array(1 => array("page" => "eoi", "filter" => "R"),
            2 => array("page" => "accept", "filter" => "R"),
            3 => array("page" => "accept", "filter" => "M"),
            4 => array("page" => "messages", "filter" => "A"),
            5 => array("page" => "visitors", "filter" => "R"),
            6 => array("page" => "eoi", "filter" => "M"),
            //7=>array("page"=>"","filter"=>""),
            8 => array("page" => "favorite", "filter" => "M"),
            9 => array("page" => "photo", "filter" => "R"),
            10 => array("page" => "decline", "filter" => "R"),
            11 => array("page" => "decline", "filter" => "M"),
            12 => array("page" => "filtered_eoi", "filter" => "R"),
            //13=>array("page"=>"","filter"=>""),
            14 => array("page" => "photo", "filter" => "M"),
            15 => array("page" => "horoscope", "filter" => "M"),
            16 => array("page" => "phonebook_contacts_viewed", "filter" => "M"),
            17 => array("page" => "contact_viewers", "filter" => "R"),
            18 => array("page" => "horoscope", "filter" => "R"),
            19 => array("page" => "intro_call", "filter" => "R"),
            20 => array("page" => "ignore", "filter" => "M"),
            21 => array("page" => "intro_call_complete", "filter" => "R"),
            23 => array("page" => "eeoi", "filter" => "R")

        );
        //fromPage to be passed as viewProfilePage param
        static public $fromPage = "contacts";
        //mapping of page and filter to ViewProfilePage Params
        static private $VIEW_PROFILE_PAGE_PARAMS_MAPPING = array(
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
                    "stype_mobile" => "WS",
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
                    "stype_mobile" => "20",
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
                    "stype_mobile" => "21",
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
                    "stype_mobile" => "22",
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
                    "stype_mobile" => "23",
                    "navigation_type" => "HOR_M",
                    "table_name" => "newjs.HOROSCOPE_REQUEST",
                    "get_contact_field" => "RECEIVER"
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
            "visitors" => array(
                "R" => array(
                    "self" => "VIEWED",
                    "contact" => "VIEWER",
                    "type" => "R",
                    "flag" => "V",
                    "stype" => "5",
                    "stype_mobile" => "WV",
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
            "messages" => array(
                "A" => array(
                    "self" => "SENDER_RECEIVER",
                    "contact" => "SENDER_RECEIVER",
                    "type" => "RM",
                    "flag" => "MSG",
                    "navigation_type" => "MES_A",
                    "table_name" => "newjs.MESSAGE_LOG",
                    "time_field" => "DATE AS TIME",
                    "get_contact_field" => "SENDER_RECEIVER"
                ),
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
            "viewed_contacts" => array(
                "R" => array(
                    "self" => "VIEWER",
                    "contact" => "VIEWED",
                    "type" => "VC",
                    "flag" => "VC",
                    "stype" => "26",
                    "stype_mobile" => "M26",
                    "navigation_type" => "VC",
                    "show_all_results" => "1",
                    "contactFlag" => "'VC'"
                )
            ),
            "viewed_contacts_by" => array(
                "R" => array(
                    "self" => "VIEWED",
                    "contact" => "VIEWER",
                    "type" => "VCB",
                    "flag" => "VCB",
                    "stype" => "27",
                    "stype_mobile" => "M27",
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
                    "stype_mobile" => "M27",
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
                    "stype_mobile" => "",
                    "navigation_type" => "CVSM",
                    "show_all_results" => "1",
                    "contactFlag" => "'CVS'"
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
            )
        );
        /**
         * Custom headings for inbox listings
         * @var type 
         */
        static private $INBOX_SUBHEADING_MAPPING = array();
        static private function initSubHeading(){
                self::$INBOX_SUBHEADING_MAPPING["PC"]["default"]= "";
                self::$INBOX_SUBHEADING_MAPPING["PC"][1]  = "<span class='opa70'>Receiving too many irrelevant interests? You can </span><a href='/profile/dpp' class='color5'>Set Filters</a>";
                self::$INBOX_SUBHEADING_MAPPING["PC"][12] = "<span class='opa70'>These interests do not match the filters you have set.</span> <a href='/profile/dpp' class='color5'>Edit/Review Filters</a>";
                self::$INBOX_SUBHEADING_MAPPING["PC"][17] = "<span><subscription_label> Benefit: </span><span class='opa70'><cnt> free member(s) out of <cntTotal> shown below could view your contacts</span>";
                self::$INBOX_SUBHEADING_MAPPING["PC"][6] = "<span class='opa70'>To get faster response, you may 'Send Reminder' to these members</span>";
        }
        /**
         * This function returns the custom headings for inbox listings
         * @param string $params
         * @return type
         */
        static public function getInboxSubHeading($params){
                self::initSubHeading();
                
                if($params['searchid'] == '')
                        $params['searchid'] = 'default';
                $str = str_replace('<cntTotal>',$params["headingTotalCount"],self::$INBOX_SUBHEADING_MAPPING['PC'][$params['searchid']]);
                $str = str_replace('<subscription_label>',$params["loggedin_subscription"],$str);
                return str_replace('<cnt>',$params["headingCount"],$str);
        }
        /* get page and filter for corresponding infoType ID
         * @param : infoTypeID
         * @return : result array 
         */

        static public function getInboxParams($infoTypeID) {
                if ($infoTypeID)
                        return self::$INBOX_ACTION_MAPPING[$infoTypeID];
                else
                        return -1;
        }

        /* get view profile page params corresponding to page and filter
         * @param : infotypeid
         * @return : result array 
         */

        static public function getViewProfilePageParams($infoTypeID) {
                $inboxParamsArr = self::getInboxParams($infoTypeID);
                if (is_array($inboxParamsArr)) {
                        $page = $inboxParamsArr["page"];
                        $filter = $inboxParamsArr["filter"];
                        $viewProfilePageParamsArr = self::$VIEW_PROFILE_PAGE_PARAMS_MAPPING[$page][$filter];
                } else
                        $viewProfilePageParamsArr = array();
                return $viewProfilePageParamsArr;
        }

        /* get infotyppe id corresponding to page and filter
         * @param : inboxParamsArr
         * @return : infotypeid 
         */

        static public function getInfoTypeIdByInboxParams($inboxParamsArr) {
                if (is_array($inboxParamsArr)) {
                        $infotypeid = array_search($inboxParamsArr, self::$INBOX_ACTION_MAPPING);
                        return $infotypeid;
                }
        }

}

?>
