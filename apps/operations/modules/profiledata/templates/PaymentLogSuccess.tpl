<link rel="stylesheet" href="~sfConfig::get('app_img_url')`/jsadmin/jeevansathi.css" type="text/css">
<link rel="stylesheet" href="~sfConfig::get('app_img_url')`/profile/images/styles.css" type="text/css">
<form action="~$moduleurl`/search" method="post">
	<input type="hidden" name=cid value="~$cid`">
	~include_partial('global/header')`
	<br>
	<table width="93%" border="0" cellspacing="1" cellpadding="2" align="center">
		<tr class="fieldsnew">
			<td class="formhead" valign="middle" colspan="13" align="center" >Payment Data</td>
		</tr>
		<br>
		<TR class="formhead" valign="middle" colspan="2" align="center"><TH>Bill Id</TH><TH>Payment Mode</TH><TH>Cheque No</TH><TH>Cheque Date</TH><TH>Cheque City</TH><TH>Bank</TH><TH>IP</TH><TH>Status</TH><TH>Entry Date(IST)*</TH><TH>Service Names</TH><TH>Gateway*</TH><TH>TXN Ref No*</TH><TH>RRN*</TH>
		~if !$paymentArr`
		<table width="60%" border="0" cellspacing="1" cellpadding="2" align="center">
			<tr class="fieldsnew">
				
				<td  valign="middle" colspan="11" align="center" >No Data to display	</td>
			</tr>
		</table>
		~else`
		~foreach $paymentArr as $kk`
		<TR>
			~foreach $kk as $i`
			<TD class="label" valign="middle" colspan="1" align="center" >~$i`</TD>
			~/foreach`
		</TR>
		~/foreach`
	</table>
	<table width="93%" border="0" cellspacing="1" cellpadding="2" align="center">
		<tr class="fieldsnew">
			
			<td  valign="middle" colspan="11" align="left" >* mark fields will come only for online transactions	</td>
		</tr>
	</table>
	~/if`
	~if !$sf_request->getParameter("actiontocall")`
	<table width="93%" border="0" cellspacing="1" cellpadding="2" align="center">
		<tr class="fieldsnew">
			
			<td  valign="middle" colspan="11" align="right" ><a href="~$moduleurl`/linkPage?cid=~$cid`&profileid1=~$profileid`">go to back</a></td>
		</tr>
	</table>
	~/if`
	~include_partial('global/footer')`