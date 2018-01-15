<?php
class NotificationEngineFactory{

	public static function geNotificationEngineObject($type){
                switch($type){
                	case GCM:
                	        return new GCM();
                	        break;
                	case IOS:
                	        return new IOS();
                	        break;
                }
        }
}
