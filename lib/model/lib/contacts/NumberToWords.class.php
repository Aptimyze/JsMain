<?php
/**
 * NumberToWords 
 *
 * @author Ankit Garg <ankit.garg@jeevansathi.com>
 * @created Mon Feb 11 10:59:17 IST 2013
 * @package jeevansathi
 * @subpackage contacts
 */
/**
 * Number To Words class
 *
 * <p>
 * This class coverts any given number into words. Eg. number 23 will be converted to twenty-three. Below is the description on how to call.
 * <code>
 * $result = NumberToWords::convertNumber($num);
 * </code>
 * </p>
 * @author Ankit Garg <ankit.garg@jeevansathi.com>
 */
class NumberToWords {

  /**
   * Convert Number
   *
   * <p>
   * This function converts the given number into words.
   * </p>
   * @param $num integer
   * @access public
   * @return string
   */
  public static function convertNumber($num) {
    list($num, $dec) = explode(".", $num);

    $output = "";

    if($num{0} == "-")
    {
      $output = "negative ";
      $num = ltrim($num, "-");
    }
    else if($num{0} == "+")
    {
      $output = "positive ";
      $num = ltrim($num, "+");
    }

    if($num{0} == "0")
    {
      $output .= "zero";
    }
    else
    {
      $num = str_pad($num, 36, "0", STR_PAD_LEFT);
      $group = rtrim(chunk_split($num, 3, " "), " ");
      $groups = explode(" ", $group);

      $groups2 = array();
      foreach($groups as $g) $groups2[] = self::convertThreeDigit($g{0}, $g{1}, $g{2});

      for($z = 0; $z < count($groups2); $z++)
      {
        if($groups2[$z] != "")
        {
          $output .= $groups2[$z].self::convertGroup(11 - $z).($z < 11 && !array_search('', array_slice($groups2, $z + 1, -1))
              && $groups2[11] != '' && $groups[11]{0} == '0' ? " and " : ", ");
        }
      }

      $output = rtrim($output, ", ");
    }

    if($dec > 0)
    {
      $output .= " point";
      for($i = 0; $i < strlen($dec); $i++) $output .= " ".self::convertDigit($dec{$i});
    }

    return $output;
  } // end of convertNumber

  /**#@+
   * @access private
   */
  /**
   * Convert Group
   *
   * <p>
   * This function gives the group to which the number lie based on the index of MSB.
   * </p>
   * @param $index integer
   * @return string
   */
  private static function convertGroup($index)
  {
    switch($index)
    {
      case 11: return " decillion";
      case 10: return " nonillion";
      case 9: return " octillion";
      case 8: return " septillion";
      case 7: return " sextillion";
      case 6: return " quintrillion";
      case 5: return " quadrillion";
      case 4: return " trillion";
      case 3: return " billion";
      case 2: return " million";
      case 1: return " thousand";
      case 0: return "";
    }
  } // end of convertGroup

  /**
   * Convert Three Digits
   *
   * <p>
   * This function converts three digits into corresponding word notation.
   * </p>
   * @param $dig1 integer
   * @param $dig2 integer
   * @param $dig3 integer
   * @return string
   */
  private static function convertThreeDigit($dig1, $dig2, $dig3)
  {
    $output = "";

    if($dig1 == "0" && $dig2 == "0" && $dig3 == "0") return "";

    if($dig1 != "0")
    {
      $output .= self::convertDigit($dig1)." hundred";
      if($dig2 != "0" || $dig3 != "0") $output .= " and ";
    }

    if($dig2 != "0") $output .= self::convertTwoDigit($dig2, $dig3);
    else if($dig3 != "0") $output .= self::convertDigit($dig3);

    return $output;
  } // end of convertThreeDigit

  /**
   * Convert Two Digits
   *
   * <p>
   * This function converts the two digits according to the groups and their place of occurence.
   * </p>
   * @param $dig1 integer
   * @param $dig2 integer
   * @return string
   */
  private static function convertTwoDigit($dig1, $dig2)
  {
    if($dig2 == "0")
    {
      switch($dig1)
      {
        case "1": return "ten";
        case "2": return "twenty";
        case "3": return "thirty";
        case "4": return "forty";
        case "5": return "fifty";
        case "6": return "sixty";
        case "7": return "seventy";
        case "8": return "eighty";
        case "9": return "ninety";
      }
    }
    else if($dig1 == "1")
    {
      switch($dig2)
      {
        case "1": return "eleven";
        case "2": return "twelve";
        case "3": return "thirteen";
        case "4": return "fourteen";
        case "5": return "fifteen";
        case "6": return "sixteen";
        case "7": return "seventeen";
        case "8": return "eighteen";
        case "9": return "nineteen";
      }
    }
    else
    {
      $temp = self::convertDigit($dig2);
      switch($dig1)
      {
        case "2": return "twenty-$temp";
        case "3": return "thirty-$temp";
        case "4": return "forty-$temp";
        case "5": return "fifty-$temp";
        case "6": return "sixty-$temp";
        case "7": return "seventy-$temp";
        case "8": return "eighty-$temp";
        case "9": return "ninety-$temp";
      }
    }
  } // end of convertTwoDigit

  /**
   * Convert Digit
   *
   * <p>
   * This function converts single digit into string.
   * </p>
   * @param $digit integer
   * @return string
   */
  private static function convertDigit($digit)
  {
    switch($digit)
    {
      case "0": return "zero";
      case "1": return "one";
      case "2": return "two";
      case "3": return "three";
      case "4": return "four";
      case "5": return "five";
      case "6": return "six";
      case "7": return "seven";
      case "8": return "eight";
      case "9": return "nine";
    }
  } // end of convertDigit
  /**#@-*/
}

