<HTML>
	<HEAD>
	<script language="JavaScript">
	function check_blank(n)
	{
		if(n==1)
		{
			if(document.mod_acc.oc_uname.value == "")
			{
				alert("Please Enter Offline Customer's Username");
				return false;
			}
		}
		else
		{
			if(document.mod_acc.oc_new_acc.value == "")
			{
				alert("Please Enter New Acceptances for Offline Customer");
				return false;
			}
			else
			{
				if(document.mod_acc.oc_new_acc.value%10!=0)
				{
					alert("Please enter the value in multiple of ten");
					return false;
				}
				else if(document.mod_acc.oc_new_acc.value<=0)
				{
					alert("Please enter valid value");
					return false;
				}
	
			}
		}

        return true;
	}
             
</script>
	<TITLE>Modify Acceptance</TITLE>
		<link rel="stylesheet" href="jeevansathi.css" type="text/css">
	</HEAD>
	<body>
	~include file="head.htm"`
	 <br>
	 <body bgcolor="#FFFFFF" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
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

	<br>
	<center>
	<p style="font-family:Verdana; font-size:10pt; font-color:#FCFFCF; font-weight: bold">MODIFY ACCEPTANCES</p>
	<br>
	<table width="50%" cellspacing ="2" cellpadding= "2">
	<form name="mod_acc" method="post" action="mod_acceptances.php">
	<tr class="formhead" height="23">
		<td colspan="2" align="center">~$msg`</td>
	</tr>
	~if  $enter_acc eq '1'`
	<tr class="formhead" height="23">
	<td colspan= "2" align="left" ><font color="grey" style="font-weight:bold">&nbsp;&nbsp;Offline Customer : ~$oc_uname`</font></td>
	
	</tr>
	<tr class="formhead" height="23">
	<td  align="left"><font color="grey" style="font-weight:bold">Acceptances Allowed : ~$oc_acc`</font></td>
	<td  align="left"><font color="grey" style="font-weight:bold">Acceptances Made : ~$oc_acc_made`</font></td>
	
	</tr>
	<tr>
		<td width="50%"class="label" align="center">
		~if $isempty_acceptances eq '1'`
			<font color ="red">
		~/if`	
		Enter Number of acceptances to be increased
		</td>
		<td class="fieldsnew" align="left" style="border:1px solid #000080">
		<input type="text" name="oc_new_acc"  value="" size="30" style ="background-color:#DEEFEF">
	</tr>
		<tr class="fieldsnew">
		<td align="center">&nbsp;</td>
		<td align="center">
		<input type="submit" name="submit2" value="Submit" onclick="return check_blank(2);"style="border:1px bordercolor:black  font-family: Times; font-size: 9pt; font-weight: bold; background-color:#DEEFEF">
		<input type="hidden" name="cid" value="~$cid`">
		<input type="hidden" name="oc_uname" value="~$oc_uname`">
		<input type="hidden" name="oc_acc" value="~$oc_acc`">
                </td>
	<tr>
	
	~else`
	
	<tr>
		<td width="50%"class="label" align="center">
		~if $isempty_ocuname eq '1'`
			<font color ="red">
		~/if`	
		Enter Username of Offline Customer
		</td>
		<td class="fieldsnew" align="left" style="border:1px solid #000080">
		<input type = "input" name="oc_uname"  size="30" style ="background-color:#DEEFEF">
	</tr>
		<tr class="fieldsnew">
		<td align="center">&nbsp;</td>
		<td align="center">
		<input type="submit" name="submit1" value="Submit" onclick="return check_blank(1);"style="border:1px bordercolor:black  font-family: Times; font-size: 9pt; font-weight: bold; background-color:#DEEFEF">
		<input type="hidden" name="cid" value="~$cid`">
                </td>
	<tr>
	~/if`
	<table>
	</center>
	</BODY>
</HTML>

