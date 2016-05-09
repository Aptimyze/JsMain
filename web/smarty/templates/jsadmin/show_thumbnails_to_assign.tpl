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
  
  <table width=760 cellspacing="1" cellpadding='0' ALIGN="CENTER" >
   <tr width=100% border=1>
    <td width="3%" class="formhead" height="23">&nbsp;</td>
    <td width="62%" class="formhead" height="23"><font><b>Welcome :~$name`</b></font></td>
    <td width="15%" class="formhead" align='RIGHT' height="23">
     <a href="check_thumbnail.php?username=~$name`&cid=~$cid`">Check Thumbnail</a>
    </td>
    <td width="6%" class="formhead" align='RIGHT' height="23">
     <a href="logout.php?cid=~$cid`">Logout</a>
    </td>
    <td width="3%" class="formhead" height="23">&nbsp;</td>
  </tr>
 </table>

 <form method="post" action="assign_thumbnails.php">
  <table width=100% align="CENTER" >
   <tr align="CENTER">
    <td class="formhead" colspan="7" height="23">
     <b>
      Thumbnails to Be Assigned 
     </b>
    </td>
   </tr>
  	<tr>
	<td width="50%"><table width=100% align="CENTER" >
	   <tr >
	    <td height="20"><b>Profiles to be assigned : </b>~$count_to_be_screened`</td>
	   </tr>
	   <tr >
	    <td height="20"><b>Profiles under screening : </b>~$count_under_screening`</td>
	   </tr></table>
	</td>
   	<td><table width=100% align="CENTER" >
	   <tr align="CENTER">
	    <td width=25% height="20"><b>Operator</b></td>
	    <td width=25% height="20"><b>Profiles being screened by this operator</b></td>
	   </tr>

	   ~section name=index loop=$photo_operators`
	    <tr align="CENTER">
	    <td width=25% height="20">~$photo_operators[index]`</td>
	    <td width=25% height="20">~$photo_operator_total[index]`</td>	
	    </tr>
	   ~/section`
	</table>
	</td>
    </tr>
    <tr>
	   <td colspan="2">&nbsp;</td>
    </tr>
    <tr>
	   <td>
	   <select name="operator">
		<option selected value="">List of operators</option>		
		~section name=index loop=$photo_operators`        						     	
	       	<option>~$photo_operators[index]`</option>	
	      	~/section`
	   </select>&nbsp;&nbsp;
   	   No. of profiles to assign <input type="text" name="assign_num">
   	   </td>
   <td>
     <input type=submit name="assign" value="Assign">
     <input type="hidden" name="count_to_be_screened" value="~$count_to_be_screened`">
     <input type=hidden name="cid" value="~$cid`">
     <input type=hidden name="adminname" value="~$name`">          
    </td>
   </tr>   
  </table>
 </form>
 <br><br><br><br><br><br><br> 
 ~include file="foot.htm"`
 </body>
</html>
