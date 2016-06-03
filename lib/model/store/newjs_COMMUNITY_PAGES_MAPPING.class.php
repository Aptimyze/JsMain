<?php
class NEWJS_COMMUNITY_PAGES_MAPPING extends TABLE
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
					$sql="SELECT SQL_CACHE PARENT_VALUE,PARENT_TYPE,PARENT_LABEL,MAPPED_VALUE,MAPPED_TYPE,MAPPED_LABEL,SOURCE,CONTENT,TITLE,DESCRIPTION,KEYWORDS,H1_TAG,FOLLOW,IMG_URL,ALT_TAG,PAGE_SOURCE FROM newjs.COMMUNITY_PAGES_MAPPING WHERE URL = :URL AND ACTIVE = 'Y'";
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
	 			$sql = "Select URL,PAGE_SOURCE from COMMUNITY_PAGES_MAPPING where PARENT_VALUE = :PARENT_VALUE and MAPPED_VALUE = :MAPPED_VALUE and PARENT_TYPE = :PARENT_TYPE and MAPPED_TYPE = :MAPPED_TYPE and PAGE_SOURCE IN('B','G') and ACTIVE='Y'";
	 			$prep = $this->db->prepare($sql);
	 			$prep->bindValue(":PARENT_VALUE",$whereArr['PARENT_VALUE'],PDO::PARAM_INT);
	 			$prep->bindValue(":MAPPED_VALUE",$whereArr['MAPPED_VALUE'],PDO::PARAM_INT);
	 			$prep->bindValue(":PARENT_TYPE",$whereArr['PARENT_TYPE'],PDO::PARAM_STR);
	 			$prep->bindValue(":MAPPED_TYPE",$whereArr['MAPPED_TYPE'],PDO::PARAM_STR);			
	 			
	 			$prep->execute();
	 			while($result = $prep->fetch(PDO::FETCH_ASSOC))
				{
					$res[]=$result;
				}
				return $res;
	 			
	 		}
	 	}
	 	catch(PDOException $e)
		{
			/*** echo the sql statement and error message ***/
			throw new jsException($e);
		}	
	}
	public function fetchL2BreadCrumb($whereArr)
	{
		try {
			if($whereArr)
			{
				$sql = "SELECT SQL_CACHE MAPPED_VALUE,MAPPED_TYPE,URL,FOLLOW FROM COMMUNITY_PAGES_MAPPING WHERE PARENT_VALUE=:PARENT_VALUE AND PARENT_TYPE=:PARENT_TYPE  AND ACTIVE='Y' and PAGE_SOURCE=:PAGE_SOURCE";
                $prep = $this->db->prepare($sql);
	 			$prep->bindValue(":PARENT_VALUE",$whereArr['PARENT_VALUE'],PDO::PARAM_INT);
	 			$prep->bindValue(":PARENT_TYPE",$whereArr['PARENT_TYPE'],PDO::PARAM_STR);
	 			$prep->bindValue(":PAGE_SOURCE",$whereArr['PAGE_SOURCE'],PDO::PARAM_STR);			
	 			
	 			$prep->execute();
	 			while($result = $prep->fetch(PDO::FETCH_ASSOC))
				{
				  $res[] = $result;	
				}
				return $res;
	 		
			}
		}
	    catch(PDOException $e)
	    {
			/*** echo the sql statement and error message ***/
			throw new jsException($e);
		}
	}
	
	public function getSiteMapLinks($parentType,$mappedType,$pageSource)
	{
		try {
				if($parentType && $mappedType)
				{
					$sql = "SELECT SQL_CACHE IF(PARENT_TYPE!=MAPPED_TYPE,concat_ws(' ',PARENT_LABEL,MAPPED_LABEL),concat_ws(' ',MAPPED_LABEL)) AS LABEL,URL FROM COMMUNITY_PAGES_MAPPING WHERE PARENT_TYPE=:PARENT_TYPE AND MAPPED_TYPE=:MAPPED_TYPE  AND ACTIVE='Y' and PAGE_SOURCE=:PAGE_SOURCE";
	                $prep = $this->db->prepare($sql);
		 			
		 			$prep->bindValue(":PARENT_TYPE",$parentType,PDO::PARAM_STR);
		 			$prep->bindValue(":MAPPED_TYPE",$mappedType,PDO::PARAM_INT);
		 			$prep->bindValue(":PAGE_SOURCE",$pageSource,PDO::PARAM_STR);			
		 			
		 			$prep->execute();
		 			while($result = $prep->fetch(PDO::FETCH_ASSOC))
					{
					  $res[] = $result;	
					}
					return $res;
				}
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
                                $sql = "select SQL_CACHE URL,PAGE_SOURCE from newjs.COMMUNITY_PAGES_MAPPING where ACTIVE='Y' and FOLLOW='Y' and PAGE_SOURCE=:PAGE_SOURCE";

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
    
        
    /*
     * @desc: get all the data from the table
     * @input: none
     * @output: none
     */
    public function getAll(){
        try{
            $sql = "SELECT * from newjs.COMMUNITY_PAGES_MAPPING";
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
            $sql = "UPDATE newjs.COMMUNITY_PAGES_MAPPING SET TITLE = :TITLE WHERE ID = :ID";
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
            $sql = "CREATE TABLE newjs.`COMMUNITY_PAGES_MAPPING_$dt` LIKE newjs.COMMUNITY_PAGES_MAPPING";
            $res = $this->db->prepare($sql);
            $res->execute();
            
            $sql = "INSERT INTO newjs.`COMMUNITY_PAGES_MAPPING_$dt` SELECT * FROM newjs.COMMUNITY_PAGES_MAPPING";
            $res = $this->db->prepare($sql);
            $res->execute();
            
        } catch (Exception $ex) {
            throw new jsException($ex);
        }
    }

}
