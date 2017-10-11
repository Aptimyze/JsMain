<style>
.vam {vertical-align:middle;}
</style>
<script>
function validate()
{
	var error = 0;
	var father = document.getElementById('Family_Back').value;
	var mother = document.getElementById('mother_occ').value;
	var tbro = document.getElementById('tbrother').value;
	var mbro = document.getElementById('mbrother').value;
	if(mbro>tbro)
	{
		document.getElementById('bro_err').style.display="block";
		var bimg_msg = '<img style="vertical-align: bottom;" src="~$IMG_URL`/profile/images/registration_new/alert.gif"/>&nbsp;Married brothers cannot exceed '+tbro+'.';
		document.getElementById('bro_err').innerHTML=bimg_msg;
		error = 1;
	}
	else
		document.getElementById('bro_err').style.display="none";
	var tsis = document.getElementById('tsister').value;
        var msis = document.getElementById('msister').value;
        if(msis>tsis)
	{
		document.getElementById('sis_err').style.display="block";
		var simg_msg = '<img style="vertical-align: bottom;" src="~$IMG_URL`/profile/images/registration_new/alert.gif"/>&nbsp;Married sisters cannot exceed '+tsis+'.';
                document.getElementById('sis_err').innerHTML=simg_msg;
		error = 1;
	}
	else
		document.getElementById('sis_err').style.display="none";
	if(error)
		return false;
	
}
</script>
<div class="edit_scrollbox2_1 t12">
~$sf_data->getRaw('hiddenInput')`
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
<div class="fs16"  style=" margin-left:23px"><strong>Tell more about your family</strong> to increase response to your Expressions of Interest.</div>
</span>
</p>
</div>
</div>
<div class="sp15"></div>
~/if`
~/if`
<div class="row3 no-marigin-padding">
<label class="grey">&nbsp;&nbsp;&nbsp;Family Values :</label>
~$sf_data->getRaw('family_values_radio')`
</div>
<div class="sp15">&nbsp;</div>
<div class="row3 no-margin-padding">
<label class="grey">Family Type :</label>
~$sf_data->getRaw('family_type_radio')`
</div>
<div class="sp15">&nbsp;</div>
<div class="row3 no-margin-padding">
<label class="grey">Family Status :</label>
~$sf_data->getRaw('family_status_radio')`
</div>
<div class="sp15">&nbsp;</div>
<div class="row3 no-margin-padding">
<label class="grey">Family Income :</label>
<select name="Family_Income"><option value="">Select</option>~$sf_data->getRaw('family_income_option')`</select>
</div>
<div class="sp15">&nbsp;</div>
<div class="row3 no-margin-padding">
<label class="grey">Father :</label>
<select  name="Family_Back" id="Family_Back">
<option value="">Select</option>
~$sf_data->getRaw('FAMILY_BACK')`
</select>
</div>
<div class="sp15">&nbsp;</div>
<div class="row3 no-margin-padding">
<label class="grey">Mother :</label>
<select name="mother_occ" id="mother_occ">
<option value="">Select</option>
~$sf_data->getRaw('MOTHER_OCC')`
</select>
</div>
<div class="sp15">&nbsp;</div>
<div class="row3 no-margin-padding">
<label class="grey">Brother(s) :</label>
<select class="combo-small-more fl" name="tbrother" id="tbrother">
<option value="">Select</option>
~$sf_data->getRaw('TBROTHERS')`
</select> &nbsp; of which married&nbsp;&nbsp;
<select class="combo-small-more" id="mbrother" name="mbrother">
<option value="">Select</option>
~$sf_data->getRaw('MBROTHERS')`
</select>
<div class="red_new" id="bro_err" style="display:none;padding-left:196px;"></div>
</div>
<div class="sp15">&nbsp;</div>
<div class="row3 no-margin-padding">
<label class="grey">Sister (s) :</label>
<select class="combo-small-more fl" name="tsister" id="tsister">
<option value="">Select</option>
~$sf_data->getRaw('TSISTERS')`
</select> &nbsp; of which married&nbsp;&nbsp;
<select class="combo-small-more" id="msister" name="msister">
<option value="">Select</option>
~$sf_data->getRaw('MSISTERS')`
</select>
<div class="red_new" id="sis_err" style="display:none;padding-left:196px;"></div>
</div>
<div class="sp15">&nbsp;</div>
<div class="row3 no-margin-padding">
<label class="grey">Living with Parents :</label>
~$sf_data->getRaw('parent_city_radio')`
</div>
<div class="sp15">&nbsp;</div>
<div class="row3 no-margin-padding">
	<label class="grey">Name of person handling profile :</label>
	<input type="text" name="person_handling_profile" id="person_handling_profile" value="~$person_handling_profile`"/>
</div>
<div class="sp15">&nbsp;</div>
</div>
