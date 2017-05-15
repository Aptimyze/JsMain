<?php
class FormInputFactory{
	static function getInputObject($field,$page){
		switch($field->getFieldType()){
			case "dropdown":
				
				$stopDefaultEntry=array("CASTE");
				if(!MobileCommon::isMobile())
				$stopDefaultEntry=array("CASTE");
				if(!in_array($field->getName(),$stopDefaultEntry))
				{
					if($field->getNAME()=="COUNTRY_RES" || $field->getNAME()=="NATIVE_COUNTRY" )
					{
						$Obj=new FieldOrder;
						$choices=$Obj->getArray("country");
					}
					if($field->getNAME()=="CITY_RES")
					{
						$Obj=new FieldOrder;
						$choices=$Obj->getArray("city");
					}
					if($field->getNAME()=="NATIVE_CITY")
					{
						$Obj=new FieldOrder("Others (please specify)");
						$choices=$Obj->getArray("native_city");
					}
					if($field->getNAME()=="P_LAGE" || $field->getNAME()=="P_HAGE")
					{
						$Obj=new FieldOrder;
						$choices=$Obj->getArray("age");
						
					}
					else if($field->getNAME()=="P_LHEIGHT" || $field->getNAME()=="P_HHEIGHT")
					{
						$Obj=new FieldOrder;
						$choices=$Obj->getArray("height");
						
					}
					else if(in_array($field->getName(),array("P_LRS","P_HRS","P_LDS","P_HDS")))
					{
						$Obj=new FieldOrder;
						$choices=$Obj->getArray($field->getName());
					}
					else if($field->getName()=="CITY_RES")
					{
						
						$Obj=new FieldOrder;
						$Obj->setDefault("city",array(51),"","");
						$Obj->UpdateSelect();
						$choices=$Obj->getArray();
					}
					else if($field->getName()=="COUNTRY_RES" || $field->getNAME()=="NATIVE_COUNTRY")
					{
						$Obj=new FieldOrder;
						$choices=$Obj->getArray("country");
					}
					else if($field->getName()=="NATIVE_STATE")
					{
						$Obj=new FieldOrder;
						$choices=$Obj->getArray("state");
					}
					else if($field->getName()=="MTONGUE")
					{
						$Obj=new FieldOrder;
						$choices=$Obj->getMtongue("community");
					}
					else if($field->getName()=="RELIGION")
					{
						$Obj=new FieldOrder;
						$choices=$Obj->getArray("religion");
						
					}
					else if($field->getName()=="EDU_LEVEL_NEW"){
						$Obj=new FieldOrder;
						$choices=$Obj->getDegree();
					}
					else if($field->getName()=="INCOME"){
						$Obj=new FieldOrder;
						$choices=$Obj->getIncomeGroup();
                    }
					else if($field->getName()=="HEIGHT"){
						$Obj=new FieldOrder;
						$choices=$Obj->getHeight_page1();
					}
                                        else if($field->getName()=="SECT_MUSLIM"){
						$Obj=new FieldOrder;
						$choices=$Obj->getSectMuslim();
                                                if($field->getBlankLabel()){
                                                    $blankArr[$field->getBlankValue()]=$field->getBlankLabel();
                                                    $choices = $blankArr+$choices;
						}
					}
					else{
						//For muslim shia and sunni sects, maththab dropdown is different
						//So need to add conditions here
						if($field->getName()=='MATHTHAB'){
				//TODO			$profileObj=LoggedInProfile::getInstance();
				//TODO			$caste=$profileObj->getCASTE();
							//151 - Muslim -Shia
							$caste=152;
							if($caste==151)
								$field_map_name='maththab_shia';
							//152 - Muslim -Sunni
							if($caste==152)
								$field_map_name='maththab_sunni';
						}else
							$field_map_name=ObjectiveFieldMap::getFieldMapKey($field->getName(),$page);
						//get all dropdown values from Fieldmaplib
						if($field_map_name)
                                                    $choices=FieldMap::getFieldLabel($field_map_name,'',1);
					if($field->getBlankLabel()){
						$blankArr[$field->getBlankValue()]=$field->getBlankLabel();
						$choices = $blankArr+$choices;
						}
				}
						return new sfWidgetFormChoice(array("choices"=>$choices,"label"=>$field->getLabel()));
				}
				else
					return new sfWidgetFormChoice(array("choices"=>"","label"=>$field->getLabel()));
				break;
			case "radio":
				if(!MobileCommon::isMobile())
					return new sfWidgetFormSelectRadio(array("choices"=>FieldMap::getFieldLabel(ObjectiveFieldMap::getFieldMapKey($field->getName()),'',1),"label"=>$field->getLabel(),'separator'=>'&nbsp;&nbsp;&nbsp;'));
				else
					return new sfWidgetFormSelectRadio(array("choices"=>FieldMap::getFieldLabel(ObjectiveFieldMap::getFieldMapKey($field->getName()),'',1),"label"=>$field->getLabel(),'type'=>'mobile'));
				break;
			case "password":
				return new sfWidgetFormInputPassword(array("label"=>$field->getLabel()));
				break;
			case "checkbox":
				return new sfWidgetFormInputCheckbox(array('value_attribute_value' => 'single', "label"=>$field->getLabel()));
					break;
			case "hidden":
				return new sfWidgetFormInputHidden();
				break;
			case "text":
				return new sfWidgetFormInputText(array("label"=>$field->getLabel()));
				break;
			case "date":
				$years=range(date('Y')-17,date('Y')-70);
				$month_num=range(1,12);
				$days=range(1,31);
				$month_str=array('Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec');
				return new sfWidgetFormDate(array("label"=>$field->getLabel(),'days'=>array_combine($days,$days),'years'=>array_combine($years, $years),'months'=>array_combine($month_num,$month_str),'empty_values'=>array('year' => 'Year', 'month' => 'Month', 'day' => 'Day'),'format'=>'%day% %month% %year%'));
				break;
			case "mobile":
				return new jsWidgetFormMobile(array("label"=>$field->getLabel()));
				break;
			case "landline":
				return new jsWidgetFormMobile(array("label"=>$field->getLabel(),'format'=>'%isd% %std% %landline%'));
				break;
			case "textarea":
				return new sfWidgetFormTextarea(array("label"=>$field->getLabel()));
				break;
			case "p_age":	
				return new jsWidgetFormPartnerAge(array("label"=>$field->getLabel()));
		}
	}
}
