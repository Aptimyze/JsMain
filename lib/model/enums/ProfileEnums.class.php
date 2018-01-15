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
        public static $saveBlankIfZeroForFields = array("DIET","DRINK","SMOKE","MOTHER_OCC","FAMILY_BACK","FAMILY_STATUS","FAMILY_TYPE","FAMILY_VALUES","COMPLEXION","BTYPE","FAMILY_INCOME","NATIVE_COUNTRY","STATE_INDIA","NATIVE_STATE");

}
?>
