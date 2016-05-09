<?php

/**
 * notduplicate actions.
 *
 * @package    jeevansathi
 * @subpackage notduplicate
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class notduplicateActions extends sfActions
{
	public $no_error=-1;
	public $not_found=1;
	public $no_duplicate=2;
	public $controller="/operations.php/";
	public function preExecute()
	{
		$request=sfContext::getInstance()->getRequest();
		$this->cid=$request->getParameter(cid);
		$this->success=$request->getParameter("success");	
		$this->user=JsOpsCommon::getcidname($this->cid);
		$generatedUrl=($this->generateUrl()!='/')?$this->generateUrl():$this->controller;
		$this->moduleurl=$generatedUrl.$this->getModuleName();
		
	}
 /**
  * Executes index action
  *
  * @param sfRequest $request A request object
  */
	public function executeIndex(sfWebRequest $request)
	{
	}
	public function executeSearch(sfWebRequest $request)
	{
		
		if($request->getParameter("Go"))
		{
			$this->error=$this->no_error;
			$this->username=$request->getParameter("username");
			$profile = Profile::getInstance();

			$jsObj = new jsadmin_PSWRDS();
			$priv = $jsObj->getPrivilegeForAgent($this->user);

			$priv = explode("+", $priv);
			if(in_array('CSEXEC',$priv) || in_array('CSSUP',$priv) || in_array('LTFSUP',$priv) || in_array('TRNGOP',$priv) ||  in_array('OPSHD',$priv) || in_array('TRNG',$priv) || in_array('P',$priv) || in_array('MG',$priv) || in_array('SLSUP',$priv) || in_array('SLHD',$priv)){
				$flag = 1;
			}
			if(in_array("ExcFld", $priv) || in_array("SupFld", $priv) || in_array("MgrFld", $priv)){
				if(!isset($flag)){
					$this->disabled = "1";
					$this->background_color = "#666666";
				} else {
					$this->background_color = "green";
				}
			} else {
				$this->background_color = "green";
			}
			
			$profile->getDetail($this->username,"USERNAME","PROFILEID","RAW");
			if($profile->getPROFILEID()==null)
			{
				$this->error=$this->not_found;
			}
			if($this->error==-1)
			{
				$this->duplicateProfile=$profile->getPROFILEID();
				$screenObj = new DuplicateProfileScreen;
				$duplicates=$screenObj->fetchDuplicate($profile->getPROFILEID());

				if(is_array($duplicates))
				{
					foreach($duplicates as $key=>$val)
					{

						$profile->getDetail($val,'PROFILEID',"USERNAME","RAW");

						$dup[$val]=$profile->getUSERNAME();

					}
				}
				else
				{
					$this->error=$this->no_duplicate;
					
				}
				$this->duplicates=$dup;
			}
		}
		else
			$this->forward("notduplicate","index");
	}
	public function executeSubmit(sfWebRequest $request)
	{
		//print_r($_POST);die;
		$duplicateProfileScreen=new DuplicateProfileScreen;
		$ids=$request->getParameter("ids");
		$checked=$request->getParameter("profiles");
		$comments=$request->getParameter("comments");
		$totalDuplicates=count($ids);
		$checkedcnt=count($checked);
		$duplicateProfile=$request->getParameter("duplicateProfile");	
			
		if($checkedcnt>0)
		{
			
			$unchecked[]=$duplicateProfile;
			foreach($ids as $key=>$val)
			{
				if(!in_array($val,$checked))
					$unchecked[]=$val;
			}
			foreach($checked as $key=>$val)
			{
				foreach($unchecked as $kk=>$vv)
				{
					$duplicateProfileScreen->removeDuplicateRelation( $val,$vv,$comments,$this->user);
				}
			}
			$uncheckedcnt=count($unchecked);
			if($checkedcnt==1)
			{
				$duplicateProfileScreen->removeDuplicate($checked[0]);
			}
			if($uncheckedcnt==1)
			{
				$duplicateProfileScreen->removeDuplicate($unchecked[0]);
			}
			
			if($checkedcnt>1 && $uncheckedcnt>1)
			{
				foreach($checked as $key=>$val)
					$duplicateProfileScreen->removeDuplicate($val);
				$duplicateProfileScreen->addDuplicates($checked,$this->user,$comments);
			}
			
			JsOpsCommon::updateFtoStatus($checked);
			JsOpsCommon::updateFtoStatus($unchecked);
			
			
		}
		$url=sfConfig::get("app_site_url").$this->controller."notduplicate/index?cid=".$this->cid."&success=1";
		$this->redirect($url);	
		sfView::none;
	}

	public function executeLogin(sfWebRequest $request)
	{
        	JsOpsCommon::login();
	}
}
