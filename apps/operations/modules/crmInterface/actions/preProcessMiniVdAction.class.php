<?php

/**
 * VdProcessAction actions.
 *
 * @package    jeevansathi
 * @subpackage commoninterface
 * @author     Manoj	 
 */
class preProcessMiniVdAction extends sfActions
{
	 /**
	  * Executes index action
	  *
	  * @param sfRequest $request A request object
	  */
        public function executePreProcessMiniVd(sfWebRequest $request){

                $this->cid      =$request->getParameter('cid');
                $this->name     =$request->getParameter('name');
		$submit		=$request->getParameter('submit');
		$clusterName	=$request->getParameter('clusterName');

                $commCrmFuncObj =new CommonCrmInterfaceFunctions();
                $curDate        =date("Y-m-d");
		$vdClusterObj   =new billing_VD_CLUSTER();	
	
                if($submit=='Add Offer'){
			$this->checkArr         	=$request->getParameter('checkArr');
			$this->dataArr               	=$request->getParameter('dataArr');
				
			$doubleValuesArr        =array('vdOfferDate','regDate','expiryDate','score');
			$criteriaMapping	=array("vdOfferDate"=>"VD_OFFER_DATE","loginDate"=>"LAST_LOGIN_DT","activated"=>"ACTIVATED","age"=>"AGE","neverPaid"=>"NEVER_PAID","everPaid"=>"EVER_PAID","regDate"=>"ENTRY_DT","expiryDate"=>"EXPIRY_DT","mtongue"=>"MTONGUE","score"=>"ANALYTIC_SCORE","discount"=>"DISCOUNT","cluster"=>"CLUSTER");	
			//print_r($this->dataArr);

			$cluster =$this->dataArr['cluster'];	
			foreach($this->checkArr as $key=>$value){

				unset($value1);
				unset($value2);

				$criteria       =$criteriaMapping[$key];
				if(in_array($key, $doubleValuesArr)){
					$key1		=$key."1";
					$key2		=$key."2";
					$value1 	=$this->dataArr[$key1];
					$value2 	=$this->dataArr[$key2];
					if($key=='score'){
						if((!intval($value1) && $value1) || (!intval($value2) && $value2))	
							$errorArr[$key] =1;		
					}
					if($value1=='' || $value2==''){
						$errorArr[$key] =1;	
					}
					if(strtotime($value2)<strtotime($value1)){
						$errorArr[$key] =1;
					}
				}
				else{
					$value1 	=$this->dataArr[$key];	
					if($key=='discount' && !intval($value1)){
						$value1 ='';
					}
					if(is_array($value1)){
						$value1 =@implode(",",$value1);
					}
					if($value1==''){
						$errorArr[$key] =1;
					}
				}
				$dataSet[$cluster][$criteria][]=$value1;
				$dataSet[$cluster][$criteria][]=$value2;
				//print_r($dataSet);
			}
			// Error handling
			if(count($errorArr)>0){
				$this->errorArr =$errorArr;
			}
			else{
				// Success
				foreach($dataSet as $cluster=>$criteria){
					foreach($criteria as $key1=>$val1){
						$vdClusterObj->addCluster($cluster,$key1,$val1[0],$val1[1]);
					}
				}
				$submitSuccess =true;
			}

                }
		if($submit=='delete'){
			$vdClusterObj->deleteCluster($clusterName);
			$submitSuccess =true;			
			$this->successMessage =false;
		}
                if($submit=='Upload Offer Now'){

	                $uploadTempObj =new test_VD_UPLOAD_TEMP('newjs_local111');
                        $uploadTempObj->truncate();

                        // Background script execute to pre-process Mini VD data
                        passthru(JsConstants::$php5path." ".JsConstants::$alertSymfonyRoot."/symfony billing:preProcessMiniVdData > /dev/null &");
                        $this->successMessage =true; 
			$submitSuccess =true;
                }
                if($submitSuccess){
                        $clusterDetails =$vdClusterObj->getClusterDetails();
                        if(is_array($clusterDetails)){
				foreach($clusterDetails as $key=>$val){
					$dataArr[$key]['START_DT'] 	=$val['VD_OFFER_DATE']['VALUE1'];
					$dataArr[$key]['END_DT'] 	=$val['VD_OFFER_DATE']['VALUE2'];
					$dataArr[$key]['DISCOUNT'] 	=$val['DISCOUNT']['VALUE1'];				
					$dataArr[$key]['CLUSTER']	=$val['CLUSTER']['VALUE1'];
				}
                        }
			$this->dataArr	=$dataArr;
			$this->setTemplate('vdClusterList');
                }
                // Default page conditions
                $this->vdDateDropdown    =$commCrmFuncObj->getDateDropDown($curDate,15);
	        $urlPath =sfConfig::get("sf_web_dir");
        	include_once($urlPath."/commonFiles/dropdowns.php");
		$this->mtongueDropdown =$MTONGUE_DROP;
	}

}
