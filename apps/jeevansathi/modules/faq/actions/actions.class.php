<?php

/**
 * faq actions.
 *
 * @package    jeevansathi
 * @subpackage faq
 * @author     Kunal Verma
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class faqActions extends sfActions
{
 /**
  * Executes index action
  *
  * @param sfRequest $request A request object
  */
    public function preExecute()
    {
        //  $this->setLayout(false);
    }
    public function executeIndex(sfWebRequest $request)
    {
      $this->redirect('/contactus/index');
        //Contains login credentials
        $loginData=$request->getAttribute("loginData");
        $this->profileID=$loginData[PROFILEID]; 
        $objFAQ = new FAQ;
        $objFAQ->ProcessData($request);
        
        $this->data = $loginData["PROFILEID"];

        $this->nonstyle =1 ;
        $this->flagged = null;
        $this->NO_NAVIGATION = 1 ;
        
        $this->trace1   = $objFAQ->getTracePath();
        $this->linkarr  = $objFAQ->getLinkArray();
        $this->trace    = $objFAQ->getTrace();
        $this->current  = $objFAQ->getCurrentSelection();
        $this->arrstart = $objFAQ->getFAQLabel();
        
        //Banners 
        $this->bms_topright  = 18;
        $this->bms_bottom = 19;
        $this->bms_left = 24;
        $this->bms_new_win= 32;
        
        //Success Story
        $this->bms_1 = 28;
        $this->bms_2 = 28;
                
        $this->rightPanelStory = IndividualStories::showSuccessPoolStory();
    }
    
    public function executeFeedback(sfWebRequest $request)
    {
        $feedBackObj = new FAQFeedBack;
    

        $loginData=$request->getAttribute("loginData");
        if($loginData[PROFILEID])
        {
            $loginProfile=LoggedInProfile::getInstance();
            $loginProfile->getDetail($loginData['PROFILEID'],"PROFILEID");
            $this->USERNAME=$loginData[USERNAME];
        }
        
        $success=false;
        if($feedBackObj->ProcessData($request)){
            $success=true;
        }

        //$objNameStore = new incentive_NAME_OF_USER;
        $loginProfile=LoggedInProfile::getInstance();
        $profileid = $loginProfile->getPROFILEID();
        //$this->NAME = $objNameStore->getName($loginProfile->getPROFILEID());

        //added this for caching
        $nameOfUserOb=new NameOfUser();        
        $nameOfUserArr = $nameOfUserOb->getNameData($profileid);
        $this->NAME = $nameOfUserArr[$profileid]["NAME"];
        unset($nameOfUserOb);
        $feedBackForm = new FeedBackForm(0);
        $this->form = $feedBackObj->getForm();
        $this->tracepath = $feedBackObj->getTracePath();
        
        
        if(MobileCommon::isMobile())
        {
            if($request->getParameter('feed') && $success===false)
                $this->ERROR=1;
                
            if($request->getParameter('feed')  && $success===true)
            {
                
                $this->MESSAGE1="Thank you for your valuable feedback.";
                $this->MESSAGE2="If required, we will get in touch with you.";
                sfContext::getInstance()->getResponse()->setTitle("Feedback sent - Jeevansathi") ;
                $this->setTemplate("mobilefaqconfirm");
            }   
            else    
                $this->setTemplate("mobilefaq");
        }
        
    }

    public function executeReportAbuseForUserLog(sfWebRequest $request)
{

  $resultArr = $request->getParameter('feed');
  $reporterName = $resultArr['reporter'];
  $reporteeName = $resultArr['reportee'];
  $crmUser = $resultArr['crmUser'];
//  $reason = $resultArr['reason'];
  $message = $resultArr['message'];


  $profileObj = NEWJS_JPROFILE::getInstance();
  $reporterPFID = $profileObj->getProfileIdFromUsername($reporterName);
  $reporteePFID = $profileObj->getProfileIdFromUsername($reporteeName);

  if($reporterPFID==NULL || $reporteePFID == NULL)
  { 
    if($reporterPFID==NULL && $reporteePFID == NULL)
    $error[message] = "both are not correct";

    else if($reporterPFID==NULL) 
      $error[message] = "reporter profileID is not correct";
    else
      $error[message] = "reportee profileID is not correct"; 
    echo json_encode($error);
    exit;
  }
  else
  { 
    $selfProfile = new Profile('',$reporterPFID);
    $resultArr['email'] = $profileObj->getProfileSelectedDetails($reporterPFID,'EMAIL')['']['EMAIL'];
    $request->setParameter('feed',$resultArr);
    $request->setParameter('reporterPFID',$reporterPFID);
    $request->setParameter('reporteePFID',$reporteePFID);
    $request->setParameter('fromCRM','1');
    ob_start();
    sfContext::getInstance()->getController()->getPresentationFor("faq", "ApiFeedbackV1"); 
    $output = ob_get_contents();
    ob_end_clean();
    die();
    $data = json_decode($output, true);
    return $data;

  }
}

}
