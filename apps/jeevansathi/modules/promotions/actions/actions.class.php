<?php

/**
 * promotions actions.
 *
 * @package    jeevansathi
 * @subpackage promotions
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class promotionsActions extends sfActions
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

  public function executeChatPromoJSPC(sfWebRequest $request)
  {
    
  }

  public function executeChatPromoJSMS(sfWebRequest $request)
  {
    
  }
}
