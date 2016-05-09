<?php
/** 
* class for performing operation related to csv data.
*/
class MmmCsvService
{
	private $csvLimit = 5000; //max number of rows can be inserted in a single go.
	
	/**
	* This function will create the dump based on dump data and mailerid .....
	* @param dumpData array
	* @param mailerId int
	*/
	public function createDump($dumpData,$mailerId)
	{
		$noOfElements = count($dumpData);

		$count = 0;

		$newArr = array();
		while($count*$this->csvLimit < $noOfElements)
		{
			for($i = 0; ($i+$count*$this->csvLimit) < ($noOfElements) && $i < $this->csvLimit ; $i++)
			{
				$newArr[$i] = $dumpData[$count*$this->csvLimit + $i];
			}
			$Individual_Mailers =  new Individual_Mailers;
			$Individual_Mailers->populateTableBasedOnArray($newArr,$mailerId);
			$count++;
			unset($newArr);
		}
	}
}
