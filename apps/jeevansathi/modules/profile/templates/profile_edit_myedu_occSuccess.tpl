
<link rel="stylesheet" type="text/css" href="~sfConfig::get(app_site_url)`/css/~$rupeeSymbol_css`"><style>
.inf_in{font-weight:bold; font-family:Arial,verdana; font-size:13px; padding:10px 5px 0px 5px;}
.vam{vertical-align:middle;}
input.decotxt_3{width:195px;padding:2px;}
</style>
<script>
	$("#college_name").autocomplete(SITE_URL+"/profile/autoSug?type=collg",	{maxItemsToShow:10,field:'#anu'});
	$("#school_name").autocomplete(SITE_URL+"/profile/autoSug?type=school",	{maxItemsToShow:10,field:'#gau'});
	$("#pg_college").autocomplete(SITE_URL+"/profile/autoSug?type=collg",	{maxItemsToShow:10,field:'#pgcol'});
	$("#organisation").autocomplete(SITE_URL+"/profile/autoSug?type=org",	{maxItemsToShow:10,field:'#organ'});
	function show_add(field,hide)
	{
	if(hide == 2){
		if(field=="other_grad"){
			document.getElementById("grad_add").style.display="block";
			document.getElementById("other_ug_degree").value="";
			document.getElementById("other_grad").style.display="none";
			document.getElementById("pg_college").focus();
		}
		if(field=="other_pg_degree_sect"){
			document.getElementById("other_pg_degree_sect").style.display="none";
			document.getElementById("other_pg_degree").value="";
			document.getElementById("other_pg_add").style.display="block";
			document.getElementById("Education_Level").focus();
		}
		}
		else{
			if(field=="other_grad"){
			document.getElementById("grad_add").style.display="none";
			document.getElementById("other_grad").style.display="block";
			document.getElementById("other_ug_degree").focus();
			}
			if(field=="other_pg_degree_sect"){
			document.getElementById("other_pg_degree_sect").style.display="block";
			document.getElementById("other_pg_add").style.display="none";
			document.getElementById("other_pg_degree").focus();
			}
			}

	}
</script>
~if FTOLiveFlags::IS_FTO_LIVE and ($mark eq 2 or $edu_level_new eq '' or $edu_level_new eq 0 or $occ_val eq '' or $occ_val eq 0 or $income_val eq '' or $income_val eq 0)`
<div class="edit_scrollbox2_2">
~else`
<div class="edit_scrollbox2_1">
~/if`
~$sf_data->getRaw('hiddenInput')`
	<input type="hidden" name="IncompleteMail" value="~$sf_request->getParameter('IncompleteMail')`">
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
</Span>You have successfully ~if $sf_request->getParameter('contactType') eq 'R'` sent reminder to~else`expressed interest in~/if`  ~$sf_request->getParameter('SIM_USERNAME')` 
</span>
<div class="sp5"></div>
<div style="color:#505050; margin-left:23px" class="fs14">you will be able to see the contact details of this user after ~if $GENDER eq F`he~else`she~/if` accepts your interest.</div>
<div class="sp15"></div>
<div class="fs16" style=" margin-left:23px"><strong>Fill your Education & Occupation details</strong> to increase response to your Expressions of Interest.</div>
</span>
</p>
</div>
</div>
<div class="sp15"></div>
~/if`
~/if`
~if $mark eq 2 or $edu_level_new eq '' or $edu_level_new eq 0 or $occ_val eq '' or $occ_val eq 0 or $income_val eq '' or $income_val eq 0`
	~if FTOLiveFlags::IS_FTO_LIVE`
	<div class="fto-notification-box">
	 	<p style="color:#bc001d">Complete the form and 
	     Get Jeevansathi Paid Membership for <span style="font-family:WebRupee; color:#bc001d">R</span><span style=" text-decoration: line-through; color:#000 "><span style="color:#bc001d">1100</span></span><strong> FREE</strong> 

		</p>
		<p style="color:#000">See e-mail IDs &amp; Phone numbers of people you like.</p>
	</div>
	<div class="sp15">&nbsp;</div>
~/if`
<div class="inf_in"><img src="~$IMG_URL`/profile/images/info_icon.gif" align="absmiddle">&nbsp;Please fill in the information marked in Red, or your profile is not visible to other members as it is incomplete</div>
~/if`

<div class="row4 no-margin-padding">
	<label class="grey">&nbsp;&nbsp;&nbsp;Name of School :</label>
	<input type="text" class="decotxt_2" name="school_name" id="school_name" value="~$school`"/><br/>
	<span id="gau" style="position:absolute;clear:all"></span>
