~include_partial('global/header')`

<form name="form1" method="post" action="mmm_create_table.php" >
<table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
       <td class="headbigblack"><b>Mail Type :</b> ~$mail_type` </td>
    </tr>
	<td class="headbigblack"><b>Mailer Id :</b> ~$mailer_id` </td>
    </tr>
	<td class="headbigblack"><b>Total No of Results :</b> ~$result_no` </td>
    </tr>
    <tr class="bgred">
       <td height="1"></td>
       <SPACER height="1" type="block"></SPACER> </tr>
    <tr>
</table>
<br><br><br>



<table WIDTH="100%" BORDER="0" CELLSPACING="2" CELLPADDING="0" BORDERCOLOR="#2F3193">
<tr> 
<td  width="49%" class="label" bgcolor="#F9F9F9">
<b>Please Enter the table spliting information in %</b>
</td>
</tr>
<tr>
~section name=i loop=$svarr`
~if $smarty.section.i.iteration ne 3`
<tr>
<td class=fieldsnew>
Server~$smarty.section.i.iteration` (~$iparr[i].ip`) <input type="text" name=~$svarr[i].spstr` size=10>
</td>
</tr>

~/if`
~if $smarty.section.i.iteration % 2 eq 0`
</tr><tr>
~/if`

~/section`
</tr>
<td  align="center" colspan="2">
<input type="hidden" name="mailer_id" value="~$mailer_id`" >
<input type="hidden" name="result_no" value="~$result_no`" >
<input type="hidden" name="cid" value="~$cid`">
<input type="submit" name="create_table" value="submit">
</td>
</tr>


</table>
</form>
