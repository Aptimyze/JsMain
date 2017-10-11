<?php
// Created by Neha
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');
include_once('modules/Leads/Lead.php');
class LeadsInListView extends Lead {

    function Lead() {
        parent::Lead();
    }
    
    function create_new_list_query($order_by, $where,$filter=array(),$params=array(), $show_deleted = 0,$join_type='', $return_array = false,$parentbean, $singleSelect = false){
	global $timedate;
        $ret_array = parent::create_new_list_query($order_by, $where,$filter,$params, $show_deleted,$join_type, $return_array,$parentbean, $singleSelect);
	 if (isset($_REQUEST['startdate_basic'])) $date_begin = $_REQUEST['startdate_basic'];
   	 if (isset($_REQUEST['enddate_basic'])) $date_end = $_REQUEST['enddate_basic']; 
	if(isset($date_begin) && $date_begin != ""){ 
        $date_begin_dbformat = $timedate->swap_formats($date_begin, $timedate->get_date_format(), $timedate->dbDayFormat);
        $ret_array['where'].=" and date_entered >= '".PearDatabase::quote($date_begin_dbformat)."'";
    }
    if(isset($date_end) && $date_end != ""){ 
        $date_end_dbformat = $timedate->swap_formats($date_end, $timedate->get_date_format(), $timedate->dbDayFormat);
         $ret_array['where'].=" and date_entered <= '".PearDatabase::quote($date_end_dbformat)."'";
    } 
//        $ret_array['where'] = str_replace("opportunities.agency_name", "jtl1.name", $ret_array['where']);
  //      $ret_array['where'] = str_replace("opportunities.advertiser_name", "jtl2.name", $ret_array['where']);

        return $ret_array;
    }
}
?> 
