<html>
<head>
	<title>Jeevansathi.com - MIS</title>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	<link rel="stylesheet" href="~sfConfig::get('app_img_url')`/jsadmin/jeevansathi.css" type="text/css">
        <script>
	function run(){
		var y = document.form1.getElementById("year");
		var selectionYear = y.options[y.selectedIndex].value;
	}
	</script>


</head>
<body bgcolor="#ffffff" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
	<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
		<tr>
			<td valign="top" width="40%" align="center"><img src="/profile/images/logo_1.gif" width="209" height="63" usemap="#Map" border="0"></td>
		</tr>
	</table>
	<form name="form1" action="~sfConfig::get('app_site_url')`/operations.php/crmMis/salesProcessWiseTrackingMis" method="post">
		<input type="hidden" name="cid" value="~$cid`">
	        <input type="hidden" name="selectionYear" value="~$selectionYear`">

		<br>
		<table border="0" align="center" width="50%" cellpadding="4" cellspacing="4" border="0">
			<tr class="formhead" align="center">
				<td colspan="2" style="background-color:lightblue"><font size=4>Sales Process-wise Tracking MIS</font></td>
			</tr>
			<tr class="formhead" align="center">
				<td colspan="2"><font size=2><a href="~sfConfig::get('app_site_url')`/mis/mainpage.php?cid=~$cid`">MainPage</a></font></td>
			</tr>

		</table>

		<table border="0" align="center" width="50%" cellpadding="4" cellspacing="4" border="0">
			<tr align="center">
                <td class="label" rowspan="3"><font size=2>
					Select Date Range
				</font></td>
				<td class="fieldsnew" align="left"><font size=2>
                    <input type="radio" name="dateRange" value="D" checked>Date wise
                </td>
                <td class="fieldsnew" align="left"><font size=2>
                    <select id="dateWiseMonth" name="dateWiseMonth" onchange="run()">
						~foreach from=$monthArr key=mn item=ii`
						      <option value="~$mn`" >~$ii`</option>
						~/foreach`
					</select>
                    <select id="year" name="dateWiseYear" onchange="run()">
						~foreach from=$yearArr key=yr item=ii`
						      <option value="~$ii`" >~$ii`</option>
						~/foreach`
					</select>                    
                </td>
            </tr>
            <tr align="left">
                <td class="fieldsnew"><font size="2">
					<input type="radio" name="dateRange" value="M" checked><font size=2>Month Wise
                </td>
                <td class="fieldsnew"><font size="2">
					<select id="monthWiseYear" name="monthWiseYear" onchange="run()">
						~foreach from=$yearArr key=yr item=ii`
						      <option value="~$ii`" >~$ii`</option>
						~/foreach`
					</select>
				</td>
			</tr>
            <tr align="left">
                <td class="fieldsnew"><font size="2">
					<input type="radio" name="dateRange" value="Q" checked><font size=2>Quarter Wise
                </td>
                <td class="fieldsnew"><font size="2">
					<select id="quarterWiseYear" name="quarterWiseYear" onchange="run()">
						~foreach from=$yearArr key=yr item=ii`
						      <option value="~$ii`" >~$ii`</option>
						~/foreach`
					</select>
				</td>
			</tr>
            
			<tr align="center">
				<td class="label" colspan="3" style="background-color:PeachPuff">
					<input type="hidden" name="outside" value="~$outside`">
					<input type="submit" name="submit" value="   GO   ">
				</td>
			</tr>
		</table>
	</form>
</body>
</html>
