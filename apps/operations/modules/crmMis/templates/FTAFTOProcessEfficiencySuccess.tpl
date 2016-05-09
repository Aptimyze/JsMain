<html>
<head>
<title>Jeevansathi.com - MIS</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel="stylesheet" href="~sfConfig::get('app_img_url')`/jsadmin/jeevansathi.css" type="text/css">
<script>
function page_load(to_page)
{
        var page="~$CUR_PAGE`&j="+to_page;
        //page=page+"&date_search_submit=1&date1xx=~$date1`&&date2xx=~$date2`";
        document.location=page;
}
</script>
<style>
DIV {position: relative; top: 45px; right:25px; color:yellow; visibility:hidden}
</style>

</head>
<body bgcolor="#ffffff" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">

<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
<tr>
        <td valign="top" width="40%" align="center"><img src="/profile/images/logo_1.gif" width="209" height="63" usemap="#Map" border="0"></td>
</tr>
<tr>
        <td align="center" class="label">
	<a href="/jsadmin/mainpage.php">Mainpage</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	~if $RESULT || $err`
	<a href="FTAFTOProcessEfficiency?cid=~$cid`&outside=~$outside`">Back</a>	
	~/if`
	</td>
</tr>
~if $err`
<tr>
	<td colspan="100%" align="center" class="label"> <font color="red">Please select the date range upto maximum of 6 months </font></td>
</tr>
~/if`
</table>

~if $err eq ''`
<br><br>
~if $RESULT`
  <table width="100%" border="0" cellpadding="4" cellspacing="4" align="center">
	 <tr class="formhead" align="center" style="background-color:lightblue;">
                <td colspan="18">FTA FTO Process Efficiency MIS</td>
        </tr>
	 <tr class="formhead" align="center">
                <td colspan="18">~$headLabel`</td>
        </tr>
	<tr  class="label" align="center">
		<td rowspan="2"  width="10%">Date</td>
		<td rowspan="2"  width="10%">Number of Allocations</td>
		<td colspan="16" >Number of FTO Activations on Xth day from Allocation</td>
	</tr>
	<tr class="label" align="center">
		~assign var=j value=1`
		~while $j <= $ftoDays`
		<td>~$j++`</td>
		~/while`
		<td>Total</td>	
	</tr>
	~assign var=totalAllocations value=0`
	~assign var=totalActivations value=0`
	~foreach from=$conversionCount item=it key=k`
		<tr align='center'>
		<td class='fieldsnew'  width="10%">~$k|date_format:"%d-%b-%y"`</td>
		<td class='fieldsnew'  width="10%">~$conversionCount.$k.ALLOCATIONS`<p class="display:none">~$totalAllocations=$totalAllocations+$conversionCount.$k.ALLOCATIONS`</p></td>
		~assign var=l value=0`
		~while $l <$ftoDays`
		~if $conversionCount.$k.$l`
		<td class='fieldsnew'>~$conversionCount.$k.$l`(~round($conversionCount.$k.$l/$conversionCount.$k.ALLOCATIONS*100)`%)</td>
		~else`
		<td class='fieldsnew'>0</td>
		~/if`	
		<p style="display:none">~$l++`</p>
		~/while`
		<td class='fieldsnew'  width="10%">~$conversionCount.$k.ACTIVATIONS`(~round($conversionCount.$k.ACTIVATIONS/$conversionCount.$k.ALLOCATIONS*100)`%)<p class="display:none">~$totalActivations=$totalActivations+$conversionCount.$k.ACTIVATIONS`</p></td>
		</tr>
	~/foreach`
		<tr class="formhead" align="center">
                <td  width="10%">Total</td>
                <td  width="10%">~$totalAllocations`</td>
		~assign var=l value=0`
		~while $l <$ftoDays`
		~if $conversionOnDay.$l`
		<td>~$conversionOnDay.$l`(~round($conversionOnDay.$l/$totalAllocations*100)`%)</td>
		~else`
		<td>0</td>
		~/if`
		<p style="display:none">~$l++`</p>
		~/while`
		<td>~$totalActivations`(~round($totalActivations/$totalAllocations*100)`%)</td>
                </tr>
  </table>
