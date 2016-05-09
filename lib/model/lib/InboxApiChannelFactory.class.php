<?php
/**
 * @brief This class is main factory class for all the channels in inbox api
 */

class InboxApiChannelFactory
{

    /* This function will return the object of required inbox channel
	*@param params : will include request and other parameters required (optional)
    *@return classobj : using mobile common it will return search channel object
    */

    public static function getChannel($params="")    
    {
		if(MobileCommon::isApp()=="I")
		{
			$message = "Call to InboxApiChannelFactory::getChannel() returned isIOS() true";
			$subject = "Inbox Alert";
			SendMail::send_email("nsitankita@gmail.com",$message,$subject);		
		}
        else if(MobileCommon::isApp()=="A")
		{
			$message = "Call to InboxApiChannelFactory::getChannel() returned isApp() true";
			$subject = "Inbox Alert";
			SendMail::send_email("nsitankita@gmail.com",$message,$subject);
		}
		else if(MobileCommon::isMobile())
		{
			$message = "Call to InboxApiChannelFactory::getChannel() returned isMobile() true";
			$subject = "Inbox Alert";
			SendMail::send_email("nsitankita@gmail.com",$message,$subject);
		}
		else
		{	
			return new InboxJSPC($params);
		}
    }
}
?>