<div class="sp15">&nbsp;</div>
</div>
<div class="row4 no-margin-padding">
	<label class="grey">&nbsp;&nbsp;&nbsp;Name of College :</label>
	<input type="text" class="decotxt_2" name="college_name" id="college_name" value="~$college`"/><br />
	<span id="anu" style="position:relative;clear:all"></span>
<div class="sp15">&nbsp;</div>
</div>
<div class="row4 no-margin-padding" ~if $sf_request->getParameter('from_fto')`style="display:none"~/if`>
	<label class="grey">&nbsp;&nbsp;&nbsp;Graduation Degree :</label>
	<select class="fl" name="Grad_Degree">
		~if $grad_degree eq '' or $grad_degree eq 0`
			<option value="">Select</option>
		~/if`
		~$sf_data->getRaw('degree_ug')`
	</select>
	<div id="grad_add" style="display:~if $other_ug_degree`None~else`block;~/if`"><i class="btn-add fl">&nbsp;</i><a href="#" onclick="show_add('other_grad',1);" class="b fl">Add more</a></div>
<div class="sp15">&nbsp;</div>
</div>
<div id="other_grad" style="display:~if $other_ug_degree && !$sf_request->getParameter('from_fto')`block;~else`none;~/if`">
	<div class="row4 no-margin-padding" id="other_grad_degree">
		<label class="grey">&nbsp;&nbsp;&nbsp;Other Graduation Degree :</label>
		<input type="text" class="decotxt_3 fl" name="other_ug_degree" id="other_ug_degree" value="~$other_ug_degree`"/>
		<i class="btn-rem fl">&nbsp;</i><a href="#" onclick="show_add('other_grad',2);" class="b fl">Remove</a>
	</div>
<div class="sp15">&nbsp;</div>
</div>
<div class="row4 no-margin-padding">
	<label class="grey">&nbsp;&nbsp;&nbsp;PG College :</label>
	<input type="text" class="decotxt_2" name="pg_college" id="pg_college" value="~$pg_college`"/><br />
	<span id="pgcol" style="position:relative;clear:all"></span>
</div>
<div class="sp15">&nbsp;</div>
<div class="row4 no-margin-padding" ~if $sf_request->getParameter('from_fto')`style="display:none"~/if`>
	<label class="grey">&nbsp;&nbsp;&nbsp;PG Degree :</label>
	<select class="decoselect_2 fl" name="pg_degree" id="pg_degree">
		~if $pg_degree eq '' or $pg_degree eq 0`
			<option value="">Select</option>
		~/if`
		~$sf_data->getRaw('degree_pg')`
	</select>
	<div id="other_pg_add" style="display:~if $other_pg_degree`none;~else`block;~/if`"><i class="btn-add fl">&nbsp;</i><a href="#" onclick="show_add('other_pg_degree_sect',1)" class="b fl">Add more</a></div>
<div class="sp15">&nbsp;</div>
</div>
<div id="other_pg_degree_sect" style="display:~if $other_pg_degree && !$sf_request->getParameter('from_fto')`block;~else`none;~/if`">
	<div class="row4 no-margin-padding">
		<label class="grey">&nbsp;&nbsp;&nbsp;Other PG Degree :</label>
		<input type="text" class="decotxt_3 fl" name="other_pg_degree" id="other_pg_degree" value="~$other_pg_degree`"/>
		<i class="btn-rem fl">&nbsp;</i><a href="#" onclick="show_add('other_pg_degree_sect',2);" class="b fl">Remove</a>
	</div>
