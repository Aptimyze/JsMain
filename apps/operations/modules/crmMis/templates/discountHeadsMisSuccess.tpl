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
<body bgcolor="#ffffff" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
	<tr>
	        <td valign="top" width="40%" align="center"><img src="/profile/images/logo_1.gif" width="209" height="63" usemap="#Map" border="0"></td>
	</tr>
</table>
        <form name="form1" action="~sfConfig::get('app_site_url')`/operations.php/crmMis/discountHeadsMis" method="post">
        <input type="hidden" name="cid" value="~$cid`">
	<br>
	<table border="0" align="center" width="60%" cellpadding="4" cellspacing="4" border="0">
	<tr class="formhead" align="center">
		<td colspan="2" style="background-color:lightblue"><font size=4>Discount Heads MIS</font></td>
	</tr>
	<tr align="left" style="background-color:SeaShell"><td colspan="2"><font size=1>
		<br>This MIS answers the following questions</br>
		<br>1 - Which Sales executive gave how much discount (on Membership Plans + Add on services) in INR to his/her allocated consumers via all channels (all sources including online link, online offer, manual billing after cash collection, cheque, bank transfer or IVR)</br>
		<br>2 - How many discounts were given for Rupee transactions and how many for Dollar transactions</br>
		<br>3 - How many Add-On services were given for free/complimentary</br>
		<br>4 - How much Extra Renewal Discount was given over and above the 'Standard Renewal Discount'</br>
		<br>5 - How many discounts were given to UnAllocated consumers?</br>
	</td></tr>
        <tr class="formhead" align="center">
                <td colspan="2"><font size=2><a href="~sfConfig::get('app_site_url')`/mis/mainpage.php?cid=~$cid`">MainPage</a></font></td>
        </tr>

	</table>

<table border="0" align="center" width="60%" cellpadding="4" cellspacing="4" border="0">
        ~if $errorMsg`
		<tr align="center" style="background-color:Red"><td colspan="2"><font size=1><b> ~$errorMsg` </b></font></td></tr>
	~/if`
	<tr align="center">
		<td class="label"><font size=2>
			<font size=2>Select Date Range</font>
		</font></td>
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
		       Select Transactions Unit
		</font></td>
		<td class="fieldsnew">
			<input type="radio" name="transactions" value="INR" checked><font size=2>&nbsp;INR </font></input>
			<input type="radio" name="transactions" value="USD"><font size=2>&nbsp;USD </font></input>
			&nbsp;&nbsp;&nbsp;
		</td>
	</tr>
	<tr align="center">
		<td class="label"><font size=2>
		       Select Output Format
		</font></td>
		<td class="fieldsnew">
			<input type="radio" name="output_format" value="HTML" checked><font size=2>&nbsp;HTML </font></input>
			<input type="radio" name="output_format" value="XLS"><font size=2>&nbsp;Excel </font></input><br>
			&nbsp;
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
