<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>AdminSearch : Jeevansathi.com Matrimonial Services</title>
<link rel="stylesheet" href="jeevansathi.css" type="text/css">
<link rel="stylesheet" href="../profile/images/styles.css" type="text/css">
<script src="~JsConstants::$jquery`"></script>

</head>
~include file="head.htm"`

<body bgcolor="#FFFFFF" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<form action="deletepage.php" method="post">
<input type="hidden" name=cid value="~$cid`">
<input type="hidden" name=pid value="~$pid`">
<input type="hidden" name=c value="~$c`">
<input type="hidden" name=user value="~$user`">
<input type="hidden" name=PAGE value="~$PAGE`">
<input type="hidden" name=grp_no value="~$grp_no`">
<input type="hidden" name=year1 value="~$year1`">
<input type="hidden" name=month1 value="~$month1`">
<input type="hidden" name=day1 value="~$day1`">
<input type="hidden" name=year2 value="~$year2`">
<input type="hidden" name=month2 value="~$month2`">
<input type="hidden" name=day2 value="~$day2`">
<input type="hidden" name=username value="~$username`">
<input type="hidden" name=email value="~$email`">
<input type="hidden" name=medit value="~$medit`">
<input type="hidden" name=val value="~$val`">
<input type="hidden" name=FROM value="~$FROM`">
<table width=100% cellspacing="1" cellpadding='3' ALIGN="CENTER" >
    <tr width=100% class="formhead">
          <td width=55% class="formhead" border=1><font><b>Welcome : ~$user`</b></font></td>
          <td width="15%" class="formhead" align="center">
          <td width=10% class="formhead" border=1 align='CENTER'><a href="logout.php?cid=~$cid`">Logout</a></td>
    </tr>
</table>
<br><br>
<table width="50%" border="0" cellspacing="1" cellpadding="4" align="center">
          <tr>
            <td class="formhead" valign="middle" colspan="2">&#155; Delete ~$c` records</td>
          </tr>
	~if $MSG`
	  <tr>
	    <td colspan=2 bgcolor="#F9F9F9" class="label" align="center"><font color="red">~$MSG`</font></td>						
	  </tr>	
	~/if`          
          <tr>
            <td width="30%" class="label" bgcolor="#F9F9F9">Reason</td>

            <td width="70%" bgcolor="#F9F9F9">

              <select class="selectReason" name="reason">
              <option disabled selected value> -- select an option -- </option>
              <option name="reasons" value=" Abusive / Indecent Language" > Abusive / Indecent Language</option>
                <option name="reasons" value="As per User Request">As per User Request</option>
                <option name="reasons" value="Conditions for Marriage / Dowry / Financial Support/ Immigration Support">Conditions for Marriage / Dowry / Financial Support/ Immigration Support</option>
                <option name="reasons" value="Below Age Limit">Below Age Limit</option>
                <option name="reasons" value="Competitor Website">Competitor Website</option>                
                <option name="reasons" value="Divorce Pending / Separated">Divorce Pending / Separated</option>
                <option name="reasons" value="Duplicate Profile">Duplicate Profile</option>
                <option name="reasons" value="Fake Profile">Fake Profile</option>
                <option name="reasons" value="Foreign Origin">Foreign Origin</option>
                <option name="reasons" value="Gender not Proper">Gender not Proper</option>
                <option name="reasons" value="Marital Status Doubtful / Second Marriage
">Marital Status Doubtful / Second Marriage
</option>
                <option name="reasons" value="Multiple Profile with same content
">Multiple Profile with same content
</option>
                <option name="reasons" value="Non Serious (Love/ Friendship / No Marriage Commitment)">Non Serious (Love/ Friendship / No Marriage Commitment)</option>
                <option name="reasons" value="Obscene Email ID
">Obscene Email ID
</option>
                <option name="reasons" value="Related to Carrier / Publicity">Related to Carrier / Publicity</option>
                <option name="reasons" value="Test Profile">Test Profile</option>
                <option name="reasons" value="Other" onClick="displayOtherField()">Other Reason</option>
              </select>		          
            </td>
            <tr id = "otherField" style="display: none">
            <td width="30%" class="label" bgcolor="#F9F9F9">Other Reason</td>
            <td width="70%" bgcolor="#F9F9F9">
            <input type="text" name="other" placeholder = "specify reason" size="18" class="textboxes1">
            </td>
          </tr>
          <tr>
            <td width="30%" class="label" bgcolor="#F9F9F9">Comments (Will not be a part of mail.)</td>

            <td width="70%" bgcolor="#F9F9F9">
        <textarea name="comments" class="testbox" cols="40" rows="2"></textarea>    
            </td>
          </tr>

          <tr valign="middle" align="Right">
              <td colspan="2"><input type="submit" name="Delete" value="  Delete  " class="textboxes1"></td></tr>
        </table>

</form>
~include file="foot.htm"`


</body>
</html>
<script>
$(document).ready(function(){
$(".selectReason").change(function(e)
{
  if(($( this ).val()) == "Other")
  {
    $("#otherField").show();
  }
  else
  {
    $("#otherField").hide();
  }
});
});
</script>