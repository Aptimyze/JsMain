<?php
/************************
 Author @Esha************/
class IgnoreDetails
{
        private $ignoringId;
        private $ignoredId;
        public $ignoringGender;
        public $ignoredGender;

/*******************
fn: findProfileId
description: search for a user name in JPROFILE and sets the IgnoredId and IgnoredGender if found
input userName: obtained from message
param ignoredGender gender
param ignoredId profile id
return: 1 if the username exists in JPROFILE and 0 else
********************/
	public function findProfileId($userName)
	{
                $sql="SELECT PROFILEID,GENDER FROM newjs.JPROFILE WHERE USERNAME='$userName'";
                $res=mysql_query_decide($sql) or logError($sql);
		if($row=mysql_fetch_array($res))
		{
			$this->ignoredGender=$row["GENDER"];
			$this->ignoredId=$row["PROFILEID"];
			return 1;
		}
		else
			return 0;
	}
/*******************
fn: findPossibleIgnorer
description: search for all entries in CALLNOW for a particular profileid-ignoredId made within last 24hrs
Return $list: the profile ids list- callers and receivers both
********************/

	public function findPossibleIgnorer()
	{
		$back_date=mktime(date("H"),date("i"),date("s"),date("m"),date("d")-1,date("Y"));
		$yesterday=date("Y-m-d H:i:s",$back_date);
		$list=array();
		$i=0;
                $sql="SELECT RECEIVER_PID FROM newjs.CALLNOW WHERE CALLER_PID='$this->ignoredId' AND CALL_DT> '$yesterday'";
                $res=mysql_query_decide($sql) or logError($sql);
                while($row=mysql_fetch_array($res))
		{
			$list[$i]=$row["RECEIVER_PID"];
			$i++;
		}
                $sql="SELECT CALLER_PID FROM newjs.CALLNOW WHERE RECEIVER_PID='$this->ignoredId' AND CALL_DT> '$yesterday'";
                $res=mysql_query_decide($sql) or logError($sql);
                while($row=mysql_fetch_array($res))
		{
			$list[$i]=$row["CALLER_PID"];
			$i++;
		}
		return $list;
	}

        public function msgtrack($msg, $no)
        {
        $sql="INSERT INTO newjs.MSGTRACK(MSG,MOBILENO) VALUES ('$msg','$no')";
        mysql_query_decide($sql) or logError($sql);
        }

        public function insertInIgnoreProfile()
        {
                if($this->ignoringId && $this->ignoredId && $this->ignoringGender!=$this->ignoredGender)
                {
                        $sql_chk="SELECT count(*) as cnt FROM newjs.IGNORE_PROFILE WHERE PROFILEID='$this->ignoringId' and IGNORED_PROFILEID='$this->ignoredId'";
                        $result_chk=mysql_query_decide($sql_chk) or logError($sql_chk);
                        $row_chk=mysql_fetch_array($result_chk);
                        if(!$row_chk["cnt"])
                        {
                                //insert into IGNORE_PROFILE
                                $sql_insert="INSERT INTO newjs.IGNORE_PROFILE(PROFILEID,IGNORED_PROFILEID,DATE) VALUES ('$this->ignoringId','$this->ignoredId',now())";
                                mysql_query_decide($sql_insert) or logError($sql_insert);
                        }
                }
        }
/*********
fn:findGender
description:finds the gender of profileid
input: profiled id
return: gender
*********/

        public function findGender($id)
        {
                $sql="SELECT GENDER FROM newjs.JPROFILE WHERE PROFILEID='$id'";
                $res=mysql_query_decide($sql) or logError($sql);
                $row=mysql_fetch_array($res);
                return $row["GENDER"];
        }

/*******************
fn: searchInJprofile
description: search for a profile id in a list of profile ids having a particular mobile no and set the ignoringId and ignoringGender if the profileid is found
param $idLisst: list of profile ids
param mobile: mobile no
return:1 if the profile id is found else 0
********************/

        public function searchInJprofile($idList,$mobile)
        {
                $sql="SELECT PROFILEID,GENDER FROM newjs.JPROFILE WHERE PROFILEID IN ('$idList') AND PHONE_MOB IN('0$mobile','$mobile','+91$mobile','91$mobile')"; 
                $res=mysql_query_decide($sql) or logError($sql);
                if($row=mysql_fetch_array($res))
		{
		 	$this->ignoringId=$row["PROFILEID"];
			$this->ignoringGender=$row["GENDER"];
			return 1;
		}
		else
			return 0;
        }
/*******************
fn: searchInJprofileContacts
description: search, in Jprofile_contact, for a profile id in a list of profile ids having a particular mobile no and set the ignoringId and ignoringGender if the profileid is found
param $idLisst: list of profile ids
param mobile: mobile no
return:1 if the profile id is found else 0
********************/

        public function searchInJprofileContacts($idList,$mobile)
        {
                $sql="SELECT PROFILEID FROM newjs.JPROFILE_CONTACT WHERE PROFILEID IN ('$idList') AND ALT_MOBILE IN('0$mobile','$mobile','+91$mobile','91$mobile')";
                $res=mysql_query_decide($sql) or logError($sql);
                if($row=mysql_fetch_array($res))
		{
			$this->ignoringId=$row["PROFILEID"];
			$this->ignoringGender=$this->findGender($this->ignoringId);
			return 1;
		}
		else
			return 0;
        }
}
?>
