<?php
//This class is used to validate the POST parameter passed to url's related to register module

class RegisterModuleInputValidate
{
	private $response;

	public function __construct()
        {
        }
	
	public function getResponse(){return $this->response;}

	/*
        This function validates the POST parameters for /register/staticTablesData url and set the response in the class variable
        @param - sfWebRequest object
        */
        public function validateStaticTablesData($request)
        {
                $pattern1 = "/^([a-zA-Z_])+$/";
                $pattern2 = "/^([0-9 :-])+$/";
                $param = json_decode($request->getParameter("json"),true);
                if($param && is_array($param))
                {
                        foreach($param as $k=>$v)
                        {
                                if(!preg_match($pattern1,$k) || !$v || !preg_match($pattern2,$v))
                                {
                                        $errorString = "register/lib/RegisterModuleInputValidate.class.php(1) :Reason($k->$v)";
                                        ValidationHandler::getValidationHandler("",$errorString);
                                        $resp = ResponseHandlerConfig::$POST_PARAM_INVALID;
                                        break;
                                }
                        }
                }
                else
                {
			$errorString = "register/lib/RegisterModuleInputValidate.class.php(2): Reason(no json params)";
			ValidationHandler::getValidationHandler("",$errorString);
                        $resp = ResponseHandlerConfig::$POST_PARAM_INVALID;
                }
                if(!$resp)
                        $this->response = ResponseHandlerConfig::$SUCCESS;
                else
                        $this->response = $resp;
        }
}
?>
