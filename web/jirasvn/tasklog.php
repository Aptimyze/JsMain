<!DOCTYPE html>
<html lang="en">
 <head>
     <link rel="stylesheet" href="//aui-cdn.atlassian.com/aui-adg/5.4.3/css/aui.css" media="all">
     <script type = "text/javascript" src = "https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
     <script src="//nikhil:2990/jira/atlassian-connect/all.js" type="text/javascript"></script>
     <!--script src="//nikhil:2990/jira/atlassian-connect/all.js" type="text/javascript"></script-->
     <script src="all.js"></script>
     <style type="text/css">
.mainbrdr {
	border:1px solid #f0f0f0
}
.fullwid {
	width:100%
}
.brdr0 {
	border:0
}
.bg1 {
	background-color:#f0f0f0;
}
.txtl {
	text-align:left;
}
.pad1 {
	padding:8px
}
.colr1 {
	color:#03C
}
.green{color:green}
.blue{color:blue}
.red{color:red}
.dn{display:none}
</style>

 </head>
 
 <body>
<table cellspacing="0" cellpadding="0" class="mainbrdr fullwid" style='font-size:12px'>
  <tbody><tr>
    <td class="fullwid"><table class="fullwid brdr0" cellspacing="0" cellpadding="0">
        <tbody><tr class="bg1">
          <th scope="col" class="pad1 txtl dn">Repository</th>
          <th scope="col" class="pad1 txtl">Revisions</th>
          <th scope="col" class="pad1 txtl">Date</th>
          <th scope="col" class="pad1 txtl">User</th>
          <th scope="col" class="pad1 txtl">Message</th>
        </tr>
<?php

$conn=new MongoClient("mongodb://devjs.infoedge.com:27017");
$db=$conn->db->svn;
$jid=$_GET['issue_key'];
//$jid="JSI-100";
updateRevisionTable();

$iter=$db->svnconfig->find();
foreach($iter as $row)
{
	$servers[$row[_id]]=$row[path];
}

$arr=array("jid"=>$jid);
$iters=$db->svnlog->find($arr);


foreach($iters as $row)
{
	
$filelist=explode("\n",$row[filelist]);
unset($arr);
foreach($filelist as $key=>$val)
{
	if($val)
	{
		$val=str_replace("A ","<span class='green'>A ",$val);
		$val=str_replace("M ","<span class='blue'>M ",$val);
		$val=str_replace("D ","<span class='red'>D ",$val);
		$val=$val."</span>";
		$arr[]=$val;
	}	
}
$filelist=implode("<BR>",$arr);
?>
 <tr>
          <td class="pad1 txtl dn"><?=$servers[$row[rid]];?></td>
          <td class="pad1 txtl colr1"><a href='http://xmppdev.jeevansathi.com/websvn/revision.php?repname=Jeevansathi&rev=<?=$row[revision];?>&peg=<?=$row[revision];?>'><?=$row[revision];?></a></td>
          <td class="pad1 txtl"><?=$row[date];?></td>
          <td class="pad1 txtl"><?=$row[user];?></td>
          <td class="pad1 txtl"><table>
              <tbody>
				  <tr>
                <td><?=$row[messages];?></td>
              </tr>
            </tbody></table>
            <table class="fullwid">
              <tbody><tr class="bg1">
                <th scope="col" class="pad1 txtl">Files Changed</th>
              </tr>
              <tr>
                <td class="pad1 txtl"><?=$filelist;?></td>
              </tr>
            </tbody></table></td>
        </tr>
        <tr><TD style="border:1px solid rgb(240, 240, 240);height:18px" colspan=5></TD></tr>
<?php	
}
?>
 
</tbody></table>


</td>
</tr>
</tbody></table>
 </body>
 
</html>
<?php
function updateRevisionTable()
{

	global $db;
	$iter=$db->svnconfig->find();
	foreach($iter as $row)
	{
		$id=$row[_id];
		$rev=$row[rev];
		$path=$row[path];
		$usern=$row[username];
		$pass=$row[password];
		$svncom="svn log -r $rev:HEAD $path --username=$usern --password=$pass --non-interactive --no-auth-cache";
		$svnresult=`$svncom`;
		$revArr=explode("------------------------------------------------------------------------",$svnresult);
		foreach($revArr as $key=>$val)
		{
			if($val && $val[0])
			{
				$arr=explode(" | ",$val);
				
				$revision=str_replace("r","",$arr[0]);
				$revision=str_replace("\n","",$revision);
				$user=$arr[1];
				$time=explode(" ",$arr[2])[0]." ".explode(" ",$arr[2])[1];
				$msg=str_replace("1 line" ,"",$arr[3]);
				unset($match);
				unset($logMe);
				preg_match_all("/([A-Z]{3}-\d+)/",$msg,$match);
				
				if(count($match[0])>0)
				{
					$logCommit=" svn log -q --verbose -r$revision $path --username=$usern --password=$pass --non-interactive --no-auth-cache";
					$logMe=`$logCommit`;
					preg_match_all("/Changed paths:((\n.*){1,100})/",$logMe,$fileLog);
					$fileslist=$fileLog[1][0]?$fileLog[1][0]:"";
					foreach($match[0] as $k_key=>$v_val)
					{
						$msg=addslashes($msg);
						$fileslist=addslashes($fileslist);
						$fileslist=str_replace("------------------------------------------------------------------------
","",$fileslist);
						$db->svnlog->insert(array("rid"=>$id,"jid"=>$v_val,"revision"=>$revision,"date"=>$time,"user"=>$user, "message"=>$msg,"filelist"=>$fileslist));

					}
				}
			}
		}
		$svnHead=`svn info -rHEAD $path --username=$usern --password=$pass --non-interactive --no-auth-cache  | grep Revision | cut -d' ' -f2`;
		
		$db->svnconfig->update(array("_id"=>$id),array("\$set"=>array("rev"=>intval($svnHead))));

		
	}
}
