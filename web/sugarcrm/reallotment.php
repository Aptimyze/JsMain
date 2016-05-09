<?php
include("connect.inc");
$db=connect_db();
$ts=time();
mysql_select_db('sugarcrm',$db);
mysql_query("set session wait_timeout=10000",$db);
$filename = "Inactive45Reassigned.csv";

                $fp = fopen($filename,"r");
                if(!$fp)
                {
                        die("no file pointer");
                }

                $whole_file=fread($fp,filesize($filename));

                $rows_arr=explode("\n",$whole_file);

                $rows_cnt=count($rows_arr);

                for($i=1;$i<$rows_cnt;$i++)
                {
                        $cols_arr=explode(",",$rows_arr[$i]);

                        $pid=$cols_arr[0];
                        $exe=$cols_arr[1];
                        $pid=str_replace("\"","",$pid);
                        $exe=str_replace("\"","",$exe);
			if($pid!='');
				$exe_arr[$exe][]="'".$pid."'";
                        unset($cols_arr);
                }
		foreach($exe_arr as $k=>$v)
		{
			$sql="SELECT id FROM users WHERE user_name='$k'";
			$res=mysql_query($sql,$db) or die(mysql_error1($sql,$db));
			while($row=mysql_fetch_array($res))
			{
				$lead_str=implode(",",$v);
				$user=$row['id'];
				$leads_updt="UPDATE leads as l,leads_cstm as lc SET l.converted=1 , l.status='10' , l.assigned_user_id='$user' WHERE l.id=lc.id_c and lc.username_c IN ($lead_str) and l.status<>'6' and l.deleted<>1";
				mysql_query($leads_updt,$db) or die(mysql_error1($leads_updt,$db));
				
			}
			
		}
function mysql_error1($sql,$db)
{
        echo $msg=$sql."\n".mysql_error($db);
        mail("neha.verma@jeevansathi.com,nehaverma.dce@gmail.com","Error in Reassigning script",$msg);
}

?>
