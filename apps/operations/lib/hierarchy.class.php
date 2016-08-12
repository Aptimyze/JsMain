<?php

/************************************
 * Author : Neha Gupta
 * This class is to derive the hierarchy among the given pairs of (employee id and head id) and to calculate the final target, given the individual target.
*************************************/


class hierarchy
{
	private $root;                     // root of the hierarchy
	private $headid_arr;               // array with key as employee id and value as head(boss) id
	private $empname_arr;              // array with key as employee id and value as employee name
	private $list;                     // list containing the hierarchical data (in-order traversal form)
	private $trav_status;              // array containing the presence status of list nodes
	private $level_order;              // array containing the level order of list nodes

	public function __construct($root)
	{
		$this->root = $root;
		$this->headid_arr = array();
		$this->empname_arr = array();
		$this->list = array();
		$this->trav_status = array();
		$this->level_order = array();
	}

	public function traverse()
	{
		print_r($this->list);
	}

        public function getAllReporters()
        {
                $jsadminPswrdsObj = new jsadmin_PSWRDS('newjs_masterRep');
                $info = $jsadminPswrdsObj->get_All_EmpID_Name_HeadID();
                $empnameArr = $info[0];
                $headidArr = $info[1];

		$queue[0] = array_search($this->root, $empnameArr);
                for($i=0; $i<count($queue); $i++){
                        $reporters = array_keys($headidArr, $queue[$i]);
                        foreach($reporters as $value){
                                if(array_search($value,$queue))
                                        die("oops !! data entry error, eg. a->b->c->d->a circular loop case where a->b denotes employee->head");
                              	array_push($queue, $value);
                        }
		}

                $allReporters = array();
                foreach($queue as $value)
                        array_push($allReporters, $empnameArr[$value]);
                return $allReporters;
        }

	public function getLevel()
	{
		$this->level_order[$this->list[0]] = 0;
		$emp_ids = array();
		$queue = array();

		$emp_ids = array_keys($this->headid_arr, $this->list[0]);
		$queue = array_merge($queue, $emp_ids);

		foreach($emp_ids as $value)
			$this->level_order[$value] = 1;

		for($i=0; $i<count($queue); $i++){
			$emp_ids = array();
			$emp_ids = array_keys($this->headid_arr, $queue[$i]);

			foreach($emp_ids as $value){
                                if(array_search($value,$queue))
                                        die("oops !! data entry error, eg. a->b->c->d->a circular loop case where a->b denotes employee->head");
				array_push($queue, $value);
				$this->level_order[$value] = $this->level_order[$this->headid_arr[$value]]+1;
			}
		}
	}

	public function insertNode($emp, $boss)
	{
		if(in_array($boss, $this->list) && !in_array($emp, $this->list) && $this->trav_status[$boss]==0)
		{
			$key = array_search($boss, $this->list);
			array_splice($this->list, $key+1, 0, $emp);
			$this->trav_status[$boss] = 1;
			$this->trav_status[$emp] = 0;
		}

		else if(in_array($boss, $this->list) && !in_array($emp, $this->list) && $this->trav_status[$boss]==1)
		{
			$key = array_search($boss, $this->list);

			$flag = 0;
			for($i=$key+1; $i<count($this->list); $i++)
			{
				if($this->trav_status[$i]==0)
				{
					if($i<count($this->list)-1 && ($this->level_order[$emp]==$this->level_order[$this->list[$i+1]]) && strcasecmp($this->empname_arr[$emp], $this->empname_arr[$this->list[$i+1]])>0)
						continue;
					array_splice($this->list, $i+1, 0, $emp);
					$this->trav_status[$emp] = 0;
					$flag=1;
					break;
				}
			}

			if($flag == 0)
			{
				array_push($emp, $this->list);
				$this->trav_status[$emp] = 0;
			}
		}
	}

	public function insertLevelNodes($level_arr)
	{
		foreach($level_arr as $value)
		{
			$boss = $this->headid_arr[$value];
			$this->insertNode($value, $boss);
		}
	}

