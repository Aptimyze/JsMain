<?php

class GoogleRemarketing {

  private static function _getParamsAsArray($params) {

    $new_params = null;

    if (is_array($params)) {
      if (count($params) === 1) {
        if (!$params) {
          $new_params = null;
        }
      }
      else {
        $i = 0;
        foreach($params as $key => $value) {
          $new_params[$i++] = $value;
        }
      }
    }
    //String based input
    else { // Comma Separated values
      if (isset($params) && $params !== "") {
        $new_params = rtrim($params, ",");
        $new_params = explode(",", $new_params);
      }
      else {
        return null;
      }
    }
    return $new_params;
  }

  private static function _getInitialBitVector($count = 0) {

    $bit_vector = null;

    for ($i = 0; $i < $count; ++$i) {
      $bit_vector[$i] = 0;
    }

    return $bit_vector;
  }

  private static function _getIndexOfFirstSetBit($bit_vector = null) {
    $index = -1;

    for ($i = 0; $i < count($bit_vector); ++$i) {
      if ($bit_vector[$i] === 1) {
        $index = $i;
        break;
      }
    }

    return $index;
  }

  public static function getManglikTag($params = "") {

    // bit vector pattern will be 
    //  0th index <-- for Manglik
    //  0 <-- initial value

    $values = array(
        "Manglik"
        );

    $params = self::_getParamsAsArray($params);
    $manglik_arr = array("M");

    if (isset($params)) {

      $bit_vector = self::_getInitialBitVector(count($values));

      for ($i = 0; $i < count($params); ++$i) {
        if (@in_array($params[$i], $manglik_arr)) {
          $bit_vector[0] = 1;
        }
      }

      $index = self::_getIndexOfFirstSetBit($bit_vector);

      if ($index !== -1) {
        return $values[$index];
      }
      else {
        return "";
      }
    }
    else {
      return "";
    }
  }

  public static function getMstatusTag($params = "") {

    // 0th index <-- for Divorcee
    // 0 <-- initial value

    $values = array(
        "Divorcee"
        );

    $params = self::_getParamsAsArray($params);
    $mstatus_arr = array("M", "D", "A");


    if (isset($params)) {

      $bit_vector = self::_getInitialBitVector(count($values));

      for ($i = 0; $i < count($params); ++$i) {
        if (@in_array($params[$i], $mstatus_arr)) {
          $bit_vector[0] = 1;
        }
      }

      $index = self::_getIndexOfFirstSetBit($bit_vector);

      if ($index !== -1) {
        return $values[$index];
      }
      else {
        return "";
      }
    }
    else {
      return "";
    }
  }

  public static function getMtongueTag($params) {

    // bit pattern
    //    2          1        0   <-- indices
    // Marathi    Punjabi   Hindi
    //    0          0        0   <-- initial values

    $values = array(
        "Hindi",
        "Punjabi",
        "Marathi"
        );

    $params = self::_getParamsAsArray($params);

    // since all-north, all-hindi + a check on all-west have to be incorporated
    $all_north = array("7", "10", "13", "14", "15", "27", "28", "30", "33", "41");
    $all_hindi = array("7", "10", "13", "19", "28", "33");
    $all_west = array("8", "9", "11", "12", "19", "20", "30", "34");

    if (isset($params)) {

      $bit_vector = self::_getInitialBitVector(count($values));

      for ($i = 0; $i < count($params); ++$i) {
        if (@in_array($params[$i], FieldMap::getFieldLabel("allHindiMtongues", $params, 1)) !== false) {
          $bit_vector[0] = 1;
        }
        else if ($params[$i] === "27") {
          $bit_vector[1] = 1;
        }
        else if ($params[$i] === "20") {
          $bit_vector[2] = 1;
        }
      }

      if (array_intersect($all_north, $params) === $all_north) {
        $bit_vector[0] = 1;
      }
      else if (array_intersect($all_hindi, $params) === $all_hindi) {
        $bit_vector[0] = 1;
      }
      else if (array_intersect($all_west, $params) === $all_west) {
        $bit_vector[0] = 0; // 30 is common in both
        $bit_vector[2] = 0;
      }

      $index = self::_getIndexOfFirstSetBit($bit_vector);

      if ($index !== -1) {
        return $values[$index];
      }
      else {
        return "";
      }
    }
    else {
      return "";
    }
  }

  public static function getReligionTag($params) {

    // bit pattern
    //      4         3        2       1          0       <-- indices
    //    Jain    Buddhist    Sikh   Christian   Muslim
    //      0         0        0       0          0       <-- Initial Values

    $values = array(
        "Muslim",
        "Christian",
        "Sikh",
        "Buddhist",
        "Jain"
        );

    $params = self::_getParamsAsArray($params);

    if (isset($params)) {

      $bit_vector = self::_getInitialBitVector(count($values));

      for ($i = 0; $i < count($params); ++$i) {
        switch($params[$i]) {

          case "2":
            $bit_vector[0] = 1;
            break;

          case "3":
            $bit_vector[1] = 1;
            break;

          case "4":
            $bit_vector[2] = 1;
            break;

          case "7":
            $bit_vector[3] = 1;
            break;

          case "9":
            $bit_vector[4] = 1;
            break;

          default:
            break;
        }
      }

      $index = self::_getIndexOfFirstSetBit($bit_vector);

      if ($index !== -1) {
        return $values[$index];
      }
      else {
        return "";
      }
    }
    else {
      return "";
    }
  }

