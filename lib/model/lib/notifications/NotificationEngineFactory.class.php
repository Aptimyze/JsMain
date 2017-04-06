<?php
class NotificationEngineFactory{
        private $gcmObj;

        public function __construct($sendMultipleParallelNotification=false){
                $this->gcmObj = new GCM($sendMultipleParallelNotification);
        }

	public function geNotificationEngineObject($type){
                switch($type){
                	case GCM:
                	        return $this->gcmObj;
                	        break;
                	case IOS:
                	        return new IOS();
                	        break;
                }
        }
}
