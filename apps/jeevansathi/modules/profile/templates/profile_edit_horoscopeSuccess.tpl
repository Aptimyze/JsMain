<!-- Top ends here -->
<script>
function closeLayer()
{
window.location ="/profile/editProfile?from_horo_layer=1";
}
</script>
<input name="flag" value="CUH" type="hidden">
<input name="width" value="700" type="hidden">
<input name="CMDsubmit" value="Y" type="hidden">
<input name="submit_layer" value=1 type="hidden">
<input name="img_url" value="/profile/images/registration_new" type="hidden">
~$sf_data->getRaw('hiddenInput')`
<div class="edit_scrollbox2_1 ">
	<input type="hidden" name="from_where" value="~$sf_request->getParameter('from_where')`">
	~if FTOLiveFlags::IS_FTO_LIVE && ($sf_request->getParameter('from_where') eq VSP || $sf_request->getParameter('from_where') eq VSP_layer)`
	<input type="hidden" name="SIM_USERNAME" value="~$sf_request->getParameter('SIM_USERNAME')`">
	<input type="hidden" name="contact" value="~$sf_request->getParameter('contact')`">
	<input type="hidden" name="NAVIGATOR" value="~$sf_request->getParameter('NAVIGATOR')`">
	~if $sf_request->getParameter('from_where') eq VSP_layer`
<div class=" lf" style="padding-left:5px">
  <div class=" lf " style="background:#eeebec; width:710px;padding:10px;margin-top:-10px">
  <div class="grn_tk sprite lf" ></div>
<p class="lf" >
<span>
<span style="margin-left:5px" class="fs14"><Span style="color:#33b300" class="b">Congratulations !
</Span>You have successfully ~if $sf_request->getParameter('contactType') eq 'R'` sent reminder to~else`expressed interest in~/if` ~$sf_request->getParameter('SIM_USERNAME')` 
</span>
<div class="sp5"></div>
<div style="color:#505050; margin-left:23px" class="fs14">You will be able to see the contact details of this user if ~if $GENDER eq F`he~else`she~/if` accepts your interest during the free trial period. </div>
<div class="sp15"></div>
<div class="fs16" style=" margin-left:23px"><strong>Upload your horoscope</strong> to increase response to your Expressions of Interest</div>
</span>
</p>
</div>
</div>
<div class="sp15"></div>
~/if`
~/if`
<div class="b row3 no-margin-padding">
<input class="chbx vam" name="horo_action"  value="C" type="radio">
Let Jeevansathi create your Horoscope</div>
<div class="sp15"></div>
<div class="row3 no-margin-padding">
<span class="b fs16 mar_left_14">OR</span>
</div>
<br>
<div class="row3 no-margin-padding ">
<br><input class="chbx vam" name="horo_action" value="U" type="radio">
<strong>Upload your digitally scanned horoscope</strong>
<div class="sp5"></div>

<div class="sp15">&nbsp;</div>
<div class="sp15">&nbsp;</div>


<div class="no-margin-padding row3 ">
<div class="sml_pink_box">
<div class="sp15">&nbsp;</div>
<div><div  class="fl div_txt_left"> Horoscope match is must for marriage?</div><div><input class="chbx vam" name="horo_match" type="radio" ~if $HOROSCOPE_MATCH eq 'Y'`checked~/if` value='Y'>Yes 
    <input class="chbx vam" name="horo_match" value='N'  type="radio" ~if $HOROSCOPE_MATCH eq 'N'`checked~/if`>No</div></div>
<div class="border_top_bot"><div class="div_txt_left fl"> Sun Sign</div>
<div><select class="combo-small" name="sunsign">
~$sf_data->getRaw('SUNSIGN')`</select></div>
</div>

<div>
<div  class="fl div_txt_left"> Moon Sign / Rashi</div>
<div><select class="combo-small" name="rashi">
~$sf_data->getRaw('RASHI')`</select></div>
</div>

<div class="border_top_bot">
<div  class="fl div_txt_left"> Nakshatram</div>
<div><select class="combo-small" name="nakshatra">
<option value="" ~if $nakshatram_val eq ''`selected~/if`>Please Select</option>
~$sf_data->getRaw('nak_array')`</select></div></div>

<div><div  class="fl div_txt_left"> ~if $community eq '17' or $community eq '31'`Chevvai Dosham~else`Manglik~/if`  :</div><div><select class="combo-small" name="manglik">
~$sf_data->getRaw('MANGLIK')`</select></div></div>
</div>
</div>
</div>
</div><div class="sp15"></div>
