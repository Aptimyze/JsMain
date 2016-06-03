<!doctype html public "-//w3c//dtd html 4.0 transitional//en">
<html>
 <head>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <title>JeevanSathi</title>
  <link rel="stylesheet" href="jeevansathi.css" type="text/css">
  <link rel="stylesheet" href="../profile/images/styles.css" type="text/css">
 </head>
 ~include file="head.htm"`
 <br>
 <body bgcolor="#FFFFFF" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
  <table width=100% cellspacing="1" cellpadding='0' ALIGN="CENTER" >
   <tr width=100% border=1>
    <td width="25%" class="formhead" height="23"><font><b>Welcome :~$username`</b></font></td>
    <td width="30%" class="formhead" align="center"><a href="mainpage.php?cid=~$cid`">Click here to go to main page</a></td>
    <td width="30%" class="formhead" align="center"><a href="add_photo_login.php?username=~$name`&cid=~$cid`">Add Offline Photos</a></td>
    <td width="6%" class="formhead" align='RIGHT' height="23">
	<a href="logout.php?cid=~$cid`">Logout</a>
    </td>
    <td width="3%" class="formhead" height="23">&nbsp;
    </td>
   </tr>
  </table>
<br>
<!--form action="photo_operator_bid.php" method=post>
<table width=80% align=center>
<tr>
<td align=center>Profile type
<select name=val>
        <option value="new">New</option>
        <option value="edit">Edit</option>
</select>
</td>
<td align=center>How many : 
<input type="text" name="num">
</td>
<td align=center>
<input type="submit" name="CMDBid" value="  Bid  ">
</td>
</tr>
</table>
<input type="hidden" name="cid" value="~$cid`">
</form-->
<br>
  <table width=100% align="CENTER" >
   <tr align="CENTER">
    <td class="formhead" colspan="7" height="23">
     <b>New photos To Be Screened</b>
    </td>
   </tr>
   <tr align="CENTER">
    <td class="label" width=5% height="20"><b>S.No.</b></td>      
    <td class="label" width=9% height="20"><b>Profile ID</b></td>
    <td class="label" width=10% align="left" height="20"><b>Customer's User Name</b></td>
    <td class="label" width=10% height="20"><b>Allot Time(IST)</b></td>
    <td class="label" width=10% height="20"><b>Submit Time(IST)</b></td>
    <td class="label" width=8% height="20"><b>Pictures</b></td>   
    <td class="label" width=12% height="20"><b>Remaining time</b></td>       
   </tr>
   ~if $newphotousersarr` 
	   ~section name=index loop=$newphotousersarr`
	    <tr align="CENTER" bgcolor="~$pcv[index].clr`" class="~$newphotousersarr[index].bandcolor`">
	     <td height="21" width="5%" >~$smarty.section.index.index_next`.</td>       
	     <td height="21" align="CENTER" width="9%" >~$newphotousersarr[index].PROFILEID`</td>
	     <td height="21" align="LEFT" width="10%" >~$newphotousersarr[index].USERNAME`</td>
	     <td height="21" width="10%" >~$newphotousersarr[index].RECV_DT`</td>
	     <td height="21" width="10%" >~$newphotousersarr[index].SUBMIT_DT`</td>
	     <td height="21" width="8%" ><a href="photo_display.php?username=~$username`&profileid=~$newphotousersarr[index].PROFILEID`&cid=~$cid`">view</a></td> 
	     <td height="21" width=12% class="~$newphotousersarr[index].status_color`">~$newphotousersarr[index].remaining_time`</td>
	    </tr>
	   ~/section`
   ~else`
	   	<tr><td  align="center" colspan=7><font color="red">
	   		There are no records to show
	   	</font></td></tr>
   ~/if`
   <tr bgcolor="#fbfbfb">
    <td colspan="7" height="21">
     &nbsp; 
    </td>
   </tr>
   <tr>
    <td colspan="7" height="21">
     &nbsp; 
    </td>
   </tr>    
  </table>
  <table width=100% align="CENTER" >
   <tr align="CENTER">
    <td class="formhead" colspan="7" height="23">
     <b>
      Edited Photos To Be Screened 
     </b>
    </td>
   </tr>
   <tr align="CENTER">
    <td class="label" width=5% height="20"><b>S.No.</b></td>      
    <td class="label" width=9% height="20"><b>Profile ID</b></td>
    <td class="label" width=10% align="left" height="20"><b>Customer's User Name</b></td>
    <td class="label" width=10% height="20"><b>Allot Time(IST)</b></td>
    <td class="label" width=10% height="20"><b>Submit Time(IST)</b></td>
    <td class="label" width=8% height="20"><b>Pictures</b></td>   
    <td class="label" width=12% height="20"><b>Remaining time</b></td>       
   </tr>
   ~if $editphotousersarr`
	   ~section name=index loop=$editphotousersarr`
	    <tr align="CENTER" bgcolor="~$pcv[index].clr`" class="~$editphotousersarr[index].bandcolor`">
	     <td height="21" width="5%" >~$smarty.section.index.index_next`.</td>       
	     <td height="21" align="CENTER" width="9%" >~$editphotousersarr[index].PROFILEID`</td>
	     <td height="21" align="LEFT" width="10%" >~$editphotousersarr[index].USERNAME`</td>
	     <td height="21" width="10%" >~$editphotousersarr[index].RECV_DT`</td>
	     <td height="21" width="10%" >~$editphotousersarr[index].SUBMIT_DT`</td>
	     <td height="21" width="8%" ><a href="photo_display.php?username=~$username`&profileid=~$editphotousersarr[index].PROFILEID`&cid=~$cid`">view</a></td>     
	     <td height="21" width=12% class="~$editphotousersarr[index].status_color`">~$editphotousersarr[index].remaining_time`</td>                   
	    </tr>
	   ~/section`
   ~else`
	   	<tr><td align="center" colspan=7><font color="red">
	   		There are no records to show
	   	</font></td></tr>
   ~/if`
   
   <tr bgcolor="#fbfbfb">
    <td colspan="7" height="21">
     &nbsp;
    </td>
   </tr>
   <tr>
    <td colspan="7" height="21">
     &nbsp;
    </td>
   </tr>    
  </table>
 </form> 
 <br><br><br><br><br><br><br>
 ~include file="foot.htm"`
 </body>
</html>
