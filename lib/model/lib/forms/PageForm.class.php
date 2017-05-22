<?php
class PageForm extends sfForm
{
	private $page_obj;
	public $formValues;
	public $lengthArr = array('YOURINFO'=>'3000','FAMILYINFO'=>'1000','JOB_INFO'=>'1000','EDUCATION'=>'1000','CONTACT'=>'1000','EMAIL'=>'100','PASSWORD'=>'40','USERNAME'=>'40','MESSENGER_ID'=>'255','SPOUSE'=>'1000','SUBCASTE'=>'250','GOTHRA'=>'250','ANCESTRAL_ORIGIN'=>'100');
	function __construct($defaults = array(), $options = array(), $CSRFSecret = null)
	{
		parent::__construct($defaults, $options, $CSRFSecret);
	}
	public function configure()
	{
	  $page = $this->getOption('page');
	  $request = $this->getOption('request');
	  $this->page_obj=RegEditFields::getPageFields($page);
	  $field_array=RegEditFields::getFieldArray($page);
	  $fields=$this->page_obj->getFields();
	  $widgets=array();
	  foreach($fields as $field){
		  //Set all fields in this form
		  $widgets[strtolower($field->getNAME())]=FormInputFactory::getInputObject($field,$page);
		  //Set all Validators in this form
		  if($request)
			$validators[strtolower($field->getNAME())]=ValidatorsFactory::getValidator($field,$request->getParameter('reg'),$page);
		  else
			$validators[strtolower($field->getNAME())]=ValidatorsFactory::getValidator($field,"",$page);
	  }
	  $this->setWidgets($widgets);
	  $this->widgetSchema->setNameFormat('reg[%s]');
	  $this->setValidators($validators);
	}
	public function getPageObject(){
	  return $this->page_obj;
	}
	
