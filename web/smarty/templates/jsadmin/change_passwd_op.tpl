<HTML>
	<HEAD>
	<script language="JavaScript">
	function checkpasswd()
	{
		if(document.changepassword.old_passwd.value == "")
		{
        		alert("Please Enter Old Password");
        		return false;
		}
		if(document.changepassword.new_pwd.value =="") 
                {
                        alert("Please Enter New Password");
                        return false;
                }
		if(document.changepassword.confirm_new_pwd.value =="")                 
		{
                        alert("Please Confirm New Password");
                        return false;
                }

        return true;
	}
                                                                                                                             
</script>

	<TITLE>Change Operator's Password.</TITLE>
		<link rel="stylesheet" href="jeevansathi.css" type="text/css">
	</HEAD>
	<BODY>
	<br>
	<table width=760 cellspacing="1" cellpadding='0' ALIGN="CENTER" >
   <tr width=100% border=1>
    <td width=30% class="formhead" height="23" align="center"><font><b>Welcome</b></font></td>
    <td width=30% class="formhead" align='CENTER' height="23">
     <a href="mainpage.php?cid=~$cid`">Click here to go to mainpage</a>
    </td>
    <td width=30% class="formhead" align='CENTER' height="23">
     <a href="logout.php?cid=~$cid`">Logout</a>
    </td>
   </tr>
  </table>
	<br><br>
	<center>
	<p style="font-family:Verdana; font-size:10pt; font-color:#FCFFCF; font-weight: bold">CHANGE PASSWORD</p>
	<br>
	<table width="50%" cellspacing ="2" cellpadding= "2">
	<form name="changepassword" method="post" action="change_passwd_op.php">
	<tr class="formhead" height="23">
		<td colspan="2" align="center">~$msg`</td>
	</tr>
	~if $enter_passwd eq '1'`
	<tr class="formhead" height="23">
		<td align="left" width="40%"><font color="grey" style="font-weight:bold">&nbsp;&nbsp;Operator : ~$operator`</font></td>
		<td align="right" ></td></tr>
	<tr>
		<td width="50%"class="label" align="center">
		~if $isempty_old_passwd eq '1'`
			<font color ="red">
		~/if`	
		Enter Old password
		</td>
		<td class="fieldsnew" align="left" style="border:1px solid #000080">
		<input type = "password" name="old_passwd"  size="30" style ="background-color:#DEEFEF">
	<tr>
	<tr>
                <td class="label" align="center">
		~if $isempty_new_pwd eq '1'`
                        <font color ="red">
                ~/if`
		Enter New password</td>
                <td class="fieldsnew" align="left" style="border:1px solid #000080">
                <input type = "password" name="new_pwd" size="30" style ="background-color:#DEEFEF">
        <tr>
	<tr>
                <td class="label" align="center">
		~if $isempty_confirm_new_pwd eq '1'`
                        <font color ="red">
                ~/if`
		Confirm New password</td>
                <td class="fieldsnew" align="left" style="border:1px solid #000080">
                <input type = "password" name="confirm_new_pwd" size="30" style ="background-color:#DEEFEF" >
        <tr>
	<tr class="fieldsnew">
		<td align="center">&nbsp;</td>
		<td align="center">
		<input type="submit" name="submit2" value="Submit"  onclick="return checkpasswd();" style="border:1px bordercolor:black  font-family: Times; font-size: 9pt; font-weight: bold; background-color:#DEEFEF">
		<input type="hidden" name="cid" value="~$cid`">
                <input type="hidden" name="operator" value="~$operator`">
		</td>
	<tr>
	~else`
		<td width="50%"class="label" align="center">
		Select Operator</td>
		<td class="fieldsnew" align="left">
		<select name="operator">
		~section name= operators loop= $operator`
		<option value= "~$operator[operators]`">~$operator[operators]`</option>
		~/section`
		</select></td>
		</tr>
		<tr class="fieldsnew">
		<td align="center">&nbsp;</td>
		<td align="center">
		<input type="submit" name="submit1" value="Submit"  style="border:1px bordercolor:black  font-family: Times; font-size: 9pt; font-weight: bold; background-color:#DEEFEF">
		<input type="hidden" name="cid" value="~$cid`">
		</td>	
		</tr>
	~/if`	
	<table>
	</center>
	</BODY>
</HTML>

