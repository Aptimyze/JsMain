<?php
class SortingArray
{
	private $sortingArray = array();
	/*
	public function __construct()
	{
		unset($this->sortingArray);
		$this->sortingArray=array();
	}*/

	public function add($sender)
	{
		$this->sortingArray[] = $sender;
	}

	/*
	public function sort($attribute = 'name')
	{
	$sortFct = 'sortBy' . ucfirst(strtolower($attribute));
	if (!in_array($sortFct, get_class_methods('Person')))
	{
	    throw new Exception('People->sort(): Can\'t sort by ' . $attribute);
	}
	usort($this->people, 'Person::' . $sortFct);
	}*/

	public function makeAccessible() 
	{
		return $this->sortingArray;
	}

	public function sort() 
	{
		usort($this->sortingArray, array($this, 'compareSender'));
		return $this->sortingArray;
	}

	public function compareSender(Sender $p1, Sender $p2) 
	{
		//return strcmp($p2->getScore(), $p1->getScore());
		return $p2->getScore()>$p1->getScore()?1:0;
	}

}
?>
