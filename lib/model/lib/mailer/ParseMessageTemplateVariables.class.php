<?php

//include_once('ProcessMessageVariable.class.php');

class ParseMessageTemplateVariables {
 
  /**
   * @fn parseStringTokens
   * @brief Parses the csv template variable list 
   *
   **/
  public function parseStringTokens($input) {
    $tokens = explode(",", $input);
    return $tokens;
  }

  public function parseToken($token,$mail_group="") {
	  list($token_name,$token_input)=explode(":",$token);
	  $var=new Variable($token_name);
	  $var->setParams($token_input);
	  if($mail_group)
		  $var->setParam('mail_group',$mail_group);
	   return $var->getValue();
  }
  /**
   * @fn getValues
   * @param $input The input string in the format (variable_name:profile_id, variable_name:profile_id ...)
   * @return actual value of the variable_name that can be replaced in the message template
   **/
  public function getValues($input) {
    $this->variable_list = $this->parseStringTokens($input);
    for ($i = 0; $i < count($this->variable_list); $i++) {
      list($this->variable_name, $this->profile_id) =  explode(":", $this->variable_list[$i]);
    }
  }
}

