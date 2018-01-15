<?php
/**
* This class contains the common configuration of mass mailer management system.
* @author lavesh
*/
class MmmConfig
{
	public static $variableMapping = array(
						"EMAIL" => "EMAIL",
						"NAME"  => "NAME",
						"PHONE" => "PHONE"
					);

	public static $mailerType = array(
                                          "urm" => "Url Mail",
					  "hcm" => "Hard Code Mail"
					 );
	public static $mailerPeriodOfStay = array("1" => "1 Month",
						  "3" => "3 Month",
						  "6" => "6 Month",
						  "12" => "1 Year"
					         );
	public static $mailerWebsite = array("J" => "Jeevansathi",
				             "9" => "99acres"
					    );
	public static $typeOfMail = array("P" => "Promotional Mails",
					  "S" => "Service Messages"
					  );
	public static $responseType = array("i" => "Individual Response",
					    "o" => "Overall Response"				
					  );
	public static $yesNo = array("Y" => "YES",		
				     "N" => "NO"
				    );
	public static $paid = array("F" => "YES",
				    "N" => "NO"
				   );
	public static $status = array("NEW" => "NEW",
				      "FORM_QUERY" => "FQ",
				      "WRITE_MAIL" => "WM",			 
				      "STOP" => "S",
	 			      "FIRED" => "F",
				      "RUNNING" => "R",
				      "MARKED_FOR_TESTING" => "TR",
				      "TEST_COMPLETED" => "TC",
				      "RUNNING_COMPLETED" => "RC"); 		  		

	/*Email Links*/
	public static $unsubscribeJsLink   = "/masscomm.php/mmm/unsubscribe";
	public static $unsubscribeJsFlag   = "U";
	public static $spamJsLink          = "/masscomm.php/mmm/unsubscribe";
	public static $spamJsFlag          = "S";

	public static $stagger = 7; 		
	
	public static $dummyName = "Lijuv";
	public static $dummyPhone = "9811122623";
	public static $mailerPeriod = "1";
} 
?>
