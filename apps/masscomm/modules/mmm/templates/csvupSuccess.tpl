~include_partial('global/header')`
<br>
<br>
<form action="csvup" method=post enctype="multipart/form-data">
<table width=80% align=center>
~if !$f`
<tr class=label>
<td>Select Mailer</td>
<td>
<select name=mailer_id>
 ~foreach from =$mailers item =i key = k`
		<option value = ~$k` ~if mail_id eq $k` selected ~/if`> ~$k`. ~$i` </option>
 ~/foreach` 
</select>
</td>
</tr>
<tr class=label>
<td><input type="file" name="csv"></td>
<td><input type="submit" name="submit" value="Submit"></td>
</tr>
~if $file`
<tr class=label>
<td><span style="color:red">~$file`</span></td>
</tr>
~/if`
~else`
<tr class=label>
<td><span style="color:green">~$file`</span></td>
</tr>
~/if`
</table>
</form>

