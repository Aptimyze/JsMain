<?php
/**
 * CLASS RegistrationFactory
 * <p>Super class of {@link mobileRegister1}, {@link desktopRegister1}, <BR>
 * {@link mobileRegister2}, {@link desktopRegister2}, <BR>
 * {@link mobileEdit}, {@link desktopEdit} <BR>
 * and {@link mobileRegister3} class<BR>
 * This class decide first carries all common and factory functionalities for pages.</p>
 * @package   jeevansathi
 * @subpackage   registration
 * @author    Esha Jain <esha.jain@jeevansathi.com>
 * @copyright 2015 Esha Jain
 */
class RegistrationFactory {

  public static function initiateClass($objController)
  {
	$loginProfile = LoggedInProfile::getInstance();
	$pageName = $objController->getRequest()->getParameter('page');
	if(!$loginProfile->getPROFILEID()&& $pageName!=RegistrationEnums::$JSPC_REG_PAGE[1])
	{
                if($objController->getRequest()->isMethod("POST") && $objController->getRequest()->getParameter("formValues")){
                    echo "logout";
                    die;
                }
		$objController->getRequest()->setParameter('page',RegistrationEnums::$JSPC_REG_PAGE[1]);
		$objController->forward("register","regPage");
	}
	switch ($pageName) 
		{
			case RegistrationEnums::$JSPC_REG_PAGE[1]:
				return new desktopRegister1($objController);
				break;
			case RegistrationEnums::$JSPC_REG_PAGE[2]:
				return new desktopRegister2($objController);
				break;
			case RegistrationEnums::$JSPC_REG_PAGE[3]:
				return new desktopRegister3($objController);
				break;
			case RegistrationEnums::$JSPC_REG_PAGE[4]:
				return new desktopRegister4($objController);
				break;
			case RegistrationEnums::$JSPC_REG_PAGE[5]:/*Phone Verification*/
				return new desktopRegister5($objController);
				break;
      case RegistrationEnums::$JSPC_REG_PAGE[6]:/*DPP & Filter Page*/
				return new desktopRegister6($objController);
				break;
      case "RegistrationEnums::$JSMS_REG_PAGE[1]":
				return new regMobilePage();
				break;
			default:
				$errorHandlerObj = new HandleError(RegistrationEnums::$errorMapping['INVALID_PAGENAME']['errorMessage'],RegistrationEnums::$errorMapping['INVALID_PAGENAME']['label'],RegistrationEnums::$errorEnums['WHITELIST']);
				$errorHandlerObj->takeAction();
				break;  
		}
  }
}
?>
