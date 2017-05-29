<?php
global $INCOME_DATA;
if($INCOME_DATA == null)
	include(JsConstants::$docRoot."/commonFiles/dropdowns.php");
else
	include_once(JsConstants::$docRoot."/commonFiles/dropdowns.php");
	
function get_income_drop($whereEqual,$whereNotEqual, $select,$type='')
{
	global $INCOME_DATA;

	for($i=0;$i<count($INCOME_DATA);$i++)
	{
		if(is_array($whereEqual))
			foreach($whereEqual as $key=>$val)
			{

				if(!($INCOME_DATA[$i][$key]==$val))
				{
					$pass=0;
					break;
				}
				else
				{
					$pass=1;
				}
			}
			if(is_array($whereNotEqual) && $pass==1)
				foreach($whereNotEqual as $key=>$val)
				{
					if(!($INCOME_DATA[$i][$key]!=$val))
					{
						$pass=0;
						break;
					}
					else
					{
						$pass=1;
					}
				}
				if($pass==1)
				{
					foreach($select as $key=>$val)
						$select[$key]=$INCOME_DATA[$i][$key];
					if($type=='array')
						$returnArray[]=$select;
				}
				$pass=0;

	}
	if($type=='array')
		return $returnArray;
	else
		return $select;

}
function current_ranges_sortby($pincome_str,$myDb)
{
   $partner_income_arr1 = array();
   $sort_arr = array();
   $pincome_arr = explode(",",$pincome_str);
   for($i=0;$i<count($pincome_arr);$i++)
   {
        $incomeArr = get_income_drop(array("VALUE"=>$pincome_arr[$i]),null,array('SORTBY'=>''));
        $partner_income_arr1[] = $incomeArr['SORTBY'];
   }
   for($i=0;$i<count($partner_income_arr1);$i++)
   {
      if($partner_income_arr1[$i]>=17 && $partner_income_arr1[$i]<=24)
      {
         $dollar = 1;
		 if(!$sort_arr["minID"])
		    $sort_arr["minID"]=$partner_income_arr1[$i];
         else
		 {
		    if($partner_income_arr1[$i]<$sort_arr["minID"])
			   $sort_arr["minID"]=$partner_income_arr1[$i];
         }
		 if(!$sort_arr["maxID"])
		    $sort_arr["maxID"]=$partner_income_arr1[$i];
		 else
		 {
		    if($partner_income_arr1[$i]>$sort_arr["maxID"])
					$sort_arr["maxID"]=$partner_income_arr1[$i];
         }
      }
	  elseif($partner_income_arr1[$i]>=2 && $partner_income_arr1[$i]<=16)
      {
	     $rupees = 1;
		 if(!$sort_arr["minIR"])
		    $sort_arr["minIR"]=$partner_income_arr1[$i];
         else
		 {
		    if($partner_income_arr1[$i]<$sort_arr["minIR"])
			   $sort_arr["minIR"]=$partner_income_arr1[$i];
         }
		 if(!$sort_arr["maxIR"])
		    $sort_arr["maxIR"]=$partner_income_arr1[$i];
		 else
		 {
		    if($partner_income_arr1[$i]>$sort_arr["maxIR"])
					$sort_arr["maxIR"]=$partner_income_arr1[$i];
		 }
      }
	}
	if($dollar && $rupees)
		$sort_arr["currency"]="both";
	elseif($dollar)
		$sort_arr["currency"]="dollar";
	else
		$sort_arr["currency"]="rupees";
        return $sort_arr;
}

