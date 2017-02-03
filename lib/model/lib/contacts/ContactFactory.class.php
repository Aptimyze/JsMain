<?php
/** CLASS ContactFactory
 * <p>Produces Contact events objects on the basis of current contact status and to be contact status<br>
 * Contact Events: <br>
 * {@link Accept}, {@link Decline}, <BR>
 * {@link CancelAccept}, {@link Reminder}, <BR>
 * {@link CancelContact}, {@link WriteMessage} <BR>
 * and {@link Initiate} <BR></p>
 * 
 * @package jeevansathi
 * @subpackage contacts
 *
 * @author Tanu Gupta <tanu.gupta@jeevansathi.com>
 * @copyright 2012 Tanu Gupta
 * @link      http://devjs.infoedge.com/mediawiki/index.php/FTO
 */
class ContactFactory
{

  /**
   * 
   *
   * @param ContactHandler contactHandleObj 

   * @return ContactEvent $eventObj
   * @static
   * @access public
   */
  public static function event( $contactHandlerObj ) {
		$engineType=$contactHandlerObj->getEngineType();
		$contactType=$contactHandlerObj->getContactType();
		$viewerObj=$contactHandlerObj->getViewer();
		$viewedObj=$contactHandlerObj->getViewed();


		if(!$viewerObj || !$viewedObj)
			throw new JsException("","Sender,Receiver not specified");
		if($engineType==ContactHandler::EOI)
		{
			if($contactHandlerObj->getToBeType())
			{

				switch($contactHandlerObj->getToBeType())
				{
						
					case ContactHandler::INITIATED:
						$action = new Initiate($contactHandlerObj);
						break;
						
					case ContactHandler::ACCEPT:
						$action = new Accept($contactHandlerObj);
						break;
						
					case ContactHandler::REMINDER:
						$action = new Reminder($contactHandlerObj);
						break;
						
					case ContactHandler::WRITE_MESSAGE:
						$action = new WriteMessage($contactHandlerObj);
						break;
						
					case ContactHandler::CANCEL_CONTACT:
						$action = new CancelContact($contactHandlerObj);
						break;
						
					case ContactHandler::DECLINE:
						$action = new Decline($contactHandlerObj);
						break;
						
					case ContactHandler::CANCEL:
						$action = new CancelAccept($contactHandlerObj);
						break;
						
				}
			}
			else
			{
				switch($contactHandlerObj->getContactType())
				{
						
					case ContactHandler::NOCONTACT:
						$contactHandlerObj->setToBeType(ContactHandler::INITIATED);//setting accept as default 
						$action=new Initiate($contactHandlerObj);
						break;
						
					case ContactHandler::INITIATED:
						if($contactHandlerObj->getContactInitiator()==ContactHandler::RECEIVER)
						{
							$contactHandlerObj->setToBeType(ContactHandler::ACCEPT);//setting accept as default 
							$action = new Accept($contactHandlerObj);
							//$action = new Decline($contactHandlerObj);
				//			$mergedComponent = new MergedComponent;
				//			$mergedComponent->component1=$component1;
				//			$mergedComponent->component2=$component1;
				//			$mergedComponent->templateName= "tpl";
						}
						elseif($contactHandlerObj->getContactInitiator()==ContactHandler::SENDER)	
						{
							$contactHandlerObj->setToBeType(ContactHandler::REMINDER);//setting accept as default 
							$action = new Reminder($contactHandlerObj);
						}
						break;
						
					case ContactHandler::ACCEPT:
							$contactHandlerObj->setToBeType(ContactHandler::WRITE_MESSAGE);//setting accept as default 
							$action = new WriteMessage($contactHandlerObj);
						break;
						
					case ContactHandler::CANCEL_CONTACT:
						$contactHandlerObj->setToBeType(ContactHandler::INITIATED);//setting accept as default 
						$action = new Initiate($contactHandlerObj);
						break;
						
					case ContactHandler::DECLINE:
					case ContactHandler::CANCEL:
						$contactHandlerObj->setToBeType(ContactHandler::ACCEPT);//setting accept as default 
						$action = new Accept($contactHandlerObj);
						break;
				}
			}

					$memObject=JsMemcache::getInstance();
					$memObject->delete('commHistory_'.$viewerObj->getPROFILEID().'_'.$viewedObj->getPROFILEID());
					$memObject->delete('commHistory_'.$viewedObj->getPROFILEID().'_'.$viewerObj->getPROFILEID());
					// block to delete the myjs cached data for ms and apps
					$memObject->delete(MyJsMobileAppV1::getCacheKey($viewerObj->getPROFILEID()).'_I');
					$memObject->delete(MyJsMobileAppV1::getCacheKey($viewedObj->getPROFILEID()).'_I');
					$memObject->delete(MyJsMobileAppV1::getCacheKey($viewerObj->getPROFILEID()).'_A');
					$memObject->delete(MyJsMobileAppV1::getCacheKey($viewedObj->getPROFILEID()).'_A');
					$memObject->delete(MyJsMobileAppV1::getCacheKey($viewerObj->getPROFILEID()).'_M');
					$memObject->delete(MyJsMobileAppV1::getCacheKey($viewedObj->getPROFILEID()).'_M');

			return $action;

		}
		else if($engineType==ContactHandler::INFO)
		{
			return (new ViewContacts($contactHandlerObj));
		}
		else
			throw New JsException("","No engine specified");
		
  } // end of member function buildComponent





} // end of CommunicatorComponentFactory
?>
