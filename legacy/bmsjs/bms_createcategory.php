<?PHP

/************************************************************************************************************************
*    FILENAME           : bms_createcategory.php
*    DESCRIPTION        : Create/Edit categories.
			  done for 99acres.
*    CREATED BY         : lavesh
*    Live On            : 20 july 2007
***********************************************************************************************************************/

include ("./includes/bms_connect.php");
$ip=FetchClientIP();
$data=authenticatedBms($id,$ip,"banadmin");
global $dbbms;

if($data)
{
	$id=$data["ID"];
	$bmsheader=fetchHeaderBms($data);
	$bmsfooter=fetchFooterBms();
	$smarty->assign("bmsheader",$bmsheader);
	$smarty->assign("bmsfooter",$bmsfooter);

	if($from_display)
	{
		if($submit3)
		{		
			//Subcategory is created here.
			for($k=0;$k<=count($categoryarray);$k++)
			{
				if(is_numeric($categoryarray[$k]))
				{
					$final_array[]=$categoryarray[$k];//non-subcategory selected
				}
			}
			if(is_array($final_array))
				$cat_list=implode("','",$final_array);

			$sql="INSERT IGNORE INTO bms2.CATEGORY_99(Name,Criteria,ParentCat,Description,ModDate) VALUES('$cat_name','$criteria','$parent_value','". addslashes(stripslashes($cat_details))."',now())";
			mysql_query($sql,$dbbms) or die(mysql_error().$sql);
			$val=mysql_insert_id();

			if($val && $cat_list)
			{
				$sql="UPDATE bms2.$criteria SET BmsCategory=$val WHERE VALUE IN ('$cat_list')";
				mysql_query($sql,$dbbms) or die(mysql_error().$sql);
			}
			display_list($criteria);
		}
		elseif(!$submit2)
		{
			//displaying form for creating sub-category.
			$smarty->assign("subcategory",1);					
			$smarty->assign("parent_category",$category);
			$smarty->assign("criteria",$criteria);	
			$sql="SELECT Value FROM bms2.CATEGORY_99 WHERE Name='$category'";
			$res=mysql_query($sql,$dbbms) or die(mysql_error().$sql);
			$row=mysql_fetch_array($res);
			if($row["Value"]>0)
			{
				$value=$row["Value"];
				$smarty->assign("parent_value",$value);
				$i=0;
				$sql="SELECT DISTINCT(LABEL),VALUE FROM bms2.$criteria WHERE BmsCategory='$value' ORDER BY SORTBY";
				$res=mysql_query($sql,$dbbms) or die(mysql_error().$sql);
				while($row=mysql_fetch_array($res))
				{
					$my_arr[$i]["label"]=$row["LABEL"];	
					$my_arr[$i]["value"]=$row["VALUE"];	
					$i=$i+1;
				}
				$smarty->assign("my_arr",$my_arr);
				$smarty->display("./$_TPLPATH/bms_createcategory1.htm");
				exit;
			}
		}
	}

	if($submit3)
	{
		if(is_array($categoryarray))
			$categoryarray=array_filter($categoryarray);//Removing blank values

		for($k=0;$k<=count($categoryarray);$k++)
		{
			if(is_numeric($categoryarray[$k]))
			{
				$final_array[]=$categoryarray[$k];//non-subcategory selected
			}
			elseif($categoryarray[$k])
			{
				$subcategory_array[]=$categoryarray[$k];//subcategory selected	
			}
		}

		if(is_array($final_array))
			$cat_list=implode("','",$final_array);

		if(is_array($subcategory_array))
			$sub_list=implode("','",$subcategory_array);

		if($category=='new')
		{
			$sql="INSERT IGNORE INTO bms2.CATEGORY_99(Name,Criteria,Description,ModDate) VALUES('$cat_name','$criteria','". addslashes(stripslashes($cat_details))."',now())";
			mysql_query($sql,$dbbms) or die(mysql_error().$sql);
			$val=mysql_insert_id();

			$sql="UPDATE  bms2.CATEGORY_99 SET ParentCat='$val' WHERE Name IN ('$sub_list')";
			mysql_query($sql,$dbbms) or die(mysql_error().$sql);
		}
		else
		{
			$sql="UPDATE bms2.CATEGORY_99 SET Description='". addslashes(stripslashes($cat_details))."', ModDate=now() where Name='$cat_name'";
			mysql_query($sql,$dbbms) or die(mysql_error().$sql);
			
			$sql="SELECT Value from bms2.CATEGORY_99 where Name='$cat_name'";
			$res=mysql_query($sql) or die(mysql_error().$sql);
			$row=mysql_fetch_array($res);	
			$val=$row["Value"];

			//all associate value is set to 0 first and then updated with new value.
			$sql="UPDATE bms2.$criteria SET BmsCategory=0 WHERE BmsCategory=$val";
			mysql_query($sql,$dbbms) or die(mysql_error().$sql);

			$sql="UPDATE  bms2.CATEGORY_99 SET ParentCat=0 WHERE ParentCat=$val";
			mysql_query($sql,$dbbms) or die(mysql_error().$sql);

			$sql="UPDATE  bms2.CATEGORY_99 SET ParentCat='$val' WHERE Name IN ('$sub_list')";
			mysql_query($sql,$dbbms) or die(mysql_error().$sql);

		}

		if($val)
		{
			$sql="UPDATE bms2.$criteria SET BmsCategory=$val WHERE VALUE IN ('$cat_list')";
			mysql_query($sql,$dbbms) or die(mysql_error().$sql);

			//update_banner_values($val,$criteria);
			display_list($criteria);
		}
		else
		{
			$smarty->assign("error",1);
			$smarty->assign("cat_name",$cat_name);				
			$smarty->assign("cat_detail",stripslashes($cat_details));			
			$submit2=1;
		}
	}

	if($submit2)
	//Displaying page for adding/editing Category.
	{
		if(!$from_display)
		{
			$crit_arr=explode("|X|",$criteria);
			$criteria=$crit_arr[0];
		}
		if($category=='default')
			display_list($criteria);

		//prefilling details when a category is edited.
		if($category!='new')
		{
			$sql="SELECT Name,Description FROM bms2.CATEGORY_99 WHERE Value='$category'";
			$res=mysql_query($sql,$dbbms) or die(mysql_error().$sql);
			$row=mysql_fetch_array($res);	
			$Name=$row["Name"];
			$desc=$row["Description"];
			$smarty->assign("cat_name",$Name);				
			$smarty->assign("cat_detail",$desc);			
		}
		$smarty->assign("criteria",$criteria);	

		$i=0;

		if($category)
		{
			$smarty->assign("category",$category);

			if($category=='new')
			{
				//Identifying Category/Subcategory which cannot be mapped.
				//A max of level2 is there ie. Catgegory having sub-category cannot be mapped.
				//Any subcatgory cannot be mapped.
				$sql="SELECT ParentCat,Name,Value FROM bms2.CATEGORY_99 WHERE Criteria='$criteria' AND ParentCat=0";
				$res=mysql_query($sql,$dbbms) or die(mysql_error().$sql);
				while($row=mysql_fetch_array($res))
				{
					$flag_set=1;
					if(is_having_subcategory($row["Value"]))
					//cannot be mapped.
					{
						$my_arr[$i]["label"]=$row["Name"].' (Limit Reached) ';	
						$my_arr[$i]["value"]='2levelreached';	
						$my_arr[$i]["newfont"]='style="font-weight: bold;color:red"';
				
						$sql_1="SELECT Name FROM bms2.CATEGORY_99 WHERE ParentCat='$row[Value]'";
						$res_1=mysql_query($sql_1,$dbbms) or die(mysql_error().$sql_1);
						while($row_1=mysql_fetch_array($res_1))
						{
							$i++;
							$my_arr[$i]["label"]='------'.$row_1["Name"];	
							$my_arr[$i]["value"]='2levelreached';	
							$my_arr[$i]["newfont"]='style="font-weight: bold;color:red"';
						}
					}
					else
					//can be mapped ( no parent/sub catgory)
					{
						$my_arr[$i]["label"]=$row["Name"];	
						$my_arr[$i]["value"]=$row["Name"];	
						$my_arr[$i]["newfont"]='style="font-weight: bold;color:blue"';
					}
					$i=$i+1;
				}
				if($flag_set)
					$not_val=already_mapped($criteria);
			}
			else
			{
				$sql="SELECT ParentCat,Name,Value FROM bms2.CATEGORY_99 WHERE Criteria='$criteria' AND ParentCat=0 and Value NOT IN (0,$category)";
				$res=mysql_query($sql,$dbbms) or die(mysql_error().$sql);
				while($row=mysql_fetch_array($res))
				{
					$skip_cat_list.=$row["Value"].',';
					if(is_having_subcategory($row["Value"]))
					{
						$my_arr[$i]["label"]=$row["Name"].' (Limit Reached) ';	
						$my_arr[$i]["value"]='2levelreached';	
						$my_arr[$i]["newfont"]='style="font-weight: bold;color:red"';
						$sql_1="SELECT Name FROM bms2.CATEGORY_99 WHERE ParentCat='$row[Value]'";
						$res_1=mysql_query($sql_1) or die(mysql_error().$sql_1);
						while($row_1=mysql_fetch_array($res_1))
						{
							$i++;
							$my_arr[$i]["label"]='------'.$row_1["Name"];	
							$my_arr[$i]["value"]='2levelreached';	
							$my_arr[$i]["newfont"]='style="font-weight: bold;color:red"';
						}
					}
					else
					{
						$my_arr[$i]["label"]=$row["Name"];	
						$my_arr[$i]["value"]=$row["Name"];	
						$my_arr[$i]["newfont"]='style="font-weight: bold;color:blue"';
					}
					$i=$i+1;
				}
				$not_val=already_mapped($criteria,$category);

				if($skip_cat_list)
					$skip_cat_list=rtrim($skip_cat_list,',');
			}			
		}

		if($not_val)
		{
			$not_val=trim($not_val,',');
			$sql="SELECT DISTINCT(LABEL),VALUE FROM bms2.$criteria WHERE VALUE NOT IN ($not_val)";
		}
		else
			$sql="SELECT DISTINCT(LABEL),VALUE FROM bms2.$criteria WHERE 1";

		//limiting condition is added as these are themselves a category
		if($criteria=='PROPTYPE')
			$sql.=" AND VALUE NOT IN ('R','C')";
		elseif($criteria=='PROPCITY')
			$sql.=" AND FLAG<>'Y'";
			
		if($skip_cat_list)
			$sql.=" AND BmsCategory NOT IN ($skip_cat_list)";
		$sql.=" ORDER BY SORTBY";	

		$res=mysql_query($sql,$dbbms) or die(mysql_error().$sql);
		while($row=mysql_fetch_array($res))
		{
			$my_arr[$i]["label"]=$row["LABEL"];
			$my_arr[$i]["value"]=$row["VALUE"];
			$i=$i+1;
		}
		$smarty->assign("my_arr",$my_arr);//unmapped array
		$smarty->display("./$_TPLPATH/bms_createcategory1.htm");
	}
	else
	{
		$i=0;
		$criteria_array=array('PROPINR','PROPCITY','PROPTYPE','PROPINRRENT');
		for($jj=0;$jj<count($criteria_array);$jj++)
		{
			$name='new|@|new|#|default|@|default|#|';

			$sql_name = "SELECT NAME,VALUE FROM bms2.CATEGORY_99 WHERE CRITERIA = '$criteria_array[$jj]'";
			$res_name = mysql_query($sql_name,$dbbms) or die("$sql_name".mysql_error());
			while($row_name = mysql_fetch_array($res_name))
			{
				$name .= "$row_name[NAME]"."|@|"."$row_name[VALUE]"."|#|";
			}

			$cat_arr[] = $criteria_array[$jj]."|X|".rtrim($name,"|#|");

			unset($name);
		}
		$smarty->assign("criteria_arr",$criteria_array);
		$smarty->assign("cat_arr",$cat_arr);
		$smarty->display("./$_TPLPATH/bms_createcategory.htm");
	}
}
else
	TimedOutBms();
					