function make_ranges_continous($cur_sort_arr,$myDb,$return)
{
	$partner_income_arr = array();
	if($cur_sort_arr["currency"]=="both")
	{
		$lincome1 = $cur_sort_arr["minID"];
		$hincome1 = $cur_sort_arr["maxID"];
		$lincome2 = $cur_sort_arr["minIR"];
		$hincome2 = $cur_sort_arr["maxIR"];
		if($lincome1==$hincome1 && !in_array($lincome1,$partner_income_arr))
			$partner_income_arr[]=$lincome1;
		else
		{
			$inc1=$lincome1;
			while($inc1<=$hincome1 && !in_array($inc1,$partner_income_arr))
			{
				$partner_income_arr[]=$inc1;
				$inc1++;
			}
		}
		if($lincome2==$hincome2 && !in_array($lincome2,$partner_income_arr))
			$partner_income_arr[]=$lincome2;
		else
		{
			$inc2=$lincome2;
			while($inc2<=$hincome2 && !in_array($inc2,$partner_income_arr))
			{
				$partner_income_arr[]=$inc2;
				$inc2++;
			}
		}
    }
	else
	{
		if($cur_sort_arr["currency"]=="dollar")
		{
			$lincome = $cur_sort_arr["minID"];
			$hincome = $cur_sort_arr["maxID"];
		}
		else
		{
			$lincome = $cur_sort_arr["minIR"];
			$hincome = $cur_sort_arr["maxIR"];
		}
		if($lincome==$hincome)
			$partner_income_arr=array($lincome);
		else
		{
			$inc=$lincome;
			while($inc<=$hincome)
			{
				$partner_income_arr[]=$inc;
				$inc++;
			}
		}
    }
	if($partner_income_arr)
	{
		$istr=implode("','",$partner_income_arr);
		$istr="'".$istr."'";
	}
	else
		$istr='';
        
	if($return=='str')
	{
		unset($cur_sort_arr);
		return $istr;
	}
	else
	{
		$cur_arr = array();
        for($i=0;$i<count($partner_income_arr);$i++)
        {
        	$sort_arr = get_income_drop(array("SORTBY"=>$partner_income_arr[$i]),null,array('SORTBY'=>'','MIN_VALUE'=>'','MAX_VALUE'=>''));
			if($sort_arr['SORTBY']==$cur_sort_arr['minIR'])
				$cur_arr['minIR']=$sort_arr['MIN_VALUE'];
			if($sort_arr['SORTBY']==$cur_sort_arr['maxIR'])
				$cur_arr['maxIR']=$sort_arr['MAX_VALUE'];
			if($sort_arr['SORTBY']==$cur_sort_arr['minID'])
				$cur_arr['minID']=$sort_arr['MIN_VALUE'];
			if($sort_arr['SORTBY']==$cur_sort_arr['maxID'])
				$cur_arr['maxID']=$sort_arr['MAX_VALUE'];
		}
		$cur_arr["currency"]=$cur_sort_arr["currency"];
		unset($partner_income_arr);
		unset($cur_sort_arr);
		return $cur_arr;
	}
}

function get_mapped_values($cur_arr,$myDb)
{
	global $removeIncomeFlag;
	$map_arr = array();
	if($cur_arr["currency"]=="dollar")
	{
		$imin = $cur_arr["minID"];
		$imax = $cur_arr["maxID"];
	}
	else
	{
		$imin = $cur_arr["minIR"];
        $imax = $cur_arr["maxIR"];
	}
	if(!$imin)
		$fmin=$imin;
	else
	{
           $min_arr = get_income_drop(array('MIN_VALUE'=>$imin),null,array('MAPPED_MIN_VAL'=>''),'');
           $fmin=$min_arr['MAPPED_MIN_VAL'];
	}
	if($imax==19)
		$fmax=$imax;
	else
	{

           $max_arr = get_income_drop(array('MAX_VALUE'=>$imax),null,array('MAPPED_MAX_VAL'=>''),'');
           $fmax=$max_arr['MAPPED_MAX_VAL'];
	}
	if($cur_arr["currency"]=="dollar")
	{
		if($cur_arr["minID"]>0)
            $removeIncomeFlag=1;
		$map_arr["minIR"]=$fmin;
		$map_arr["maxIR"]=$fmax;
		$map_arr["currency"]="rupees";
	}
	else
	{
		if($cur_arr["minIR"]>0)
			$removeIncomeFlag=1;
		$map_arr["minID"]=$fmin;
                $map_arr["maxID"]=$fmax;
		$map_arr["currency"]="dollar";
	}
	return $map_arr;
}

