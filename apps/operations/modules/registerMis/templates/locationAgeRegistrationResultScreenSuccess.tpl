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
  <tr class="formhead" align="center">
          <td colspan="2" style="background-color:lightblue"><font size=3>Registration MIS</font></td>
  </tr>
  <tr class="formhead" align="center">
          <td colspan="2" style="background-color:lightblue"><font size=3>Result Screen - ~$displayName`</font></td>
  </tr>
  <tr class="formhead" align="center">
          <td colspan="2" style="background-color:PeachPuff"><font size=2>For the ~if $range_format eq 'Q' || $range_format eq 'M'`year of~else`month of~/if` ~$displayDate`</font></td>
  </tr>
</table>

<table width='100%' align=center style="table-layout: fixed;">
<tr class=formhead style="background-color:LightSteelBlue">
  <td width="200px" align=center>~if $range_format eq 'Q' || $range_format eq 'M'` Month ~else` Day ~/if`</td>
  ~if $range_format eq 'M'` 
        ~foreach from=$monthNames key=k item=monthColumn`
                <td width="80px" align=center>
                ~$monthColumn`
                </td>
        ~/foreach`
  ~elseif $range_format eq 'Q'`
        ~foreach from=$quarterNames key=k item=quarterColumn`
                <td width="80px" align=center>
                ~$quarterColumn`
                </td>
        ~/foreach`
    ~else`
        ~for $day=1 to 31`
                <td width="80px" align=center>
                ~$day`
                </td>
        ~/for`
  ~/if`
  <td width='40px' align=center>Total</td>
  <td width='60px' align=center>Percentage (%)</td>
</tr> 

~if $totalCountValue neq 0`
~foreach from=$groupData['loopOn'] key=key1 item=value`
<tr>
                  <td width="120px" align=center>
                ~$value`
                </td>
                ~foreach from=$groupData['iterate'] key=k2 item=iterVal`
                <td align=center>
                ~if $groupData[$iterVal][$key1] neq ""` ~$groupData[$iterVal][$key1]`
                ~else`
                0
                ~/if`
                </td>
                ~/foreach`
</tr>
~/foreach`
~/if`
</table>
</body>
</html>