<div class="sp15">&nbsp;</div>
</div>
<div class="row4 no-margin-padding" ~if $sf_request->getParameter('from_fto') && $edu_level_new neq ''`style="display:none"~/if`>
	<label class="grey" ~if $edu_level_new eq '' or $edu_level_new eq 0`style="color:red!important"~/if`>&nbsp;&nbsp;&nbsp;Highest Degree :</label>
	<select name="Education_Level" id="Education_Level">~if $edu_level_new eq '' or $edu_level_new eq 0`<option value="">Select</option>~/if`~$sf_data->getRaw('education_level')`
	</select>
<div class="sp15">&nbsp;</div>
</div>
<div class="row4 no-margin-padding" name="education" id="education" ~if $sf_request->getParameter('from_fto')`style="display:none"~/if`><a name="edu"></a>
<label class="grey">More about ~if $RELATION eq '1'`my~else`~if $GENDER eq 'F'`her~else`his~/if`~/if` education :</label>
<textarea class="textarea-big fl" id="Educ_Qualification" name="Educ_Qualification" maxlength="1000" ~if $from_edu_link`onload="this.focus();"~/if` onkeyup="return ismaxlength(this);">~$sf_data->getRaw('EDUCATION')`</textarea>
<div class="sp15">&nbsp;</div>
</div>
<div class="row4 no-margin-padding" ~if $sf_request->getParameter('from_fto')`style="display:none"~/if`>
<label class="grey">Work status :</label>
<select name="work_status">
<option value="">Select</option>
~$sf_data->getRaw('work_status_opt')`
</select>
<div class="sp15">&nbsp;</div>
</div>
<div class="row4 no-margin-padding" ~if $sf_request->getParameter('from_fto') && $occ_val neq ''`style="display:none"~/if`>
<label class="grey" ~if $occ_val eq '' or $occ_val eq 0`style="color:red!important"~/if`>Occupation :</label>
<select name="Occupation">~if $occ_val eq '' or $occ_val eq 0`<option value="">Select</option>~/if`~$sf_data->getRaw('occupation')`</select>
<div class="sp15">&nbsp;</div>
</div>
<div class="row4 no-margin-padding">
	<label class="grey">Name of Organization :</label>
	<input type="text" value="~$organisation`" id="organisation" name="organisation" class="decotxt_2" /><br/>
	<span id="organ" style="position:relative;clear:all"></span>
</div>
<div class="sp15">&nbsp;</div>
<div class="row4 no-margin-padding" ~if $sf_request->getParameter('from_fto') && $income_val neq ''`style="display:none"~/if`>
<label class="grey" ~if $income_val eq '' or $income_val eq 0`style="color:red!important"~/if`>&nbsp;&nbsp;Annual Income :</label>
<select  name="Income">~if $income_val eq '' or $income_val eq 0`<option value="">Select</option>~/if`~$sf_data->getRaw('INCOME')`</select>
<div class="sp15">&nbsp;</div>
</div>
<div class="row4 no-margin-padding" ~if $sf_request->getParameter('from_fto')`style="display:none"~/if`>
	<label class="grey">&nbsp;&nbsp;More about ~if $RELATION eq '1'`my~else`~if $GENDER eq 'F'`her~else`his~/if`~/if` work :</label>
	<textarea id="Job_Info" class="textarea-big fl" name="Job_Info" maxlength="1000" onkeyup="return ismaxlength(this);">~$sf_data->getRaw('JOBINFO')`</textarea>
<div class="sp15">&nbsp;</div>
</div>
<div class="row4 no-margin-padding" ~if $sf_request->getParameter('from_fto')`style="display:none"~/if`>
	<label class="grey">Interested in going abroad:</label>
~$sf_data->getRaw('going_abroad_radio')`
<div class="sp15">&nbsp;</div>
</div>
~if $GENDER eq 'F'`
<div class="row4 no-margin-padding" ~if $sf_request->getParameter('from_fto')`style="display:none"~/if`>
	<label class="grey">Plan to work after marriage :</label>
	~$sf_data->getRaw('work_after_marriage_radio')`
</div><div class="sp15">&nbsp;</div>
~/if`

</div>
<script>
	~if $from_edu_link`
	setTimeout(function() { document.getElementById('Educ_Qualification').focus(); }, 1000);
	~/if`
	~if $from_work_link`
	setTimeout(function() { document.getElementById('Job_Info').focus(); }, 1000);
	~/if`
</script>