function get_pincome_str($cur_arr,$myDb)
{
	global $removeIncomeFlag;
	$sort_arr = array();
	if($cur_arr['minIR']=='0' || $cur_arr['minID']=='0' || $cur_arr['maxIR']=='0' || $cur_arr['maxID']=='0')
		$no_income_case=1;
	else
		$no_income_case=0;
	if(isset($cur_arr['minIR']))
	{
        $arr = get_income_drop(array('MIN_VALUE'=>$cur_arr[minIR],'TYPE'=>'RUPEES','VISIBLE'=>'Y'), array('SORTBY'=>0),array('SORTBY'=>''),'');
        $sort_arr['minIR']= $arr['SORTBY'];
   }
	else
		$sort_arr['minIR']=$cur_arr['minIR'];

	if(isset($cur_arr['minID']))
	{       
		$arr = get_income_drop(array('MIN_VALUE'=>$cur_arr[minID],'TYPE'=>'DOLLARS',), array('SORTBY'=>0),array('SORTBY'=>''),'');
		$sort_arr['minID'] = $arr['SORTBY'];
    }
	else
		$sort_arr['minID']=$cur_arr['minID'];

	if(isset($cur_arr['maxIR']))
    {
    	$arr = get_income_drop(array('MAX_VALUE'=>$cur_arr[maxIR],'TYPE'=>'RUPEES',), null,array('SORTBY'=>''),'');
    	$sort_arr['maxIR']= $arr['SORTBY'];
	   
	}
	else
		 $sort_arr['maxIR']=$cur_arr['maxIR'];

	if(isset($cur_arr['maxID']))
    {
    	$arr = get_income_drop(array('MAX_VALUE'=>$cur_arr[maxID],'TYPE'=>'DOLLARS',), array('SORTBY'=>0),array('SORTBY'=>''),'');
    	$sort_arr['maxID']= $arr['SORTBY'];
	}
	else
		$sort_arr['maxID']=$cur_arr['maxID'];
	$sort_arr['currency']=$cur_arr['currency'];

	$str=make_ranges_continous($sort_arr,$myDb,'str');

	$pincome_arr = array();
	if($str!='')
    {
       $str = str_replace("'","",$str);
       $arr = explode(",",$str);
       sort($arr);
       for($i=0;$i<count($arr);$i++)
       {
       	$incomeArr = get_income_drop(array('SORTBY'=>$arr[$i]),null,array('VALUE'=>''));
       	$pincome_arr[] = $incomeArr['VALUE'];
       }       
	   $final_str = implode("','",$pincome_arr);
	   if($no_income_case && !$removeIncomeFlag)
			$final_str="15','".$final_str;	
	}
	else
		$final_str ="15";
	return "'".$final_str."'";
}

function populateIncomeDropDowns($db='',$selectedValue='')
{
	if(!$db)
		global $db;
	if(!$db)
		$db=connect_db();
	global $smarty;
	$INCOME = get_income_drop(array("VISIBLE"=>'Y'),null,array('LABEL'=>'','MIN_LABEL'=>'','MIN_VALUE'=>'','MAX_LABEL'=>'','MAX_VALUE'=>'','TYPE'=>'','VALUE'=>'','MAPPED_MIN_VAL'=>'','MAPPED_MAX_VAL'=>''),'array');
    foreach($INCOME as $key=>$val)
    {
    	if($val['LABEL']=='No Income')
	    {
			$maxLabel[0][$val['MAX_VALUE']]='No Income';
			$maxLabel[1][$val['MAX_VALUE']]='No Income';
		}
    	else
		{
			if($val['TYPE']=='RUPEES')
			{
				if(isset($val['MIN_LABEL']))
					$minLabel[0][$val['MIN_VALUE']]=$val['MIN_LABEL'];
				if(isset($val['MAX_LABEL']))
					$maxLabel[0][$val['MAX_VALUE']]=$val['MAX_LABEL'];
			}
			else if($val['TYPE']=='DOLLARS')
			{
				if(isset($val['MIN_LABEL']))
					$minLabel[1][$val['MIN_VALUE']]=$val['MIN_LABEL'];
				if(isset($val['MAX_LABEL']))
					$maxLabel[1][$val['MAX_VALUE']]=$val['MAX_LABEL'];
			}
		}
    if($selectedValue)
	{
		if($selectedValue==$val['VALUE'])
		{
			$smarty->assign("selectedLowerIncomeRs",$val['MAPPED_MIN_VAL']);
			$smarty->assign("selectedUpperIncomeRs",$val['MAPPED_MAX_VAL']);
			$smarty->assign("selectedLowerIncomeDo",$val['MIN_VALUE']);
			$smarty->assign("selectedUpperIncomeDo",$val['MAX_VALUE']);
		}
	}
    }	
	$smarty->assign("MAX_LABEL_RS",$maxLabel[0]);
	$smarty->assign("MIN_LABEL_RS",$minLabel[0]);
	$smarty->assign("MAX_LABEL_DO",$maxLabel[1]);
	$smarty->assign("MIN_LABEL_DO",$minLabel[1]);
}

