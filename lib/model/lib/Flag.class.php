<?php
/**
 * Flag class handles the screening value of open
 * text field of site. Checks, update flag values
 * of corresponding open field
 *
 * @package    lib
 * @subpackage model
 * @author     Nikhil dhiman
 * @version    SVN: $Id: actions.class.php 23810 2011-07-14 03:07:44 Nikhil dhiman $
 */
class Flag
{

	/**
	 * Returns after setting corresponding flag value
	 * @return $newvalue int
	 */
	public static function setFlag($FLAGID,$value,$flagSet='')
	{
		if(!$value)
			$value=0;
		$bitPosition=self::checkFlagId($FLAGID,$flagSet);
		$newvalue=self::isBitSet($bitPosition,$value)?$value:bcadd($value,bcpow(2,$bitPosition));
		return $newvalue;
	}
	/**
	 * Returns value formed after removing that
	 * flag value from it
	 * @return $newvalue int
	 */
	public  static function removeFlag($FLAGID,$value,$flagSet='')
	{
		if(!$value)
			$value=0;
		$bitPosition=self::checkFlagId($FLAGID,$flagSet);
		$newvalue=self::isBitSet($bitPosition,$value)?bcsub($value,bcpow(2,$bitPosition)):$value;
                return $newvalue;
	}
	/**
	 * Returns highest bit value of screening text field flag
	 * @return int
	 */
	public static function setAllFlags()
	{
		return FieldMap::getFieldLabel('flagval',"sum");
	}
	/**
	 * Returns highest bit value of screening photo flag
	 * @return int
	 */
	function setAllPhotoFlags()
	{

			return FieldMap::getFieldLabel("photoval","sum");
	}
	/**
	 * Returns if value for corresponding flag is 1 or 0
	 * @param: $FLAGID String textfield name
	 * @param: $value int screening bit value
	 * @return true[1]/false[0]
	 */
	static function isFlagSet($FLAGID,$value,$flagSet = "")
	{
		if(!$value)
			$value=0;
		$bitPosition=self::checkFlagId($FLAGID,$flagSet);
		if(self::isBitSet($bitPosition,$value))
			return true;
		else
			return false;
	}
	/**
	 * @param $FLAGID String
	 * @return $newflag int
	 * @throw If $FLAGID not found
	 */
	private static function checkFlagId($FLAGID,$flagSet="")
	{
		$photo_val=FieldMap::getFieldLabel("photoval",strtolower($FLAGID));

		if($flagSet == "duplicationFieldsVal")
		{
			$flag_val=FieldMap::getFieldLabel("duplicationFieldsVal",strtolower($FLAGID));
		}
		else
			$flag_val=FieldMap::getFieldLabel("flagval",strtolower($FLAGID));

		if($flag_val==='' && $photo_val==='')
		{
			throw new jsException('',"flag value doesn't exist $FLAGID, $value");
		}
		$newflag=$flag_val;
		if(!$newflag)
			$newflag=$photo_val;
		return $newflag;
	}
	static function isBitSet($number, $value) 
	{
		$tmpValue = bcmod($value,bcpow(2,$number+1));
		return bccomp(bcsub($tmpValue, bcpow(2,$number)), 0)>= 0;
	}
	static function areAllBitsSet($value)
	{
	  return FieldMap::getFieldLabel("flagval","sum")=="$value";
	}

}
