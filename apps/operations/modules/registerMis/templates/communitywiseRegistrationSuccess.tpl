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
    <td colspan="2" style="background-color:lightblue"><font size=3><a href="communitywiseRegistration?cid=~$cid`">Main Page</a></font></td>
  </tr>
  <tr class="formhead" align="center">
          <td colspan="2" style="background-color:lightblue"><font size=3>Community wise registration</font></td>
  </tr>
  <tr class="formhead" align="center">
          <td colspan="2" style="background-color:PeachPuff"><font size=2>For the ~if $range_format eq 'Y'`year of~else`period~/if` ~$displayDate`</font></td>
  </tr>
  ~if $selectedCities neq ''`
        <tr class="formhead" align="center">
                <td colspan="2" style="background-color:PeachPuff"><font size=2>Cities: ~$selectedCities`</font></td>
        </tr>
  ~/if`
</table>

<table width='100%' align=center style="table-layout: fixed;">
	<tr class=formhead style="background-color:LightSteelBlue">
	  <td width="40px" align=center>Date</td>
	  <td width='40px' align=center>City</td>
	  <td width='40px' align=center>Source Category</td>
	  <td width='40px' align=center>Community</td>
	  <td width='40px' align=center>Total Registeration</td>
	  <td width='40px' align=center>Quality Registeration</td>
	</tr>

	~foreach from=$registrationData['source_data'] item=data key=k`
          ~if $data['SCREENED_SIC'] != 0 && $data['SCREENED_SIC'] != null`
          <tr  style='background-color:#DCE6F2'>
          	<td width="40px" align=center>
          		~$data['REG_DATE']`
          	</td>

          	<td width="40px" align=center>
          		~FieldMap::getFieldLabel("city_india",$data['SOURCECITY'])`
          	</td>
          	<td width="40px" align=center>
          		~$data['SOURCEID']`
          	</td>

          	<td width="40px" align=center>
          		SI
          	</td>
          	
          	<td width="40px" align=center>
          		~$data['SCREENED_SIC']`
          	</td>
          	
          	<td width="40px" align=center>
          		0
          	</td>
          </tr>
          ~/if`

           ~if $data['OTHERS_COMMUNITY'] != 0 && $data['OTHERS_COMMUNITY'] != null`
          <tr  style='background-color:#DCE6F2'>
          	<td width="40px" align=center>
          		~$data['REG_DATE']`
          	</td>

          	<td width="40px" align=center>
          		~FieldMap::getFieldLabel("city_india",$data['SOURCECITY'])`
          	</td>
          	<td width="40px" align=center>
          		~$data['SOURCEID']`
          	</td>

          	<td width="40px" align=center>
          		Others
          	</td>
          	
          	<td width="40px" align=center>
          		~$data['OTHERS_COMMUNITY']`
          	</td>
          	
          	<td width="40px" align=center>
          		0
          	</td>
          </tr>
          ~/if`

          ~if ($data['M26MVCC'] != 0 && $data['M26MVCC'] != null) || ($data['F22MVCC'] != 0 && $data['F22MVCC'] != null)`
          <tr  style='background-color:#DCE6F2'>
          	<td width="40px" align=center>
          		~$data['REG_DATE']`
          	</td>

          	<td width="40px" align=center>
          		~FieldMap::getFieldLabel("city_india",$data['SOURCECITY'])`
          	</td>
          	<td width="40px" align=center>
          		~$data['SOURCEID']`
          	</td>

          	<td width="40px" align=center>
          		CC
          	</td>
          	
          	<td width="40px" align=center>
          		~$data['M26'] + $data['F22']`
          	</td>
          	<td width="40px" align=center>
          		~$data['M26MVCC'] + $data['F22MVCC']`
          	</td>
          </tr>
          ~/if`
    ~/foreach`
</table>
</body>
</html>
