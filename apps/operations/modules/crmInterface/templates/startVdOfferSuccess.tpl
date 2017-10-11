<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	<link rel="stylesheet" href="~sfConfig::get('app_img_url')`/jsadmin/jeevansathi.css" type="text/css">
</head>
<body bgcolor="#ffffff" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
	<form name="vdExtension" action="~sfConfig::get('app_site_url')`/operations.php/crmInterface/startVdOffer" method="POST">
		<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
			<tr>
				<td valign="top" width="40%" align="center"><img src="/profile/images/logo_1.gif" width="209" height="80" usemap="#Map" border="0"></td>
			</tr>
			<tr class="formhead" align="center" width="100%">
				<td colspan="3" style="background-color:lightblue" height="30">
					<font size=3>Schedule Variable Discount Offer</font>
				</td>
			</tr>
			<tr>
				<td colspan="3" style="background-color:lightblue" height="30" align="center">
					<a href="~sfConfig::get('app_site_url')`/jsadmin/mainpage.php?cid=~$cid`">Click here to go to main page</a>&nbsp;&nbsp;&nbsp;&nbsp;
					<a href="~sfConfig::get('app_site_url')`/operations.php/crmInterface/manageVdOffer?cid=~$cid`">Back</a>&nbsp;&nbsp;&nbsp;&nbsp;
					<a href="~sfConfig::get('app_site_url')`/jsadmin/logout.php?cid=~$cid`">Logout</a>
				</td>
			</tr>
		</table>
		<br>
		<div width="100%" style="background-color:lightblue;text-align:center;padding:20px;font-size:12px;">
                        <div><b>
				~if $vdActive`
					Currently VD Offer is active till ~$vdExpiryDate`<br>
				~else if $vdError`
					<font color="red">Please select correct dates.</font>
				~else if $vdSuccess`
	                                VD Offer scheduled successfully.<br>
				~/if`
				~if $vdScheduled`	
					New VD Offer is already scheduled. 
				~/if`
			</div>
		</div>
		<div width="100%" style="background-color:lightblue;text-align:left;padding:20px;font-size:12px;">
				~if $vdSuccess || $vdScheduled`
					VD Start Date:&nbsp;&nbsp; ~$startDate`<br> 
					VD End Date:&nbsp;&nbsp; ~$endDate`<br> 
					Schedule Date:&nbsp;&nbsp; ~$vdExecuteDate`  
				~/if`
                 </div>
			<br>
			<div align="left" width="100%">
				Select New VD Offer Start Date:
                                <select name="vdStartDate" >
                                        ~foreach from=$vdDateDropdown key=k item=v`
                                                <option value="~$k`">~$v`</option>
                                        ~/foreach`
                                </select>
			</div>
                        <div align="left">
                                Select New VD Offer End Date:                             
                                <select name="vdEndDate" >
                                        ~foreach from=$vdDateDropdown key=k item=v`
                                                <option value="~$k`">~$v`</option>
                                        ~/foreach`
                                </select>
                        </div>
                        <div align="left">
                                Select VD Execution Time:                             
                                <select name="vdExecuteDate" >
                                        ~foreach from=$scheduleDateDropdown key=k item=v`
                                                <option value="~$k`">~$v`</option>
                                        ~/foreach`
                                </select>
                        </div>
		</div>
		<br>
		<div style="margin:0 auto;text-align:center;">
			~if $disableStart`
				<input style="font-size:16px;" type="submit" name="submit" value="Schedule" disabled>	
			~else`
				<input style="font-size:16px;" type="submit" name="submit" value="Schedule">
			~/if`
			<input type="hidden" name="name" value="~$name`">
			<input type="hidden" name="cid" value="~$cid`">
		</div>
	</form>
</body>
</html>
