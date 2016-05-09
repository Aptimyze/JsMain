<body leftmargin="5" topmargin="5" onLoad="BuyerPreferenceSelected(0);RecipientsSelected();SelectDefaults();">
<form action="/masscomm.php/mmm/formQuerySubmit" method="post" onsubmit="return validate();" onload="return ResComSelected();" name="form1">
<table width="760" border="0" cellspacing="0" cellpadding="0" align="center">
<tr>
<td>
</td>

<td class=small vAlign=top width=10><B><SPACER type="block"  
width="10"></B></td>
<td width="571" valign="top"> 

<table width="99%" border="0" cellspacing="0" cellpadding="0">
<br>
<br>

<tr>
                                                                                                 
<td bgcolor="#ffffcc" class=fieldsnew>
<select name="mailer_id" id = "mailer_id">
        <option value='' ~if !$mailer_id` selected ~/if`> Select a Mailername</option>
        ~foreach from =$mailers item =i key = k`
                <option value = ~$k` ~if $mailer_id eq $k` selected ~/if`> ~$k`. ~$i` </option>
        ~/foreach`
</select>
</td>
</tr>
<tr>
<td>
<input type="hidden" name="site" value="~$site`" />
</td>
</tr>      
<tr>
	<tr>
       <td height="8"></td>
       <SPACER height="8" type="block"></SPACER> </tr>
        <tr>
       <td height="8"></td>
       <SPACER height="8" type="block"></SPACER> </tr>
</tr>



<tr>
       <td class="headbigblack"><b>Compose Query </b></td>
      </tr>
      <tr class="bgred">
       <td height="1"></td>
       <SPACER height="1" type="block"></SPACER> </tr>
        <tr>
       <td height="8"></td>
       <SPACER height="8" type="block"></SPACER> </tr>
     </table></td>
</tr>
</table>

<table width="98%" border="0" cellspacing="0" cellpadding="0">
<tr>
      <td height="3" colspan="2"></td>
</tr>

<tr>
      <td height="3" colspan="2"></td>
<SPACER height="3" type="block"></SPACER> </tr>
<tr>
<td width="96%" height="2" class=mediumblack> To select multiple options press the +Ctrl key on your keyboard
</td>
</tr>
<tr>
<td width="96%" class=mediumblack> </td>
</tr>
</table>
</td>
</tr>
<tr>
<td>
<br>

<!--Profile criteria starts here-->
<table width="98%" border="0" cellspacing="4" cellpadding="2">
<tr> 
<td class=mediumblack width="40%">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;This mailer is meant for</td>
<td  class=mediumblack width="60%">
	<input type=radio name="recipient_type" value='S' checked onClick="document.getElementById('recipient_type_hidden').value='S';RecipientsSelected();">Sellers&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	<input type=radio name="recipient_type" value='B' ~if $recipient_type eq 'B'`checked~/if` onClick="document.getElementById('recipient_type_hidden').value='B';RecipientsSelected();">Buyers&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
</td>
</tr>
<tr>
<td class=mediumblack width="40%" colspan=2><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Select profile details</b> </td>
</tr>

<tr>
<td class=mediumblack width="40%">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;City of registration</b> </td>
<td  class=mediumblack width="60%">
	<span style="display:block;float:left;">
	<input type=radio name="register_city_radio" id="register_city_region_radio" value='CR' onClick="handleRegionDD('register_city')">
	<b>Select a Region</b></br>
	<select style="width:120px;" name="city_region[]" size="5" multiple class="TextBox" id="register_city_region" disabled="true">
	~foreach from=$CITY_REGION item=city key=Id`
        <option value="~$city.value`" ~if $city.selected eq '1'` selected ~/if`>~$city.label`</option>
        ~/foreach`
        </select>
	</span>
	<span style="display:block;float:left;margin-left:20px;">
	<input type=radio name="register_city_radio" id="register_city_radio" value='C' checked onClick="handleRegionDD('register_city_region')">
	<b>Select a City and State</b><br/>
	<select name="city[]" size="5" multiple class="TextBox" id="register_city">
	<option value="">All</option>
	~foreach from=$CITY item=city key=Id`
	<option value="~$city.value`" ~if $city.selected eq '1'` selected ~/if` ~if $city.class eq 'boldclass'` class='boldclass' ~/if`>~$city.label`</option>
	~/foreach`
	</select>
	</span>
