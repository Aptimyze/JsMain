<?php
//Mail Builder test.
include(dirname(__FILE__).'/../../bootstrap/unit.php');
class TestClass {
  public static function getSuggestedProfileIdArray($forProfiles = "") {
    if (is_array($forProfiles)) {
      foreach ($forProfiles as $key => $profileid) {
        
        $result = SearchCommonFunctions::getDppMatches($profileid, 'fto_offer', SearchSortTypesEnums::popularSortFlag);
        $suggested_profiles[$profileid] = $result[SEARCH_RESULTS];
      }
    } else {
      echo "This has to be an array.";
      exit -1;
    }
    return $suggested_profiles;
  }
}

$t = new lime_test(1, new lime_output_color());

$email_sender = new EmailSender(9,1749);

$to_profiles = array(144111,3187961,148,269);

$actual = $email_sender->bulkSend(&$to_profiles, array(
      array("jeevansathi_contact_address", "jeevansathi_contact_address"),
      array("suggested_profiles", "suggested_profiles", call_user_func(array(TestClass, getSuggestedProfileIdArray), &$to_profiles), false)));
$t->is($actual, true, "Mail sent successfully.");
