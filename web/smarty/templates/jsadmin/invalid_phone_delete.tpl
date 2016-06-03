<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>UserView : Jeevansathi.com Matrimonial Services</title>
<link rel="stylesheet" href="jeevansathi.css" type="text/css">
<link rel="stylesheet" href="../profile/images/styles.css" type="text/css">
</head>
~include file="head.htm"`

<body bgcolor="#FFFFFF" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">

<table width=100% cellspacing="1" cellpadding='3' ALIGN="CENTER" >
    <tr width=100% class="formhead">
          <td width=30% class="formhead" border=1><font><b>Welcome : ~$user`</b></font></td>
          <td width=25% class="formhead" border=1 align='CENTER'><a href="mainpage.php?cid=~$cid`">Click here to go to main page</a></td>
          <td width="15%" class="formhead" align="center">
          <td width=10% class="formhead" border=1 align='CENTER'><a href="logout.php?cid=~$cid`">Logout</a></td>
    </tr>
</table>
<br>
<br>

<!-- Form Starts -->
<form action="invalid_phone_status.php" method="post" >
<input type="hidden" name=cid value="~$cid`">
<input type="hidden" name=user value="~$user`">
<input type="hidden" name="pids" value="~$pids`">
  ~section name=index loop=$pids`
  	<input type="hidden" name="pids[]" value="~$pids[index]`">
  ~/section`		
  <table width=70% align="CENTER" >

	~if $MSGVer neq ''`
	    <tr align="CENTER">
	        <td class="formhead" colspan="7" height="23"><b><font size="2" color="blue">~$MSGVer` </font></b></td>
	    </tr>
	~/if`

    <tr align="CENTER">
      <td class="formhead" colspan="7" height="23"><b><font size="4" color="blue">Mark Invalid/ Delete selected Invalid profiles  </font></b></td>
    </tr>

    <table width=70% align="CENTER" >
        <tr>
        <td height="23" class="formhead" align="center" width=50%>
        Action Type:
        </td>
        <td height="23" class="formhead" align="left">
         <input name="reason" type="radio" value="invalid" checked> Invalid Phone</br>
         <input name="reason" type="radio" value="delete" > Delete Profile
        </td>
    </table>
    <table width=70% align="CENTER" >
        <tr>
        <td height="23" class="formhead" align="center" width=50%>
        Comments:
        </td>
        <td height="23" class="formhead" align="left">
        <textarea name="comments" class="testbox" cols="40" rows="2"></textarea>
        </td>
        </tr>
        <tr>
    </table>

    <table width=70% align="CENTER" >
        <tr>
                <td height="23" class="formhead" align="center" colspan=2 width="100%" >
	        Are you sure you want to mark selected profiles for deletion/Invalid?
                </td>
        </tr>
        <tr>
	        <td height="23" class="formhead" align="right">
	        <input type="submit" name="submit" value="Cancel">
		<td height="23" class="formhead" align="left">
		<input type="submit" name="confirm" value="Confirm">
        	</td>
        </tr>
        </form>

    	<tr bgcolor="#fbfbfb">
    		  <td colspan="7" height="21">&nbsp; </td>
   	</tr>
   	<tr>
   		  <td colspan="7" height="21">&nbsp; </td>
   	</tr>
   	<tr>
      		<td width="8%" height="21">&nbsp; </td>
      		<td height="21" width="11%" align="center"></td>
	</tr>
    </table>
  </table>	
    <br><br>
~include file="foot.htm"`
</body>
</html>
