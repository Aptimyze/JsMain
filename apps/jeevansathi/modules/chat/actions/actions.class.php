<?php

/**
 * chat actions.
 *
 * @package    jeevansathi
 * @subpackage chat
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class chatActions extends sfActions
{
	/**
	* Executes authenticateChatSession action  - returns jid,sid and rid for chat session
	*
	* @param sfRequest $request A request object
	*/
 	public function executeAuthenticateChatSessionV1(sfWebRequest $request)
 	{
		$xmppPrebind = new XmppPrebind('localhost', 'http://localhost:7070/http-bind/', 'converse', false, false);
		$username = substr("a1@localhost", 0,2);
		$xmppPrebind->connect($username, '123');
		$xmppPrebind->auth();
		$response = $xmppPrebind->getSessionInfo(); // array containing sid, rid and jid

		$apiResponseHandlerObj = ApiResponseHandler::getInstance();
		$apiResponseHandlerObj->setHttpArray(ResponseHandlerConfig::$SUCCESS);
		$apiResponseHandlerObj->setResponseBody($response);
		$apiResponseHandlerObj->generateResponse();
		die;
 	}
}
?>