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
<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
	<tr>
	        <td valign="top" width="40%" align="center"><img src="/profile/images/logo_1.gif" width="209" height="63" usemap="#Map" border="0"></td>
	</tr>
	<tr>
	        <td align="center" class="label">
			<a href="/jsadmin/mainpage.php?cid=~$cid`">Mainpage</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			~if $RESULT || $err`
			<a href="ftaRegular?cid=~$cid`&submit=0&user=~$user`">Back</a>	
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
<br>
~if $RESULT`
<table width="100%" border="0" cellpadding="4" cellspacing="4" align="center">
        <tr class="formhead" align="center">
                <td colspan="100%" style="background-color:lightblue";>FTA Executive Efficiency MIS</td>
        </tr>
	<tr class="formhead" align="center">
		<td colspan="100%">~$head_label`</td>
	</tr>
	<tr class="label" align="center">
		~foreach from=$label_arr item=i`
			<td>~$i.NAME`</td>
		~/foreach`
	</tr>
	~foreach from=$data_arr item=data_val1 key=key`	
	   ~if $data_val1.CALLED_DATE || $data_val1.PHOTO_DATE || $data_val1.EOI_DATE || $data_val1.PAID_DATE`
		<tr align="center">
			<td class="fieldsnew">~$key`</td>
		    <td class="fieldsnew">~if $data_val1.CALLED_DATE`~$data_val1.CALLED_DATE`~else` 0 ~/if`</a></td>
            <td class="fieldsnew">~if $data_val1.PHOTO_DATE`~$data_val1.PHOTO_DATE`~else` 0 ~/if`</td>
			<td class="fieldsnew">~if $data_val1.EOI_DATE`~$data_val1.EOI_DATE`~else` 0 ~/if`</td>
            <td class="fieldsnew">~if $data_val1.PAID_DATE`~$data_val1.PAID_DATE`~else` 0 ~/if`</td>
		</tr>
	   ~/if`		
	~/foreach`
</table>
~else`
	<form name="form1" method="post" action="ftaRegular">
	<input type="hidden" name="cid" value="~$cid`">
	<input type="hidden" name="user" value="~$user`">
	<table border="0" align="center" width="60%" cellpadding="4" cellspacing="4" border="0">
		<tr class="formhead" align="center">
			<td colspan="2">FTA Executive Efficiency MIS</td>
		</tr>
		<tr align="center">
			<td class="label">
				<input type="radio" name="select_type" value="M" checked>Select reporting Month and Year
			</td>
			<td class="fieldsnew">
				<select name="month" class="textboxes1">
					<option value="">Month</option>
					~foreach from=$mmarr item=month`
						<option value="~$month['VALUE']`" ~if $month['VALUE'] eq $curmonth` selected ~/if`>~$month['NAME']`</option>
					~/foreach`
				</select>
				&nbsp;
				<select name="year_month" class="textboxes1">
					<option value="">Year</option>
					~foreach from=$yyarr item=year`
						<option value="~$year`" ~if $year eq $curyear` selected ~/if`>~$year`</option>
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
                                        ~foreach from=$ddarr item=day`
                                                <option value="~$day`" ~if $day eq $curday1` selected ~/if`>~$day`</option>
                                        ~/foreach`
                                </select>
                                &nbsp;
                                <select name="month_r1" class="textboxes1">
                                        <option value="">Month</option>
                                       ~foreach from=$mmarr item=month`
											<option value="~$month['VALUE']`" ~if $month['VALUE'] eq $curmonth1` selected ~/if`>~$month['NAME']`</option>
										~/foreach`
                                </select>
                                &nbsp;
                                <select name="year_r1" class="textboxes1">
                                        <option value="">Year</option>
                                        ~foreach from=$yyarr item=year`
											<option value="~$year`" ~if $year eq $curyear1` selected ~/if`>~$year`</option>
										~/foreach`
                                </select>
				To
                                <select name="day_r2" class="textboxes1">
                                        <option value="">Day</option>
                                        ~foreach from=$ddarr item=day`
                                                <option value="~$day`" ~if $day eq $curday` selected ~/if`>~$day`</option>
                                        ~/foreach`
                                </select>
                                &nbsp;
                                <select name="month_r2" class="textboxes1">
                                        <option value="">Month</option>
                                        ~foreach from=$mmarr item=month`
											<option value="~$month['VALUE']`" ~if $month['VALUE'] eq $curmonth` selected ~/if`>~$month['NAME']`</option>
										~/foreach`
                                </select>
                                &nbsp;
                                <select name="year_r2" class="textboxes1">
                                        <option value="">Year</option>
                                       ~foreach from=$yyarr item=year`
											<option value="~$year`" ~if $year eq $curyear` selected ~/if`>~$year`</option>
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
