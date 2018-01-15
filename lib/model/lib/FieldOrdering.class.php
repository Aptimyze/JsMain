<?php

/**
 * Auto Select actions.
 *
 * @package    jeevansathi
 * @subpackage profile
 * @author     Nikhil dhiman
 * @version    SVN: $Id: actions.class.php 23810 2011-07-14 03:07:44 Nikhil dhiman $
 */
/**
 * Auto Select feature.<p></p>
 * 	
 *  
 * @author Nikhil dhiman
 */

class FieldOrder 
{
	/**
	* Executes index action
	*
	* @param sfRequest $request A request object
	*/
	private $_type;
	private $_default;
	private $_linked;
	private $_query;
	private $_json;
	private $defaultExist;
	
	public function __construct($szCustomOther="")
	{
		$this->defaultOthers = "Others";
		if(strlen($szCustomOther)!=0)
		{
			$this->defaultOthers = $szCustomOther;
		}
	}
	
	public function getJson()
	{
		return $this->_json;
	}
	
	public function setDefaultExist($bool)
   {
		$this->defaultExist=$bool;
   } 
	
	public function setDefault($type="",$linked="",$query="",$def="")
	{
//		setDefault($type,$linked,$query,$def)
		$this->_type=$type;
		$this->_linked=$linked;
		$this->_query=$query;
		$this->_default=$def;
		$this->defaultExist=0;
	}
	/**
	 * 
	 */
	public function UpdateSelect()
	{
		
		if(in_array($this->_type,array("country","city","community","caste","religion","age","height","P_LRS","P_HRS","P_LDS","P_HDS","stdcode","isdcode","impcaste",'edu_level_new','state',"native_city")))
		{
			if($this->_type=="impcaste")
				$this->getImpCaste();
			if($this->_type=='country')
				$this->getCountry();
			if($this->_type=='city')
				$this->getCity();
			if($this->_type=='community')
				$this->getMtongue();
			if($this->_type=='caste')
				$this->getCaste();
			if($this->_type=='religion')
				$this->getReligion();
			if($this->_type=="age")
					$this->getAge();
			if($this->_type=="P_LRS")
					$this->getIncome(0);
			if($this->_type=="P_HRS")
					$this->getIncome(1);
			if($this->_type=="P_LDS")
					$this->getIncome(2);
			if($this->_type=="P_HDS")
					$this->getIncome(3);
			if($this->_type=="height")
					$this->getHeight();
			if($this->_type=="stdcode")
					$this->getSTDCode();
			if($this->_type=="isdcode")
					$this->getISDCode();					
			if($this->_type=="edu_level_new")
					$this->getDegree();					
			if($this->_type=="state")
					$this->getState();	
			if($this->_type=="native_city")
					$this->getNaitveCity();	
			if(!$this->defaultExist && $this->_json && $this->NoSelect())
			{
					$this->UpdateJson($this->getDefaultText($this->_type),"",0,1,1);
			}		
		}
		else
			throw new JsException("","Type not valid in auto select","");
	}
	private  function NoSelect()
	{
		return true;
		if(in_array($this->_type,array("impcaste")))
			return false;
			
	}
	public function getImpCaste()
	{
		$casteArr=FieldMap::getFieldLabel("caste",'',1);
		$arr=DppAutoSuggestEnum::$IMP_CASTE_ARR;
		foreach($arr as $key=>$val)
		{
			foreach($val as $kk=>$vv)
			{
				if(!$temp[$key][$kk])
				{
					
					$temp[$key][$kk]=1;
					if($key && $kk)
						$this->UpdateJson($kk,$key,preg_replace('/[A-Z][a-z]{3,10}[:][ ]/',"",$casteArr[$kk]),0);
				}	
			}
		}
		$this->_type="";
	}
	public function getAge()
	{
		$profileObj=LoggedInProfile::getInstance();
		
			if($profileObj->getGENDER()=="F")
				$st=21;
			else
				$st=18;
			$this->UpdateJson($st,$st,0,1);
			$st++;
			for($i=$st;$i<=70;$i++)
			{
				$this->UpdateJson($i,$i,0,0);
			}
	}
	public function getHeight()
	{
			$arr=FieldMap::getFieldLabel("height",'',1);
			$st=0;
			foreach($arr as $key=>$val)
			{
				if(!$st)
					$this->UpdateJson($val,$key,0,1);
				else
					$this->UpdateJson($val,$key,0);
				$st++;	
			}
	}
	public function getIncome($whichOne)
	{
		$this->_type="";
		$incomeArr=array("lincome","hincome","lincome_dol","hincome_dol");
		$arr=FieldMap::getFieldLabel($incomeArr[$whichOne],'',1);
		foreach($arr as $key=>$val)
		{
			if($val)
			$this->UpdateJson($val,$key,0);
		}
	}
	public function getCaste()
	{
		if(!is_array($this->_linked))
			return;
		$arr=FieldMap::getFieldLabel("religion_caste",'',1);
		foreach($arr as $key=>$val)
		{
			if(!in_array($key,$this->_linked))
				unset($arr[$key]);
			else
			$finalArr[]=$arr[$key];
		}
		
		if(is_array($finalArr))
		{
			foreach($finalArr as $key=>$val)
			{
				$valArr=explode(",",$val);
				$casteArr=FieldMap::getFieldLabel("caste",'',1);
				foreach($valArr as $kk=>$vv)
				{
					$caste=$casteArr[$vv];
					
					$caste=preg_replace('/[A-Z][a-z]{3,10}[:][ ]/',"",$caste);
					$this->UpdateJson($caste,$vv,0);
				}	
			}
		}
	}
	public function getCountry()
	{
			$arr=FieldMap::getFieldLabel("impcountry",'',1);
			$this->UpdateJson(" ","",1,0);
			foreach($arr as $key=>$val)
				$this->UpdateJson($val,$key,0);
			$this->UpdateJson("-----","",1,0);
			$arr=FieldMap::getFieldLabel("country",'',1);
			foreach($arr as $key=>$val)
			{
				$this->UpdateJson($val,$key,0);
			}
	}
	public function getState()
	{
			$arr=FieldMap::getFieldLabel("state_india",'',1);
			foreach($arr as $key=>$val)
				$this->UpdateJson($val,$key,0);	
	}
	public function getDegree()
	{
		$edu_arr=FieldMap::getFieldLabel("education_grouping",'',1);
		$result_arr=array(""=>"Please Select");
		foreach($edu_arr as $key=>$val)
		{
			if($key!=10){
			 $regionArr=explode(",",FieldMap::getFieldLabel("education_grouping_mapping_to_edu_level_new",$key));
			 $eduArr=FieldMap::getFieldLabel("education",'',1);
			 foreach($regionArr as $kk=>$vv)
				{
					$result_arr[$val][$vv]=$eduArr[$vv];
				}
			 $blank_lbl.="&nbsp;";
				 $result_arr[$blank_lbl]=array();
			}
		}
	    $result_arr["22"]="Other";
		return $result_arr;
	}
	
