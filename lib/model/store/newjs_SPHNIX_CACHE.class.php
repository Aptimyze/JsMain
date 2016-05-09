<?php
class NEWJS_SPHNIX_CACHE extends TABLE{
       

        

        public function __construct($dbname="")
        {
			parent::__construct($dbname);
        }
	public function getFP_CACHE($searchid)
	{
		try
		{
			$res=null;
			if($searchid)
			{
				$sql="SELECT RESULTS FROM newjs.FEATURED_PROFILE_CACHE WHERE SID=:SEARCHID";
				$prep=$this->db->prepare($sql);
				$prep->bindValue(":SEARCHID",$searchid,PDO::PARAM_INT);
				$prep->execute();
				if($result = $prep->fetch(PDO::FETCH_ASSOC))
				{
					$res=$result;
				}
				
			}
			else
				throw new jsException("No searchid present in sphnix search cache");
				
			return $res;	
			
		}
		catch(PDOException $e)
		{
			/*** echo the sql statement and error message ***/
			throw new jsException($e);
		}
	}
	public function getSR_CACHE($searchid,$sphinxJCACHE,$Sort)
	{
		try
		{
			$res=null;
			if($searchid)
			{
				$sql="SELECT RESULTS FROM newjs.SPHINX_SEARCHRESULTS_CACHE WHERE SID=:SEARCHID AND PAGE_START_ID=:sphinxJCACHE AND SORT_TYPE=:SORT";
				$prep=$this->db->prepare($sql);
				$prep->bindValue(":SEARCHID",$searchid,PDO::PARAM_INT);
				$prep->bindValue(":sphinxJCACHE",$sphinxJCACHE,PDO::PARAM_INT);
				$prep->bindValue(":SORT",$Sort,PDO::PARAM_STR);
				$prep->execute();
				if($result = $prep->fetch(PDO::FETCH_ASSOC))
				{
					$res=$result;
				}
				
			}
			else
				throw new jsException("No searchid present in sphnix search cache");
				
			return $res;
			
		}
		catch(PDOException $e)
		{
			/*** echo the sql statement and error message ***/
			throw new jsException($e);
		}
	} 
		
		
}
?>
