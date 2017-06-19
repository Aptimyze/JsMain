<?php
class mmmjs_99_execution extends TABLE{

public function __construct($dbname="matchalerts_slave_localhost")
{
	parent::__construct($dbname);
}

public function getQueryAndResultBySearchCriteria($type,$column,$selectWhat,$limit_start='',$limit='',$mailer_id='')
{
	try
	{
		$obj = new createDropdown();
		$PROPERTY_TYPE = $obj->getPropertyTypeArray();
		switch($type) {
			case 'B':
				$sqlb = "";
				$isBUYER=0;
			
				if($column['sub_partners']){
						$isBUYER++;
						$sqlb.= "MMM_BUYER.SUB_PARTNERS='".$column['sub_partners']."' AND ";
				}
				if($column['sub_promo']){
					$isBUYER++;
					$sqlb.= "MMM_BUYER.SUB_PROMO='".$column['sub_promo']."' AND ";
				}
				if($column['buyer_rescom'])
				{
					$isBUYER++;
					$sqlb.= "MMM_BUYER.RES_COM='".$column['buyer_rescom']."' AND ";
				}
	
				if(!$column['buyer_preference_all'])
				{
					if($column['buyer_preference_buy']){
						$sqlb.= "((MMM_BUYER.PREFERENCE='B' AND ";
						{
							$budget_min = $column['budget_min'];
							$budget_max = $column['budget_max'];
							$isBUYER++;
							if(($budget_max==0) && ($budget_min==0)){
								$budget_max=9999999999;
								$budget_min=0;
								$sqlb.="MMM_BUYER.BUDGET_MIN >=$budget_min AND MMM_BUYER.BUDGET_MAX <=$budget_max";
							}
							elseif(($budget_min > 0) && ($budget_max==0)){
								if($budget_min==499999)
									$sqlb.="MMM_BUYER.BUDGET_MIN <$budget_min";
								else{
									$budget_max=9999999999;
									$sqlb.="MMM_BUYER.BUDGET_MIN <=$budget_max AND MMM_BUYER.BUDGET_MAX >= $budget_min";
								}
							}
							elseif(($budget_max > 0) && ($budget_min==0)){
								if($budget_min==499999)
									$sqlb.="MMM_BUYER.BUDGET_MAX <=$budget_max" ;
								else
									$sqlb.="MMM_BUYER.BUDGET_MIN <=$budget_max AND MMM_BUYER.BUDGET_MAX >= $budget_min";
							}
							elseif(($budget_max) && ($budget_min))
							$sqlb.="MMM_BUYER.BUDGET_MIN >=$budget_min AND MMM_BUYER.BUDGET_MAX <=$budget_max";
				
							$sqlb.= ") ";
						}
					}
				
					if($column['buyer_preference_rent'])
						$pref_list.="'R',";
					if($column['buyer_preference_lease'])
						$pref_list.="'L',";
					if($column['buyer_preference_pg'])
						$pref_list.="'P',";
				
					if(strlen($pref_list)>0)
					{
						$isBUYER++;
						if($column['buyer_preference_buy'])
							$sqlb.="OR ";
						$pref_list = '('.substr($pref_list,0,strlen($pref_list)-1).')';
				
						if($column['buyer_preference_buy'])
							$sqlb.= " MMM_BUYER.PREFERENCE IN$pref_list) AND ";
						else
							$sqlb.= " MMM_BUYER.PREFERENCE IN$pref_list AND ";
					}
					else{
						$isBUYER++;
						if($column['buyer_preference_buy'])
							$sqlb.= ") AND ";
						}
				}
				if(!is_array($column['buyer_prop_city']))
				{
					$column['buyer_prop_city'] = explode(',',$column['buyer_prop_city']);
				}	
				if($column['buyer_prop_city'] && !in_array('',$column['buyer_prop_city']))
				{
					$isBUYER++;
					$sqlb.= "(";
					for($i=0;$i<count($column['buyer_prop_city']);$i++)
					{
						$sqlb.= "FIND_IN_SET(".$column['buyer_prop_city'][$i].",MMM_BUYER.PROP_CITY)>0 OR ";
					
						$sql1 = "SELECT LEVELID,VALUE,LABEL FROM locations.LOCATION WHERE VALUE='".$column['buyer_prop_city'][$i]."'";
						$res1=$this->db->prepare($sql1);
						$res1->execute();
						$row1 = $res1->fetch(PDO::FETCH_ASSOC);
						
						if($row1['VALUE']=='216' || $row1['VALUE']=='221' || $row1['LEVELID']=='3' || preg_match('/\(All\)/',$row1['LABEL'])==1)   //This is a state
						{
						//exceptional cases of other cities
							if($row1['VALUE']=='216')       //this has no subcities
							{
								$sqlb.= "FIND_IN_SET(".$column['buyer_prop_city'][$i].",MMM_BUYER.PROP_CITY)>0 OR ";
							}

							$parent_array = array($row1['VALUE']);
                                                        $clone_parent_array = $parent_array;
                                                        while(!empty($parent_array)){
                                                        	$v = array_pop($parent_array);
                                                        	$sql2 = "SELECT CHILD_ID as VALUE FROM locations.PARENT_CHILD_RELATION WHERE PARENT_ID='".$v."'";
                                                        	$res2=$this->db->prepare($sql2);
                                                        	$res2->execute();
								while($row2 = $res2->fetch(PDO::FETCH_ASSOC))
                                                        	{
									if(!empty($row2['VALUE']) && !in_array($row2['VALUE'],$clone_parent_array)){
										$sqlb.= "FIND_IN_SET($row2[VALUE],MMM_BUYER.PROP_CITY)>0 OR ";
                                                        			array_push($parent_array,$row2[VALUE]);
										array_push($clone_parent_array,$row2['VALUE']);
									}
                                                        	}
                                                        }
						
							/*$sql2 = "SELECT CHILD_ID as VALUE FROM locations.PARENT_CHILD_RELATION WHERE PARENT_ID='".$row1['VALUE']."'";
							$res2=$this->db->prepare($sql2);
							$res2->execute();

							while($row2 = $res2->fetch(PDO::FETCH_ASSOC))
							{
								$sqlb.= "FIND_IN_SET($row2[VALUE],MMM_BUYER.PROP_CITY)>0 OR ";

								$sql3 = "SELECT CHILD_ID as VALUE FROM locations.PARENT_CHILD_RELATION WHERE PARENT_ID='".$row2['VALUE']."'";
                                                        	$res3=$this->db->prepare($sql3);
                                                        	$res3->execute();
								while($row3 = $res3->fetch(PDO::FETCH_ASSOC))
                                                        	{

									$sqlb.= "FIND_IN_SET($row3[VALUE],MMM_BUYER.PROP_CITY)>0 OR ";
								}
							}*/	
						
							if($row1['VALUE']=='1')       //special case for delhi, add cities like noida, faridabad etc. to the list
							{
								$sqlb.= "FIND_IN_SET('7',MMM_BUYER.PROP_CITY)>0 OR FIND_IN_SET('222',MMM_BUYER.PROP_CITY)>0 OR FIND_IN_SET('8',MMM_BUYER.PROP_CITY)>0 OR FIND_IN_SET('9',MMM_BUYER.PROP_CITY)>0 OR FIND_IN_SET('10',MMM_BUYER.PROP_CITY)>0 OR ";
							}
						}
					}
					$sqlb = substr($sqlb,0,strlen($sqlb)-4);
					$sqlb.= ") AND ";
				}
				
				if(!is_array($column['buyer_property_type'])){
					$column['buyer_property_type'] = explode(',',$column['buyer_property_type']);
				}
				if($column['buyer_property_type'] && !in_array('',$column['buyer_property_type']))
				{
					$isBUYER++;
					$sqlb.= "(";
					for($i=0;$i<count($column['buyer_property_type']);$i++)
					{
						if($column['buyer_property_type'][$i]=='R')	//all residential
						{
							for($j=2;$j<=8;$j++)
							{
								$temp = $PROPERTY_TYPE[$j]['VALUE'];
								$sqlb.= "FIND_IN_SET($temp,MMM_BUYER.PROPERTY_TYPE)>0 OR ";
							}
							$sqlb .= "FIND_IN_SET(90,MMM_BUYER.PROPERTY_TYPE)>0 OR )";  // adding studio apartment in all residential search(Bug 5223)
						}
						else if($column['buyer_property_type'][$i]=='C')    //all commercial
						{
							for($j=10;$j<=24;$j++)
							{
								$temp = $PROPERTY_TYPE[$j]['VALUE'];
								$sqlb.= "FIND_IN_SET($temp,MMM_BUYER.PROPERTY_TYPE)>0 OR ";
							}
						}
						else if($column['buyer_property_type'][$i]=='L')    //all land
						{
							for($j=26;$j<=29;$j++)
							{
								$temp = $PROPERTY_TYPE[$j]['VALUE'];
								$sqlb.= "FIND_IN_SET($temp,MMM_BUYER.PROPERTY_TYPE)>0 OR ";
							}
						}
						else
							$sqlb.= "FIND_IN_SET(".$column['buyer_property_type'][$i].",MMM_BUYER.PROPERTY_TYPE)>0 OR ";
					}
					$sqlb = substr($sqlb,0,strlen($sqlb)-4);
					$sqlb.= ") AND ";
				}
				if($column['buyer_country_source'] == 'Y')
				{
					$isBUYER++;
					$sqlb .= "MMM_BUYER.COUNTRY_CODE <> 'IN' AND ";
				}
				else
				{
					$isBUYER++;
				}
				
				if($isBUYER){
					$sqlb = substr($sqlb,0,strlen($sqlb)-5);
					$sql_final = "SELECT ".$selectWhat ." FROM mmm_99.MMM_BUYER WHERE MMM_BUYER.SCREENING = 'Y' AND MMM_BUYER.ACTIVATED = 'Y' AND ".$sqlb;
					if($sqlb=='')
						$sql_final = "SELECT ".$selectWhat." FROM mmm_99.MMM_BUYER WHERE MMM_BUYER.SCREENING = 'Y' AND MMM_BUYER.ACTIVATED = 'Y'";
				}
				
			break;
			case 'S':
				$sqls = "";
				$isSELLER=0;
				if($column['sub_partners']){
					$isSELLER++;
					$sqls.= "MMM_SELLER.SUB_PARTNERS='".$column['sub_partners']."' AND ";
				}
				if($column['sub_promo']){
					$isSELLER++;
					$sqls.= "MMM_SELLER.SUB_PROMO='".$column['sub_promo']."' AND ";
				}
				if(!is_array($column['city'])){
					$column['city'] = explode(',',$column['city']);
				}
				if($column['city'])	//if some city selected
				{
					if(!in_array('',$column['city']))	//something other than 'All' selected
					{
						$isSELLER++;
						$sqls.="MMM_SELLER.CITY IN ";
						$city_list = "(";
						for($i=0;$i<count($column['city']);$i++)
						{
							if($column['city'][$i]=='')
								continue;
							$sql1 = "SELECT LEVELID,LABEL,VALUE FROM locations.LOCATION WHERE VALUE='".$column['city'][$i]."'";
							$res1=$this->db->prepare($sql1);
							$res1->execute();
							$row1 = $res1->fetch(PDO::FETCH_ASSOC);

							if($row1['LEVELID']=='3' || $row1['VALUE']=='216' || $row1['VALUE']=='221' || preg_match('/\(All\)/',$row1['LABEL'])==1)	//This is a state
							{
									//exceptional cases of other cities
								if($row1['VALUE']=='216')	//this has no subcities
								{
									$city_list.= $column['city'][$i].",";
								}

								$parent_array = array($row1['VALUE']);
                                                       		$clone_parent_array = $parent_array; 
                                                        	while(!empty($parent_array)){
                                                        		$v = array_pop($parent_array);
                                                        		$sql2 = "SELECT CHILD_ID as VALUE FROM locations.PARENT_CHILD_RELATION WHERE PARENT_ID='".$v."'";
                                                        		$res2=$this->db->prepare($sql2);
                                                                	$res2->execute();
									while($row2 = $res2->fetch(PDO::FETCH_ASSOC))
                                                        		{
										if(!empty($row2['VALUE']) && !in_array($row2['VALUE'],$clone_parent_array))
                                                        			{
											$city_list.= "$row2[VALUE],";
											array_push($parent_array,$row2[VALUE]);
											array_push($clone_parent_array,$row2[VALUE]);
										}
                                                        		}
                                                        	}
					/*			$sql2 = "SELECT CHILD_ID as VALUE FROM locations.PARENT_CHILD_RELATION WHERE PARENT_ID='".$row1['VALUE']."'";
								$res2=$this->db->prepare($sql2);
								$res2->execute();

								while($row2 = $res2->fetch(PDO::FETCH_ASSOC))
								{
									$city_list.= "$row2[VALUE],";

									$sql3 = "SELECT CHILD_ID as VALUE FROM locations.PARENT_CHILD_RELATION WHERE PARENT_ID='".$row2['VALUE']."'";
                                                                	$res3=$this->db->prepare($sql3);
                                                                	$res3->execute();
									while($row3 = $res3->fetch(PDO::FETCH_ASSOC))
                                                                	{
										$city_list.= "$row3[VALUE],";
									}
								}*/
				
								if($row1['VALUE']=='1')	//special case for delhi, add cities like noida, faridabad etc. to the list
								{
									$city_list.= "7,222,8,9,10,";
								}
							}
							else	//This is a city
							{
								$city_list.= $column['city'][$i].",";
							}
						}
						$city_list = substr($city_list,0,strlen($city_list)-1);
						$city_list.=")";
						$sqls.="$city_list AND ";
					}
				}
				
				if(!($column['seller_class_agent'] && $column['seller_class_builder'] && $column['seller_class_owner']))	//1 or 2 classes selected
				{
					$isSELLER++;
					$temp="MMM_SELLER.CLASS IN(";
					if($column['seller_class_agent'])
						$temp.="'A',";
					if($column['seller_class_builder'])
						$temp.="'B',";
					if($column['seller_class_owner'])
						$temp.="'O',";
				
					$temp = substr($temp,0,strlen($temp)-1).') AND ';
					$sqls.=$temp;
				}
				
				if($column['seller_rescom'])
				{
					$isSELLER++;
					$sqls.="MMM_SELLER.RES_COM='".$column['seller_rescom']."' AND ";
				}

				if(!$column['seller_preference_all'])
				{
					if($column['seller_preference_sell'])
						$pref_list.="'S',";
					if($column['seller_preference_rent'])
						$pref_list.="'R',";
					if($column['seller_preference_lease'])
						$pref_list.="'L',";
					if($column['seller_preference_pg'])
						$pref_list.="'P',";
				
					if(strlen($pref_list)>0)
					{
						$isSELLER++;
						$pref_list = '('.substr($pref_list,0,strlen($pref_list)-1).')';
						$sqls.= "MMM_SELLER.PREFERENCE IN$pref_list AND ";
					}
				}
				if(!is_array($column['seller_property_type'])){
					$column['seller_property_type'] = explode(',',$column['seller_property_type']);
				}
				if($column['seller_property_type'] && !in_array('',$column['seller_property_type']))
				{
					$isSELLER++;
					$sqls.= "MMM_SELLER.PROPERTY_TYPE IN(";
					for($i=0;$i<count($column['seller_property_type']);$i++)
					{
						if($column['seller_property_type'][$i]=='R')       //all residential
						{
							for($j=2;$j<=8;$j++)
							{
								$temp = $PROPERTY_TYPE[$j]['VALUE'];
								$sqls.= "'$temp',";
							}
							$sqls.="'90',"; //adding studio apartment under all residentail search against bug 5223
						}
						else if($column['seller_property_type'][$i]=='C')    //all commercial
						{
							for($j=10;$j<=24;$j++)
							{
								$temp = $PROPERTY_TYPE[$j]['VALUE'];
								$sqls.= "'$temp',";
							}
						}
						else if($column['seller_property_type'][$i]=='L')    //all land
						{
							for($j=26;$j<=29;$j++)
							{
								$temp = $PROPERTY_TYPE[$j]['VALUE'];
								$sqls.= "'$temp',";
							}
						}
						else
							$sqls.= "'".$column['seller_property_type'][$i]."',";
					}
					$sqls = substr($sqls,0,strlen($sqls)-1);
					$sqls.= ") AND ";
				}
				if(!is_array($column['seller_prop_city'])){
					$column['seller_prop_city'] = explode(',',$column['seller_prop_city']);
				}
				if($column['seller_prop_city'] && !in_array('',$column['seller_prop_city']))
				{
					$isSELLER++;
					$sqls.= "MMM_SELLER.SELLER_CITY IN(";
					for($i=0;$i<count($column['seller_prop_city']);$i++)
					{
						$sql1 = "SELECT LEVELID,LABEL,VALUE FROM locations.LOCATION WHERE VALUE='".$column['seller_prop_city'][$i]."'";
						$res1=$this->db->prepare($sql1);
						$res1->execute();
						$row1 = $res1->fetch(PDO::FETCH_ASSOC);

						if($row1['VALUE']=='216' || $row1['VALUE']=='221' || $row1['LEVELID']==3 || preg_match('/\(All\)/',$row1['LABEL'])==1)   //This is a state
						{
							//exceptional cases of other cities
							if($row1['VALUE']=='216')       //this has no subcities
							{
								$sqls.= "'".$column['seller_prop_city'][$i]."',";
							}
				
							$parent_array = array($row1['VALUE']);
							$clone_parent_array = $parent_array;
                                                        while(!empty($parent_array)){
                                                        	$v = array_pop($parent_array);
                                                                $sql2 = "SELECT CHILD_ID as VALUE FROM locations.PARENT_CHILD_RELATION WHERE PARENT_ID='".$v."'";
                                                                $res2=$this->db->prepare($sql2);
                                                                $res2->execute();
                                                                while($row2 = $res2->fetch(PDO::FETCH_ASSOC))
                                                                {
									if(!empty($row2['VALUE']) && !in_array($row2['VALUE'],$clone_parent_array))
									{
                                                                		$sqls.= "'$row2[VALUE]',";
                                                                        	array_push($parent_array,$row2[VALUE]);
										array_push($clone_parent_array,$row2[VALUE]);	
									}
                                                                }
                                                        }

							/*$sql2 = "SELECT CHILD_ID as VALUE FROM locations.PARENT_CHILD_RELATION WHERE PARENT_ID='".$row1['VALUE']."'";
							$res2=$this->db->prepare($sql2);
							$res2->execute();
							while($row2 = $res2->fetch(PDO::FETCH_ASSOC))
							{
								$sqls.= "'$row2[VALUE]',";
								$sql3 = "SELECT CHILD_ID as VALUE FROM locations.PARENT_CHILD_RELATION WHERE PARENT_ID='".$row2['VALUE']."'";
                                                        	$res3=$this->db->prepare($sql3);
                                                        	$res3->execute();
                                                        	while($row3 = $res3->fetch(PDO::FETCH_ASSOC))
                                                        	{
									$sqls.= "'$row3[VALUE]',";
								}
							}*/
				
							if($row1['VALUE']=='1')       //special case for delhi, add cities like noida, faridabad etc. to the list
							{
								$sqls.= "'7','222','8','9','10',";
							}
						}
						else
						{
							$sqls.= "'".$column['seller_prop_city'][$i]."',";
						}
					}
					$sqls = substr($sqls,0,strlen($sqls)-1);
					$sqls.= ") AND ";
				}
				if($column['seller_country_source'] == 'Y')
				{
					$isSELLER++;
					$sqls .= "MMM_SELLER.COUNTRY_CODE <> 'IN' AND ";
					 
				}
				else
				{
					$isSELLER++;
				}
				
				if($isSELLER) {
					$sqls = substr($sqls,0,strlen($sqls)-5);
					$sql_final = "SELECT ".$selectWhat." FROM mmm_99.MMM_SELLER WHERE MMM_SELLER.SCREENING = 'Y' AND MMM_SELLER.ACTIVATED = 'Y' AND ".$sqls;
					if($sqls=='')
						$sql_final = "SELECT ".$selectWhat." FROM mmm_99.MMM_SELLER WHERE MMM_SELLER.SCREENING = 'Y' AND MMM_SELLER.ACTIVATED = 'Y'";
				}
				
			break;
		}
		
		if($limit != '') 
			$sql_final .= " limit ".$limit_start.",".$limit;
		if($selectWhat == 'COUNT(DISTINCT(PROFILEID)) as cnt')
		{
			$mmmjs_MAIN_MAILER =  new mmmjs_MAIN_MAILER;
                        $mmmjs_MAIN_MAILER->insertSearchQuery($sql_final,$mailer_id);
		}
		echo $sql_final;
		$result=$this->db->prepare($sql_final);
		$result->execute();
		while($rowset = $result->fetch(PDO::FETCH_ASSOC))
		{
			$result_final[]=$rowset;
		}
		return $result_final;
	}
    catch(PDOException $e)
    {
		throw new jsException($e);
    }
}

function executeFetchedQuery($sql){
	$result_final = array();
	$result=$this->db->prepare($sql);
        $result->execute();
        while($rowset = $result->fetch(PDO::FETCH_ASSOC))
        {
        	$result_final[]=$rowset;
        }
        return $result_final;

}
	/**
        * insert row in the table
        * @param $mailer - associative array key(column name) & value
        * @throws - PDO Exception 
        */

function insertEntry($mailer){
	try
        {
		$fields = array('MAILER_ID','RECIPIENT_TYPE','SCREENING','ACTIVATED','SUB_PROMO','SUB_PARTNERS','BUYER_RESCOM','BUYER_PREFERENCE_BUY','BUYER_PREFERENCE_RENT','BUYER_PREFERENCE_LEASE','BUYER_PREFERENCE_PG','BUYER_PROP_CITY','BUYER_PROPERTY_TYPE','BUDGET_MIN','BUDGET_MAX','BUYER_COUNTRY_SOURCE','BUYER_UPPER_LIMIT','SELLER_UPPER_LIMIT','SELLER_CLASS_AGENT','SELLER_CLASS_BUILDER','SELLER_CLASS_OWNER','SELLER_RESCOM','SELLER_PREFERENCE_ALL','BUYER_PREFERENCE_ALL','SELLER_PROPERTY_TYPE','SELLER_PROP_CITY','SELLER_COUNTRY_SOURCE','CITY','SELLER_PREFERENCE_SELL','SELLER_PREFERENCE_RENT','SELLER_PREFERENCE_LEASE','SELLER_PREFERENCE_PG','CITY_REGION','BUYER_PROP_CITY_REGION','SELLER_PROP_CITY_REGION');
		foreach($fields as $key=>$value)
		{
			$lower = strtolower($value);
			if(is_array($mailer[$lower]))
			{
				foreach($mailer[$lower] as $k => $v)
				{
					if($v == '')
						unset($mailer[$lower][$k]);
				}
				if(count($mailer[$lower])>0)
				{
					$table_col_values .= "'".$this->ArraytoString($mailer[$lower])."',";
					$table_col .= $value.",";
				}
			}
			else if($mailer[$lower] != '')
			{
				$table_col_values .= "'".$mailer[$lower]."',";
				$table_col .= $value.",";
			}
		}
		//remove last comma from the string
		if (substr($table_col_values, -1, 1) == ',')
		{
			  $table_col_values = substr($table_col_values, 0, -1);
		}	
		if (substr($table_col, -1, 1) == ',')
                {
                          $table_col = substr($table_col, 0, -1);
                }
		$sql = "replace into mmmjs.MAILER_SPECS_99(";
		$sql .= $table_col;
		$sql .= ") VALUES (";
		$sql .= $table_col_values;
		$sql .= ")";
		$res=$this->db->prepare($sql);
            	$res->execute();
	}
        catch(PDOException $e)
        {	
		throw new jsException($e);
        }

}
 	/**
        * This function will convert array to string.
        */
        private function ArraytoString($arr)
        {
                if(is_array($arr))
                {       $str = "";
                        foreach($arr as $key => $value)
                        {
                                if($value != '')
                                {       if(is_string($value))
                                                $str.=','.$value;
                                        else
                                                $str.=','.'$value';
                                }
                        }
                        return substr($str, 1);
                }
                return $arr;
        }

