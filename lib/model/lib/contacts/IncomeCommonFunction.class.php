<?php
/**
 * 
 * The IncomeCommonFunction class contains the common functions related to Income table.
 * 
 * Code to use method of IncomeCommonFunction
 * 
 * <code>
 * $incomeObj = new IncomeCommonFunction();
 * //to fetch income mapping array.
 * $incomeObj->incomeMapping($rsLowIncome,$rsHighIncome,$dollarLowIncome,$dollarHighIncome)
 * 
 * //to get sorted income
 * IncomeCommonFunction::getSortedIncome($income='',$profileid='',$gender='',$plus_minus_value='')
 * 
 * </code>
 * 
 * @author Rohit Khandelwal
 * @package jeevansathi
 * @subpackage contacts
 *
 */

class IncomeCommonFunction
{
	/**
	 * 
	 * Used to initialize object of IncomeCommonFunction class.
	 */
	public function __construct()
	{
		$this->incomeData = FieldMap::getFieldLabel("income_data","",1);
		$this->removeIncomeFlag = 0;
	}
	/** 
	 * fetch all select parameters from incomeData array for given whereEqual and whereNotEqual Condition.
	 * @param array $whereEqual
	 * @param array $whereNotEqual
	 * @param array $select
	 * @param string $type default ''
	 * @return array
	 */
	public function getIncomeDrop($whereEqual,$whereNotEqual, $select,$type='')
	{	
		for($i=0;$i<count($this->incomeData);$i++)
		{
			if(is_array($whereEqual))
				foreach($whereEqual as $key=>$val)
				{
	
					if(!($this->incomeData[$i][$key]==$val))
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
						if(!($this->incomeData[$i][$key]!=$val))
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
							$select[$key]=$this->incomeData[$i][$key];
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
	/**
	 * 
	 *
	 * @param string $pincome_str
	 * @return array
	 * @uses getIncomeDrop()
	 * @access public
	 */
	public function currentRangeSortby($pincome_str)
	{
	   $partner_income_arr1 = array();
	   $sort_arr = array();
	   $pincome_arr = explode(",",$pincome_str);
	   for($i=0;$i<count($pincome_arr);$i++)
	   {
	        $incomeArr = $this->getIncomeDrop(array("VALUE"=>$pincome_arr[$i]),null,array('SORTBY'=>''));
	        $partner_income_arr1[] = $incomeArr['SORTBY'];
	   }
	   for($i=0;$i<count($partner_income_arr1);$i++)
	   {
	      if($partner_income_arr1[$i]>=13 && $partner_income_arr1[$i]<=20)
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
		  elseif($partner_income_arr1[$i]>=1 && $partner_income_arr1[$i]<=12)
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
	/**
	 * 
	 * 
	 * @param array $cur_sort_arr
	 * @param string $return
	 * @return string | array
	 * @uses getIncomeDrop()
	 * @access public
	 */
	
	public function make_ranges_continous($cur_sort_arr,$return)
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
	        	$sort_arr = $this->getIncomeDrop(array("SORTBY"=>$partner_income_arr[$i]),null,array('SORTBY'=>'','MIN_VALUE'=>'','MAX_VALUE'=>''));
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
	/**
	 * Returns mapped min and max values for given min and max values e.g. 
	 * mapped dollar values for rupees values or mapped rupees values for dollar values.
	 *
	 * @param array $cur_arr
	 * @return array
	 * @uses getIncomeDrop()
	 * @access public
	 */
	
	public function getMappedValues($cur_arr)
	{
		//global $removeIncomeFlag;
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
	           $min_arr = $this->getIncomeDrop(array('MIN_VALUE'=>$imin),null,array('MAPPED_MIN_VAL'=>''),'');
	           $fmin=$min_arr['MAPPED_MIN_VAL'];
		}
		if($imax==19)
			$fmax=$imax;
		else
		{
	
	           $max_arr = $this->getIncomeDrop(array('MAX_VALUE'=>$imax),null,array('MAPPED_MAX_VAL'=>''),'');
	           $fmax=$max_arr['MAPPED_MAX_VAL'];
		}
		if($cur_arr["currency"]=="dollar")
		{
			if($cur_arr["minID"]>0)
	            $this->removeIncomeFlag=1;
			$map_arr["minIR"]=$fmin;
			$map_arr["maxIR"]=$fmax;
			$map_arr["currency"]="rupees";
		}
		else
		{
			if($cur_arr["minIR"]>0)
				$this->removeIncomeFlag=1;
			$map_arr["minID"]=$fmin;
	                $map_arr["maxID"]=$fmax;
			$map_arr["currency"]="dollar";
		}
		return $map_arr;
	}
	/**
	 * 
	 *
	 * @param array $cur_arr
	 * @return string 
	 * @uses getIncomeDrop()
	 * @uses make_ranges_continous()
	 * @access public
	 */
	public function getPincomeStr($cur_arr)
	{
		//global $removeIncomeFlag;
		$sort_arr = array();
		if($cur_arr['minIR']=='0' || $cur_arr['minID']=='0' || $cur_arr['maxIR']=='0' || $cur_arr['maxID']=='0')
			$no_income_case=1;
		else
			$no_income_case=0;
		if(isset($cur_arr['minIR']))
		{
	        $arr = $this->getIncomeDrop(array('MIN_VALUE'=>$cur_arr['minIR'],'TYPE'=>'RUPEES','VISIBLE'=>'Y'), array('SORTBY'=>0),array('SORTBY'=>''),'');
	        $sort_arr['minIR']= $arr['SORTBY'];
	   }
		else
			$sort_arr['minIR']=$cur_arr['minIR'];
	
		if(isset($cur_arr['minID']))
		{       
			$arr = $this->getIncomeDrop(array('MIN_VALUE'=>$cur_arr['minID'],'TYPE'=>'DOLLARS',), array('SORTBY'=>0),array('SORTBY'=>''),'');
			$sort_arr['minID'] = $arr['SORTBY'];
	    }
		else
			$sort_arr['minID']=$cur_arr['minID'];
	
		if(isset($cur_arr['maxIR']))
	    {
	    	$arr = $this->getIncomeDrop(array('MAX_VALUE'=>$cur_arr['maxIR'],'TYPE'=>'RUPEES',), array('SORTBY'=>22),array('SORTBY'=>''),'');
	    	$sort_arr['maxIR']= $arr['SORTBY'];
		   
		}
		else
			 $sort_arr['maxIR']=$cur_arr['maxIR'];
	
		if(isset($cur_arr['maxID']))
	    {
	    	$arr = $this->getIncomeDrop(array('MAX_VALUE'=>$cur_arr['maxID'],'TYPE'=>'DOLLARS',), array('SORTBY'=>0),array('SORTBY'=>''),'');
	    	$sort_arr['maxID']= $arr['SORTBY'];
		}
		else
			$sort_arr['maxID']=$cur_arr['maxID'];
		$sort_arr['currency']=$cur_arr['currency'];
	
		$str = $this->make_ranges_continous($sort_arr,'str');
	
		$pincome_arr = array();
		if($str!='')
	    {
	       $str = str_replace("'","",$str);
	       $arr = explode(",",$str);
	       sort($arr);
	       for($i=0;$i<count($arr);$i++)
	       {
	       	$incomeArr = $this->getIncomeDrop(array('SORTBY'=>$arr[$i]),null,array('VALUE'=>''));
	       	$pincome_arr[] = $incomeArr['VALUE'];
	       }       
		   $final_str = implode("','",$pincome_arr);
		   if($no_income_case && !$this->removeIncomeFlag)
				$final_str="15','".$final_str;	
		}
		else
			$final_str ="15";
		return "'".$final_str."'";
	}
	/**
	 * 
	 * @param boolean $selectedValue
	 * @return void
	 * @uses getIncomeDrop()
	 * @access public
	 */
	
	public function populateIncomeDropDowns($selectedValue='')
	{
		//global $smarty;
		$INCOME = $this->getIncomeDrop(array("VISIBLE"=>'Y'),null,array('LABEL'=>'','MIN_LABEL'=>'','MIN_VALUE'=>'','MAX_LABEL'=>'','MAX_VALUE'=>'','TYPE'=>'','VALUE'=>'','MAPPED_MIN_VAL'=>'','MAPPED_MAX_VAL'=>''),'array');
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
	    /*if($selectedValue)
		{
			if($selectedValue==$val['VALUE'])
			{
				$smarty->assign("selectedLowerIncomeRs",$val['MAPPED_MIN_VAL']);
				$smarty->assign("selectedUpperIncomeRs",$val['MAPPED_MAX_VAL']);
				$smarty->assign("selectedLowerIncomeDo",$val['MIN_VALUE']);
				$smarty->assign("selectedUpperIncomeDo",$val['MAX_VALUE']);
			}
		}*/
	    }	
		/*
		$smarty->assign("MAX_LABEL_RS",$maxLabel[0]);
		$smarty->assign("MIN_LABEL_RS",$minLabel[0]);
		$smarty->assign("MAX_LABEL_DO",$maxLabel[1]);
		$smarty->assign("MIN_LABEL_DO",$minLabel[1]);*/
	}
	/**
	 * 
	 *
	 * @param array $incomeRangeArr
	 * @return array
	 * @access public
	 */
	
	public function getIncomeText($incomeRangeArr)
	{
		//global $INCOME_MAX_DROP,$INCOME_MIN_DROP;
		$INCOME_MIN_RS = FieldMap::getFieldLabel("lincome","");
		$INCOME_MAX_RS = FieldMap::getFieldLabel("hincome","");
		$INCOME_MIN_DOL = FieldMap::getFieldLabel("lincome_dol","");
		$INCOME_MAX_DOL = FieldMap::getFieldLabel("hincome_dol","");
		
		$minIR=$incomeRangeArr["minIR"];
		$maxIR=$incomeRangeArr["maxIR"];
		$minID=$incomeRangeArr["minID"];
		$maxID=$incomeRangeArr["maxID"];
		if($minIR==0)
		{
			if($maxIR)
				$rsText="Rs. 0 to ".$INCOME_MAX_RS[$maxIR];
			else
				$rsText="Rs. No Income";
		}
		else
			$rsText=$INCOME_MIN_RS[$minIR]." to ".$INCOME_MAX_RS[$maxIR];
		if($rsText)
			$varr[]=str_replace("to and above","and above",$rsText);
	
		if($minID==0)
		{
			if($maxID)
				$rsDoll=" $0 to ".$INCOME_MAX_DOL[$maxID];
			else
				$rsDoll=" $ No Income";
		}
		else
			$rsDoll=$INCOME_MIN_DOL[$minID]." to ".$INCOME_MAX_DOL[$maxID];
		if($rsDoll)
			$varr[]=str_replace("to and above","and above",$rsDoll);
		return $varr;
	}
	/**
	 * 
	 * @param int $value
	 * @param string $type
	 * @return string
	 * @access public
	 */
	public function plus4_income($value,$type)
	{
		$dbObj = new newjs_INCOME();
		$min_value = $dbObj->getMinValue($value);
		$min_value_end=$min_value+4;
		$min=10000;
		$max=-100;
		$result1 = $dbObj->getMinMaxValue($min_value,$min_value_end,$type);
		foreach($result1 as $key=>$val)
		{
			if($min>$val["MIN_VALUE"])
				$min=$val["MIN_VALUE"];
			if($max<$val["MAX_VALUE"])
				$max=$val["MAX_VALUE"];
		}
		return $this->createDropIncome($type,$min,$max);
	}
	/**
	 *
	 * @param string $currency
	 * @param int $min
	 * @param int $max
	 * @return string
	 * @uses getMappedValues()
	 * @uses getPincomeStr()
	 * @access public
	 */
	
	public function createDropIncome($currency,$min,$max)
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
		$arrMapped=$this->getMappedValues($cur_sort_arr);
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
		$incomeArrStr=$this->getPincomeStr($cur_sort_arr);
		$incomeArrStr=str_replace("'","",$incomeArrStr);
		return $incomeArrStr;
	}
	/**
	 * 
	 *
	 * @param int $my_income
	 * @param int $pid
	 * @param string $gender
	 * @param int $plus_minus_value
	 * @access public
	 * @return int
	 */
	
	public static function getSortedIncome($my_income='',$pid='',$gender='',$plus_minus_value='')
	{
	        
	        if($my_income==15)
	                $income_sortby=0;
	        elseif($my_income==21)
	                $income_sortby=19;
	        elseif($my_income==14)
	                $income_sortby=20;
	        elseif($my_income==23)
	                $income_sortby=12;
	        elseif($my_income==20 || $my_income==22)
	                $income_sortby=$my_income/2;
	        elseif($my_income<7)
	                $income_sortby=$my_income;
	        elseif(in_array($my_income,array(16,17,18)))
	                $income_sortby=$my_income-9;
	        else
	                $income_sortby=$my_income+5;
	
	        if($plus_minus_value)
	        {
	                global $loggedInGender;
	                $loggedInGender=$gender;
	
	                if($gender=='F')
	                        $income_sortby-=$plus_minus_value;
	                else
	                        $income_sortby+=$plus_minus_value;
	        }
	        return $income_sortby;
	}
	/**
	 *
	 * @param string $rsLIncome
	 * @param string $rsHIncome
	 * @param string $doLIncome
	 * @param string $doHIncome
	 * @return array
	 * @access public
	 */
	public function incomeMapping($rsLIncome,$rsHIncome,$doLIncome,$doHIncome)
	{
			$resultArr["doLIncome"]=$doLIncome;
			$resultArr["doHIncome"]=$doHIncome;
			$resultArr["rsLIncome"]=$rsLIncome;
			$resultArr["rsHIncome"]=$rsHIncome;
			if($rsLIncome || $rsLIncome =='0')
			{
				$cur_sort_arr["minIR"]=intval($rsLIncome);
				$rsIncomeMentioned=1;
			}
			if($rsHIncome || $rsHIncome=='0')
				$cur_sort_arr["maxIR"]=intval($rsHIncome);
			if($doLIncome || $doLIncome=='0')
			{
				$cur_sort_arr["minID"]=intval($doLIncome);
				$doIncomeMentioned=1;
			}
			if($doHIncome || $doHIncome =='0')
				 $cur_sort_arr["maxID"]=intval($doHIncome);
			if($rsIncomeMentioned && $doIncomeMentioned)
				$cur_sort_arr["currency"]='both';
			elseif($rsIncomeMentioned)
				$cur_sort_arr["currency"]='rupees';
			else
				$cur_sort_arr["currency"]='dollar';		
			
			
			if(!($rsIncomeMentioned && $doIncomeMentioned))
			{
				$arrMapped=$this->getMappedValues($cur_sort_arr);
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
			}
			$resultArr["istr"]=$this->getPincomeStr($cur_sort_arr);
			if(!$resultArr["istr"])
				$resultArr["istr"]='';

			if($rsLIncome!='' && $rsHIncome!=''){
				$resultArr["doLIncome"]=$cur_sort_arr["minID"];
				$resultArr["doHIncome"]=$cur_sort_arr["maxID"];
			}
			else if($doLIncome!='' && $doHIncome!=''){
				$resultArr["rsLIncome"]=$cur_sort_arr["minIR"];
				$resultArr["rsHIncome"]=$cur_sort_arr["maxIR"];
			}
		return $resultArr;
	}
}

?>
