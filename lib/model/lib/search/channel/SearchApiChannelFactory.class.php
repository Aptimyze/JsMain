<?php
/**
 * @brief This class is main factory class for all the channels in search api
 * @author Reshu Rajput / Lavesh Rawat
 * @created 1 Sep 15
 */

class SearchChannelFactory
{

        /* This function will return the object of required search channel
	*@param params : will include request and other parameters required (optional)
        *@return classobj : using mobile common it will return search channel object
        */
        public static function getChannel($params="")
        {
		if(MobileCommon::isApp()=="I")
		{
			return new SearchJSIOS($params);
		}
                elseif(MobileCommon::isApp()=="A")
		{
			return new SearchJSAPP($params);
		}
		elseif(MobileCommon::isMobile())
		{
			return new SearchJSMS($params);
		}
		else
		{	
			return new SearchJSPC($params);
		}
        }

}
?>
