<?php
class NotificationEngineFactory{
        private $sendMultipleParallelNotification;

        public function __construct($sendMultipleParallelNotification=false){
                //$this->gcmObj = new GCM($sendMultipleParallelNotification);
		$this->sendMultipleParallelNotification =$sendMultipleParallelNotification;
        }

	public function geNotificationEngineObject($engineType,$notificationType=''){
                switch($engineType){
                	case GCM:
				$this->gcmObj = new GCM($this->sendMultipleParallelNotification);
                	        return $this->gcmObj;
                	        break;
                	case IOS:
                	        return new IOS();
                	        break;
			case FCM:
				return new BrowserFCM($notificationType,$this->sendMultipleParallelNotification);
				break;
                }
        }
}
