<?php
function update_table_master($mypid,$profileid,$table_name,$FIELD1,$FIELD2,$updateDb,$updateY='')
{
	if($table_name=="newjs.CONTACTS")
	{
		global $run_on;
		if($mypid && $profileid)
		{	
			$sql="UPDATE $table_name SET SEEN='Y' WHERE $FIELD2 in($mypid,$profileid) AND $FIELD1 in($profileid,$mypid)";
			mysql_query($sql,$updateDb);
			return mysql_affected_rows($updateDb);
		}		
	}
	elseif($table_name=="userplane.CHAT_REQUESTS" || $table_name=="jsadmin.OFFLINE_MATCHES" || $table_name=="jsadmin.VIEW_CONTACTS_LOG")
	{
		if($table_name=="jsadmin.VIEW_CONTACTS_LOG")
			$sql="select count(*) from $table_name WHERE $FIELD1=$profileid AND $FIELD2=$mypid AND SOURCE='".CONTACT_ELEMENTS::CALL_DIRECTLY_TRACKING."'";
		else
			$sql="select count(*) from $table_name WHERE $FIELD1=$profileid AND $FIELD2=$mypid";
		$res=mysql_query($sql,$updateDb);

		$countrow=mysql_fetch_row($res);
		if($countrow[0]>0)
		{
			$sql="UPDATE $table_name SET SEEN='Y' WHERE $FIELD1=$profileid AND $FIELD2=$mypid";
			mysql_query($sql,$updateDb);
	                return mysql_affected_rows($updateDb);
		}
		else
			return 0;
	}
	else
	{	
		if($updateY)
			$sql="UPDATE $table_name SET $updateY='Y' WHERE $FIELD1=$profileid AND $FIELD2=$mypid AND $updateY='U'";
		else
			$sql="UPDATE $table_name SET SEEN='Y' WHERE $FIELD1=$profileid AND $FIELD2=$mypid";
		
		// IVR-Callnow table update call status
		if($table_name=='newjs.CALLNOW')			 
			$sql .=" AND (CALL_STATUS='R' OR CALL_STATUS='M')";

//		echo $sql."<BR>";
		mysql_query($sql,$updateDb);
		return mysql_affected_rows($updateDb);
		//echo "<br>";
		//echo "<br>";
		//echo "\n".$sql;
	}
}
