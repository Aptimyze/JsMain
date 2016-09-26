<html>
<head>
   <title>Jeevansathi.com - MIS</title>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	<link rel="stylesheet" href="~sfConfig::get('app_img_url')`/jsadmin/jeevansathi.css" type="text/css">
	<script type="text/javascript">
    $(function () {
        var count = 0;
        $('#date1').dateDropDowns({ dateFormat: 'DD-mm-yy',yearStart: "2015", yearEnd: "~$rangeYear`"});
        $('#date2').dateDropDowns({ dateFormat: 'DD-mm-yy',yearStart: "2015", yearEnd: "~$rangeYear`"});
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
		~if $communitywiseRegistration`
        	<form name="form1" action="~sfConfig::get('app_site_url')`/operations.php/registerMis/communitywiseRegistration?cid=~$cid`" method="post">
        ~else`
        	<form name="form1" action="~sfConfig::get('app_site_url')`/operations.php/registerMis/qualityRegistration?cid=~$cid`" method="post">
		~/if`
        <input type="hidden" name="cid" value="~$cid`">

	<table border="0" align="center" width="60%" cellpadding="4" cellspacing="4" border="0">
	<tr>
	        <td align="center" class="label"><font size=2>
			<a href="~sfConfig::get('app_site_url')`/mis/mainpage.php?name=~$agentName`&cid=~$cid`">Mainpage</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		</font></td>
	</tr>
	<tr></tr>
	<tr class="formhead" align="center">
	~if $communitywiseRegistration`
		<td colspan="2" style="background-color:lightblue"><font size=3>City/Community/Source wise Quality Registration</font></td>
	~else`
		<td colspan="2" style="background-color:lightblue"><font size=3>Registration Quality MIS</font></td>
	~/if`
	</tr>
	<tr></tr>
	</table>

	~if $errorMsg != ''`
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
  ~/if`
		<table border="0" align="center" width="60%" cellpadding="4" cellspacing="4" border="0">
		<!--<tr align="center" style="background-color:SeaShell"><td colspan="2"><font size=2>
			Channel-wise data is valid since 1st Jan 2015 and default channel before 1st Jan 2015 is Desktop.
		</td></tr>-->

		<tr></tr>
		<tr></tr>
		<tr></tr>
		<tr></tr>
    <tr align="center">
			<td class="label">
				<input type="radio" name="range_format" value="DMY" checked="yes" />
				<font size=2>Select Date Range</font>
			</td>
			<td class="fieldsnew">
				<input id="date1" type="text" value="">
				&nbsp;&nbsp;&nbsp;
				<b>To</b>
				&nbsp;&nbsp;&nbsp;
				<input id="date2" type="text" value="">
			</td>
		</tr>


~if !$communitywiseRegistration`

		<tr align="center">
			<td class="label">
				<input type="radio" name="range_format" value="Y" />
				<font size=2> Select Year	</font>
			</td>
			<td class="fieldsnew">
				<select id="year" name="yearValue">
					~foreach from=$yearArr item=yearVal`
					      <option value="~$yearVal.VALUE`" ~if $yearVal.VALUE eq $todayYear` selected="yes" ~/if`>~$yearVal.NAME`</option>
					~/foreach`
				</select>
			</td>
		</tr>		
		<tr align="center">
			<td class="label"><font size=2>
			       Select SourceGroup
			</font></td>
			<td class="fieldsnew">
        <select multiple name ='source_names[]' size=15 style='min-width: 255px'>
          ~foreach from=$sources item=src`
            <option value="~$src.GROUPNAME`">~$src.GROUPNAME`</option>
          ~/foreach`
        </select>
			</td>
		</tr>
		<tr></tr>
		<tr align="center">
			<td class="label"><font size=2>
			       Select Cities
			</font></td>
			<td class="fieldsnew">
        <select multiple name ='source_cities[]' size=15 style='min-width: 255px'>
          ~foreach from=$source_cities key=k item=src_city`
            <option value="~$k`">~$src_city`</option>
          ~/foreach`
        </select>
			</td>
		</tr>
		<tr></tr>
	~/if`
		<tr align="center">
			<td class="label"><font size=2>
			       Select Report Format
			</font></td>
			<td class="fieldsnew">
				<input type="radio" name="report_format" value="HTML" checked><font size=2>&nbsp;HTML Format </font></input><br />
				<input type="radio" name="report_format" value="CSV"><font size=2>&nbsp;CSV Format </font></input><br>
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
	</form>
</body>
</html>
