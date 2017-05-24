<?php
class NEWJS_COMMUNITY_PAGES extends TABLE
{
    public function __construct($dbname="")
    {
	    parent::__construct($dbname);
    }
    public function getLevelObject($url)
    {
        try 
		{
		    if($url)
			{ 
					$sql="SELECT SQL_CACHE VALUE,LABEL_NAME,SMALL_LABEL,TYPE,LEVEL,SOURCE,CONTENT,TITLE,DESCRIPTION,KEYWORDS,H1_TAG,FOLLOW,IMG_URL,ALT_TAG,PAGE_SOURCE FROM COMMUNITY_PAGES WHERE URL = :URL AND ACTIVE = 'Y' AND LEVEL='1'";
					$prep=$this->db->prepare($sql);
					$prep->bindValue(":URL",$url,PDO::PARAM_STR);
					$prep->execute();
					if($result = $prep->fetch(PDO::FETCH_ASSOC))
					{
						return $result;
					}
					return NULL;
				}
				else
				throw new jsException("No url present in seo level object");	
			}
			catch(PDOException $e)
			{
				/*** echo the sql statement and error message ***/
				throw new jsException($e);
			}
	}
	public function getURL($whereArr)
	{
		try 
	 	{
	 		if($whereArr)
	 		{
	 			$sql = "Select URL,PAGE_SOURCE from COMMUNITY_PAGES where VALUE = :VALUE and TYPE = :TYPE and PAGE_SOURCE IN('B','G') and ACTIVE='Y'";
	 			$prep = $this->db->prepare($sql);
	 			$prep->bindValue(":VALUE",$whereArr['VALUE'],PDO::PARAM_INT);
	 			$prep->bindValue(":TYPE",$whereArr['TYPE'],PDO::PARAM_STR);
	 			$prep->execute();
	 			while($result = $prep->fetch(PDO::FETCH_ASSOC))
				{
					$res[]= $result;
				}
				return $res;
	 			
	 		}
	 		else
				throw new jsException("No search parameter present in input array");
	 	}
	 	catch(PDOException $e)
		{
			/*** echo the sql statement and error message ***/
			throw new jsException($e);
		}	
	}
	public function fetchL1BreadCrumb($whereArr)
	{
		try {
			if($whereArr)
			{
				$sql="SELECT SQL_CACHE SMALL_LABEL,LABEL_NAME,VALUE,URL,FOLLOW FROM COMMUNITY_PAGES WHERE TYPE IN (".$whereArr['TYPE'].") AND LEVEL='1' AND ACTIVE='Y' and PAGE_SOURCE= :PAGE_SOURCE ORDER BY ID";
				$prep = $this->db->prepare($sql);	 			
	 			//$prep->bindValue(":TYPE",$whereArr['TYPE'],PDO::PARAM_STR);
	 			$prep->bindValue(":PAGE_SOURCE",$whereArr['PAGE_SOURCE'],PDO::PARAM_STR);
	 			$prep->execute();
	 			while($result = $prep->fetch(PDO::FETCH_ASSOC))
				{
					$res[] = $result;
				}
				return $res;

			}
			
		} catch (PDOException $e) {
			throw new jsException($e);
		}
		
	}
    public function getResult($whereArr)
    {
		try 
	 	{
	 		if($whereArr)
	 		{
	 			$sql = "Select SQL_CACHE SMALL_LABEL,LABEL_NAME,TYPE from COMMUNITY_PAGES where VALUE = :VALUE and TYPE = :TYPE";
	 			$prep = $this->db->prepare($sql);
	 			$prep->bindValue(":VALUE",$whereArr['VALUE'],PDO::PARAM_INT);
	 			$prep->bindValue(":TYPE",$whereArr['TYPE'],PDO::PARAM_STR);
	 			$prep->execute();
	 			while($result = $prep->fetch(PDO::FETCH_ASSOC))
				{
					$res[]= $result;
				}
				return $res;
	 			
	 		}
	 		else
				throw new jsException("No search parameter present in input array");
	 	}
	 	catch(PDOException $e)
		{
			/*** echo the sql statement and error message ***/
			throw new jsException($e);
		}	
	}
	
	public function getLink($caste, $occupation, $religion, $mtng,$city,$country='',$page_source='N')
    {
		try 
	 	{
			if($caste)
				$arr[]="( TYPE = 'CASTE' AND VALUE IN ($caste) AND LEVEL = '1' AND ACTIVE='Y')";
			if($occupation)
				$arr[]="( TYPE = 'OCCUPATION' AND VALUE IN ($occupation) AND LEVEL = '1' AND ACTIVE='Y')";
			if($religion)
				$arr[] = "( TYPE = 'RELIGION' AND VALUE IN ($religion) AND LEVEL = '1' AND ACTIVE='Y')";
			if($mtng)
				$arr[] = "( TYPE = 'MTONGUE' AND VALUE IN ($mtng) AND LEVEL = '1' AND ACTIVE='Y')";
			if($city)
				$arr[] = "( (TYPE = 'CITY' OR TYPE = 'STATE') AND VALUE IN ($city) AND LEVEL = '1' AND ACTIVE='Y')";
			if($country)
				$arr[] = "( (TYPE = 'COUNTRY') AND VALUE IN ($country) AND LEVEL = '1' AND ACTIVE='Y')";
			
			$whereSql=implode(" OR ",$arr);
			
			if($whereSql)
			{
				$whereSql="(".$whereSql.")"."and PAGE_SOURCE='$page_source'";
			
				$sql = "SELECT SQL_CACHE URL,TYPE,VALUE FROM newjs.COMMUNITY_PAGES WHERE $whereSql";
				$prep = $this->db->prepare($sql);
	
				$prep->execute();
				while($result = $prep->fetch(PDO::FETCH_ASSOC))
				{
					$res[]= $result;
				}
			
				return $res;
	 		}
	 		return null;
	 	}
	 	catch(PDOException $e)
		{
			/*** echo the sql statement and error message ***/
			throw new jsException($e);
		}	
	}
	public function getSiteMapLinks($pageSource)
	{
		try {
			$sql = "Select SQL_CACHE IF(SMALL_LABEL!='',SMALL_LABEL,LABEL_NAME) AS LABEL,UPPER(TYPE) as TYPE,URL FROM COMMUNITY_PAGES WHERE PAGE_SOURCE = :PAGE_SOURCE AND ACTIVE='Y'";
			$prep = $this->db->prepare($sql);
	 		$prep->bindValue(":PAGE_SOURCE",$pageSource,PDO::PARAM_STR);
	 		$prep->execute();
	 		while($result = $prep->fetch(PDO::FETCH_ASSOC))
			{
				$res[$result['TYPE']][]= $result;
			}
			return $res;
			
		}
		catch (PDOException $e)
		{
			/*** echo the sql statement and error message ***/
			throw new jsException($e);
		}
	}
	
