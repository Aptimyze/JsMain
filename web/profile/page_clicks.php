<?php
/**
*       Filename        :       mainmenu.php
*       Description     :
*       Created by      :
*       Changed by      :
*       Changed on      :
        Changes         :       New Service added called Eclassified , changes done due to it.
**/
	
	include("connect.inc");
	$db=connect_db();
	$arr=unserialize($memcache->get('PAGE_VIEWS'));
	if(is_array($arr))
	{
		foreach($arr as $profileid=>$val)
		{
			$count=$arr[$profileid]["count"];
              
                	$sql="update PAGE_VIEWS  set TOTAL_COUNT=TOTAL_COUNT+$count where PROFILEID='$profileid'";
	                $res=mysql_query_decide($sql)  or die(mysql_error_js());//logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
			
                	if(mysql_affected_rows_js()<=0)
	                {
                	        $sql="insert ignore into PAGE_VIEWS(PROFILEID,TOTAL_COUNT) values('$profileid',$count)";
        	                mysql_query_decide($sql)  or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");

                	}
		}
	}
	$memcache->delete('PAGE_VIEWS');
					