	public function getHierarchy($usernames)
	{
		$jsadminPswrdsObj = new jsadmin_PSWRDS('newjs_slave');
		$this->headid_arr = $jsadminPswrdsObj->get_Emp_Head_Id_Array($usernames);
		$this->empname_arr = $jsadminPswrdsObj->get_Emp_Name_Array($usernames);
		asort($this->empname_arr);

		$boss = array_search($this->root, $this->empname_arr);
		$this->list = array($boss);
		$emp_under_boss = array();

		foreach($this->empname_arr as $key => $value)
		{
			if($this->headid_arr[$key] == $boss)
				$emp_under_boss[] = $key;
		}

		$this->list = array_merge($this->list, $emp_under_boss);
		$this->trav_status[$boss] = 1;

		foreach($emp_under_boss as $value)
			$this->trav_status[$value] = 0;

		$this->getLevel();

		for($i=2; $i<=max($this->level_order); $i++)
		{
			$level_arr = array();
			$level_arr = array_keys($this->level_order, $i);

			$name_map = array();
			foreach($level_arr as $value)
				$name_map[] = $this->empname_arr[$value];

			asort($name_map);

			$level_arr = array();
			foreach($name_map as $value)
				$level_arr[] = array_search($value, $this->empname_arr);

			$this->insertLevelNodes($level_arr);
		}
	}
	/*
	This function is used to get all the hierarchy data related to a logged in executive
	@param - array of executives under the logged in executive (optional)
	@return - array through which hierarchy tree can be constructed. 
		  Below root is level 0 then level 1 and so on. 
		  if DIRECT_REPORTEE_STATUS = 1 then child nodes exist
		  if DIRECT_REPORTEE_STATUS = 0 then child nodes do not exist
	*/
	public function getHierarchyData($allReporters="")
	{
		if(!$allReporters)
			$allReporters = $this->getAllReporters();

		if($allReporters && is_array($allReporters))
		{
			$this->getHierarchy($allReporters);
			foreach($this->list as $key => $value)
                	{
                        	$hierarchy[$key]['USERNAME'] = $this->empname_arr[$value];
                        	$hierarchy[$key]['DIRECT_REPORTEE_STATUS'] = $this->trav_status[$value];
                        	$hierarchy[$key]['LEVEL'] = $this->level_order[$value];
                	}
		}
		return $hierarchy;
	}
	
	public function getHierarchyInfoStructure($individual_target)
	{
		$this->getHierarchy(array_keys($individual_target));
		$hierarchy = array();

		foreach($this->list as $key => $value)
		{
			$hierarchy[$key]['USERNAME'] = $this->empname_arr[$value];
			$hierarchy[$key]['DIRECT_REPORTEE_STATUS'] = $this->trav_status[$value];
			$hierarchy[$key]['LEVEL'] = $this->level_order[$value];
			$hierarchy[$key]['INDIVIDUAL_TARGET'] = $individual_target[$this->empname_arr[$value]];
		}

		for($i=count($this->list)-1; $i>=0; $i--)
		{
			$hierarchy[$i]['NUM_DIRECT_REPORTEE'] = 0;
			$hierarchy[$i]['SUM_X'] = 0;
			$hierarchy[$i]['SUM_Y'] = 0;
			$hierarchy[$i]['N'] = 0;

			if($hierarchy[$i]['DIRECT_REPORTEE_STATUS']==1)
			{
				if($hierarchy[$i]['INDIVIDUAL_TARGET']>0)
					$hierarchy[$i]['N'] = 1;

				$children = array();
				$children = array_keys($this->headid_arr, $this->list[$i]);

				for($c=0; $c<count($children); $c++)
				{
					$index_arr = array();
					$index_arr = array_keys($this->list, $children[$c]);
					$index = $index_arr[0];

					if(($hierarchy[$index]['DIRECT_REPORTEE_STATUS']==0) && ($hierarchy[$index]['INDIVIDUAL_TARGET']>0))
					{
						$hierarchy[$i]['N'] += 1;
						$hierarchy[$i]['SUM_X'] += $hierarchy[$index]['FINAL_TARGET'];
					}
					else if($hierarchy[$index]['DIRECT_REPORTEE_STATUS']==1)
						$hierarchy[$i]['SUM_Y'] += $hierarchy[$index]['FINAL_TARGET'];
				}
			}

			$hierarchy[$i]['FINAL_TARGET'] = $this->calculateFinalTarget($hierarchy[$i]);
		}
		return $hierarchy;
	}

	public function calculateFinalTarget($user)
	{
		$user['FINAL_TARGET'] = $user['INDIVIDUAL_TARGET']+$user['SUM_X'];
		$user['FINAL_TARGET'] *= 1-.005*$user['N'];
		$user['FINAL_TARGET'] += $user['SUM_Y'];
		return round($user['FINAL_TARGET']);
	}
}

?>
