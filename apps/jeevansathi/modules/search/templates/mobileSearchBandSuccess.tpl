	<!-- Sub Title -->
	<section class="s-info-bar">
		<div class="pgwrapper">
			<span class="pull-left">Search for your Life Partner</span>
		</div>
	</section>
	
	<!-- Confirmation -->
	<section>
		~assign var='keywd_index' value=','`
		<div class="pgwrapper">
			<div class="js-content">
			<form name = "search_partner" action="~sfConfig::get('app_site_url')`/search/perform" method="post" onsubmit="return checkData();">
				<div class="frm-container">
					~foreach from=$dataArray['gender'] item=value key=k`
						<div class="pull-left">
                                                	<input type="radio" value="~$value.VALUE`" class="mrtp3 mrrgt5" name="gender" ~if $value.VALUE eq $dataArray['selectedValues']['selectedGender']` checked="yes"~/if`>
                                        	</div>
						<div class="pull-left mrright_mob5 mrtop_mob3">
                                                	~$value.LABEL`
                                        	</div>

                                      	~/foreach`
                		</div>
				<div class="frm-container">
					<div class="row04"><div>
							<select name = "religion" id="religion">
								~foreach from=$dataArray['religion'] item=value key=k`
									~if $dataArray['selectedValues']['selectedReligion']|contains:$keywd_index`
                                                                        	<option value = "~$value.VALUE`" ~if $value.VALUE eq ''` selected="yes" ~/if`>~$value.LABEL`</option>
									~else`
                                                                        	<option value = "~$value.VALUE`" ~if $value.VALUE eq $dataArray['selectedValues']['selectedReligion']` selected="yes" ~/if`>~$value.LABEL`</option>
									~/if`
                                                                ~/foreach`
							</select>
							</div>
					</div>
				</div>
				<div class="frm-container">
					<div class="row05">
						<div>
							<select id = "lage" name = "lage">
								~foreach from=$dataArray['age'] item=value key=k`
									<option value = "~$value`" ~if $value eq $dataArray['selectedValues']['selectedLage']` selected="yes" ~/if`>~$value` years</option>
								~/foreach`
							</select>
						</div>
						<div align="center" class="mid">to</div>
						<div>
							<select id = "hage" name = "hage">
								~foreach from=$dataArray['age'] item=value key=k`
									<option value = "~$value`" ~if $value eq $dataArray['selectedValues']['selectedHage']` selected="yes" ~/if`>~$value` years</option>
								~/foreach`
							</select>
						</div>
					</div>
				</div>
				<div class="frm-container hide" id = "age_error">
					<div class="row04" style = "font-weight:bold; color:#FF0000;">
						<div>Lower age limit should be less than higher age limit</div>
					</div>
				</div>
				<div class="frm-container">
					<div class="row04"><div>
							<select name="location">
								~foreach from=$dataArray['location'] item=value key=k`
									~if $value.VALUE eq 'AN'`<option disabled = "yes">Indian States</option>~/if`
                                                                        <option value = "~$value.VALUE`" ~if $value.VALUE eq $dataArray['selectedValues']['selectedCity_Country']` selected="yes" ~/if`>~$value.LABEL`</option>
									~if $value.VALUE eq 22`<option disabled = "yes">Major Indian Cities</option>~/if`
									~if $value.VALUE eq 'WB'`<option disabled = "yes">All Indian Cities</option>~/if`
                                                                ~/foreach`
							</select>
							</div>
					</div>
				</div>
				<div class="frm-container">
					<div class="row04"><div>
							<select name="mtongue">
								~foreach from=$dataArray['mtongue'] item=value key=k`
									~if ~$value.ISREGION` eq 'Y'`<option value = "" disabled = "yes"></option>~/if`
                                                                        <option value = "~$value.VALUE`" ~if $value.VALUE eq $dataArray['selectedValues']['selectedMtongue']` selected="yes" ~/if` ~if ~$value.ISREGION` eq 'Y'` style="font-weight: bold;color:#E06400" ~/if`>~$value.LABEL`</option>
                                                                ~/foreach`
							</select>
							</div>
					</div>
				</div>
				<div class="frm-container">
					<div class="row04"><div>
							<select id="caste" name = "caste">
								~foreach from=$dataArray['caste'] item=value key=k`
                                                                        ~if ~$value.ISALL` eq 'Y'`<option value = "" disabled = "yes"></option>~/if`
                                                                        <option value = "~$value.VALUE`" ~if $value.VALUE eq $dataArray['selectedValues']['selectedCaste']` selected="yes" ~/if` ~if ~$value.ISALL` eq 'Y'` style="background-color:#FFD84F" ~/if` ~if ~$value.ISGROUP` eq 'Y'`style = "color:#E06400"~/if` ~if ~$value.ISCHILD` eq 'Y'`style = "padding-left:25px;"~/if`>~if ~$value.ISCHILD` eq 'Y'`- ~/if`~$value.LABEL`~if ~$value.ISGROUP` eq 'Y'` - All~/if`</option>
                                                                ~/foreach`
							</select>
							</div>
					</div>
				</div>
				<div class="frm-container">
					<div class="row04"><div>
							<select name="mstatus">
								~foreach from=$dataArray['mstatus'] item=value key=k`
                                                                        <option value = "~$value.VALUE`" ~if $value.VALUE eq $dataArray['selectedValues']['selectedMstatus']` selected="yes" ~/if`>~$value.LABEL`</option>
                                                                ~/foreach`
							</select>
							</div>
					</div>
				</div>
				<div class="frm-container">
					<div class="row05">
						<div><select name = "lheight" id = "lheight">
								~foreach from=$dataArray['height'] item=value key=k`
                                                                        <option value = "~$value.VALUE`" ~if $value.VALUE eq $dataArray['selectedValues']['selectedLheight']` selected="yes" ~/if`>~$value.LABEL`</option>
                                                                ~/foreach`
							</select>
						</div>
						<div align="center" class="mid">to</div>
						<div><select name = "hheight" id = "hheight">
								~foreach from=$dataArray['height'] item=value key=k`
                                                                        <option value = "~$value.VALUE`" ~if $value.VALUE eq $dataArray['selectedValues']['selectedHheight']` selected="yes" ~/if`>~$value.LABEL`</option>
                                                                ~/foreach`
						</select>
						</div>
					</div>
				</div>
				<div class="frm-container hide" id = "height_error1">
					<div class="row05" style = "font-weight:bold; color:#FF0000;">
						<div id = "height_error2">Please select minimum height</div>
						<div id = "height_error5">&nbsp;</div>
						<div align="center" class="mid" style = "line-height:10px;">&nbsp;</div>
						<div id = "height_error3">Please select maximum height</div>
					</div>
				</div>
				<div class="frm-container hide" id = "height_error4">
					<div class="row04" style = "font-weight:bold; color:#FF0000;">
						<div>Lower height limit should be less than higher height limit</div>
					</div>
				</div>
				<div class="frm-container">
					<div class="row04">
						<div>
							<a href="javascript:void(0)" id="more-btn" class="normal-btn">
								<span id = "icon_plus_minus" class="iconplus">&nbsp;</span><span class="icon-ln">&nbsp;</span>
								<span class="lnk-txt" id = "btn-text">More options</span>
							</a>
							<input type = "hidden" name = "more_options_btn" id = "more_options_btn" value = "N" />
						</div>
					</div>
				</div>
			<div class = "hide" id = "more_search_params">
				<div class="frm-container">
					<div class="row05">
						<div><select name = "lincome" id="lincome">
							~foreach from=$dataArray['income'] item=value key=k`
								~if $value.VALUE neq 19`
								~if $value.VALUE eq '0'`
                                                                        ~assign var='income_val' value='NONE'`
                                                                ~elseif $value.VALUE eq ''`
                                                                        ~assign var='income_val' value='BLANK'`
                                                                ~else`
                                                                        ~assign var='income_val' value=$value.VALUE`
                                                                ~/if`
                                                                ~if $dataArray['selectedValues']['selectedLincome'] eq '0'`
                                                                        ~assign var='selected_income_val' value='NONE'`
                                                                ~elseif $dataArray['selectedValues']['selectedLincome'] eq ''`
                                                                        ~assign var='selected_income_val' value='BLANK'`
                                                                ~else`
                                                                        ~assign var='selected_income_val' value=$dataArray['selectedValues']['selectedLincome']`
                                                                ~/if`
								<option value = "~$value.VALUE`" ~if $income_val eq $selected_income_val` selected="yes" ~/if`>~$value.LABEL`</option>
								~/if`
							~/foreach`
							</select>
						</div>
						<div align="center" class="mid">to</div>
						<div>
							<select name = "hincome" id="hincome">
							~foreach from=$dataArray['income'] item=value key=k`
								~if $value.VALUE eq '0'`
									~assign var='income_val' value='NONE'`
								~elseif $value.VALUE eq ''`
									~assign var='income_val' value='BLANK'`
								~else`
									~assign var='income_val' value=$value.VALUE`
								~/if`
								~if $dataArray['selectedValues']['selectedHincome'] eq '0'`
									~assign var='selected_income_val' value='NONE'`
								~elseif $dataArray['selectedValues']['selectedHincome'] eq ''`
									~assign var='selected_income_val' value='BLANK'`
								~else`
									~assign var='selected_income_val' value=$dataArray['selectedValues']['selectedHincome']`
								~/if`
								<option value = "~$value.VALUE`" ~if $income_val eq $selected_income_val` selected="yes" ~/if`>~$value.LABEL`</option>
							~/foreach`
							</select>
						</div>
					</div>
				</div>
				<div class="frm-container hide" id = "income_error1">
					<div class="row05" style = "font-weight:bold; color:#FF0000;">
						<div id = "income_error2">Please select minimum income</div>
						<div id = "income_error5">&nbsp;</div>
						<div align="center" class="mid" style = "line-height:10px;">&nbsp;</div>
						<div id = "income_error3">Please select maximum income</div>
					</div>
				</div>
				<div class="frm-container hide" id = "income_error4">
					<div class="row04" style = "font-weight:bold; color:#FF0000;">
						<div>Lower income limit should be less than higher income limit</div>
					</div>
				</div>
				<div class="frm-container">
					<div class="row04"><div>
								<select name = "occupation">
								~foreach from=$dataArray['occupation'] item=value key=k`
                                                                        <option value = "~$value.VALUE`" ~if $value.VALUE eq $dataArray['selectedValues']['selectedOccupationGrouping']` selected="yes" ~/if`>~$value.LABEL`</option>
                                                                ~/foreach`
								</select>
							</div>
					</div>
				</div>
				<div class="frm-container">
					<div class="row04"><div>
								<select name = "education">
								~foreach from=$dataArray['education'] item=value key=k`
                                                                        <option value = "~$value.VALUE`" ~if $value.VALUE eq $dataArray['selectedValues']['selectedEducationGrouping']` selected="yes" ~/if`>~$value.LABEL`</option>
                                                                ~/foreach`
								</select>
							</div>
					</div>
				</div>
				<div class="frm-container">
					<div class="row04"><div>
								<select name = "diet">
								~foreach from=$dataArray['diet'] item=value key=k`
                                                                        <option value = "~$value.VALUE`" ~if $value.VALUE eq $dataArray['selectedValues']['selectedDiet']` selected="yes" ~/if`>~$value.LABEL`</option>
                                                                ~/foreach`
								</select>
							</div>
					</div>
				</div>
			</div>
				<div class="frm-container">
					<div class="row04"><div><input type="checkbox" name="Photos" value="Y" ~if $dataArray['selectedValues']['selectedHavePhoto'] eq 'Y'`checked="yes"~/if` /> With photo only</div></div>
				</div>
				<div class="frm-container">
					<div class="row04"><div><input type="submit" value="Search" class="btn actived-btn" /></div></div>
				</div>

				<input type="hidden" name="CLICKTIME"  value="1" />
                        	<input type="hidden" name="MOBILE_SEARCH" value="Y" />
				~foreach from=$dataArray['religionCasteDependency'] item=value key=k`
					<input type = "hidden" id = "religion~$value.RELIGION_VALUE`" value = "~$value.CASTE_STRING`" />
				~/foreach`
			</form>
				<br />
				<div class="frm-container">
					<section class="gray-box" id="boxContainer">
						<div style="padding:10px">
							<label><strong>or search by profile id</strong></label>
							<form name = "search_profile" action="" method="post" id = "search_profile">
							<div class="pull-left" style="margin-right:0;width:70%"><input type="text" value="" class="nor-rlrb" style="margin-top:0" id = "search_username" /></div>
							<div class="pull-right" style="text-align:right"><input type="button" value="Search" id="search_by_profileid_btn" class="btn actived-btn" /></div>
							</form>
						</div>
                                            <div class="clr"></div>
                                            <span style="color: red; display: none; padding:9px; font-size: 13px" id="email_error">Email ID is not allowed. Please enter a profile id</span>
					</section>
				</div>
			~if $loggedIn`
				<div class="frm-container">
                                        <div class="row04">
                                                <div>
                                                        <a href="~sfConfig::get('app_site_url')`/search/partnermatches" class="normal-btn w100 pull-left">
                                                                <span class="icon-dpm">&nbsp;</span><span class="icon-ln">&nbsp;</span>
                                                                <span class="lnk-txt fc444">Desired partner matches</span>
                                                        </a>
                                                </div>
                                        </div>
                                </div>
                                <div class="frm-container">
                                        <div class="row04">
                                                <div>
                                                        <a href="~sfConfig::get('app_site_url')`/search/twoway" class="normal-btn w100 pull-left">
                                                                <span class="icon-mlm">&nbsp;</span><span class="icon-ln">&nbsp;</span>
                                                                <span class="lnk-txt fc444">Mutual Matches</span>
                                                        </a>
                                                </div>
                                        </div>
                                </div>
			~else`
				<div class="frm-container">
                                        <div class="row04">
                                                <div><a href="/jsmb/register.php?source=mobreg3" class="btn pre-next-btn" style="width:100%;font-size:16px;font-weight:bold;padding:7px 12px;">New user? Register now</a></div>
                                        </div>
                                </div>
				<div class="frm-container">
					<div class="row07">
						~foreach from=$seoLinks item=value key=k`
							<div class="search-tag"><a href="~sfConfig::get('app_site_url')`~$value.0`" style = "text-decoration:none; color: #6700FC;">~$value.1`</a></div>
                                		~/foreach`
					</div>
				</div>
			~/if`
			</div>
		</div> 
	</section>
<script type = "text/javascript">
	var SITE_URL = "~sfConfig::get('app_site_url')`";
</script>
