<?php

/**
 * e actions.
 *
 * @package    jeevansathi
 * @subpackage e
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class eActions extends sfActions
{
 /**
  * Executes index action
  *
  * @param sfRequest $request A request object
  */
  public function executeRedirect(sfWebRequest $request)
  {
	$link_id=$request->getParameter('link_id');
	$link=LinkFactory::getLink($link_id);
	$link->trackLink();
	$link->forward($request);
	die;
   // $this->forward('default', 'module');
  }
  
  public function executeTracking(sfWebRequest $request)
  {
		$trackingModuleName=$request->getParameter('trackingClass');
		$trackingClass=TrackingFactory::getTrackingClass($trackingModuleName);
		$trackingClass->track($request);
		$trackingClass->forward($request);
		die;
  }
  
}
