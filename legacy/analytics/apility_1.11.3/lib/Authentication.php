<?php
  class APIlityAuthentication {
    // class variables
    var $clientEmail;
    var $email;
    var $password;
    var $developerToken;
    var $userAgent;
    var $applicationToken;

    // constructor
    function APIlityAuthentication(
      $email = null,
      $password = null,
      $developerToken = null,
      $clientEmail = null,
      $applicationToken = null
    ) {
            
      if (isset($clientEmail)) $this->clientEmail = $clientEmail;
      if (isset($email)) $this->email = $email;
      if (isset($password)) $this->password = $password;
      if (isset($developerToken)) {
        if (!USE_SANDBOX) {
          $this->developerToken = $developerToken;
        }
        else {
          $this->developerToken = $email."++".CURRENCY_FOR_SANDBOX;
        }
      }
      if (isset($applicationToken)) $this->applicationToken = $applicationToken;
      
      // hard-wire the user agent
      $this->userAgent = 'Google APIlity PHP Library for AdWords';

      // set the headers upon authentication context creation if soapclients
      // already exist
      $soapClients = &APIlityClients::getClients();      
      if (isset($soapClients)) $soapClients->setSoapHeaders($this);               
    }

    // get functions
    function getClientEmail() {
      return $this->clientEmail;
    }

    function getEmail() {
      return $this->email;
    }

    function getPassword() {
      return $this->password;
    }

    function getDeveloperToken() {
      if (!USE_SANDBOX) {
        return $this->developerToken;
      }
      else {
        return ($this->email)."++".CURRENCY_FOR_SANDBOX;
      }
    }

    function getApplicationToken() {
      return $this->applicationToken;
    }

    function getUserAgent() {
      return $this->userAgent;
    }

    // this will return a valid header for soap clients
    function getHeader() {
      return "<email>".$this->getEmail()."</email>
              <password>".$this->getPassword()."</password>
              <useragent>".$this->getUserAgent()."</useragent>
              <developerToken>".$this->getDeveloperToken()."</developerToken>
              <clientEmail>".$this->getClientEmail()."</clientEmail>
              <applicationToken>".$this->getApplicationToken()."</applicationToken>";
    }

    // set functions
    function setClientEmail($newClientEmail) {
      $this->clientEmail = $newClientEmail;
      $soapClients = &APIlityClients::getClients();
      $soapClients->setSoapHeaders($this);
    }

    function setEmail($newEmail) {
      $this->email = $newEmail;
      $soapClients = &APIlityClients::getClients();
      $soapClients->setSoapHeaders($this);
    }

    function setPassword($newPassword) {
      $this->password = $newPassword;
      $soapClients = &APIlityClients::getClients();
      $soapClients->setSoapHeaders($this);
    }

    function setApplicationToken($applicationToken) {
      $this->applicationToken = $applicationToken;
      $soapClients = &APIlityClients::getClients();
      $soapClients->setSoapHeaders($this);
    }

    function setDeveloperToken($newDeveloperToken) {
      if (!USE_SANDBOX) {
        $this->developerToken = $newDeveloperToken;
      }
      else {
        $this->developerToken = $this->getEmail()."++".CURRENCY_FOR_SANDBOX;
      }
      $soapClients = &APIlityClients::getClients();
      $soapClients->setSoapHeaders($this);
    }

  /**
   * Gets global Authentification context
   * 
   * @return APIlityAuthentication object
   * @static static
   * @author Yury Ksenevich
   */
  function &getContext() {
    static $authentificationContext;
    return $authentificationContext;  
  }

  /**
   * Sets Authentification context
   * 
   * @param array $authenticationIni requires the following ini elements: Client_Email, Email, Password, Developer_Token, Application_Token
   * @static static
   * @author Yury Ksenevich
   */
  function setContext($authenticationIni) {
    $authenticationContext = &APIlityAuthentication::getContext();
    
    @$authenticationIni['Client_Email']? $clientEmail = $authenticationIni['Client_Email'] : $clientEmail = ''; 
    @$authenticationIni['Email']? $email = $authenticationIni['Email'] : $email = '';
    @$authenticationIni['Password']? $password = $authenticationIni['Password'] : $password = '';
    @$authenticationIni['Developer_Token']? $developerToken = $authenticationIni['Developer_Token'] : $developerToken = '';
    @$authenticationIni['Application_Token']? $applicationToken = $authenticationIni['Application_Token'] : $applicationToken = '';

    // for APIlity to work properly, all authentication contexts should always
    // be called $authenticationContext
    $authenticationContext = new APIlityAuthentication(
      $email, 
      $password, 
      $developerToken, 
      $clientEmail, 
      $applicationToken
    );   

    $soapClients = &APIlityClients::getClients();
    $soapClients->setSoapHeaders($authenticationContext);
  }
}

?>