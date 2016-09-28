

<link rel="stylesheet" href="~sfConfig::get('app_img_url')`/jsadmin/jeevansathi.css" type="text/css">
<link rel="stylesheet" href="~sfConfig::get('app_img_url')`/profile/images/styles.css" type="text/css">



<form action="~$moduleurl`/search" method="post">
<input type="hidden" name=cid value="~$cid`">
~include_partial('global/header')`
<br>


	~if $modArr`
	~foreach from=$modArr key=kk item=vv`
	<table width="50%" border="0" cellspacing="1" cellpadding="2" align="center">
	<tr class="fieldsnew">
	<td class="formhead" valign="middle" colspan="3" align="center" >Modification Log : ~$kk`  </td>
	</tr>
<br>
	<TR class="formhead" valign="middle" colspan="3" align="center"><TH>~$kk`</TH><TH>Mod_Date (IST)</TH><TH>IP</TH>
</tr>
	~foreach $vv as $ii`
	<TR>
	~foreach $ii as $i`
	<TD class="label" valign="middle" colspan="1" align="center" >~$i`</TD>
~/foreach`
	
	</TR>
	~/foreach`
	</table><BR></BR>
	~/foreach`
	
	~/if`
	
~if !$sf_request->getParameter("actiontocall")`
 <table width="50%" border="0" cellspacing="1" cellpadding="2" align="center">
<tr class="fieldsnew">
	
	<td  valign="middle" colspan="11" align="right" ><a href="~$moduleurl`/linkPage?cid=~$cid`&profileid1=~$profileid`">go to back</a></td>
	</tr>
 </table>
 ~/if`

~include_partial('global/footer')`

