
<link rel="stylesheet" href="~sfConfig::get('app_img_url')`/jsadmin/jeevansathi.css" type="text/css">
<link rel="stylesheet" href="~sfConfig::get('app_img_url')`/profile/images/styles.css" type="text/css">




~include_partial('global/header')`
<br><br>
<form action="~$moduleurl`/NameLocationAgeSearch" method="post">
<input type="hidden" name=cid value="~$cid`">
<input type="hidden" name=username value="~$username`">
<table width="70%" border="0" cellspacing="1" cellpadding="4" align="center">
<tr class="fieldsnew">
	
	<td class="formhead" valign="middle" colspan="2" align="center" >Search User</td>
	</tr>

    	<!-- NAME -->
          <tr>
            <td width="28%" class="label" bgcolor="#F9F9F9" style="font-size:14px" align="right">Enter Name:
</td>

            <td width="30%" bgcolor="#F9F9F9">
              <input type="text" name="username" value="~if $username`~$username`~/if`" size="16" maxlength="40" class="textboxes1">
            </td>
            
             </tr>

             <!-- AGE -->
             <tr>
            <td width="28%" class="label" bgcolor="#F9F9F9" style="font-size:14px" align="right">Enter Age:
</td>

            <td width="30%" bgcolor="#F9F9F9">
              <input type="text" name="age" value="~if $age`~$age`~/if`" size="16" maxlength="2" class="textboxes1">
            </td>
            
             </tr>
             <!-- Address -->
             <tr>
            <td width="28%" class="label" bgcolor="#F9F9F9" style="font-size:14px" align="right">Enter Address:
</td>

            <td width="30%" bgcolor="#F9F9F9">
              <input type="text" name="address" value="~if $address`~$address`~/if`" size="40" maxlength="200" class="textboxes1">
            </td>
            
             </tr>

             <!-- Email -->
             <tr>
            <td width="28%" class="label" bgcolor="#F9F9F9" style="font-size:14px" align="right">Enter EmailId:
</td>

            <td width="30%" bgcolor="#F9F9F9">
              <input type="text" name="email" value="~if $email`~$email`~/if`" size="40" maxlength="100" class="textboxes1">
            </td>
            
             </tr>
             ~if $error`
             <tr valign="middle" align="center" >
	
              <td class="fieldsnew" colspan="2" style="width:50px;height:10px;color:red"><BR>~$error`</td></tr>~/if`
              
              ~if $legalDataCount eq 0`
				<tr valign="middle" align="center" >
	
              <td class="fieldsnew" colspan="2" style="width:50px;height:10px;color:red"><BR>~$noResultsFoundMsg`</td></tr>
				~/if`

              <tr valign="middle" align="center" >
	
              <td class="fieldsnew" colspan="2"><input type="submit" name="Go" value="  Search  " class="textboxes1" style="width:70px;height:30px;background:green;color:white"></td></tr>
			</tr> 
			

          
	</table>
	</form>
<br>
</form>


~if $legalDataCount neq 0`

<table width="100%" border="0" cellspacing="1" cellpadding="4" align="center">
	<tr class="fieldsnew">

		<td class="formhead" valign="middle" colspan="6" align="center" >Search Results</td>
	</tr>
	<tr>
		<td width="28%" class="label" bgcolor="#F9F9F9" style="font-size:14px" align="center">PROFILEID
		</td>
		<td width="28%" class="label" bgcolor="#F9F9F9" style="font-size:14px" align="center">NAME
		</td>
		<td width="28%" class="label" bgcolor="#F9F9F9" style="font-size:14px" align="center">EMAIL
		</td>
		<td width="28%" class="label" bgcolor="#F9F9F9" style="font-size:14px" align="center">AGE
		</td>
		<td width="28%" class="label" bgcolor="#F9F9F9" style="font-size:14px" align="center">CONTACT
		</td>
		<td width="28%" class="label" bgcolor="#F9F9F9" style="font-size:14px" align="center">PARENTS_CONTACT
		</td>
	</tr>
	~foreach from=$legalDataArr key=k item=dataArr`
	<tr>
	<td class="label" valign="middle" colspan="1" align="center"> ~$dataArr.USERNAME`</td>
	<td class="label" valign="middle" colspan="1" align="center"> ~$dataArr.NAME`</td>
	<td class="label" valign="middle" colspan="1" align="center"> ~$dataArr.EMAIL`</td>
	<td class="label" valign="middle" colspan="1" align="center"> ~$dataArr.AGE`</td>
	<td class="label" valign="middle" colspan="1" align="center"> ~$dataArr.CONTACT`</td>
	<td class="label" valign="middle" colspan="1" align="center"> ~$dataArr.PARENTS_CONTACT`</td>
	</tr>
	~/foreach`
</table>
~/if`

~include_partial('global/footer')`

