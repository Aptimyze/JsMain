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
	~foreach from=$mailer_idarr key=k item=v`
		<tr class=fieldsnew>
		<td>&nbsp;~$v`-~$mailer_arr[$k]` ~if $senttot[$k]`(<span class=green>~$senttot[$k]`</span>)</td>~/if`
		~foreach from=$ddarr key=dd item=vv`
			<td align=center>&nbsp;~$cnt[$k][$dd]`</td>
		~/foreach`
		<td align=center>&nbsp;~$tota[$k]`</td>
		</tr>
	~/foreach`
	<tr class=label>
	<td>&nbsp; Total</td>
	~foreach from=$ddarr key=dd item=v`
		<td align=center>&nbsp;~$totb[$dd]`</td>
	~/foreach`
	<td align=center>&nbsp;~$totall`</td>
	</table>
~else`
	~if $NOMODE`
	<p align="center"><font color="red">Please select atleast one mode!</font></p>
	~/if`
	<form action="openRateMIS" method=post>
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
        <td><input type=radio name=dt_type value="dayrange">Day Range</td>
        <td>
        <select name="dyy1">
        ~section name=yy loop=$yyarr`
        <option value="~$yyarr[yy]`">~$yyarr[yy]`</option>
        ~/section`
        </select>-
        <select name="dmm1">
        ~section name=mm loop=$mmarr`
        <option value="~$mmarr[mm]`">~$mmarr[mm]`</option>
        ~/section`
        </select>-
	<select name="ddd1">
        ~section name=dd loop=$ddarr`
        <option value="~$ddarr[dd]`">~$ddarr[dd]`</option>
        ~/section`
        </select>
	to
	<select name="dyy2">
        ~section name=yy loop=$yyarr`
        <option value="~$yyarr[yy]`">~$yyarr[yy]`</option>
        ~/section`
        </select>-
        <select name="dmm2">
        ~section name=mm loop=$mmarr`
        <option value="~$mmarr[mm]`">~$mmarr[mm]`</option>
        ~/section`
        </select>-
        <select name="ddd2">
        ~section name=dd loop=$ddarr`
        <option value="~$ddarr[dd]`">~$ddarr[dd]`</option>
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
