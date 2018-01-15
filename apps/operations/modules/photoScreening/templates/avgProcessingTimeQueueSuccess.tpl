
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	<style>
	DIV {position: relative; top: 45px; right:25px; color:yellow; visibility:hidden}
	</style>
              
</head>
<body bgcolor="#ffffff" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
	<tr>
	        <td valign="top" width="40%" align="center"><img src="/profile/images/logo_1.gif" width="209" height="63" usemap="#Map" border="0"></td>
	</tr>
</table>
        <form name="form1" action="~sfConfig::get('app_site_url')`/operations.php/photoScreening/photoScreeningAverageTime" method="post">
        <input type="hidden" name="cid" value="~$cid`">
        <input type="hidden" name="monthName" value="~$monthName`">
        <input type="hidden" name="yearName" value="~$yearName`">
	<table border="0" align="center" width="60%" cellpadding="4" cellspacing="4" border="0">
	<tr>
	        <td align="center" class="label"><font size=2>
			<a href="/jsadmin/mainpage.php?cid=~$cid`">Mainpage</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		</font></td>
	</tr>
	<tr></tr>
	<tr class="formhead" align="center">
		<td colspan="2" style="background-color:lightblue"><font size=3>Photo Screening MIS</font></td>
	</tr>
	<tr></tr>
	</table>

	<table border="0" align="center" width="60%" cellpadding="4" cellspacing="4" border="0">
	<tr></tr>
	<tr></tr>
	<tr></tr>
	<tr></tr>
		<tr align="center">
			<td class="label"><font size=2> Select Reporting Month, Year and Queue	</font></td>
			
			<td class="fieldsnew">
                     <select id="month" name="monthValue" >
                     ~foreach from=$monthArr item=monthVal`
                            <option value="~$monthVal`" ~if $monthVal eq $mon` selected ~/if`>~$monthVal`</option>
                     ~/foreach`
                     </select>
                     <select id="year" name="yearValue">
                     ~foreach from=$yearArr item=yearVal`
                            <option value="~$yearVal`" >~$yearVal`</option>
                     ~/foreach`
                     </select>
                     <select name="queue">
					 <option value="QUEUE1">AcceptReject</option>
					 <option value="QUEUE2">PhotoProcessing</option>
					 <option value="QUEUE3">PhotoProcessingOverall</option>
					 </select>
		</td>
		</tr>      
		<tr></tr>
		<tr></tr>
		<tr align="center">
			<td class="label" colspan="2" style="background-color:Moccasin">
				<input type="hidden" name="outside" value="~$outside`">
				<input type="submit" name="submit" value="SUBMIT">
			</td>
		</tr>
	</table>
	</form>
<br>

