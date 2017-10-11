<script>
function editAnul()
{
	document.getElementById("Anul").style.display="block";
}
function editedAnul()
{
        document.getElementById("Anul").style.display="none";
}
function closeLayer()
{
        $.colorbox.close();
        window.location="~$SITE_URL`/profile/viewprofile.php?ownview=1&EditWhatNew=JST";
}
function textplace()
{
	if(document.getElementById("user_name").value == "")
			{
				document.getElementById("user_name").placeholder="Name will not be disclosed";
			}
}
function validate(flag){
			var name_of_user=document.getElementById("user_name").value;

			var allowed_chars = /^[a-zA-Z\.\,\s\']+$/;
			var name_of_user_invalid_chars = 0;

			if(name_of_user != "")
			{
				if(!allowed_chars.test(name_of_user) || trim(name_of_user)=='')
					name_of_user_invalid_chars = 1;
				else
					name_of_user_invalid_chars = 0;
			}
			if(name_of_user_invalid_chars)
			{
				document.getElementById("name_of_user_submit_err").style.display = "block";
				document.getElementById("line_break").style.display = "block";
				return false;
			}
			else
			{
				document.getElementById("name_of_user_submit_err").style.display = "none";
				document.getElementById("line_break").style.display = "none";
			}
		if(flag==1)
		{
			id_type=$("input[name='id_proof_type']:checked").val();
			id_val=$("#id_proof_no").val();
			if(id_val!=''){
			if(!id_type){
			$("#id_err1").css("display","block");
			return false;
			}
			else
			$("#id_err1").css("display","none");
			switch(id_type){
			case "P":
			pattern=/^[a-zA-Z]\d{7}$/;
			break;
			case "U":
			pattern=/^\d{12}$/;
			break;
			case "V":
			pattern=/^[a-zA-Z]{3}\d{7}$/;
			break;
			case "N":
			pattern=/^[a-zA-Z]{5}\d{4}[a-zA-Z]$/;
			break;
			case "D":
			if(id_val.length>18)
			 var invalid=true;
			pattern=/^.*[^\s].*[^\s].*[^\s].*[^\s].*$/;
			break;
			default:
			pattern=/.*/;
			break;
			}
			if(id_val.match(pattern)&&!invalid){
			$("#id_err").css("display","none");
			return true;
			}
			else{
			$("#id_err").css("display","block");
			$("#id_proof_no").focus();
			return false;
			}
			}
			return true;
		}
		return true;
}
</script>
~$sf_data->getRaw('hiddenInput')`
<div class="edit_scrollbox2_1">
<div class="row3 no-margin-padding">
<label class="grey">&nbsp;&nbsp;&nbsp;Posted By :</label>
<select style="width:185px;" name="Relationship">
<option value="1" ~if $RELATION eq "1"`selected~/if`>Self</option>
<option value="2" ~if $RELATION eq "2" or $RELATION eq "2D"`selected~/if`>Parent</option>
<option value="3" ~if $RELATION eq "3"`selected~/if`>Sibling</option>
<option value="4" ~if $RELATION eq "4"`selected~/if`>Relative/Friend</option>
<option value="5" ~if $RELATION eq "5"`selected~/if`>Marriage Bureau</option>
<option value="6" ~if $RELATION eq "6" or $RELATION eq "6D"`selected~/if`>Other</option>
</select>
</div>
<div class="sp15">&nbsp;</div>
<div class="row3 no-margin-padding">
<label class="grey" name="my_height">&nbsp;&nbsp;&nbsp;~if $GENDER eq 'F'`Bride's Name~else`Groom's Name~/if` :</label>
<input type="text" name="username"  id="user_name" onblur="validate(2)" onkeyup="textplace()" maxlength="40" value="~$NAME`" ~if !$NAME` placeholder="Name will not be disclosed"~/if`/>
</div>
<div class="sp15" id="line_break" ~if !$name_of_user_Error` style="display:none" ~/if` class="err" >&nbsp;</div>
<div style="position:absolute; left:204px; top:125px;">
<span id="name_of_user_submit_err" ~if !$name_of_user_Error` style="display:none" ~/if` class="err">
<label>&nbsp;</label>
Full name cannot contain special characters.
</span>
</div>
<div class="sp15">&nbsp;</div>
<div class="row3 no-margin-padding">
<label class="grey"><i class="green-hash">#</i> Date of Birth :</label>
~$DTOFB`
</div>
<div class="sp15">&nbsp;</div>
<div class="row3 no-margin-padding">
<label class="grey"><i class="green-hash">#</i> Gender :</label>
~if $GENDER eq 'F'`Female~else`Male~/if`
</div>
<div class="sp15">&nbsp;</div>
<div class="row3 no-margin-padding">
<label class="grey" name="my_height">&nbsp;&nbsp;&nbsp;Height :</label>
<select style="width:185px;" name="Height" id="myh">
~$sf_data->getRaw('HEIGHT')`
</select>
</div>
<div class="sp15">&nbsp;</div>
<div class="row3 no-margin-padding">
<label class="grey"><i class="green-hash">#</i> Marital Status :</label>
~$MSTATUS_BI`
</div>
<div class="sp5">&nbsp;</div>
~if $MSTATUS eq "M" or $MSTATUS eq "S" or $MSTATUS eq "A"`
~if $anul_entry neq 1`
<div class="row3 no-margin-padding">
<label class="grey">&nbsp;</label>
<span class="b grey_note_box" style="width:58% !important">~if $MSTATUS eq "A"`More details on annulment &nbsp;~elseif $MSTATUS eq "S"`Status of the divorce &nbsp;~elseif $MSTATUS eq "M"`Spouse's consent &nbsp;~/if`<a href="#" class="blink" onclick="editAnul();">Edit</a></span>
</div>
<div class="sp15">&nbsp;</div>
~else`
<div class="row3 no-margin-padding">
<label class="grey">&nbsp;</label>
<span class="b" style="background-color:#f6f5f5;padding:5px;width:500px;border:1px #dedddd solid;">~if $MSTATUS eq "A"`"Marriage annulled by ~$COURT` court on ~$ANUL_DATE`"~else`"~$REASON_MSG`"~/if` &nbsp;<a href="#" class="blink" onclick="editAnul();">Edit</a></span>
</div>
~/if`
~/if`
<div id="Anul" style="display:none;">
<div class="clear"></div>
<div style="padding:5px 0 7px 131px">
<div class="grey_note_box" style="width:87% !important">
~if $anul_entry neq 1`
<span style="font-weight:bold;"> ~if $MSTATUS eq "A"`More details on annulment ~elseif $MSTATUS eq "S"`Status of the divorce ~elseif $MSTATUS eq "M"`Spouse's consent ~/if`</span>
~else`
~if $MSTATUS eq "A"`
<span style="font-weight:bold;"> "Marriage annulled by ~$COURT` court on ~$ANUL_DATE`"</span>
~else`
<span style="font-weight:bold;"> "~$REASON_MSG`"</span>
~/if`
~/if`
<div style="margin:8px 2px 5px 2px; padding:5px; background-color:#FFFFFF;">
~if $MSTATUS eq "A"`
<div class="row1"><label style="width:200px;"><span style="color:#ff0000;">*</span> Court which annulled your marriage </label>: <input type="text" value="~$COURT`" name="COURT" style="width:100px; height:14px;" /></div>
<div class="row1"><label style="width:200px;"><span style="color:#ff0000;">*</span> Date when it was annulled </label>: 
<select class="textbox" size="1" name="Day" style="width:70px;">
<option value="" selected>Day</option>
<option value="01" ~if $day eq "01"` selected ~/if`>01</option>
<option value="02" ~if $day eq "02"` selected ~/if`>02</option>
<option value="03" ~if $day eq "03"` selected ~/if`>03</option>
<option value="04" ~if $day eq "04"` selected ~/if`>04</option>
<option value="05" ~if $day eq "05"` selected ~/if`>05</option>
<option value="06" ~if $day eq "06"` selected ~/if`>06</option>
<option value="07" ~if $day eq "07"` selected ~/if`>07</option>
<option value="08" ~if $day eq "08"` selected ~/if`>08</option>
<option value="09" ~if $day eq "09"` selected ~/if`>09</option>
<option value="10" ~if $day eq "10"` selected ~/if`>10</option>
<option value="11" ~if $day eq "11"` selected ~/if`>11</option>
<option value="12" ~if $day eq "12"` selected ~/if`>12</option>
<option value="13" ~if $day eq "13"` selected ~/if`>13</option>
<option value="14" ~if $day eq "14"` selected ~/if`>14</option>
<option value="15" ~if $day eq "15"` selected ~/if`>15</option>
<option value="16" ~if $day eq "16"` selected ~/if`>16</option>
<option value="17" ~if $day eq "17"` selected ~/if`>17</option>
<option value="18" ~if $day eq "18"` selected ~/if`>18</option>
<option value="19" ~if $day eq "19"` selected ~/if`>19</option>
<option value="20" ~if $day eq "20"` selected ~/if`>20</option>
<option value="21" ~if $day eq "21"` selected ~/if`>21</option>
<option value="22" ~if $day eq "22"` selected ~/if`>22</option>
<option value="23" ~if $day eq "23"` selected ~/if`>23</option>
<option value="24" ~if $day eq "24"` selected ~/if`>24</option>
<option value="25" ~if $day eq "25"` selected ~/if`>25</option>
<option value="26" ~if $day eq "26"` selected ~/if`>26</option>
<option value="27" ~if $day eq "27"` selected ~/if`>27</option>
<option value="28" ~if $day eq "28"` selected ~/if`>28</option>
<option value="29" ~if $day eq "29"` selected ~/if`>29</option>
<option value="30" ~if $day eq "30"` selected ~/if`>30</option>
<option value="31" ~if $day eq "31"` selected ~/if`>31</option>
</select>
<select class="textbox" size="1" name="Month" style="width:70px;">
<option value="" selected>Month</option>
<option value="01" ~if $ANUL_MON eq "01"`selected~/if`>January</option>
<option value="02" ~if $ANUL_MON eq "02"`selected~/if`>February</option>
<option value="03" ~if $ANUL_MON eq "03"`selected~/if`>March</option>
<option value="04" ~if $ANUL_MON eq "04"`selected~/if`>April</option>
<option value="05" ~if $ANUL_MON eq "05"`selected~/if`>May</option>
<option value="06" ~if $ANUL_MON eq "06"`selected~/if`>June</option>
<option value="07" ~if $ANUL_MON eq "07"`selected~/if`>July</option>
<option value="08" ~if $ANUL_MON eq "08"`selected~/if`>August</option>
<option value="09" ~if $ANUL_MON eq "09"`selected~/if`>September</option>
<option value="10" ~if $ANUL_MON eq "10"`selected~/if`>October</option>
<option value="11" ~if $ANUL_MON eq "11"`selected~/if`>November</option>
<option value="12" ~if $ANUL_MON eq "12"`selected~/if`>December</option>
</select>
<select class="textbox" size="1" name="Year" style="width:70px;">
~$sf_data->getRaw('years')`
</select>
</div>
~/if`
<div class="row1">
~if $MSTATUS eq "A"`
<label style="width:200px;">Please mention the reason on which the annulment was pronounced </label>
~/if`
~if $MSTATUS eq "M"`
<label style="width:200px;">Consent </label>
~/if`
~if $MSTATUS eq "S"`
<label style="width:200px;">Please mention the status </label>
~/if`
<div style="vertical-align:top;">: <textarea name="REASON" rows="5" cols="10" style="width:216px; height:40px; vertical-align:top">~$REASON`</textarea></div></div>

<div class="sp15">&nbsp;</div>

</div>
<div class="fl" style="text-align:center;"><input type="button" class="gray_btn" value="Submit" onclick="editedAnul();"/></div><div class="fr"><span style="color:#ff0000; font-weight:bold">* mandatory fields</span></div>
<div class="sp2"></div>
</div>
</div>
</div>
~if $MSTATUS neq 'N'`
<div class="sp15">&nbsp;</div>
<div class="row3 no-margin-padding">
<label class="grey">&nbsp;&nbsp;&nbsp;Have Children :</label>
<input type="radio" class="chbx" style="vertical-align:middle" name="Has_Children" value="N" ~if $HAVECHILD eq "" or $HAVECHILD eq "N"`checked~/if`> No &nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" class="chbx" style="vertical-align:middle" name="Has_Children" value="YT"  ~if $HAVECHILD eq "YT"`checked~/if`> Yes, living together &nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" class="chbx" style="vertical-align:middle" name="Has_Children" value="YS" ~if $HAVECHILD eq "YS"`checked~/if`> 
Yes, living separately &nbsp;&nbsp;&nbsp;&nbsp;
</div>
~/if`
<div class="sp15">&nbsp;</div>
~if $COUNTRY_RES neq '51'`
<div class="row3 no-margin-padding">
<label class="grey">&nbsp;&nbsp;&nbsp;Citizenship :</label>
<select style="width:185px;" name="Citizenship">
~$sf_data->getRaw('CITIZEN_RES')`
</select>
<div class="sp15">&nbsp;</div>
</div>
~/if`
<div class="row3 no-margin-padding">
<label class="grey"> Verification ID :</label>
<span class="fl widthauto no-margin-padding" >
~$sf_data->getRaw('ID_TYPE_RADIO')`
<!--
<input id="verif_voter_id" value="V" name="verif_voter_id" class="chbx vam" type="radio" /> 
Voter ID&nbsp;&nbsp;&nbsp;
<input id="verif_dl" value="D" name="verif_dl" class="chbx vam" type="radio" />
Drivers licence
&nbsp;&nbsp;&nbsp;
<input id="verif_uid" value="U" name="verif_uid" class="chbx vam" type="radio" />
UID
&nbsp;&nbsp;&nbsp;
<input id="verif_passport" value="P" name="verif_passport" class="chbx vam" checked="checked" type="radio" /> 
Passport
&nbsp;&nbsp;&nbsp;
<input id="verif_pan" value="N" name="verif_pan" class="chbx vam" checked="checked" type="radio" /> 
PAN No
-->
<div class="sp5">&nbsp;</div>
<input type="text" name="id_proof_no" id="id_proof_no" value="~$ID_PROOF_NO`"/>
<div class="sp5">&nbsp;</div>
<div class="red_new" id="id_err" style="display:none;">
<img style="vertical-align: bottom;" src="~$IMG_URL`/profile/images/registration_new/alert.gif"/>
&nbsp;Invalid ID has been put. Please enter correct No.<br>&nbsp;&nbsp;&nbsp;&nbsp; Please do not put any spaces betweeen numbers/alphabets.
</div>
<div class="red_new" id="id_err1" style="display:none;">
<img style="vertical-align: bottom;" src="~$IMG_URL`/profile/images/registration_new/alert.gif"/>
&nbsp;Please choose type of ID Proof.
</div>

</span>

</div>

<div class="row3 no-margin-padding">
<LABEL class="grey">&nbsp;</LABEL>
<span style="width:70%;" >This number will not be visible to any other Jeevansathi member. This number will be used for profile authentication and verification purposes</span>
</div>

</div>
<div class="lf note b"><i class="green-hash">#</i><font class="fs13 b"> To edit these fields contact</font>
<a href="#" class="blink b blue" onclick="closeLayer();">Jeevansathi support team</a>
</div>
