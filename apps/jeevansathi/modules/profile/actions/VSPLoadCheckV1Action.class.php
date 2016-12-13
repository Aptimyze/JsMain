<?php
/**
 *
 * @package    jeevansathi
 * @author     Mohammad Shahjahan
 */
class VSPLoadCheckV1Action extends sfAction
{
 /**
  * Executes index action
  *
  * @param sfRequest $request A request object
  */

 function execute($request){
 	$sender_username = $request->getParameter("sender_username");
 	$receiver_username =  $request->getParameter("receiver_username");

 	if ( isset($sender_username) && isset($receiver_username))
 	{
	 	$vspLoadCheck =new VSPLoadCheck();
	    $result = $vspLoadCheck->set($sender_username,$receiver_username);
 	}
 	die();
 }
}
