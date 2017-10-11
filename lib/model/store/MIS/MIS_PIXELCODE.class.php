<?php
//This class is used to execute queries on MIS.PIXELCODE table
class MIS_PIXELCODE extends TABLE {
    public function __construct($dbname = '') {
        parent::__construct($dbname);
    }
    public function getPixelcode($groupname) {
        if (!$groupname) return;
        try {
            $sql = "SELECT SQL_CACHE PIXELCODE FROM MIS.PIXELCODE WHERE GROUPNAME=:GROUP AND PAGEID=''";
            $res = $this->db->prepare($sql);
            $res->bindValue(":GROUP", $groupname, PDO::PARAM_STR);
            $res->execute();
            $result = $res->fetch(PDO::FETCH_ASSOC);
            return $result[PIXELCODE];
        }
        catch(PDOException $e) {
            throw new jsException($e);
        }
    }
    public function getPixelCodes($groupname='',$page='')
    {
        if (!$groupname) return;
        try {
            $sql = "SELECT SQL_CACHE * FROM MIS.PIXELCODE WHERE ";
	    if($groupname)
		$sql.=" GROUPNAME=:GROUP ";
	    if($groupname && $page)
		$sql.=" AND ";
	    if($page)
		$sql.= " PAGEID like :PAGEID ";
	    $sql.=" AND STATUS=:STATUS";
            $res = $this->db->prepare($sql);
	    if($groupname)
		    $res->bindValue(":GROUP", $groupname, PDO::PARAM_STR);
	    if($page)
		    $res->bindValue(":PAGEID","%". $page."%", PDO::PARAM_STR);
	    $res->bindValue(":STATUS", "Y", PDO::PARAM_STR);
            $res->execute();
	    while($ress=$res->fetch(PDO::FETCH_ASSOC))
	      $result[] = $ress;
            return $result;
        }
        catch(PDOException $e) {
            throw new jsException($e);
        }
    }
    public function insertPixelCodes($pixelcode)
    {
        if (!$pixelcode['GROUPNAME']&&!$pixelcode['PAGEID']) return;
	try {
		$keyArr = array_keys($pixelcode);
		$sqlKeyArr = array("GROUPNAME","PAGEID","CONDITION","REPLACEMENT","PIXELCODE","STATUS");
		foreach($sqlKeyArr as $k=>$v)
		{
			if($pixelcode[$v])
			$keysPresent[]=$v;
		}
		$sqlStr=implode(",",$keysPresent);
		$sqlStrBind=":".implode(",:",$keysPresent);
	    $sql = "INSERT INTO  `PIXELCODE` (  ".$sqlStr." ) VALUES ( ".$sqlStrBind.");";
            $res = $this->db->prepare($sql);
	    foreach($keysPresent as $k=>$v)
		    $res->bindValue(":".$v, $pixelcode[$v], PDO::PARAM_STR);
            $res->execute();
	}
        catch(PDOException $e) {
            throw new jsException($e);
        }
	
    }
}
?>
