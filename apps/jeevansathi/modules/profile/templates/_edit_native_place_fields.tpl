<div id="whole_native" style="opacity:0">
<div id="native_state"class="row4 no-margin-padding" >
<label class="grey" style="margin-top: 4px;">Family based out of  :</label>
<div class="lf t11"><div class="lf">
<select name="reg_native_state" id="reg_native_state"  style="width:200px">~$sf_data->getRaw('NATIVE_STATE')`</select>
</div></div>
<div class="lf" style="padding:3px 0px 0px 15px">
	<input type="checkbox" value="~$sf_data->getRaw('OUTSIDE_INDIA')`" name="outside_inda" id="chk_outside_india" style="width:20px;height:15px;border:0px" >&nbsp; Outside India
</div></div>
<div class="clr"></div>	
<div class="sp15">&nbsp;</div>


<div id="native_city"class="row4 no-margin-padding">
<label class="grey"></label>
<div class="lf t11"><div class="lf" style="padding-left: 197px;">
<select name="reg_native_city" id="reg_native_city" style="width:200px">~$sf_data->getRaw('NATIVE_CITY')`</select>
</div></div></div>

<div id="native_country"class="row4 no-margin-padding">
<label class="grey"></label>
<div class="lf t11"><div class="lf" style="padding-left: 197px;">
<select name="reg_native_country" id="reg_native_country"  style="width:200px">~$sf_data->getRaw('NATIVE_COUNTRY')`</select>
</div></div></div>
<div class="sp15">&nbsp;</div>

<div class="row4 no-margin-padding" id="native_place">
<label style="margin-top: 4px;" class="grey">Specify home/town  :</label>
<div class="lf t11"><div class="lf">
<input type="text" id="reg_ancestral_origin" name="reg_ancestral_origin" value="~$sf_data->getRaw('NATIVE_PLACE')`"><br>
<i class="green lf fs12">This field will be screened before going live</i>
</div></div>
<div class="sp15">&nbsp;</div>
</div>

</div>

