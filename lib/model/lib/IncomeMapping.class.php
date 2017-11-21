<?php
/**
This class is used to perform mapping of rupees with dollars and dollars with rupees.
**/
class IncomeMapping
{
	private $incomeArr = array();
	private $removeIncomeFlag = 0;
	private $noIncomeCase = 0;
	private $dollarCurrency = "D";
	private $rupeeCurrency = "R";
	private $bothCurrency = "B";
	private $noIncomeValue = 15;

	public function getIncomeArr($param='')
	{
		if($param)
			return $this->incomeArr[$param];
		else
			return $this->incomeArr;
	}

	public function __construct($rArr="",$dArr="")
	{
		if($rArr)
		{
			$this->incomeArr["minIR"] = $rArr["minIR"];
			$this->incomeArr["maxIR"] = $rArr["maxIR"];
			$this->incomeArr["currency"] = "R";
		}
		if($dArr)
		{
			$this->incomeArr["minID"] = $dArr["minID"];
			$this->incomeArr["maxID"] = $dArr["maxID"];
			$this->incomeArr["currency"] = "D";
		}
		if($rArr && $dArr)
			$this->incomeArr["currency"] = "B";
		$this->incomeData = FieldMap::getFieldLabel("income_data","",1);
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
        This function is to be called to find the final income values having both rupees and dollars.
        * @param  type(optional)
        * @return array having all income values
        **/
	public function getAllIncomes($type='')
	{
		if(!$type)
			$this->getMappedValues();
		$sort_arr = $this->getSortbyValues();
		$incomeValues = $this->makeIncomeRange($sort_arr);
		return $incomeValues;
	}
	
	/**
        This function is used to map the rupee value to dollar or dollar value to rupee.
        * @param  none
        * @return none
        **/
	public function getMappedValues()
	{
		
		
		if($this->incomeArr["currency"] == $this->dollarCurrency)
		{
			$imin = $this->incomeArr["minID"];
			$imax = $this->incomeArr["maxID"];
		}
		else
		{
			$imin = $this->incomeArr["minIR"];
			$imax = $this->incomeArr["maxIR"];
		}
		if(!$imin)
			$mappedMin = $imin;
		else
		{
	           $min_arr = $this->getIncomeDrop(array('MIN_VALUE'=>$imin),null,array('MAPPED_MIN_VAL'=>''),'');
	           $mappedMin = $min_arr['MAPPED_MIN_VAL'];
		}
		if($imax==19)
			$mappedMax=$imax;
		else
		{
	
	           $max_arr = $this->getIncomeDrop(array('MAX_VALUE'=>$imax),null,array('MAPPED_MAX_VAL'=>''),'');
	           $mappedMax=$max_arr['MAPPED_MAX_VAL'];
		}
		if($this->incomeArr["currency"] == $this->dollarCurrency)
		{
			//if($this->incomeArr["minID"]>0)
              //          	$this->removeIncomeFlag=1;
                	$this->incomeArr["minIR"] = $mappedMin;
                	$this->incomeArr["maxIR"] = $mappedMax;
		}
		else
		{
			if($this->incomeArr["minIR"]>0)
                        	$this->removeIncomeFlag=1;
                	$this->incomeArr["minID"] = $mappedMin;
                	$this->incomeArr["maxID"] = $mappedMax;
		}
	}

	/**
        This function gives the SORTBY values from newjs.INCOME table corresponding to income values.
        * @param  none
        * @return array with rupee and dollar sortby values
        **/
	private function getSortbyValues()
	{
		if($this->incomeArr["minIR"]==0 || $this->incomeArr["maxID"]==0 || ($this->incomeArr["minID"]>0 && $this->incomeArr["minIR"]==0))
			$this->noIncomeCase = 1;
		else
			$this->noIncomeCase = 0;

		$tempSortArr=$this->getIncomeDrop(array('MIN_VALUE'=>$this->incomeArr['minIR'],'TYPE'=>'RUPEES','VISIBLE'=>'Y'), array('SORTBY'=>0),array('SORTBY'=>''));
		$sort_arr["minIR"] = $tempSortArr["SORTBY"];
		$tempSortArr=$this->getIncomeDrop(array('MAX_VALUE'=>$this->incomeArr['maxIR'],'TYPE'=>'RUPEES','VISIBLE'=>'Y'),null,array('SORTBY'=>''));
		$sort_arr["maxIR"] = $tempSortArr["SORTBY"];
		$tempSortArr=$this->getIncomeDrop(array('MIN_VALUE'=>$this->incomeArr['minID'],'TYPE'=>'DOLLARS',), array('SORTBY'=>0),array('SORTBY'=>''));
		$sort_arr["minID"] = $tempSortArr["SORTBY"];
		$tempSortArr=$this->getIncomeDrop(array('MAX_VALUE'=>$this->incomeArr['maxID'],'TYPE'=>'DOLLARS',), array('SORTBY'=>0),array('SORTBY'=>''));
		$sort_arr["maxID"] = $tempSortArr["SORTBY"];
		$sort_arr["currency"] = $this->bothCurrency;
		return $sort_arr;
	}

	/**
        This function generates the continuous SORTBY values and then income values corresponding to the SORTBY values
        * @param  output of getSortbyValues()
        * @return final income values array
        **/
	private function makeIncomeRange($sort_arr)
	{
		$partner_income_arr = array();
		

		if($sort_arr["currency"] == $this->bothCurrency)
		{
			$lincome1 = $sort_arr["minID"];
			$hincome1 = $sort_arr["maxID"];
			$lincome2 = $sort_arr["minIR"];
			$hincome2 = $sort_arr["maxIR"];

			while($lincome1<=$hincome1 && !in_array($lincome1,$partner_income_arr))
			{
				$partner_income_arr[]=$lincome1;
				$lincome1++;
			}

			while($lincome2<=$hincome2 && !in_array($lincome2,$partner_income_arr))
			{
				$partner_income_arr[]=$lincome2;
				$lincome2++;
			}
		}
		else
		{
			if($sort_arr["currency"] == $this->dollarCurrency)
			{
				$lincome = $sort_arr["minID"];
                        	$hincome = $sort_arr["maxID"];
			}
			else
			{
				$lincome = $sort_arr["minIR"];
                        	$hincome = $sort_arr["maxIR"];
			}

			while($lincome<=$hincome && !in_array($lincome,$partner_income_arr))
                        {
                                $partner_income_arr[]=$lincome;
                                $lincome++;
                        }
		}

		if($partner_income_arr)
		{
			
			$partner_income_arr_str = "'".implode("','",$partner_income_arr)."'";
			for($i=0;$i<count($partner_income_arr);$i++)
			{
				$incomeArr = $this->getIncomeDrop(array('SORTBY'=>$partner_income_arr[$i]),null,array('VALUE'=>''));
				$incomeValues[] = $incomeArr['VALUE'];
			}
			if($this->noIncomeCase && !$this->removeIncomeFlag && is_array($incomeValues) && !in_array(15,$incomeValues))
				$incomeValues[] = $this->noIncomeValue;
		}
		else
			$incomeValues[] = $this->noIncomeValue;
			
		
		return $incomeValues;
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
		}
		return array('0'=>$minLabel,'1'=>$maxLabel);
	}
	/**
	 * 
	 *
	 * @param array $incomeRangeArr
	 * @return array
	 * @access public
	 */
	
