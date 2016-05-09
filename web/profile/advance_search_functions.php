<?php
/*************************advance_search_functions.php**********************
        Created By                      : Neha Verma
        Created on                      : 1-Oct-2008
        Description                     : This file contains various function 
					  used by advance search
******************************************************************************/

function ASform_logged_nodpp($profileid,$partner_exist="",$flag="")
{
        global $smarty;

        $default_sql="select AGE,HEIGHT,RELIGION,MTONGUE from JPROFILE where  activatedKey=1 and PROFILEID='$profileid'";
        $default_res=mysql_query_decide($default_sql) or logError($ERROR_STRING,$default_sql);
        if($default_row=mysql_fetch_assoc($default_res))
        {
                if(!$partner_exist && $flag!='search')
                {
                        $smarty->assign("d_caste",$default_row['CASTE']);
                        $smarty->assign("d_manglik",$default_row['MANGLIK']);
                        $smarty->assign("d_mstatus",$default_row['MSTATUS']);
                }
                $global_profile_info["AGE"]=$default_row['AGE'];
                $global_profile_info["HEIGHT"]=$default_row['HEIGHT'];
                $global_profile_info["CASTE"]=$default_row['CASTE'];
                $global_profile_info["MSTATUS"]=$default_row['MSTATUS'];
	}
}

