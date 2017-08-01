~if !$sf_request->getParameter("actiontocall")`
<!--var prof_checksum="~$sf_request->getAttribute('checksum')`";-->
<table width="760" border="0" cellspacing="0" cellpadding="2" ALIGN = "CENTER">
<tr>
<td><img src="~sfConfig::get('app_img_url')`/images/logo_1.gif" width="192" height="65"></td>
</tr>
<tr>
<td class=bigwhite bgcolor="#6BB97B"> &nbsp;
</td>
</tr>
</table>
~if $sf_request->getAttribute('name')` 
<table width="760" cellspacing="5" cellpadding='0' ALIGN = "CENTER">
<tr width=100% border=1>
<td width="33%" class="formhead" height="23" align="center"><b>Welcome ~$sf_request->getAttribute('name')` </b></td>
<td width="33%" class="formhead" height="23" align="center"><a onclick="goBrowserBack()" href="~sfConfig::get('app_site_url')`/jsadmin/mainpage.php">Click here to go to main page</a></td>
~if $showExclusiveServicingBack`
<td width="33%" class="formhead" height="23" align="center"><a onclick="goBrowserBack()" href="~sfConfig::get('app_site_url')`/operations.php/jsexclusive/menu">Go back to exclusive main menu</a></td>
~/if`
<td width="33%" class="formhead" align='CENTER' height="23"><a href="~sfConfig::get('app_site_url')`/jsadmin/logout.php">Logout</a></td>

</tr>
</table>
~/if`
~/if`

