<?php
/**
 * FtoOfferLib class
 *
 * @created Tue Dec 28 09:44:13 IST 2012
 * @author Ankit Garg <ankit.garg@jeevansathi.com>
 * @package jeevansathi
 * @subpackage fto
 */
/**
 * FtoOfferLib class
 *
 * <p>
 * This is a library containing static helper methods for {@link ftoActions} class
 * </p>
 */
class FtoOfferLib {
  /**@#+
   * @access public 
   */
  /**
   * getProfileDetails
   *
   * <p>
   * This function gets the profile details of other profile
   * </p>
   * 
   * @param $profileid integer
   * @throws jsException
   * @return array
   */
  public static function getProfileDetails($profileid) {

    if (true === is_numeric($profileid)) {

      $profilePicUrl = "";
      $thumbnailPicUrl = "";
      $showViewAlbumLink = null;
      $profileObj = new Profile("", $profileid);
      $profileObj->getDetail("", "", "*");
      $profileObjArray[0] = $profileObj;
      $photoObj = new PictureArray($profileObjArray);
      $photoObjectArray = $photoObj->getProfilePhoto();

      if ($photoObjectArray[$profileid]) {
        $profilePicUrl = $photoObjectArray[$profileid]->getProfilePicUrl();
        $thumbnailPicUrl = $photoObjectArray[$profileid]->getThumbailUrl();
        $showViewAlbumLink = 1;
      }
      else {
        $showViewAlbumLink = 0;
        if ($profileObj->getGENDER() === "M") {
          $profilePicUrl = PictureService::getRequestOrNoPhotoUrl("requestPhoto", "ProfilePicUrl", "M");
          $thumbnailPicUrl = "NA";
        }
        else {
          $profilePicUrl = PictureService::getRequestOrNoPhotoUrl("requestPhoto", "ProfilePicUrl", "F");
          $thumbnailPicUrl = "NA";
        }
      }

      $viewProfileUrl = sfConfig::get('app_site_url') . 
        "/profile/viewprofile.php?profilechecksum=" . 
        JsCommon::createChecksumForProfile($profileObj->getPROFILEID());

      return array(
          $profileObj, 
          array(
            "age"               => $profileObj->getAGE(),
            "showViewAlbumLink" => $showViewAlbumLink, 
            "username"          => $profileObj->getUSERNAME(),
            "profilePicUrl"     => $profilePicUrl,
            "thumbnailPicUrl"   => $thumbnailPicUrl,
            "height"            => $profileObj->getDecoratedHeight(),
            "religion"          => $profileObj->getDecoratedReligion(),
            "community"         => ltrim(FieldMap::getFieldLabel("community_small", $profileObj->getMTONGUE()), "-"),
            "caste"             => ltrim(FieldMap::getFieldLabel("caste_small", $profileObj->getCASTE()), "-"),
            "viewProfileUrl"    => $viewProfileUrl,
            "education"         => $profileObj->getDecoratedEducation(),
            "occupation"        => $profileObj->getDecoratedOccupation(),
            "incomeLevel"       => $profileObj->getDecoratedIncomeLevel(),
            "country"           => $profileObj->getDecoratedCountry()
            )
          );
    }
    else {
      throw new jsException('', 'Profile Id is not an integer.');
    }
  }

