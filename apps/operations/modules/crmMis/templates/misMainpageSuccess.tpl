<html>
<head>
	<title>Jeevansathi.com - MIS</title>
</head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel="stylesheet" href="~sfConfig::get('app_site_url')`/profile/images/styles.css" type="text/css">
        <title>JeevanSathi</title>
</meta>
<body bgcolor="#ffffff" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
	<br><br>
	<table width=80% align="center">
		<tr><td colspan=2>
		        <table width="100%" aligh="center">
				<tr align="center" class="bigblack">
        			<td width="25%" class="class3"><a href="~sfConfig::get('app_site_url')`/jsadmin/mainpage.php?name=~$username`&cid=~$cid`">JSAdmin</a></td>
        			<td width="25%" class="class3"><a href="~sfConfig::get('app_site_url')`/jsadmin/mainpage.php?name=~$username`&cid=~$cid`">Billing</a></td>
        			<td width="25%" class="class3"><a href="~sfConfig::get('app_site_url')`/operations.php/crmMis/misMainpage?cid=~$cid`">Mis</a></td>
        			<td width="25%" class="class3"><a href="~sfConfig::get('app_site_url')`/jsadmin/logout.php?name=~$username`&cid=~$cid`">Logout</a></td>
        			</tr>
			</table><br><br>
		</td></tr>
		<tr><td colspan=2><h2>According to your assigned privilages we found following link(s) for you.</h2><br><br></td></tr>
	        ~foreach from=$linkDetailsArr item=linkDetails name=link`
			<tr class="bigblack">
				<td class="class4" width=50%>
	                        	~$smarty.foreach.link.index+1`.  <a href="~$linkDetails.MAIN_URL`">~$linkDetails.NAME`</a>
				</td>
                        	<td class="class4">
					~if $linkDetails.JUMP_URL` 
						<a href="~$linkDetails.JUMP_URL`">JUMP</a>
					~/if`	
				</td>
                	</tr>
        	~/foreach`
	</table>
	<br><br>
</body>
</html>
