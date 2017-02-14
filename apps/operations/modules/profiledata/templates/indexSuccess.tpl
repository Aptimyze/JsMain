
<link rel="stylesheet" href="~sfConfig::get('app_img_url')`/jsadmin/jeevansathi.css" type="text/css">
<link rel="stylesheet" href="~sfConfig::get('app_img_url')`/profile/images/styles.css" type="text/css">
<script language="JavaScript">
<!--
function sure()
{
        return confirm("Are you sure to change the membership of PAID user?");
}

function MM_openBrWindow(theURL,winName,features)
{
        window.open(theURL,winName,features);
}
function loadForm()
{
        document.form1.submit();
}                                                                                 

-->
</script>




~include_partial('global/header')`
<br><br>
<form action="~$moduleurl`/linkPage" method="post">
<input type="hidden" name=cid value="~$cid`">
<input type="hidden" name=username value="~$username`">
<table width="70%" border="0" cellspacing="1" cellpadding="4" align="center">
<tr class="fieldsnew">
	
	<td class="formhead" valign="middle" colspan="2" align="center" >Show Profile History</td>
	</tr>

    
          <tr>
            <td width="28%" class="label" bgcolor="#F9F9F9" style="font-size:14px" align="right">Enter User Name:
</td>

            <td width="30%" bgcolor="#F9F9F9">
              <input type="text" name="username" value="~if $username`~$username`~/if`" size="16" maxlength="40" class="textboxes1">
            </td>
            
             </tr>
             ~if $username`
             <tr valign="middle" align="center" >
	
              <td class="fieldsnew" colspan="2" style="width:50px;height:10px;color:red"><BR>~$error`</td></tr>~/if`
              <tr valign="middle" align="center" >
	
              <td class="fieldsnew" colspan="2"><input type="submit" name="Go" value="  Search  " class="textboxes1" style="width:70px;height:30px;background:green;color:white"></td></tr>
			</tr> 
			

          
	</table>
	</form>
<br>
</form>
<script>
function check_mes(ele,defaultmes)
{
	if(ele.value==defaultmes)
		ele.value="";
}
</script>
~include_partial('global/footer')`

