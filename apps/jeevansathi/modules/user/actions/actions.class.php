<?php

/**
 * user actions.
 *
 * @package    jeevansathi
 * @subpackage user
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class userActions extends sfActions
{
 /**
  * Executes index action
  *
  * @param sfRequest $request A request object
  */
  public function executeLogin(sfWebRequest $request)
  {
        if($this->getUser()->isAuthenticated()){
		$this->redirect( $_SERVER["HTTP_REFERER"] );
		throw new sfStopException ( );
        }
	else{
		if($request->getParameter("submit")){
			if($request->getParameter("username")=="tanu" && $request->getParameter("password")=="tanu"){
			    $this->getUser()->signIn();
			    $this->redirect( $_SERVER["HTTP_REFERER"] );
			    throw new sfStopException ( );		
			}
			else
				$this->error="User not authenticated";	
		}
	}
  }

  public function t(sfWebRequest $request)
  {
        $this->getUser()->signOut();
        $this->redirect( "/" );
        throw new sfStopException ( );
	die;
  }
}
