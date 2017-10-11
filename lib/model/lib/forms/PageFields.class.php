<?php
class PageFields{
	private $fields_by_id;
	private $fields_group_wise;
	private $position;
	private $page;
	private $fields_by_name;
	function __construct($page){
		$this->page=$page;
	}
	function setField($field_id,$field_group,$field_position,$field_obj){
		$this->fields_group_wise[$field_group][]=$field_id;
		$this->fields_by_id[$field_id]=$field_obj;
		$this->position[$field_id]=$field_position;
		$this->fields_by_name[$field_obj->getName()]=$field_obj;
	}
    function getGroupWiseFields($group){
		$arr=array();
		foreach($this->fields_group_wise[$group] as $field_id)
			$arr[$this->position[$field_id]]=$this->fields[$field_id];
		return $arr;
	}
	function getFields(){
		return $this->fields_by_id;
	}
	function getFieldByName($name){
		return $this->fields_by_name[$name];
	}
}
