
<link rel="stylesheet" href="~sfConfig::get('app_img_url')`/jsadmin/jeevansathi.css" type="text/css">
<link rel="stylesheet" href="~sfConfig::get('app_img_url')`/profile/images/styles.css" type="text/css">

<form action="~$moduleurl`/search" method="post">
<input type="hidden" name=cid value="~$cid`">
~include_partial('global/header')`

<table width="80%" border="0" cellspacing="1" cellpadding="2" align="center">
<tr class="fieldsnew">
	
	<td class="formhead" valign="middle" colspan="6" align="center" >Contact History  </td>
	</tr>
<br>
	<TR class="formhead" valign="middle" colspan="6" align="center"><TH>Sender</TH><TH>Receiver</TH><TH>Date (IST)</TH><TH>TYPE</TH><TH>IP</TH><TH style="width:40%">MESSAGE</TH>
	<TR><TD style="height:20px"> </TD></TR>
	~if !$EOIArr`
	<tr><TD class="label" colspan=6 style="color:red">No records found
	
	</td></tr>
	~/if`
	~foreach $EOIArr as $ii`
	~foreach from=$ii key=vv item=kk`
	
	~foreach $kk as $data`
	<TR>
	<TD class="label" valign="middle" colspan="1" align="center" >~$data.SENDER`</TD>
	<TD class="label" valign="middle" colspan="1" align="center" >~$data.RECEIVER`</TD>
	<TD class="label" valign="middle" colspan="1" align="center" >~$data.DATE`</TD>
	<TD class="label" valign="middle" colspan="1" align="center" >~$data.TYPE`</TD>
	<TD class="label" valign="middle" colspan="1" align="center" >~$data.IP`</TD>
	<TD class="label" valign="middle" colspan="1" align="center" >~$data.MESSAGE`</TD>
</TR>
	~/foreach`
	
	<TR><TD style="height:20px"> </TD></TR>
	~/foreach`
	~/foreach`
	</table>
~if !$sf_request->getParameter("actiontocall")`	
 <table width="58%" border="0" cellspacing="1" cellpadding="2" align="center">
<tr class="fieldsnew">
	
	<td  valign="middle" colspan="11" align="right" ><a href="~$moduleurl`/linkPage?cid=~$cid`&profileid1=~$profileid`">go to back</a></td>
	</tr>
 </table> 
 ~/if`

~include_partial('global/footer')`
