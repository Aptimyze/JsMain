
<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
<tr>
        <td valign="top" width="30%" bgcolor="#efefef"></td>
        <td valign="top" width="40%" bgcolor="#efefef" align="center"><img src="/images/mmm_js/logo.gif" width="269" height="82" usemap="#Map" border="0"></td>
        <td valign="bottom" width="30%" bgcolor="#efefef">
        </td>
</tr>
                                                                                                 
<tr><td colspan="100%" align="center"><h3>Welcome to<br> Mass Mailer Management Sytem<br>  <span class=red> >>>&nbsp; &nbsp;Save search query for the particular mailer by selecting a mailer from the list or entering the mailerid  &nbsp;&nbsp>>></span> </h3><br><br></td>
</td>
</tr>

</table>


<table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
       <td height="4"></td>
       <SPACER height="4" type="block"></SPACER> </tr>
      <tr>
       <td class="headbigblack" align = "center"><b>Total No Of Results : ~$expectedProfilesCount` </b></td>
      </tr>
      <tr class="bgred">
       <td height="1"></td>
       <SPACER height="1" type="block"></SPACER> </tr>
        <tr>
       <td height="8"></td>
       <SPACER height="8" type="block"></SPACER> </tr>
</table>
<table WIDTH="75%" BORDER="0" CELLSPACING="2" CELLPADDING="0" BORDERCOLOR="#2F3193" ALIGN="center">
<br>
<br>

<tr>
<td class=label><b>Selected Mailer Id </b>
</td>

<td bgcolor="#ffffcc" class=fieldsnew>
~$mailerId`
</td>
</tr>
</table>
<table ALIGN = "center">
<tr>
<form name="form1" method="post" action="formQuerySave" >
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<input type = "hidden" name = "mailer_id" value = ~$mailerId`>

<button type = "submit" align = "center" name="save_query"> Save Query</button>
<input type = "hidden" name = "site" value = ~$site`>
</form>
</tr>

<tr>
~if $site eq 'J'`
<form name="form2" method="post" action="formQueryJs" >
~/if`
~if $site eq '9'`
<form name="form2" method="post" action="formQuery_99">
~/if`
<input type = "hidden" name = "id" value = ~$mailerId`>
<button type = "submit" align = "center" name="edit_query"> Edit Query</button>
<input type="hidden" id="register_city_radio_hidden" name = "register_city_radio_hidden" value="~$register_city_radio`"/>
<input type="hidden" id="seller_city_radio_hidden" name = "seller_city_radio_hidden" value="~$seller_city_radio`"/>
<input type="hidden" id="buyer_city_radio_hidden" name = "buyer_city_radio_hidden" value="~$buyer_city_radio`"/>
<input type="hidden" id="recipient_type_hidden" name = "recipient_type_hidden" value="~$recipient_type`"/>
</form>
</tr>
</table>
