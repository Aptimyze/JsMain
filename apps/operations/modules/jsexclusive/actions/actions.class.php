<?php

/**
 * jsexclusive actions.
 *
 * @package    jeevansathi
 * @subpackage jsexclusive
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class jsexclusiveActions extends sfActions
{
 /**
  * Executes index action
  *
  * @param sfRequest $request A request object
  */
  public function executeIndex(sfWebRequest $request)
  {
    $this->forward('default', 'module');
  }
  
  public function executeMenu(sfWebRequest $request)
  {
   //Get Count for each option 
      
      //Counter for welcome calls
      }
  public function executeWelcomeCalls(sfWebRequest $request){
      
    //Get all clients here
      
  }
}
