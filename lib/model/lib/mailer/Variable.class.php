<?php

//include_once("ClassCodes.class.php");

/**
  * @class Variable
  * @brief Class containing various properties associated with a template variable
  * @author Ankit Garg
  * @created 2012-06-18
  **/

class Variable {
  private $variable_name;
  private $variable_processing_class_code;
  private $default_value;
  private $var_description;
  private $max_length;
  private $max_length_sms;
  private $params;
  function __construct($name) {
	  $variable_data=MailerArray::getVariable($name);
	  $this->variable_name=$name;
	  $this->variable_processing_class_code=$variable_data["VARIABLE_PROCESSING_CLASS"];
	  $this->default_value=$variable_data["DEFAULT_VALUE"];
	  $this->max_length=$variable_data["MAX_LENGTH"];
	  $this->max_length_sms=$variable_data["MAX_LENGTH_SMS"];
	  $this->var_description=$variable_data["DESCRIPTION"];
  }
  /**
   *@fn isStatic
   *@brief Returns a boolean value corresponding to the variable name, identifying whether it has a static value or not
   *@param $processing_class_code Takes the class code for this variable processing class
   *@return True or False
   **/

  public function isStatic() {
    if ($this->variable_processing_class_code == CLASS_CODES::_STATIC) {
      return True;
    }
    else {
      return False;
    }
  }

 /**
   * @fn setDefaultValue
   * @brief sets the default value of the variable in mail template
   * @param $def The default value to be set
   * @return Void
   **/

  public function setDefaultValue($def) {
    $this->default_value = $def;
  }

  /**
    * @fn getDefaultValue
    * @brief gets the default value of the variable in mail template
    * @return default_value
    **/

  public function getDefaultValue() {
    if ($this->isStatic() || $this->variable_processing_class_code == CLASS_CODES::_NA) {
      if ($this->default_value)
        return $this->default_value;
    }
  }

  public function getValue(){
	  if($this->isStatic())
		  return $this->default_value;
	  else{
	  $variable_processing_class=VariableProcessingClassFactory::createObject($this);
	  return $variable_processing_class->getActualValue();
	  }
  }

  /**
    * @fn setDescription
    * @brief Sets the description of the variable in mail template
    * @param $des the actual description that needs to be set
    * @return void
    **/

  public function setDescription($des) {
    $this->var_description = $des;
  }

  /**
    * @fn getDescription
    * @brief gets the description of the variable in mail template
    * @return var_description
    **/

  public function getDescription() {
    return $this->var_description;
  }

  /**
    * @fn setVariableName
    * @brief sets the variable name vis-a-vis of Variable name in mail template
    * @param $name The name of the variable to be set
    * @return Void
    **/

  public function setVariableName($name) {
    $this->variable_name = $name;
  }

  /**
    * @fn getVariableName
    * @brief gets the variable name for the corresponding variable in mail template
    * @return variable_name
    **/

  public function getVariableName() {
    return $this->variable_name;
  }

  /**
    * @fn setVariableProcessingClassCode
    * @brief sets variable processing class code for the corresponding variable in the mail template
    * @param $code contains the code of the variable processing class
    * @return void
    **/

  public function setVariableProcessingClassCode($code) {
    $this->variable_processing_class_code = $code;
  }

  /**
    * @fn getVariableProcessingClassCode
    * @brief gets the variable processing class code for the corresponding variable in the mail template
    * @return variable_processing_class_code
    **/

  public function getVariableProcessingClassCode() {
    return $this->variable_processing_class_code;
  }

  /**
    * @fn setMaxLength
    * @brief sets the max length allowed for a variable in mail template
    * @param $len length which is to be set
    * @return void
    **/

  public function setMaxLength($len) {
    $this->max_length = $len;
  }

  /**
    * @fn getMaxLength
    * @brief gets the Max Length allowed for a variable in mail template
    * @return max_length
    **/

  public function getMaxLength() {
    return $this->max_length;
  }

  /**
    * @fn setMaxLengthSMS
    * @brief sets max length allowed for a variable in sms template
    * @param $sms_len
    * @return void
    **/

  public function setMaxLengthSMS($sms_len) {
    $this->max_length_sms = $sms_len;
  }

  /**
    * @fn getMaxLengthSMS
    * @brief gets the max length allowed for a variable in sms template
    * @return max_length_sms
    **/

  public function getMaxLengthSMS() {
    return $this->max_length_sms;
  }
/**
 * @fn getParam
 * @brief Get value of parameter whose names is passed in argument
 * @param param_name string Name of parameter.
 * @return value of the parameter
 * @throws Exception is no parameter is defined for given argument.
 * */
  public function getParam($param_name){
	  if(array_key_exists($param_name,$this->params))
		  return $this->params[$param_name];
	  /*else {
		  //throw new Exception("Param $param_name not defined for $this->variable_name variable");
		  return "";
    }*/
  }
  /** 
   * @fn setParams
   * @brief This function will set all input parameters associated.
   * @param string all_params.It expects all_param in format of "var_name1=var_value1,var_name2=var_value2..."
   * @returns void
   * */
  public function setParams($all_params){
	$var_list=explode(",",$all_params);
	foreach ($var_list as $var){
		list($var_name,$var_val)=explode("=",$var);
		$this->params[$var_name]=$var_val;
	}
  }
  public function setParam($var_name,$val){
		$this->params[$var_name]=$val;
  }
}

