<script> 
var page="edit1";
function checkWeight()
{
	var filter  = /^[0-9]+$/;
	weight=document.getElementById("weight").value;
	if(weight!='' && !filter.test(weight)){
		document.getElementById("weight_err").style.display="block";
		return false;
		}
	else{
		document.getElementById("weight_err").style.display="none";
		return true;
		}

}
</script>
<SCRIPT type="text/javascript" language="Javascript" SRC="~$IMG_URL`/min/?f=/profile/js/~$behaviour_js`,/profile/js/~$registration_js`,/js/~$advance_search_js`,/profile/js/~$gadget_as_js`"></SCRIPT>
~$sf_data->getRaw('hiddenInput')`

<div class="edit_scrollbox2_1 fs13">
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
<div class="fs16" style=" margin-left:23px"><strong>Tell more about yourself</strong> to increase response to your Expressions of Interest.</div>
</span>
</p>
</div>
</div>
<div class="sp15"></div>
~/if`
~/if`
<div class="row4 no-margin-padding">
<label class="grey">Diet :</label>
~$sf_data->getRaw('diet_radio')`
</div>

<div class="sp15">&nbsp;</div>

<div class="row4 no-margin-padding">
<label class="grey">Smoke :</label>
~$sf_data->getRaw('smoke_radio')`
</div>

<div class="sp15">&nbsp;</div>

<div class="row4 no-margin-padding">
<label class="grey">Drink :</label>
~$sf_data->getRaw('drink_radio')`
</div>

<div class="sp15">&nbsp;</div>

<div class="row4 no-margin-padding">
<label class="grey">Complexion :</label>
~$sf_data->getRaw('complexion_radio')`
</div>
<div class="sp15">&nbsp;</div>

<div class="row4 no-margin-padding">
<label class="grey">Body Type :</label>
~$sf_data->getRaw('body_type_radio')`
</div>

<div class="sp15">&nbsp;</div>

<script>
function shown(han)
{
	if(han == '1' || han=='2')
		document.getElementById('nhan').style.display="block";
	else {
		document.getElementById('nhan').style.display="none";
    document.getElementById('nature_of_handicap').selectedIndex = document.getElementById('nature_of_handicap').options[0];
  }
}
</script>
<div class="row4 no-margin-padding">
<label class="grey" style="height:90px;">Challenged :</label>
~$sf_data->getRaw('handicapped_radio')`
</div>

<div class="sp15">&nbsp;</div>

<div class="row4 no-margin-padding" id="nhan" style="display:none;">
<label class="grey">Nature of Handicap :</label>
<select style="width:145px" name="nature_of_handicap" id="nature_of_handicap">
<option value="">Select</option>
~$sf_data->getRaw('nature_handicap_option')`
</select>
</div>

<div class="sp15">&nbsp;</div>

<div class="row4 no-margin-padding">
<label class="grey">Blood  Group :</label>
<select class="combo-small-more" name="blood_group" id="blood_group">
<option value="">Select</option>
~$sf_data->getRaw('blood_group_option')`
</select>
</div>
<div class="sp15">&nbsp;</div>


<div class="row4 no-margin-padding">
<label class="grey">Weight :</label>
<input class="combo-small-more" type="text" size="4" maxlength="3" name="weight" value="~$WEIGHT`" id="weight" onblur="checkWeight();"> Kgs</div>
<div class="clear"></div>
<div class="red_new" id="weight_err" style="display:none; margin:0 0 0 195px;">
<img style="vertical-align: bottom;" src="~$IMG_URL`/profile/images/registration_new/alert.gif"/>&nbsp;Only numbers are allowed in Weight.
</div>

<div class="sp15">&nbsp;</div>

<div class="row4 no-margin-padding">
<label class="grey">Thalassemia :</label>
~$sf_data->getRaw('thalassemia_radio')`
</div>
<div class="sp15">&nbsp;</div>

<div class="row4 no-margin-padding">
<label class="grey">Residential Status :</label>
<select style="width:145px;" name="rstatus" id="rstatus">
<option value="">Select</option>
~$sf_data->getRaw('rstatus_option')`
</select>
</div>
<div class="sp15">&nbsp;</div>

<div class="row4 no-margin-padding">
<label class="grey">Own House :</label>
~$sf_data->getRaw('own_house_radio')`
</div>
<div class="sp15">&nbsp;</div>

<div class="row4 no-margin-padding">
<label class="grey">Own Car :</label>
~$sf_data->getRaw('have_car_radio')`
</div>
<div class="sp15">&nbsp;</div>

<div class="row4 no-margin-padding widthauto">
<label class="grey">Spoken Language :</label>
</div>
<span>
<div class="lf t11 setwidth">
<div class="setwidth"><div class="lf">Select Items</div><div class="rf"><a id="language_select_all" onclick="add_checkboxes(this); remove_doesnt_matter_conflict(this);" class="blink">Select All</a></div></div>
<div class="lf scroll fs13">
	 <div style="display:none" id="language_div">
                <input type="hidden" name="language_str" id="language_str" value="~$LANGUAGE_str`">
                ~foreach from=$LANGUAGE item=val key=k`
                <input type="checkbox" value="~$k`" name="language_arr[]" id="language_~$k`"><label id="language_label_~$k`">~$val`</label><br>
                ~/foreach`
        </div>
        <div style="overflow:hidden;" id="language_source_div">
                ~foreach from=$LANGUAGE item=val key=k`
                <input id="language_displaying_~$k`" class="chbx" type="checkbox" onclick="add_checkboxes(this); remove_doesnt_matter_conflict(this);" value="~$k`" name="language_displaying_label_arr[]"><label id="language_displaying_label_~$k`">~$val`</label><br>
                ~/foreach`
        </div>
</div>
</div>
<span class="horz_sp20 fl">&nbsp;</span>
<div class="lf t11 setwidth">
<div style="padding:0 5px 0 2px;"><div class="lf">Selected Items</div><div class="rf"><a id="language_clear_all" onclick="remove_checkboxes(this); remove_doesnt_matter_conflict(this);" class="blink">Clear All</a></div></div>
<div class="lf scroll fs13">
<div style="overflow:hidden;" id="language_target_div">
<div id="language_DM"><label>Doesn't Matter</label></div>
</div>
</div></div>
</span>
<div class="sp15"></div>
<div class="row4 no-margin-padding">
<label class="grey">Open to pets :</label>
~$sf_data->getRaw('open_to_pet_radio')`
</div>
<div class="sp15"></div>
<div class="row4 no-margin-padding">
<label class="grey">HIV + :</label>
~$sf_data->getRaw('hiv_radio')`
</div>

<div class="sp15"></div>
<script>
shown('~$HANDICAPPED`');
var fields_to_fill = new Array("language");
fill_details(fields_to_fill);
</script>
</div>
