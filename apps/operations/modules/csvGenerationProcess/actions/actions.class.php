<?php

/**
 * csvGenerationProcess actions.
 *
 * @package    jeevansathi
 * @subpackage csvGenerationProcess
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class csvGenerationProcessActions extends sfActions
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
   public function executeGenerateCsv(sfWebRequest $request)
  {
          $processName	=$request->getParameter('processName');
          $date   	=$request->getParameter('date');
          $csvType	=$request->getParameter('csvType');
	  $cid      	=$request->getParameter("cid");

          $processName	=str_replace("'","",$processName);
          $processName	=str_replace("\"","",$processName);
          $date		=str_replace("'","",$date);
          $date		=str_replace("\"","",$date);

	  $agentAllocDetailsObj   =new AgentAllocationDetails();
	  $crmUtilityObj	  =new crmUtility();	
	  $privilegeArr        	  =$agentAllocDetailsObj->getprivilage($cid);
	  $linkAccess 		  =$crmUtilityObj->fetchPrivilegeLinks($privilegeArr);
	  if($linkAccess['CSV']!='Y')
		die('Do not have the privilege to access.');

	  $fNamesArr    =csvFields::$csvName;
	  $fName	=$fNamesArr[$processName];	
          $csvHandler	=new csvGenerationHandler();
          $csvHandler->generateCSV($processName,$date,$csvType);

	  if($processName=='sugarcrmLtf'){		  
		  $csvTypeArr	=@explode("_",$csvType);
		  $fName	=$fName."_"."$csvTypeArr[1]"."_"."$csvTypeArr[0]";
	  }			 

          header("Content-Type: application/data");
          header("Content-Disposition: attachment; filename=$fName.dat");
          header("Pragma: no-cache");
          header("Expires: 0");
	  successfullDie();	         	 
  }
}
