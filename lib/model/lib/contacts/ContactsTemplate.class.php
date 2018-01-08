<?php
/**
 *CLASS ContactsTemplate
 * Fetch templatename that needs to be fetched for contact Engine
 * 
 * <code>
    * ***Fetch array of error function to call
		*	$contactsTemplateObj=new ContactsTemplate();
		* $ContactsTemplateObj->getTemplateName($contactType,$profileState,$toBeStatus,$engineType,$pageSource,$contactInitiator,$action,$checkSenderReceiver);
		* <format >
		* contactType=I,A,D,C,E,N
		* </format>
		* <format >
		* profileState=C1,C2,C3,D1,D2,D3,D4,E1,E2,E2,F,P
		* </format> 
		* <format >
		* tobestatus=I,A,D,C,E
		* </format>
		* <format>
		* engineType=EOI,INFO
		* </format>
		* <format>
		* pageSource=VDP,SEARCH,CONTACTS,ALBUM
		* </format>
		* <format>
		* ContactInitiator=S,R
		* </format>
		* <format>
		* Action=PRE,POST
		* </format>
		* <format>
		* checkSenderReceiver=Y,N,''
		* </format>
		* </code>
 * PHP versions 4 and 5
 * @package   jeevansathi
 * @subpackage   contacts
 * @author    Nikhil Dhiman <nikhil.dhiman@jeevansathi.com>
 * @copyright 2012 Nikhil Dhiman
 * @version   SVN: 9619
 * @link      http://devjs.infoedge.com/mediawiki/index.php/FTO
  */
class ContactsTemplate
{
	/**
	 * Intialize templateData
	 */
	public function  __construct()
	{
		$this->templateData = ContactEngineMap::getFieldLabel("template_data","",1);
		
	}
	/**
	 * Return template name
	 * @param String $contactType 
	 * @param String $profileState
	 * @param String $tobeStatus
	 * @param String $pageSoucre
	 * @param String $ContactInintator
	 * @param String $action
	 * @param String $checkSenderReceiver
	 * @return Array $array
	 */
	public function getTemplateName($contactType="",$profileState="",$toBeStatus="",$engineType="",$pageSource="",$contactInitiator="",$action="",$checkSenderReceiver= "")
	{
		if($contactType == '' && $profileState == '' && $engineType == '' && $action == '')
		{
			throw new jsException("",'Required Input Parametes are not given');
		}
		
		$tempArr['CONTACT_TYPE']=$contactType;
		$tempArr['PROFILE_STATE']=$profileState;
		$tempArr['ENGINE_TYPE'] = $engineType;
		$tempArr['ACTION_TYPE'] = $action;
		
		if($engineType=='INFO' && !$contactInitiator)
			throw new jsException("",'Contact Initiator is not given');
			
		if($contactInitiator) 
			if($engineType=="INFO" || $checkSenderReceiver)
				$tempArr[SENDER_RECEIVER]=$contactInitiator;
				
		if($toBeStatus)
			$tempArr['TO_BE_STATUS'] = $toBeStatus;
				
		if($pageSource)
			$tempArr['PAGE'] = $pageSource;	
		foreach($this->templateData as $val)
		{
			$arr = array_diff_assoc($tempArr,$val);
                        if(empty($arr))
			{  
				return $val['TEMPLATE_NAME'];
			}
		}
	}
}
?>
