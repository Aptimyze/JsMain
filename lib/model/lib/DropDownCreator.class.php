<?php

class DropDownCreator
{
	static function createDD($field,$selectedValue,$options="",$withPleaseSelect="",$isEditParam=0)
	{
		switch($field){
			case "caste":
				$dropdownString=caste_populate_religion($options,$selectedValue);
				break;
			case "caste_hindu":
				$dropdownString=caste_populate_religion(Religion::HINDU,$selectedValue);
				break;
			case "caste_jain":
				$dropdownString=caste_populate_religion(Religion::JAIN,$selectedValue);
				break;
			case "caste_christian":
				$dropdownString=caste_populate_religion(Religion::CHRISTIAN,$selectedValue);
				break;
			case "caste_muslim":
				$dropdownString=caste_populate_religion(Religion::MUSLIM,$selectedValue);
				break;
			case "caste_sikh":
				$dropdownString=caste_populate_religion(Religion::SIKH,$selectedValue);
				break;
			case "caste_parsi":
				$dropdownString=caste_populate_religion(Religion::PARSI,$selectedValue);
				break;
			case "Country_Residence":
				$dropdownString=create_dd($selectedValue,"Country_Residence");
				break;
			case "Country_Birth":
				$dropdownString=create_dd($selectedValue,"Country_Birth");
			case "Height":
				$dropdownString=create_dd($selectedValue,"Height");
				break;
			case "sampraday":
			case "maththab_sunni":
			case "maththab_shia":
			case "namaz":
			case "fasting":
			case "quran":
			case "umrah_hajj":
			case "sunnah_cap":
			case "sunnah_beard":
			case "sibling":
			case "family_background":
			case "mother_occupation":
			case "occupation":
			case "degree_ug":
			case "degree_pg":
			case "work_status":
			case "blood_group":
			case "nature_handicap":
			case "rstatus":
			case "sect_hindu":
			case "sect_jain":
			case "sect_muslim":
			case "sect_sikh":
			case "sect_buddhist":
			case "sunsign":
			case "manglik_label":
			case "rashi":
			case "relationship":
			case "mstatus":
			case "children_ascii_array":
			case "state_india":
				$dropdownString=DropDownCreator::createOptStringFromField($field,$selectedValue,"",$withPleaseSelect);
				break;
			case "country":
			case "native_city":
				$dropdownString = DropDownCreator::createDropDownFromFieldOrdering($field,$selectedValue,$options,"Others (please specify)");
				break;
			case "mtongue":
				$dropdownString=create_dd($selectedValue,"Mtongue","","","Y");
				break;
			case "income_level":
				$dropdownString=create_dd($selectedValue,"Income");
				break;
			case "education":
				$dropdownString=create_dd($selectedValue,"Education_Level_New");
				break;
			case "city_for_country":
				$dropdownString=create_dd($selectedValue,"city_for_country",0,$options);
				break;
			case "cityAndCountry":
				$dropdownString=countryAndCityDropDown($selectedValue,$isEditParam);
				break;
			case "year":
				$dropdownString=DropDownCreator::dropdownForYear($selectedValue,50);
				break;
			default:
				throw new DropDownException("The dropdown field: $field has no dropdown creator defined.");
		}
		return $dropdownString;
	}
	public static function createOptStringFromField($fieldName,$selectedValue,$optGroupVals="",$withPleaseSelect="")
	{
		$mapArray=FieldMap::getFieldLabel($fieldName,"",1);
		if($withPleaseSelect){
			if($selectedValue==='')
				$optString="<option value=\"\">Please Select</option>\n";
			else
				$optString="<option value=\"\" selected>Please Select</option>\n";
		}
		
		foreach($mapArray as $value => $label){
			if("$value"==="$selectedValue")
				$optString.="<option value=\"$value\" selected>$label</option>\n";
			else
				$optString.="<option value=\"$value\">$label</option>\n";
		}
		return $optString;
	}
	public static function createDropDownFromFieldOrdering($fieldName,$selectedValue,$dependentValue,$szCustomOtherLabel='')
	{
		$Obj=new FieldOrder($szCustomOtherLabel);
	
		$Obj->setDefault(strtolower($fieldName),array($dependentValue),"","");
		$Obj->UpdateSelect();
		$choices=$Obj->getArray();
	
		foreach($choices as $value => $label){
			if(is_array($label))
			{
				$optString.=self::getOptGroupString($label,$selectedValue,$value);
			}
			elseif(is_string($label))
			{
				if("$value" === "$selectedValue")
					$optString.="<option value=\"$value\" selected>$label</option>\n";
				else
					$optString.="<option value=\"$value\">$label</option>\n";
			}
		}
		return $optString;
		
	}
	
	private static function getOptGroupString($arrChoices, $szSelectedValue,$szLabel)
	{
		$optString= "<optgroup label=\"$szLabel\">";
		foreach($arrChoices as $value => $label){
			if("$value" === "$szSelectedValue")
				$optString.="<option value=\"$value\" selected=selected>$label</option>\n";
			else
				$optString.="<option value=\"$value\">$label</option>\n";
		}
		$optSting.= "</optgroup>";
		
		return $optString;
	}
	public static function createRadioStringFromField($fieldName,$selectedValue,$elementName,$class="",$numberOfSpaces=1,$script_orig="")
	{
		if($numberOfSpaces){
		$NumberOfCharInSpaceString=6*$numberOfSpaces+1;
		while($numberOfSpaces>0){
			$numberOfSpaces--;
			$spcString.="&nbsp;";
		}
		}
		else{//If numberOfSpaces is 0 then insert newline between radio buttons
			$spcString="<br>";
			$NumberOfCharInSpaceString=5;
		}
		$mapArray=FieldMap::getFieldLabel($fieldName,"",1);
		$id=1;
		foreach($mapArray as $value=> $label){
			$script=str_replace("value",$value,$script_orig);
			$id_name="$elementName"."$id";
			if($value==$selectedValue)
				$radioString.="<input type=\"radio\"  id=\"$id_name\" value=\"$value\" name=\"$elementName\" class=\"$class\" checked $script> $label $spcString\n";
			else
				$radioString.="<input type=\"radio\"  id=\"$id_name\" value=\"$value\" name=\"$elementName\" class=\"$class\" $script> $label $spcString\n";
			$id++;
	}
		//Remove spaces from last radio button
		$radioString=substr($radioString,0,-$NumberOfCharInSpaceString);
		return $radioString;
	}
	public static function dropdownForYear($selectedValue,$numberOfYears)
	{
		$currentYear=date('Y');
		$initYear=$currentYear-$numberOfYears;
		if(!$selectedValue)
			$optionString="<option value=\"\" selected>Year</option>";
		while($currentYear  > $initYear){
			if($selectedValue==$currentYear)
				$optionString.="<option value=\"$currentYear\" selected ~/if`>$currentYear</option>\n";
			else $optionString.="<option value=\"$currentYear\">$currentYear</option>\n"; 
			$currentYear--;
		}
		return $optionString;
	}
}

?>
