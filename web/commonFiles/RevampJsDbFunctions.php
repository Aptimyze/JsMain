<?php

include_once(JsConstants::$docRoot . "/commonFiles/dropdowns.php");
include_once(JsConstants::$docRoot . "/classes/Mysql.class.php");
include_once(JsConstants::$docRoot . "/commonFiles/mysql_multiple_connections.php");
include_once(JsConstants::$docRoot . "/commonFiles/SymfonyPictureFunctions.class.php");
//$output_param = 1 means return array 
//$output_param = 0 means return string
function get_all_caste_revamp_js_db($caste, $db = "", $output_param, $from_matchalert = "") {
  if (is_array($caste))
    $caste_values = implode("','", $caste);
  else
    $caste_values = $caste;

  $caste_values = trim($caste_values, "'");
  $caste_values = trim($caste_values, "\"");

  $Caste_arr = array();
  $allCasteArray = AllCasteMap::getAllCaste($caste_values);
  
  foreach ($allCasteArray as $value=>$arrCasteRow) {
    if ($arrCasteRow["ISALL"] == "Y") { 
      $tempArr = explode(',',AllCasteMap::$arrAllCaste_GroupByParent[$value]);
      $Caste_arr = array_merge($Caste_arr,$tempArr);
      unset($tempArr);
    } else if ($arrCasteRow["ISGROUP"] == "Y") {
      $tempArr = explode(',',AllCasteMap::$arrAllCaste_GroupMapping[$value]);
      $Caste_arr = array_merge($Caste_arr,$tempArr);
      unset($tempArr);
    } else {
      $Caste_arr[] = strval($value);
    }
  }

  if (is_array($Caste_arr))
    $output = array_unique($Caste_arr);
  else
    $output = "";

  if ($output_param) {
    return $output;
  }
  else {
    if (is_array($output))
      return implode("','", $output);
    else
      return "";
  }
}

//This function is used to check if a particular caste belongs to any group
function is_part_of_a_group($caste) {
  global $CASTE_GROUP_ARRAY;
  if (is_array($CASTE_GROUP_ARRAY))
    foreach ($CASTE_GROUP_ARRAY as $k => $v) {
      $casteArr = explode(",", $v);
      foreach ($casteArr as $kk => $vv) {
        if ($vv == $caste || $k == $caste)
          return 1;
      }
    }
  return 0;
}

//This function is used to find all possible parents (Group/Religion) of a particular caste
function getcasteparent_revamp_js_db($caste) {
  $sql_query = "SELECT ISALL, ISGROUP, PARENT FROM newjs.CASTE WHERE VALUE=" . $caste;
  $res_query = mysql_query_decide($sql_query) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes", $sql_query, "ShowErrTemplate");
  $row_query = mysql_fetch_array($res_query);
  if ($row_query["ISALL"] == 'Y') {
    //print "I am a Religion"; 
    $castes[] = $caste;
  }
  else if ($row_query["ISGROUP"] == 'Y') {
    //print "I am a Group";
    $sql_query1 = "SELECT VALUE FROM newjs.CASTE WHERE ISALL='Y' AND PARENT=" . $row_query["PARENT"];
    $res_query1 = mysql_query_decide($sql_query1) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes", $sql_query2, "ShowErrTemplate");
    if ($row_query1 = mysql_fetch_array($res_query1)) {
      $castes[] = $caste;
      $castes[] = $row_query1['VALUE'];
    }
  }
  else {
    //print "I am a Sub Group";
    $sql_query2 = "SELECT VALUE FROM newjs.CASTE WHERE (ISALL='Y' AND PARENT=" . $row_query["PARENT"] . ") UNION SELECT GROUP_VALUE FROM newjs.CASTE_GROUP_MAPPING WHERE CASTE_VALUE= " . $caste;
    $res_query2 = mysql_query_decide($sql_query2) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes", $sql_query2, "ShowErrTemplate");
    $castes[] = $caste;
    while ($row_query2 = mysql_fetch_array($res_query2)) {
      $castes[] = $row_query2["VALUE"];
    }
  }
  $caste_group_str = @implode(",", $castes);
  return $castes;
}

//This function returns all members of a group to which the given caste belongs.
function show_group_members($caste) {
  global $CASTE_GROUP_ARRAY;
  foreach ($CASTE_GROUP_ARRAY as $k => $v) {
    $casteArr = explode(",", $v);
    foreach ($casteArr as $kk => $vv) {
      if ($vv == $caste || $k == $caste) {
        $groupArr[] = $v;
      }
    }
  }
  if (count($groupArr)) {
    $str = implode(",", $groupArr);
    $tempArr = explode(",", $str);
    sort($tempArr);
    $tempArr = array_unique($tempArr);
    return implode(",", $tempArr);
  }
  else
    return 0;
}

?>