  public static function getGenderTag($params) {

    // bit pattern
    //      2       1       0   <-- Indices
    //  Profiles  Grooms  Brides
    //      0       0       0   <-- Initial Value

    $values = array(
        "Brides",
        "Grooms",
        "Profiles"
        );

    $params = self::_getParamsAsArray($params);

    if (isset($params)) {

      $bit_vector = self::_getInitialBitVector(count($values));

      switch($params[0]) {

        case "F":
          $bit_vector[0] = 1;
          break;

        case "M":
          $bit_vector[1] = 1;
          break;

        case "":
          $bit_vector[2] = 1;
          break;

        default:
          break;
      }

      $index = self::_getIndexOfFirstSetBit($bit_vector);

      if ($index !== -1) {
        return $values[$index];
      }
      else {
        return "";
      }
    }
    else {
      return $values[2];
    }
  }

  public static function getCasteTag($params) {

    // bit pattern
    //   9     8      7      6      5        4       3         2         1         0   <-- Indices
    // Arora  Jat  Maratha Khatri Rajput  Agarwal   Bania   Kshatriya   Kayastha  Brahmin
    //   0     0      0      0      0        0       0         0         0         0         0   <-- Initial Values

    $values = array(
        "Brahmin",
        "Kayastha",
        "Kshatriya",
        "Bania",
        "Agarwal",
        "Rajput",
        "Khatri",
        "Maratha",
        "Jat",
        "Arora"
        );

    $params = self::_getParamsAsArray($params);
    $params_count = count($params);

    if (isset($params)) {

      $bit_vector = self::_getInitialBitVector(count($values));

      $revampCasteFunctionsObj = new RevampCasteFunctions();

      $all_brahmin_arr = $revampCasteFunctionsObj->getAllCastes("25", 1); // caste group 25
      $all_kayastha_arr = $revampCasteFunctionsObj->getAllCastes("76", 1);// caste group 76
      $all_kshatriya_arr = $revampCasteFunctionsObj->getAllCastes("82", 1); // caste group 82
      $all_bania_arr = $revampCasteFunctionsObj->getAllCastes("20", 1); // caste group 20
      $all_agarwal_arr = $revampCasteFunctionsObj->getAllCastes("485", 1); // caste group 485
      // Trac #1853 - addition of new castes.
      $all_rajput_arr = $revampCasteFunctionsObj->getAllCastes("116", 1); // caste group 116
      $all_khatri_arr = $revampCasteFunctionsObj->getAllCastes("484", 1); //caste group 484
      $all_maratha_arr = $revampCasteFunctionsObj->getAllCastes("494", 1); // caste group 494
      $all_jat_arr = array("71"); // hindu jat

        for ($i = 0; $i < $params_count; ++$i) {

          if (@in_array($params[$i], $all_brahmin_arr) !== false) {
            $bit_vector[0] = 1;
          }
          else if (@in_array($params[$i], $all_kayastha_arr) !== false) {
            $bit_vector[1] = 1;
          }
          else if (@in_array($params[$i], $all_kshatriya_arr) !== false) {
            $bit_vector[2] = 1;
          }
          else if (@in_array($params[$i], $all_bania_arr) !== false) {
            $bit_vector[3] = 1;
          }
          else if (@in_array($params[$i], $all_agarwal_arr) !== false) {
            $bit_vector[4] = 1;
          }
          else if (@in_array($params[$i], $all_rajput_arr) !== false) {
            $bit_vector[5] = 1;
          }
          else if (@in_array($params[$i], $all_khatri_arr) !== false) {
            if ($params[$i] == 18) { // handled for hindu arora only search
              $bit_vector[9] = 1;
            } else {
              $bit_vector[6] = 1;
            }
          }
          else if (@in_array($params[$i], $all_maratha_arr) !== false) {
            $bit_vector[7] = 1;
          }
          else if (@in_array($params[$i], $all_jat_arr) !== false) {
            $bit_vector[8] = 1;
          }
        }

      $index = self::_getIndexOfFirstSetBit($bit_vector);

      if ($index !== -1) {
        return $values[$index];
      }
      else {
        return "";
      }
    }
    else {
      return "";
    }
  
}

public static function getCountryTag($params_country) {

  $params = self::_getParamsAsArray($params_country);

  if (isset($params)) {
    if (@in_array("51", $params) !== false && count($params) === 1) {
      return array(-1, "");
    }
    else {
      return array(0, "NRI");
    }
  }
  else {
    return array(-1, "");
  }

}

public static function getCityTag($params_city) {

  // bit pattern
  //      4       3       2       1       0   <-- Indices
  //  Kolkata   Pune  Bangalore Mumbai   NCR
  //      0       0       0       0       0   <-- Initial Values

  $values = array(
      "NCR",
      "Mumbai",
      "Bangalore",
      "Pune",
      "Kolkata"
      );

  $params = self::_getParamsAsArray($params_city);

  if (isset($params)) {

    $bit_vector = self::_getInitialBitVector(count($values));

    $ncr_arr = FieldMap::getFieldLabel("delhiNcrCities", "", 1);

    for ($i = 0; $i < count($params); ++$i) {
      if (@in_array($params[$i], $ncr_arr) !== false) {
        $bit_vector[0] = 1;
      }
      else if ($params[$i] === "MH04") {
        $bit_vector[1] = 1;
      }
      else if ($params[$i] === "KA02") {
        $bit_vector[2] = 1;
      }
      else if ($params[$i] === "MH08") {
        $bit_vector[3] = 1;
      }
      else if ($params[$i] === "WB05") {
        $bit_vector[4] = 1;
      }
    }

    $index = self::_getIndexOfFirstSetBit($bit_vector);

    if ($index !== -1) {
      return array($index, $values[$index]);
    }
    else {
      return array(-1, "");
    }
  }
  else {
    return array(-1, "");
  }
}

public static function getResidenceTag($params_country, $params_city) {
  // get index of country
  // get index of city
  // whichever index is lesser, pick that value to display as residence
  // in case of tie, use country's index value first
  list($index1, $value1) = self::getCountryTag($params_country);
  list($index2, $value2) = self::getCityTag($params_city);

  //echo "Index1: $index1, Value1: $value1, Index2: $index2, Value2: $value2\n";
  // Tie
  if ($index1 !== -1 && $index2 !== -1) {
    if ($index1 === $index2) {
      return $value1;
    }
    else if ($index1 < $index2) {
      return $value1;
    }
    else {
      return $value2;
    }
  }
  else if ($index1 === -1 && $index2 !== -1) {
    return $value2;
  }
  else if ($index1 !== -1 && $index2 === -1) {
    return $value1;
  }
  else if ($index1 === -1 && $index2 === -1) {
    return "";
  }
}

public static function getOccupationTag($params_occupation) {

  // bit pattern
  //      2        1       0      <-- Indices
  //   Software   IAS    Doctor 
  //      0        0       0      <-- Initial Values

  $values = array(
      "Doctor",
      "IAS",
      "Software"
      );

  $params = self::_getParamsAsArray($params_occupation);

  if (isset($params)) {

    $bit_vector = self::_getInitialBitVector(count($values));

    for ($i = 0; $i < count($params); ++$i) {
      switch($params[$i]) {

        case "24":
        case "57":
          $bit_vector[0] = 1;
          break;

        case "33":
          $bit_vector[1] = 1;
          break;

        case "20":
          $bit_vector[2] = 1;
          break;
      }
    }


    $index = self::_getIndexOfFirstSetBit($bit_vector);

    if ($index !== -1) {
      return array($index, $values[$index]);
    }
    else {
      return array(-1, "");
    }
  }
  else {
    return array(-1, "");
  }
}

public static function getEducationTag($params_education) {

  // bit pattern
  //     1       0    <-- Indices
  //    CA     Doctor 
  //     0       0    <-- Initial Values

  $values = array(
      "Doctor",
      "CA"
      );

  $params = self::_getParamsAsArray($params_education);

  if (isset($params)) {

    $bit_vector = self::_getInitialBitVector(count($values));

    $doctor_arr = explode(",", "17,28,30,19");
    $ca_arr = explode("," , "7,8,10");

    if (isset($params)) {
      for ($i = 0; $i < count($params); ++$i) {

        if (@in_array($params[$i], $doctor_arr) !== false) {
          $bit_vector[0] = 1;
        }
        else if (@in_array($params[$i], $ca_arr) !== false) {
          $bit_vector[1] = 1;
        }
      }
    }

    $index = self::_getIndexOfFirstSetBit($bit_vector);

    if ($index !== -1) {
      return array($index, $values[$index]);
    }
    else {
      return array(-1, "");
    }
  }
  else {
    return array(-1, "");
  }
}

public static function getEducationOccupationTag($params_occupation, $params_education) {
  // get index of occupation
  // get index of education
  // whichever index is lesser, pick that value to display as education
  // in case of tie, use occupation's index value first

  list($index1, $value1) = self::getOccupationTag($params_occupation);
  list($index2, $value2) = self::getEducationTag($params_education);

  //echo "Index1: $index1, Value1: $value1, Index2: $index2, Value2: $value2\n";
  if ($index1 !== -1 && $index2 !== -1) {
    if ($index1 === $index2) {
      return $value2;
    }
    else if ($index1 < $index2) {
      return $value1;
    }
    else {
      return $value2;
    }
  }
  else if ($index1 === -1 && $index2 !== -1) {
    return $value2;
  }
  else if ($index1 !== -1 && $index2 === -1) {
    return $value1;
  }
  else if ($index1 === -1 && $index2 === -1) {
    return "";
  }
}

}
