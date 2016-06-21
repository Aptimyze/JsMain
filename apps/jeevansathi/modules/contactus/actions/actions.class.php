<?php

/**
 * contactus actions.
 *
 * @package    jeevansathi
 * @subpackage contactus
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class contactusActions extends sfActions
{
 /**
  * Executes index action
  *
  * @param sfRequest $request A request object
  */
  public function executeIndex(sfWebRequest $request)
  {
	
	$contactUSObj = new ContactUS();
	
	$contactUSObj->processData($request);

		
	$this->infoSel 			= $contactUSObj->getInfoSel();
	$this->locationArr 		= $contactUSObj->getLocation();
	$this->city_label_Arr 	= $contactUSObj->getCityLabel();
	
	$this->state_sel 		= $contactUSObj->getDefaultStateLabel();
	$this->city_sel  		= $contactUSObj->getDefaultCityLabel();
	
	$this->defaultStateFlag = $contactUSObj->getDefaultFlag();
	$this->state 			= $contactUSObj->getListState();
	
	$this->city 			= $contactUSObj->getListCity();
	$this->info 			= $contactUSObj->getInfo();

	$this->show_sel_city 	= $contactUSObj->getShowCity();
    $this->infoCity 		= $contactUSObj->getInfoCity();
    
    $this->googleApiKey 	= $contactUSObj->getGoogleApiKey();
	//var_dump($this->show_sel_city);  
	if($request->getAttribute("loginData"))
    {
        $this->loginData = $request->getAttribute("loginData");
        $jProfileObj = new JPROFILE();
        $userDetails = $jProfileObj->get($this->loginData["PROFILEID"],"PROFILEID","CITY_RES, COUNTRY_RES");
        $this->userCity = $userDetails["CITY_RES"];
        $this->userCountry = $userDetails["COUNTRY_RES"];
    }
    $request->setParameter('INTERNAL', 1);
    $this->data = $this->fetchAPIData($request);
    $this->fromSideLink = $request->getParameter("fromSideLink");
    //var_dump($this->data);
    if(MobileCommon::isNewMobileSite())
    {
        $this->setTemplate("JSMSContactUs");
    }
    else
    {
        $this->setTemplate("JSPCContactUs");
    }
	
	
  }
  
  public function fetchAPIData($request)
  {
    ob_start();
    $data = sfContext::getInstance()->getController()->getPresentationFor('contactus', 'ApiContactUsV1');
    $output = ob_get_contents();
    ob_end_clean();
    $data = json_decode($output, true);
    return $data;
  }
  
    public function executeNotification(sfWebRequest $request)
    {
        if($request->getAttribute("loginData")){
            $loginData = $request->getAttribute("loginData");
            $profileid = $loginData["PROFILEID"];
            //if($profileid == "11234133"){
                $this->allow = 'Y';
            //}
        }
        else{
            die;
        }
    }
}
