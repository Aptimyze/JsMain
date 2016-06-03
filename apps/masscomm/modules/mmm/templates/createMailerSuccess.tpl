~include_partial('global/header')`

~if $successMsg`
	<table width="100%" border="0" cellspacing="1" cellpadding="4" align="center">
		<br>
		<br>
		<br>
		<tr>
			<td colspan="4" align="center">
				<h2>A New Mailer with following specifications has been created</h2>
			</td>
		</tr>

		 <tr>
			 <td class="formhead" valign="middle" colspan="2">
				&#155;Mailer Name 
			</td>
			 <td class="formhead" valign="middle" colspan="2">
			~$mail["MAILER_NAME"]`
			 </td>
		 </tr>


		 <tr>
			 <td class="formhead" valign="middle" colspan="2">
				&#155;Client Name 
			 </td>
			 <td class="formhead" valign="middle" colspan="2">
				~$mail["CLIENT_NAME"]`
			 </td>
		 </tr> 

		 <tr>
			 <td class="formhead" valign="middle" colspan="2">
				&#155;Company Name 
			 </td>
			 <td class="formhead" valign="middle" colspan="2">
				~$mail["COMPANY_NAME"]`
			 </td>
		 </tr> 

		 <tr>
			 <td class="formhead" valign="middle" colspan="2">
				&#155;Creation Time 
			 </td>
			 <td class="formhead" valign="middle" colspan="2">
				~$mail["CTIME"]`
			 </td>
		 </tr>

		 <tr>
			<td class="formhead" valign="middle" colspan="2">&#155;Mailer Id </td>
			<td class="formhead" valign="middle" colspan="2">
				~$mail["MAILER_ID"]`
			</td>
		 </tr>
	</table>
	<br>
	<br>
	~$message`
~else`
	<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
	<tr>
	<td colspan="4" align="center">
	<span class=red >>>>&nbsp;&nbsp;Create New Mailer By Entering Details&nbsp;&nbsp;>>></h2>
	</td>
	</tr>
	</table>
	<table width="90%" border="0" cellspacing="0" cellpadding="0" align="center">
	<tr>
		<td class="headbigblack">
			<b>Create Mailer </b>
		</td>
	</tr>

	<tr class="bgred">
		<br>
			<td height="1"></td>
			<SPACER height="1" type="block"></SPACER>
		</tr>
	</table>
	<form name="form1" method="post" action="/masscomm.php/mmm/createMailer" >
		<table WIDTH="75%" BORDER="0" CELLSPACING="2" CELLPADDING="0" BORDERCOLOR="#2F3193" ALIGN="center">
		<br><br>
			<tr>
				<td width="49%" class="label" bgcolor="#F9F9F9" >
					<b>Name Of Mailer</b>
				</td>

				<td class=fieldsnew>
					<input type= "textbox" name="mailer_name" value = "~$edit['mailer_name']`" maxlength="40">
					<span class = red> &nbsp;&nbsp;~$errors['mailer_name']`</span>
				</td>
			</tr>

			<tr> 
				<td width="49%" class="label" bgcolor="#F9F9F9">
					<b>Enter Client Name</b>
				</td>

				<td class=fieldsnew>
					<input type= "textbox" name="client_name" value = "~$edit['client_name']`" maxlength="40">
					<span class = red> &nbsp;&nbsp;~$errors['client_name']`</span>
				</td>
			</tr>


			<tr>
				<td width="49%" class="label" bgcolor="#F9F9F9">
					<b>Type Of Mail</b>
				</td>

				<td class=fieldsnew>
					<select name="mail_type">
						~foreach from=MmmConfig::$mailerType item=value key=key`
							<option value="~$key`" >~$value`</option>
						~/foreach`
					</select>
				</td>
			</tr>


			<tr>
				<td width="49%" class="label" bgcolor="#F9F9F9">
					<b>Response Type</b>
				</td>

				<td class=fieldsnew>
					<select name="response_type">
						~foreach from=MmmConfig::$responseType item=value key=key`
							<option value="~$key`" >~$value`</option>
						~/foreach`
					</select>
				</td>
			</tr>

			<tr>
				<td width="49%" class="label" bgcolor="#F9F9F9">
					<b>Company Name</b>
				</td>
				<td class=fieldsnew><input type="text" name=company value = "~$edit['company']`" maxlength="40">
				<span class = red> &nbsp;&nbsp;~$errors['company']`</span>
				</td>
			</tr>

			<tr>
				<td width="49%" class="label" bgcolor="#F9F9F9">
					<b>Period of stay</b>
				</td>

				<td class=fieldsnew>
					<select name=pos >
						~foreach from=MmmConfig::$mailerPeriodOfStay item=value key=key`
							<option value="~$key`" >~$value`</option>
						~/foreach`
					</select>
				</td>
			</tr>

			<tr>
				<td width="49%" class="label" bgcolor="#F9F9F9">
					<b>Mailer For</b>
				</td>

				<td class=fieldsnew>
					~foreach from=MmmConfig::$mailerWebsite item=value key=key`
					<input type=radio name="mailer_for" value=~$key` ~if $key eq 'J'` checked ~/if` > ~$value`
					~/foreach`
				</td>
			</tr>

			<tr>
				<td align="center" colspan="2" class=fieldsnew>
					<input type="hidden" name="cid" value="~$cid`">
					<input type="submit" name="submit" value="Submit">
				</td>
			</tr>
		<table>
	</form>
~/if`	
