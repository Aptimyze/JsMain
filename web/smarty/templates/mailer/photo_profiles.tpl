		~assign var='kk' value=0`
        ~foreach from=$photo_profiles_inputs item=profile_id key=k`
                ~if $kk neq 0`
		<p></p>
                ~/if`
		<tr>
    <td width="20"></td>
    <td width="280">
    <table width="110" border="0" cellspacing="0" cellpadding="0" align="left">
  <tr>
    <td width="133"><a href="(LINK)PHOTO_ALBUM:profileid=~$profileid`,receiver_id=~$profile_id`(/LINK)" style="text-decoration:none; color:#0f529d;" target="_blank"><img border="0"  align="left" width="100" vspace="0" hspace="0" height="133" src="(PHOTO)PROFILE_PIC:receiver_id=~$profileid`,profileid=~$profile_id`,photo_type=search(/PHOTO)" style="border:1px solid #e6e6e6;"></a></td>
  
    
  </tr>
  <tr><td height="15"></td></tr>
  <tr>
    <td height=""></td>
  </tr>
</table>
<table width="150" border="0" cellspacing="0" cellpadding="0" style="font-family:Arial, Verdana; font-size:12px; color:#000000;-webkit-text-size-adjust: none; text-align:left;">
  <tr>
    <td width="5"></td>
    <td><var>{{AGE:profileid=~$profile_id`}}</var> yrs; <var>{{HEIGHT:profileid=~$profile_id`}}</var></td>
  </tr>
  <tr>
     <td width="5"></td>
    <td><var>{{RELIGION_CASTE_VALUE_TEMPLATE:profileid=~$profile_id`}}<var></td>
  </tr>
  <tr>
     <td width="5"></td>
    <td><var>{{MTONGUE:profileid=~$profile_id`}}</var></td>
  </tr>
  <tr>
     <td width="5"></td>
    <td><var>{{EDUCATION:profileid=~$profile_id`}}</var></td>
  </tr>
    <tr>
    <td width="5"></td>
    <td><var>{{OCCUPATION:profileid=~$profile_id`}}</td>
  </tr>
    <tr>
    <td width="5"></td>
    <td><var>{{INCOME:profileid=~$profile_id`}}</var></td>
  </tr>
    <tr>
   <td width="5"></td>
    <td><var>{{CITY_WITH_COUNTRY:profileid=~$profile_id`}}</var></td>
  </tr>
    <tr><td height="10"></td></tr>
</table>
    </td>
  </tr>
</table>
  </td>
  </tr>
  
  <tr>
    <td></td>
  </tr>
 
  <tr>
    <td><table  style="max-width:600px; min-width:240px" border="0" cellspacing="0" cellpadding="0">
  
</table></td>
  </tr>
  <tr>
   
    	<td height="5" style="border-bottom:1px solid #eae9e9"></td>
    
  </tr>
		~assign var='kk' value=$kk+1`
        ~/foreach`
