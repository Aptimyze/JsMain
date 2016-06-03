<?php

/**
 * @class Google Remarketing for Search Page.
 * @author Ankit Garg
 * @created Wed Oct 17 10:11:58 IST 2012
 */
class GoogleRemarketing_unrevamped {

  public function __construct() {}

  public function getManglikTag($Manglik) {
    $tag = 0;

    if (@in_array('M', $Manglik)) {
      $tag = "Manglik";
    }
    else {
      $tag = "";
    }
    return $tag;
  }

  public function getMStatusTag($MStatus) {
    $tag = "";

    if (count($MStatus) < 1) {
      $tag = "";
    }

    if (count($MStatus) === 1) {
      if (!$MStatus) {
        $MStatus = null;
      }
    }

    if (
        isset($MStatus) &&
        @in_array('M', $MStatus)
       ) 
    {
      $tag = "Divorcee";
    }
    else {
      $tag = "";
    }
    return $tag;
  }

  public function getMtongueTag($Mtongue) {
    $tag = "";
    global $MTONGUE_DROP;

    if (count($Mtongue) < 1) {
      $tag = "";
    }
    else {

      $all_north = array("7", "10", "13", "14", "15", "27", "28", "30", "33", "41");
      $all_hindi = array("7", "10", "13", "19", "28", "33");
      $all_west = array("8", "9", "11", "12", "19", "20", "30", "34");

      //Booleans to identify hindi or punjabi
      $hindi = false;
      $punjabi = false;

      // Label Array
      $mtongue_label_array = 
        array(
            "Hindi" => array("7", "10", "19", "33", "41"),
            "Punjabi" => array("27"),
            "Marathi" => array("20")
            );

      //Perform Array _intersect first on all_north and all_hindi
      if (count($Mtongue) === 1) {
        if (!$Mtongue) {
          $Mtongue = null;
        }
      }

      if (isset($Mtongue)) {
        if (array_intersect($all_north, $Mtongue) === $all_north) { //Set Hindi
          $tag = "Hindi";
        }
        else if (array_intersect($all_hindi, $Mtongue) === $all_hindi) 
        {
          $tag = "Hindi";
        }
        else if (array_intersect($all_west, $Mtongue) === $all_west) 
        {
          $tag = "";
        }
        else {
          foreach ($Mtongue as $mtongue) {
            foreach ($mtongue_label_array as $key => $value) {
              if (
                  @in_array($mtongue, $value) !== false && 
                  $key === "Hindi"
                 ) 
              {
                $hindi = true;
                $tag = $key;
                break;
              }
              else if (
                  $hindi === false && 
                  @in_array($mtongue, $value) !== false && 
                  $key === "Punjabi"
                  ) 
              {
                $punjabi = true;
                $tag = $key;
              }
              else if (
                  $hindi === false && 
                  $punjabi === false && 
                  @in_array($mtongue, $value) !== false && 
                  $key === "Marathi"
                  )
              {
                $tag = $key;
              }
            }
            if ($hindi === true) {
              break;
            }
          }
          /**/
        }
      }
    }
    return $tag;
  }