	public function getIncomeGroup()
	{
		$income_arr=FieldMap::getFieldLabel("income_grouping",'',1);
		$result_arr=array(""=>"Please Select");
		foreach($income_arr as $key=>$val)
		{
			 $regionArr=explode(",",FieldMap::getFieldLabel("income_grouping_mapping",$key));
			 $incomeArr=FieldMap::getFieldLabel("income_level",'',1);
			 foreach($regionArr as $kk=>$vv)
				{
					$result_arr[$val][$vv]=$incomeArr[$vv];
				}
			 $blank_lbl.="&nbsp;";
			 $result_arr[$blank_lbl]=array();
	
		}
		return $result_arr;
	}
	
	public function getHeight_page1()
	{
		$heightArr=FieldMap::getFieldLabel("height",'',1);
		$result_arr=array(""=>"Please Select");
		foreach($heightArr as $key=>$val)
		{
			 $result_arr[$key]=$val;
			 if($key==12 || $key ==24 || $key ==36){
				 $blank_lbl.="&nbsp;";
				 $result_arr[$blank_lbl]=array();
			 }
		}
		return $result_arr;
	}
	public function getReligion()
	{
			$arr=FieldMap::getFieldLabel("religion",'',1);
			foreach($arr as $key=>$val)
			{
				if($key!=8)
				$this->UpdateJson($val,$key,0);
			}
	}
	public function getCity()
	{
			
		if(!is_array($this->_linked))
			return;
		$arr=FieldMap::getFieldLabel("topindia_city",'',1);
		$topCity=$arr[51];
		
		$arr=FieldMap::getFieldLabel("country_city",'',1);
		foreach($arr as $key=>$val)
		{
			if(!in_array($key,$this->_linked))
				unset($arr[$key]);
			else
			{
				if($key==51)
					$arr[$key]="$topCity,separator,".$arr[$key];
				$finalArr[]=$arr[$key];
			}	
		}
		//default Top cities
		
		if(is_array($finalArr))
		{
			$this->UpdateJson(" ","",1,0);
			foreach($finalArr as $key=>$val)
			{
				$valArr=explode(",",$val);
				$cityArr=FieldMap::getFieldLabel("city_india",'',1);
				foreach($valArr as $kk=>$vv)
				{
					if($vv=="separator")
						$this->UpdateJson("-----","",1,0);
					else
					{
						$city=$cityArr[$vv];
						//$caste=preg_replace('/[A-Z][a-z]{3,10}[:][ ]/',"",$caste);
						$this->UpdateJson($city,$vv,0);
					}
				}	
			}
		}
	
		$this->UpdateJson($this->defaultOthers,0,0);
	}
	
