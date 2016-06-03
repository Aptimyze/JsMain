<?php

/**
 * sem actions.
 *
 * @package    jeevansathi
 * @subpackage sem
 * @author     Kunal Vermaa
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class semActions extends sfActions
{
 /**
  * Executes index action
  *
  * @param sfRequest $request A request object
  */
  public function executeIndex(sfWebRequest $request)
  {
	$this->cid=$request->getParameter(cid);
    
    $this->bSuccess 	= false;
	$this->bRefresh		= false;
	$this->bEdit 		= false;
	$this->bParseError 	= false;
	
    $objStore = new jsadmin_SEM_CUSTOM_REG_PAGE;
	$arrCustomPage = $objStore->fetchAllRecords();
	
	$arrPages = array();
	$this->arrContent 	= array();
	$this->arrPages   	= array();
	$this->arrTime		= array();
	$this->arrTitle		= array();
	
	$format = 'Y-m-d H:i:s';
    //Listing Array
    foreach($arrCustomPage as $row)
    {
		$val = $row['PAGE_ID'];

		$this->arrPages[$val] 		= $val;
		$this->arrContent[$val] 	= $row['CONTENT'];
		$this->arrTitle[$val] 		= (strlen($row['TITLE'])==0?"Untitled":$row['TITLE']);
		//Decorating TimeStamp
		$time = DateTime::createFromFormat($format,$row['TIME']);
		$this->arrTime[$val] 		= $time->format('j-M-Y h:i:s');
	}
	
	//Form 
    $this->form = new sfForm();
    $this->form->setWidgets(array(
	'htmlCode' => new sfWidgetFormTextArea(array('label'=>" "),array('cols'=>"95",'rows'=>"18",'title' => "Paste Your HTML Code Here",'class'=>"defaultText",'id'=>'notes')),
	'title'	   => new sfWidgetFormInput(array('label'=>" "),array('placeholder'=>"Enter the page title here !!")),
	));
    
    //Paramters
    $action = $request->getParameter('act');
    $submit = $request->getParameter('submit');
    $modify = $request->getParameter('modify');
	$html 	= $request->getParameter('htmlCode');
	$url 	= sfConfig::get(app_site_url);
	
	//New Html Page
    if(isset($submit) && isset($action) && $action=="new" && $html != "" && $html != "Paste Your HTML Code Here")
    {
		$bResult = $this->parseHTML($html);
		$szTitle = $request->getParameter('title');
		if($bResult == true)
		{
			$this->page_id 	= $objStore->insertRecord($html,$szTitle);//Add this page in backend
			$this->bSuccess = true;
			$this->bRefresh	= true;
			$this->Msg = "Html Page Successfully Submitted !! And Its Page-Id is $this->page_id.";
		}
		else
		{
			$this->form->setDefaults(array('htmlCode'=>$html,'title'=>$szTitle));
			$this->bParseError = true;
		}
	}
	//Delete
	if(isset($action) && $action=="delete")
    {
		$p = $request->getParameter("p");
		$this->bSuccess = true;
		$this->bRefresh	= true;
		if(array_key_exists($p,$this->arrPages))
		{
			$objStore->deleteRecord($p);
			$this->Msg = "Html Page Successfully Deleted.";
		}
		else
		{
			$this->Msg = "Html PageId : $p does not exist.";
		}
	}
	//Edit
	if(isset($action) && $action=="edit")
    {
		$p = $request->getParameter("p");
		if(array_key_exists($p,$this->arrPages))
		{
			$htmlEdit = $this->arrContent[$p];
			$titleEdit = $this->arrTitle[$p];
			$this->bEdit = true;
			$this->iPageID = $p;
			$this->form->setDefaults(array('htmlCode'=>$htmlEdit,'title'=>$titleEdit));
		}
		else
		{
			$this->bSuccess = true;
			$this->bRefresh	= true;
			$this->Msg = "Html PageId : $p does not exist.";
		}
	}
	//Update Data
	if(isset($modify) && isset($action) && $action=="new" && $html != "" && $html != "Paste Your HTML Code Here")
	{
		$bResult = $this->parseHTML($html);
		$p = $request->getParameter("iPageID");
		$szTitle 	= $request->getParameter('title');
		if($szTitle == "Untitled")
			$szTitle = "";
		
		if($bResult)
		{
			$objStore->updateRecord($p,$html,$szTitle);
			$this->bSuccess = true;
			$this->bRefresh	= true;
			$this->iPageID = -1;
			
			$this->Msg = "Html Page Successfully Updated !! And Its Page-Id is $p.";
		}
		else
		{
			$this->form->setDefaults(array('htmlCode'=>$html,'title'=>$szTitle));
			$this->bParseError = true;
			$this->bEdit = true;
			$this->iPageID = $p;
		}
	}
  }
  
  private function parseHTML(&$szHtml)
  {
		$arrKeyWords = array(
							"Heading"=>"HEADING",
							"SubHead1"=>"SUBHEAD1",
							"SubHead2"=>"SUBHEAD2",
							"Form Open Tag"=>"<form>",
							"Form Close Tag"=>"</form>",
							"Css Backfround Image Property"=>"IMAGEURL",
							"Body close tag"=>"</body>",
							"Body tag"=>"<body",
							"Member login anchor id"=>"mem_login",
							);
		$arrCheck = array(
							"Heading"=>"HEADING",
							"SubHead1"=>"SUBHEAD1",
							"SubHead2"=>"SUBHEAD2",
							"Css Backfround Image Property"=>"IMAGEURL",
							"Member login anchor id"=>"mem_login",
							);					
		$arrMsg = array();
		foreach($arrKeyWords as $szKey=>$szVal)
		{
			$bIsExist = stripos($szHtml,$szVal);
			if($bIsExist === false)
			{
				$arrMsg[] = $szKey;
			}
		}
		
		if(count($arrMsg))
		{
			$this->szParserMsg = implode(",",$arrMsg) . " Keyword Missing in html.";
			return false;
		}
		else
		{
			//Replacing all Keywords with the Keywords(in arrCheck)
			foreach($arrCheck as $szKey=>$szVal)
			{
				$arrTemp = preg_split("/$szVal/i",$szHtml);
				$szHtml = implode($szVal,$arrTemp);
			}
		}
		return true;
  }
}
