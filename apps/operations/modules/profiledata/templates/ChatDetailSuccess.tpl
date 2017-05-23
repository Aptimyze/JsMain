
<link rel="stylesheet" href="~sfConfig::get('app_img_url')`/jsadmin/jeevansathi.css" type="text/css">
<link rel="stylesheet" href="~sfConfig::get('app_img_url')`/profile/images/styles.css" type="text/css">

<form action="~$moduleurl`/search" method="post">
<input type="hidden" name=cid value="~$cid`">
~include_partial('global/header')`

<table width="80%" border="0" cellspacing="1" cellpadding="2" align="center">
<tr class="fieldsnew">
	
	<td class="formhead" valign="middle" colspan="7" align="center" >Chat History  </td>
	</tr>
<br>
	<TR class="formhead" valign="middle" colspan="7S" align="center"><TH>Sender</TH><TH>Receiver</TH><TH>Date (IST)</TH><TH>IP</TH><TH style="width:40%">MESSAGE</TH>
	<TR><TD style="height:20px"> </TD></TR>
	~if !$arrDetails`
	<tr><TD class="label" colspan=6 style="color:red">No records found
	
	</td></tr>
	~/if`
                
	~foreach $arrDetails as $ii`
        <TR>
	~foreach from=$ii key=vv item=data`
            <TD class="label" valign="middle" colspan="1" align="center" >~$data`</TD>
	~/foreach`
        </TR>
        <TR><TD style="height:20px"> </TD></TR>
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