  public function getReligionTag($Religion) {
    $tag = ""; 

    global $RELIGIONS;

    // Religion is not set
    if (count($Religion) < 1) {
      $tag = "";
    }
    else {

      $muslim = false;
      $christian = false;
      $sikh = false;
      $jain = false;
      $buddhist = false;

      $religion_label_array = 
        array(
            "Muslim" => array("2"),
            "Christian" => array("3"),
            "Sikh" => array("4"),
            "Jain" => array("9"),
            "Buddhist" => array("7")
            );

      // Treating single empty array object as null
      if (count($Religion) === 1) {
        if (!$Religion) {
          $Religion = null;
        }
      }

      if (isset($Religion)) {
        // Religion is set

        foreach ($religion_label_array as $key => $value) {
          /**/
          if (array_intersect($value, $Religion) === $value) {
            $tag = $key;
            break;
          }/*/
             if (
             @in_array($religion, $value) !== false &&
             $key === "Muslim"
             ) 
             {
             $muslim = true;
             $tag = $key;
             break;
             }
             else if (
             @in_array($religion, $value) !== false &&
             $key === "Christian"
             )
             {
             $christian = true;
             $tag = $key;
             }
             else if (
             $christian === false &&
             @in_array($religion, $value) !== false &&
             $key === "Sikh"
             ) 
             {
             $sikh = true;
             $tag = $key;
             }
             else if (
             $christian === false &&
             $sikh === false &&
             @in_array($religion, $value) !== false &&
             $key === "Jain"
             )
             {
             $jain = true;
             $tag = $key;
             }
             else if (
             $christian === false &&
             $sikh === false &&
             $jain === false &&
             @in_array($religion, $value) !== false &&
             $key === "Buddhist"
             )
             {
             $buddhist = true;
             $tag = $key;
             }
             }
             if (
             $muslim === true
             )
             {
             break;
             }
        /**/
      }
    }
  }
  return $tag;
}

public function getGenderTag($Gender) {
  $tag = "";

  if ($Gender === 'F') {
    $tag = "Brides";
  }
  else {
    $tag = "Grooms";
  }
  return $tag;
}

public function getEducationOccupationTag($Edu_Level_New, $Occupation) {
  $tag = "";

  if (
      count($Edu_Level_New) < 1 &&
      count($Occupation) < 1
     )
  {
    $tag = "";
  }
  else {
    global $EDUCATION_LEVEL_NEW_DROP;
    global $OCCUPATION_DROP;

    $occupation_label_array =
      array(
          "Doctor" => array("24"),
          "IAS" => array("33"),
          "Software" => array("20")
          );

    $education_label_array =
      array(
          "Doctor" => array("17", "28", "30", "19"),
          "CA" => array("7", "8", "10")
          );
    /* Performing clean up */
    if (count($Occupation) === 1) {
      if (!$Occupation) {
        $Occupation = null;
      }
    }

    if (count($Edu_Level_New) === 1) {
      if (!$Edu_Level_New) {
        $Edu_Level_New = null;
      }
    }
    /* Clean up done */

    if (isset($Occupation)) {
      if (@in_array("57", $Occupation) !== false) { //Added for Doctor as occupation
        $tag = "Doctor";
      }
      else {
        foreach ($occupation_label_array as $occ_key => $occ_val) {
          if (array_intersect($occ_val, $Occupation) === $occ_val) {
            $tag = $occ_key;
          }
        }
      }
    }
    if (
        isset($Edu_Level_New) &&
        $tag !== "Doctor"
       )
    {
      foreach ($Edu_Level_New as $education) {
        if ($tag !== "Doctor") { // No point to search further
          foreach ($education_label_array as $edu_key => $edu_val) {
            if (@in_array($education, $edu_val) !== false) {
              $tag = $edu_key;
              break;
            }
          }
        }
      }
    }
  }
  return $tag;
}

public function getResidenceTag($City_Res, $Country_Res) {
  $tag = "";

  if (
      count($City_Res) < 1 && 
      count($Country_Res) < 1
     ) 
  {
    $tag = "";
  }
  else {

    global $COUNTRY_DROP;

    $ncr_id_array = array(
        "DE00", 
        "UP25",
        "HA03",
        "HA02",
        "UP12",
        "UP47",
        "UP48"
        );

    $india_city_id_array = 
      array(
          "Mumbai" => array("MH04"),
          "Bangalore" => array("KA02"),
          "Pune" => array("MH08"),
          "Kolkata" => array("WB05")
          );
    /*
    $ncr = false;
    $mumbai = false;
    $bangalore = false;
    $pune = false;
    $kolkata = false;
    */

    /* Performing Clean up */
    if (count($Country_Res) === 1) {
      if (!$Country_Res) {
        $Country_Res = null;
      }
    }

    if (count($City_Res) === 1) {
      if (!$City_Res) {
        $City_Res = null;
      }
    }
    /* Clean up done */

    if (isset($Country_Res)) {

      foreach ($Country_Res as $country) {

        if (
            strcasecmp($COUNTRY_DROP[$country], "India") !== 0
           ) 
        {
          $tag = "NRI";
          break;
        }
      }
    }
    if ($tag !== "NRI") 
    {
      if (isset($City_Res)) {
        foreach ($City_Res as $city) {
          if (
              @in_array($city, $ncr_id_array) !== false
             ) 
          {
            $tag = "NCR";
            break;
          }
          else {
            /**/
            foreach ($india_city_id_array as $key => $value) {
              if (
                  array_intersect($value, $City_Res) === $value
                 ) 
              {
                $tag = $key;
                break;
              }
              /*/
                if (
                isset($india_city_id_array[$city]) &&
                $india_city_id_array[$city] === "Mumbai"
                ) 
                {
                $mumbai = true;
                $tag = $india_city_id_array[$city];
                break;
                }
                else if (
                isset($india_city_id_array[$city]) &&
                $india_city_id_array[$city] === "Bangalore"
                )
                {
                $bangalore = true;
                $tag = $india_city_id_array[$city];
                }
                else if (
                $bangalore === false &&
                isset($india_city_id_array[$city]) &&
                $india_city_id_array[$city] === "Pune"
                )
                {
                $pune = true;
                $tag = $india_city_id_array[$city];
                }
                else if (
                $bangalore === false &&
                $pune === false &&
                isset($india_city_id_array[$city]) &&
                $india_city_id_array[$city] === "Kolkata"
                )
                {
                $kolkata = true;
                $tag = $india_city_id_array[$city];
                }
              /**/
            }
          }
        }
      }
    }
  }

  return $tag;
}

public function getCasteTag($Caste) {
  $tag = "";

  if (count($Caste) < 1) {
    $tag = "";
  }
  else {
    global $CASTE_GROUP_ARRAY;
    $flag = false;
    $caste_group_id_array = 
      array( // The values are arranged in the order of desired preference as per PRD
          "25" => "Brahmin",
          "76" => "Kayastha",
          "82" => "Kshatriya",
          "20" => "Bania",
          "485" => "Agarwal"
          );

    $caste_label_array = 
      array(
          "Brahmin" => $CASTE_GROUP_ARRAY["25"],
          "Kayastha" => $CASTE_GROUP_ARRAY["76"],
          "Kshatriya" => $CASTE_GROUP_ARRAY["82"],
          "Bania" => $CASTE_GROUP_ARRAY["20"],
          "Agarwal" => $CASTE_GROUP_ARRAY["485"]
          );

    $brahmin = false;
    $kayastha = false;
    $kshatriya = false;
    $bania = false;
    $agarwal = false;

    if (count($Caste) === 1) {
      if (!$Caste) {
        $Caste = null;
      }
    }

    if (isset($Caste)) {
      foreach ($Caste as $caste) {

        if (
            isset($caste_group_id_array[$caste])
           )
        { // If this is set, it is most likely that group is searched 
          $tag = $caste_group_id_array[$caste];
          break;
        }
        else {
          foreach ($caste_label_array as $key => $value) {
            // Correct logic for ordered update of caste values
            $caste_values = explode(",", $value);
            if (
                @in_array($caste, $caste_values) !== false &&
                $key === "Brahmin"
               ) 
            {
              $brahmin = true;
              $tag = $key;
              break;
            }
            else if (
                @in_array($caste, $caste_values) !== false &&
                $key === "Kayastha"
                )
            {
              $kayastha = true;
              $tag = $key;
            }
            else if (
                $kayastha === false &&
                @in_array($caste, $caste_values) !== false &&
                $key === "Kshatriya"
                )
            {
              $kshatriya = true;
              $tag = $key;
            }
            else if (
                $kayastha === false &&
                $kshatriya === false &&
                @in_array($caste, $caste_values) !== false &&
                $key === "Bania"
                )
            {
              $bania = true;
              $tag = $key;
            }
            else if (
                $kayastha === false &&
                $kshatriya === false &&
                $bania === false &&
                @in_array($caste, $caste_values) !== false &&
                $key === "Agarwal"
                )
            {
              $agarwal = true;
              $tag = $key;
            }
          }
          if ($brahmin === true) {
            break;
          }
        }
      }
    }
  }
  return $tag;
}
}
