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
    <td colspan="2" style="background-color:lightblue"><font size=3><a href="qualityRegistration?cid=~$cid`">Main Page</a></font></td>
  </tr>
  <tr class="formhead" align="center">
          <td colspan="2" style="background-color:lightblue"><font size=3>Registration Quality MIS</font></td>
  </tr>
  <tr class="formhead" align="center">
          <td colspan="2" style="background-color:PeachPuff"><font size=2>For the ~if $range_format eq 'Y'`year of~else`period~/if` ~$displayDate`</font></td>
  </tr>
  ~if $selectedCities neq ''`
        <tr class="formhead" align="center">
                <td colspan="2" style="background-color:PeachPuff"><font size=2>~$selectedLabel`: ~$selectedCities`</font></td>
        </tr>
  ~/if`
</table>

<table width='100%' align=center style="table-layout: fixed;">
<tr class=formhead style="background-color:LightSteelBlue">
  <td width="200px" align=center>~if $range_format eq 'Y'` Month ~else` Day ~/if`</td>
  ~if $range_format eq 'Y'`
        ~foreach from=$columnDates item=dtColumn`
                <td width="80px" align=center>
                ~if $dtColumn >12`
                     ~$displayDate + 1`-0~$dtColumn-12`
                ~else`
                     ~$displayDate`-~$dtColumn`
                ~/if`
                </td>
        ~/foreach`
  ~else`
  <td width='80px' align=center>~implode from=$columnDates delim="</td><td width='80px' align=center >"`</td>
  ~/if`
  <td width='40px' align=center>Total</td>
