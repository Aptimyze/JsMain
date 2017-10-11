~include_partial('global/header')`

<form name="form1" method="post" action="createTable2" >

<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
<tr>
  <td class="headbigblack" width="100%"><b>Select a mailer for table creation </b></td>
</tr>
<tr class="bgred">
   <td height="1"></td>
 <SPACER height="1" type="block"></SPACER> </tr>
<tr>
</table>
<br>



<table WIDTH="75%" BORDER="0" CELLSPACING="2" CELLPADDING="0" BORDERCOLOR="#2F3193" ALIGN="CENTER">

<tr><td><b>~$msg`</b></td></tr>
<tr>
<td colspan="2" class="label">
</td>

<tr> 
<td colspan="2" class="label"><b>Select Mailer</b>
</td>

<td bgcolor="#ffffcc" class="fieldsnew">
<select name="mailer_id">
        ~foreach from =$mailers item =i key =k`
		<option value = ~$k`> ~$k`. ~$i` </option>
	~/foreach`        
</select>
</td>
</tr>


<tr>
<td   colspan="2" align="center">
<input type="hidden" name="cid" value="~$cid`">
<input type="submit" name="submit" value="submit">
</td>
</tr>


</table>

