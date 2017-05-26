<html>
 <head>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <title>JeevanSathi</title>
  <link rel="stylesheet" href="~sfConfig::get('app_img_url')`/jsadmin/jeevansathi.css" type="text/css">
  <link rel="stylesheet" href="~sfConfig::get('app_img_url')`/profile/images/styles.css" type="text/css">
<script language="javascript">
<!--
function MM_openBrWindow(theURL,winName,features)
{
        window.open(theURL,winName,features);
}
function go()
{
        document.getElementById("sub").style.display="none";
        document.getElementById("process").style.display="block";       
}
//-->
</script>
 </head>
 <br>
 <body bgcolor="#FFFFFF" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
  <table width=760 cellspacing="1" cellpadding='0' ALIGN="CENTER" >
   <tr width=100% border=1>
    <td width=30% class="formhead" height="23" align="center"><font><b>Welcome :~$names`</b></font></td>
    <td width=30% class="formhead" align='CENTER' height="23">
     <a href="/jsadmin/mainpage.php?cid=~$cid`">Click here to go to mainpage</a>
    </td>
    <td width=30% class="formhead" align='CENTER' height="23">
     <a href="/jsadmin/logout.php?cid=~$cid`">Logout</a>
    </td>
   </tr>
  </table>
        <form name="form1" action="NoLongerWorking?cid=~$cid`" method="post">
        ~if $msg eq ''`
        <table width=760 align="center">
                <tr class="formhead">
                        <td align="center" width="33%">&nbsp;Executive Name</td>
                </tr>
                <tr>
                        <td align="center" width="67%"><input type="text" name="gone_user" id="gone_user" value=""></td>
                </tr>
                <tr align="CENTER" class="fieldsnew" id="sub" style="display:block;">
                      <td colspan="2"><input type="submit" name="Submit" value="Release" onclick="go();">&nbsp;&nbsp;&nbsp;
                </tr>
                <tr align="CENTER" class="fieldsnew" id="process" style="display:none;">
                      <td colspan="2">Releasing please wait...&nbsp;&nbsp;&nbsp;
                </tr>
        </table>
        ~else`<p align="center">~$msg`</p>~/if`
        </form>

  <br><br><br><br>
</body>
</html>