	public function getIncomeText()
	{
		//global $INCOME_MAX_DROP,$INCOME_MIN_DROP;
		$INCOME_MIN_RS = FieldMap::getFieldLabel("lincome","",1);
		$INCOME_MAX_RS = FieldMap::getFieldLabel("hincome","",1);
		$INCOME_MIN_DOL = FieldMap::getFieldLabel("lincome_dol","",1);
		$INCOME_MAX_DOL = FieldMap::getFieldLabel("hincome_dol","",1);
		
		$minIR=$this->incomeArr["minIR"];
		$maxIR=$this->incomeArr["maxIR"];
		$minID=$this->incomeArr["minID"];
		$maxID=$this->incomeArr["maxID"];
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
	/*public function plus4_income($value,$type)
	{
		$dbObj = new NEWJS_INCOME();
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
	}*/
	
	/*public function createDropIncome($currency,$min,$max)
	{
		if($currency=='RUPEES')
		{
			$this->incomeArr["currency"]='R';
			$this->incomeArr["minIR"]=$min;
			$this->incomeArr["maxIR"]=$max;
		}
		else
		{
			$this->incomeArr["currency"]='D';
			$this->incomeArr["minID"]=$min;
			$this->incomeArr["maxID"]=$max;
		}
		$this->getMappedValues();
		$sort_arr = $this->getSortbyValues();
		$incomeArr=$this->makeIncomeRange($sort_arr);
		$incomeArrStr=implode(",",$incomeArr);
		return $incomeArrStr;
	}*/
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
	
	public function getSortedIncome($my_income='',$pid='',$gender='',$plus_minus_value='')
	{
		
		$income = $this->getIncomeDrop(array('VALUE'=>$my_income),null,array('SORTBY'=>''));	        
	    $income_sortby = $income['SORTBY'];
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
	
	public function incomeMapping()
	{
			$resultArr["doLIncome"]=$this->incomeArr['minID'];
			$resultArr["doHIncome"]=$this->incomeArr['maxID'];
			$resultArr["rsLIncome"]=$this->incomeArr['minIR'];
			$resultArr["rsHIncome"]=$this->incomeArr['maxIR'];
			
			if($this->incomeArr["currency"]!='B')
			{
				$this->getMappedValues();
			}			
			$sort_arr = $this->getSortbyValues();
			$incomeRangeArr  = $this->makeIncomeRange($sort_arr);
                        if($incomeRangeArr)
        		{
                		$resultArr["istr"]=implode("','",$incomeRangeArr);
                		$resultArr["istr"]="'".$resultArr["istr"]."'";
       			}	
        		else
                		$resultArr["istr"]='';
			if(isset($this->incomeArr['minIR'])  && isset($this->incomeArr['maxIR'])){
				$resultArr["doLIncome"]=$this->incomeArr["minID"];
				$resultArr["doHIncome"]=$this->incomeArr["maxID"];
			}
			if(isset($this->incomeArr['minID']) && isset($this->incomeArr['maxID'])){
				$resultArr["rsLIncome"]=$this->incomeArr["minIR"];
				$resultArr["rsHIncome"]=$this->incomeArr["maxIR"];
			}
			//If all values are null then make istr to null also
			if( !strlen($resultArr['doLIncome']) && !strlen($resultArr['doHIncome']) &&
				!strlen($resultArr['rsLIncome']) && !strlen($resultArr['rsHIncome']) )
			{
				$resultArr["istr"]=null;
			}	
		return $resultArr;
	}
	
	public function getTrendsSortBy($income)
	{	
		$incomeArr = $this->getIncomeDrop(array('VALUE'=>$income),null,array('TRENDS_SORTBY'=>''));	        
	    $trendSortBy = $incomeArr['TRENDS_SORTBY'];
	    return $trendSortBy;
	}
	
	public function getTrendsSortByRupeeLabel($trendSortByValue)
	{
		$incomeArr = $this->getIncomeDrop(array('TRENDS_SORTBY'=>$trendSortByValue,'TYPE'=>'RUPEES','VISIBLE'=>'Y'),null,array('LABEL'=>''));
		$label = $incomeArr['LABEL'];
		return $label;
	}

	public function getIncomeFromTrendsSortBy($income)
	{
		$incomeArr = $this->getIncomeDrop(array('TRENDS_SORTBY'=>$income,'VISIBLE'=>'Y'),null,array('VALUE'=>'','MIN_VALUE'=>'','MAX_VALUE'=>'','TYPE'=>'','SORTBY'=>''),'array');
		return $incomeArr;
	}
        
        /**
         * This function finds incomes lower than a particular value on the basis of income sort by
         * @param type $currentValue current income index
         * @return array of incomes
         */
        public function getLowerIncomes($currentValue){
                $incomeSortByArr=FieldMap::getFieldLabel("income_sortby",'',1);
                $currentOrder = $incomeSortByArr[$currentValue];
                $lowerArray = array();
                foreach($incomeSortByArr as $key=>$inc){
                        if($inc <= $currentOrder)
                                $lowerArray[] = $key;
                }
                return($lowerArray);
        }
        public function getImmediateLowerIncome($key,$currentValue){
                $incomeSortByArr=FieldMap::getFieldLabel($key,'',1);
                $higherArray = array();
                foreach($incomeSortByArr as $key=>$inc){
                        if($key == $currentValue){
                                break;
                        }else{
                                 $higherArray[] = $key;
                        }
                }
                if(count($higherArray)>0)
                        return(end($higherArray));
                
                return "";
        }
        public function getImmediateHigherIncome($key,$currentValue){
                $incomeSortByArr=FieldMap::getFieldLabel($key,'',1);
                krsort($incomeSortByArr);
                $higherArray = array();
                foreach($incomeSortByArr as $key=>$inc){
                        if($key == $currentValue){
                                break;
                        }else{
                                 $higherArray[] = $key;
                        }
                }
                if(count($higherArray)>0)
                        return(end($higherArray));
                
                return "";
        }
        /**
         * This function removes no income index from income array if array size is greater than 1
         * @param type $incomeArr array of incomes
         * @return income array without no income value
         */
	public function removeNoIncome($incomeArr){
                if(count($incomeArr)> 1){
                        if(($key = array_search(15, $incomeArr)) !== false) {
                                unset($incomeArr[$key]);
                        }
                }
                return $incomeArr;
        }
	
}
?>