	public function getNaitveCity()
	{
		$arrCity=FieldMap::getFieldLabel("city_india",'',1);
		
		ksort($arrCity);
		$arrFinalOut = array();
		foreach($arrCity as $key=>$val)
		{
			if(strlen($key)===2)
			{
				$currentKey = $key;
				$arrFinalOut[$currentKey] = array(); 
			}
			else
			{
				$arrFinalOut[$currentKey][$key] = $val;
			}
		}
		

		if(is_array($this->_linked) && is_array($arrFinalOut[$this->_linked[0]]))
		{
			asort($arrFinalOut[$this->_linked[0]]);
			foreach($arrFinalOut[$this->_linked[0]] as $key=>$val)
			{
				$this->UpdateJson($val,$key,0);	
			}
		}
		
		if(is_array($this->_linked) && $this->_linked[0] == "all")
		{
			foreach($arrFinalOut as $cityKey=>$subCity)
			foreach($arrFinalOut[$cityKey] as $key=>$val)
			{
				$this->UpdateJson($val,$key,$cityKey);	
			}
		}
		//For the case of ajax calls
		if($this->defaultOthers === "Others")
			$this->defaultOthers .=  " (please specify)";
	
		$this->UpdateJson($this->defaultOthers,0,0);
	}
	function getMtongue()
	{
		$mregion=FieldMap::getFieldLabel("mtongue_region_label",'',1);
		$result_arr=array(""=>"Please Select");
	    $blank_lbl.="&nbsp;";
	    $result_arr[$blank_lbl]=array();
		
		foreach($mregion as $key=>$val)
		{
			$this->UpdateJson($val,"",1);
			 $regionArr=explode(",",FieldMap::getFieldLabel("mtongue_region",$key));
			 if(MobileCommon::isMobile())
				$mtongueArr=FieldMap::getFieldLabel("reg_community_small",'',1);
			else
				$mtongueArr=FieldMap::getFieldLabel("reg_community",'',1);
			 //unset foreign origin value
			 unset($mtongueArr[1]);
			 foreach($regionArr as $kk=>$vv)
				{
					if($mtongueArr[$vv])
						if($val!='Others')
						 $result_arr[$val][$vv]=$mtongueArr[$vv];
					    else
						 $result_arr['---------'][$vv]=$mtongueArr[$vv];
				}
			if($val!='Others'&&$val!='East'){ $blank_lbl.="&nbsp;";
			 $result_arr[$blank_lbl]=array();
			}
		}
		return $result_arr;
	}
	function UpdateJson($label,$value,$optLabel,$force_default=0,$topOrder=0)
	{
		$st=count($this->_json);
		if(!$st)
			$st=0;
		
		$def=0;	
		if(is_null($this->_default))$this->_default=array();
		if(in_array($value,$this->_default?$this->_default:array()) || $force_default)
		{
				$def=1;
				$this->defaultExist=1;
		}
		if($topOrder)
		{
			$json=$this->_json;
			$this->_json=null;
			
			$tt=0;
			$this->_json[$tt][0]=$value;
			$this->_json[$tt][1]=$label;
			$this->_json[$tt][2]=$def;
			$this->_json[$tt][3]=$optLabel;
			
			$tt++;
			foreach($json as $key=>$val)
			{
				
				$this->_json[$tt]=$json[$key];
				
				$tt++;
			}
			
		}
		else
		{
			$this->_json[$st][0]=$value;
			$this->_json[$st][1]=$label;
			$this->_json[$st][2]=$def;
			$this->_json[$st][3]=$optLabel;
		}	
	}
	function getArray($type="")
	{
		if($type)
		{	
			$this->setDefault($type);
			$this->UpdateSelect();
		}	
		$default=false;
		for($i=0;$i<count($this->_json);$i++)
		{
			
			if($this->_json[$i][3])
			{
				$optGroup=$this->_json[$i][1];
				$default=true;
			}
			else
			{
				if($default)
					$array[$optGroup][$this->_json[$i][0]]=$this->_json[$i][1];
				else
					$array[$this->_json[$i][0]]=$this->_json[$i][1];
			}
		}
		return $array;
	}
	public function getSTDCode()
	{
		if(!is_array($this->_linked))
			return;
		$valueArr = $this->_linked;	
		$stdcode =RegFields::getPageFields("stdcodes",$valueArr[0]);
		$this->UpdateJson($stdcode,$this->_linked,0,1);
	}
	
	public function getISDCode()
	{
		if(!is_array($this->_linked))
			return;
		$valueArr = $this->_linked;	
		$isdcode =RegFields::getPageFields("isdcode",$valueArr[0]);
		$this->UpdateJson($isdcode,$this->_linked,0,1);
	}
	
	private function getDefaultText($type)
	{
		switch($type)
		{
			case 'community':
				return "Select a Mother Tongue";
			case 'religion':
				return "Select a Religion";
            case 'country':
                return "Select Country";
            case 'height':
				return "Select Height";
			case 'state':
				return "Select state";
			 default:
			    return "Please select ".strtolower($type);
		}
	}
					
}
?>
