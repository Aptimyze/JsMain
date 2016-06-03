<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>UserView : Jeevansathi.com Matrimonial Services</title>
<link rel="stylesheet" href="jeevansathi.css" type="text/css">
<link rel="stylesheet" href="../profile/images/styles.css" type="text/css">

<script language="javascript">
function validateProfiles()
{  
	document.getElementsByName('submit').value="Submit";
        var list = document.getElementsByName('pids[]');
        var selCount = 0;
        for(var i=0; i<list.length; i++){
		selVal = document.getElementsByName(list[i].value);
                if(selVal['0'].checked == true || selVal['1'].checked == true){
                        return false;
		}
        }
        if( selCount == 0 ){
                document.getElementById('errortopmsg').innerHTML ="Please select the profiles to mark them Valid/ Invalid.";
                return true;
        }
}   
</script>

</head>
~include file="head.htm"`

<body bgcolor="#FFFFFF" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">

<table width=100% cellspacing="1" cellpadding='3' ALIGN="CENTER" >
    <tr width=100% class="formhead">
          <td width=30% class="formhead" border=1><font><b>Welcome : ~$user`</b></font></td>
          <td width=25% class="formhead" border=1 align='CENTER'><a href="mainpage.php?cid=~$cid`">Click here to go to main page</a></td>
          <td width="15%" class="formhead" align="center">
          <td width=10% class="formhead" border=1 align='CENTER'><a href="logout.php?cid=~$cid`">Logout</a></td>
    </tr>
</table>
<br>
<br>

<!-- Form Starts -->
<form name="form1" action="invalid_phone_status.php" method="post" onSubmit="if( validateProfiles() )return false">
<input type="hidden" name=cid value="~$cid`">
<input type="hidden" name=user value="~$user`">
<div id="errortopmsg" style="color:red;font-weight:bold;text-align:center"></div>
  <table width=100% align="CENTER" >
~if $MSG neq ''`
    <tr align="CENTER">
	<td class="formhead" colspan="7" height="23"><b><font size="2" color="blue">~$MSG` </font></b></td>
    </tr>
    <tr align="CENTER">
	<td class="formhead" colspan="7" height="23">
 	<a href="invalid_phone_status.php?cid=~$cid`&user=~$user`"> Back </a>	
	</td>
    </tr>	
~else`
    <tr align="CENTER">
      <td class="formhead" colspan="8" height="23"><b><font size="4" color="blue">Report of Invalid Phones</font></b></td>
    </tr>
    <tr align="CENTER">
      <td class="label" width=5% height="20"> <b>S.No.</b></td>
      <td class="label" width=10% height="21"><b>Submitter </b></td>
      <td class="label" width=10% height="21"><b>Submittee </b></td>
      <td class="label" width=20% height="21"><b>Date</b></td>
      <td class="label" width=15% height="21"><b>Phone</b></td>	
      <td class="label" width=30% height="21"><b>Comments</b></td>
      <td class="label" width=5% height="21"><b>Valid</b></td>	
      <td class="label" width=5% height="21"><b>Invalid</b></td>	
      </tr>
    ~section name=index loop=$dataVal`

	<tr class="label" align="CENTER" bgcolor="#fbfbfb" class="~$dataVal[index].bandcolor`">
		<td height="20" align="CENTER" width="5%">	~$dataVal[index].SNo`	 	</td>
    		<td height="21" width="10%">			~$dataVal[index].submitterUser`	</td>
    		<td height="21" width="10%" align="LEFT">	~$dataVal[index].submitteeUser`	</td>
    		<td height="21" width="20%">			~$dataVal[index].date_time`	</td>
		<td height="21" width="15%">                  ~$dataVal[index].phoneNo`       </td>	
    		<td height="21" width="30%">			~$dataVal[index].comments`	</td>
		<input type="hidden" name="pids[]" value="~$dataVal[index].submittee`">
		<td height="20" align="CENTER" width="5%">			
			<input type="radio" name="~$dataVal[index].submittee`" value="1">
		</td>
		<td height="20" align="CENTER" width="10%">
			<input type="radio" name="~$dataVal[index].submittee`" value="2"> 	    
        	</td>
	</tr>
    ~/section`
  </table>

  <table width="100%">	
    <tr>
       <td height="23" class="formhead" align="center" width="100%">
	~if $dataVal[0].SNo neq ''`
       		<input type="submit" name="submit" value="Submit">
	~else`
		<font color="red">No profile having Invalid Phones exists .</font>
	~/if`
       </td>
    </tr>
 </table>
</form>
~/if`

 <table width="100%">	
    <tr bgcolor="#fbfbfb">
      <td colspan="7" height="21">&nbsp; </td>
    </tr>
    <tr>
      <td colspan="7" height="21">&nbsp; </td>
    </tr>
    <tr>
      <td width="8%" height="21">&nbsp; </td>
      <td height="21" width="11%" align="center"></td>
    </tr>	
  </table>

<!-- Ends Form -->

 <br><br>
~include file="foot.htm"`
</body>
</html>
