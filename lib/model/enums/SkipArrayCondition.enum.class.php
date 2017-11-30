<?php

class SkipArrayCondition {
	public static $PEOPLE_WHO_VIEWED_MY_CONTACTS = array(
						"CONTACT"=>array(
									ContactHandler::DECLINE,
									ContactHandler::CANCEL,
									ContactHandler::CANCEL_CONTACT),
						"IGNORE");
	public static $CONTACTS_VIEWED = array(
						"CONTACT"=>array(
									ContactHandler::DECLINE,
									ContactHandler::CANCEL,
									ContactHandler::CANCEL_CONTACT),
						"IGNORE");
  public static $SHORTLIST = array(
						"CONTACT"=>array(
									ContactHandler::DECLINE,
									ContactHandler::CANCEL,
									ContactHandler::CANCEL_CONTACT),
						"IGNORE");
	
	public static $MESSAGE = array(ContactHandler::DECLINE,
									ContactHandler::CANCEL,
									"IGNORE");
	public static $PHOTO_REQUEST = array(
						"CONTACT"=>array(
									ContactHandler::DECLINE,
									ContactHandler::CANCEL),
						"IGNORE");
	public static $HOROSCOPE = array(
						"CONTACT"=>array(
									ContactHandler::DECLINE,
									ContactHandler::CANCEL),
						"IGNORE");
	public static $INTRO_CALLS = array(
								"IGNORE");
	public static $INTRO_CALLS_COMPLETE = array(
								"IGNORE");
	public static $MATCHALERT = array(
						"CONTACT"=>array(
									ContactHandler::DECLINE,
									ContactHandler::CANCEL,
									ContactHandler::INITIATED,
									ContactHandler::ACCEPT,
									ContactHandler::CANCEL_CONTACT),
						"IGNORE");
	public static $VISITOR = array(
						"CONTACT"=>array(
									ContactHandler::DECLINE,
									ContactHandler::CANCEL,
									ContactHandler::INITIATED,
									ContactHandler::ACCEPT,
									ContactHandler::CANCEL_CONTACT),
						"IGNORE");
	public static $default = array("IGNORE");
	
	public static $SkippedAll = array(
						"CONTACT"=>array(
									ContactHandler::DECLINE,
									ContactHandler::CANCEL,
									ContactHandler::INITIATED,
									ContactHandler::ACCEPT,
									ContactHandler::CANCEL_CONTACT),
						"IGNORE");
        public static $MATCHOFTHEDAY = array(
						"CONTACT"=>array(
									ContactHandler::DECLINE,
									ContactHandler::CANCEL,
									ContactHandler::INITIATED,
									ContactHandler::ACCEPT,
									ContactHandler::CANCEL_CONTACT),
						"IGNORE");
	public static $MESSAGE_CONSIDER = array (
							"CONTACT"=>array(
										ContactHandler::ACCEPT
									)
						);
									
}
