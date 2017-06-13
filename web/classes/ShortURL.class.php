<?php
/***
 * Class to 
 * - generate a ShortURL , when the ActualURL is given. ShortURL generated
 *   will be saved in the newjs DB ,inside the shortURL table.
 * - return the ActualURL, when the ShortURL is provided. 
 *
 * @author Gaurav Singhal
 *
 */
class ShortURL {
	
	private $baseArray = array('b', 'a', 'J', 't', 'Z', 'N', 'v', 'S', 'L', 'g', 'z', '8', '4', 'W', 'O', 'y', 'h', '3',
								'6', 'd', 'e', 'n', 'P', 'B', 'G', 'q', 'k', 'E', '0', 'R', 'p', 'w', 'f', 'T', 'M', '2',
								'm', 'o', 'j', 'H', 'A', 'U', 'Y', '7', 'D', 'c', 'I', 'Q', '9', 'C', 'i', 's', 'l', 'x', 
								'1', 'r', 'u', 'K', 'F', 'V', 'X', '5');

	private $CONVERSION_BASE = 62;
	private $MULTIPLICATION_FACTOR = 5;
	private $SUBTRACTION_FACTOR = 30;
	private $MODULUS_FACTOR = 14776335;
	private $D1_bit = 0;
	private $ID_third = 1;
	private $Month_bit = 2;
	private $ID_fourth = 3;
	private $M1_bit = 4;
	private $ID_first = 5;
	private $Date_bit = 6;
	private $ID_second = 7;
	private $AUTO_INCREMENT = 242235;
	private $PREFIX_URL = "http://js1.in/";
	
	/**
	 * Constructor to initiate a connection. By-default it
	 * connects to the newjs database
	 */

	public function __construct()
	{
		include_once $_SERVER["DOCUMENT_ROOT"]."/classes/Mysql.class.php";
		include_once(JsConstants::$docRoot."/classes/LoggingWrapper.class.php");
		$mysql= new Mysql;
		$this->db=$mysql->connect();
	}
	
	/**
	 * Method to generate the Long URL
	 * @param $url: shortURL which is used to generate the LongURL
	 * @return $LongURL String 
	 */
	public function getLongURL($url)
	{
		$isValidate = $this->validateURL($url);
		$LongURL="";
		if (!$isValidate)
		{
			//logError ("InValid URL: ","","continue");
			return $LongURL;
		}	

		$receivedMonth = (array_search($url[$this->Month_bit], $this->baseArray) / $this->MULTIPLICATION_FACTOR);
		$receivedDate = (array_search($url[$this->Date_bit], $this->baseArray) - $this->SUBTRACTION_FACTOR);

		$id = ((array_search($url[$this->ID_first], $this->baseArray) * pow($this->CONVERSION_BASE, 3)) + (array_search($url[$this->ID_second],	$this->baseArray) * pow ($this->CONVERSION_BASE, 2)) + (array_search($url[$this->ID_third], $this->baseArray) * $this->CONVERSION_BASE) + (array_search($url[$this->ID_fourth], $this->baseArray)));

		$query = sprintf("Select url from newjs.shortURL where ActualID = '%d' and MONTH(entryDate) = '%d' and DAYOFMONTH(entryDate) = '%d'" ,
					$id, $receivedMonth, $receivedDate);
		
		$mysql = new Mysql;
		$query_res = $mysql->executeQuery($query, $this->db) or logError ("Error to fetch the Actual URL ","","continue");

		if($query_result = $mysql->fetchAssoc($query_res))
		{
			$LongURL = $query_result['url'];
		}

		return $LongURL;
	}

	/**
	 * Method to generate the ShortURL 
	 * @param: InputURL received from the client
	 */

	public function setShortURL ($url)
	{
	
		$query = sprintf("insert into newjs.shortURL (url, entryDate) VALUES ('%s', CURDATE())", mysql_real_escape_string($url));
		$mysql = new Mysql;
		$mysql->executeQuery($query, $this->db) or logError ("Error in inserting url and fetch the counter value","","continue");
		$id = mysql_insert_id();
		$actualID = $id % $this->MODULUS_FACTOR;
		if ($actualID < 242235) $actualID += 242235;

		$query = sprintf("UPDATE newjs.shortURL SET ActualID = '%d' where id = '%d'", $actualID,$id);
		$mysql->executeQuery($query, $this->db) or logError ("Error in updating shortURL table ","","continue"); 
		$id = $this->generateID($actualID);

		return $this->constructURL($id);
	}

