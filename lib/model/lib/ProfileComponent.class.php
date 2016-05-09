<?php
class ProfileComponent
{
	private $nullValueMarker;
	public function __get($field)
	{
		return $this->nullValueMarker;
	}
	public function __set($field,$value)
	{
		$this->$field=$value;
	}
	public function setNullValueMarker($value)
	{
		$this->nullValueMarker=$value;
	}
}