function is_having_subcategory($val)
{
	global $dbbms;
	$sql="SELECT count(*) as cnt FROM bms2.CATEGORY_99 WHERE ParentCat='$val'";
	$res=mysql_query($sql,$dbbms) or die(mysql_error().$sql);
	$row=mysql_fetch_array($res);
	return($row['cnt']);
}

//Ignoring Values That are already mapped.
//These values are not going to be displayed.
function already_mapped($criteria,$category="")
{
	global $dbbms,$smarty;
	$i=0;
	if($category)
	{
		$cat_list=$category;
		$sql="SELECT Name,Value FROM bms2.CATEGORY_99 WHERE ParentCat='$category'";
		$res=mysql_query($sql,$dbbms) or die(mysql_error().$sql);
		while($row=mysql_fetch_array($res))
		{
			$selected_my_arr[$i]["label"]=$row["Name"];
			$selected_my_arr[$i]["value"]=$row["Name"];
			$selected_my_arr[$i]["newfont"]='style="font-weight: bold;color:blue"';
			$i=$i+1;
			$cat_list.=','.$row["Value"];
		}
	}
	if($category)
	{
		if($i>0)
			$sql="SELECT DISTINCT(LABEL),VALUE,BmsCategory FROM bms2.$criteria WHERE BmsCategory in ($cat_list) ORDER BY SORTBY ";	
		else
			$sql="SELECT DISTINCT(LABEL),VALUE,BmsCategory FROM bms2.$criteria WHERE BmsCategory='$category' ORDER BY SORTBY ";	

	}
	else
		$sql="SELECT DISTINCT(LABEL),VALUE FROM bms2.$criteria WHERE BmsCategory>0 ORDER BY SORTBY ";	
		
	$res=mysql_query($sql,$dbbms) or die(mysql_error().$sql);
	while($row=mysql_fetch_array($res))
	{
		if($category)
		{
			if($row["BmsCategory"]==$category)
			{
				$selected_my_arr[$i]["label"]=$row["LABEL"];
				$selected_my_arr[$i]["value"]=$row["VALUE"];
				$i=$i+1;
			}
		}
		$not_val.=$row["VALUE"].",";
	}

	//Not to show Subcategory values of parent of current sub-category.
        if($category)
        {
		$sql="SELECT ParentCat FROM bms2.CATEGORY_99 WHERE Value='$category'";
		$res=mysql_query($sql,$dbbms) or die(mysql_error().$sql);
		if($row=mysql_fetch_array($res))
		{
			$cat_of_sub_value=$row["ParentCat"];
		}

		if($cat_of_sub_value)
		{
			$sql="SELECT Value FROM bms2.CATEGORY_99 WHERE ParentCat=$cat_of_sub_value and Value<>$category";
			$res=mysql_query($sql,$dbbms) or die(mysql_error().$sql);
			while($row=mysql_fetch_array($res))
			{
				$cat_of_sub_value.=','.$row["Value"];
			}

			$cat_of_sub_value=rtrim($cat_of_sub_value,',');
	                $sql="SELECT DISTINCT(LABEL),VALUE FROM bms2.$criteria WHERE BmsCategory in ($cat_of_sub_value)";
        	        $res=mysql_query($sql,$dbbms) or die(mysql_error().$sql);
                	while($row=mysql_fetch_array($res))
	                {
        	                $not_val.=$row["VALUE"].",";
                	}
		}
        }


	if($category)
		$smarty->assign("selected_my_arr",$selected_my_arr);
	return($not_val);
}

