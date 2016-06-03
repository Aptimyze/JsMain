<html>
<head>

<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>Feedback Details : Jeevansathi.com Matrimonial Services</title>
<link rel="stylesheet" href="jeevansathi.css" type="text/css">
<link rel="stylesheet" href="/profile/images/styles.css" type="text/css">
</head>
~include file="head.htm"`

<body bgcolor="#FFFFFF" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">

<table width=100% cellspacing="1" cellpadding='3' ALIGN="CENTER" >
    <tr width=100% class="formhead">
          <td width=55% class="formhead" border=1><font><b>Welcome</b></font></td>
          <td width="15%" class="formhead" align="center">&nbsp;</td>
          <td width=10% class="formhead" border=1 align='CENTER'>&nbsp;</td>
    </tr>
</table>
<br><br>
 <form action="feedback_check.php" method="post">
  <table width=100% align="CENTER" class="fieldsnew">
    <tr align="CENTER">
      <td class="formhead" colspan="7" height="23"><b><font size="3" color="blue"> Feedback Details </font></b></td>
    </tr>
    <tr align="CENTER">
      <td class="fieldsnew" width=5% height="20">&nbsp;</td>
      <td colspan=5 class="fieldsnew" align="LEFT"><font color="blue">Total Records ~$NUM`</font></td>	
    </tr>
    <tr align="CENTER">
      <td class="label" width=5% height="20"><b>S.No.</b></td>
      <td class="label" width="15" height="20"><b>Name</b></td>
      <td class="label" width=20% height="21"><b>Address</b></td>
      <td class="label" width=15% height="21"><b>Email</b></td>
      <td class="label" width=15 height="21"><b>Date</b></td>      
      <td class="label" width=10% height="21"><b>Reply</b></td>      
      <td class="label" width=10% height="21"><b>Discard</b></td>      

      </tr>
    ~section name=index loop=$ROW`
    <tr align="CENTER" bgcolor="#fbfbfb" ~if $ROW[index].ABUSE eq 'Y'` class="red" ~/if`>
      <td height="20" align="CENTER" width="5%">~$ROW[index].SNO`</td>
      <td height="20" width="15%">~$ROW[index].NAME`</td>
      <td height="21" width="20%">~$ROW[index].ADDRESS`</td>
      <td height="21" width="15%" align="LEFT">~$ROW[index].EMAIL`</td>
      <td height="21" width="15%" >~$ROW[index].DATE`</td>
      <td width=10% height="21"><a href="replyfeedback.php?email=~$ROW[index].EMAIL`&id=~$ROW[index].ID`">reply</a></td>      
      <td height="21" width="10%" ><input type=checkbox name=cb~$ROW[index].ID`></td>
    </tr>
    <tr bgcolor="#fgfgfg" >
      <td height="21" align="CENTER" width="5%">&nbsp;</td>
      <td width=20% height="21"><b>Comments</b></td>
      <td height="21" colspan="5">~$ROW[index].COMMENTS`</td>
    </tr>
	<tr class="label">
      <td height="21" align="CENTER" width="5%" colspan=7>&nbsp;</td>
	</tr>
    ~/section`
    <tr bgcolor="#fbfbfb">
      <td colspan="3" height="21">&nbsp</td>
      <td colspan="" height="21"><input type=submit name=Submit value="Discard"></td>
   </tr>
  </table>

 </form>
 

 <br><br><br><br><br><br>
~include file="foot.htm"`


</body>
</html>
