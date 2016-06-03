<table width=100% align=center cellspacing=10 cellpadding =10 border=3>
<tr class=fieldsnew>
<td><span style="color:blue">BLUE:</span> Sent</td>
<td><span style="color:green">GREEN:</span> Open</td>
<td><span style="color:red">RED:</span> Unsubscribe</td>
</tr>
</table>
<table width=100% align=center cellspacing=4 cellpadding=5 border=0>
<tr class=label>
<td> Mailer ID</td>
~foreach from=$loop_i item=i`
<td align=center>~$i`</td>
~/foreach`
<td align=center>Total</td>
</tr>

~foreach from=$loop_o key=k item=i`
<tr class=fieldsnew>
<td>~$mailer_name[$i]` (~$i`)</td>
~foreach from=$loop_i item=j`
<td align=center>~if $s`<span style="color:blue"> ~$mis[$i]["sent"][$j]` ~/if` ~if $o` <br><span style="color:green"> ~$mis[$i]["open"][$j]` ~/if` ~if $u` <br><span style="color:red"> ~$mis[$i]["uns"][$j]` ~/if`</td>
~/foreach`
<td align=center>~if $s`<span style="color:blue"> ~$sums[$i]` ~/if` ~if $o` <br><span style="color:green"> ~$sumo[$i]` ~/if` ~if $u` <br><span style="color:red"> ~$sumu[$i]` ~/if`</td>
</tr>
~/foreach`

<tr class=fieldsnew>
<td>Total</td>
~foreach from=$loop_i item=j`
<td align=center>~if $s`<span style="color:blue"> ~$tots[$j]` ~/if` ~if $o` <br><span style="color:green"> ~$toto[$j]` ~/if` ~if $u` <br><span style="color:red"> ~$totu[$j]` ~/if`</td>
~/foreach`
<td align=center>~if $s`<span style="color:blue"> ~$totals` ~/if` ~if $o` <br><span style="color:green"> ~$totalo` ~/if` ~if $u` <br><span style="color:red"> ~$totalu` ~/if`</td>
</tr>
</table>
<table>
<tr class=fieldsnew>
<td>YEAR: ~$year`</td>
<tr>
~if $month`
<tr class=fieldsnew>
<td>MONTH: ~$month`</td>
<tr>
~/if`
</table>
