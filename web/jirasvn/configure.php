<!DOCTYPE html>
<html lang="en">
 <head>
     <link rel="stylesheet" href="//aui-cdn.atlassian.com/aui-adg/5.4.3/css/aui.css" media="all">
     <script type = "text/javascript" src = "https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
     <!--script src="//nikhil:2990/jira/atlassian-connect/all.js" type="text/javascript"></script-->
     <script src="all.js"></script>
 </head>
 <body>
	 <table width="100%" border="0" cellspacing="0" cellpadding="0" style="border:1px solid #f0f0f0">
<tbody><tr>
<td width="100%">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tbody><tr style="background-color:#f0f0f0;">
    <th scope="col" style="text-align:left; padding:8px">Repository Path</th>
    <th scope="col" style="text-align:left; padding:8px">Username</th>
    <th scope="col" style="text-align:left; padding:8px">Password</th>
    <th scope="col" style="text-align:left; padding:8px">Action</th>
    
  </tr>
<?php
$conn=new MongoClient("mongodb://devjs.infoedge.com:27017");
$db=$conn->db->svn;
//$db=mysql_connect("devjs.infoedge.com","localuser","Km7Iv80l");

$iter=$db->svnconfig->find()->sort(array("id"=>1));
//$res=mysql_query($sql) or die(mysql_error());
foreach($iter as $key=>$val)
//while($row=mysql_fetch_assoc($res))
{
	
	$row=$val;
?>
 <tr>
    <td style="text-align:left; padding:8px"><?=$row[path];?></td>
    <td style="text-align:left; padding:8px; color:#03C"><?=$row[username];?></td>
    <td style="text-align:left; padding:8px">*****</td>
    
    <td style="text-align:left; padding:8px"><input type="button" name='remove_svnpath' value='Remove' id="<?=$row[_id]?>" ></td>
  </tr>
<?php	
}
?>
 <tr>
    <td style="text-align:left; padding:8px"><input type="text" placeholder="Svn Repository Path" name="svnpath" value="" id="svnpath"></td>
    <td style="text-align:left; padding:8px; color:#03C"><input type="text" placeholder="Svn username" name="svnusername" value="" id="svnusername"></td>
    <td style="text-align:left; padding:8px"><input type="password" placeholder="Svn password" name="svnpassword" value="" id="svnpassword"></td>
    <td style="text-align:left; padding:8px"><input type="button" name='Add' value='Add' id="add"></td>
  </tr>
</tbody></table>


</td>
</tr>
</tbody></table>
 </body>
 <script>
 $("#add").bind("click",function(ev){
	 $("input[type='text']").attr("disabled",true);
	 var path=$("#svnpath").val();
	 var username=$("#svnusername").val();
	 var pass=$("#svnpassword").val();
	 $.ajax({
		 method: "POST",
  url: "/jirasvn/svnsubmit_master.php",
  data: { 
	  path:path,username:username,pass:pass,add:1
	  }
	}).done(function(msg){
		if(msg=="pass"){
			window.location.reload();
		}
		else 
		{
			alert(msg); 
			$("input[type='text']").removeAttr("disabled");
		}
	});
 });
 $("input[name='remove_svnpath']").bind("click",function(){
	 var val=$(this).attr("id");
	 if(val)
	 {	
	 $.ajax(
	 {method:"POST",data:{remove:val},url:"/jirasvn/svnsubmit_master.php" }).done(function(msg){window.location.reload();});
	 }
 });
 </script>
</html>
