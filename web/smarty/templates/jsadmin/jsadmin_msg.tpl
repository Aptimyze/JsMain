<!doctype html public "-//w3c//dtd html 4.0 transitional//en">
<html>
 <head>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <title>JeevanSathi</title>
  <link rel="stylesheet" href="jeevansathi.css" type="text/css">
  <link rel="stylesheet" href="../profile/images/styles.css" type="text/css">
 </head>
~if $FLAG eq "addcaste"`
	~$HEAD`
~else`
 ~include file="head.htm"`
~/if`
 <body bgcolor="#FFFFFF" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
~if $cid`  
  <table width=760 cellspacing="1" cellpadding='0' ALIGN="CENTER" >
   <tr width=100% border=1>
    <td width=30% class="formhead" align='CENTER' height="23">
     <a href="../jsadmin/mainpage.php?cid=~$cid`">Click here to go to mainpage</a>
    </td>
    <td width=30% class="formhead" align='CENTER' height="23">
     <a href="../jsadmin/logout.php?cid=~$cid`">Logout</a>
    </td>
   </tr>
  </table>
~/if`
<br /><br /><br />
  <table width=760 align="CENTER" >
   <tr>
    <td height="23" class="formhead" align="center">
     ~$MSG`
    </td>
   </tr>      
  </table>
  <br><br><br><br><br><br><br><br><br><br><br>
~if $FLAG eq "addcaste"`
  ~$FOOT`
~/if`
 </body>
</html>
