~include_partial('global/header')`

~if $successMsg`
	~include_partial('global/successMsg',[successMsg=>$successMsg])`
~else`
	<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
		<tr>
			<td class="headbigblack" width="100%">
				<b>Write Mailer Specifications </b>
			</td>
		</tr>

		<tr class="bgred">
			<td height="1"></td>
			<SPACER height="1" type="block"></SPACER>
		</tr>

		<tr>
	</table>

	<br>
		
	<form name="form1" method="post" action="/masscomm.php/mmm/urlMail" onsubmit="return validateForm();">
		<table WIDTH="75%" BORDER="0" CELLSPACING="2" CELLPADDING="0" BORDERCOLOR="#2F3193">
		<tr> 
			<td width="49%" class="label" bgcolor="#F9F9F9">
				<b>Select Mailer</b>
			</td>

			<td ALIGN="left"BGCOLOR="#F5F5F5" HEIGHT="15">
				<select name="mailer_id" onchange="myFunction()">
					<option value=""> From Mailers</option>
					~foreach from =$mailers item =i key = k`
						<option value = ~$k` ~if $id eq $k` selected ~else if $edit['mailer_id'] eq $k` selected ~/if`> ~$k`. ~$i` </option>
					~/foreach`        
				</select>
				<span class = red> &nbsp;&nbsp;~$errors["mailer_id"]`</span>
			</td>
		</tr>


		<tr>
			<td width="49%" class="label" bgcolor="#F9F9F9">
				<b>Enter Template Name Corresponding to this mailer</b>
			</td>

			<td class="fieldsnew" >
				<input type ="text" name="template_name" ~if $mailinfo['TEMPLATE_NAME']` value="~$mailinfo['TEMPLATE_NAME']`" ~else if $edit['template_name']` value="~$edit['template_name']`" ~else` value="~$templateName`" ~/if` maxlength="25">
				<span class = red> &nbsp;&nbsp;~$errors["template_name"]`</span>
			</td>
		</tr>

		<tr>
			<td width="49%" class="label" bgcolor="#F9F9F9">
				<b>Enter Url</b>
			</td>

			<td class="fieldsnew" >
				<input type ="text" name="browserUrl" ~if $mailinfo['BROWSERURL']` value="~$mailinfo['BROWSERURL']`" ~else if $edit['browserUrl']` value="~$edit['browserUrl']`" ~else` value="" ~/if` size="100">
				<span class = red> &nbsp;&nbsp;~$errors["browserUrl"]`</span>
			</td>
		</tr>


		<tr>
			<td width="49%" class="label" bgcolor="#F9F9F9">
				<b>Enter Subject</b>
			</td>

			<td class="fieldsnew" >
				<input type ="text" name="subject" ~if $mailinfo['SUBJECT']` value="~$mailinfo['SUBJECT']`" ~else if $edit['subject']` value="~$edit['subject']`" ~else` value="" ~/if` size="100">
				<span class = red> &nbsp;&nbsp;~$errors["subject"]`</span>
			</td>
		</tr>


		<tr>
			<td width="49%" class="label" bgcolor="#F9F9F9">
				<b>Enter Sender Email Id</b>
			</td>

			<td class="fieldsnew">
				<input type ="text" name="f_email" ~if $mailinfo['F_EMAIL']` value="~$mailinfo['F_EMAIL']`" ~else if $edit['f_email']` value="~$edit['f_email']`" ~else` value="" ~/if` size="100" maxlength="35">
				<span class = red> &nbsp;&nbsp;~$errors["f_email"]`</span>
			</td>
		</tr>

		<tr>
			<td width="49%" class="label" bgcolor="#F9F9F9">
				<b>Enter Sender Name</b>
			</td>
		
			<td class="fieldsnew">
				<input type ="text" name="f_name" ~if $mailinfo['F_NAME']` value="~$mailinfo['F_NAME']`" ~else if $edit['f_name']` value="~$edit['f_name']`" ~else if $site eq "J"` value="jeevansathi.com" ~elseif !$edit`value= "99acres.com" ~/if` size="100" maxlength="35">
				<span class = red> &nbsp;&nbsp;~$errors["f_name"]`</span>
			</td>
		</tr>

		<tr>
			<td width="40%" class="label" bgcolor="#F9F9F9">
                <b>Schedule Mailer</b>
            </td>

			<td colspan="2">
                 <input type="text" readonly="readonly" onclick="displayCalendar(this,'yyyy/mm/dd',this); closeDatePickerOnLayerClose();" id="rl_reminder_date" name="rl_reminder_date" style="background:url('/images/mmm_js/calendar.gif') 98% 2px no-repeat;width:100px;cursor:pointer;border:1px #ccc solid;" ~if $mailinfo['date']` value = "~$mailinfo['date']`" ~else if $edit['date']` value="~$edit['date']`" ~/if`>
			<input type="text" required="true" name="hour" id="hour" maxlength="2" size="1" style="width:25px;" placeholder = "HH" onblur="validateHour()" onkeypress="return isNumberKey(event)" ~if $mailinfo['hour']` value = "~$mailinfo['hour']`" ~else if $edit['hour']` value="~$edit['hour']`" ~/if`><b>:</b><input type="text" required="true" name="minute" id="minute" maxlength="2" size="1" style="width:25px;" placeholder = "MM" onblur="validateMinute()" onkeypress="return isNumberKey(event)" ~if $mailinfo['minute']` value = "~$mailinfo['minute']`" ~else if $edit['minute']` value="~$edit['minute']`" ~/if`>
				<span class = red> &nbsp;&nbsp;
					~if $errors["rl_reminder_date"]`  ~$errors["rl_reminder_date"]`
					~else if $errors["hour"]`  ~$errors["hour"]`
					~else if $errors["minute"]`  ~$errors["minute"]`
					~/if`
				</span>

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
                <span class = red> &nbsp;&nbsp;~$errors['stagger']`</span>
            </td>
        </tr>


		<tr>
			<td width="49%" class="label" bgcolor="#F9F9F9">
				<input type="hidden" name="mail_type" value="1">
				<input type="submit" name="submit" value="submit">
				<input type="hidden" name="data" value="">
				<input type="hidden" name="status" value="WM">
			</td>
		</tr>

		~if $overwrite eq 1`
		<tr>
			<td ALIGN="left"BGCOLOR="#F5F5F5" HEIGHT="15" colspan="2">
				<input type="submit" name="overwrite" value="Overwrite the Mail Data">
			</td>
		</tr>
		~/if`
	</form>
	</table>
~/if`
