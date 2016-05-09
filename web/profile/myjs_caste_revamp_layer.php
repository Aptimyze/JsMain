<?php
	include("connect.inc");
include_once(JsConstants::$docRoot."/commonFiles/dropdowns.php");
	include_once("old_dropdown.php");
        $db=connect_db();
        $data=authenticated();
        if(!$data)
        {
		include_once("include_file_for_login_layer.php");
                $smarty->display("login_layer.htm");
                die;
        }
	
	$profileid = $data["PROFILEID"];
	$display_layer_multiple = 0;
      	if ($flag == 1)
	{
		$old_caste_label = explode(":",$OLD_CASTE_ARR[$oldVal]);
		$smarty->assign("OLD_CASTE_VAL",trim($old_caste_label[1]));
		$new_caste_label = explode(":",$CASTE_DROP[$newVal]);
		$smarty->assign("CURRENT_CASTE_VAL",trim($new_caste_label[1]));
		$smarty->assign("MERGE1",trim($old_caste_label[1]));
		$merge2_label = explode(":",$OLD_CASTE_ARR[$MERGE_CASTE_ARR[$oldVal]]);
		$smarty->assign("MERGE2",trim($merge2_label[1]));
		$smarty->assign("OLD_CASTE_VAL_NUM",$oldVal);
        	$smarty->assign("CURRENT_CASTE_VAL_NUM",$newVal);
		$smarty->assign("FIELD_VALS","Castes");
		$smarty->assign("CASTE_DISPLAY",1);
	}
	else if ($flag == 2)
	{
		$revamp_val_arr = explode(",",$revamp_val);

		if(in_array("1",$revamp_val_arr))
		{
                	$old_caste_label = explode(":",$OLD_CASTE_ARR[$oldVal]);
                	$smarty->assign("OLD_CASTE_VAL",trim($old_caste_label[1]));
                	$new_caste_label = explode(":",$CASTE_DROP[$newVal]);
                	$smarty->assign("CURRENT_CASTE_VAL",trim($new_caste_label[1]));
                	$smarty->assign("MERGE1",trim($old_caste_label[1]));
                	$merge2_label = explode(":",$OLD_CASTE_ARR[$MERGE_CASTE_ARR[$oldVal]]);
                	$smarty->assign("MERGE2",trim($merge2_label[1]));
			$smarty->assign("OLD_CASTE_VAL_NUM",$oldVal);
        		$smarty->assign("CURRENT_CASTE_VAL_NUM",$newVal);
			$fieldArr[] = "Castes";
			$smarty->assign("CASTE_DISPLAY",1);
		}
		if(in_array("2",$revamp_val_arr))
		{
			$statement = "SELECT NEW_CASTE,NEW_CASTE_MTONGUE FROM newjs.MAPPING_CASTE_CASTE_MTONGUE WHERE OLD_CASTE = ".$newVal." ORDER BY NEW_CASTE_SORTBY";
                	$result = mysql_query($statement,$db) or logError("due to some temporary problem your request could not be processed. please try after some time.",$statement,"showerrtemplate");
                	while ($row = mysql_fetch_array($result))
                	{
                        	$dropdown_label = explode(":",$CASTE_DROP[$row["NEW_CASTE"]]);
                        	if ($row["NEW_CASTE_MTONGUE"])
                        	{
                                	if ($row["NEW_CASTE_MTONGUE"] == $mTong)
                                        	$revamp_layer_caste_dropdown[$row["NEW_CASTE"]] = $dropdown_label[1];
                        	}
                        	else
                        	{
                                	$revamp_layer_caste_dropdown[$row["NEW_CASTE"]] = $dropdown_label[1];
                        	}
                	}
                	$new_caste_label = explode(":",$CASTE_DROP[$newVal]);
                	$smarty->assign("OLD_CASTE_VAL",trim($new_caste_label[1]));
                	$smarty->assign("CURRENT_CASTE_VAL",trim($new_caste_label[1]));
                	$smarty->assign("CASTE_DROPDOWN",$revamp_layer_caste_dropdown);
			$smarty->assign("OLD_CASTE_VAL_NUM",$oldVal);
        		$smarty->assign("CURRENT_CASTE_VAL_NUM",$newVal);
			$fieldArr[] = "Castes";
			$smarty->assign("CASTE_DISPLAY",1);
			$display_layer_multiple = 1;
		}
		if(in_array("3",$revamp_val_arr))
		{
			$smarty->assign("OLD_OCCUPATION",$OLD_OCCUPATION_ARR[$oldOVal]);	
			$smarty->assign("NEW_OCCUPATION",$OCCUPATION_DROP[$newOVal]);	
			$fieldArr[] = "Occupations";
        		$smarty->assign("CURRENT_OCC_VAL_NUM",$newOVal);
			$smarty->assign("OCCUPATION_DISPLAY",1);
		}
		if(in_array("4",$revamp_val_arr))
		{
			$smarty->assign("OLD_OCCUPATION",$OLD_OCCUPATION_ARR[$oldOVal]);
			$smarty->assign("NEW_OCCUPATION",$OCCUPATION_DROP[$newOVal]);
			$fieldArr[] = "Occupations";
        		$smarty->assign("CURRENT_OCC_VAL_NUM",$newOVal);
			$smarty->assign("OCCUPATION_DISPLAY",1);
			$occ_dropdown_arr = explode(",",$OCC_SPLIT_ARR[$oldOVal]);
			foreach($occ_dropdown_arr as $k=>$v)
			{
				$occ_dropdown[$v] = $OCCUPATION_DROP[$v];
			}
			$smarty->assign("OCC_DROPDOWN",$occ_dropdown);
			$display_layer_multiple = 1;
		}
		if(in_array("5",$revamp_val_arr))
		{
			$smarty->assign("OLD_OCCUPATION",$OLD_OCCUPATION_ARR[$oldOVal]);
			$smarty->assign("NEW_OCCUPATION","Select");
			$fieldArr[] = "Occupations";
        		$smarty->assign("CURRENT_OCC_VAL_NUM",$newOVal);
			$smarty->assign("OCCUPATION_DISPLAY",1);
			$smarty->assign("OCC_DROPDOWN",$OCC_OTHER_ARR);
			$display_layer_multiple = 1;
		}
		if(in_array("6",$revamp_val_arr))
		{
			$smarty->assign("OLD_EDUCATION",$OLD_EDU_ARR[$oldEVal]);
                        $smarty->assign("NEW_EDUCATION",$EDUCATION_LEVEL_NEW_DROP[$newEVal]);
                        $fieldArr[] = "Education";
                        $smarty->assign("CURRENT_EDU_VAL_NUM",$newEVal);
                        $smarty->assign("EDUCATION_DISPLAY",1);
			if($oldEVal==27)
			{
                        	$smarty->assign("EDU_DROPDOWN",$EDUCATION_LEVEL_NEW_DROP);
			}
			else
			{
                        	$edu_dropdown_arr = explode(",",$EDU_SPLIT_ARR[$oldEVal]);
                        	foreach($edu_dropdown_arr as $k=>$v)
                        	{
                                	$edu_dropdown[$v] = $EDUCATION_LEVEL_NEW_DROP[$v];
                        	}
                        	$smarty->assign("EDU_DROPDOWN",$edu_dropdown);
			}
                        $display_layer_multiple = 1;
		}

		if(count($fieldArr)>1)
		{
			$field_val_str = "";
			foreach($fieldArr as $k=>$v)
			{
				if($k==count($fieldArr)-1)
				{
					$field_val_str = trim($field_val_str);
					$field_val_str = rtrim($field_val_str,",");
					$field_val_str = $field_val_str." and ".$v;
				}
				else
				{
					$field_val_str = $field_val_str.$v.", ";
				}
			}
			$smarty->assign("FIELD_VALS",$field_val_str);
		}
		else
		{
			$smarty->assign("FIELD_VALS",implode(",",$fieldArr));
		}
	}

	if($display_layer_multiple==0)
	{
		$statement = "UPDATE MIS.REVAMP_LAYER_CHECK SET CASTE_REVAMP_FLAG = 0 WHERE PROFILEID = ".$profileid;
        	mysql_query($statement,$db) or logError("due to some temporary problem your request could not be processed. please try after some time.",$statement,"ShowErrTemplate");
	}

	$smarty->assign("DISPLAY_LAYER_MULTIPLE",$display_layer_multiple);
	$smarty->display("myjs_caste_revamp_layer.htm");
?>
