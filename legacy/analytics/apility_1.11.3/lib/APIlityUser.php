<?php
  class APIlityManager {
    var $email;
    var $password;
    var $developerToken;
    var $applicationToken;
    var $authenticationContext;
    
    // constructor
    function APIlityManager(
        $email = null,
        $password = null,
        $developerToken = null,
        $applicationToken = null
    ) {
      if ($email && $password && $developerToken && $applicationToken) {
        $this->email = $email;
        $this->password = $password;
        $this->developerToken =  $developerToken;
        $this->applicationToken = $applicationToken; 
                 
        $authentication = array();
        $authentication['Client_Email'] = null;
        $authentication['Email'] = $email;
        $authentication['Password'] = $password;
        $authentication['Developer_Token'] = $developerToken;
        $authentication['Application_Token'] = $applicationToken;
        APIlityAuthentication::setContext($authentication);        
      }
      else {
        // parse the default authentication.ini file
        $authenticationIni = 
            parse_ini_file(dirname(__FILE__).'/../authentication.ini');
        APIlityAuthentication::setContext($authenticationIni);        
      }
      $this->email = $authenticationIni['Email'];
      $this->password = $authenticationIni['Password'];
      $this->developerToken =  $authenticationIni['Developer_Token'];
      $this->applicationToken = $authenticationIni['Application_Token']; 
      
      $this->authenticationContext = &APIlityAuthentication::getContext();
      $this->dummy();
    }
        
    // this dummy function's only purpose is to include the other files  
    function dummy() {    
      if (IS_ENABLED_OO_MODE) {
        if ((strcasecmp(API_VERSION, "v10") == 0) ||
            (strcasecmp(API_VERSION, "v11") == 0)) {
          // the corresponding .php files contain the classes and have been 
          // imported in apility.php
          $currentWorkingDirectory = dirname(__FILE__);
          require_once($currentWorkingDirectory.'/Campaign.inc');
          require_once($currentWorkingDirectory.'/AdGroup.inc');
          require_once($currentWorkingDirectory.'/Criterion.inc');
          require_once($currentWorkingDirectory.'/Ad.inc');
          // these files have no classes, importing them here will put them in
          // the scope of this class
          require_once($currentWorkingDirectory.'/Report.php');
          require_once($currentWorkingDirectory.'/TrafficEstimate.php');
          require_once($currentWorkingDirectory.'/Info.php');
          require_once($currentWorkingDirectory.'/Account.php');
          require_once($currentWorkingDirectory.'/KeywordTool.php');
          require_once($currentWorkingDirectory.'/SiteSuggestion.php');
        }
      }   
    }
    
    function getOverallPerformedOperations() {
      $soapClients = &APIlityClients::getClients();
      return $soapClients->getOverallPerformedOperations();
    }
    
    function getOverallConsumedUnits() {
      $soapClients = &APIlityClients::getClients();      
      return $soapClients->getOverallConsumedUnits();      
    }
        
    function getLastResponseTimes() {
      $soapClients = &APIlityClients::getClients();      
      return $soapClients->getLastResponseTimes();      
    }    
    
    function getFaultStack() {
      $faultStack = &APIlityFault::getFaultStack();
      return $faultStack;
    }    
    
    function getLastSoapRequests() {
      $soapClients = &APIlityClients::getClients();
      return $soapClients->getLastSoapRequests();      
    }
    
    function getLastSoapResponses() {
      $soapClients = &APIlityClients::getClients();
      return $soapClients->getLastSoapResponses();      
    }
        
    function __call($method, $args) {
      return call_user_func_array($method, $args);
    }   
    
    // getters
    function getEmail() {
      return $this->email;  
    }

    function getPassword() {
      return $this->password;  
    }

    function getDeveloperToken() {
      return $this->developerToken;  
    }

    function getApplicationToken() {
      return $this->applicationToken;  
    }
    
    // setters    
    function setContext($authentication) {
      APIlityAuthentication::setContext($authentication);              
      $this->authenticationContext = &APIlityAuthentication::getContext();      
    }
    
    function setEmail($newEmail) {
      $this->email = $newEmail;
      $this->authenticationContext->setEmail($newEmail);  
    }
    
    function setPassword($newPassword) {
      $this->password = $newPassword;
      $this->authenticationContext->setPassword($newPassword);  
    }
    
    function setDeveloperToken($newDeveloperToken) {
      $this->developerToken = $newDeveloperToken;
      $this->authenticationContext->setDeveloperToken($newDeveloperToken);  
    }
    
    function setApplicationToken($newApplicationToken) {
      $this->applicationToken = $newApplicationToken;
      $this->authenticationContext->setApplicationToken($newApplicationToken);  
    }
    
  }
  
  class APIlityUser extends APIlityManager {  
    var $clientEmail;
    
    // constructor
    function APIlityUser(
        $email = null,
        $password = null,
        $clientEmail = null,
        $developerToken = null,
        $applicationToken = null
    ) {      
      // we need to construct the superclass first, this is php-specific
      // object-oriented behaviour
      APIlityUser::APIlityManager($email, $password, $developerToken, $applicationToken);
      if (!$clientEmail) {
        $authenticationIni = 
            parse_ini_file(dirname(__FILE__).'/../authentication.ini');            
        $clientEmail = $authenticationIni['Client_Email']; 
      }
      $this->clientEmail = $clientEmail;      
      $this->authenticationContext->setClientEmail($clientEmail);      
    } 
           
    // getters    
    function getClientEmail() {
      return $this->clientEmail;  
    }
    
    // setters
    function setClientEmail($newEmail) {
      $this->clientEmail = $newEmail;
      $this->authenticationContext->setClientEmail($newEmail);  
    }    
  }
?>