	public function getAllTopSeoLinks()
	{
		$sql="select SQL_CACHE PAGE_SOURCE,if(TYPE='MSTATUS','SPECIAL_CASES',TYPE) as TYPE,if (SMALL_LABEL ='',LABEL_NAME,SMALL_LABEL) as LABEL,URL,VALUE from newjs.COMMUNITY_PAGES where SORTBY>0 and  ACTIVE='Y'order by SORTBY ASC";
		
		$prep = $this->db->prepare($sql);
		$prep->execute();
		while($row=$prep->fetch(PDO::FETCH_ASSOC))
		{
			$seoFooter[trim($row[TYPE])][trim($row[VALUE])][trim($row[PAGE_SOURCE])]=array($row[URL],$row[LABEL],preg_replace('/[\/-]/',' ',$row[LABEL]));
		}
		return $seoFooter;
	}
	public function getBreadcrumbLink($type,$value)
    {
		try 
	 	{
				$sql = "SELECT SQL_CACHE URL FROM newjs.COMMUNITY_PAGES WHERE TYPE = :TYPE AND VALUE = :VALUE AND LEVEL = '1' AND ACTIVE='Y' and PAGE_SOURCE='N'";
				
				$prep = $this->db->prepare($sql);
				$prep->bindValue(":VALUE",$value,PDO::PARAM_INT);
	 			$prep->bindValue(":TYPE",$type,PDO::PARAM_STR);
				$prep->execute();
				while($result = $prep->fetch(PDO::FETCH_ASSOC))
				{
					$res = $result;
				}
			
				return $res;
	 	}
	 	catch(PDOException $e)
		{
			/*** echo the sql statement and error message ***/
			throw new jsException($e);
		}	
	}
	public function getURLS($source)
        {
                try
                {
                                $sql = "select SQL_CACHE URL,PAGE_SOURCE from newjs.COMMUNITY_PAGES where ACTIVE='Y' and FOLLOW='Y' and PAGE_SOURCE=:PAGE_SOURCE";

                                $prep = $this->db->prepare($sql);
                                $prep->bindValue(":PAGE_SOURCE",$source,PDO::PARAM_STR);
                                $prep->execute();
                                while($result = $prep->fetch(PDO::FETCH_ASSOC))
                                {
                                        $res[] = $result;
                                }

                                return $res;
                }
                catch(PDOException $e)
                {
                        /*** echo the sql statement and error message ***/
                        throw new jsException($e);
                }
        }
    
    /*@desc:Get all data from the table
    *@input: none
    * @output: none
    */
    public function getAll(){
        try{
            $sql = "SELECT * from newjs.COMMUNITY_PAGES";
            $prep = $this->db->prepare($sql);
            $prep->execute();
            while($row = $prep->fetch(PDO::FETCH_ASSOC)){
                $result[]=$row;
            }
            return $result;
        } catch (Exception $ex) {
            throw new jsException($ex);
        }
    }
    
    /*
     * @desc: update title for a given id
     * @input: id, title
     * @output: none
     */
    public function update($id, $titleStr){
        try{
            $sql = "UPDATE newjs.COMMUNITY_PAGES SET TITLE = :TITLE WHERE ID = :ID";
            $res = $this->db->prepare($sql);
            $res->bindValue(":TITLE", $titleStr, PDO::PARAM_STR);
            $res->bindValue(":ID", $id, PDO::PARAM_INT);
            $res->execute();
        } catch (Exception $ex) {
            throw new jsException($ex);
        }
    }
    
    /*
     * @desc Create a backup table before performing title change
     * @input: none
     * @output: none
     */
    public function createBackupTable(){
        try{
            $dt = date('Y-m-d');
            $sql = "CREATE TABLE newjs.`COMMUNITY_PAGES_$dt` LIKE newjs.COMMUNITY_PAGES";
            $res = $this->db->prepare($sql);
            $res->execute();
            
            $sql = "INSERT INTO newjs.`COMMUNITY_PAGES_$dt` SELECT * FROM newjs.COMMUNITY_PAGES";
            $res = $this->db->prepare($sql);
            $res->execute();
            
        } catch (Exception $ex) {
            throw new jsException($ex);
        }
    }
}

