<?php

/**
 * Auto Select actions.
 *
 * @package    jeevansathi
 * @subpackage profile
 * @author     Nikhil dhiman
 * @version    SVN: $Id: actions.class.php 23810 2011-07-14 03:07:44 Nikhil dhiman $
 */
/**
 * Auto Select feature.<p></p>
 * 	
 *  
 * @author Nikhil dhiman
 */

class autoMobileSelectAction extends sfAction
{
	
	 /**
     * Automatically calls before the action to execute.
     *
     */
	public function preExecute()
	{
		$this->fObj=new FieldOrder;
	}
	
	/**
     * Handles Detailed profile of user, all validations, 
     * error message are handled in this.
     *@param $request contains sfWebrequest parameter send by symfony
     *
     */
	public function execute($request)
	{
		$this->getResponse()->setContentType('application/json');
		
		$type=$request->getParameter("t");
		if($type=="caste")
			$data=$this->getAllCaste();
		if($type=="city")
			$data=$this->getAllCity();
		if($type=="native_city")
			$data=$this->getNativeCity();
		echo json_encode($data);
		return sfView::NONE;
			
	}

	public function getAllCity()
	{
		$arr=array("51"=>"1","128"=>"1");
		foreach($arr as $key=>$val)
		{
			if($key==51 || $key==128)
			{
				
			$this->fObj->setDefault("city",array($key),"","");
			$this->fObj->UpdateSelect();
			$temp=$this->fObj->getJson();
			foreach($temp as $kk=>$vv)
			$data[$key][]=array($vv[0],$vv[1]);	
		}
			//break;
		}
		return $data;	
	}
	public function getAllCaste()
	{
		$arr=FieldMap::getFieldLabel("religion_caste",'',1);
		foreach($arr as $key=>$val)
		{
			
				$valArr=explode(",",$val);
				
				if($key==1 && MobileCommon::isMobile())
				{
					$data[$key] = $this->getImpCaste();
				}
				else
				{
					$data[$key][]=array("","Please select");
					foreach($valArr as $kk=>$vv)
					{
						$caste=FieldMap::getFieldLabel("caste",$vv);
						
						$caste=preg_replace('/[A-Z][a-z]{3,10}[:][ ]/',"",$caste);
						$data[$key][]=array($vv,$caste);
					}
				}	
		}
		return $data;
		
		
	}
	
	private function getImpCaste()
	{
		$request=sfContext::getInstance()->getRequest();
		$mtongue = $request->getParameter("m");
		$this->fObj->setDefault("impcaste",array(),"","");
		$this->fObj->setDefaultExist(1);
		$this->fObj->UpdateSelect();
		$impCasteJSon = $this->fObj->getJson();
		$this->fObj=null;
		$this->fObj=new FieldOrder;
		$this->fObj->setDefault("caste",array("1"),"","");
		$this->fObj->UpdateSelect();
		$CasteJSon = $this->fObj->getJson();
		
		unset($CasteJSon[0]);
		$newJson = array();	
		$newJson[0]=array("","Please select",1,0);
		$cnt = 1;
		if($impCasteJSon && $CasteJSon)
		{
			foreach($impCasteJSon as $key=>$val)
			{
				if($val[0]==$mtongue)
				{
					$newJson[$cnt]=array($val[1],$val[3],0,0);
					$cnt++;
				}
			}
			$newJson[$cnt]=array("","-----",0,1);
			$cnt++;	
			foreach($CasteJSon as $key=>$val)
			{
				
						$newJson[$cnt]=$val;
						$cnt++;
				
			}
			return $newJson;
		}
	}
	
	private function getNativeCity()
	{
		$request=sfContext::getInstance()->getRequest();
		$linked_state = $request->getParameter("l");
		$this->fObj->setDefault("native_city",array($linked_state),"","");
		$this->fObj->UpdateSelect();
		$CityJSon = $this->fObj->getJson();
		
		unset($CityJSon[0]);
		$newJson = array();	
		$newJson[0]=array("","Please select",1,0);
		if($CityJSon)
		{
			foreach($CityJSon as $key=>$val)
			{
				$stateIndex = $val[3];unset($val[3]);
				if(!array_key_exists($stateIndex,$newJson))
				{
					$newJson[$stateIndex][]=array("","Select city",0,0);
				}
				$newJson[$stateIndex][]=$val;
			}
			
			foreach($newJson as $stateIndex=>$listSubCity)
			{
				$len = count($newJson[$stateIndex]);
				$newJson[$stateIndex][]=array("0","Others(please specify)",0,0);
			}
			return $newJson;
		}
		
	}
}
?>
