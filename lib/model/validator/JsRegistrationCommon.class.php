<?php
class JsRegistrationCommon
{
	public static function getSourcePage()
	{
		$page = $_SERVER['PHP_SELF'];
		$php_page=explode("/",$page);
		$count=count($php_page);
		$count--;
		$source_page=trim($php_page[$count]);

		$pageArray=array(
		"registration_page1.php"=>"REG",
		"bck_registration_ajax_validation.php"=>"BREGAJX",
		"validate_input.php"=>"VALIN",
		"validate_function.php"=>"VALFN",
		"check.php"=>"CHECK",
		"order_match_astrol.php"=>"ASTRO",
		"forward_profile.php"=>"FWDPR",
		"promo_entry.php"=>"PROEN",
		"auto_reg_functions.php"=>"AUTOR",
		"1min.php"=>"A_MIN",
		"verify_email_Id.php"=>"VERID",
		"faq_other.php"=>"FAQ",
		"unsubscribe.php"=>"UNSUB",
		"rav.php"=>"RAV",
		"registration_pg1.php"=>"REG1",
		"myjs_gmailid.php"=>"GMAIL",
		"order_astrol.php"=>"OAST",
		"registration_page4.php"=>"REG4",
		"registration_new.php"=>"REGNW",
		"offline_registration.php"=>"OFREG",
		"top_save_matchalert.php"=>"MATCH",
		"naukri_js.php"=>"NAUKRI",
		"registration_ajax_validation.php"=>"REGAXVL",
		"register.php"=>"MOB_REG"
		);
		foreach ($pageArray as $key => $value) 
		{
			if($source_page==$key)
				return $value;
		}
		
		$page="OTHERS";
		return $page;
	}
}

?>