</td>
</tr>
<table width="98%" border="0" cellspacing="0" cellpadding="0">
<tr>
<td bgcolor="cccccc" height="1"></td><SPACER height="1" type="block"></SPACER>
</tr>
</table>
<br>
<!--Profile criteria ends here-->

<!--Status criteria starts here-->
<table width="98%" border="0" cellspacing="4" cellpadding="2">
<tr>
<td class="mediumblack" colspan=2>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>Status</b></td>
</tr>

<tr>
<td class="mediumblack" width="40%">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Profile has been Screened</td>
<td class="mediumblack">
<input type=radio name=screening value='' disabled="true">Doesn't Matter&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<input type=radio name=screening value='Y' checked>Yes&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<input type=radio name=screening value='N' disabled="true" ~if $screening eq 'N'`checked~/if`>No
</td>
</tr>

<tr>
<td class="mediumblack" width="40%">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Profile is Active</td>
<td class="mediumblack">
<input type=radio name=activated value='' disabled="true">Doesn't Matter&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<input type=radio name=activated value='Y' checked>Yes&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<input type=radio name=activated value='N' disabled="true" ~if $activated eq 'N'`checked~/if`>No
</td>
</tr>

<tr>
<td class="mediumblack" width="40%">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Susbscribed for Promotional Mails </td>
<td class="mediumblack">
<input type=checkbox name=sub_promo id=sub_promo value='Y' ~if $sub_promo eq 'Y'`checked~/if`>Yes&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
</td>
</tr>

<tr>
<td class="mediumblack" width="40%">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Subscribed for Mailers From Our Partners</td>
<td class="mediumblack">
<input type=checkbox name=sub_partners id = sub_partners value='Y' ~if $sub_partners eq 'Y'`checked~/if`>Yes&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
</td>
</tr>
</table>

<table width="98%" border="0" cellspacing="0" cellpadding="0">
<tr>
<td bgcolor="cccccc" height="1"></td><SPACER height="1" type="block"></SPACER>
</tr>
</table>
<!--Status criteria ends here-->
<br>

<!--Buyer criteria starts here-->
<table width="98%" border="0" cellspacing="4" cellpadding="2" id="buyer_criteria">
<tr>
<td class="mediumblack" colspan=2>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>Buyer Criteria</b></td>
</tr>
<tr>
<td class=mediumblack width="40%">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Residential or Commercial Property Buyer</b> </td>
<td  class=mediumblack width="60%">
<input type=radio name=buyer_rescom value='' checked onClick="ResComSelected()">Both&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<input type=radio name=buyer_rescom value='R' ~if $buyer_rescom eq 'R'`checked~/if` onClick="ResComSelected()">Residential&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<input type=radio name=buyer_rescom value='C' ~if $buyer_rescom eq 'C'`checked~/if` onClick="ResComSelected()">Commercial
</td>
</tr>

<tr>
<td class=mediumblack width="40%">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Preference</b> </td>
<td  class=mediumblack width="60%">
<input type=checkbox name=buyer_preference_buy ~if $buyer_preference_buy eq 'on'`checked~/if` onClick="BuyerPreferenceSelected(this)">Buy&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<input type=checkbox name=buyer_preference_rent ~if $buyer_preference_rent eq 'on'`checked~/if` onClick="BuyerPreferenceSelected(this)">Rent&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<input type=checkbox name=buyer_preference_lease ~if $buyer_preference_lease eq 'on'`checked~/if` onClick="BuyerPreferenceSelected(this)">Lease&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<input type=checkbox name=buyer_preference_pg ~if $buyer_preference_pg eq 'on'`checked~/if` onClick="BuyerPreferenceSelected(this)">PG
</td>
</tr>

<tr>
<td class=mediumblack width="40%">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Property Cities</b> </td>
<td  class=mediumblack width="60%">
	<span style="display:block;float:left;">
	<input type=radio name="buyer_city_radio" id="buyer_city_region_radio" value='BR' onClick="handleRegionDD('buyer_city')">
	<b>Select a Region</b><br/>
	<select style="width:120px;" name="buyer_prop_city_region[]" size="4" multiple class="TextBox" id="buyer_city_region" disabled="true">
	~foreach from=$BUYER_CITY_REGION item=city key=Id`
        <option value="~$city.value`" ~if $city.selected eq '1'` selected ~/if`>~$city.label`</option>
        ~/foreach`
        </select>
	</span>
	<span style="display:block;float:left;margin-left:20px;">
	<input type=radio name="buyer_city_radio" id="buyer_city_radio" value='B' checked onClick="handleRegionDD('buyer_city_region')">
	<b>select City and State</b><br/>
        <select name="buyer_prop_city[]" size="4" multiple class="TextBox" id='buyer_city'>
        <option value="">Doesn't Matter</option>
         ~foreach from=$BUYER_CITY item=city key=Id`
        <option value="~$city.value`" ~if $city.selected eq '1'` selected ~/if` ~if $city.class eq 'boldclass'` class='boldclass' ~/if`>~$city.label`</option>
        ~/foreach`
        </select>
	</span>
