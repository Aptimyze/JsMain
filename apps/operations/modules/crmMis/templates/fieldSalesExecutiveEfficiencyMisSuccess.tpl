<html>
<head>
   <title>Jeevansathi.com - MIS</title>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	<link rel="stylesheet" href="~sfConfig::get('app_img_url')`/jsadmin/jeevansathi.css" type="text/css">
	<script type="text/javascript">
    $(function () {
    	var count = 0;
        $('#date1').dateDropDowns({ dateFormat: 'DD-mm-yy',yearStart: "2014", yearEnd: "~$rangeYear`"});
        $('#date2').dateDropDowns({ dateFormat: 'DD-mm-yy',yearStart: "2014", yearEnd: "~$rangeYear`"});
        $('#date1_dateLists_day_list option:selected').prop('selected', false);
        $('#date1_dateLists_day_list').on('click', function(){
        	count = 1;
        });
        $('#date1_dateLists_month_list').on('click', function(){
        	if(count != 1){
        		$('#date1_dateLists_day_list option:selected').prop('selected', false);
        	}
        });
    });    
</script>
</head>
<body>
<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
	<tr>
	        <td valign="top" width="40%" align="center"><img src="~sfConfig::get('app_img_url')`/profile/images/logo_1.gif" width="209" height="63" usemap="#Map" border="0"></td>
	</tr>
</table>
        <form name="form1" action="~sfConfig::get('app_site_url')`/operations.php/crmMis/fieldSalesExecutiveEfficiencyMis" method="get">
        <input type="hidden" name="cid" value="~$cid`">

	<table border="0" align="center" width="60%" cellpadding="4" cellspacing="4" border="0">
	<tr>
	        <td align="center" class="label"><font size=2>
			<a href="~sfConfig::get('app_site_url')`/mis/mainpage.php?name=~$agentName`&cid=~$cid`">Mainpage</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		</font></td>
	</tr>
	<tr></tr>
	<tr class="formhead" align="center">
		<td colspan="2" style="background-color:lightblue"><font size=3>Field Sales Executive Efficiency MIS</font></td>
	</tr>
	<tr></tr>
	</table>

	~if $errorMsg`
		<table border="0" align="center" width="60%" cellpadding="4" cellspacing="4" border="0">
		 <tr></tr>
                <tr></tr>
                <tr></tr>
                <tr></tr>
                <tr align="center">
                        <td class="label">
			<font size=2> ~$errorMsg`  </font>
                        </td>
		</tr>
		</table>
	~else`
		<table border="0" align="center" width="60%" cellpadding="4" cellspacing="4" border="0">
		<tr></tr>
		<tr></tr>
		<tr></tr>
		<tr></tr>
		<tr align="center">
			<td class="label">
				<input type="radio" name="range_format" value="MY" />
				<font size=2> Select Reporting Month and Year	</font>
			</td>
			<td class="fieldsnew">
				<select id="month" name="monthValue">
					~foreach from=$monthArr item=monthVal`
					      <option value="~$monthVal.VALUE`" ~if $monthVal.VALUE eq $todayMonth` selected="yes" ~/if`>~$monthVal.NAME`</option>
					~/foreach`
				</select>
				&nbsp;
				<select id="year" name="yearValue">
					~foreach from=$yearArr item=yearVal`
					      <option value="~$yearVal.VALUE`" ~if $yearVal.VALUE eq $todayYear` selected="yes" ~/if`>~$yearVal.NAME`</option>
					~/foreach`
				</select>
			</td>
		</tr>
		<tr align="center">
			<td class="label">
				<input type="radio" name="range_format" value="DMY" checked="yes" />
				<font size=2>Select Reporting Date Range</font>
			</td>
			<td class="fieldsnew">
				<input id="date1" type="text" value="">
				&nbsp;&nbsp;&nbsp;
				<b>To</b>
				&nbsp;&nbsp;&nbsp;
				<input id="date2" type="text" value="">
			</td>
		</tr>
		<tr align="center">
			<td class="label"><font size=2>
			       Select Report Format
			</font></td>
			<td class="fieldsnew">
				<input type="radio" name="report_format" value="HTML" checked><font size=2>&nbsp;HTML Format </font></input><br />
				<input type="radio" name="report_format" value="XLS"><font size=2>&nbsp;Excel Format </font></input><br>
				&nbsp;
			</td>
		</tr>
		<tr></tr>
		<tr></tr>
		<tr align="center">
			<td class="label" colspan="2" style="background-color:Moccasin">
				<input type="submit" name="submit" value="SUBMIT">
			</td>
		</tr>
		</table>
	~/if`
	</form>
</body>
</html>
