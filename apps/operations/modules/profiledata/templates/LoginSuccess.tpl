
<link rel="stylesheet" href="~sfConfig::get('app_img_url')`/jsadmin/jeevansathi.css" type="text/css">
<link rel="stylesheet" href="~sfConfig::get('app_img_url')`/profile/images/styles.css" type="text/css">

<form action="~$moduleurl`/search" method="post">
<input type="hidden" name=cid value="~$cid`">
~include_partial('global/header')`
<br>
<table width="50%" border="0" cellspacing="1" cellpadding="2" align="center">
<tr class="fieldsnew">
	
	<td class="formhead" valign="middle" colspan="2" align="center" >Login Data</td>
	</tr>
<br>
	<TR class="formhead" valign="middle" colspan="2" align="center"><TH>IP Address</TH><TH>Time (IST)</TH>
	~foreach $loginArr as $kk`

	<TR>
	~foreach $kk as $i`
	<TD class="label" valign="middle" colspan="1" align="center" >~$i`</TD>

	~/foreach`
	</TR>
	~/foreach`
</table>
~if !$sf_request->getParameter("actiontocall")`
<table width="50%" border="0" cellspacing="1" cellpadding="2" align="center">
<tr class="fieldsnew">
	
	<td  valign="middle" colspan="11" align="right" ><a href="~$moduleurl`/linkPage?profileid1=~$profileid`">go to back</a></td>
	</tr>
</table>
~/if`

~include_partial('global/footer')`