</td>
</tr>

<tr>
	<td class=mediumblack width="40%">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Property Type</b> </td>
		<td  class=mediumblack width="60%">
			<select name="buyer_property_type[]" size="4" multiple class="TextBox">
				<option value="">Any</option>
				~foreach from=$PROPERTY_TYPE item=prop_type key=Id`
			        <option value="~$prop_type.value`" ~if $prop_type.selected eq '1'` selected ~/if`>~$prop_type.label`</option>
        			~/foreach`
			</select>
	</td>
</tr>

<tr>
	<td class=mediumblack width="40%">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Buying Budget </td>
	<td>
			<select name="budget_min" style="float:left" id="buy_budget_min" tabindex="2" nameinerr="Min Budget" valtype="budget_min" id="buying_budget" onchange="CheckDD(this.value,'MIN');">
				<option value="0">Min</option>
				~foreach from=$BUYING_BUDGET_MIN item=bud key=Id`
                                <option value="~$bud.value`" class="" ~if $bud.selected eq '1'` selected ~/if`>~$bud.label`</option>
                                ~/foreach`
			</select>
			<span style="float:left;margin-top:8px;" name="buy_budget_to" id="buy_budget_to">&nbsp;to&nbsp;</span>
		        <select name="budget_max" id="buy_budget_max" tabindex="2" style="float:left" nameinerr="Max Budget" valtype="budget_max" onchange="CheckDD(this.value,'MAX');">
				<option value="0">Max</option>
				~foreach from=$BUYING_BUDGET_MAX item=bud key=Id`
                                <option value="~$bud.value`" class="" ~if $bud.selected eq '1'` selected ~/if`>~$bud.label`</option>
                                ~/foreach`
			</select>
	</td>
</tr>

<tr>
<td class=mediumblack width="40%">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Database NRI</td>
<td  class=mediumblack width="60%">
<input type=checkbox name=buyer_country_source value=Y ~if $buyer_country_source eq 'Y'`checked~/if` >Yes</input>
</td>
</tr>

<tr>
<td class=mediumblack width="40%">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Limit on Number of Results</td>
<td class=mediumblack width="60%" >
<input type ="text" name="buyer_upper_limit"  ~if $buyer_upper_limit` value = ~$buyer_upper_limit` ~/if` />
</td>
</tr>

</table>
<!--Buyer Criteria ends here-->

<!--Seller Criteria starts here-->
<table width="98%" border="0" cellspacing="4" cellpadding="2" id="seller_criteria">
<tr>
<td class="mediumblack" colspan=2>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>Seller Criteria (Based on features of properties posted)</b></td>
</tr>

