~include_partial('global/header')`

<body bgcolor="#FFFFFF" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">

~if $source eq NULL || $source eq 'master'`
	<form action="~sfConfig::get('app_site_url')`/jsadmin/mainpage.php?cid=~$cid`" method="post">
~elseif $interface eq ProfilePicturesTypeEnum::$INTERFACE["2"]`
	<form action= "~JsConstants::$siteUrl`/operations.php/photoScreening/processInterface?cid=~$cid`&source=~$source`" method="post">

~else`
	<form action="~JsConstants::$siteUrl`/operations.php/photoScreening/screen?cid=~$cid`&source=~$source`" method="post">
~/if`

<input type="hidden" name=cid value="~$cid`">
<br><br>
<table width="50%" border="0" cellspacing="1" cellpadding="4" align="center">
	  <tr>
	~if $source eq NULL || $source eq 'master'`
	    <td colspan=2 bgcolor="#F9F9F9" class="label" align="center"><font color="red"><b>You have successfully skipped a profile.</b></font></td>						
	~else`
	    <td colspan=2 bgcolor="#F9F9F9" class="label" align="center"><font color="red"><b>You have successfully skipped a profile. Click on Submit to continue.</b></font></td>						
	~/if`
	  </tr>	
          <tr valign="middle" align="Right">
	~if $source neq NULL && $source neq 'master'`
              <td colspan="2"><input type="submit" name="Submit" value="  Submit  " class="textboxes1"></td></tr>
	~/if`
        </table>
</form>
</body>
~include_partial('global/footer')`