function getIncomeText($incomeRangeArr)
{
	global $INCOME_MAX_DROP,$INCOME_MIN_DROP;
	$minIR=$incomeRangeArr["minIR"];
	$maxIR=$incomeRangeArr["maxIR"];
	$minID=$incomeRangeArr["minID"];
	$maxID=$incomeRangeArr["maxID"];
	if($minIR==0)
	{
		if($maxIR)
			$rsText="Rs. 0 to ".$INCOME_MAX_DROP[$maxIR];
		else
			$rsText="Rs. No Income";
	}
	else
		$rsText=$INCOME_MIN_DROP[$minIR]." to ".$INCOME_MAX_DROP[$maxIR];
	if($rsText)
		$varr[]=str_replace("to and above","and above",$rsText);

	if($minID==0)
	{
		if($maxID)
			$rsDoll=" $0 to ".$INCOME_MAX_DROP[$maxID];
		else
			$rsDoll=" $ No Income";
	}
	else
		$rsDoll=$INCOME_MIN_DROP[$minID]." to ".$INCOME_MAX_DROP[$maxID];
	if($rsDoll)
		$varr[]=str_replace("to and above","and above",$rsDoll);
	return $varr;
}
/*function plus4_income($value,$type,$db)
{
	$sql2="SELECT MIN_VALUE FROM newjs.INCOME WHERE VALUE='$value'";
	$result2=mysql_query($sql2);
	$row2=mysql_fetch_assoc($result2);
	$min_value=$row2["MIN_VALUE"];

	$min_value_end=$min_value+4;
	$min=10000;
	$max=-100;
	$sql1="SELECT MIN_VALUE,MAX_VALUE FROM newjs.INCOME WHERE MIN_VALUE BETWEEN $min_value AND $min_value_end AND TYPE='$type' AND VALUE!=7";
	$result1=mysql_query($sql1) or die(mysql_error());
	while($row1=mysql_fetch_assoc($result1))
	{
		if($min>$row1["MIN_VALUE"])
			$min=$row1["MIN_VALUE"];
		if($max<$row1["MAX_VALUE"])
			$max=$row1["MAX_VALUE"];
	}
	return create_drop_income($type,$db,$min,$max);
}
*/
function create_drop_income($currency,$db,$min,$max)
{
	if($currency=='RUPEES')
	{
		$cur_sort_arr["currency"]='rupees';
		$cur_sort_arr["minIR"]=$min;
		$cur_sort_arr["maxIR"]=$max;
		$rsIncomeMentioned=1;
	}
	else
	{
		$cur_sort_arr["currency"]='dollar';
		$cur_sort_arr["minID"]=$min;
		$cur_sort_arr["maxID"]=$max;
	}
	$arrMapped=get_mapped_values($cur_sort_arr,$db);
	if($rsIncomeMentioned)
	{
		$cur_sort_arr["minID"]=$arrMapped["minID"];
		$cur_sort_arr["maxID"]=$arrMapped["maxID"];
	}
	else
	{
		$cur_sort_arr["minIR"]=$arrMapped["minIR"];
		$cur_sort_arr["maxIR"]=$arrMapped["maxIR"];
	}
	$cur_sort_arr["currency"]='both';
	$incomeArrStr=get_pincome_str($cur_sort_arr,$db,$return);
	$incomeArrStr=str_replace("'","",$incomeArrStr);
	return $incomeArrStr;
}


