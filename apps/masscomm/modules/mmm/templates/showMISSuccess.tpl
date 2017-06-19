~include_partial('global/header')`
<table width="50%" border="0" cellspacing="0" cellpadding="0" align="center">
<tbody>
<tr>
       <td class="label" width="50%" align="center">MIS FOR <b>~$mailer_name`</b> MAILER</td>
</tr>
                                                                                                 
<tr>
        <td class="label" width="50%" align="center">Total Mails Sent=<b>~$no_of_sent` ~if $mailsToBeSent` (Approx. ~$mailsToBeSent`) ~/if` </b>for MAILER ID : <b>~$mailer_id`</b></td>
</tr>
<tr>
<td height="10">
</td>
</tr>

</tbody>
</table>


<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
<tr>
	<td class="label" width="10%" align="center"><b>S.No.</b></td><td  width="1%"></td>
        <td class="label" width="40%" align="center"><b>DATE</b></td><td  width="1%"></td>
        <td class="label" width="40%" align="center"><b>No. Of Mails Open</b></td><td  width="1%"></td>
        
</tr>
~assign var="c" value=1`
~foreach from=$mis key=k item=i`
<tr>
<td>
</td>
</tr>

<tr>
	<td  width="10%" bgcolor="#efefef" align="center">~$c`</td><td  width="1%"></td>
        <td  width="40%" bgcolor="#efefef" align="center">~$k`</td><td  width="1%"></td>
        <td  width="40%" bgcolor="#efefef" align="center" >~$i`</td><td  width="1%"></td>
</tr>
~assign var="c" value=$c+1`
~/foreach`

<tr>
<td height="40">
</td>
</tr>



</tbody>
</table>