	/**
        * retrieve row from the table based on id
        * @param $id - primary key
        * @return row of the table
        * @throws - PDO Exception 
        */
        public function retrieveEntry($id)
        {
                try
                {

                        $sql = "select * from mmmjs.MAILER_SPECS_99 where  MAILER_ID = '".$id."'";
			$res=$this->db->prepare($sql);
                        $res->execute();
                        $row = $res->fetch(PDO::FETCH_ASSOC);
                        return $row;
                }
                catch(PDOException $e)
                {       throw new jsException($e);
                }
        }
	public function PopulateDumpData($dumpData,$id)
	{	try
                {	
			foreach($dumpData as $key=>$value)
			{
				$email = addslashes($value['EMAIL']);
				$name=addslashes($value['NAME']);
				$profileid=$value['PROFILEID'];
				$phoneNo=$value['PHONE'];
				$table_name = $id."mailer";
				$sql = "INSERT IGNORE INTO mmmjs.$table_name (PROFILEID,EMAIL,NAME,PHONE) VALUES ('".$profileid."','".$email."','".$name."','".$phoneNo."')";
				$res=$this->db->prepare($sql);
                        	$res->execute();
                	}
		}
		catch(PDOException $e)
		{
			throw new jsException($e);
		}
        }
	
}
?>
