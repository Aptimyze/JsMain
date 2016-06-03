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
	<form name="form1" action="~sfConfig::get('app_site_url')`/operations.php/crmMis/cityWiseFreshAndRenewalMis" method="post">
		<input type="hidden" name="cid" value="~$cid`">
	        <input type="hidden" name="selectionYear" value="~$selectionYear`">

		<br>
		<table border="0" align="center" width="50%" cellpadding="4" cellspacing="4" border="0">
			<tr class="formhead" align="center">
				<td colspan="2" style="background-color:lightblue"><font size=4>City Wise Fresh &amp; Renewal MIS</font></td>
			</tr>
			<tr class="formhead" align="center">
				<td colspan="2"><font size=2><a href="~sfConfig::get('app_site_url')`/mis/mainpage.php?cid=~$cid`">MainPage</a></font></td>
			</tr>

		</table>

		<table border="0" align="center" width="50%" cellpadding="4" cellspacing="4" border="0">
			<tr align="left">
				<td class="label"><font size=2>
					Select Date Range
				</font></td>
				<td class="fieldsnew">
					<input type="radio" name="selectionRange" value="Q"><font size=2>Quarter Wise &nbsp;&nbsp;&nbsp;
					<input type="radio" name="selectionRange" value="M" checked><font size=2>Month Wise &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					<select id="year" name="selectionYear" onchange="run()">
						~foreach from=$yearArr key=yr item=ii`
						      <option value="~$yr`" >~$yr`</option>
						~/foreach`
					</select>
				</td>
			</tr>
			<tr align="left">
				<td class="label"><font size=2>
					Select Sales Type
				</font></td>
				<td class="fieldsnew">
					<input type="radio" name="saleType" value="F"><font size=2>Fresh Sales </font></input>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					<input type="radio" name="saleType" value="R"><font size=2>Renewal Sales </font></input>&nbsp;&nbsp;&nbsp;
					<input type="radio" name="saleType" value="T" checked><font size=2>Total Sales </font></input>
				</td>
			</tr>
			<tr align="left">
				<td class="label"><font size=2>
					Select Output Format
				</font></td>
				<td class="fieldsnew">
					<input type="radio" name="output_format" value="HTML" checked><font size=2>HTML </font></input>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					<input type="radio" name="output_format" value="XLS"><font size=2>Excel </font></input>
				</td>
			</tr>
			<tr align="center">
				<td class="label" colspan="2" style="background-color:PeachPuff">
					<input type="hidden" name="outside" value="~$outside`">
					<input type="submit" name="submit" value="   GO   ">
				</td>
			</tr>
		</table>
	</form>
</body>
</html>
