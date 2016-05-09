<?php
/**
 * CLASS canSendFactory
 * <p>Super class of {@link canSendEmail} <BR>
 * This class decide first carries all common and factory functionalities for all channels.</p>
 * @package   jeevansathi
 * @author    Esha Jain <esha.jain@jeevansathi.com>
 * @copyright 2015 Esha Jain
 */
class canSendFactory {

  public static function initiateClass($channel,$dataArray,$profileid)
  {
	switch ($channel) 
	{
		case CanSendEnums::$channelEnums[EMAIL]:
                    $channel = CanSendEnums::$channelEnums[EMAIL];
                    $alertType = $dataArray['EMAIL_TYPE'];
                    $subscriptionField = CanSendEnums::$channelTypeToFieldMap[$channel][$alertType];
                    if($subscriptionField)
                        $subscriptionClassObj = new CanSendEnums::$fieldMap[$subscriptionField]['TABLE_CLASS'];
                    $bouncedMailObj = new bounces_BOUNCED_MAILS;
			return new canSendEmail($dataArray,$profileid,$subscriptionClassObj,$bouncedMailObj);
			break;
		default:
			return false;
	}
  }
}
?>
