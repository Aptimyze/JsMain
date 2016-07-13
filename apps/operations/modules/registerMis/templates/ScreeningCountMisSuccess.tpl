<html>
<head>
   <title>Jeevansathi.com - MIS</title>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	<link rel="stylesheet" href="~sfConfig::get('app_img_url')`/jsadmin/jeevansathi.css" type="text/css">
	<script type="text/javascript">
    $(function () {
        var count = 0;
        $('#date1').dateDropDowns({ dateFormat: 'DD-mm-yy',yearStart: "2016", yearEnd: "~$rangeYear`"});
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
        <form name="form1" action="~sfConfig::get('app_site_url')`/operations.php/registerMis/ScreeningCountMis?cid=~$cid`" method="post">
        <input type="hidden" name="cid" value="~$cid`">

	<table border="0" align="center" width="60%" cellpadding="4" cellspacing="4" border="0">
	<tr>
	        <td align="center" class="label"><font size=2>
			<a href="~sfConfig::get('app_site_url')`/mis/mainpage.php?name=~$agentName`&cid=~$cid`">Mainpage</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		</font></td>
	</tr>
	<tr></tr>
	<tr class="formhead" align="center">
		<td colspan="2" style="background-color:lightblue"><font size=3>Registration Quality MIS</font></td>
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
			<td class="fieldsnew">
				<input id="date1" type="text" value="">
				&nbsp;&nbsp;&nbsp;
			</td>
		</tr>
		<tr></tr>
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
