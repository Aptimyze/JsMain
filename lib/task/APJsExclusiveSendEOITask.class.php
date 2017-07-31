<?php

/**
 * This task gets all the profiles for which EOI to be sent on user behalf by Agent.
 * 
 * @package    jeevansathi
 * @author     Nitesh Sethi
 */
ini_set('memory_limit','512M');
class APJsExclusiveSendEOITask extends sfBaseTask
{
	private $errorMsg;
    private $minEois = 10;
    private $clusterForMutualMatches = array(0=>'LAST_ACTIVITY');
    private $removeFilteredProfiles = true;
    private $maxEoiReceiver = 5;
    private $lastLoginDateCondition = 15;
    private $lastLoginDays = 17;
    private $verifyActiveDays = 7;
    private $isOneTime = 0;

	
  protected function configure()
  {
	$this->showTime=time();
          //$this->addArguments(array( 	));
	$this->addOptions(array(
       new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name','jeevansathi'),
     ));
    $this->namespace        = 'cron';
    $this->name             = 'APJsExclusiveSendEOI';
    $this->briefDescription = 'AP Send EOIs of JsExclusinve memebers from backend';
    $this->detailedDescription = <<<EOF
The [APJsExclusiveSendEOI|INFO] task does things.
Call it with:

  [php symfony cron:APJsExclusiveSendEOI |INFO]
EOF;

  }
	/**
    * set the array errorTypeArr value for the given type
    * @return void
    * @access protected
    */	
	protected function execute($arguments = array(), $options = array())
	{
		sfContext::createInstance($this->configuration);              
		$autoContObj = new ASSISTED_PRODUCT_AUTOMATED_CONTACTS_TRACKING();
		$senderReceiverMappingObj = new billing_EXCLUSIVE_CLIENT_MEMBER_MAPPING();
        $data=$senderReceiverMappingObj->getPendingJsExclusiveEoiData();
     //  print_r($data);die;
		try{
			foreach($data as $key=>$val)
			{
				$senderProfileObj = new Profile();
				$senderProfileId = $val['CLIENT_ID'];
				$senderProfileObj->getDetail($senderProfileId, "PROFILEID");
				
				$recProfileObj = new Profile();
				$recProfileId = $val['MEMBER_ID'];
				$recProfileObj->getDetail($recProfileId, "PROFILEID");
				$contactEngineObj = $this->sendEOI($senderProfileObj, $recProfileObj);
			                           
				if($contactEngineObj){
					if($contactEngineObj->getComponent()->errorMessage != '')
					{
						// if any error occurs send mail
						 $mailMes = "AP error -> ".$contactEngineObj->getComponent()->errorMessage." Sender: $senderId Receiver: $receiverId ";
						$this->Showtime("Error $mailMes");
						$val["FAILURE_REASON"]=$contactEngineObj->getComponent()->errorMessage;
						$senderReceiverMappingObj->updateSendEoiError($val['Id'],$val["FAILURE_REASON"]);
						//SendMail::send_email("nikhil.dhiman@jeevansathi.com,hemant.a@jeevansathi.com",$mailMes,"Contacts entry error in APSendEOITask.class.php");
					}
					else
					{
						//tracking of EOI sent
						try{
								$autoContObj->insertIntoAutoContactsTracking($senderProfileId,$recProfileId);
								$senderReceiverMappingObj->updateScreenedStatus($val['ID']);
				        }
						catch(Exception $ex)
						{
							$this->setExceptionError($ex);
						}
					}
				}
			}

		}
		catch(Exception $ex)
		{
			$this->setExceptionError($ex);
		}	
	}
	
	/** logs sfException
	@param $ex Exception Obj
	*/
	private function setExceptionError($ex)
	{
		$this->errorMsg=" ".$ex->getMessage();
		
	}
	/**
    * send EOI's
    * @return $contactEngineObj
    * @param $profileObj
    * @param $receiverObj
    * @access private
    */	
	
	private function sendEOI($profileObj,$receiverObj)
	{
		
		try
		{
			$contactObj = new Contacts($profileObj, $receiverObj);
			if($contactObj->getTYPE() == 'N')
			{
				$contactHandlerObj = new ContactHandler($profileObj,$receiverObj,"EOI",$contactObj,'I',ContactHandler::POST);
				$contactHandlerObj->setPageSource("AP");		
				$contactHandlerObj->setElement("MESSAGE",'');
				$contactHandlerObj->setElement("STATUS","I");
				$contactHandlerObj->setElement("PROFILECHECKSUM",JsCommon::createChecksumForProfile($profileObj->getPROFILEID()));
				$contactHandlerObj->setElement("STYPE",3);
				$contactEngineObj=ContactFactory::event($contactHandlerObj);
				return $contactEngineObj;
			}
		}
		catch(Exception $e)
		{
			$this->setExceptionError($e);
			//$this->errorMsg = $this->errorMsg.'Caught Exception: '. $profileObj->getPROFILEID().'->'.$receiverObj->getPROFILEID().'=>'.$e->getMessage(). "";
		}
		
				
		return null;
		
	}
	public function Showtime($mes)
	{
		$time=time();
		echo "\n---$mes-->".($time-$this->showTime);
		$this->showTime=$time;
	}
	
}
