<?php
class createDropdown {

public function createDropdown(){
	$this->PROPERTY_TYPE = $this->getPropertyTypeArray();
	$this->BUDGET_MIN_MAX = $this->getMinMaxStaticData();
	$this->CITY_REGION = $this->getCityRegionData();
}

public function connect_db_99($dbname){
	$hostname = MysqlDbConstants::$MMM_99['HOST'];
	$port = MysqlDbConstants::$MMM_99['PORT'];
	$user = MysqlDbConstants::$MMM_99['USER'];
	$pass = MysqlDbConstants::$MMM_99['PASS'];
	$db=@mysql_connect("$hostname".":".$port,"$user","$pass") or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes","","ShowErrTemplate");
       	@mysql_select_db($dbname,$db);
        return $db;
}
public function getCityRegionData(){
$city_region_array = array ('0'=>array(
                                      	'VALUE'=>'N',
                                        'LABEL'=>'North Region'
                                      ),
                            '1'=>array(
                                        'VALUE'=>'S',
                                        'LABEL'=>'South Region'
                                        ),
                             '2'=>array(
                                        'VALUE'=>'E',
                                        'LABEL'=>'East Region'
                                        ),
                             '3'=>array(
                                        'VALUE'=>'W',
                                        'LABEL'=>'West Region'
                                        )
                            );
	return $city_region_array;
}
public function getPropertyTypeArray(){
	$sql = "SELECT * FROM static_data.PROPERTY_TYPE WHERE TYPE_IDENTIFIER='PROPERTY_TYPE'";
        $db99=$this->connect_db_99("static_data");
        $res = mysql_query($sql,$db99) or die(mysql_error($db99));
        $row_count = mysql_num_rows($res);
        mysql_close($db99);
       	if($row_count > 0)
        {
        	$valuelabel=array();
                $i=1;
                while ($row = mysql_fetch_array($res))
                {
                	foreach ($row as $key=>$value)
                        {
				if($key == 'LABEL')
					$valuelabel[$i]['LABEL'] = $value;
				if($key == 'VALUE')
                                	$valuelabel[$i]['VALUE'] = $value;
                        }
                        $i++;
                }
		foreach($valuelabel as $key=>&$value)
		{
			if($value['VALUE'] == '80')
				$value['LABEL'] = 'Other Residential';
			if($value['VALUE'] == '81') 
				$value['LABEL'] = 'Other Commercial';
		}

                return $valuelabel;
         }
}
public function getMinMaxStaticData()
{
			
	$sql = "SELECT * FROM static_data.BUDGET_MIN_MAX WHERE TYPE_IDENTIFIER='BUYING_BUDGET' AND ACTIVE='Y' order by SORTBY asc";
	$db99=$this->connect_db_99("static_data");
	$res = mysql_query($sql,$db99) or die(mysql_error($db99));
	$row_count = mysql_num_rows($res);
        mysql_close($db99);
	if($row_count > 0)
	{
		$valuelabel=array();
		$i=0;
		while ($row = mysql_fetch_array($res))
		{
			foreach ($row as $key=>$value)
			{
				if($key == 'MIN_VALUE')
					$valuelabel[$i]['MIN_VALUE'] = $value;
				if($key == 'LABEL')
					$valuelabel[$i]['LABEL'] = $value;
			}
			$i++;
		}
		foreach($valuelabel as $key=>&$value)
		{
			if($value['MIN_VALUE'] == '1') {
				$value['MIN_VALUE'] = '499999';
				break;
			}
		}	
		return $valuelabel;
	}
}

public function create_dd($selected,$cname)
{
	$ret = array();
	if(is_array($selected))
	{
		$s_arr = $selected;
	}
	elseif($selected!="")
	{
		$s_arr=explode(",",$selected);
	}
	else 
		$s_arr=array();

	if($cname=="99property_type")
	{
		//*********PROPERTY TYPE ARRAY FOR SERACH PURPOSE*********

		for($i=1;$i<=count($this->PROPERTY_TYPE);$i++)
                {
			$ret[$i]['value']=$this->PROPERTY_TYPE[$i]['VALUE'];
			$ret[$i]['label']=$this->PROPERTY_TYPE[$i]['LABEL'];
			if(in_array($this->PROPERTY_TYPE[$i]["VALUE"],$s_arr))
				$ret[$i]['selected'] = '1';
			else
				$ret[$i]['selected'] = '0';
                }

	}
	
	if($cname=="city99")
        {
		$db99=$this->connect_db_99("property");
		//$sql = "select SQL_CACHE VALUE, LABEL, LEVELID from locations.LOCATION ";
		$sql = "SELECT LVALUE,VALUE, SORT_BY, LABEL,LEVELID FROM locations.GROUP_DATA, locations.LOCATION WHERE GROUPID='6' AND LVALUE=VALUE AND ACTIVATED='Y' ORDER BY SORT_BY";
                $res = mysql_query($sql,$db99) or die(mysql_error($db99));
		//mysql_close($db99);
                $ret = array();
		$i = 0;
                while($myrow = mysql_fetch_array($res))
                {
			
			$ret[$i]['value']=$myrow['VALUE'];
			$ret[$i]['label']=$myrow['LABEL'];
			if($myrow['LEVELID'] == '3'||$myrow['VALUE'] == '221'||$myrow['VALUE']=='216' || $myrow['VALUE'] == '19' ||$myrow['VALUE'] == '262' || $myrow['VALUE'] == '185' || preg_match('/\(All\)/',$myrow['LABEL'])==1)
                                $class = "boldclass";
                        else
                                 $class = "";
			
			$sql1 = "SELECT PARENT_ID as VALUE FROM locations.PARENT_CHILD_RELATION WHERE CHILD_ID='".$myrow['VALUE']."'" ;
                	$res1 = mysql_query($sql1,$db99) or die(mysql_error($db99));
                	while($row1 =   mysql_fetch_array($res1))
			{
				if(!in_array($row1['VALUE'],$parentArray)){
					$parentArray[]=$row1['VALUE'];
					$parentId=$row1['VALUE'];
				}
				break;
			}
			if($parentId == '262')  // For Goa with parent city other hotspots
				$class = "";
			
			$ret[$i]['class'] = $class;
			if(in_array($myrow["VALUE"],$s_arr))
				$ret[$i]['selected'] = 1;
			else
				$ret[$i]['selected'] = 0;
			$i++;
                }
		
		mysql_close($db99);

        }
	
	if($cname=="buying_budget")
	{
		 for($i=0;$i<count($this->BUDGET_MIN_MAX);$i++)
                {
			if($this->BUDGET_MIN_MAX[$i]['MIN_VALUE'] == 0)
				continue;
                        $ret[$i]['value']=$this->BUDGET_MIN_MAX[$i]['MIN_VALUE'];
                        $ret[$i]['label']=$this->BUDGET_MIN_MAX[$i]['LABEL'];
                        if(in_array($this->BUDGET_MIN_MAX[$i]["MIN_VALUE"],$s_arr))
                                $ret[$i]['selected'] = '1';
                        else
                                $ret[$i]['selected'] = '0';
                }
	}
	if($cname == 'city_region')
	{
		for($i=0;$i<count($this->CITY_REGION);$i++)
		{
			$ret[$i]['value'] = $this->CITY_REGION[$i]['VALUE'];
			$ret[$i]['label'] = $this->CITY_REGION[$i]['LABEL'];
			if(in_array($this->CITY_REGION[$i]["VALUE"],$s_arr))
                                $ret[$i]['selected'] = '1';
                        else
                                $ret[$i]['selected'] = '0';
		}
	}
	return $ret;
}
}
?>
