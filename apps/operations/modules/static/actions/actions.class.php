<?php

/**
 * photoScreening actions.
 *
 * @package    jeevansathi
 * @subpackage photoScreening
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class staticActions extends sfActions
{
 /**
  * Executes index action
  *
  * @param sfRequest $request A request object
  */
  public function executeIndex(sfWebRequest $request)
  {
    $this->AUTH_FAILURE = $request->getParameter("authFailure");
    $this->EXPIRE = $request->getParameter("EXPIRE");
    $this->INVALID = $request->getParameter("INVALID");
  }

  public function executeTimedout(sfWebRequest $request)
  {
  }
  public function executeLogin(sfWebRequest $request)
  {
        include($_SERVER['DOCUMENT_ROOT']."/jsadmin/connect.inc");//for login()
        $arr = $request->getParameterHolder()->getAll();
        $id=$arr['username'];
        $pass=$arr['password'];
        if ($id && $pass)
        {
                $f = login($id,$pass);
                $url = "Location: ".sfConfig::get("app_site_url")."/jsadmin/mainpage.php?cid=".$f;
                header($url);
        }
        die;
  }
}
?>