/************************************************************************************************
Function to fill values in multi select gadget present in advance search and edit profile templates
@param string $field is name of the field corresponding to which values has to be populated
@param string $values are set of values which are pre-selected 
**************************************************************************************************/
function fill_MSgadget($field,$values,$flag='',$from_edit='')
{
        global $smarty,$EDUCATION_GROUPING_DROP;
	include("arrays.php");
	if($field=="Hchild")
	{ 
		$hidden_vals.=" <input type=\"checkbox\" value=\"DM\" name=\"partner_hchild_arr[]\" id=\"partner_hchild_DM\"> <label id=\"partner_hchild_label_DM\">Doesn't Matter</label><br>";
                foreach($CHILDREN as $value=>$label)
                {
                        $checkIt="";
                        if(in_array($value,explode(",",$values)))
                        {
                                if($checked)
                                        $checked.=",".$value;
                                else
                                        $checked.=$value;
                                $checkIt='checked="checked"';
                        }
                        else
                        {
                                //$shown_vals.= "<input type=\"checkbox\" class=\"chbx \" name=\"partner_hchild_displaying_arr[]\" checked=\"checked\" id=\"partner_hchild_displaying_".$value."\" value=".$value."><label id=\"partner_hchild_displaying_label_".$value."\">".$label."</label><br>";
                        }
                        $hidden_vals.=" <input type=\"checkbox\" value=".$value." name=\"partner_hchild_arr[]\" $checkIt id=\"partner_hchild_".$value."\"> <label id=\"partner_hchild_label_".$value."\">".$label."</label><br>";
                        $shown_vals.= "<input type=\"checkbox\" class=\"chbx \" name=\"partner_hchild_displaying_arr[]\" ".$checkIt." id=\"partner_hchild_displaying_".$value."\" value=".$value."><label id=\"partner_hchild_displaying_label_".$value."\">".$label."</label><br>";
                }
           
                $smarty->assign("checked_hchild",$checked);
                $smarty->assign("hidden_hchild",$hidden_vals);
                $smarty->assign("shown_hchild",$shown_vals);
	}
	elseif($field=="Sampraday")
	{
		//$sampraday= array("DM"=>"Doesn't Matter","M"=>"Murthipujak",'S'=>"Sthanakwas",'T'=>"Terapanth");
		$smarty->assign("sampraday",$SAMPRADAY);
	}
	elseif($field=="Turban")
	{
		$turban= array("DM"=>"Doesn't Matter",'Y'=>"Yes",'N'=>"No",'O'=>"Occasionally");
		$smarty->assign("turban",$turban);
	}
	elseif($field=="Mathab")
	{
		$hidden_vals.=" <input type=\"checkbox\" value=\"DM\" name=\"partner_mathab_arr[]\" id=\"partner_mathab_DM\"> <label id=\"partner_mathab_label_DM\">Doesn't Matter</label><br>";
                foreach($MATHTHAB as $value=>$label)
                {
                        $hidden_vals.=" <input type=\"checkbox\" value=".$value." name=\"partner_mathab_arr[]\" id=\"partner_mathab_".$value."\"> <label id=\"partner_mathab_label_".$value."\">".$label."</label><br>";
                        $shown_vals.= "<input type=\"checkbox\" class=\"chbx \" name=\"partner_mathab_displaying_arr[]\" id=\"partner_mathab_displaying_".$value."\" value=".$value."><label id=\"partner_mathab_displaying_label_".$value."\">".$label."</label><br>";
                }
                $smarty->assign("hidden_mathab",$hidden_vals);
                $smarty->assign("shown_mathab",$shown_vals);
	}
	elseif($field=="Diet")
	{
		$val_array=explode(',',$values);
		$hidden_vals.=" <input type=\"checkbox\" value=\"DM\" name=\"partner_diet_arr[]\" id=\"partner_diet_DM\"> <label id=\"partner_diet_label_DM\">Doesn't Matter</label><br>";
                foreach($DIET as $value=>$label)
                {
                        $hidden_vals.=" <input type=\"checkbox\" value=".$value." name=\"partner_diet_arr[]\" id=\"partner_diet_".$value."\"> <label id=\"partner_diet_label_".$value."\">".$label."</label><br>";
			$val="'".$value."'";
			if(in_array($val,$val_array))                     
                        {
                                if($checked)
                                        $checked.=",".$val;
                                else
                                        $checked.=$val;
                        }
                        else
                        {
                                $shown_vals.= "<input type=\"checkbox\" class=\"chbx \" name=\"partner_diet_displaying_arr[]\" id=\"partner_diet_displaying_".$value."\" value=".$value."><label id=\"partner_diet_displaying_label_".$value."\">".$label."</label><br>";
                        }
                }
                $smarty->assign("checked_diet",$checked);
                $smarty->assign("hidden_diet",$hidden_vals);
                $smarty->assign("shown_diet",$shown_vals);
	}
	elseif($field=="Smoke")
        {
                $val_array=explode(',',$values);
                $hidden_vals.=" <input type=\"checkbox\" value=\"DM\" name=\"partner_smoke_arr[]\" id=\"partner_smoke_DM\"> <label id=\"partner_smoke_label_DM\">Doesn't Matter</label><br>";
                foreach($SMOKE as $value=>$label)
                {
                        $hidden_vals.=" <input type=\"checkbox\" value=".$value." name=\"partner_smoke_arr[]\" id=\"partner_smoke_".$value."\"> <label id=\"partner_smoke_label_".$value."\">".$label."</label><br>";
                        $val="'".$value."'";
                        if(in_array($val,$val_array))
                        {
                                if($checked)
                                        $checked.=",".$val;
                                else
                                        $checked.=$val;
                        }
                        else
                        {
                                $shown_vals.= "<input type=\"checkbox\" class=\"chbx \" name=\"partner_smoke_displaying_arr[]\" id=\"partner_smoke_displaying_".$value."\" value=".$value."><label id=\"partner_smoke_displaying_label_".$value."\">".$label."</label><br>";
                        }
                }
                $smarty->assign("checked_smoke",$checked);
                $smarty->assign("hidden_smoke",$hidden_vals);
                $smarty->assign("shown_smoke",$shown_vals);
        }
	elseif($field=="Drink")
        {
                $val_array=explode(',',$values);
                $hidden_vals.=" <input type=\"checkbox\" value=\"DM\" name=\"partner_drink_arr[]\" id=\"partner_drink_DM\"> <label id=\"partner_drink_label_DM\">Doesn't Matter</label><br>";
                foreach($DRINK as $value=>$label)
                {
                        $hidden_vals.=" <input type=\"checkbox\" value=".$value." name=\"partner_drink_arr[]\" id=\"partner_drink_".$value."\"> <label id=\"partner_drink_label_".$value."\">".$label."</label><br>";
                        $val="'".$value."'";
                        if(in_array($val,$val_array))
                        {
                                if($checked)
                                        $checked.=",".$val;
                                else
                                        $checked.=$val;
                        }
                        else
                        {
                                $shown_vals.= "<input type=\"checkbox\" class=\"chbx \" name=\"partner_drink_displaying_arr[]\" id=\"partner_drink_displaying_".$value."\" value=".$value."><label id=\"partner_drink_displaying_label_".$value."\">".$label."</label><br>";
                        }
                }
                $smarty->assign("checked_drink",$checked);
                $smarty->assign("hidden_drink",$hidden_vals);
                $smarty->assign("shown_drink",$shown_vals);
        }
	elseif($field=="Body")
	{
		$val_array=explode(',',$values);
		$hidden_vals.=" <input type=\"checkbox\" value=\"DM\" name=\"partner_body_arr[]\" id=\"partner_body_DM\"> <label id=\"partner_body_label_DM\">Doesn't Matter</label><br>";
                foreach($BODYTYPE as $value=>$label)
                {
                        $hidden_vals.=" <input type=\"checkbox\" value=".$value." name=\"partner_body_arr[]\" id=\"partner_body_".$value."\"> <label id=\"partner_body_label_".$value."\">".$label."</label><br>";
			$val="'".$value."'";
			if(in_array($val,$val_array))                     
                        {
                                if($checked)
                                        $checked.=",".$val;
                                else
                                        $checked.=$val;
                        }
                        else
                        {
                                $shown_vals.= "<input type=\"checkbox\" class=\"chbx \" name=\"partner_body_displaying_arr[]\" id=\"partner_body_displaying_".$value."\" value=".$value."><label id=\"partner_body_displaying_label_".$value."\">".$label."</label><br>";
                        }
                }
                $smarty->assign("checked_body",$checked);
                $smarty->assign("hidden_body",$hidden_vals);
                $smarty->assign("shown_body",$shown_vals);
	}
	elseif($field=="Complexion")
	{
		$val_array=explode(',',$values);
		$hidden_vals.=" <input type=\"checkbox\" value=\"DM\" name=\"partner_complexion_arr[]\" id=\"partner_complexion_DM\"> <label id=\"partner_complexion_label_DM\">Doesn't Matter</label><br>";
                foreach($COMPLEXION as $value=>$label)
                {
                        $hidden_vals.=" <input type=\"checkbox\" value=".$value." name=\"partner_complexion_arr[]\" id=\"partner_complexion_".$value."\"> <label id=\"partner_complexion_label_".$value."\">".$label."</label><br>";
			$val="'".$value."'";
			if(in_array($val,$val_array))                     
                        {
                                if($checked)
                                        $checked.=",".$val;
                                else
                                        $checked.=$val;
                        }
                        else
                        {
                                $shown_vals.= "<input type=\"checkbox\" class=\"chbx \" name=\"partner_complexion_displaying_arr[]\" id=\"partner_complexion_displaying_".$value."\" value=".$value."><label id=\"partner_complexion_displaying_label_".$value."\">".$label."</label><br>";
                        }
                }
                $smarty->assign("checked_complexion",$checked);
                $smarty->assign("hidden_complexion",$hidden_vals);
                $smarty->assign("shown_complexion",$shown_vals);
	}
	elseif($field=="Wstatus")
        {  
                if($values)
                        $wstatusSel=explode(",",$values);
                else
                        $wstatusSel[0]="DM";
                foreach($wstatusSel as $k=>$v){
                        $wstatusSelected[$v]=1;
                }
                $smarty->assign("wstatusSelected",$wstatusSelected);
                
                $wstatus=array("DM"=>"Any",1=>"Not Working",2=>"Employed",3=>"Entrepreneur",4=>"Consultant",5=>"Student",6=>"Academia",7=>"Defence",8=>"Independent Worker/Freelancer");
		$smarty->assign("wstatus",$wstatus);
	}
	elseif($field=="Handicap")
        {
        	$smarty->assign("handicap",$HANDICAPPED);
		$val_array=explode(',',$values);
                $hidden_vals.=" <input type=\"checkbox\" value=\"DM\" name=\"partner_handicapped_arr[]\" id=\"partner_handicapped_DM\"> <label id=\"partner_handicapped_label_DM\">Doesn't Matter</label><br>";
                foreach($HANDICAPPED as $value=>$label)
                {
                        $hidden_vals.=" <input type=\"checkbox\" value=".$value." name=\"partner_handicapped_arr[]\" id=\"partner_handicapped_".$value."\"> <label id=\"partner_handicapped_label_".$value."\">".$label."</label><br>";
                        $val="'".$value."'";
                        if(in_array($val,$val_array))
                        {
                                if($checked)
                                        $checked.=",".$val;
                                else
                                        $checked.=$val;
                        }
                        else
                        {
                                $shown_vals.= "<input type=\"checkbox\" class=\"chbx \" name=\"partner_handicapped_displaying_arr[]\" id=\"partner_handicapped_displaying_".$value."\" value=".$value."><label id=\"partner_handicapped_displaying_label_".$value."\">".$label."</label><br>";
                        }
                }
                $smarty->assign("checked_handicapped",$checked);
                $smarty->assign("hidden_handicapped",$hidden_vals);
                $smarty->assign("shown_handicapped",$shown_vals);
	}
	elseif($field=="Nhandicap")
        {
        	$smarty->assign("nhandicap",$NATURE_HANDICAP);
		$val_array=explode(',',$values);
                $hidden_vals.=" <input type=\"checkbox\" value=\"DM\" name=\"partner_nhandicapped_arr[]\" id=\"partner_nhandicapped_DM\"> <label id=\"partner_nhandicapped_label_DM\">Doesn't Matter</label><br>";
                foreach($NATURE_HANDICAP as $value=>$label)
                {
                        $hidden_vals.=" <input type=\"checkbox\" value=".$value." name=\"partner_nhandicapped_arr[]\" id=\"partner_nhandicapped_".$value."\"> <label id=\"partner_nhandicapped_label_".$value."\">".$label."</label><br>";
                        $val="'".$value."'";
                        if(in_array($val,$val_array))
                        {
                                if($checked)
                                        $checked.=",".$val;
                                else
                                        $checked.=$val;
                        }
                        else
                        {
                                $shown_vals.= "<input type=\"checkbox\" class=\"chbx \" name=\"partner_nhandicapped_displaying_arr[]\" id=\"partner_nhandicapped_displaying_".$value."\" value=".$value."><label id=\"partner_nhandicapped_displaying_label_".$value."\">".$label."</label><br>";
                        }
                }
                $smarty->assign("checked_nhandicapped",$checked);
                $smarty->assign("hidden_nhandicapped",$hidden_vals);
                $smarty->assign("shown_nhandicapped",$shown_vals);

	}
	elseif($field=="Mstatus")
	{
		$val_array=explode(',',$values);
		$hidden_vals.=" <input type=\"checkbox\" value=\"DM\" name=\"partner_mstatus_arr[]\" id=\"partner_mstatus_DM\"> <label id=\"partner_mstatus_label_DM\">Doesn't Matter</label><br>";
		foreach($MSTATUS as $value=>$label)
		{
			if(!$flag || ($flag && $value!='M'))
			{	
				$hidden_vals.=" <input type=\"checkbox\" value=".$value." name=\"partner_mstatus_arr[]\" id=\"partner_mstatus_".$value."\"> <label id=\"partner_mstatus_label_".$value."\">".$label."</label><br>";
				$val="'".$value."'";
				if(in_array($val,$val_array))
				{
					if($checked)
						$checked.=",".$val;
					else
						$checked.=$val;
				}
				else
				{
					$shown_vals.= "<input type=\"checkbox\" class=\"chbx \" name=\"partner_mstatus_displaying_arr[]\" id=\"partner_mstatus_displaying_".$value."\" value=".$value."><label id=\"partner_mstatus_displaying_label_".$value."\">".$label."</label><br>";
				}
			}
		}
		$smarty->assign("checked_mstatus",$checked);
		$smarty->assign("hidden_mstatus",$hidden_vals);
                $smarty->assign("shown_mstatus",$shown_vals);
	}
	elseif($field=="Manglik")
	{
		$val_array=explode(',',$values);
		$hidden_vals.=" <input type=\"checkbox\" value=\"DM\" name=\"partner_manglik_arr[]\" id=\"partner_manglik_DM\"> <label id=\"partner_manglik_label_DM\">Doesn't Matter</label><br>";
		foreach($MANGLIK as $value=>$label)
		{
			if($value!='D')
			{	
				$hidden_vals.=" <input type=\"checkbox\" value=".$value." name=\"partner_manglik_arr[]\" id=\"partner_manglik_".$value."\"> <label id=\"partner_manglik_label_".$value."\">".$label."</label><br>";
				$val="'".$value."'";
				if(in_array($val,$val_array))
				{
					if($checked)
						$checked.=",".$val;
					else
						$checked.=$val;
				}
				else
				{
					$shown_vals.= "<input type=\"checkbox\" class=\"chbx \" name=\"partner_manglik_displaying_arr[]\" id=\"partner_manglik_displaying_".$value."\" value=".$value."><label id=\"partner_manglik_displaying_label_".$value."\">".$label."</label><br>";
				}
			}
		}
		$smarty->assign("checked_manglik",$checked);
		$smarty->assign("hidden_manglik",$hidden_vals);
                $smarty->assign("shown_manglik",$shown_vals);
	}
	elseif($field=="Religion")
	{
		$val_array=explode(',',$values);
		if($flag!='1')
			$rval_array=explode(',',$flag);
		$sql = "SELECT SQL_CACHE VALUE,LABEL FROM newjs.RELIGION ORDER BY SORTBY";
        	$res = mysql_query_decide($sql) or logError("error",$sql);
		$rel_arr= Array();	
        	while($row = mysql_fetch_array($res))
        	{
	                $label = $row['LABEL'];
	                $religion_value = $row['VALUE'];
			if($flag!='1' && is_array($rval_array))
				if(in_array("'".$religion_value."'",$rval_array))
					$rel_flag=1;
	                $sql_caste = "SELECT SQL_CACHE VALUE,LABEL,ISGROUP from CASTE WHERE PARENT='$religion_value' AND VALUE NOT IN (242,243,244,245,246) ORDER BY SORTBY";
	                $res_caste = mysql_query_decide($sql_caste) or logError("error",$sql);
	                while($row_caste = mysql_fetch_array($res_caste))
	                {
			
				$caste_value = $row_caste['VALUE'];
				$val="'".$caste_value."'";
				if($flag=='1')
                                {
					if(in_array("'".$religion_value."'",$val_array))
                                        	$rel_flag=1;
                                }
				else
				{
					if(in_array($val,$val_array))
					{
						$checked[]=$val;
						if($religion_value=='2')
						$checked_caste=$caste_value;
						$rel_flag=1;
					}
				}

	                        $caste_label_arr = explode(": ",$row_caste['LABEL']);
	                        if($caste_label_arr[1])
	                        $caste_label = $caste_label_arr[1];
	                        else
	                        $caste_label = $caste_label_arr[0];
	                        $caste_label=str_replace(" ",":",$caste_label);
				if($row_caste['ISGROUP']=='Y')
                                        $caste_label="All:".$caste_label;
	                        $caste_str .= $caste_value."$".$caste_label."#";
        	        }
        	        $religion_str = $religion_value."|X|".$caste_str;
			if($rel_flag)
				$rel_arr[]=$religion_str;
			else
			{
				if($from_edit)
                               		$shown_vals.= "<input type=\"checkbox\" onclick=\"add_checkboxes(this); remove_doesnt_matter_conflict(this); \"  class=\"chbx \" name=\"partner_religion_displaying_arr[]\" id=\"partner_religion_displaying_".$religion_str."\" value=".$religion_str."><label id=\"partner_religion_displaying_label_".$religion_str."\">".$label."</label><br>";
                        	else
	                               	$shown_vals.= "<input type=\"checkbox\" class=\"chbx \" name=\"partner_religion_displaying_arr[]\" id=\"partner_religion_displaying_".$religion_str."\" value=".$religion_str."><label id=\"partner_religion_displaying_label_".$religion_str."\">".$label."</label><br>";
			}
			$rel_flag=0;
        	        $hidden_vals.=" <input type=\"checkbox\" value=".$religion_str." name=\"partner_religion_arr[]\" id=\"partner_religion_".$religion_str."\"> <label id=\"partner_religion_label_".$religion_str."\">".$label."</label><br>";
               		unset($caste_str);
        	}
		//$checked_rel=implode("','",$rel_arr);
		//$checked_rel="'".$checked_rel."'";
		$smarty->assign("muslim_caste",$checked_caste);
        	$smarty->assign("checked_religion",$rel_arr);
        	$smarty->assign("checked_caste",$checked);
        	$smarty->assign("hidden_religion",$hidden_vals);
        	$smarty->assign("shown_religion",$shown_vals);
	}
	elseif($field=="Mtongue")
	{
		$sql = "SELECT SQL_CACHE VALUE, LABEL, SMALL_LABEL, REGION FROM MTONGUE WHERE REGION <> '5' ORDER BY REGION DESC,SORTBY_NEW";
                $res = mysql_query_optimizer($sql) or logError("error",$sql);
                $hidden_vals.=" <input type=\"checkbox\" value=\"DM\" name=\"partner_mtongue_arr[]\" id=\"partner_mtongue_DM\"> <label id=\"partner_mtongue_label_DM\">All</label><br>";
                while($myrow = mysql_fetch_array($res))
                {
                        $mtongue_region=$myrow['REGION'];
			$val_array=explode(",",$values);
                        if($mtongue_region!=$mtongue_region_old)
                        {
                        	if($mtongue_region==5)
                                {
                                                //2339:lavesh -- All Hindi will come below North Option.
                                       $flag_allhindi.= "<option value=\"10,19,33,7,28,13,41\"  >All Hindi</option>\n"; 
                                       $hidden_vals_hindi.=" <input type=\"checkbox\" value=\"10,19,33,7,28,13,41\" name=\"partner_mtongue_arr[]\" id=\"partner_mtongue_10,19,33,7,28,13,41\"> <label id=\"partner_mtongue_label_10,19,33,7,28,13,41\">All Hindi</label><br>";
                                       $shown_vals_hindi.= "<input type=\"checkbox\" class=\"chbx \" name=\"partner_mtongue_displaying_arr[]\" id=\"partner_mtongue_displaying_10,19,33,7,28,13,41\" value=\"10,19,33,7,28,13,41\"><label id=\"partner_mtongue_displaying_label_10,19,33,7,28,13,41`\">All Hindi</label><br>";
                                 }
                                 elseif($mtongue_region==4)
                                 {
                        	        $shown_vals.=" <span style=\"color:#0a89fe;\">North India</span><div class=\"clear\" style=\"line-height:5px;\"> &nbsp;</div>";
					$hidden_vals.= "<input type=\"checkbox\" name=\"partner_mtongue_arr[]\" id=\"partner_mtongue_10|#|41|#|33|#|27|#|7|#|28|#|13|#|14|#|15|#|70|#|36|#|10,19,33,7,28,13,41\" value=\"10|#|41|#|33|#|27|#|7|#|28|#|13|#|14|#|15|#|70|#|36|#|10,19,33,7,28,13,41\"><label id=\"partner_mtongue_label_10|#|41|#|33|#|27|#|7|#|28|#|13|#|14|#|15|#|70|#|36|#|10,19,33,7,28,13,41\">North India</label><br>";
					 $flag_allhindi.= "<option value=\"10,19,33,7,28,13,41\"  >All Hindi</option>\n";
                                       $hidden_vals_hindi.=" <input type=\"checkbox\" value=\"10,19,33,7,28,13,41\" name=\"partner_mtongue_arr[]\" id=\"partner_mtongue_10,19,33,7,28,13,41\"> <label id=\"partner_mtongue_label_10,19,33,7,28,13,41\">All Hindi</label><br>";
                                       $shown_vals_hindi.= "<input type=\"checkbox\" class=\"chbx \" name=\"partner_mtongue_displaying_arr[]\" id=\"partner_mtongue_displaying_10,19,33,7,28,13,41\" value=\"10,19,33,7,28,13,41\"><label id=\"partner_mtongue_displaying_label_10,19,33,7,28,13,41`\">All Hindi</label><br>";
					if($flag_allhindi)
                                        {
                                        	$hidden_vals.=$hidden_vals_hindi;
                                        	$shown_vals.=$shown_vals_hindi;
                                        }
				}
				elseif($mtongue_region==3)
                                {
                                     $shown_vals.=" <span style=\"color:#0a89fe;\">West India</span><div class=\"clear\" style=\"line-height:5px;\"> &nbsp;</div>";
                                                $hidden_vals.= "<input type=\"checkbox\" class=\"chbx \" name=\"partner_mtongue_arr[]\" id=\"partner_mtongue_20|#|12|#|19|#|34|#|30|#|9\" value=\"20|#|12|#|19|#|34|#|30|#|9\"><label id=\"partner_mtongue_label_20|#|12|#|19|#|34|#|30|#|9\">West India</label><br>";
                                }
                                elseif($mtongue_region==2)
                                {
                                          $shown_vals.=" <span style=\"color:#0a89fe;\">South India</span><div class=\"clear\" style=\"line-height:5px;\"> &nbsp;</div>";
                                                $hidden_vals.= "<input type=\"checkbox\" class=\"chbx \" name=\"partner_mtongue_arr[]\" id=\"partner_mtongue_31|#|3|#|16|#|17|#|2|#|18|#|35|#|71\" value=\"31|#|3|#|16|#|17|#|2|#|18|#|35|#|71\"><label id=\"partner_mtongue_label_31|#|3|#|16|#|17|#|2|#|18|#|35|#|71\">South India</label><br>";
                                }
                                elseif($mtongue_region==1)
                                {
                                                $shown_vals.=" <span style=\"color:#0a89fe;\">East India</span><div class=\"clear\" style=\"line-height:5px;\"> &nbsp;</div>";
                                                $hidden_vals.= "<input type=\"checkbox\" class=\"chbx \" name=\"partner_mtongue_arr[]\" id=\"partner_mtongue_6|#|25|#|5|#|4|#|21|#|22|#|23|#|24|#|29|#|32\" value=\"6|#|25|#|5|#|4|#|21|#|22|#|23|#|24|#|29|#|32\"><label id=\"partner_mtongue_label_6|#|25|#|5|#|4|#|21|#|22|#|23|#|24|#|29|#|32\">East India</label><br>";
                                }
                                $mtongue_region_old=$mtongue_region;
			}
			if($myrow['VALUE']=='19' && $mtongue_region==4)
				$myrow['VALUE']='41';
			if($myrow['VALUE']=='30' && $mtongue_region==4)
                                $myrow['VALUE']='70';
			if($myrow['VALUE']=='36' && $mtongue_region==2)
                                $myrow['VALUE']='71';

			$hidden_vals.=" <input type=\"checkbox\" value=".$myrow['VALUE']." name=\"partner_mtongue_arr[]\" id=\"partner_mtongue_".$myrow['VALUE']."\"> <label id=\"partner_mtongue_label_".$myrow['VALUE']."\">".$myrow['LABEL']."</label><br>";
			$val="'".$myrow['VALUE']."'";
			if(in_array($val,$val_array))
                        {
//				if($val=="'10'")
//					$val="'10,19,33,7,28,13'";
                        	if($checked)
                                       $checked.=",".$val;
                                else
                                       $checked.=$val;
                        }
                        else
			{
                                $shown_vals.= "<input type=\"checkbox\" class=\"chbx \" name=\"partner_mtongue_displaying_arr[]\" id=\"partner_mtongue_displaying_".$myrow['VALUE']."\" value=".$myrow['VALUE']."><label id=\"partner_mtongue_displaying_label_".$myrow['VALUE']."\">".$myrow['LABEL']."</label><br>";
			}
				/*if(!in_array($myrow["VALUE"],array(7,10,19,33,28)))// lavesh(restrict 3 option of all hindi)
                                {
                                        $hidden_vals.=" <input type=\"checkbox\" value=".$myrow['VALUE']." name=\"partner_mtongue_arr[]\" id=\"partner_mtongue_".$myrow['VALUE']."\"> <label id=\"partner_mtongue_label_".$myrow['VALUE']."\">".$myrow['LABEL']."</label><br>";
                                        $shown_vals.= "<input type=\"checkbox\" class=\"chbx \" name=\"partner_mtongue_displaying_arr[]\" id=\"partner_mtongue_displaying_".$myrow['VALUE']."\" value=".$myrow['VALUE']."><label id=\"partner_mtongue_displaying_label_".$myrow['VALUE']."\">".$myrow['LABEL']."</label><br>";
                                }*/
                }
		$smarty->assign("checked_mtongue",$checked);
                $smarty->assign("hidden_mton",$hidden_vals);
                $smarty->assign("shown_mton",$shown_vals);
	}
        elseif($field=="Country_Residence")
	{
		$val_array=explode(',',$values);
		$sql ="SELECT a.LABEL as country_lab,a.VALUE as country,b.LABEL as city_lab,b.VALUE as city FROM newjs.COUNTRY_NEW as a,CITY_NEW as b WHERE a.VALUE =b.COUNTRY_VALUE AND b.TYPE!='STATE' ORDER BY TOP_COUNTRY DESC , a.LABEL, b.LABEL";
		$res= mysql_query_decide($sql) or die(mysql_error_js());
		$country_temp='7';
		$value="7";
		$label="Australia";	
		while($myrow=mysql_fetch_array($res))
		{
			if($myrow['country']==$country_temp )
			{
				$val="'".$country_temp."'";
                                if(in_array($val,$val_array))
                                {
					$city=$myrow['city'];
					$city_hidden_vals.=" <input type=\"checkbox\" value=".$city." name=\"partner_city_arr[]\" id=\"partner_city_".$city."\"> <label id=\"partner_city_label_".$city."\">".$myrow['city_lab']."</label><br>";
					$city_val="'".$city."'";
					if(!@in_array($city_val,$flag))
						$city_shown_vals.= "<input type=\"checkbox\" class=\"chbx \" name=\"partner_city_displaying_arr[]\" id=\"partner_city_displaying_".$city."\" value=".$city."><label id=\"partner_city_displaying_label_".$city."\">".$myrow['city_lab']."</label><br>";

				}
				$city_lab=str_replace(" ",":",$myrow['city_lab']);
				$value.="#".$city_lab."|".$myrow['city'];
			}
			else
			{
				$hidden_vals.=" <input type=\"checkbox\" value=".$value." name=\"partner_country_arr[]\" id=\"partner_country_".$value."\"> <label id=\"partner_country_label_".$value."\">".$label."</label><br>";
				$val="'".$country_temp."'";
	                        if(in_array($val,$val_array))
	                        {
	                                if($checked)
	                                        $checked.=",'".$value."'";
	                                else
	                                        $checked.="'".$value."'";
	                        }
	                        else
				{
					if($from_edit)
		                		$shown_vals.= "<input type=\"checkbox\" onclick=\"add_checkboxes(this); remove_doesnt_matter_conflict(this); \"  class=\"chbx \" name=\"partner_country_displaying_arr[]\" id=\"partner_country_displaying_".$value."\" value=".$value."><label id=\"partner_country_displaying_label_".$value."\">".$label."</label><br>";
					else
		                		$shown_vals.= "<input type=\"checkbox\" class=\"chbx \" name=\"partner_country_displaying_arr[]\" id=\"partner_country_displaying_".$value."\" value=".$value."><label id=\"partner_country_displaying_label_".$value."\">".$label."</label><br>";
				}
				$label=$myrow['country_lab'];
				$value=$myrow['country'];
				$country_temp=$value;
				$city_lab=str_replace(" ",":",$myrow['city_lab']);
                                $value.="#".$city_lab."|".$myrow['city'];
			}

		}
		$val="'136'";
		$value='136';
		$label='Others';
		$hidden_vals.=" <input type=\"checkbox\" value=".$value." name=\"partner_country_arr[]\" id=\"partner_country_".$value."\"> <label id=\"partner_country_label_".$value."\">".$label."</label><br>";
		if(in_array($val,$val_array))
		{
			if($checked)
                        	$checked.=",'".$value."'";
                        else
                                $checked.="'".$value."'";
		}
		else
                {
	                if($from_edit)
                                  $shown_vals.= "<input type=\"checkbox\" onclick=\"add_checkboxes(this); remove_doesnt_matter_conflict(this); \"  class=\"chbx \" name=\"partner_country_displaying_arr[]\" id=\"partner_country_displaying_".$value."\" value=".$value."><label id=\"partner_country_displaying_label_".$value."\">".$label."</label><br>";
                         else
                                  $shown_vals.= "<input type=\"checkbox\" class=\"chbx \" name=\"partner_country_displaying_arr[]\" id=\"partner_country_displaying_".$value."\" value=".$value."><label id=\"partner_country_displaying_label_".$value."\">".$label."</label><br>";
                 }

		if(strstr($checked,"'51#"))
		{
                	$city_hidden_vals=" <input type=\"checkbox\" value=\"NCR\"  name=\"partner_city_arr[]\" id=\"partner_city_NCR\"> <label id=\"partner_city_label_NCR\">Delhi/NCR</label><br>".$city_hidden_vals;

		}	
		$smarty->assign("hidden_city",$city_hidden_vals);
                $smarty->assign("shown_city",$city_shown_vals);
		$smarty->assign("checked_country",$checked);
		$smarty->assign("hidden_country",$hidden_vals);
                $smarty->assign("shown_country",$shown_vals);
	}
        elseif($field=="Country_ResidenceState")
	{
                $CITY_GROUPING_DROP = array("STATE"=>"Major Indian States","CITY"=>"Major Indian Cities");
                $state_group = true;
                $city_group = true;
		$val_array=explode(',',$values);
		$sql ="SELECT a.LABEL as country_lab,a.VALUE as country,b.LABEL as city_lab,b.VALUE as city,b.TYPE FROM newjs.COUNTRY_NEW as a,CITY_NEW as b WHERE a.VALUE =b.COUNTRY_VALUE ORDER BY TOP_COUNTRY DESC , a.LABEL, b.LABEL";
		$res= mysql_query_decide($sql) or die(mysql_error_js());
		$country_temp='7';
		$value="7";
		$label="Australia";
                $stateValue="";
		while($myrow=mysql_fetch_array($res))
		{ 
                        if(($state_group && $myrow["TYPE"]=="STATE") || ($city_group && $myrow["TYPE"]=="CITY"))
                        {
                                $optg=$CITY_GROUPING_DROP[$myrow["TYPE"]];
                                $dataCat=" <span style=\"color:#0a89fe;\" cat='".$myrow["TYPE"]."'>".$optg."</span><div class=\"clear\" style=\"line-height:5px;\"> &nbsp;</div>";
                                if($myrow["TYPE"]=="STATE"){
                                        $state_shown_vals.=$dataCat;
                                        $state_group= false;
                                }
                                else{
                                        $city_shown_vals.=$dataCat;
                                        $city_group= false;
                                }
                        }
			if($myrow['country']==$country_temp )
			{ 
				$val="'".$country_temp."'";
                                if(in_array($val,$val_array))
                                {
					$city=$myrow['city'];
					if($myrow['TYPE']!="STATE")
                                                $city_hidden_vals.=" <input type=\"checkbox\" value=".$city." name=\"partner_city_arr[]\" id=\"partner_city_".$city."\"> <label id=\"partner_city_label_".$city."\">".$myrow['city_lab']."</label><br>";
					else
                                                $state_hidden_vals.=" <input type=\"checkbox\" value=".$city." name=\"partner_city_arr[]\" id=\"partner_city_".$city."\"> <label id=\"partner_city_label_".$city."\">".$myrow['city_lab']."</label><br>";
                                        $city_val="'".$city."'";
					if(!@in_array($city_val,$flag)){
                                                if($myrow['TYPE']!="STATE")
                                                        $city_shown_vals.= "<input type=\"checkbox\" class=\"chbx \" name=\"partner_city_displaying_arr[]\" id=\"partner_city_displaying_".$city."\" value=".$city."><label id=\"partner_city_displaying_label_".$city."\">".$myrow['city_lab']."</label><br>";
                                                else
                                                        $state_shown_vals.= "<input type=\"checkbox\" class=\"chbx \" name=\"partner_city_displaying_arr[]\" id=\"partner_city_displaying_".$city."\" value=".$city."><label id=\"partner_city_displaying_label_".$city."\">".$myrow['city_lab']."</label><br>";
                                        }

				}
				$city_lab=str_replace(" ",":",$myrow['city_lab']);
                                if($myrow['TYPE']!="STATE")
                                        $value.="#".$city_lab."|".$myrow['city'];
                                else
                                        $stateValue.="#".$city_lab."|".$myrow['city'];
                        }
			else
			{ 
                                if($stateValue && $country_temp=="51"){
                             $value=   "51".$stateValue.substr($value,2);
                             $stateValue="";
                        }
				$hidden_vals.=" <input type=\"checkbox\" value=".$value." name=\"partner_country_arr[]\" id=\"partner_country_".$value."\"> <label id=\"partner_country_label_".$value."\">".$label."</label><br>";
				$val="'".$country_temp."'";
	                        if(in_array($val,$val_array))
	                        { 
	                                if($checked)
	                                        $checked.=",'".$value."'";
	                                else
	                                        $checked.="'".$value."'";
	                        }
	                        else
				{
					if($from_edit)
		                		$shown_vals.= "<input type=\"checkbox\" onclick=\"add_checkboxes(this); remove_doesnt_matter_conflict(this); \"  class=\"chbx \" name=\"partner_country_displaying_arr[]\" id=\"partner_country_displaying_".$value."\" value=".$value."><label id=\"partner_country_displaying_label_".$value."\">".$label."</label><br>";
					else
		                		$shown_vals.= "<input type=\"checkbox\" class=\"chbx \" name=\"partner_country_displaying_arr[]\" id=\"partner_country_displaying_".$value."\" value=".$value."><label id=\"partner_country_displaying_label_".$value."\">".$label."</label><br>";
				}
				$label=$myrow['country_lab'];
				$value=$myrow['country'];
                                $country_temp=$value;
				$city_lab=str_replace(" ",":",$myrow['city_lab']);
                                if($myrow['TYPE']!="STATE")
                                        $value.="#".$city_lab."|".$myrow['city'];
                                else
                                        $stateValue.="#".$city_lab."|".$myrow['city'];
                               
			}
		}
		$val="'136'";
		$value='136';
		$label='Others';
		$hidden_vals.=" <input type=\"checkbox\" value=".$value." name=\"partner_country_arr[]\" id=\"partner_country_".$value."\"> <label id=\"partner_country_label_".$value."\">".$label."</label><br>";
		if(in_array($val,$val_array))
		{
			if($checked)
                        	$checked.=",'".$value."'";
                        else
                                $checked.="'".$value."'";
		}
		else
                { 
	                if($from_edit)
                                  $shown_vals.= "<input type=\"checkbox\" onclick=\"add_checkboxes(this); remove_doesnt_matter_conflict(this); \"  class=\"chbx \" name=\"partner_country_displaying_arr[]\" id=\"partner_country_displaying_".$value."\" value=".$value."><label id=\"partner_country_displaying_label_".$value."\">".$label."</label><br>";
                         else
                                  $shown_vals.= "<input type=\"checkbox\" class=\"chbx \" name=\"partner_country_displaying_arr[]\" id=\"partner_country_displaying_".$value."\" value=".$value."><label id=\"partner_country_displaying_label_".$value."\">".$label."</label><br>";
                
                }

		if(strstr($checked,"'51#"))
		{
                	$city_hidden_vals=" <input type=\"checkbox\" value=\"NCR\"  name=\"partner_city_arr[]\" id=\"partner_city_NCR\"> <label id=\"partner_city_label_NCR\">Delhi/NCR</label><br>".$city_hidden_vals;

		}	
                //if($myrow['country_lab']=="India")
                       // {print_r($shown_vals);die;}
		$smarty->assign("hidden_city",$city_hidden_vals);
                $smarty->assign("shown_city",$city_shown_vals);
                $smarty->assign("hidden_state",$state_hidden_vals);
                $smarty->assign("shown_state",$state_shown_vals);
		$smarty->assign("checked_country",$checked);
		$smarty->assign("hidden_country",$hidden_vals);
                $smarty->assign("shown_country",$shown_vals);
	}
	elseif($field=="Education")
	{
		$val_array=explode(',',$values);

		$sql= "SELECT SQL_CACHE el.VALUE AS VALUE, el.LABEL AS LABEL, el.GROUPING AS GROUPING FROM EDUCATION_LEVEL_NEW el, EDUCATION_GROUPING eg WHERE el.GROUPING = eg.VALUE ORDER BY eg.SORTBY,el.SORTBY";
		$res= mysql_query_decide($sql) or die(mysql_error_js());
		
		$shown_vals = "";
		$hidden_vals="";
		$i=0;
		$first_group = true;

		while($row= mysql_fetch_array($res))
		{
			$group = $row['GROUPING'];

			if(isset($group_old) && $group_old != $group)
			{
				$tempString= $tempString."<input type=\"checkbox\" name=\"partner_education_arr[]\" id=\"partner_education_".rtrim($group_values[$i],"|#|")."\" value=\"".rtrim($group_values[$i],"|#|")."\"><label id=\"partner_education_label_".rtrim($group_values[$i],"|#|")."\">".$EDUCATION_GROUPING_DROP[$group_old]."</label><br>".$hidden_vals;
                        	$i++;
				$hidden_vals="";
			}
                        $group_values[$i] .= $row['VALUE']."|#|";
			
			if($group_old != $group)
                        {
				$group_count++;
                                if($first_group)
                                {
                                        $optg=$EDUCATION_GROUPING_DROP[$group];
					$shown_vals.=" <span style=\"color:#0a89fe;\">".$optg."</span><div class=\"clear\" style=\"line-height:5px;\"> &nbsp;</div>";
					$first_group= false;
                                }
                                elseif($group == count($EDUCATION_GROUPING_DROP))
                                {
					$shown_vals.=" </br><span style=\"color:#0a89fe;\">&nbsp; </span><div class=\"clear\" style=\"line-height:5px;\"> &nbsp;</div>";
                                        $optg='';
                                }
                                else
                                {
                                        $optg=$EDUCATION_GROUPING_DROP[$group];
					$shown_vals.=" </br><span style=\"color:#0a89fe;\">".$optg."</span><div class=\"clear\" style=\"line-height:5px;\"> &nbsp;</div>";
                                }
			}
			$hidden_vals.=" <input type=\"checkbox\" value=".$row["VALUE"]." name=\"partner_education_arr[]\" id=\"partner_education_".$row["VALUE"]."\"> <label id=\"partner_education_label_".$row["VALUE"]."\">".$row["LABEL"]."</label><br>";
                        $val="'".$row['VALUE']."'";
			
			if(in_array($val,$val_array))
                        {
                                if($checked)
                                        $checked.=",".$val;
                                else
                                        $checked.=$val;
                        }
                        else
                        {
                                $shown_vals.= "<input type=\"checkbox\" class=\"chbx \" name=\"partner_education_displaying_arr[]\" id=\"partner_education_displaying_".$row["VALUE"]."\" value=".$row["VALUE"]."><label id=\"partner_education_displaying_label_".$row["VALUE"]."\">".$row["LABEL"]."</label><br>";
                        }
                        
			$group_old = $group;
		}
		$tempString= $tempString."<input type=\"checkbox\" name=\"partner_education_arr[]\" id=\"partner_education_".$group_values[$i]."\" value=\"".$group_values[$i]."\"><label id=\"partner_education_label_".$group_values[$i]."\">&nbsp;</label><br>".$hidden_vals;
		$hidden_vals = $tempString;
		unset($tempString);
		
		$smarty->assign("checked_edu",$checked);
		$smarty->assign("hidden_education",$hidden_vals);
                $smarty->assign("shown_education",$shown_vals);
	}
	elseif($field == "Occupation")
        {
                $val_array=explode(',',$values);
                $sql = "select SQL_CACHE VALUE, LABEL from OCCUPATION order by SORTBY";
                $res = mysql_query_decide($sql) or logError("error",$sql);
                while($myrow = mysql_fetch_array($res))
                {
			$val="'".$myrow['VALUE']."'";
                        if(in_array($val,$val_array))
                        {
                                $hidden_vals.=" <input type=\"checkbox\" value=".$myrow['VALUE']." name=\"partner_occupation_arr[]\" id=\"partner_occupation_".$myrow['VALUE']."\"> <label id=\"partner_occupation_label_".$myrow['VALUE']."\">".$myrow['LABEL']."</label><br>";
                                if($checked)
                                	$checked.=",".$val;
                                else
                                        $checked.=$val;

                        }
                        else
                        {
                                $hidden_vals.=" <input type=\"checkbox\" value=".$myrow['VALUE']." name=\"partner_occupation_arr[]\" id=\"partner_occupation_".$myrow['VALUE']."\"> <label id=\"partner_occupation_label_".$myrow['VALUE']."\">".$myrow['LABEL']."</label><br>";
        			$shown_vals.= "<input type=\"checkbox\" class=\"chbx \" name=\"partner_occupation_displaying_arr[]\" id=\"partner_occupation_displaying_".$myrow['VALUE']."\" value=".$myrow['VALUE']."><label id=\"partner_occupation_displaying_label_".$myrow['VALUE']."\">".$myrow['LABEL']."</label><br>";
                        }
                }
		$smarty->assign("checked_occup",$checked);
                $smarty->assign("hidden_occup",$hidden_vals);
                $smarty->assign("shown_occup",$shown_vals);
        }
	elseif($field=='Income')
	{
		$val_array=explode(',',$values);
		$sql = "select SQL_CACHE VALUE, LABEL from INCOME order by SORTBY";
		$res = mysql_query_decide($sql) or logError("error",$sql);
		while($myrow = mysql_fetch_array($res))
		{
	        	$hidden_vals.=" <input type='checkbox' value=".$myrow['VALUE']." name='partner_income_arr[]' id='partner_income_".$myrow['VALUE']."'> <label id='partner_income_label_".$myrow['VALUE']."'>".$myrow['LABEL']."</label><br>";
			$val="'".$myrow['VALUE']."'";
                        if(in_array($val,$val_array))
                        {
				 if($checked)
                                        $checked.=",".$val;
                                else
                                        $checked.=$val;
			}
			else
		        	$shown_vals.= "<input type=\"checkbox\" class=\"chbx \" name=\"partner_income_displaying_arr[]\" id=\"partner_income_displaying_".$myrow['VALUE']."\" value=".$myrow['VALUE']."><label id=\"partner_income_displaying_label_".$myrow['VALUE']."\">".$myrow['LABEL']."</label><br>";
		}
		$smarty->assign("checked_income",$checked);
		$smarty->assign("hidden_income",$hidden_vals);
		$smarty->assign("shown_income",$shown_vals);
	}
        elseif($field=='HIV')
	{
                if($values=="Y")
                        $HIV["yes"] = 1;
                else if($values=="N")
                        $HIV["no"] = 1;
                else 
                        $HIV["DM"] = 1;
                
		$smarty->assign("HIV",$HIV);
	}
        elseif($field=='liveWithParents')
	{
                if($values=="Y")
                        $liveWithParents["yes"] = 1;
                else if($values=="N")
                        $liveWithParents["no"] = 1;
                else 
                        $liveWithParents["DM"] = 1;
                $smarty->assign("liveWithParents",$liveWithParents);
	}

}

