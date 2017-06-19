<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <title>Jeevansathi.com - Assign Profile</title>
  </meta>	
</head>
~include_partial('global/header')`
<br>
<body bgcolor="#FFFFFF" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table width=100% cellspacing="1" cellpadding='0' ALIGN="CENTER" >
        <tr width=100% border=1>
                <td width="25%" class="formhead" align="center">Upload Notification CSV file</td>
        </tr>
</table>
~if $unAuthorized`
	<table width="40%" border="0" align="center" cellpadding="4" cellspacing="4">
		<tr class="fieldsnew" align="center">
			<td>
				You do not have privilage to upload CSV file.
				<br><a href="~sfConfig::get('app_site_url')`/jsadmin/mainpage.php?cid=~$cid`">Continue&gt;&gt;</a>
			</td>
		</tr>
	</table>
~elseif $successful`
	<table width="40%" border="0" align="center" cellpadding="4" cellspacing="4">
		<tr class="fieldsnew" align="center">
			<td>
				You have successfully uploaded the CSV file. Please wait for some time to upload another csv.
				<br>
				<a href="~sfConfig::get('app_site_url')`/jsadmin/mainpage.php?cid=~$cid`">Continue&gt;&gt;</a>
			</td>
		</tr>
	</table>
~else`
	~if $invalidFile`
	<table width="40%" border="0" align="center" cellpadding="4" cellspacing="4">
		<tr class="fieldsnew" align="center">
			<td>
				Please select a valid CSV file to upload.
			</td>
		</tr>
	</table>
	~/if`
	<form name="csvUpload" method="post" action="~sfConfig::get('app_site_url')`/operations.php/csvUpload/uploadNotificationCsv" enctype="multipart/form-data">
	<table width="40%" border="0" align="center" cellpadding="4" cellspacing="4">
		<tr class="fieldsnew">
			<td>
				<input type="file" name="uploaded_csv" size="25">
			</td>
			<td>
				<input type="submit" name="upload" value="Upload">
			</td>
		</tr>
	</table>
	<input type="hidden" name="cid" value="~$cid`">
	</form>
~/if`
<br><br>
~include_partial('global/footer')`
</body>
</html>