function find_income_currency($income)
{
	global $INCOME_DATA;
	
        for($i=0;$i<count($INCOME_DATA);$i++)
	{

		if($INCOME_DATA[$i]["VALUE"]==$income)
		{
			if($INCOME_DATA[$i]["TYPE"]=="RUPEES")
				return "rupees";
			else
				return "dollar";
		}
	}
}

// returns equal if income1 and income2 are equal
// returns smaller if income1 < income2
// returns greater if income1>income2
function compareIncomes($income1,$income2)
{
	global $INCOME_DATA;

	if($income1==$income2)
		return "equal";

	$currency1=find_income_currency($income1);
	$currency2=find_income_currency($income2);

	$sortOrder1=current_ranges_sortby($income1,'');

	if($currency1!=$currency2)
	{
		for($i=0;$i<count($INCOME_DATA);$i++)
        	{
			if($INCOME_DATA[$i]["VALUE"]==$income2)
			{
				if($currency1=="rupees")
				{
					if($sortOrder1['minIR']>$INCOME_DATA[$i]['MAPPED_MAX_VAL'])
						return "greater";
					elseif($sortOrder1['maxIR']<$INCOME_DATA[$i]['MAPPED_MIN_VAL'])			
						return "smaller";
					else
						return "equal";
				}
				elseif($currency1=="dollar")
				{
					if($sortOrder1['minID']>$INCOME_DATA[$i]['MAPPED_MAX_VAL'])                     
                                                return "greater";               
                                        elseif($sortOrder1['maxID']<$INCOME_DATA[$i]['MAPPED_MIN_VAL'])
                                                return "smaller";
                                        else
                                                return "equal";
				}
			}
		}
	}
	else
	{
		$sortOrder2=current_ranges_sortby($income2,'');

		if($currency1=="rupees")
		{
			if($sortOrder1['minIR']>$sortOrder2['maxIR'])
                        	return "greater";
                        elseif($sortOrder1['maxIR']<$sortOrder2['minIR'])
                                return "smaller";
                        else
                                return "equal";
                }
                elseif($currency1=="dollar")
                {
			if($sortOrder1['minID']>$sortOrder2['maxID'])
                		return "greater";
        		elseif($sortOrder1['maxID']<$sortOrder2['minID'])
                                return "smaller";
                        else
                                return "equal";
                }
	}
}
function getSortByIncome($income='',$pid='',$gender='',$plus_minus_value='')
{
	if(!$income && $pid && ($plus_minus_value && !$gender) )
        {
                $sql="SELECT INCOME,GENDER FROM newjs.JPROFILE WHERE  activatedKey=1 and PROFILEID=$pid";
                $res=mysql_query_decide($sql) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql_mis,"ShowErrTemplate");
                $row=mysql_fetch_array($res);
                $income=$row["INCOME"];
                $gender=$row["GENDER"];
        }

	$incomeArr = get_income_drop(array("VALUE"=>$income),null,array("SORTBY"=>''));
        $sortBy = $incomeArr['SORTBY'];
	if($plus_minus_value)
        {
                global $loggedInGender;
                $loggedInGender=$gender;

                if($gender=='F')
                        $sortBy-=$plus_minus_value;
                else
                        $sortBy+=$plus_minus_value;
        }

	return $sortBy;
}


function getTrendsSortBy($income)
{	
	$incomeArr = get_income_drop(array('VALUE'=>$income),null,array('TRENDS_SORTBY'=>''));	        
	$trendSortBy = $incomeArr['TRENDS_SORTBY'];
	return $trendSortBy;
}

function getTrendsSortByRupeeLabel($trendSortByValue)
{	
	$incomeArr = get_income_drop(array('TRENDS_SORTBY'=>$trendSortByValue,'TYPE'=>'RUPEES','VISIBLE'=>'Y'),null,array('LABEL'=>''));	        
	$label = $incomeArr['LABEL'];
	return $label;
}
?>