	/**
	 * Method to validate the ShortURL 
	 * @param: Incoming ShortURL
	 * @return: true/false -> Incoming ShortURL is valid / Tampered URL
	 */

	private function validateURL ($url)
	{
		$M1 = $this->baseArray[(array_search($url[$this->ID_first], $this->baseArray) * array_search($url[$this->ID_fourth], $this->baseArray))  % $this->CONVERSION_BASE];

		$actual_M1 = $url[$this->M1_bit];

		$D1 = $this->baseArray[(array_search($url[$this->ID_second], $this->baseArray) * array_search($url[$this->ID_third], $this->baseArray)) % $this->CONVERSION_BASE];

		$actual_D1 = $url[$this->D1_bit];

		$receivedMonth = (array_search($url[$this->Month_bit], $this->baseArray) / $this->MULTIPLICATION_FACTOR);
		$receivedDate = (array_search($url[$this->Date_bit], $this->baseArray) - $this->SUBTRACTION_FACTOR);
		$month_1  = mktime(0, 0, 0, date("m")-1 , date("d"), date("Y"));                    // Previous month's date

		$today = getdate();
		$Month = $today['mon'];
		$Date = $today['mday'];

		$query_limit_month = date("m", $month_1);
		$query_limit_date = date("d", $month_1);
		
		$validURL = 0;
		if ($receivedMonth == $query_limit_month && $receivedDate >= $query_limit_date) $validURL = 1;
		else if ($receivedMonth == $Month && $receivedDate <= $Date) $validURL = 1;

		try {
			if ($M1 != $actual_M1) return false; //throw new Exception("Mismatching in M1 bit : URL has been changed " . $M1 . " " . $actual_M1);
			if ($D1 != $actual_D1) return false; //throw new Exception("Mismatching in the D1 bit : URL has been changed ");

			if ($validURL == 0) return false;//throw new Exception("Expired ShortURL : {$url}");
		} catch (Exception $e) {
			LoggingWrapper::getInstance()->sendLog(LoggingEnums::LOG_ERROR, $e, array('moduleName' => 'ShortURL'));
			return false;
		}
		
		return true;
	}

	/**
	 * Method to generate the 4-character ID in base-62
	 * @param: An auto-generated ID ($value)
	 */

	private function generateID ($value)
	{
		$id = '';
		while (floor($value) > 0) {
			$id .= $this->baseArray[$value % 62];
			$value /= 62;
		}
		$id = strrev($id);
		return $id;
	}
	
	/**
	 * Method to construct a 8-character ShortURL
	 * @param: 4-character long ID
	 * @return: ShortURL
	 */
	private function constructURL ($id)
	{
		$shortURL = "????????";
		$M1 = $this->baseArray[(array_search($id[$this->D1_bit], $this->baseArray) * array_search($id[$this->ID_fourth], $this->baseArray)) % $this->CONVERSION_BASE];
		$shortURL[4] = $M1;
		$D1 = $this->baseArray[(array_search($id[$this->ID_third], $this->baseArray) * array_search($id[$this->Month_bit], $this->baseArray))% $this->CONVERSION_BASE];
		$shortURL[0] = $D1;
		$today = getdate();
		$Month = $today['mon'];
		$Date = $today['mday'];
		$shortURL[2] = $this->baseArray[($Month * $this->MULTIPLICATION_FACTOR) % $this->CONVERSION_BASE];
		$shortURL[6] = $this->baseArray[($Date + $this->SUBTRACTION_FACTOR) % $this->CONVERSION_BASE];
		$shortURL[1] = $id[$this->Month_bit];
		$shortURL[3] = $id[$this->ID_fourth];
		$shortURL[5] = $id[$this->D1_bit];
		$shortURL[7] = $id[$this->ID_third];
		$shortURL = $this->PREFIX_URL . $shortURL;
		return $shortURL;
	}

}

?>
