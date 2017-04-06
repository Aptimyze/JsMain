<?php
/**
 * Mobile Web Horscope Action
 *
 * @package    jeevansathi
 * @subpackage profile
 * @author     Kunal Verma
 * @date       25th April 2016
 */

class mobHoroscopeAction extends sfAction
{
  /**
   * 
   * @param type $request
   */
  public function execute($request) 
  {
    $objLoggedInProfile = LoggedInProfile::getInstance();
    
    $this->title = "Create Horoscope";
    list($BIRTH_YR, $BIRTH_MON, $BIRTH_DAY) = explode("-", $objLoggedInProfile->getDTOFBIRTH());
    $this->Unique_Id = $objLoggedInProfile->getPROFILEID();
    $this->BIRTH_YR = $BIRTH_YR;
    $this->BIRTH_DAY = $BIRTH_DAY;
    $this->BIRTH_MON = $BIRTH_MON;
    if($request->getParameter("KEY")=="ios")
    {
      $this->backButton = 1;
    }
	$androidView = $request->getParameter('andWebView');
        {
            if ($androidView == 1) 
	    {
              $this->webView = 1;
            }
	}
  }
}
?>
