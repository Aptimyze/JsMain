<?php
/*
 * GotItBand.class.php
 * 
 * Esha Jain <esha.jain@jeevansathi.com>
 * 
 * @brief This class is used to handle all functionalities related to got it education band
 * @author Esha Jain
 * @created 2013-07-08
 */
class GotItBand
{
	private $profile;
	public static $MYJS = 2;
	public static $CONTACTS = 3;
	public static $PROFILE = 5;
	public static $MATCHALERT = 7;
	public static $KUNDLI_MATCHES =11;
	public static $MATCH_2WAY = 13;
	public static $educationMYJS="Here you can view  a summary of all activities related to your account";
	public static $educationCONTACTS="These are Interests, Requests and Messages you have received. Respond to them to proceed further";
	public static $educationPROFILE="Here you can upload your photographs and update information about your Self, Family, Work and Education.";
	public static $educationMATCHALERT="Here you can see all the 'Daily Recommendations' which are sent to you on Email in the last 45 days";
	public static $educationKUNDLI_MATCHES="Here you can see all Members who match your Kundli (Guna Score between you and the member is at least 18)";
	public static $educationMATCH_2WAY="Here you can see those matches  where both of you match each others' Partner Preference";
	public function __construct($profile)
	{
		if (!isset($profile))
			throw ("No Profile id or object is provided in GotIdBand.class.php");
		if ($profile instanceof Profile)
			$this->profile = $profile;
		else
		{
			$this->profile = new Profile('', $profile);
		}
	}
	public function showBand($page,$entryDate)
	{
		$compareDate = date("Y-m-d H:i:s",mktime(date("H"), date("i"), date("s"), date("m")-1, date("d"), date("Y")));
		if($entryDate>=$compareDate)
		{
			$gotItBandDbObj = new newjs_GOT_IT_BAND;
			$data = $gotItBandDbObj->getArray(array("PROFILEID"=>$this->profile->getPROFILEID()),'','','*');
			$profilePagesData = $data[0]['PAGES_DONE'];
			return $this->showBandOnPage($page,$profilePagesData);
		}
		else
			return false;
	}
	public function showBandOnPage($page,$profilePagesData)
	{
		if(!$profilePagesData)
			return true;
		if($profilePagesData%$page==0)
			return false;
		return true;
	}
	public function setPageBandDone($page)
	{
		$profileid = $this->profile->getPROFILEID();
		$gotItBandDbObj = new newjs_GOT_IT_BAND;
		$done = $gotItBandDbObj->insert($page,$profileid);
		if(!$done)
			$done = $gotItBandDbObj->update($page,$profileid);
	}
}
?>
