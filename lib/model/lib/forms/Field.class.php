<?php
class Field{
	private $id;
	private $name;
	private $fieldType;
	private $constrantClass;
	private $jsValidation;
	private $dependentFields;
	private $tableName;
	private $label;
	private $blankValue;
	private $blankLabel;
	function __construct($id,$name=""){
		$this->id=$id;
		if($name)
			$this->name=$name;
	}
	function setName($name){
		$this->name=$name;
	}
	function setLabel($label){
		$this->label=$label;
	}
	function setFieldType($type){
		$this->fieldType=$type;
	}
	function setConstraintClass($className){
		$this->constrantClass=$className;
	}
	function setJsValidation($validation){
		$this->jsValidation=$validation;
	}
	function setDependentField($field){
		$this->dependentFields=$field;
	}
	function getName(){
		return $this->name;
	}
	function getFieldType(){
		return $this->fieldType;
	}
	function getConstraintClass(){
		return $this->constrantClass;
	}
	function getJsValidation(){
		return $this->jsValidation;
	}
	function getDependentField(){
		return $this->dependentFields;
	}
	function getLabel(){
		return $this->label;
	}
	function setTableName($table){
		return $this->tableName=$table;
	}
	function getTableName(){
		return $this->tableName;
	}
	function setBlankValue($blank){
		return $this->blankValue=$blank;
	}
	function getBlankValue(){
		return $this->blankValue;
	}
	function getBlankLabel(){
		return $this->blankLabel;
	}
	function setBlankLabel($blank){
		return $this->blankLabel=$blank;
	}
}
