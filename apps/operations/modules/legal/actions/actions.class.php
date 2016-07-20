<?php

/**
 * legal actions.
 *
 * @package    jeevansathi
 * @subpackage legal
 * @author     Sanyam Chopra
 * @version    14th July 2016
 */
class legalActions extends sfActions
{

	
	public $controller="/operations.php/";
	
 /**
  * Executes index action
  *
  * @param sfRequest $request A request object
  */
  public function executeIndex(sfWebRequest $request)
  {
    
		return sfView::NONE;
  }

  //This function is associated with NameLocationAgeSearchSuccess.tpl and is used to fetch parameters from the form on submit and display results on the same page after evaluation
  public function executeNameLocationAgeSearch(sfWebRequest $request)
  {
  	$request=sfContext::getInstance()->getRequest();
  	$this->cid=$request->getParameter(cid);
  	$this->success=$request->getParameter("success");
  	$this->user=JsOpsCommon::getcidname($this->cid);
  	$generatedUrl=($this->generateUrl()!='/')?$this->generateUrl():$this->controller;
  	$this->moduleurl=$generatedUrl.$this->getModuleName();

  	//One of Name and Address is mandatory
  	if($request->getParameter("username") =="" && $request->getParameter("address") == "" && $request->getParameter("email") == "" )
  	{
  		$this->error = "Please Provide atleast one of Name, Address, Email";
  	} 
  	//if any one of Name or Address is filled
  	elseif($request->getParameter("username") != "" || $request->getParameter("address") != "" || $request->getParameter("email") != "") 
  	{
  		$this->username=trim($request->getParameter("username"));
  		$this->age=$request->getParameter("age");
  		$this->address = trim($request->getParameter("address"));
  		$this->email = trim($request->getParameter("email"));
  		$nameArr = "";
  		$addressArr = "";
  		$nameArr = $this->getArrValues($this->username);
  		$addressArr = $this->getArrValues($this->address);
  		$nameAgeSearchObj =  new nameAgeSearch();
  		$this->legalDataArr = $nameAgeSearchObj->getProfilesForLegal($nameArr,$this->age,$addressArr,$this->email);
  		$this->legalDataCount = count($this->legalDataArr);
  		$this->noResultsFoundMsg = "No results found.";
  	}

  	$this->setTemplate("NameLocationAgeSearch");
  }

  //This function converts the input string into an array
  public function getArrValues($data)
  {
  	if(strpos($data," "))
  	{
  		$dataArr = explode(" ",$data);	
  	}
  	elseif(strlen($data))
  	{
  		$dataArr[] = $data;
  	}
  	return $dataArr;
  }
}