function display_list($criteria)
{
	global $smarty,$dbbms,$_TPLPATH ;
	$i=0;
	$sql="SELECT Name,Value FROM bms2.CATEGORY_99 WHERE Criteria='$criteria' AND ParentCat=0";
	$res=mysql_query($sql,$dbbms) or die(mysql_error().$sql);
	while($row=mysql_fetch_array($res))
	{
		$j=0;
		$cat[$i]=$row["Name"];
		$val=$row["Value"];

		$sql1="SELECT Name,Value FROM bms2.CATEGORY_99 WHERE ParentCat='$val'";
		$res1=mysql_query($sql1,$dbbms) or die(mysql_error().$sql1);
		while($row1=mysql_fetch_array($res1))
		{
			$k=0;
			$subcat[$i][$j]=$row1["Name"];
			$val_sub=$row1["Value"];
			$sql2="SELECT DISTINCT(LABEL) FROM bms2.$criteria WHERE BmsCategory='$val_sub'";
			$res2=mysql_query($sql2,$dbbms) or die(mysql_error().$sql2);
			while($row2=mysql_fetch_array($res2))
			{
				$element[$i][$j][$k]=$row2["LABEL"];	
				$k++;
			}
			$j++;		
		}
		$subcat[$i][$j]="For-category: ".$row["Name"];
		$k=0;
		$sql2="SELECT DISTINCT(LABEL) FROM bms2.$criteria WHERE BmsCategory='$val'";
		$res2=mysql_query($sql2,$dbbms) or die(mysql_error().$sql2);
		while($row2=mysql_fetch_array($res2))
		{
			$element[$i][$j][$k]=$row2["LABEL"];	
			$k++;
			$flag=1;
		}
		if($flag)
			unset($flag);
		else
			unset($subcat[$i][$j]);
		$i++;
	}

	$j=0;
	$k=0;
	$cat[$i]="Default";
	$subcat[$i][$j]="For Category: Default";

	$sql2="SELECT DISTINCT(LABEL) FROM bms2.$criteria WHERE BmsCategory=0";
	if($criteria=='PROPTYPE')
		$sql2.=" AND VALUE NOT IN ('R','C')";
	elseif($criteria=='PROPCITY')
		$sql2.=" AND FLAG<>'Y'";
	$res2=mysql_query($sql2,$dbbms) or die(mysql_error().$sql2);
	while($row2=mysql_fetch_array($res2))
	{
		$element[$i][$j][$k]=$row2["LABEL"];	
		$k++;
	}
	$smarty->assign("cat",$cat);
	$smarty->assign("subcat",$subcat);
	$smarty->assign("element",$element);
	$smarty->assign("criteria",$criteria);
	$smarty->display("./$_TPLPATH/bms_category_display.htm");

	exit;
}

