~include_partial('global/header')`
<br>
~if $flag eq '1'`
	<table width=100% align=center cellspacing=4 cellpadding=5 border=0>
	<tr class=label>
	<td>&nbsp; Mailer ID</td>
	~section name=dd loop=$ddarr`
		<td align=center>&nbsp;~$ddarr[dd]`</td>
	~/section`
	<td align=center>&nbsp;Total</td>
	</tr>
	~section name=mid loop=$mailer_idarr`
		<tr class=fieldsnew>
		<td>&nbsp;~$mailer_idarr[mid]`-~$mailer_arr[mid]` (<span class=green>~$senttot[mid]`</span>)</td>
		~section name=dd loop=$ddarr`
			<td align=center>&nbsp;~$cnt[mid][dd]`</td>
		~/section`
		<td align=center>&nbsp;~$tota[mid]`</td>
		</tr>
	~/section`
	<tr class=label>
	<td>&nbsp; Total</td>
	~section name=dd loop=$ddarr`
		<td align=center>&nbsp;~$totb[dd]`</td>
	~/section`
	<td align=center>&nbsp;~$totall`</td>
	</table>
~else`
	<form action="unsubscribeMIS" method=post>
	<table width=80% align=center>
        <tr>
        <td><input type=radio name=dt_type value="mnt">Month Wise</td>
        <td>
        <select name="myy">
        ~section name=yy loop=$yyarr`
        <option value="~$yyarr[yy]`">~$yyarr[yy]`</option>
        ~/section`
        </select>
        </td>
        </tr>
        <tr>
        <td><input type=radio name=dt_type value="day">Day Wise</td>
        <td>
        <select name="dyy">
        ~section name=yy loop=$yyarr`
        <option value="~$yyarr[yy]`">~$yyarr[yy]`</option>
        ~/section`
        </select>-
        <select name="dmm">
        ~section name=mm loop=$mmarr`
        <option value="~$mmarr[mm]`">~$mmarr[mm]`</option>
        ~/section`
        </select>
        </td>
        </tr>
	<tr>
        <td><input type=submit name=CMDGo value="  Go  "></td>
        <td>&nbsp;</td>
        </tr>
        </table>
	<input type=hidden name="cid" value="~$cid`">
	</form>
~/if`
<br>
</table>
</body>
