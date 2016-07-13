<html>
<head>
   	<title>Jeevansathi.com - MIS</title>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	<link rel="stylesheet" href="~sfConfig::get('app_img_url')`/jsadmin/jeevansathi.css" type="text/css">
	<style>
	DIV {position: relative; top: 45px; right:25px; color:yellow; visibility:hidden}
	</style>
</head>
<body bgcolor="#ffffff" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
        <input type="hidden" name="monthName" value="~$monthName`">
        <input type="hidden" name="yearName" value="~$yearName`">
<table width="100%" align="center">
	<tr>
	        <td valign="top" width="40%" align="center"><img src="/profile/images/logo_1.gif" width="209" height="63" usemap="#Map" border="0"></td>
	</tr>
  <tr class="formhead" align="center">
    <td colspan="2" style="background-color:lightblue"><font size=3><a href="LocationAgeRegistration?cid=~$cid`">Back</a></font></td>
  </tr>
  <tr class="formhead" align="center">
    <td colspan="2" style="background-color:lightblue"><font size=3><a href="~sfConfig::get('app_site_url')`/mis/mainpage.php?name=~$agentName`&cid=~$cid`">Main Page</a></font></td>
  </tr>
</table>
<table>
                        <tr><td style="color:red;">
                                New Profiles
                        </td></tr>
                        <tr><td style="color:DarkBlue;">
				Edit Profiles
                        </td></tr>
                        <tr><td style="color:cyan;">
				Photo accept/reject New Profiles
                        </td></tr>
                        <tr><td style="color:black;">
				Photo accept/reject Edit Profiles
                        </td></tr>
                        <tr><td style="color:green;">
				Photo Process New Profiles
                        </td></tr>
                        <tr><td style="color:maroon;">
				Photo Process Edit Profiles
                        </td></tr>
</table>

<table width='100%' align=center style="table-layout: fixed;">
<tr class=formhead style="background-color:LightSteelBlue">
  <td width="200px" align=center>~if $range_format eq 'Q' || $range_format eq 'M'` Month ~else` Day ~/if`</td>
        ~foreach from=$hrArr key=k item=hrNum`
                <td width="80px" align=center>
                ~$hrNum`
                </td>
        ~/foreach`
</tr> 

~foreach from=$finalRec key=key1 item=value`
<tr>
                  <td width="120px" align=center>
                ~$key1`
                </td>
                ~foreach from=$value key=k2 item=counts`
                <td align=center>
			<table>
			<tr><td style="color:red;">
				~$counts['PROFILE_NEW']`
			</td></tr>
			<tr><td style="color:DarkBlue;">
				~$counts['PROFILE_EDIT']`
			</td></tr>
			<tr><td style="color:cyan;">
				~$counts['PHOTO_ACCEPT_REJ_NEW']`
			</td></tr>
			<tr><td style="color:black;">
				~$counts['PHOTO_ACCEPT_REJ_EDIT']`
			</td></tr>
			<tr><td style="color:green;">
				~$counts['PHOTO_PROCESS_NEW']`
			</td></tr>
			<tr><td style="color:maroon;">
				~$counts['PHOTO_PROCESS_EDIT']`
			</td></tr>
			</table>
                </td>
                ~/foreach`
</tr>
~/foreach`
</table>
</body>
</html>