~else`
	<form name="form1" method="post" action="FTAFTOProcessEfficiency">
	<input type="hidden" name="cid" value="~$cid`">
	<table border="0" align="center" width="60%" cellpadding="4" cellspacing="4" border="0">
		<tr class="formhead" align="center" style="background-color:lightblue;">
                <td colspan="17">FTA FTO Process Efficiency MIS</td>
		</tr>
		<tr class="formhead" align="center">
			<td colspan="2">Select Month or Time Period</td>
		</tr>
		<tr align="center">
			<td class="label">
				<input type="radio" name="select_type" value="M" checked>Select reporting Month and Year
			</td>
			<td class="fieldsnew">
				<select name="month" class="textboxes1">
					<option value="">Month</option>
					~foreach from=$mmarr item=i key=k`
						<option value="~$i.VALUE`" ~if $i.VALUE eq $curmonth` selected ~/if`>~$i.NAME`</option>
					~/foreach`
				</select>
				&nbsp;
				<select name="year_month" class="textboxes1">
					<option value="">Year</option>
					~foreach from=$yyarr item=i`
						<option value="~$i`" ~if $i eq $curyear` selected ~/if`>~$i`</option>
					~/foreach`
				</select>
			</td>
		</tr>
                <tr align="center">
                        <td class="label">
                                <input type="radio" name="select_type" value="R">Select reporting Date range
                        </td>
                       <td class="fieldsnew">
                                <select name="day_r1" class="textboxes1">
                                        <option value="">Day</option>
                                        ~foreach from=$ddarr item=i`
                                                <option value="~$i`" ~if $i eq $day45DaysBefore` selected ~/if`>~$i`</option>
                                        ~/foreach`
                                </select>
                                &nbsp;
                                <select name="month_r1" class="textboxes1">
                                        <option value="">Month</option>
                                        ~foreach from=$mmarr item=i`
                                                <option value="~$i.VALUE`" ~if $i.VALUE eq $month45DaysBefore` selected ~/if`>~$i.NAME`</option>
                                        ~/foreach`
                                </select>
                                &nbsp;
                                <select name="year_r1" class="textboxes1">
                                        <option value="">Year</option>
                                        ~foreach from=$yyarr item=i`
                                                <option value="~$i`" ~if $i eq $year45DaysBefore` selected ~/if`>~$i`</option>
                                        ~/foreach`
                                </select>
				To
                                <select name="day_r2" class="textboxes1">
                                        <option value="">Day</option>
                                        ~foreach from=$ddarr item=i`
                                                <option value="~$i`" ~if $i eq $curday` selected ~/if`>~$i`</option>
                                        ~/foreach`
                                </select>
                                &nbsp;
                                <select name="month_r2" class="textboxes1">
                                        <option value="">Month</option>
                                        ~foreach from=$mmarr item=i`
                                                <option value="~$i.VALUE`" ~if $i.VALUE eq $curmonth` selected ~/if`>~$i.NAME`</option>
                                        ~/foreach`
                                </select>
                                &nbsp;
                                <select name="year_r2" class="textboxes1">
                                        <option value="">Year</option>
                                        ~foreach from=$yyarr item=i`
                                                <option value="~$i`" ~if $i eq $curyear` selected ~/if`>~$i`</option>
                                        ~/foreach`
                                </select>
                        </td>
                </tr>
                <tr align="center">
                        <td class="label">
                               Select Report Format
                        </td>
                        <td class="fieldsnew">
				<input type="radio" name="format_type" value="HTML" checked>&nbsp;HTML Format <br>
				<input type="radio" name="format_type" value="XLS">&nbsp;Excel Format <br>
                                &nbsp;
                        </td>
                </tr>
		<tr align="center">
			<td class="label" colspan="2">
				<input type="hidden" name="outside" value="~$outside`">
				<input type="submit" name="submit" value="Go" class="buttons1">
			</td>
		</tr>
	</table>
	</form>
~/if`
~/if`
</body>
</html>
