~include_partial('global/header')`

~if $successMsg`
        ~include_partial('global/successMsg',[successMsg=>$successMsg])`
~else`
	<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
	<tr>
		<td class="headbigblack">
			<b>Write Mailer Specifications </b>
		</td>
	</tr>

	<tr class="bgred">
		<td height="1" ></td>
		<SPACER height="1" type="block"></SPACER> 
	</tr>
		<!--tr-->
	</table>

	<br>                                                                                                 

	<form name="form1" method="post" action="hardcodeMail">
		<table WIDTH="100%" BORDER="0" CELLSPACING="2" CELLPADDING="0" BORDERCOLOR="#2F3193">
		<tr> 
			<td width="49%" class="label" bgcolor="#F9F9F9">
				<b>Select Mailer</b>
			</td>

			<td class="fieldsnew">
				<select name="mailer_id" onchange="myFunction()">
					<option value=""> From Mailers </option>
					~foreach from =$mailers item =i key=k`
						<option value = ~$k` ~if $id eq $k` selected ~else if $edit['mailer_id'] eq $k` selected ~/if`> ~$k`. ~$i` </option>
					~/foreach`        
				</select>
				<span class = red> &nbsp;&nbsp;~$errors["mailer_id"]`</span>
			</td>
		</tr>

		<tr>
			<td width="49%" class="label" bgcolor="#F9F9F9">
				<b>Enter Template Name </b>
			</td>

			<td class="fieldsnew">
				<input type ="text" name="template_name" ~if $mailinfo['TEMPLATE_NAME']` value="~$mailinfo['TEMPLATE_NAME']`" ~else if $edit['template_name']` value="~$edit['template_name']`" ~else` value = "~$templateName`" ~/if`>
				<span class = red> &nbsp;&nbsp;~$errors["template_name"]`</span>
			</td>
		</tr>

		<!--
		<tr>
			<td width="49%" class="label" bgcolor="#F9F9F9">
				<b>Enter view In Browser Url</b>
			</td>
			<td class="fieldsnew" >
				<input type ="text" name="browserUrl" ~if $mailinfo['BROWSERURL']` value=~$mailinfo['BROWSERURL']` ~else if $edit['browserUrl']` value=~$edit['browserUrl']` ~else` value="" ~/if` size="100">
				<span class = red> &nbsp;&nbsp;~$errors["browserUrl"]`</span>
			</td>	
		</tr>
		-->

		<tr>
			<td width="49%" class="label" bgcolor="#F9F9F9">
				<b>Enter Subject</b>
			</td>
			<td class="fieldsnew">
				<input type ="text" name="subject" ~if $mailinfo['SUBJECT']` value="~$mailinfo['SUBJECT']`" ~else if $edit['subject']` value="~$edit['subject']`" ~else` value="" ~/if` size="100">
				<span class = red> &nbsp;&nbsp;~$errors["subject"]`</span>
			</td>
		</tr>


		<tr>
			<td width="49%" class="label" bgcolor="#F9F9F9">
				<b>Enter Sender Email Id</b>
			</td>
			<td class="fieldsnew">
				<input type ="text" name="f_email" ~if $mailinfo['F_EMAIL']` value=~$mailinfo['F_EMAIL']` ~else if $edit['f_email']` value=~$edit['f_email']` ~else` value="" ~/if` size="100">
				<span class = red> &nbsp;&nbsp;~$errors["f_email"]`</span>
			</td>
		</tr>

		<tr>
			<td width="49%" class="label" bgcolor="#F9F9F9">
				<b>Enter Sender Name</b>
			</td>
			<td class="fieldsnew">
				<input type ="text" name="f_name" ~if $mailinfo['F_NAME']` value="~$mailinfo['F_NAME']`" ~else if $edit['f_name']` value="~$edit['f_name']`" ~else if $site eq "J"` value="jeevansathi.com" ~else` value= "99acres.com" ~/if` size="100">
				<span class = red> &nbsp;&nbsp;~$errors["f_name"]`</span>
			</td>
		</tr>

		<tr>
			<td width="49%" class="label" bgcolor="#F9F9F9">
				<b>Enter Stagger Period</b>
			</td>

			<td class="fieldsnew">
				<select name="stagger" id = "stagger">
				<option value='' ~if !$mailinfo['STAGGER'] && !$edit['stagger']` selected ~/if`> Select the Period</option>
				~for $k = 1 to MmmConfig::$stagger`
					<option value = ~$k` ~if $mailinfo['STAGGER'] eq $k` selected ~else if $edit['stagger'] eq $k` selected ~/if`> ~$k` </option>
				~/for`        
				</select>
				<span class = red> &nbsp;&nbsp;~$errors["stagger"]`</span>
			</td>
		</tr>

		<tr>
			<td width="49%" class="label" bgcolor="#F9F9F9">
				<b>Please enter the mail text along with the mail variables  i.e. for name write &#126;$name&#96; </b>
			</td>

			<td class="fieldsnew">
				<textarea name="data" rows="20", cols="70" ~if $mailinfo['DATA']`> ~$mailinfo['DATA']` ~else if $edit['data']`>~$edit['data']` ~else`> ~/if`</textarea>
				<span class = red> &nbsp;&nbsp;~$errors["data"]`</span>
			</td>
		</tr>

	
		<tr>
			<td class="fieldsnew">
				<input type="hidden" name="mail_type" value="1">
				<input type="submit" name="submit" value="submit">
				<input type="hidden" name="status" value="WM">
			</td>
		</tr>
	</form>
~/if`
