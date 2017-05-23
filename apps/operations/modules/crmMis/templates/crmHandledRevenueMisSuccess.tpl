<html>
<head>
   	<title>Jeevansathi.com - MIS</title>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	<link rel="stylesheet" href="~sfConfig::get('app_img_url')`/jsadmin/jeevansathi.css" type="text/css">
	<style>
	DIV {position: relative; top: 45px; right:25px; color:yellow; visibility:hidden}
	</style>
                <script>
                function run(){
                        var m = document.form1.getElementById("month");
                        var monthName = m.options[m.selectedIndex].value;
                        
                        var y = document.form1.getElementById("year");
                        var yearName = y.options[y.selectedIndex].value;
                }
                </script>

</head>
<body bgcolor="#ffffff" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
	<tr>
	        <td valign="top" width="40%" align="center"><img src="/profile/images/logo_1.gif" width="209" height="63" usemap="#Map" border="0"></td>
	</tr>
</table>
        <form name="form1" action="~sfConfig::get('app_site_url')`/operations.php/crmMis/crmHandledRevenueMis" method="post">
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
		<td colspan="2" style="background-color:lightblue"><font size=3>CRM Handled Revenue MIS</font></td>
	</tr>
	<tr class="formhead" align="center">
		<td id="alertMsg" colspan="2" style="background-color:red; display:none"><font size=3>Location-wise Ticket Data is not available</font></td>
	</tr>
	<tr></tr>
	</table>

	<table border="0" align="center" width="60%" cellpadding="4" cellspacing="4" border="0">
	<tr></tr>
	<tr></tr>
	<tr></tr>
	<tr></tr>
	~if $SUBMIT_STATUS eq 0`
		<tr align="center">
			<td class="label"><font size=2> Select Reporting Month, Year and Fortnight</font></td>
			
			<td class="fieldsnew">
                                <select id="month" name="monthValue" onchange="run()">
                                        ~foreach from=$monthArr item=monthVal`
                                              <option value="~$monthVal`" ~if $monthVal eq $monthName`selected ~/if`>~$monthVal`</option>
                                        ~/foreach`
                                </select>
				&nbsp;
                                <select id="year" name="yearValue" onchange="run()">
                                        ~foreach from=$yearArr item=yearVal`
                                              <option value="~$yearVal`" ~if $yearVal eq $yearName`selected ~/if`>~$yearVal`</option>
                                        ~/foreach`
                                </select>
                                
                                <select id="fortnight" name="fortnightValue" >
                                    <option value="1" ~if $fortnight eq "1"` selected ~/if` >H1</option>
                                    <option value="2" ~if $fortnight eq "2"` selected ~/if`>H2</option>
                                </select>
			</td>
		</tr>
                <tr align="center">
                        <td class="label"><font size=2>
                               Select Report Type
                        </font></td>
                        <td class="fieldsnew">
				<input id="reportTypeLocation" type="radio" name="report_type" value="LOCATION"><font size=2>&nbsp;Location-Wise </font></input>
				<input type="radio" name="report_type" value="TEAM" checked><font size=2>&nbsp;Team-Wise </font></input><br>
                                &nbsp;
                        </td>
                </tr>
                <tr align="center">
                        <td class="label"><font size=2>
                               Select Report Content
                        </font></td>
                        <td class="fieldsnew">
				<input type="radio" name="report_content" value="REVENUE" checked><font size=2>&nbsp;Revenues </font></input>
				<input id="reportContentTickets" type="radio" name="report_content" value="TICKET"><font size=2>&nbsp;Tickets </font></input><br>
                                &nbsp;
                        </td>
                </tr>
                <tr align="center">
                        <td class="label"><font size=2>
                               Select Report Format
                        </font></td>
                        <td class="fieldsnew">
				<input type="radio" name="report_format" value="HTML" checked><font size=2>&nbsp;HTML Format </font></input>
				<input type="radio" name="report_format" value="XLS"><font size=2>&nbsp;Excel Format </font></input><br>
                                &nbsp;
                        </td>
                </tr>
		<tr></tr>
		<tr></tr>
		<tr align="center">
			<td class="label" colspan="2" style="background-color:Moccasin">
				<input type="hidden" name="outside" value="~$outside`">
				<input id="submitButton" type="submit" name="submit" value="SUBMIT">
			</td>
		</tr>
	~/if`
	</table>
	</form>
</body>
</html>

<script type="text/javascript">

$(document).ready(function(){
	$("#submitButton").click(function() {
		if($("#reportTypeLocation:checked").length > 0 && $("#reportContentTickets:checked").length > 0) {
			$("#alertMsg").show();
			event.preventDefault();
		}
	});
});

</script>
