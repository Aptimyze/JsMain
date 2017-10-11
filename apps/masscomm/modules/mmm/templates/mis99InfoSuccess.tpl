<table id = "misTable" border="1" style="width:300px">

<tr>
~foreach from=$columnName item=j`
<th>
~$j`
</th>
~/foreach`
</tr>

~foreach from=$mailerDataArr key=mailerId item=mailerData`
<tr>
    <td>~$mailerId`</td>
    <td>~$mailerData['MAILER_NAME']`</td>
    <td>~$mailerData['MAILER_TYPE']`</td>
    <td>~$mailerData['START_TIME']`</td>
    <td>~$mailerData['RECEIVER']`</td>
	<td>~$mailerData['F_EMAIL']`</td>
    <td>~$mailerData['SUBJECT']`</td>
	<td>~$mailerData['TARGET_CITY']`</td>
    <td>~$mailerData['SENT']`</td>
    <td>~$mailerData['TOTAL_OPEN']`</td>
    <td>~$mailerData['TOTAL_UNSUBSCRIBE']`</td>
    <td>~$mailerData['RESPONSE']`</td>
	<td>~$mailerData['OPEN_RATE']`</td>
    <td>~$mailerData['BROWSERURL']`</td>
</tr>
~/foreach`

</table>

