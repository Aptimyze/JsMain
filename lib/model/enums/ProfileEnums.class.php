<?php
/**
 * ProfileEnums.class.php
 * 
 */

/**
 * Class ProfileEnums represents the constant used in Profile Detail
 *
 * @package    jeevansathi
 * @subpackage profile
 * @author     Kunal Verma
 * @version    02nd Sept 2015
 */
class ProfileEnums
{	
  //Error Type used in ProfileCommon::checkViewed
  const IGNORED_BY_ME     = 11;
  const IGNORED_BY_OTHER  = 12;
  const PROFILE_ACTIVATED  = 'Y';
  const PROFILE_NOT_ACTIVATED  = 'N';
  const PROFILE_DELETED  = 'D';
  const PROFILE_HIDDEN  = 'H';
  const PROFILE_UNDERSCREENING  = 'U';
  const HAVE_CHILD_KEY = "dpp_have_child";
  const CASTE_KEY = "dpp_caste";
  const MUSLIM_NAME = "Muslim";
        public static $saveBlankIfZeroForFields = array("DIET","DRINK","SMOKE","MOTHER_OCC","FAMILY_BACK","FAMILY_STATUS","FAMILY_TYPE","FAMILY_VALUES","COMPLEXION","BTYPE","FAMILY_INCOME","NATIVE_COUNTRY","STATE_INDIA","NATIVE_STATE");
        public static $dppTickFields = array("dpp_age"=>"AGE","dpp_height"=>"HEIGHT","dpp_marital_status"=>"MSTATUS","dpp_have_children"=>"HAVECHILD","dpp_country"=>"COUNTRYRES","dpp_city"=>"CITYRES","dpp_religion"=>"RELIGION","dpp_caste"=>"CASTE","dpp_mtongue"=>"MTONGUE","dpp_manglik"=>"MANGLIK","dpp_edu_level"=>"ELEVEL_NEW","dpp_occupation"=>"OCCUPATION","dpp_earning"=>"INCOME","dpp_diet"=>"DIET","dpp_smoke"=>"SMOKE","dpp_drink"=>"DRINK","dpp_complexion"=>"COMP","dpp_btype"=>"BTYPE","dpp_handi"=>"HANDI");
        public static $sendInstantMessagesForFields = array("CASTE"=>"Caste","PHONE_MOB"=>"Phone Number","EMAIL"=>"Email","CITY_RES"=>"City","MTONGUE"=>"Mother Tongue","INCOME"=>"Income","EDU_LEVEL_NEW"=>"Highest Education","OCCUPATION"=>"Occupation","MANGLIK"=>"Manglik");

        public static $matchAlertMailerStypeArr = array("BN1","BN2","BN3","BN4","BN5");
        public static $removeFromDppTickArr = array("dpp_state","dpp_appearance","dpp_lifestyle","dpp_have_children","dpp_nature_handi","dpp_special_case");

}
?>
