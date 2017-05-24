<?php 
class PartialList{
	private $partials;
    function __construct(){
	}
	function addPartial($smarty_var_name,$tpl_name,$inputs='',$in_loop=true){
		$this->partials[]=new Partial($smarty_var_name,$tpl_name,$inputs,$in_loop);
	}
	/* This will return the last partial from the list and removes the returns item from list
	 * */
	function fetchPartial(){
		$count=count($this->partials);
		if(!$count)
			return false;
		else
		{
			$last_elem=$count-1;
			//unset last element and return it;
			$partial=$this->partials[$last_elem];
			unset($this->partials[$last_elem]);
			return $partial;
		}
	}
}
class Partial{
	public $tpl;
	public $inputs;
	public $name;
	public $in_loop;
	function __construct($name,$tpl,$inputs,$in_loop){
		$this->name=$name;
		$this->tpl=$tpl;
		$this->inputs=$inputs;
		$this->in_loop=$in_loop;
	}
}
