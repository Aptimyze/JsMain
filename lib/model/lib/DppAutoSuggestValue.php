<?php
/**
 *CLASS DppAutoSuggestValue
 * The AutoSuggestValue value to be inserted in Jpartner table when user register for the first time
 * ProfileFields Value should be same as that of Profile Class field values
 *<BR>
 * How to call this file<BR> 
 * <code>
    * $AutoSuggestValueValue=AutoSuggestValue::getAutoSuggestValue($field,$value,$profileObj)
* </code>
 * PHP versions 4 and 5
 * @package   jeevansathi
 * @subpackage   registration Revamp
 * @author   Nitesh
 * @copyright 2013 
  */
class DppAutoSuggestValue
{
	/**
	* Return auto Suggested Value for a particular field
	* @return string
	 * @profileidobj profile obj of user
	 * @field field name
	 * @fieldID field Id
	 * @return $jpartnerObj jpartner of profile
	 */
	public static function getAutoSuggestValue($field,$fieldId,$profileObj)
	{
		$dppData = DppAutoSuggestEnum::$AUTO_SUGGEST_ARRAY;
		$field="get".$field;
		$value= call_user_func(array($profileObj,$field));
		//REmoving looping of array 
		if($dppData[$fieldId][$value][0])
			return $dppData[$fieldId][$value][0];
		elseif($dppData[$fieldId])
		{
			foreach($dppData[$fieldId] as $k=>$value)
			{
				if($value[1])
					$result = call_user_func_array(array('DppAutoSuggestValue',$value[1]), array($profileObj));
				return $result;
			}
		}
		else
			return;
		/*foreach($dppData as $key=>$val)
		{
			foreach($val as $k=>$v)
			{
				
				if($key==$fieldId)
				{
					
					if($k)
				   {
					   
					   if($k==$value && $v)
					   {
						   return $v[0];
					   }
					   
				   }
					elseif($v[1])
					   {
						 $result = call_user_func_array(array('DppAutoSuggestValue', $v[1]), array($profileObj));
						 return $result;
					   }
					   else
					   return;
						
				}
			}
					
		}*/
		
	}
	
	/**
	* Return auto MANGLIK Auto Suggest Value for MANGLIK field
	* @return string
	 * @profileidobj profile obj of user
	 */
	
	static function  MANGLIKAutoSuggest($profileObj)
	{
		$manglik=$profileObj->getMANGLIK();
		$horoscope=$profileObj->getHOROSCOPE_MATCH();
		$arr=DppAutoSuggestEnum::$MANGLIK_ARRAY;
		
		foreach($arr as$K=>$V)
		{
			foreach($V as $k=>$v)
			{
				if($K==$manglik)
					if($k==$horoscope)
						return $v;
			}
		}			
	}
	
	/**
	* Return auto AGE Auto Suggest Value for AGE field
	* @return Array containg Lower Age and Upper Age as Key value Pair respectively
	 * @profileidobj profile obj of user
	 */
	
	static function  AGEAutoSuggest($profileObj)
	{
		$arr=DppAutoSuggestEnum::$AGE_ARRAY;
		$age=$profileObj->getAGE();
		$mstatus=$profileObj->getMSTATUS();
                if($mstatus == "")
                  $mstatus="N";
		if($mstatus!="N")
			$mstatus="E";
		$gender=$profileObj->getGENDER();
			foreach($arr as$K=>$V)
				{
					foreach($V as $k=>$v)
					{
						foreach($v as $key=>$val)
						{
							if($K==$gender)
								if($k==$mstatus)
									if($key==$age)
										return $val;
						}
					}
				}
	
	}
	
	/**
	* Return auto HEIGHT Auto Suggest Value for HEIGHT field
	* @return Array containg Lower HEIGHT and Upper HEIGHT as Key value Pair respectively
	 * @profileidobj profile obj of user
	 */
	static function HEIGHTAutoSuggest($profileObj)
	{
		$arr=DppAutoSuggestEnum::$HEIGHT_ARRAY;
		$height=$profileObj->getHEIGHT();
		$gender=$profileObj->getGENDER();
			foreach($arr as$K=>$V)
				{
					foreach($V as $k=>$v)
					{
							if($K==$gender)
								if($k==$height)
										return $v;
					}
				}
	}
	
	/**
	* Return NULL for doesnt matter fields
	* @return NULL
	 * @profileidobj profile obj of user
	 */
	
	static function CommonAutoSuggest($profileObj)
	{
		return ;
	}
	/**
	* Return HANDICAPPED Auto Suggest for AUTO SUGGEST field
	* @return string
	 * @profileidobj profile obj of user
	 */
	static function HANDICAPPEDAutoSuggest($profileObj)
	{
		if($profileObj->getHANDICAPPED()=="N")
			return "N";
		elseif($profileObj->getHANDICAPPED())
			return DppAutoSuggestEnum::$HANDICAPPED;
		else
			return;
	}
	/**
	* Return auto INCOME Auto Suggest Value for INCOME field
	* @return Array containg Lower INCOME and Upper INCOME as Key value Pair respectively
	 * @profileidobj profile obj of user
	 */
	static function INCOMEAutoSuggest($profileObj)
	{
		$arr=DppAutoSuggestEnum::$INCOME_ARRAY;
		$income=$profileObj->getINCOME();
		$gender=$profileObj->getGENDER();
		
		foreach($arr as$K=>$V)
		{
			foreach($V as $k=>$v)
			{
				foreach($v as $key=>$val)
				{
					if($K==$gender)
						if($k==$income)
							{
								$rsArray=array('minIR'=>$key,'maxIR'=>$val);
								$incomeMapping= new IncomeMapping($rsArray);
								return $incomeMapping->incomeMapping();								
							}
				}
			}
		}
	}
	
	/**
	* Return auto RELIGION Auto Suggest Value for RELIGION field
	* @return string
	 * @profileidobj profile obj of user
	 */
	static function ReligionAutoSuggest($profileObj)
	{
		$arr=DppAutoSuggestEnum::$RELIGION_ARRAY;
		$caste=$profileObj->getCASTE();
		return ($arr[$caste]["RELIGION"]);

	}
	

	public static function getDppSuggestionsForFilledValues($field,$fieldId,$fieldValue)
	{
		$dppData = DppAutoSuggestEnum::$AUTO_SUGGEST_ARRAY;
		if($dppData[$fieldId][$fieldValue][0])
			return $dppData[$fieldId][$fieldValue][0];
		else
			return;
	}
}
