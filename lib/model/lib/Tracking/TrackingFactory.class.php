<?php
class TrackingFactory{
	public static function getTrackingClass($name){
		switch($name){
		case 'Incomplete':
			return new IncompleteTracking();
		default:
			return new TrackingClass($id);
		}
	}
   }
