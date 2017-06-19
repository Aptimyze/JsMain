<table width="760" border="0" cellspacing="0" cellpadding="0" align="center">
	<form action="/masscomm.php/mmm/formQuerySubmit" method="post" name="form1" id="form1">
		<tr>
			<td class=small vAlign=top width=10>
				<B>
					<SPACER type="block" width="10">
				</B>
			</td>

			<td width="571" valign="top">
				<table width="99%" border="0" cellspacing="0" cellpadding="0">
					<br>
					<br>
					<tr>

						<td class=label><b>Select Mailer By Mailer Name</b>
						</td>
						<td bgcolor="#ffffcc" class=fieldsnew>
							<select name="mailer_id" id = "mailer_id">
							<option value='' ~if !$edit['MAILER_ID']` selected ~/if`> Select a Mailername</option>
							~foreach from =$mailers item =i key = k`
							<option value = ~$k` ~if $edit['MAILER_ID'] eq $k` selected ~/if`> ~$k`. ~$i` </option>
							~/foreach`        
							</select>
						</td>
					</tr>

					<tr>
						<td height="8"></td>
						<SPACER height="8" type="block"></SPACER>
					</tr>

					<tr>
						<td height="8"></td>
						<SPACER height="8" type="block"></SPACER>
					</tr>

					<tr>
						<td class="headbigblack"><b>Compose Query </b></td>
					</tr>

					<tr class="bgred">
						<td height="1"></td>
						<SPACER height="1" type="block"></SPACER>
					</tr>

					<tr>
						<td height="8"></td>
						<SPACER height="8" type="block"></SPACER>
					</tr>
				</table>
			</td>
		</tr>

		<table width="98%" border="0" cellspacing="0" cellpadding="0">
			<tr>
				<td height="3" colspan="2"></td>
			</tr>

			<tr>
				<td height="3" colspan="2"></td>
				<SPACER height="3" type="block"></SPACER>
			</tr>

			<tr>
				<td width="96%" height="2" class=mediumblack> To select 
					multiple options press the +Ctrl key on your keyboard                          
				</td>
			</tr>

			<tr>
				<td width="96%" class=mediumblack> </td>
			</tr>
		</table>

		~*
		<!--/td>
		</tr-->	
		*`
		<tr>
			<td>
				<br>
				<table width="98%" border="0" cellspacing="4" cellpadding="2">
					<tr>
						<td class="mediumblack"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b>Type Of Mail</td>
						<td width="63%" class="mediumblack">
							~foreach from = $typeOfMail key = k item = i`
							<input type=radio value = ~$k` name="type" ~if $edit['TYPE'] eq $k` checked ~else if $k eq P` checked ~/if`> ~$i`
							&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
							~/foreach`
							<br>
					</tr>
					<tr>
						<td class=mediumblack colspan="2"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;1. 
							Select Partner's</b> 
						</td>
					</tr>
					<tr>
						<td class=mediumblack width="30%"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b>a. 
							Gender*
						</td>
						<td  class=mediumblack width="70%">
							<select name="gender" size="1" class="TextBox">
							<option value="" ~if !$edit['GENDER']` selected ~/if`>All</option>
							~foreach from = $gender key = k item=i`
							~assign var="tempVar" value =",~$edit['GENDER']`,"`
							~assign var="keyVar" value =",$k,"`
							<option value= ~$k` ~if $tempVar|contains:$keyVar` selected ~/if`> ~$i` </option>
							~/foreach`								
							</select>
						</td>
					</tr>
					<tr>
						<td class=mediumblack width="30%"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b>c. Religion/ Caste </td>
						<td width="70%"> 
							<select name="caste[]" size="4" multiple class="TextBox">
							<option value=""  ~if !$edit['CASTE']` selected ~/if`>All</option>
							~foreach from = $caste item=i key = k`
							~assign var="tempVar" value =",~$edit['CASTE']`,"`
							~assign var="keyVar" value =",$k,"`
							<option value= ~$k` ~if $tempVar|contains:$keyVar` selected ~/if`> ~$i` </option>
							~/foreach`
							</select>
						</td>
					</tr>
					<tr>
						<td class=mediumblack width="30%"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b>
							d. Manglik Status 
						</td>
						<td width="70%" class="mediumblack">
							<input type = "checkbox" name ="manglik[]"  value = '' class = "all" id = "manglik" ~if !$edit['MANGLIK']` checked ~/if` >All 
							~foreach from = $manglik item=i key = k`
							~assign var="tempVar" value =",~$edit['MANGLIK']`,"`
							~assign var="keyVar" value =",$k,"`
							<input type = "checkbox" name ="manglik[]"  value = ~$k` ~if $tempVar|contains:$keyVar` checked ~/if` > ~$i` &nbsp;&nbsp;&nbsp 
							~/foreach`
						</td>
					</tr>
					<tr>
						<td class=mediumblack width="30%"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b>e. Mother tongue/ ethnicity 
							(state of origin)
						</td>
						<td width="70%"> 
							<select name="mtongue[]" size="4" multiple class="TextBox">
							<option  value="" ~if !$edit['MTONGUE']` selected ~/if` >ALL</option>
							~foreach from = $mtongue item=i key=k`
							~assign var="tempVar" value =",~$edit['MTONGUE']`,"`
							~assign var="keyVar" value =",$k,"`
							<option value= ~$k` ~if $tempVar|contains:$keyVar` selected ~/if` > ~$i` </option>
							~/foreach`
							</select>
						</td>
					</tr>
					<tr>
						<td class=mediumblack width="30%"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b>f. Marital Status?</td>
						<td width="70%" class="mediumblack">
							<input type = "checkbox" name ="mstatus[]"  value = '' class = "all" id = "mstatus" ~if !$edit['MSTATUS']` checked ~/if`>All 
							~foreach from = $marital item=i key = k`
							~assign var="tempVar" value =",~$edit['MSTATUS']`,"`
							~assign var="keyVar" value =",$k,"`
							<input type = "checkbox" name = "mstatus[]" value = ~$k`  ~if $tempVar|contains:$keyVar` checked ~/if`> ~$i` &nbsp;&nbsp;&nbsp 
							~/foreach`
						</td>
					</tr>
					<tr>
						<td class=mediumblack width="30%"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b>g. 
							Has Children
						</td>
						<td class=mediumblack width="70%">
							<input type="checkbox" name="havechild[]" value="" class = "all" id ="havechild" ~if !$edit['HAVECHILD']` checked ~/if`>Doesn't Matter &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;
							~foreach from = $children item=i key = k`
							~assign var="tempVar" value =",~$edit['HAVECHILD']`,"`					
							~assign var="keyVar" value =",$k,"`
							<input type = "checkbox" name = "havechild[]" value= ~$k` ~if $tempVar|contains:$keyVar` checked ~/if`> ~$i`
							~/foreach`
						</td>
					</tr>
					<tr>
						<td class=mediumblack width="30%"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b>h. Age between ? </td>
						<td width="70%"><select name="min_age" class="TextBox">
							<option value = '' ~if !$edit['MIN_AGE']` selected ~/if`>Please Select </option>
							~foreach from = $age item=i key=k`
							<option value= ~$k` ~if $edit['MIN_AGE'] eq $k` selected ~/if`> ~$i` </option>
							~/foreach`
							</select>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;		
							<select name="max_age" class="TextBox">
							<option value = '' ~if !$edit['MAX_AGE']` selected ~/if`>Please Select </option>
							~foreach from = $age item=i key=k`
							<option value= ~$k` ~if $edit['MAX_AGE'] eq $k` selected ~/if`> ~$i` </option>
							~/foreach`
							</select>
						</td>
					</tr>
					<tr>
						<td class=mediumblack width="30%"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b>i. 
							Height between ?*
						</td>
						<td width="70%">
							<select name="min_height" size="1" class="TextBox">
							<option value = '' ~if !$edit['MIN_HEIGHT']` selected ~/if`>Please Select </option>
							~foreach from = $height key=k item=i`
							<option value= ~$k` ~if $edit['MIN_HEIGHT'] eq $k` selected ~/if`> ~$i`</option>
							~/foreach`
							</select>
							&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
							<select name="max_height" size="1" class="TextBox">
							<option value = '' ~if !$edit['MAX_HEIGHT']` selected ~/if`>Please Select </option>
							~foreach from = $height key = k item=i`
							<option value= ~$k` ~if $edit['MAX_HEIGHT'] eq $k` selected ~/if`> ~$i`</option>
							~/foreach`
							</select>
						</td>
					</tr>
				</table>
				<table width="98%" border="0" cellspacing="0" cellpadding="0">
					<tr>
						<td bgcolor="cccccc" height="1"></td>
						<SPACER height="1" type="block"></SPACER>
					</tr>
				</table>
				<br>
				<table width="98%"  border="0" cellpadding="2" cellspacing="4">
					<tr>
						<td colspan="2" class="mediumblack"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b><strong>2. Desired Attributes of Partner:</strong> <br>
							<b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b><b>&nbsp;&nbsp;&nbsp;&nbsp;</b>(Select ALL options which are acceptable)
						</td>
					</tr>
					<tr>
						<td width="30%" class="mediumblack"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b>a. Body Type*</td>
						<td class="mediumblack">
							<input type="checkbox" name=btype[]  value="" class = "all" id ="btype" ~if !$edit['BTYPE']` checked ~/if`>Any
							&nbsp;&nbsp;&nbsp;
							~foreach from = $btype key=k item=i`
							~assign var="tempVar" value =",~$edit['BTYPE']`,"`
							~assign var="keyVar" value =",$k,"`
							<input type = "checkbox" name ="btype[]"  value = ~$k` ~if $tempVar|contains:$keyVar` checked ~/if`>~$i`
							&nbsp;&nbsp;&nbsp;
							~/foreach`
						</td>
					</tr>
					<tr>
						<td width="30%" class="mediumblack"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b>b. Complexion?*</td>
						<td class="mediumblack">
							<input type="checkbox" name=complexion[]  value="" class = "all" id ="complexion" ~if !$edit['COMPLEXION']` checked ~/if`>Any
							&nbsp;&nbsp;&nbsp;
							~foreach from = $complexion key=k item=i`
							~assign var="tempVar" value =",~$edit['COMPLEXION']`,"`
							~assign var="keyVar" value =",$k,"`
							<input type = "checkbox" name ="complexion[]"  value = ~$k` ~if $tempVar|contains:$keyVar` checked ~/if`>~$i`
							&nbsp;&nbsp;&nbsp;
							~/foreach`
						</td>
					</tr>
					<tr>
						<td width="30%" class="mediumblack"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b>c. Diet*</td>
						<td class="mediumblack">
							<input type="checkbox" name=diet[]  value="" class = "all" id ="diet" ~if !$edit['DIET']` checked ~/if`>Doesn't Matter
							&nbsp;&nbsp;&nbsp;
							~foreach from = $diet key=k item=i`
							~assign var="tempVar" value =",~$edit['DIET']`,"`
							~assign var="keyVar" value =",$k,"`
							<input type = "checkbox" name ="diet[]"  value = ~$k` ~if $tempVar|contains:$keyVar` checked ~/if`>~$i`
							&nbsp;&nbsp;&nbsp;
							~/foreach`
						</td>
					</tr>
					<tr>
						<td width="30%" class="mediumblack"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b>d. Smoke?*</td>
						<td class="mediumblack">
							<input type="radio" name=smoke  value="" ~if !$edit['SMOKE']` checked ~/if`>Doesn't Matter
							&nbsp;&nbsp;&nbsp;
							~foreach from = $smoke key=k item=i`
							~assign var="tempVar" value =",~$edit['SMOKE']`,"`
							~assign var="keyVar" value =",$k,"`
							<input type="radio" name=smoke  value=~$k` ~if $tempVar|contains:$keyVar` checked ~/if`>~$i`
							&nbsp;&nbsp;&nbsp;
							~/foreach`
						</td>
					</tr>
					<tr>
						<td width="30%" class="mediumblack"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b>e. Drink?*</td>
						<td class="mediumblack">
							<input type="radio" name=drink  value="" ~if !$edit['DRINK']` checked ~/if`>Doesn't Matter
							&nbsp;&nbsp;&nbsp;
							~foreach from = $drink key=k item=i`
							~assign var="tempVar" value =",~$edit['DRINK']`,"`
							~assign var="keyVar" value =",$k,"`
							<input type="radio" name=drink  value=~$k` ~if $tempVar|contains:$keyVar` checked ~/if`>~$i`
							&nbsp;&nbsp;&nbsp;
							~/foreach`
						</td>
					</tr>
					<tr>
						<td width="30%" class="mediumblack"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b>f. Handicapped*</td>
						<td class="mediumblack">
							<input type="checkbox" name=handicapped[]  value="" class = "all" id ="handicapped" ~if !$edit['HANDICAPPED']` checked ~/if`>Any
							&nbsp;&nbsp;&nbsp;
							~foreach from = $handicapped key=k item=i`
							~assign var="tempVar" value =",~$edit['HANDICAPPED']`,"`
							~assign var="keyVar" value =",$k,"`
							<input type = "checkbox" name ="handicapped[]"  value = ~$k` ~if $tempVar|contains:$keyVar` checked ~/if`>~$i`
							&nbsp;&nbsp;&nbsp;
							~/foreach`
						</td>
					</tr>
				</table>
				<table width="98%"  border="0" cellspacing="0" cellpadding="0">
					<tr>
						<td height="1" bgcolor="cccccc"></td>
						<SPACER height="1" type="block"></SPACER>
					</tr>
				</table>
				<br>
				<table width="98%"  border="0" cellpadding="2" cellspacing="4">
					<tr>
						<td colspan="2" class="mediumblack"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b><strong>3. Desired Particulars of Partner: </strong><br>
							<b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b><b>&nbsp;&nbsp;&nbsp;&nbsp;</b>(Select ALL options which are acceptable)
						</td>
					</tr>
					<tr>
						<td width="30%" class="mediumblack"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b>a. Occupation*</td>
						<td class="mediumblack">
							<select name="occupation[]" multiple size="4" class="TextBox">
							<option value = '' selected ~if !$edit['OCCUPATION']` selected ~/if`>All </option>
							~foreach from = $occupation key=k item=i`
							~assign var="tempVar" value =",~$edit['OCCUPATION']`,"`
							~assign var="keyVar" value =",$k,"`
							<option value= ~$k` ~if $tempVar|contains:$keyVar` selected ~/if`> ~$i` </option>
							~/foreach`
							</select>
						</td>
					</tr>
					<tr>
						<td class="mediumblack"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b>b. Country of Residence*</td>
						<td class="mediumblack">
							<select name="country_res[]" multiple size="4"  class="TextBox">
							<option value = '' ~if !$edit['COUNTRY_RES']` selected ~/if`>All </option>
							~foreach from = $country key=k item=i`
							~assign var="tempVar" value =",~$edit['COUNTRY_RES']`,"`
							~assign var="keyVar" value =",$k,"`
							<option value= ~$k` ~if $tempVar|contains:$keyVar` selected ~/if`> ~$i` </option>
							~/foreach`
							</select>
						</td>
					</tr>
					<tr>
						<td class="mediumblack"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b>c. Country of Birth*</td>
						<td class="mediumblack">
							<select name="country_birth[]" multiple size="4"  class="TextBox">
							<option value = '' ~if !$edit['COUNTRY_BIRTH']` selected ~/if`>All </option>
							~foreach from = $country key=k item=i`
							~assign var="tempVar" value =",~$edit['COUNTRY_BIRTH']`,"`
							~assign var="keyVar" value =",$k,"`
							<option value= ~$k` ~if $tempVar|contains:$keyVar` selected ~/if`> ~$i` </option>
							~/foreach`
							</select>
						</td>
					</tr>
					<tr>
						<td class="mediumblack"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b>d. City/State of residence*</td>
						<td><select name="city_res[]" multiple  size="4"  class="TextBox">
							<option value="" ~if !$edit['CITY_RES']` selected ~/if` >ALL</option>
							~foreach from=$city key =k item=i`
							~assign var="tempVar" value =",~$edit['CITY_RES']`,"`
							~assign var="keyVar" value =",$k,"`
							<option value= ~$k` ~if $tempVar|contains:$keyVar` selected ~/if`> ~$i` </option>
							~/foreach`
							</select>
						</td>
					</tr>
					<tr>
						<td class="mediumblack"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b>e. Residency status*</td>
						<td class="mediumblack"> <select name="res_status[]" multiple size="6" class="TextBox">
							<option value="" ~if !$edit['RES_STATUS']` selected ~/if`>ALL</option>
							~foreach from=$rstatus key =k item=i`
							~assign var="tempVar" value =",~$edit['RES_STATUS']`,"`
							~assign var="keyVar" value =",$k,"`
							<option value= ~$k` ~if $tempVar|contains:$keyVar` selected ~/if`> ~$i` </option>
							~/foreach`
							</select>
						</td>
					</tr>
					<tr>
						<td class="mediumblack"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b>f. Education level*</td>
						<td class="mediumblack">
							<select name="edu_level[]" multiple size="4" class="TextBox">
							<option value = '' ~if !$edit['EDU_LEVEL']` selected ~/if`>All </option>
							~foreach from = $education key=k item=i`
							~assign var="tempVar" value =",~$edit['EDU_LEVEL']`,"`
							~assign var="keyVar" value =",$k,"`
							<option value= ~$k` ~if $tempVar|contains:$keyVar` selected ~/if`> ~$i` </option>
							~/foreach`
							</select>
						</td>
					</tr>
					<tr>
						<td class="mediumblack"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b>g. Relation of person*</td>
						<td class="mediumblack"> 
							<select name="relation[]" multiple size="7" class="TextBox">
							<option value="0" ~if !$edit['RELATION']` selected ~/if`>ALL</option>
							~foreach from= $relation key=k item=i`
							~assign var="tempVar" value =",~$edit['RELATION']`,"`
							~assign var="keyVar" value =",$k,"`
							<option value= ~$k` ~if $tempVar|contains:$keyVar` selected ~/if`> ~$i` </option>
							~/foreach`
							</select>
						</td>
					</tr>
				</table>
				<table width="98%"  border="0" cellspacing="0" cellpadding="0">
					<tr>
						<td bgcolor="cccccc"></td>
						<SPACER height="1" type="block"></SPACER>
					</tr>
				</table>
				<table width="98%"  border="0" cellspacing="4" cellpadding="2">
					<tr>
						<td colspan="2">&nbsp;&nbsp;&nbsp;&nbsp;<b class="mediumblack">4.Show Photos,Incomplete,Paid,Residence,Mobile </b>
						<td>
					</tr>
					<tr>
						<td class="mediumblack"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b>a. Profiles with photos?</td>
						<td width="63%" class="mediumblack"> 
							<input type=radio value="" name="havephoto" ~if !$edit['HAVEPHOTO']` checked ~/if`>
							All&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
							~foreach from = $photo_a key=k item =i`
							~assign var="tempVar" value =",~$edit['HAVEPHOTO']`,"`
							~assign var="keyVar" value =",$k,"`
							<input type="radio" name="havephoto"  value=~$k` ~if $tempVar|contains:$keyVar` checked ~/if`>~$i`
							~/foreach`
						</td>
						<br>
					</tr>
					<tr>
						<td class="mediumblack"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b>b. Incomplete Profile?</td>
						<td width="63%" class="mediumblack">
							<input type=radio value="" name="incomplete" ~if !$edit['INCOMPLETE']` checked ~/if`>
							All&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
							~foreach from = $yesNo key = k item = i`
							<input type=radio value=~$k` name="incomplete" ~if $edit['INCOMPLETE'] eq $k` checked ~/if`>
							~$i`&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
							~/foreach`
						</td>
						<br>
					</tr>
					<tr>
						<td class="mediumblack"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b>c. Show Residence Phone No?</td>
						<td width="63%" class="mediumblack">
							<input type=radio value="" name="showphone_res" ~if !$edit['SHOWPHONE_RES']` checked ~/if`>
							All&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
							~foreach from = $yesNo key = k item = i`
							<input type=radio value=~$k` name="showphone_res" ~if $edit['SHOWPHONE_RES'] eq $k` checked ~/if`>
							~$i`&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
							~/foreach`
						</td>
						<br>
					</tr>
					<tr>
						<td class="mediumblack"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b>d. Show Mobile No?</td>
						<td width="63%" class="mediumblack">
							<input type=radio value="" name="showphone_mob" ~if !$edit['SHOWPHONE_MOB']` checked ~/if`>
							All&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
							~foreach from = $yesNo key = k item = i`
							<input type=radio value=~$k` name="showphone_mob" ~if $edit['SHOWPHONE_MOB'] eq $k` checked ~/if`>
							~$i`&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
							~/foreach`
						</td>
						<br>
					</tr>
					<tr>
						<td class="mediumblack"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b>d. Paid Member?</td>
						<td width="63%" class="mediumblack">
							<input type=radio value="" name="subscription" ~if !$edit['SUBSCRIPTION']` checked ~/if`>
							All&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
							~foreach from = $paid key = k item = i`
							<input type=radio value=~$k` name="subscription" ~if $edit['SUBSCRIPTION'] eq $k` checked ~/if`>
							~$i`&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
							~/foreach`
						</td>
						<br>
					</tr>
					<tr>
						<table width="98%"  border="0" cellspacing="0" cellpadding="0">
							<tr>
								<td bgcolor="cccccc"></td>
								<SPACER height="1" type="block"></SPACER>
							</tr>
						</table>
					</tr>
					<br>
				</table>
				<table width="98%"  border="0" cellspacing="4" cellpadding="2">
					<tr>
						<td colspan="2">&nbsp;&nbsp;&nbsp;&nbsp;<b class="mediumblack">5. Income</b>
						<td>
					</tr>
					<tr>
						<td></td>
						<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b class="mediumblack">From</b></td>
						<td><b class="mediumblack">To</b></td>
					</tr>
					<tr>
						<td class=mediumblack width="34%"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b>Annual income</td>
						<td class=mediumblack  width="20%">Rs.
							<select name="lincome" size= "1" class="textbox">
							<option value='' ~if $edit['LINCOME'] eq ''` selected ~/if`>Please Select</option>
							~foreach from = $lincome key = k item=i`
							<option value= ~$k` ~if $edit['LINCOME'] eq $k` selected ~/if` > ~$i`</option>
							~/foreach`
							</select>
						</td>
						<td class=mediumblack  width="20%">
							<select name="hincome" size= "1" class="textbox">
							<option value="" ~if $edit['HINCOME']` selected ~/if`>Please Select </option>
							~foreach from = $hincome key=k item=i`
							~if $i != ""`
							<option value= ~$k` ~if $edit['HINCOME'] eq $k` selected ~/if`> ~$i`</option>
							~/if`
							~/foreach`
							</select>
						</td>
					</tr>
					<tr>
						<td class=mediumblack width="34%"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b>
							~if $check_income eq "Y"`<font color="red"></font> ~else` ~/if`
						</td>
						<td class=mediumblack  width="20%">$&nbsp;&nbsp;&nbsp;
							<select name="lincome_dol" size= "1" class="textbox">
							<option value="" ~if $edit['LINCOME_DOL'] eq ''` selected ~/if`>Please Select </option>
							~foreach from = $lincome_dol key=k item=i`
							<option value= ~$k+1` ~if $edit['LINCOME_DOL'] eq $k+1` selected ~/if`> ~$i`</option>
							~/foreach`
							</select>
						</td>
						<td class=mediumblack  width="20%">
							<select name="hincome_dol" size= "1" class="textbox">
							<option value="" ~if !$edit['HINCOME_DOL']` selected ~/if`>Please Select </option>
							~foreach from = $hincome_dol key=k item=i`
							<option value= ~$k` ~if $edit['HINCOME_DOL'] eq $k` selected ~/if`> ~$i`</option>
							~/foreach`
							</select>
						</td>
					</tr>
					<tr>
						<table width="98%"  border="0" cellspacing="0" cellpadding="0">
							<tr>
								<td bgcolor="cccccc"></td>
								<SPACER height="1" type="block"></SPACER>
							</tr>
						</table>
					</tr>
				</table>
				<table width="98%"  border="0" cellspacing="4" cellpadding="2">
					<tr>
						<td colspan="2" >&nbsp;&nbsp;&nbsp;&nbsp;<b class="mediumblack">6. Based on date :</b></td>
						<td colspan="2" >&nbsp;&nbsp;&nbsp;&nbsp;</td>
					</tr>
					<tr>
						<td class="mediumblack" width="25%"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b>a.Entry Date &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Between</td>
						<td class="mediumblack" colspan=3>
							<input type="text" name="entry_dt1" class="tcal" ~if $edit['ENTRY_DT1']`value=~$edit['ENTRY_DT1']` ~else` value="" ~/if`/> 
							&nbsp;&nbsp;&nbsp;&nbsp;
							<input type="text" name="entry_dt2" class="tcal" ~if $edit['ENTRY_DT2']`value=~$edit['ENTRY_DT2']` ~else` value="" ~/if`/>
						</td>
					</tr>
					<tr>
						<td class="mediumblack" width="25%"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b>a.Modify Date &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Between</td>
						<td class="mediumblack" colspan=3>
							<input type="text" name="modify_dt1" class="tcal" ~if $edit['MODIFY_DT1']`value=~$edit['MODIFY_DT1']` ~else` value="" ~/if`/>
							&nbsp;&nbsp;&nbsp;&nbsp;
							<input type="text" name="modify_dt2" class="tcal" ~if $edit['MODIFY_DT2']`value=~$edit['MODIFY_DT2']` ~else` value="" ~/if`/>
						</td>
					</tr>
					<tr>
						<td class="mediumblack" width="25%"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b>a.LastLogin Date &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Between</td>
						<td class="mediumblack" colspan=3>
							<input type="text" name="lastlogin_dt1" class="tcal" ~if $edit['LASTLOGIN_DT1']`value=~$edit['LASTLOGIN_DT1']` ~else` value="" ~/if`/>
							&nbsp;&nbsp;&nbsp;&nbsp;
							<input type="text" name="lastlogin_dt2" class="tcal" ~if $edit['LASTLOGIN_DT2']`value=~$edit['LASTLOGIN_DT2']` ~else` value="" ~/if` />
						</td>
					</tr>
					<tr>
						<td colspan="2" >&nbsp;&nbsp;&nbsp;&nbsp;<b class="mediumblack">7. Limit on Number of Results :</b></td>
						<td colspan="2" >&nbsp;&nbsp;&nbsp;&nbsp;
							<input type ="text" name="upper_limit"  id = "upper_limit"  ~if $edit['UPPER_LIMIT']` value = ~$edit['UPPER_LIMIT']` ~/if`>
						</td>
					</tr>
				</table>
		<tr>
			<table width="98%"  border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td bgcolor="cccccc"></td>
					<SPACER height="1" type="block"></SPACER>
				</tr>
			</table>
		</tr>
		<table>
			<tr>
				<td width="25%">&nbsp;</td>
				<td class="mediumblack"><br>
					<input type="hidden" name="fsubmit" value="fsubmit">
					<input type="hidden" name="site" value="J">
					<input type="image" name="fsubmit" value="fsubmit"  src="http://www.jeevansathi.com/profile/images/submit_button.gif" width="76" height="23">
				</td>
			</tr>
		</table>
	</td>
	</tr>
	</form>
</table>