<tr>
<td class=mediumblack width="40%">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;~if $check_sellerclass eq 'Y'`<font color=red>Seller Class<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Check atleast one~else`<font color=black>Seller Class~/if`</font></td>
<td  class=mediumblack width="60%">
        <input type="checkbox" name="seller_class_agent" class="TextBox" ~if $seller_class_agent eq 'on'`checked~/if` onClick="SellerClassSelected()">Agent&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	<input type="checkbox" name="seller_class_builder" class="TextBox" ~if $seller_class_builder eq 'on'`checked~/if` onClick="SellerClassSelected()">Builder&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	<input type="checkbox" name="seller_class_owner" class="TextBox" ~if $seller_class_owner eq 'on'`checked~/if` onClick="SellerClassSelected()">Owner&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
</td>
</tr>

<tr>
<td class=mediumblack width="40%">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Residential or Commercial Property Seller</b> </td>
<td  class=mediumblack width="60%">
<input type=radio name=seller_rescom value='' checked onClick="ResComSelected()">Both&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<input type=radio name=seller_rescom value='R' ~if $seller_rescom eq 'R'`checked~/if` onClick="ResComSelected()">Residential&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<input type=radio name=seller_rescom value='C' ~if $seller_rescom eq 'C'`checked~/if` onClick="ResComSelected()">Commercial
</td>
</tr>

<tr>
<td class=mediumblack width="40%">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Preference</b> </td>
<td  class=mediumblack width="60%">
<input type=checkbox name=seller_preference_all ~if $seller_preference_all eq 'on'`checked~/if` onClick="SellerPreferenceSelected(this)">Doesn't Matter&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<input type=checkbox name=seller_preference_sell ~if $seller_preference_sell eq 'on'`checked~/if` onClick="SellerPreferenceSelected(this)">Sell&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<input type=checkbox name=seller_preference_rent ~if $seller_preference_rent eq 'on'`checked~/if` onClick="SellerPreferenceSelected(this)">Rent&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<input type=checkbox name=seller_preference_lease ~if $seller_preference_lease eq 'on'`checked~/if` onClick="SellerPreferenceSelected(this)">Lease&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<input type=checkbox name=seller_preference_pg ~if $seller_preference_pg eq 'on'`checked~/if` onClick="SellerPreferenceSelected(this)">PG
</td>
</tr>

<tr>
	<td class=mediumblack width="40%">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Property Type(s)</b> </td>
	<td  class=mediumblack width="60%">
		<select name="seller_property_type[]" size="4" multiple class="TextBox">
			<option value="">Doesn't Matter</option>
			~foreach from=$SELLER_PROPERTY_TYPE item=prop_type key=Id`
        		<option value="~$prop_type.value`" ~if $prop_type.selected eq '1'` selected ~/if`>~$prop_type.label`</option>
		        ~/foreach`
		</select>
	</td>
</tr>

<tr>
<td class=mediumblack width="40%">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;The cities, seller has properties in</b> </td> <td  class=mediumblack width="60%">
	<span style="display:block;float:left;">
	<input type=radio name="seller_city_radio" id="seller_city_region_radio" value='SR' onClick="handleRegionDD('seller_city')">
	<b>Select a Region</b></br>
	<select style="width:120px;" name="seller_prop_city_region[]" size="4" multiple class="TextBox" id="seller_city_region" disabled="true">
	~foreach from=$SELLER_CITY_REGION item=city key=Id`
        <option value="~$city.value`" ~if $city.selected eq '1'` selected ~/if`>~$city.label`</option>
        ~/foreach`
        </select>
	</span>
	<span style="display:block;float:left;margin-left:20px;">
	<input type=radio name="seller_city_radio" id="seller_city_radio" value='S' checked onClick="handleRegionDD('seller_city_region')">
	<b>Select a City and State</b></br>
        <select name="seller_prop_city[]" size="4" multiple class="TextBox" id='seller_city'>
        <option value="">Doesn't Matter</option>
	~foreach from=$SELLER_CITY item=city key=Id`
        <option value="~$city.value`" ~if $city.selected eq '1'` selected ~/if` ~if $city.class eq 'boldclass'` class='boldclass' ~/if`>~$city.label`</option>
        ~/foreach`
        </select>
	</span>
</td>
</tr>

<tr>
<td class=mediumblack width="40%">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Database NRI</b> </td>
<td  class=mediumblack width="60%">
<input type=checkbox name=seller_country_source  value=Y ~if $seller_country_source eq 'Y'`checked~/if`>Yes</input>
</td>
</tr>

<tr>
<td class=mediumblack width="40%">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Limit on Number of Results</td>
<td class=mediumblack width="60%">
<input type ="text" name="seller_upper_limit"  ~if $seller_upper_limit` value = ~$seller_upper_limit` ~/if`/>
</td>
</tr>

</table>
<!--Seller criteria ends here-->

<table align="center">
<tr>
<td width="25%">&nbsp;</td>
<td class="mediumblack"><br>
<input type="hidden" name="fsubmit" value="fsubmit">
<input type="image" name="fsubmit" value="fsubmit"  src="http://www.jeevansathi.com/profile/images/submit_button.gif" width="76" height="23"></td>

</tr>

</table>

  </td>
</tr>
</table>
</td>
</tr>
</table>
</td>
</tr>
</table>
<br>
<br>
<input type="hidden" id="register_city_radio_hidden" name = "register_city_radio_hidden" value="~$register_city_radio`"/>
<input type="hidden" id="seller_city_radio_hidden" name = "seller_city_radio_hidden" value="~$seller_city_radio`"/>
<input type="hidden" id="buyer_city_radio_hidden" name = "buyer_city_radio_hidden" value="~$buyer_city_radio`"/>
<input type="hidden" id="recipient_type_hidden" name = "recipient_type_hidden" value="~$recipient_type`"/>
</form>
</body>