//Modifying category subcategory value will affect banners booked on  sub-category(catgory) and parent category.
function update_banner_values($val,$criteria)
{
	global $dbbms;
	if($criteria=='PROPINRRENT')
		$criteria_n='PROPINR';
	else
		$criteria_n=$criteria;

	//if a category is edited then it will have its subcategory value as well.
	$sql="SELECT Value FROM bms2.CATEGORY_99 WHERE ParentCat=$val";
	$res=mysql_query($sql,$dbbms) or die(mysql_error().$sql);
	while($row=mysql_fetch_array($res))
	{
		$temp_val1=$row["Value"];
		$sql1="SELECT DISTINCT(VALUE) FROM bms2.$criteria WHERE BmsCategory=$temp_val1";
		$res1=mysql_query($sql1,$dbbms) or die(mysql_error().$sql1);
		while($row1=mysql_fetch_array($res1))
		{
			$parent_value.=$row1["VALUE"].' , ';
		}
	}

	//served and expired banner will not be affected .
	$sql="SELECT BannerId,Category_sub from bms2.BANNER WHERE BannerStatus in ('booked','newrequest','ready','live') AND Category_sub like '% $val %' ";
	$res=mysql_query($sql,$dbbms) or die(mysql_error().$sql);
	while($row=mysql_fetch_array($res))
	{
		unset($cat_sub_arr);
		$bannerid=$row["BannerId"];
		$cat_sub=$row["Category_sub"];
		$cat_sub=trim($cat_sub,'#');
		$cat_sub_arr=explode(',',$cat_sub);
		$cat_sub_str=implode(',',$cat_sub_arr);
		
		$sql1="SELECT DISTINCT(VALUE) FROM bms2.$criteria WHERE BmsCategory in ($cat_sub_str)";
		$res1=mysql_query($sql1,$dbbms) or die(mysql_error().$sql1);
		while($row1=mysql_fetch_array($res1))
		{
			$value.=$row1["VALUE"].' , ';
		}

		if(!$value)
			$value='';

		if($parent_value)	
		{
			$value=$parent_value.$value;
		}
		if($value)
			$value='# '.rtrim($value,' , ').' #';
		
		$sql1="UPDATE bms2.BANNER SET Banner$criteria_n='$value' WHERE BannerId='$bannerid'";
		mysql_query($sql1,$dbbms) or die(mysql_error().$sql1);
		unset($value);
	}

	//if a subcategory is modified then all its parent category will be modified.
	$sql="SELECT ParentCat FROM bms2.CATEGORY_99 WHERE Value=$val";
	$res=mysql_query($sql,$dbbms) or die(mysql_error().$sql);
	while($row=mysql_fetch_array($res))
	{
		$parentval=$row["ParentCat"];
		if($parentval)
			update_banner_values($parentval,$criteria);//recursive calling of a function
	}
}

?>