  /**
   * getSuggestedProfiles
   *
   * <p>
   * This function gets the suggested profiles for other profileid
   * </p>
   * 
   * @uses SearchCommonFunctions::getDppMatches() function to get suggested matches.
   * @param $profileid integer
   * @return array or integer, array when suggested matches are found, integer when not found.
   * @throws jsException
   */
  public static function getSuggestedProfiles($profileid) {

    if(true === is_numeric($profileid)) {

      $loginProfileChecksum = JsCommon::createChecksumForProfile($profileid);
      $response = SearchCommonFunctions::getDppMatches($profileid, 'fto_offer', SearchSortTypesEnums::popularSortFlag, 'Y', 'comma');
      $suggestedProfilesArray = null;
      $profilePicUrl = "";
      $thumbnailPicUrl = "";
      $profileids = $response["SEARCH_RESULTS"]; // currently 4 in number. If this number is changed then please update here.

      if ($profileids) {
        $profileArrayObj = new ProfileArray();
        $profileObjArray = $profileArrayObj->getDetail(array("PROFILEID" => $profileids), '', '', "*", "JPROFILE");

        $profileObjArray = self::getProfilesInOrder($profileObjArray, explode(",", $profileids));
        $photoObj = new PictureArray($profileObjArray);
        $photoObjArray = $photoObj->getProfilePhoto();
        
        if ($response["TOTAL_SEARCH_RESULTS"] >= 1) { // If any suggested match found
          $i = 0;
          foreach ($profileObjArray as $profileid => $profileObj) {
            if ($photoObjArray[$profileid]) {
              $profilePicUrl = $photoObjArray[$profileid]->getProfilePicUrl();
              $thumbnailPicUrl = $photoObjArray[$profileid]->getThumbailUrl();
            }
            else {
              if ($profileObj->getGENDER() === "M") {
                $profilePicUrl = PictureService::getRequestOrNoPhotoUrl("requestPhoto", "ProfilePicUrl", "M");
                $thumbnailPicUrl = PictureService::getRequestOrNoPhotoUrl("noPhoto", "ThumbailUrl", "M");
              }
              else {
                $profilePicUrl = PictureService::getRequestOrNoPhotoUrl("requestPhoto", "ProfilePicUrl", "F");
                $thumbnailPicUrl = PictureService::getRequestOrNoPhotoUrl("noPhoto", "ThumbailUrl", "F");
              }
            }
            $viewProfileUrl = sfConfig::get('app_site_url') . 
              "/profile/viewprofile.php?profilechecksum=" . 
              JsCommon::createChecksumForProfile($profileid);

            $suggestedProfilesArray[$i++] = array(
                "username" => $profileObj->getUSERNAME(), 
                "profilechecksum" => JsCommon::createChecksumForProfile($profileObj->getPROFILEID()), 
                "age" => $profileObj->getAGE(), 
                "profilePicUrl" => $profilePicUrl, 
                "thumbnailPicUrl" => $thumbnailPicUrl,
                "height" => $profileObj->getDecoratedHeight(), 
                "religion" => $profileObj->getDecoratedReligion(), 
                "community" => ltrim(FieldMap::getFieldLabel("community_small", $profileObj->getMTONGUE()), "-"), 
                "caste" => ltrim(FieldMap::getFieldLabel("caste_small", $profileObj->getCASTE()), "-"), 
                "viewProfileUrl" => $viewProfileUrl
                );
          }
          return $suggestedProfilesArray;
        }
        else { // if no suggested match is found.
          return -1;
        }
      }
      else { // if profileids is not an array or not found.
        return -1;
      }
    }
    else {
      throw new jsException('', 'Profile ID is not numeric');
    }
  }

  /**
   * getProfileDrafts
   *
   * <p>
   * This function gets the preset EOI messages for EOI done from offer pages.
   * </p>
   *
   * @param $profile Profile
   * @throws jsException
   * @return string
   */
  public static function getProfileDrafts(Profile $profile) {

    if ($profile instanceof Profile) {
      return PresetMessage::getEoiMes($profile);
    }
    else {
      throw new jsException('', 'Not an Instance of Profile');
    }
  }

  /**
   * getProfilesInOrder
   * 
   * <p>
   * This function aligns the output of getDppMatches and ProfileArray class
   * </p>
   *
   * @param $inputArray array
   * @param $cmpArray array
   * @return array
   */
  public static function getProfilesInOrder($inputArray, $cmpArray) {
    
    $output = null;

    foreach ($cmpArray as $key => $val) {
      $output[$val] = $inputArray[$val];  
    }
    return $output;
  }
  /**@#-*/
}
