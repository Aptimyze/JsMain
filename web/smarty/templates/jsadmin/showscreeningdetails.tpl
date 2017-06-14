<!doctype html public "-//w3c//dtd html 4.0 transitional//en">

<html>
 <head>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <title>JeevanSathi</title>
  <link rel="stylesheet" href="jeevansathi.css" type="text/css">
  <link rel="stylesheet" href="../profile/images/styles.css" type="text/css">
 </head>
 ~include file="head.htm"`
 <br>
 <body bgcolor="#FFFFFF" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
  <table width=100% cellspacing="1" cellpadding='0' ALIGN="CENTER" >
   <tr width=100% border=1>
    <td width="3%" class="formhead" height="23">&nbsp;</td>
    <td width="42%" class="formhead" height="23"><font><b>Welcome :~$username`</b></font></td>
	<td width="20%" class="formhead" height="23">
	<a href="mis_aj.php">
		Back to MIS
	</a>
	</td>
    <td width="6%" class="formhead" align='RIGHT' height="23">
     <a href="logout.php?cid=~$cid`">
      Logout
     </a>
    </td>    
    <td width="3%" class="formhead" height="23">
     &nbsp;
    </td>
   </tr>
  </table>
  <table width=100% align="CENTER" >
   <tr align="CENTER">
    <td class="label" width=5% height="20"><b>S.No.</b></td>      
    <td class="label" width=5% height="20"><b>Profile id</b></td>
    <td class="label" width=15% height="20"><b>User</b></td>
    <td class="label" width=25% height="20"><b>Allot time</b></td>
    <td class="label" width=25% height="20"><b>Submitted time</b></td>
   </tr>            
	~section name=index loop=$user_arr` 
    <tr class="label" align="CENTER">    
     <td height="20" width="5%" align="center" >~$smarty.section.index.index_next`.</td>            
     <td height="20" width="5%" align="CENTER">~$user_arr[index].PROFILEID`</td>                                 
     <td class="label" width=15% height="20">~$user_arr[index].USERNAME`</td>
     <td class="label" width=25% height="20">~$user_arr[index].ALLOT_TIME`</td>
     <td class="label" width=25% height="20">~$user_arr[index].SUBMITED_TIME`</td>
    </tr>
   	~/section`      
   <tr bgcolor="#fbfbfb">
    <td colspan="14" height="21">
     &nbsp; 
    </td>
   </tr>
   <tr>
    <td colspan="7" height="21">
     &nbsp; 
    </td>
   </tr>    
  </table>
 <br><br><br><br><br><br><br>
 ~include file="foot.htm"`
 </body>
</html>
