~include_partial('global/header')`

<body bgcolor="#FFFFFF" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<form action="~sfConfig::get('app_site_url')`/operations.php/photoScreening/skipProfile?cid=~$cid`" method="post">

<!-- carry on variables for screening-->
<input type="hidden" name=cid value="~$cid`">
<input type="hidden" name="mailid" value="~$mailid`">
<input type="hidden" name="profileid" value="~$profileid`">
<input type="hidden" name="comp" value="~$comp`">
<input type="hidden" name="mail" value="~$mail`">
<input type="hidden" name="source" value="~$source`">
<input type="hidden" name="interface" value="~$interface`">
<!-- carry on variables for screening-->

<br><br>
<table width="50%" border="0" cellspacing="1" cellpadding="4" align="center">
	~if $MSG`
	  <tr>
	    <td colspan=2 bgcolor="#F9F9F9" class="label" align="center"><font color="red">~$MSG`</font></td>						
	  </tr>	
	~/if`
          <tr>
            <td width="30%" class="label" bgcolor="#F9F9F9">Comments</td>

            <td width="70%" bgcolor="#F9F9F9">
        <textarea name="comments" id="comm" class="testbox" cols="40" rows="2" value=''></textarea>    
            </td>
          </tr>
          <tr valign="middle" align="Right">
              <td colspan="2"><input type="submit" onclick="return checkComment();" name="Skip" value="  Skip  " class="textboxes1"></td></tr>
        </table>
</form>
</body>
<script>
function checkComment()
{
	if(document.getElementById("comm").value == '')
	{
		alert("Please fill the reason for skipping the profile to continue to the next page");
		return false;
	}
	return true;
}
</script>
~include_partial('global/footer')`
