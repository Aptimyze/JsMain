<?php
  function getManagersClientAccounts() {
    global $authenticationContext;
    // we want to get the current manager's account clients so temporarily unset
    // any eventually existing clientEmail setting
    $saveClientEmail = $authenticationContext->getClientEmail();
    $authenticationContext->setClientEmail("");
    global $soapClients;
    $someSoapClient = $soapClients->getAccountClient();
    // prepare soap parameters
    $soapParameters = "<getClientAccounts></getClientAccounts>";
    // execute soap call
    $clientAccounts = $someSoapClient->call("getClientAccounts", $soapParameters);
    $soapClients->updateSoapRelatedData(extractSoapHeaderInfo($someSoapClient->getHeaders()));
    if ($someSoapClient->fault) {
      pushFault($someSoapClient, $_SERVER['PHP_SELF'].":getManagersClientAccounts()", $soapParameters);
      // in case of an error make sure that at least the clientEmail gets restored
      $authenticationContext->setClientEmail($saveClientEmail);
      return false;
    }
    // restore clientEmail
    $authenticationContext->setClientEmail($saveClientEmail);
    // make sure we really return an array
 		if(is_array($clientAccounts['getClientAccountsReturn'])) {
	    return $clientAccounts['getClientAccountsReturn'];
		}
		else {
	    return array($clientAccounts['getClientAccountsReturn']);
		}
  }

  function getClientsClientAccounts() {
    global $soapClients;
    global $authenticationContext;
    $someSoapClient = $soapClients->getAccountClient();
    // we want to get the current client's account clients so make sure that the
    // clientEmail is set at all
    if ($authenticationContext->getClientEmail() != "") {
      // prepare soap parameters
      $soapParameters = "<getClientAccounts></getClientAccounts>";
      // execute soap call
      $clientAccounts = $someSoapClient->call("getClientAccounts", $soapParameters);
      $soapClients->updateSoapRelatedData(extractSoapHeaderInfo($someSoapClient->getHeaders()));
      if ($someSoapClient->fault) {
        pushFault($someSoapClient, $_SERVER['PHP_SELF'].":getClientsClientAccounts()", $soapParameters);
        return false;
      }
      // make sure we really return an array
	 		if(is_array($clientAccounts['getClientAccountsReturn'])) {
		    return $clientAccounts['getClientAccountsReturn'];
			}
			else {
		    return array($clientAccounts['getClientAccountsReturn']);
			}
    }
    else return false;
  }

  function getAccountInfo() {
    global $soapClients;
    $someSoapClient = $soapClients->getAccountClient();
    // prepare soap parameters
    $soapParameters = "<getAccountInfo></getAccountInfo>";
    // execute soap call
    $accountInfo = $someSoapClient->call("getAccountInfo", $soapParameters);
    $soapClients->updateSoapRelatedData(extractSoapHeaderInfo($someSoapClient->getHeaders()));
    if ($someSoapClient->fault) {
      pushFault($someSoapClient, $_SERVER['PHP_SELF'].":getAccountInfo()", $soapParameters);
      return false;
    }
    return $accountInfo['getAccountInfoReturn'];
  }

  function updateAccountInfo(
    $defaultNetworkTargeting,
    $descriptiveName,
    $emailPromotionsPreferences,
    $languagePreference,
    $primaryBusinessCategory
  ) {
    global $soapClients;
    $someSoapClient = $soapClients->getAccountClient();
    // prepare soap parameters
		if ($defaultNetworkTargeting) {
		  $defaultNetworkTargetingXml =
		    "<defaultNetworkTargeting>".$defaultNetworkTargeting."</defaultNetworkTargeting>";
		}
		else {
		  $defaultNetworkTargetingXml = '';
		}
    if ($descriptiveName) {
      $descriptiveNameXml =
        "<descriptiveName>".$descriptiveName."</descriptiveName>";
    }
    else {
      $descriptiveNameXml = '';
    }
    if ($emailPromotionsPreferences) {
      $emailPromotionsPreferencesXml =
        "<emailPromotionsPreferences>".$emailPromotionsPreferences."</emailPromotionsPreferences>";
    }
    else {
      $emailPromotionsPreferencesXml = '';
    }
		if ($languagePreference) {
		  $languagePreferenceXml =
		    "<languagePreference>".$languagePreference."</languagePreference>";
		}
		else {
		  $languagePreferenceXml = '';
		}
		if ($primaryBusinessCategory) {
		  $primaryBusinessCategoryXml =
		    "<primaryBusinessCategory>".$primaryBusinessCategory."</primaryBusinessCategory>";
		}
		else {
		  $primaryBusinessCategoryXml = '';
		}
    $soapParameters = "<updateAccountInfo>
    									 	 <account>".
											     $defaultNetworkTargetingXml.
											     $descriptiveNameXml.
											     $emailPromotionsPreferencesXml.
											     $languagePreferenceXml.
											     $primaryBusinessCategoryXml."
											   </account>
    									 </updateAccountInfo>";
    // execute soap call
    $accountInfo = $someSoapClient->call("updateAccountInfo", $soapParameters);
    $soapClients->updateSoapRelatedData(extractSoapHeaderInfo($someSoapClient->getHeaders()));
    if ($someSoapClient->fault) {
      pushFault($someSoapClient, $_SERVER['PHP_SELF'].":updateAccountInfo()", $soapParameters);
      return false;
    }
    return true;
  }

  function setLoginInfo($newLoginEmail, $newPassword) {
    global $soapClients;
    $someSoapClient = $soapClients->getAccountClient();
    // prepare soap parameters
    $soapParameters = "<setLoginInfo>
                         <login>".$newLoginEmail."</login>
                         <newPassword>".$newPassword."</newPassword>
                       </setLoginInfo>";
    // execute soap call
    $someSoapClient->call("setLoginInfo", $soapParameters);
    $soapClients->updateSoapRelatedData(extractSoapHeaderInfo($someSoapClient->getHeaders()));
    if ($someSoapClient->fault) {
      pushFault($someSoapClient, $_SERVER['PHP_SELF'].":setLoginInfo()", $soapParameters);
      return false;
    }
    return true;
  }

  function createEmailPreferencesXml(
    $marketResearchEnabled,
    $newsletterEnabled,
    $promotionsEnabled,
    $accountPerformanceEnabled,
    $disapprovedAdsEnabled
  ) {
  	if ($marketResearchEnabled) {
  	  $marketResearchEnabled = "true";
  	}
  	else {
  	  $marketResearchEnabled = "false";
  	}
  	if ($newsletterEnabled) {
  	  $newsletterEnabled = "true";
  	}
  	else {
  	  $newsletterEnabled = "false";
  	}
  	if ($promotionsEnabled) {
  	  $promotionsEnabled = "true";
  	}
  	else {
  	  $promotionsEnabled = "false";
  	}
  	if ($accountPerformanceEnabled) {
  	  $accountPerformanceEnabled = "true";
  	}
  	else {
  	  $accountPerformanceEnabled = "false";
  	}
  	if ($disapprovedAdsEnabled) {
  	  $disapprovedAdsEnabled = "true";
  	}
  	else {
  	  $disapprovedAdsEnabled = "false";
  	}
    return   "<marketResearchEnabled>".$marketResearchEnabled."</marketResearchEnabled>
             <newsletterEnabled>".$newsletterEnabled."</newsletterEnabled>
             <promotionsEnabled>".$promotionsEnabled."</promotionsEnabled>
             <accountPerformanceEnabled>".$accountPerformanceEnabled."</accountPerformanceEnabled>
             <disapprovedAdsEnabled>".$disapprovedAdsEnabled."</disapprovedAdsEnabled>";
  }

  function createDefaultNetworkTargetingXml($defaultNetworkTargeting) {
    $defaultNetworkTargetingXml = "";
    foreach($defaultNetworkTargeting as $networkTarget) {
      $defaultNetworkTargetingXml .= "<networkTypes>".$networkTarget."</networkTypes>";
    }
    return $defaultNetworkTargetingXml;
  }
?>