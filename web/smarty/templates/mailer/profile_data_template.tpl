~assign var='profile_id' value=$profile_data_template_inputs`
<table width="100%" border="0" cellspacing="2" cellpadding="0" style="font-family:Arial, Verdana; font-size:12px; color:#000000;-webkit-text-size-adjust: none;">
	<tr>
		<td width="107"><b>Age &amp; Height</b></td>
		<td width="5">:</td>
		<td><var>{{AGE:profileid=~$profile_id`}}</var> yrs; <var>{{HEIGHT:profileid=~$profile_id`}}</var></td>
	</tr>
	<tr>
		<td><b><var>{{RELIGION_CASTE_OR_SECT_LABEL:profileid=~$profile_id`}}</var></b></td>
		<td>:</td>
		<td><var>{{RELIGION_CASTE_VALUE_TEMPLATE:profileid=~$profile_id`}}</var></td>
	</tr>
	<tr>
		<td><b>Mother Tongue</b></td>
		<td>:</td>
		<td><var>{{MTONGUE:profileid=~$profile_id`}}</var></td>
	</tr>
	<tr>
		<td><b>Education</b></td>
		<td>:</td>
		<td><var>{{EDUCATION:profileid=~$profile_id`}}</var></td>
	</tr>
	<tr>
		<td><b>Occupation</b></td>
		<td>:</td>
		<td><var>{{OCCUPATION:profileid=~$profile_id`}}</var></td>
	</tr>
	<tr>
		<td><b>Income</b></td>
		<td>:</td>
		<td><var>{{INCOME:profileid=~$profile_id`}}</var></td>
	</tr>
	<tr>
		<td><b>Location</b></td>
		<td>:</td>
		<td><var>{{CITY_WITH_COUNTRY:profileid=~$profile_id`}}</var></td>
	</tr>
	<tr>
             	<td colspan="3" height="10"></td>
       	</tr>
     	<tr>
          	<td align="right" style="padding-right:10px;" colspan="3"><a href="(LINK)DETAILED_PROFILE_HOME:profileid=~$profileid`,receiver_id=~$profile_id`(/LINK)" style="text-decoration:underline; color:#0f529d;">View Full Profile</a></td>
       	</tr>
       	<tr>
           	<td colspan="3" height="10"></td>
      	</tr>
</table>
