~include_partial('global/header')`

<form name="form1" method="post" action="setTestId" >

<input type="hidden" name="actionTaken" value="Delete">

<table width="70%" border="0" cellspacing="0" cellpadding="0" align="left">
	<tr>
		<td></td>
		<td align="right">
		<h2>TEST MAILID LIST</h2>
		</td>
	</tr>

	<tr>
		~foreach from = $sites key = k item = i`
			<td  colspan="2" align="center" class="label" bgcolor="#F9F9F9" ><b>~$i`</b>
			</td>
		~/foreach`
	</tr>
        <tr>
		<td colspan="2" align="center">
			<table style = "margin-left: 350px" width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
				~foreach from = $testIdPJ key = k item = i`
					<tr>
						<td colspan="4" align="justify" >
							<input type = "checkbox" name ="mailIdsPermanent[]"  value = ~$k` >~$i`
						</td>
					</tr>
				~/foreach`
			</table>
		</td>
		<td colspan="2" align="center">
			<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
				~foreach from = $testIdP9 key = k item = i`
					<tr>	
						<td colspan="4" align="justify" >
							<input type = "checkbox" name ="mailIdsPermanent[]"  value = ~$k` >~$i`
						</td>
					</tr>
				~/foreach`
	 		</table>
	 	</td>
	</tr>
	<tr>
		<td>
			~if $testIdT`		
				<table  width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
					<tr>
						<td >Temporary Ids for the Mailer Id ~$mailer_id`</td>
					</tr>
		  			~foreach from = $testIdT key = k item = i`
 					<tr>
						<td colspan="4" align="left" >
							<input type = "checkbox" name ="mailIdsTemporary[]"  value = ~$k` >~$i`
			    		     	</td>
					</tr>
					~/foreach`
				</table>
				<input type="hidden" name="mailer_id" value = ~$mailer_id`>
			~/if`
		</td>
	</tr>
	<tr>
 		<td>
			 <span style="margin-left:450x;">
			 	<input type = "submit" name ="submit" value = "Delete">
			 </span>
		</td>
	</tr>
</table>
</form>

                                                                                                 
<form name="form2" method="post" action="setTestId" id="form2">
	<input type="hidden" name="actionTaken" value="PermanentAdd">
	<table width="100%" border="0" cellspacing="0" cellpadding="0" align="left">
	<tr>
		<td>
			<span class="orange" >>>>&nbsp;Add Permanent Email Ids Comma Seperated &nbsp;>>></span>
			<input type = "textbox" name = "emailIds" id = "emailIdsP">
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<select name="site" id = "site">
			        	<option value=''> Site</option>
				        ~foreach from =$sites item =i key = k`
						<option value = ~$k`> ~$i` </option>
					~/foreach`        
				</select>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<input type = "submit" name = "submit" value = "Add">
		</td>
	</tr>
	</table>
</form>


<form name="form3" method="post" action="setTestId" id = "form3">
	<input type="hidden" name="actionTaken" value="TemporaryAdd">
	<table width="100%" border="0" cellspacing="0" cellpadding="0" align="left">
	<tr>
		<td>
			<span class=orange >>>>&nbsp;Add Temporary Email Ids Comma Seperated &nbsp;>>></span>
			<input type = "textbox" name = "emailIds" id = "emailIdsT">
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<select name="mailer_id" id = "mailer_id">
					<option value=''>Mailer Id</option>
					~foreach from =$mailers item =i key = k`
						<option value = ~$k` ~if $mailer_id eq $k` selected=selected ~/if`> ~$i` ~$k`</option>
					~/foreach`        
				</select>
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<input type = "submit" name = "submit" value = "Add">
		</td>

		</tr>
	</table>
</form>
