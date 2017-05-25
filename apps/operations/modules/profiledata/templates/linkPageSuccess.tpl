
<link rel="stylesheet" href="~sfConfig::get('app_img_url')`/jsadmin/jeevansathi.css" type="text/css">
<link rel="stylesheet" href="~sfConfig::get('app_img_url')`/profile/images/styles.css" type="text/css">



<input type="hidden" name=cid value="~$cid`">
~include_partial('global/header')`
<br>
<form action="~$moduleurl`/linkPage" method="post">
<input type="hidden" name=cid value="~$cid`">
<input type="hidden" name=username value="~$username`">
<table width="70%" border="0" cellspacing="1" cellpadding="4" align="center">
<tr class="fieldsnew">
	
	<td class="formhead" valign="middle" colspan="2" align="center" >Show Profile History</td>
	</tr>

    
          <tr>
            <td width="28%" class="label" bgcolor="#F9F9F9" style="font-size:14px" align="right">Enter User Name/Email:
</td>

            <td width="30%" bgcolor="#F9F9F9">
              <input type="text" name="username" value="~if $username`~$username`~/if`" size="16" maxlength="40" class="textboxes1">
            </td>
            
             </tr>
             ~if $username`
             <tr valign="middle" align="center" >
	
              <td class="fieldsnew" colspan="2" style="width:50px;height:10px;color:red"><BR>~$error`</td></tr>~/if`
              <tr valign="middle" align="center" >
	
              <td class="fieldsnew" colspan="2"><input type="submit" name="Go" value="  Search  " class="textboxes1" style="width:70px;height:30px;background:green;color:white"></td></tr>
			</tr> 
			

          
	</table>
	</form>
<table width="40%" border="0" cellspacing="2" cellpadding="2" align="center">
<tr class="fieldsnew">
	
	<td class="formhead" valign="middle" colspan="4" align="center" ><span style="font-weight:normal">User Name:</span> ~$username`  <span style="font-weight:normal">-- Status:</span> ~$activated`</td>
	</tr>
<br>
	<TR>
	<TD class="label" valign="middle"  align="center" colspan="4" ><a href="~$pdfUrl`/allPdf?cid=~$cid`&profileid1=~$profileid`&dialer_check=1">Generate ALL PDF</a> </TD>
	</TR>
	<TR>
	<TD class="label" valign="middle" colspan="1" align="center" >User Login Details</TD>
	<TD class="label" valign="middle" colspan="1" align="center" ><a href="~$moduleurl`/Login?cid=~$cid`&profileid1=~$profileid`">click to view</a> </TD>
	<TD class="label" valign="middle" colspan="1" align="center" ><a href="~$pdfUrl`/pdf?cid=~$cid`&profileid1=~$profileid`&actiontocall=Login&dialer_check=1">Generate PDF</a> </TD>
	<TD class="label" valign="middle" colspan="1" align="center" ><a href="~$pdfUrl`/csv?cid=~$cid`&profileid1=~$profileid`&actiontocall=Login&dialer_check=1">Generate CSV</a> </TD>
	</TR>
	<TR>
	<TD class="label" valign="middle" colspan="1" align="center" >User Logout Details</TD>	
	<TD class="label" valign="middle" colspan="1" align="center" ><a href="~$moduleurl`/Logout?cid=~$cid`&profileid1=~$profileid`">click to view</a> </TD>
	<TD class="label" valign="middle" colspan="1" align="center" ><a href="~$pdfUrl`/pdf?cid=~$cid`&profileid1=~$profileid`&actiontocall='Logout'&dialer_check=1">Generate PDF</a> </TD>
	<TD class="label" valign="middle" colspan="1" align="center" ><a href="~$pdfUrl`/csv?cid=~$cid`&profileid1=~$profileid`&actiontocall='Logout'&dialer_check=1">Generate CSV</a> </TD>
	</TR>
	<TR>
	<TD class="label" valign="middle" colspan="1" align="center" >Profile Details</TD>	
	<TD class="label" valign="middle" colspan="1" align="center" ><a href="~$moduleurl`/ProfileLog?cid=~$cid`&profileid1=~$profileid`">click to view</a> </TD>
	<TD class="label" valign="middle" colspan="1" align="center" ><a href="~$pdfUrl`/pdf?cid=~$cid`&profileid1=~$profileid`&actiontocall=ProfileLog&dialer_check=1">Generate PDF</a> </TD>
	<TD class="label" valign="middle" colspan="1" align="center" > - </TD>
	</TR>
	<TR>
	<TD class="label" valign="middle" colspan="1" align="center" >Profile Modification Details</TD>	
	<TD class="label" valign="middle" colspan="1" align="center" ><a href="~$moduleurl`/EditLog?cid=~$cid`&profileid1=~$profileid`">click to view</a> </TD>
	<TD class="label" valign="middle" colspan="1" align="center" ><a href="~$pdfUrl`/pdf?cid=~$cid`&profileid1=~$profileid`&actiontocall=EditLog&dialer_check=1">Generate PDF</a> </TD>
	<TD class="label" valign="middle" colspan="1" align="center" ><a href="~$pdfUrl`/csv?cid=~$cid`&profileid1=~$profileid`&actiontocall=EditLog&dialer_check=1">Generate CSV</a> </TD>
	</TR>
	<TR>
	<TD class="label" valign="middle" colspan="1" align="center" >Expression Of Interest History</TD>	
	<TD class="label" valign="middle" colspan="1" align="center" ><a href="~$moduleurl`/EOILog?cid=~$cid`&profileid1=~$profileid`">click to view</a> </TD>
	<TD class="label" valign="middle" colspan="1" align="center" ><a href="~$pdfUrl`/pdf?cid=~$cid`&profileid1=~$profileid`&actiontocall=EOILog&dialer_check=1">Generate PDF</a> </TD>
	<TD class="label" valign="middle" colspan="1" align="center" ><a href="~$pdfUrl`/csv?cid=~$cid`&profileid1=~$profileid`&actiontocall=EOILog&dialer_check=1">Generate CSV</a> </TD>
	</TR>
	<TR>
	<TD class="label" valign="middle" colspan="1" align="center" >Payment Details</TD>	
	<TD class="label" valign="middle" colspan="1" align="center" ><a href="~$moduleurl`/PaymentLog?cid=~$cid`&profileid1=~$profileid`">click to view</a> </TD>
	<TD class="label" valign="middle" colspan="1" align="center" ><a href="~$pdfUrl`/pdf?cid=~$cid`&profileid1=~$profileid`&actiontocall=PaymentLog&dialer_check=1">Generate PDF</a> </TD>
	<TD class="label" valign="middle" colspan="1" align="center" ><a href="~$pdfUrl`/csv?cid=~$cid`&profileid1=~$profileid`&actiontocall=PaymentLog&dialer_check=1">Generate CSV</a> </TD>
	</TR>
        <TR>
	<TD class="label" valign="middle" colspan="1" align="center" >Album</TD>	
	<TD class="label" valign="middle" colspan="1" align="center" ><a href="/operations.php/photoScreening/getAlbum?cid=~$cid`&profileid=~$profileid`">click to view</a> </TD>
	<TD class="label" valign="middle" colspan="1" align="center" > <a href="~$pdfUrl`/pdf?cid=~$cid`&profileid=~$profileid`&profileid1=~$profileid`&actiontocall=getAlbum&dialer_check=1">Generate PDF</a></TD>
	<TD class="label" valign="middle" colspan="1" align="center" >  - </TD>
	</TR>
        <TR>
	<TD class="label" valign="middle" colspan="1" align="center" >Chat Detail</TD>	
	<TD class="label" valign="middle" colspan="1" align="center" ><a href="~$moduleurl`/ChatDetail?cid=~$cid`&profileid1=~$profileid`">click to view</a> </TD>
	<TD class="label" valign="middle" colspan="1" align="center" > <a href="~$pdfUrl`/pdf?cid=~$cid`&profileid1=~$profileid`&profileid1=~$profileid`&actiontocall=ChatDetail&dialer_check=1">Generate PDF</a></TD>
	<TD class="label" valign="middle" colspan="1" align="center" >  - </TD>
	</TR>
	
</table>
  

~include_partial('global/footer')`

