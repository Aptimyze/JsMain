<?php
//$k=svn_status("http://svntrac.infoedge.com/svn/jeevansathi/branches/SMJSM_2015_04_W2");

//$s=new Svn;

//$db=mysql_connect("devjs.infoedge.com","localuser","Km7Iv80l");
$conn=new MongoClient("mongodb://devjs.infoedge.com:27017");
$db=$conn->db->svn;
//$db->svnconfig->remove(array("_id"=>13));
if($_POST["add"])
{
	
	$path=$_POST[path];
	$username=$_POST[username];
	$password=$_POST[pass];
	$svninfo=`svn info $path --username=$username --password=$password --non-interactive --no-auth-cache`;
	if(strpos($svninfo,"Repository Root")===false)
	{
		die("Credentials not right");
	}
	$iter=$db->jiraauto->findAndModify(array(),array("\$inc"=>array("id"=>1)),array("_id"=>0));
	
	$incid=$iter[id]++;
	
	$db->svnconfig->insert(array("_id"=>$incid,"path"=>$path,"username"=>$username,"password"=>$password,"rev"=>0));
	
	//$sql="insert into jira.SVNCONFIG(path,username,password)values('$path','$username','$password')";
	//mysql_query($sql) or die(mysql_error());
	die("pass");
}
else if($_POST['remove'])
{	echo $ids=$_POST["remove"];
	$k=array("_id"=>intval($ids));
	
	$db->svnconfig->remove($k);
		die("pass");
}
