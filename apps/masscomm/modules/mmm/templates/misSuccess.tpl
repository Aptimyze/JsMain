~include_partial('global/header')`
<br>
<br>
	<form action="MISInfo" method=post>
	<table width=80% align=center>
	<tr>
	<td>Mailers From</td>
	<td>
	<select name="site" onchange="reload()">
	<option value="" ~if $sites eq ""` selected ~/if`>All
	<option value="J" ~if $sites eq "J"` selected ~/if` >Jeevansathi
	<option value="9" ~if $sites eq "9"` selected ~/if` >99 Acres
	</select>
	</td>
	<td>
	<select name="mailer_id" ~if $l eq 0` style="visibility: hidden" ~/if`>
	<option value="">All
	~foreach from = $mailers item=i key=k`
	<option value=~$k`>~$k`.~$i`
	~/foreach`
	</select>
	</td>
	</tr>
	<tr>
	<td><input type=radio name=dt_type value="mnt" checked>Month Wise</td>
	<td>
	<select name="years_m">
	~foreach from=$years item=i key=k`
	<option value=~$i`>~$i`</option>
	~/foreach`	
	</select>
	</td>
	</tr>
	<tr>
	<td><input type=radio name=dt_type value="day">Day Wise</td>
	<td>
	<select name="years_d">
	~foreach from=$years item=i key=k`
	<option value=~$i`>~$i`</option>
	~/foreach`	
	</select>-
	<select name="months">
	~foreach from=$months item=i key=k`
	<option value=~$i`>~$i`</option>
	~/foreach`
	</select>
	</td>
	</tr>
	<tr><td><br></td></tr>
	<tr>
	<td>
	<input type="checkbox" name="sent" checked>Sent
	</td>
	<td>
	<input type="checkbox" name="open" checked>Open
	</td>
	<td>
	<input type="checkbox" name="unsubscribe" checked>Unsubscribe
	</td>
	</tr>
	<tr><td><br></td></tr>
	<tr>
	<td><input type=submit name=CMDGo value="  Go  "></td>
	<td>&nbsp;</td>
	</tr>
	</table>
	<input type=hidden name="cid" value="~$cid`">
	</form>
<br>
</table>