</tr>
~assign var='dateTotal' value=0`
<tr class=formhead style="background-color:#C3D69B">
        <td align=center><strong>Total Registrations</strong></td>
  ~foreach from=$columnDates item=dtColumn`
    ~assign var='dateTotal' value=$dateTotal+$dates_count[$dtColumn]`
    <td  align=center>~if isset($dates_count[$dtColumn])` ~$dates_count[$dtColumn]` ~else`0~/if`</td>
  ~/foreach`
  <td>~$dateTotal`</td>
</tr>
~assign var='i' value=0`
~foreach from=$sgroupData key=ky1 item=groupData`
    ~foreach from=$groupData key=ky item=srcData`
      ~if $i !=0 && isset($srcData['is_grp'])`
      <tr><td colspan="~count($columnDates)`" style="height: 12px;"></td></tr>
      ~/if`
      ~assign var='i' value=$i+1`
      ~if isset($srcData['is_grp'])` ~assign var='headerColor' value='background-color:#DCE6F2'` ~else` ~assign var='headerColor' value='background-color:#E6E0EC'` ~/if`
      ~if $ky1 neq 0`
        <tr style = 'background-color:#CCC1DA' >
          <td  align=center>~$srcData['groupName']`</td>
          ~assign var='dateTotal' value=0`
           ~foreach from=$columnDates item=dtColumn`
            ~if isset($srcData['TOTAL_REG'][$dtColumn])`
              ~assign var='TotalReg' value=$srcData['TOTAL_REG'][$dtColumn]`
            ~else`
              ~assign var='TotalReg' value=0`
            ~/if`
            ~assign var='dateTotal' value=$dateTotal+$TotalReg`
            <td  align=center >
            ~$TotalReg`
            </td>
           ~/foreach`
           <td>~$dateTotal`</td>
        </tr>
      ~/if`
      <tr style = '~$headerColor`' >
        <td  align=center style='color:#0072CB'>F>=22</td> 
        ~assign var='dateTotal' value=0`
         ~foreach from=$columnDates item=dtColumn`
          ~if isset($srcData['F22'][$dtColumn])`
            ~assign var='TotalReg' value=$srcData['F22'][$dtColumn]`
          ~else`
            ~assign var='TotalReg' value=0`
          ~/if`
          ~assign var='dateTotal' value=$dateTotal+$TotalReg`
          <td  align=center >
          ~$TotalReg`
          </td>
         ~/foreach`
         <td>~$dateTotal`</td>
      </tr>
      <tr style = '~$headerColor`' >
        <td  align=center style='color:#009B49'>F>=22 + MV</td>  
        ~assign var='dateTotal' value=0`
         ~foreach from=$columnDates item=dtColumn`
          ~if isset($srcData['F22MV'][$dtColumn])`
            ~assign var='TotalReg' value=$srcData['F22MV'][$dtColumn]`
          ~else`
            ~assign var='TotalReg' value=0`
          ~/if`
          ~assign var='dateTotal' value=$dateTotal+$TotalReg`
          <td  align=center >
          ~$TotalReg`
          </td>
         ~/foreach`
         <td>~$dateTotal`</td>
      </tr>
      <tr style = '~$headerColor`' >
        <td  align=center style='color:#FF0000'>F>=22 + MV + CC</td>
        ~assign var='dateTotal' value=0`
         ~foreach from=$columnDates item=dtColumn`
          ~if isset($srcData['F22MVCC'][$dtColumn])`
            ~assign var='TotalReg' value=$srcData['F22MVCC'][$dtColumn]`
          ~else`
            ~assign var='TotalReg' value=0`
          ~/if`
          ~assign var='dateTotal' value=$dateTotal+$TotalReg`
          <td  align=center >
          ~$TotalReg`
          </td>
         ~/foreach`
         <td>~$dateTotal`</td>
      </tr>
      <tr style = '~$headerColor`' >
        <td  align=center style='color:#0072CB'>M>=26</td>
        ~assign var='dateTotal' value=0`
         ~foreach from=$columnDates item=dtColumn`
          ~if isset($srcData['M26'][$dtColumn])`
            ~assign var='TotalReg' value=$srcData['M26'][$dtColumn]`
          ~else`
            ~assign var='TotalReg' value=0`
          ~/if`
          ~assign var='dateTotal' value=$dateTotal+$TotalReg`
          <td  align=center >
          ~$TotalReg`
          </td>
         ~/foreach`
         <td>~$dateTotal`</td>
      </tr>
      <tr style = '~$headerColor`' >
        <td  align=center style='color:#009B49'>M>=26 + MV</td>
        ~assign var='dateTotal' value=0`
         ~foreach from=$columnDates item=dtColumn`
          ~if isset($srcData['M26MV'][$dtColumn])`
            ~assign var='TotalReg' value=$srcData['M26MV'][$dtColumn]`
          ~else`
            ~assign var='TotalReg' value=0`
          ~/if`
          ~assign var='dateTotal' value=$dateTotal+$TotalReg`
          <td  align=center >
          ~$TotalReg`
          </td>
         ~/foreach`
         <td>~$dateTotal`</td>
      </tr>
      <tr style = '~$headerColor`' >
        <td  align=center style='color:#FF0000'>M>=26 + MV + CC</td>
        ~assign var='dateTotal' value=0`
         ~foreach from=$columnDates item=dtColumn`
          ~if isset($srcData['M26MVCC'][$dtColumn])`
            ~assign var='TotalReg' value=$srcData['M26MVCC'][$dtColumn]`
          ~else`
            ~assign var='TotalReg' value=0`
          ~/if`
          ~assign var='dateTotal' value=$dateTotal+$TotalReg`
          <td  align=center >
          ~$TotalReg`
          </td>
         ~/foreach`
         <td>~$dateTotal`</td>
      </tr>
      <tr style = '~$headerColor`' >
        <td  align=center style='color:#80008O'>All screened + SIC</td>
        ~assign var='dateTotal' value=0`
         ~foreach from=$columnDates item=dtColumn`
          ~if isset($srcData['SCREENED_SIC'][$dtColumn])`
            ~assign var='TotalReg' value=$srcData['SCREENED_SIC'][$dtColumn]`
          ~else`
            ~assign var='TotalReg' value=0`
          ~/if`
          ~assign var='dateTotal' value=$dateTotal+$TotalReg`
          <td  align=center >
          ~$TotalReg`
          </td>
         ~/foreach`
         <td>~$dateTotal`</td>
      </tr>
      <tr style = '~$headerColor`' >
      <td  align=center style='color:#FF0000'>
        ~if $ky1 eq 0`
                <strong>Total Quality Reg</strong>
        ~else`
                Total Quality Reg
        ~/if`
      </td>  
      ~assign var='dateTotal' value=0`
         ~foreach from=$columnDates item=dtColumn`
          ~if isset($srcData['M26MVCC'][$dtColumn])`
            ~assign var='M26MVCC' value=$srcData['M26MVCC'][$dtColumn]`
          ~else`
            ~assign var='M26MVCC' value=0`
          ~/if`
          ~if isset($srcData['F22MVCC'][$dtColumn])`
            ~assign var='F22MVCC' value=$srcData['F22MVCC'][$dtColumn]`
          ~else`
            ~assign var='F22MVCC' value=0`
          ~/if`
          ~assign var='TotalReg' value=$F22MVCC+$M26MVCC`
          ~assign var='dateTotal' value=$dateTotal+$TotalReg`
          <td  align=center >
          ~$TotalReg`
          </td>
         ~/foreach`
         <td>~$dateTotal`</td>
    </tr>
  ~/foreach`
~/foreach`
</table>
</body>
</html>