	/* update or insert data into table
	* takes profileid as input, and if entry is done first time, no profieid is needed
	* @returns LoggedInProfile object if it is created otherwise return id of entry that was updated
	* */
	public function updateData($profileid='',$values_that_are_not_in_form=array()){
	  $this->formValues=$this->getValues();
 	  if(in_array("casteNoBar", array_keys($this->formValues)))
 	  {
 	  	unset($this->formValues['casteNoBar']);	  	
 	  }
	  $haveJeduArr = array("SCHOOL","COLLEGE","OTHER_UG_DEGREE","OTHER_PG_DEGREE","PG_COLLEGE","PG_DEGREE","UG_DEGREE");
	  foreach($this->formValues as $field_name=>$value){
		  if($value!==null){
		  $field_name=strtoupper($field_name);
		  $field_obj=$this->page_obj->getFieldByName($field_name);
		  $table_name_arr=explode(":",$field_obj->getTableName());
		  $table_name=$table_name_arr[0];
		  $column_name_arr=explode(",",$table_name_arr[1]);
		  if(count($column_name_arr)==1)
		  {
			  //Normal case - only one column to be updated for one form widget
			  $column_name=$column_name_arr[0];
			  //If column_name is relation then values are to be changed in Mobile reg page and JPROFILE tables
			  if($column_name=='RELATION'){
				}  
			  if($value && in_array($column_name,$haveJeduArr))
				$haveJedu="Y";
			  switch($table_name){
				 case "JPROFILE":
					if(array_key_exists($column_name,$this->lengthArr)  && $value)
					{
						if($column_name!="PASSWORD")
							$value=htmlentities($value);
						
						$jprofileFieldArr[$column_name]= substr($value,0,$this->lengthArr[$column_name]);
					}
					else{
						if($column_name=='RELATION'){
							$value=$this->cleanGender($value);
						}
					 	$jprofileFieldArr[$column_name]=$value;
					}
					 break;
				 case  "JPROFILE_EDUCATION":
					 $jprofileEducationArr[$column_name]=$value;
					 break;
				  case "JPROFILE_CONTACT":
					  $jprofileContactArr[$column_name]=$value;
					  break;
				  case "REG_LEAD":
					  $leadArr[$column_name]=$value;
					  break;
				  case "REGISTRATION_PAGE1":
						if($column_name=='RELATION'){
							$value=$this->cleanGender($value);
						}
					  $mobile_reg[$column_name]=$value;
					  break;
				  case "NATIVE_PLACE":
                                                $nativePlaceArr[$column_name] = $value;
                                                if($column_name=="NATIVE_COUNTRY"&&$value!='51')
                                                {
                                                        $nativePlaceArr['NATIVE_CITY']='';
                                                        $nativePlaceArr['NATIVE_STATE']='';
                                                }
					  break;
                                 case "NAME_OF_USER":
						$nameOfUserArr[$column_name] = trim($value);
					  break;
			  }
			  //Handle religion related fields here as religion table names start with JP_
			  if(strpos($table_name,"JP_")!==false && $value){
				  //for maththab there are certain changes in values do following
				  if($column_name=='MATHTHAB'){

					  $maththab_exceptions=array('150'=>'8','254'=>'1','255'=>'2','256'=>'3','257'=>'4','258'=>'5','259'=>'6','260'=>'7');
					  $religionArr[$column_name]=$maththab_exceptions[$value]?$maththab_exceptions[$value]:$value;
				  }
				  $religionArr[$column_name]=$value;
				  $religion_table=$table_name;
			  }
		  }
		  else
		  {	
			  //Abnormal case: Handle where a form widget contains multiple columns as mobile contains isd and mobile number, landline isd,std and landline number
			  //In db values for mulptiple inpupts will be done as <column_name1>-<value_name1>,<column_name2>-<value_name2>
				  foreach($column_name_arr as $column){
					  $column_arr=explode("-",$column);
					  $column_name=$column_arr[0];
					  $value_name=$column_arr[1];
					  switch($table_name){
						 case "JPROFILE":
							 if(!$jprofileFieldArr[$column_name])
								$jprofileFieldArr[$column_name]=$value[$value_name];
							 break;
						  case "REG_LEAD":
							  $leadArr[$column_name]=$value[$value_name];
							  break;
						  case "REGISTRATION_PAGE1":
							  $mobile_reg[$column_name]=$value[$value_name];
							  break;
					  }
				  }
		  }
	  }}
		  if(count($values_that_are_not_in_form)&&count($jprofileFieldArr))
			  $jprofileFieldArr=$jprofileFieldArr+$values_that_are_not_in_form;
		if($haveJedu)
			$jprofileFieldArr["HAVE_JEDUCATION"]=$haveJedu;
		if($jprofileFieldArr[EDU_LEVEL_NEW]!='')
		{
			include_once(JsConstants::$docRoot."/commonFiles/connect_dd.inc");
			$tempEduLevel = get_old_value($jprofileFieldArr[EDU_LEVEL_NEW], "EDUCATION_LEVEL_NEW");
			if($tempEduLevel)
				$jprofileFieldArr[EDU_LEVEL] = $tempEduLevel;
		}
		  //To be uncommented in new registration pages array_keys($jprofileFieldArr)+array_keys($jprofileEducationArr)+array_keys($jprofileContactArr);
	  $flag_arr=array_keys(FieldMap::getFieldLabel('flagval','',1));
	  if(count($jprofileFieldArr)){
		  if(!$profileid){
			  $loggedInObj=new LoggedInProfile();
			  if($jprofileFieldArr[RELIGION] == '5' || $jprofileFieldArr[RELIGION] == '6' || $jprofileFieldArr[RELIGION] == '7' || $jprofileFieldArr[RELIGION] == '10')
			  {
				  $rel =  FieldMap::getFieldLabel('religion',$jprofileFieldArr[RELIGION] );
				  $jprofileFieldArr[CASTE] = array_search($rel,FieldMap::getFieldLabel('caste',1,1));
			  }
                        if(is_array($nameOfUserArr) && array_key_exists("NAME",$nameOfUserArr) && $nameOfUserArr['NAME'])
                        {
                              $nameOfUserObj = new NameOfUser();
                              $nameOfUserArr['NAME']=$nameOfUserObj->filterName($nameOfUserArr['NAME']);
                              $isNameAutoScreened  = $nameOfUserObj->isNameAutoScreened($nameOfUserArr['NAME'],$jprofileFieldArr['GENDER']);
                                if($isNameAutoScreened)
                                {
                                        $jprofileFieldArr['SCREENING'] = Flag::setFlag($FLAGID="name",$jprofileFieldArr['SCREENING']);
                                }
                        }

			  $id=$loggedInObj->insert($jprofileFieldArr);
		  }else{
		  	  //Update screening flag
			  $loggedInObj= LoggedInProfile::getInstance("",$profileid);
			  if($screen_flag=$loggedInObj->getSCREENING()){
					$fields_to_update=array_keys($jprofileFieldArr);
					foreach($jprofileFieldArr as $field=>$value){
						if($value){
							if(in_array(strtolower($field),$flag_arr)){
								$screen_flag = Flag::removeFlag($field, $screen_flag);
							}
						}
					}
					$jprofileFieldArr['SCREENING']=$screen_flag;
			  }
                                if(in_array("ANCESTRAL_ORIGIN",array_keys($jprofileFieldArr)) && $jprofileFieldArr['ANCESTRAL_ORIGIN']=='')
                                {       
                                        $screen_flag = Flag::setFlag('ANCESTRAL_ORIGIN',$screen_flag);
                                        $jprofileFieldArr['SCREENING']=$screen_flag;
                                } 
			  //For nakshatra we save label in JPROFILE
			  if(isset($jprofileFieldArr['NAKSHATRA']))
				  $jprofileFieldArr['NAKSHATRA']=FieldMap::getFieldLabel('nakshatra',$jprofileFieldArr['NAKSHATRA']);
			  $loggedInObj->edit($jprofileFieldArr);
		  }
	 }
	  if(count($jprofileEducationArr)){
              if(!$loggedInObj->getPROFILEID())
                  $loggedInObj->setPROFILEID($id);
              
		  $loggedInObj->editEducation($jprofileEducationArr);
	  }
	  /* to be used in new registration pages
	  if(count($jprofileContactArr)){
		  $loggedInObj->editContact($jprofileContactArr);
	  }
	  if(count($jprofileHobbyArr)){
		  $loggedInObj->editHobby($jprofileHobbyArr);
	  }
	   */
	  if(count($leadArr)){
		$leadObj = new MIS_REG_LEAD();
		
		if ($leadArr[RELATIONSHIP] == '1' || $leadArr[RELATIONSHIP] == '2' || $leadArr[RELATIONSHIP] == '6' || $leadArr[RELATIONSHIP] == '4') $leadArr[GENDER] = 'M';
		else if ($leadArr[RELATIONSHIP] == '2D' || $leadArr[RELATIONSHIP] == '6D' || $leadArr[RELATIONSHIP] == '1D' || $leadArr[RELATIONSHIP] == '4D') $leadArr[GENDER] = 'F';
			
		$id=$leadObj->insert($leadArr);
	}
	  if(count($mobile_reg)){
		  if(count($values_that_are_not_in_form))
			  $mobile_reg=$mobile_reg+$values_that_are_not_in_form;
		  $mobRegObj = new NEWJS_REGISTRATION_PAGE1();
		  $id=$mobRegObj->insert($mobile_reg);
	}
	  if(count($religionArr)){
		  $religionArr[PROFILEID]=$id;
		  include_once(sfConfig::get("sf_web_dir") . "/profile/functions_edit_profile.php");
		  edit_nonHindu_religion($religionArr,"newjs.".$religion_table,$religion_log_table);
	  }
	  if(count($nativePlaceArr)){
			$nativePlaceArr[PROFILEID]=$id;
			$nativePlaceObj = ProfileNativePlace::getInstance();
			if($nativePlaceObj->InsertRecord($nativePlaceArr) === 0)
			{
				unset($nativePlaceArr[PROFILEID]);
				$nativePlaceObj->UpdateRecord($id,$nativePlaceArr);
			}
	  }
           if(count($nameOfUserArr)&&($nameOfUserArr['NAME']!=''||$nameOfUserArr['DISPLAY']!='')){
                $nameOfUserObj = new NameOfUser();
                if(!array_key_exists("DISPLAY",$nameOfUserArr))
                        $nameOfUserArr['DISPLAY']="";
                $nameOfUserObj->insertName($id,$nameOfUserArr['NAME'],$nameOfUserArr['DISPLAY']);
	  }
	  return $id;
	}
	function cleanGender($value){
		if($value=='2D')
			$value = '2';
		elseif($value=='6')
			$value = '3';
		elseif($value=='6D')
			$value = '3';
		return $value;
	}
}
?>
