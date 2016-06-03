<?php

function bread_crumb($searchid="",$checksum="",$currentusername="",$crmback="",$inf_checksum="",$cid="",$label_select_no)
{
	include_once "connect.inc";
	include_once "arrays.php";
        include_once "search.inc";
        $db=connect_db();

		global $smarty;

		$smarty->assign("label_select_no",$label_select_no);

		$sql="select SEARCH_TYPE,GENDER,CASTE,MTONGUE,LAGE,HAGE,WITHPHOTO,COUNTRY_RES,CITY_RES,KEYWORD, PHOTOBROWSE,ONLINE,FRESHNESS,MSTATUS,LHEIGHT,HHEIGHT,INCOME,SUBSCRIPTION,KEYWORD_TYPE,OCCUPATION,DIET,MANGLIK,EDU_LEVEL_NEW,EDU_LEVEL,RELATION,CHILDREN,BTYPE,COMPLEXION,SMOKE,DRINK,HANDICAPPED,RES_STATUS,CASTE_DISPLAY,NEWSEARCH_CLUSTERING,BREAD_CRUMB,INCOME_CLUSTER_MAPPING,OCCUPATION_CLUSTER_MAPPING,EDUCATION_CLUSTER_MAPPING from newjs.SEARCHQUERY where ID='$searchid'";
                $result=mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
                                                                                                                             
                $searchrow=mysql_fetch_array($result);
                //section added by Gaurav on 2 Feb 2006 for NRI's,COSMPOLITAN search
                $smarty->assign("NEWSEARCH_CLUSTERING",$searchrow['NEWSEARCH_CLUSTERING']);
                //end of section added by Gaurav on 2 Feb 2006 for NRI's,COSMPOLITAN search
                                                                                                                             
                //section added by Gaurav on 25 May 2006 for online search in clustering so that when CHATNOW is clicked in top band, the members online get preselected in clutering
                if($searchrow['ONLINE']==1 && $searchrow['STYPE']!='J')
                        $smarty->assign("NEWSEARCH_CLUSTERING",'O');
                //end of section added by Gaurav on 25 May 2006 for online search in clustering so that when CHATNOW is clicked in top band, the members online get preselected in clutering
	
		//section added by Gaurav on 6 Nov 2006
		$smarty->assign("lage",$searchrow['LAGE']);
                $smarty->assign("hage",$searchrow['HAGE']);
		$smarty->assign("lheight",$searchrow['LHEIGHT']);
                $smarty->assign("hheight",$searchrow['HHEIGHT']);
		$smarty->assign("FRESHNESS",$searchrow['FRESHNESS']);
		$smarty->assign("hp_mstatus",$searchrow['MSTATUS']);	
		$smarty->assign("Photos",$searchrow['WITHPHOTO']);	
		$smarty->assign("searchonline",$searchrow['ONLINE']);
		
		//end of section added by Gaurav on 6 Nov 2006
	
		$smarty->assign("BREAD_CRUMB",$searchrow['BREAD_CRUMB']);
                $smarty->assign("CASTE_DISPLAY",$searchrow['CASTE_DISPLAY']);
                $smarty->assign("MANGLIK",$searchrow['MANGLIK']);
                $smarty->assign("RELATION_VAL",$searchrow['RELATION']);
                $smarty->assign("RSTATUS",$searchrow['RES_STATUS']);
                $smarty->assign("DIET",$searchrow['DIET']);
                $smarty->assign("CHILDREN",$searchrow['CHILDREN']);
                $smarty->assign("BTYPE",$searchrow['BTYPE']);
                $smarty->assign("COMPLEXION",$searchrow['COMPLEXION']);
                $smarty->assign("SMOKE",$searchrow['SMOKE']);
                $smarty->assign("DRINK",$searchrow['DRINK']);
                $smarty->assign("HANDICAPPED",$searchrow['HANDICAPPED']);
		//Added By lavesh for correct search on action on clear
		//$smarty->assign("bread_crumb_hidden",$searchrow['BREAD_CRUMB']);
		$smarty->assign("Mtongue_val",$searchrow['MTONGUE']);
		$smarty->assign("Caste_val",$searchrow['CASTE']);
		$smarty->assign("City_res_val",$searchrow['CITY_RES']);
		$smarty->assign("Country_res_val",$searchrow['COUNTRY_RES']);
		//Ends Here.

		//Modified By lavesh
		$smarty->assign("Gender",$searchrow['GENDER']);

                if($searchrow['OCCUPATION_CLUSTER_MAPPING'])
                {
                        $smarty->assign("OCCUPATION",$searchrow['OCCUPATION_CLUSTER_MAPPING']);
                }                 
                                                                                                                             
                if($searchrow['EDUCATION_CLUSTER_MAPPING'])
                {
                        $smarty->assign("EDU_LEVEL_NEW",$searchrow['EDUCATION_CLUSTER_MAPPING']);
                }

                if($searchrow['INCOME_CLUSTER_MAPPING'])
                {
                        $smarty->assign("INCOME",$searchrow['INCOME_CLUSTER_MAPPING']);
                }
                                                                                                                             
                //Modification By Lavesh ends here.
		
		$bread_crumb_str=$searchrow['BREAD_CRUMB'];
		$bread_crumb_arr=explode(",",$bread_crumb_str);

		$key_income=array_search('income',$bread_crumb_arr);
		$key_occupation=array_search('occupation',$bread_crumb_arr);
		$key_education=array_search('edu',$bread_crumb_arr);
		$key_relation=array_search('relation',$bread_crumb_arr);
		$key_diet=array_search('diet',$bread_crumb_arr);
		$key_manglik=array_search('manglik',$bread_crumb_arr);
		$key_special=array_search('special',$bread_crumb_arr);

		if($searchrow['INCOME'])
		{
			//Modified By lavesh on 4 sep 2006.To a)Prevent calling of function in a loop.b)display New label from INCOME_CLUSTER instead of INCOME.
			if($label_select_no%2==0)
			{
				$income_label_clustering=cluster_label_select_1("INCOME_CLUSTER",$searchrow['INCOME']);	
				$income_label_clustering=$income_label_clustering[0];
			}
			else
			{
				$cluster_income=$searchrow['INCOME_CLUSTER_MAPPING'];
				$income_label_clustering=cluster_label_select("INCOME_CLUSTER",$cluster_income);
			}
			//Modification Ends Here.
			
			if($income_label_clustering)
			{
				$bread_crumb_final_arr[$key_income]=$income_label_clustering." <a href=\"#\" onclick=\"search_clustering('income','All');\">[Clear]</a> ";// &gt";
				$smarty->assign("remove_income",$income_label_clustering);		
				//added by lavesh
				$remove_income=$income_label_clustering."&nbsp;<a href=\"#\" onclick=\"search_clustering('income','All');\">[Show All]</a> ";
				$smarty->assign("remove_income",$remove_income);
				//ends here.
			}
			
                }
		if($searchrow['OCCUPATION'])
                {
			//Modified By lavesh of same above reason.
			if($label_select_no%3==0)
			{
				$occupation_label_clustering=cluster_label_select_1("OCCUPATION_CLUSTER",$searchrow['OCCUPATION']);
				$occupation_label_clustering=$occupation_label_clustering[0];
                        }
			else
			{
				$cluster_occupation=$searchrow['OCCUPATION_CLUSTER_MAPPING'];
				$occupation_label_clustering=cluster_label_select("OCCUPATION_CLUSTER",$cluster_occupation);
			}
			//Modification Ends Here.
			
			if($occupation_label_clustering)
			{
				$bread_crumb_final_arr[$key_occupation]=$occupation_label_clustering." <a href=\"#\" onclick=\"search_clustering('occupation','All');\">[Clear]</a> ";// &gt";
				//added by lavesh
				$remove_occ=$occupation_label_clustering."&nbsp;<a href=\"#\" onclick=\"search_clustering('occupation','All');\">[Show All]</a> ";
				$smarty->assign("remove_occ",$remove_occ);
				//Ends Here.

			}
                }
                if($searchrow['EDU_LEVEL_NEW'])
                {
			//Modified By lavesh of same above reason.
			if($label_select_no%5==0)
			{
				$education_label_clustering=cluster_label_select_1("EDUCATION_LEVEL_NEW_CLUSTER",$searchrow['EDU_LEVEL_NEW']);
				$education_label_clustering=$education_label_clustering[0];
			}
			else
			{	
				$cluster_education=$searchrow['EDUCATION_CLUSTER_MAPPING'];
				$education_label_clustering=cluster_label_select("EDUCATION_LEVEL_NEW_CLUSTER",$cluster_education);
			}
			//Modification Ends Here.
			if($education_label_clustering)
			{
				$bread_crumb_final_arr[$key_education]=$education_label_clustering." <a href=\"#\" onclick=\"search_clustering('edu','All');\">[Clear]</a> ";// &gt";
				//added by lavesh
				$remove_edu=$education_label_clustering."&nbsp;<a href=\"#\" onclick=\"search_clustering('edu','All');\">[Show All]</a> ";
				$smarty->assign("remove_edu",$remove_edu);
				//Ends here.
			}
                }
		if($searchrow['RELATION'])
		{
			if($searchrow['RELATION']=="1")
				$relation_label_clustering='Self';
			elseif($searchrow['RELATION']=="2,3")
				$relation_label_clustering='Parents';
			
			if($relation_label_clustering)
			{
				$bread_crumb_final_arr[$key_relation]=$relation_label_clustering." <a href=\"#\" onclick=\"search_clustering('relation','All');\">[Clear]</a> ";
				$remove_special[$key_relation]=$relation_label_clustering;//added by lavesh
			}
		}
		if($searchrow['DIET'])
		{
			if($searchrow['DIET']=="V")
				$diet_label_clustering="Vegetarian";
			elseif($searchrow['DIET']=="N")
                                $diet_label_clustering="Non-veg";
			elseif($searchrow['DIET']=="J")
                                $diet_label_clustering="Jain";

			if($diet_label_clustering)
			{
				$bread_crumb_final_arr[$key_diet]=$diet_label_clustering." <a href=\"#\" onclick=\"search_clustering('diet','All');\">[Clear]</a> ";
				$remove_special[$key_diet]=$diet_label_clustering;//added by lavesh
			}
		}
		if($searchrow['MANGLIK'])
		{
			if($searchrow['MANGLIK']=="M")
				$manglik_label_clustering="Manglik";
			elseif($searchrow['MANGLIK']=="N")
                                $manglik_label_clustering="Non Manglik";
			elseif($searchrow['MANGLIK']=="D")
                                $manglik_label_clustering="Not Applicable/Don't Know";

			if($manglik_label_clustering)	
			{
				$bread_crumb_final_arr[$key_manglik]=$manglik_label_clustering." <a href=\"#\" onclick=\"search_clustering('manglik','All');\">[Clear]</a> ";
				$remove_special[$key_manglik]=$manglik_label_clustering;//added by lavesh
			}
		}
		if($searchrow['NEWSEARCH_CLUSTERING'])
		{
			if($searchrow['NEWSEARCH_CLUSTERING']=="N")
				$special_label_clustering="NRI";
			elseif($searchrow['NEWSEARCH_CLUSTERING']=="R")
				$special_label_clustering="Cosmopolitan";
			elseif($searchrow['NEWSEARCH_CLUSTERING']=="E")
				$special_label_clustering="eValuePack Members";
			elseif($searchrow['NEWSEARCH_CLUSTERING']=="O")
				$special_label_clustering="Members Online";
			
			if($special_label_clustering)
			{
				$bread_crumb_final_arr[$key_special]=$special_label_clustering." <a href=\"#\" onclick=\"search_clustering('newsearch_clustering','All');\">[Clear]</a> ";
				$remove_special[$key_special]=$special_label_clustering;//added by lavesh
			}
		}
		
		if($bread_crumb_final_arr)
		{
			ksort($bread_crumb_final_arr);
			//print_r($bread_crumb_final_arr);
		
			$bread_crumb_final_str=implode(" > ",$bread_crumb_final_arr);
		}
		if(is_array($remove_special))
		{
			ksort($remove_special);
			$remove_special=implode(",",$remove_special);
			$remove_special=$remove_special."&nbsp;<a href=\"#\" onclick=\"search_clustering('Superclear','All');\">[Show All]</a> ";
			$smarty->assign("remove_special",$remove_special);
		}
		/*// flag 'only_when_cluster' used to check 'Search Results' label in breadcrumb.htm 
		if($bread_crumb_final_str)
			$smarty->assign("only_when_cluster","Y");
		*/
		$smarty->assign("BREAD_CRUMB_FINAL_STR",$bread_crumb_final_str);
		$smarty->assign("BREADCRUMB",$smarty->fetch("breadcrumb.htm"));
}

//Added By lavesh on 3 sep 2006.
//It selects Label from CLUSTER TABLES to display in breadcrumb.
function cluster_label_select($table,$value)
{
	$value_temp=explode(',',$value);
	sort($value_temp);
        for($i=0;$i<count($value_temp);$i++)
        {
                $sql="SELECT LABEL FROM newjs.$table WHERE VALUE='$value_temp[$i]' AND VISIBLE<>'N'";
                $result=mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
                while($row=mysql_fetch_array($result))
                {
                        $val.=$row['LABEL'].' , ';
                }
        }
        $val=rtrim($val,', ');
        return($val);
}

function cluster_label_select_1($table,$value)
{
	$sql = "select SQL_CACHE LABEL from $table WHERE OLD_VALUE='$value'AND VISIBLE='N'";
	$res = mysql_query_decide($sql) or logError("error",$sql) ;
	$myrow= mysql_fetch_row($res);
	return($myrow);
}

//Created By lavesh.
function cluster_display_select($table,$value)
{
	$sql="SELECT VALUE FROM newjs.$table WHERE OLD_VALUE='$value' AND VISIBLE='N'";
	$result=mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
	$row=mysql_fetch_array($result);
	$val=$row['VALUE'];
	return($val);
}

?>