function fill_MSgadget_reg($field,$values,$flag='',$from_edit='')
{
        global $smarty;
	include("arrays.php");
        if($field=='Income')
        {
                $val_array=explode(',',$values);
                $sql = "select SQL_CACHE VALUE, LABEL from INCOME order by SORTBY";
                $res = mysql_query_decide($sql) or logError("error",$sql);
                while($myrow = mysql_fetch_array($res))
                {
                        $hidden_vals.=" <input type='checkbox' value=".$myrow['VALUE']." name='partner_income_arr[]' id='partner_income_".$myrow['VALUE']."'> <label id='partner_income_label_".$myrow['VALUE']."'>".$myrow['LABEL']."</label><br>";
                        $val="'".$myrow['VALUE']."'";
                        if(in_array($val,$val_array))
                        {
                                 if($checked)
                                        $checked.=",".$val;
                                else
                                        $checked.=$val;
                        }
                        else
                                $shown_vals.= "<input type=\"checkbox\" class=\"chbx \" name=\"partner_income_displaying_arr[]\" id=\"partner_income_displaying_".$myrow['VALUE']."\" value=".$myrow['VALUE']."><label id=\"partner_income_displaying_label_".$myrow['VALUE']."\">".$myrow['LABEL']."</label><br>";
                }
                $smarty->assign("checked_income",$checked);
                $smarty->assign("hidden_income",$hidden_vals);
                $smarty->assign("shown_income",$shown_vals);
        }
	
	elseif($field=="Religion")
	{
		$sql = "SELECT SQL_CACHE VALUE,LABEL FROM newjs.RELIGION ORDER BY SORTBY";
        	$res = mysql_query_decide($sql) or logError("error",$sql);
		$rel_arr= Array();
		$i=1;	
        	while($row = mysql_fetch_array($res))
        	{
	                $label = $row['LABEL'];
	                $religion_value = $row['VALUE'];
			$caste_str='';
	                $sql_caste = "SELECT SQL_CACHE VALUE,LABEL from CASTE WHERE PARENT='$religion_value' AND VALUE NOT IN (242,243,244,245,246) ORDER BY SORTBY";
	                $res_caste = mysql_query_decide($sql_caste) or logError("error",$sql);
	                while($row_caste = mysql_fetch_array($res_caste))
	                {
			
				$caste_value = $row_caste['VALUE'];

	                        $caste_label_arr = explode(": ",$row_caste['LABEL']);
	                        if($caste_label_arr[1])
	                        $caste_label = $caste_label_arr[1];
	                        else
	                        $caste_label = $caste_label_arr[0];
	                        $caste_label=str_replace(" ",":",$caste_label);
	                        $caste_str .= $caste_value."$".$caste_label."#";
        	        }
        	        $religion_str = $religion_value."|X|".$caste_str;
			if($religion_value==$values)
			{
				$religion_arr[0]=$religion_str;
				$label_arr[0]=$label;
			}
			$religion_arr[$i]=$religion_str;
			$label_arr[$i]=$label;
			$i++;
		}

		$religion_str=$religion_arr[0];
		$label=$label_arr[0];
		$priority_vals= "<input type=\"checkbox\" class=\"chbx \" name=\"partner_religion_displaying_arr[]\" id=\"partner_religion_displaying_".$religion_str."\" value=".$religion_str."><label id=\"partner_religion_displaying_label_".$religion_str."\">".$label_arr[0]."</label><br>";
		$smarty->assign('mapped_rel',$religion_str);
		$len=count($religion_arr);
		for($i=1;$i<$len;$i++)
		{
			$religion_str=$religion_arr[$i];
			$label=$label_arr[$i];
        	        $hidden_vals.=" <input type=\"checkbox\" value=".$religion_str." name=\"partner_religion_arr[]\" id=\"partner_religion_".$religion_str."\"> <label id=\"partner_religion_label_".$religion_str."\">".$label."</label><br>";
			$shown_vals.= "<input type=\"checkbox\" class=\"chbx \" name=\"partner_religion_displaying_arr[]\" id=\"partner_religion_displaying_".$religion_str."\" value=".$religion_str."><label id=\"partner_religion_displaying_label_".$religion_str."\">".$label."</label><br>";
		}
			$rel_flag=0;
               		unset($caste_str);
		$smarty->assign("muslim_caste",$checked_caste);
        	$smarty->assign("hidden_religion",$hidden_vals);
        	$smarty->assign("shown_religion",$shown_vals);
        	$smarty->assign("priority_religion",$priority_vals);
	}
	elseif($field=="Mtongue")
	{
		$sql = "SELECT SQL_CACHE VALUE, LABEL, SMALL_LABEL, REGION FROM MTONGUE WHERE REGION <>5 ORDER BY REGION DESC,SORTBY_NEW";
                $res = mysql_query_optimizer($sql) or logError("error",$sql);
                $hidden_vals.=" <input type=\"checkbox\" value=\"DM\" name=\"partner_mtongue_arr[]\" id=\"partner_mtongue_DM\"> <label id=\"partner_mtongue_label_DM\">All</label><br>";
                while($myrow = mysql_fetch_array($res))
                {
                        $mtongue_region=$myrow['REGION'];
                        if($mtongue_region!=$mtongue_region_old)
                        {
                        	if($mtongue_region==5)
                                {
                                                //2339:lavesh -- All Hindi will come below North Option.
                                       $flag_allhindi.= "<option value=\"10,19,33,7,28,13,41\"  >All Hindi</option>\n"; 
                                       $hidden_vals_hindi.=" <input type=\"checkbox\" value=\"10,19,33,7,28,13,41\" name=\"partner_mtongue_arr[]\" id=\"partner_mtongue_10,19,33,7,28,13,41\"> <label id=\"partner_mtongue_label_10,19,33,7,28,13,41\">All Hindi</label><br>";
                                       $shown_vals_hindi.= "<input type=\"checkbox\" class=\"chbx \" name=\"partner_mtongue_displaying_arr[]\" id=\"partner_mtongue_displaying_10,19,33,7,28,13,41\" value=\"10,19,33,7,28,13,41\"><label id=\"partner_mtongue_displaying_label_10,19,33,7,28,13,41`\">All Hindi</label><br>";
                                 }
                                 elseif($mtongue_region==4)
                                 {
                        	        $shown_vals.=" <span style=\"color:#0a89fe;\">North</span><div class=\"clear\" style=\"line-height:5px;\"> &nbsp;</div>";
					$hidden_vals.= "<input type=\"checkbox\" name=\"partner_mtongue_arr[]\" id=\"partner_mtongue_10|#|41|#|33|#|27|#|7|#|28|#|13|#|14|#|15|#|70|#|36|#|10,19,33,7,28,13,41\" value=\"10|#|41|#|33|#|27|#|7|#|28|#|13|#|14|#|15|#|70|#|36|#|10,19,33,7,28,13,41\"><label id=\"partner_mtongue_label_10|#|41|#|33|#|27|#|7|#|28|#|13|#|14|#|15|#|70|#|36|#|10,19,33,7,28,13,41\">North</label><br>";
					 $flag_allhindi.= "<option value=\"10,19,33,7,28,13,41\"  >All Hindi</option>\n";
                                       $hidden_vals_hindi.=" <input type=\"checkbox\" value=\"10,19,33,7,28,13,41\" name=\"partner_mtongue_arr[]\" id=\"partner_mtongue_10,19,33,7,28,13,41\"> <label id=\"partner_mtongue_label_10,19,33,7,28,13,41\">All Hindi</label><br>";
                                       $shown_vals_hindi.= "<input type=\"checkbox\" class=\"chbx \" name=\"partner_mtongue_displaying_arr[]\" id=\"partner_mtongue_displaying_10,19,33,7,28,13,41\" value=\"10,19,33,7,28,13,41\"><label id=\"partner_mtongue_displaying_label_10,19,33,7,28,13,41`\">All Hindi</label><br>";
					if($flag_allhindi)
                                        {
                                        	$hidden_vals.=$hidden_vals_hindi;
                                        	$shown_vals.=$shown_vals_hindi;
                                        }
				}
				elseif($mtongue_region==3)
                                {
                                     $shown_vals.=" <span style=\"color:#0a89fe;\">West</span><div class=\"clear\" style=\"line-height:5px;\"> &nbsp;</div>";
                                                $hidden_vals.= "<input type=\"checkbox\" class=\"chbx \" name=\"partner_mtongue_arr[]\" id=\"partner_mtongue_20|#|12|#|19|#|34|#|30|#|9\" value=\"20|#|12|#|19|#|34|#|30|#|9\"><label id=\"partner_mtongue_label_20|#|12|#|19|#|34|#|30|#|9\">West</label><br>";
                                }
                                elseif($mtongue_region==2)
                                {
                                          $shown_vals.=" <span style=\"color:#0a89fe;\">South</span><div class=\"clear\" style=\"line-height:5px;\"> &nbsp;</div>";
                                                $hidden_vals.= "<input type=\"checkbox\" class=\"chbx \" name=\"partner_mtongue_arr[]\" id=\"partner_mtongue_31|#|3|#|16|#|17|#|2|#|18|#|35|#|71\" value=\"31|#|3|#|16|#|17|#|2|#|18|#|35|#|71\"><label id=\"partner_mtongue_label_31|#|3|#|16|#|17|#|2|#|18|#|35|#|71\">South</label><br>";
                                }
                                elseif($mtongue_region==1)
                                {
                                                $shown_vals.=" <span style=\"color:#0a89fe;\">East</span><div class=\"clear\" style=\"line-height:5px;\"> &nbsp;</div>";
                                                $hidden_vals.= "<input type=\"checkbox\" class=\"chbx \" name=\"partner_mtongue_arr[]\" id=\"partner_mtongue_6|#|25|#|5|#|4|#|21|#|22|#|23|#|24|#|29|#|32\" value=\"6|#|25|#|5|#|4|#|21|#|22|#|23|#|24|#|29|#|32\"><label id=\"partner_mtongue_label_6|#|25|#|5|#|4|#|21|#|22|#|23|#|24|#|29|#|32\">East</label><br>";
                                }
                                $mtongue_region_old=$mtongue_region;
			}
			if($myrow['VALUE']=='30' && $mtongue_region==4)
                                $myrow['VALUE']='70';
			$val=$myrow['VALUE'];
			if($myrow['VALUE']=='19' && $mtongue_region==4)
				$val=41;
			if($myrow['VALUE']=='36' && $mtongue_region==2)
				$val=71;
			if($val==$values)
			{
				$allHindiStr = '10,19,33,7,28,13,41';
				$allHindi = explode(',','10,19,33,7,28,13,41');
				if(in_array($values,$allHindi))
				{
					$priLabel = "All Hindi";
					$values = $allHindiStr;
				}
				else
					$priLabel = $myrow['LABEL'];
			}
			$mtongue_arr[]=$val;
			$label_arr[$val]=$myrow['LABEL'];
			$hidden_vals.=" <input type=\"checkbox\" value=".$val." name=\"partner_mtongue_arr[]\" id=\"partner_mtongue_".$val."\"> <label id=\"partner_mtongue_label_".$val."\">".$myrow['LABEL']."</label><br>";
                        $shown_vals.= "<input type=\"checkbox\" class=\"chbx \" name=\"partner_mtongue_displaying_arr[]\" id=\"partner_mtongue_displaying_".$val."\" value=".$val."><label id=\"partner_mtongue_displaying_label_".$val."\">".$myrow['LABEL']."</label><br>";
		}
		$priority_vals.= "<input type=\"checkbox\" class=\"chbx \" name=\"partner_mtongue_displaying_arr[]\" id=\"partner_mtongue_displaying_".$values."\" value=".$values."><label id=\"partner_mtongue_displaying_label_".$values."\">".$priLabel."</label><br>";
                $smarty->assign("hidden_mton",$hidden_vals);
                $smarty->assign("shown_mton",$shown_vals);
                $smarty->assign("priority_mton",$priority_vals);
	}	
}
?>
