<script>
var page="";
var user_login=1;
</script>

	<noscript>
		<div style="position:fixed;z-index:1000;width:100%">
			<div style="text-align:center;padding-bottom:3px;font-family:verdana,Arial;font-size:12px;font-size-adjust:none;font-stretch:normal;font-style:normal;font-variant:normal;font-weight:normal;line-height:normal;background-color:#E5E5E5;">
				<b>
					<img src="~$IMG_URL`/profile/images/registration_new/error.gif" width="23" height="20"> Javascript is disabled in your browser.Due  to this certain functionalities will not work.
					<a href="~$IMG_URL`/profile/js_help.htm" target="_blank">Click Here</a> , to know how  to enable it.
				</b>
			</div>
		</div>
	</noscript>
	~if $REG_P6 neq '1'`
		~if ~$REVAMP_HEAD` eq '1'`
			~include_partial("global/header")`
		~/if`
	~/if`

	~if $REG_P6 eq '1'`
	<p class="tp_bg sprtereg"></p>
<div class="reg_cont">
	~include_partial("register/regHeader",[SITE_URL=>$SITE_URL])`
	<h4 class="drk_gry nfs16">
		Set Filters so that only relevant profiles are able to send you interests/contact you.
	</h4>
~else`
<div id="main_cont">	
    
<!--pink strip ends here-->
  <p class="clr_4"></p>
<div id="topSearchBand"></div>
~include_partial('global/sub_header')`	
	~/if`
	<div class="clr cl_5"></div>

	
	<form name="myform" id="myform" onSubmit="return false"> 
		<input type="hidden" id="fromPage" name="fromPage" value="~$fromPage`">
  	<input type="hidden" id="Filterid" name="Filterid" value="~$Filterid`">
	<input type="hidden" name="profilechecksum" value="~$profilechecksum`">
	<input type="hidden" id="crmback" name="crmback" value="~$crmback`">
	<input type="hidden" id="cid" name="cid" value="~$cid`">
	<input type="hidden" name="pid" value="~$pid`">
	<input type="hidden" id="crmredirect" name="crmredirect" value="~$crmredirect`">
	<input type="hidden" name="from_reg" id="from_reg" value="~$REG_P6`">
	<input type="hidden" name="isMobile" id="isMobile" value="~$isMobile`">
	<input type="hidden" name="img_url" value="~$IMG_URL`/profile/images/registration_new">
	~if $REG_P6 neq '1'`
	<div style="display: block;" id="PRI_SET">
		<div class="filter-title fl">Set Filters so that only relevant profiles are able to send you interests/contact you</div>
	<div class="filter-example fl">For example : if you set an Age Filter of 24 to 26, a person who is 27 years old will not be able to send you an interest/view your contact numbers</div>
	<div class="clr"></div>
	~/if`
		<a name="top_revamp"></a>
	<div class="main_form_cont pos_rltv" style="width:770px;~if $REG_P6 neq '1'`margin-top:4px;~/if`">
~if $IS_FTO_LIVE and $REG_P6 eq '1'`
		<div class="fto-notification-box box3">
			<p style="color:#bc001d">Complete the form and 
			     Get Jeevansathi Paid Membership for <span style="font-family:WebRupee; color:#bc001d">&#8377;</span><span style=" text-decoration: line-through; color:#000 "><span style="color:#bc001d">1100</span></span><strong> FREE</strong> 
				 </p>
				 <div style=" height:5px"></div>
				 <p>See e-mail IDs / Phone numbers of people you like.</p>
			 </div>
			 <div style="width:100%" class="fl">
				 <div class="fto-arrow-down sprtereg">&nbsp;</div>
			 </div>
		~/if`
		<i class="d_t_l pos_abs h_w_6 "></i>
		<i class="d_t_r pos_abs h_w_6  p_tr_0"></i>
		<i class="d_b_r pos_abs h_w_6  p_br_0"></i>
		<i class="d_b_l pos_abs h_w_6  p_bl_0"></i>
		<div class="clr cl_5"></div>
		<div style="display:none;" id="confirm">
			~if $APShowMessage eq '1'`
				<div class="lf" style="padding:5px; width:560px;margin-left:2px;padding-left:202px; background-color:#ffffbb;">
				                                        <div class="lf">
				                                                                                        <img src="~$IMG_URL`/profile/images/confirm.gif" hspace="10" vspace="0" align="left">
				                                                                                                                                </div>
				                                                                                                                                                                        <div class="lf t18 b" style="width:382px;margin-left:10px;padding-top:5px;" id ="myspan">Your request has been sent to screening team, this will be updated once approved.</div>
				                                                                                                                                                                                                                <div class="rf b"><a href="#" onclick ="hide_confirmation(); return false;" style="position:relative;z-index:10000;">[x]</a></div>
				                                                                                                                                                                                                                                                </div>
				                                                                                                                                                               ~else`
			~if $REG_P6 neq '1'`
				<div class="lf" style="padding:5px; width:560px;margin-left:2px;padding-left:202px; background-color:#ffffbb;">
					<div class="lf">
						<img src="~$IMG_URL`/profile/images/confirm.gif" hspace="10" vspace="0" align="left">
					</div>
					<div class="lf t18 b" style="width:382px;margin-left:10px;padding-top:5px;" id ="myspan">Your filters have been set.</div>
					<div class="rf b"><a href="#" onclick ="hide_confirmation(); return false;" style="position:relative;z-index:10000;">[x]</a></div>
				</div>
			~/if`
			~/if`
		</div>
		
		<div class="orng fr b f_13" style="width:190px; background:url(~$IMG_URL`/profile/images/registration_revamp_new/arrw.gif) 50% 100% no-repeat; padding-bottom:13px; margin:8px 10px  8px 0;">
				Setting filter means only people satisfying your criteria will be able to contact you
		</div>
		<table width="90%" class="stfltrtble" border="0" cellspacing="0" cellpadding="0">
			<tr>
			    <td class="b textr_stfltr">Marital Status :</td>
			    <td>
				    <div>Only Prospective ~if $gli eq 'M'`brides~else`grooms~/if` with the following <b>Marital Status</b> can express interest in me</div>
				    <br class="clr" />
				    ~if $REG_P6`
				    <div class="b"><span class="b" id="mstatus_message"></span>~$mstatus`<a href="/profile/edit_dpp.php?width=700&FLAG=partner&profilechecksum=~$profilechecksum`&filter=mstatus&from_filter=1&from_reg=1&gli=~$gli`&fromPage=~$fromPage`" class="thickbox chng_lnk" onmouseover="javascript:mouseOver('mstatus_help');" onmouseout="javascript:mouseOut('mstatus_help');">[ Change ]</a>
				    ~else`
				    <div class="b"><span class="b" id="mstatus_message"></span>~$mstatus`<a href="/profile/edit_dpp.php?width=700&FLAG=partner&profilechecksum=~$profilechecksum`&filter=mstatus&from_filter=1&gli=~$gli`&APeditID=~$APeditID`&fromPage=~$fromPage`" class="thickbox chng_lnk" onmouseover="javascript:mouseOver('mstatus_help');" onmouseout="javascript:mouseOut('mstatus_help');">[ Change ]</a>
				    ~/if`
					    <div class="hintbox-new warn" style="position: absolute; z-index: 200; display: none;" id="mstatus_help">
						    <div class="new-new">
							    <div class="forarrow-new"></div>
								This will also change marital status for the Desired Partner Profile that you have selected.
						    </div>
					    </div>
				    </div>
			    </td>
			    <td class="stas_fltr">
					~if $filter_redirect eq '1'`
					<input type="checkbox" id="mstatus_filter" name="mstatus_filter" value="Y" class="checkBoxClicked" checked  /> 
					~if $mstatus_flag eq "Y"`
			    	<span id="MSTATUS_text" class="filter-set">Filter had been set</span>
			    	~else`
			    	<span id="MSTATUS_text">Set this as a Filter</span>
					~/if`
					</td>
					~else`
			    	<input type="checkbox" id="mstatus_filter" name="mstatus_filter" value="Y" class="checkBoxClicked" ~if $mstatus_flag eq "Y"` checked ~/if`  />
			    	~if $mstatus_flag eq "Y"`
			    	<span id="MSTATUS_text" class="filter-set">Filter had been set</span>
			    	~else`
			    	<span id="MSTATUS_text">Set this as a Filter</span>
			    	~/if`
			    	~/if`
					</td>
					
			</tr>
			<tr>
			    <td class="b textr_stfltr">Religion :</td>
			    <td>
				    <div>Only Prospective ~if $gli eq 'M'`brides~else`grooms~/if` with the following <b>Religions</b> can express interest in me</div><br class="clr" />
				    <div class="b"><span class="b" id="religion_message"></span>~$religion`
				    ~if $REG_P6`
					<a href="/profile/edit_dpp.php?width=700&FLAG=partner&profilechecksum=~$profilechecksum`&filter=religion&from_filter=1&from_reg=1&gli=~$gli`&fromPage=~$fromPage`" class="thickbox chng_lnk" onmouseover="javascript:mouseOver('religion_help');" onmouseout="javascript:mouseOut('religion_help');">[ Change ]</a>			
				    ~else`
					<a href="/profile/edit_dpp.php?width=700&FLAG=partner&profilechecksum=~$profilechecksum`&filter=religion&from_filter=1&gli=~$gli`&APeditID=~$APeditID`&fromPage=~$fromPage`" class="thickbox chng_lnk" onmouseover="javascript:mouseOver('religion_help');" onmouseout="javascript:mouseOut('religion_help');">[ Change ]</a>
				    ~/if`
					    <div class="hintbox-new warn" style="position: absolute; z-index: 200; display: none;" id="religion_help">
						    <div class="new-new">
							    <div class="forarrow-new"></div>
								This will also change religion for the Desired Partner Profile that you have selected.
						    </div>
					    </div>
				    </div>
			    </td>
			    <td class="stas_fltr">
					~if $religion_check eq '1'`
					<input type="checkbox" id="religion_filter" name="religion_filter" value="Y" class="checkBoxClicked" checked  /> 
					~if $religion_flag eq "Y"`
			    	<span id="RELIGION_text" class="filter-set">Filter had been set</span>
			    	~else`
			    	<span id="RELIGION_text">Set this as a Filter</span>
					~/if`
					</td>
					~else`
					<input type="checkbox" id="religion_filter" name="religion_filter" class="checkBoxClicked" ~if $religion_flag eq "Y"` checked  ~/if` value="Y" /> 
					~if $religion_flag eq "Y"`
			    	<span id="RELIGION_text" class="filter-set">Filter had been set</span>
			    	~else`
			    	<span id="RELIGION_text">Set this as a Filter</span>
			    	~/if`
			    	~/if`
					</td>
					
			</tr>
			<tr>
			    <td class="b textr_stfltr">Caste :</td>
			    <td>
				    <div>Only Prospective ~if $gli eq 'M'`brides~else`grooms~/if` with the following <b>Caste</b> can express interest in me</div><br class="clr" />
				    <div class="b"><span class="b" id="caste_message"></span>~$caste`
				    ~if $REG_P6`
					<a href="/profile/edit_dpp.php?width=700&FLAG=partner&profilechecksum=~$profilechecksum`&filter=caste&from_filter=1&from_reg=1&gli=~$gli`&fromPage=~$fromPage`" class="thickbox chng_lnk" onmouseover="javascript:mouseOver('caste_help');" onmouseout="javascript:mouseOut('caste_help');">[ Change ]</a>		
				     ~else`
					<a href="/profile/edit_dpp.php?width=700&FLAG=partner&profilechecksum=~$profilechecksum`&filter=caste&from_filter=1&gli=~$gli`&APeditID=~$APeditID`&fromPage=~$fromPage`" class="thickbox chng_lnk" onmouseover="javascript:mouseOver('caste_help');" onmouseout="javascript:mouseOut('caste_help');">[ Change ]</a>
				     ~/if`
					    <div class="hintbox-new warn" style="position: absolute; z-index: 200; display: none;" id="caste_help">
						    <div class="new-new">
							    <div class="forarrow-new"></div>
								This will also change caste for the Desired Partner Profile that you have selected.
						    </div>
					    </div>
				    </div>
			    </td>
			    <td class="stas_fltr"><input type="checkbox" id="caste_filter" name="caste_filter" class="checkBoxClicked" ~if $caste_flag eq "Y"` checked  ~/if` value="Y" />
					~if $caste_flag eq "Y"`
			    	<span id="CASTE_text" class="filter-set">Filter had been set</span>
			    	~else`
			    	<span id="CASTE_text">Set this as a Filter</span>
			    	~/if`
					</td>
					
			</tr>
			<tr>
			    <td class="b textr_stfltr">Age :</td>
			    <td>
				    <div>Only Prospective ~if $gli eq 'M'`brides~else`grooms~/if` with the following <b>Age</b> can express interest in me</div><br class="clr" />
				    <div class="b"><span class="b" id="age_message"></span>~$lage` to ~$hage` 
				    ~if $REG_P6`
				    		<a href="/profile/edit_dpp.php?width=700&FLAG=partner&profilechecksum=~$profilechecksum`&filter=age&from_filter=1&from_reg=1&gli=~$gli`&fromPage=~$fromPage`" class="thickbox chng_lnk" onmouseover="javascript:mouseOver('age_help');" onmouseout="javascript:mouseOut('age_help');">[ Change ]</a>
				    ~else`
				    		<a href="/profile/edit_dpp.php?width=700&FLAG=partner&profilechecksum=~$profilechecksum`&filter=age&from_filter=1&gli=~$gli`&APeditID=~$APeditID`&fromPage=~$fromPage`" class="thickbox chng_lnk" onmouseover="javascript:mouseOver('age_help');" onmouseout="javascript:mouseOut('age_help');">[ Change ]</a>
				    ~/if`
					    <div class="hintbox-new warn" style="position: absolute; z-index: 200; display: none;" id="age_help">
						    <div class="new-new">
							    <div class="forarrow-new"></div>
								This will also change age for the Desired Partner Profile that you have selected.
						    </div>
					    </div>
				    </div>
			    </td>
			    <td class="stas_fltr"><input type="checkbox" id="age_filter" name="age_filter" class="checkBoxClicked" ~if $age_flag eq "Y"` checked ~/if` value="Y"/>
			     	~if $age_flag eq "Y"`
			    	<span id="AGE_text" class="filter-set">Filter had been set</span>
			    	~else`
			    	<span id="AGE_text">Set this as a Filter</span>
			    	~/if`
					</td>
					
			</tr>
			<tr>
			    <td class="b textr_stfltr">Income :</td>
			    <td>
				    <div>Only Prospective ~if $gli eq 'M'`brides~else`grooms~/if` with the following <b>Income</b> can express interest in me</div><br class="clr" />
				    ~if $REG_P6`
					    <div class="b" id="income_value"><span class="b" id="income_message"></span>~$income|decodevar`<a href="/profile/edit_dpp.php?width=700&FLAG=partner&profilechecksum=~$profilechecksum`&filter=income&from_filter=1&from_reg=1&gli=~$gli`&fromPage=~$fromPage`" class="thickbox chng_lnk" onmouseover="javascript:mouseOver('income_help');" onmouseout="javascript:mouseOut('income_help');">[ Change ]</a>
				    ~else`
					    <div class="b" id="income_value"><span class="b" id="income_message"></span>~$income|decodevar`<a href="/profile/edit_dpp.php?width=700&FLAG=partner&profilechecksum=~$profilechecksum`&filter=income&from_filter=1&gli=~$gli`&APeditID=~$APeditID`&fromPage=~$fromPage`" class="thickbox chng_lnk" onmouseover="javascript:mouseOver('income_help');" onmouseout="javascript:mouseOut('income_help');">[ Change ]</a>
				    ~/if`
					    <div class="hintbox-new warn" style="position: absolute; z-index: 200; display: none;" id="income_help">
						    <div class="new-new">
							    <div class="forarrow-new"></div>
								This will also change income for the Desired Partner Profile that you have selected.
						    </div>
					    </div>
				    </div>
			    </td>
			    <td class="stas_fltr"><input type="checkbox" id="income_filter" name="income_filter" value="Y" class="checkBoxClicked" ~if $income_flag eq "Y"` checked  ~/if`/> 
					~if $income_flag eq "Y"`
			    	<span id="INCOME_text" class="filter-set">Filter had been set</span>
			    	~else`
			    	<span id="INCOME_text">Set this as a Filter</span>
			    	~/if`
					</td>
					
			</tr>
			<tr>
			    <td class="b textr_stfltr">Community :</td>
			    <td>
				    <div>Only Prospective ~if $gli eq 'M'`brides~else`grooms~/if` with the following <b>Communities</b> can express interest in me</div>
				    <br class="clr" />
				    ~if $REG_P6`
				    	<div class="b"><span class="b" id="mtongue_message"></span>~$mtongue|decodevar`<a href="/profile/edit_dpp.php?width=700&FLAG=partner&profilechecksum=~$profilechecksum`&filter=community&from_filter=1&from_reg=1&gli=~$gli`&fromPage=~$fromPage`" class="thickbox chng_lnk" onmouseover="javascript:mouseOver('communities_help');" onmouseout="javascript:mouseOut('communities_help');">[ Change ]</a>
				     ~else`
				    	<div class="b"><span class="b" id="mtongue_message"></span>~$mtongue|decodevar`<a href="/profile/edit_dpp.php?width=700&FLAG=partner&profilechecksum=~$profilechecksum`&filter=community&from_filter=1&gli=~$gli`&APeditID=~$APeditID`&fromPage=~$fromPage`" class="thickbox chng_lnk" onmouseover="javascript:mouseOver('communities_help');" onmouseout="javascript:mouseOut('communities_help');">[ Change ]</a>
					~/if`
					    <div class="hintbox-new warn" style="position: absolute; z-index: 200; display: none;" id="communities_help">
						    <div class="new-new">
							    <div class="forarrow-new"></div>
								This will also change communities for the Desired Partner Profile that you have selected.
						    </div>
					    </div>
				    </div>
			    </td>
			    <td class="stas_fltr"><input type="checkbox" id="mtongue_filter" name="mtongue_filter" value="Y"  class="checkBoxClicked" ~if $mtongue_flag eq "Y"` checked  ~/if` /> 
					~if $mtongue_flag eq "Y"`
			    	<span id="MTONGUE_text" class="filter-set">Filter had been set</span>
			    	~else`
			    	<span id="MTONGUE_text">Set this as a Filter</span>
			    	~/if`
					</td>
					
			</tr>
			<tr>
			    <td class="b textr_stfltr">Country :</td>
			    <td>
				<div>Only Prospective ~if $gli eq 'M'`brides~else`grooms~/if` with the following <b>Country</b> can express interest in me</div><br class="clr" />
				    ~if $REG_P6`
					<div class="b"><span class="b" id="country_res_message"></span>~$country_res`<a href="/profile/edit_dpp.php?width=700&FLAG=partner&profilechecksum=~$profilechecksum`&filter=country&from_filter=1&from_reg=1&gli=~$gli`&fromPage=~$fromPage`" class="thickbox chng_lnk" onmouseover="javascript:mouseOver('country_help');" onmouseout="javascript:mouseOut('country_help');" >[ Change ]</a>
				    ~else`
					<div class="b"><span class="b" id="country_res_message"></span>~$country_res`<a href="/profile/edit_dpp.php?width=700&FLAG=partner&profilechecksum=~$profilechecksum`&filter=country&from_filter=1&gli=~$gli`&APeditID=~$APeditID`&fromPage=~$fromPage`" class="thickbox chng_lnk" onmouseover="javascript:mouseOver('country_help');" onmouseout="javascript:mouseOut('country_help');" >[ Change ]</a>
				     ~/if`
				    <div class="hintbox-new warn" style="position: absolute; z-index: 200; display: none;" id="country_help">
					    <div class="new-new">
						    <div class="forarrow-new"></div>
							This will also change country for the Desired Partner Profile that you have selected.
					    </div>
				    </div>
				</div>
			    </td>
			    <td class="stas_fltr"><input type="checkbox" id="country_res_filter" name="country_res_filter" value="Y" class="checkBoxClicked" ~if $country_flag eq "Y"` checked  ~/if` /> 
					~if $country_flag eq "Y"`
			    	<span id="COUNTRY_RES_text" class="filter-set">Filter had been set</span>
			    	~else`
			    	<span id="COUNTRY_RES_text">Set this as a Filter</span>
			    	~/if`
					</td>
					
			</tr>
			<tr>	
			    <td class="b textr_stfltr">City :</td>
			    <td>
				   <div>Only Prospective ~if $gli eq 'M'`brides~else`grooms~/if` with the following <b>City</b> can express interest in me</div><br class="clr" />
			     ~if $REG_P6`
					   <div class="b"><span class="b" id="city_res_message"></span>~$city`<a href="/profile/edit_dpp.php?width=700&FLAG=partner&profilechecksum=~$profilechecksum`&filter=city&from_filter=1&from_reg=1gli=~$gli`&fromPage=~$fromPage`" class="thickbox chng_lnk" onmouseover="javascript:mouseOver('city_help');" onmouseout="javascript:mouseOut('city_help');" >[ Change ]</a>
			      ~else`
				  	 <div class="b"><span class="b" id="city_res_message"></span>~$city`<a href="/profile/edit_dpp.php?width=700&FLAG=partner&profilechecksum=~$profilechecksum`&filter=city&from_filter=1&gli=~$gli`&APeditID=~$APeditID`&fromPage=~$fromPage`" class="thickbox chng_lnk" onmouseover="javascript:mouseOver('city_help');" onmouseout="javascript:mouseOut('city_help');" >[ Change ]</a>
			      ~/if`
				    <div class="hintbox-new warn" style="position: absolute; z-index: 200; display: none;" id="city_help">
					    <div class="new-new">
						    <div class="forarrow-new"></div>
							This will also change city for the Desired Partner Profile that you have selected.
					    </div>
				    </div>
				</div>
			    </td>
			    <td class="stas_fltr"><input type="checkbox" id="city_res_filter" name="city_res_filter" value="Y" class="checkBoxClicked" ~if $city_flag eq "Y"` checked  ~/if`/>
					~if $city_flag eq "Y"`
			    	<span id="CITY_RES_text" class="filter-set">Filter had been set</span>
			    	~else`
			    	<span id="CITY_RES_text">Set this as a Filter</span>
			    	~/if`
					</td>
					
			</tr>
		</table>
		<div class="clr"></div>
		<div align="center">
		~if $REG_P6 eq '1'`
				<input onclick="return validate7('normal');" id="Submit" name="Sub" value="Save" type="submit" class="nsubbtn" border="0" style="margin:30px 1px 30px 102px;" >
				~if !$IS_FTO_LIVE`
				<a href="~$SITE_URL`/social/addPhotos?checksum=~$CHECKSUM`&profilechecksum=~$profilechecksum`&from_registration=1" class="b f_13" style="color:#057ec3; padding-left:15px;">Skip to Upload Photos</a>~else`
					<span onclick="return validate7('skip_to_fto');"><a href="#" class="b f_13" style="color:#057ec3; padding-left:15px;">Skip to Free Trial Offer details</a></span>~/if`
		~else`
			~if $filter_redirect eq '1'`
			<input onclick="return validate7('redirect_save');" id="Submit" name="Sub" value="Set Filters" type="image" src="~$IMG_URL`/profile/images/registration_revamp_new/save_conti.png" border="0" style="margin:30px 0 30px 0;" >
			<input onclick="return redirect_filter('redirect_uncheck');" id="Submit" name="Sub" value="Set Filters" type="image" src="~$IMG_URL`/profile/images/registration_revamp_new/no_set_filters.jpg" border="0" style="margin:30px 0 30px 0;" >
			~if $dontSetFilter eq '1'`
			<input onclick="window.location='~$SITE_URL`/profile/mainmenu.php'"; id="Submit" name="Sub" value="Set Filters" type="image" src="~$IMG_URL`/profile/images/registration_revamp_new/will_set_filters.jpg" border="0" style="margin:30px 0 30px 0;" >
			~/if`
			~else`
			<input onclick="return validate7('normal');" id="Submit" name="Sub" value="Set Filters" type="image" src="~$IMG_URL`/profile/images/registration_revamp_new/save_conti.png" border="0" style="margin:30px 0 30px 0;" >
			~/if`	
		~/if`	
		</div>
	</div>
</div>
			 <div id="SHOW_LOADER" style="display:none">
				  <div class="lf" style="width:100%;margin-top:25px;text-align:center;"><img src="~$IMG_URL`/P/images/loader_big.gif" width="54" height="55"><br><br><span style="font:normal 20px Arial, Helvetica, sans-serif">Your filters are being set<span style="font-size:13px">...</span></span></div>
			 </div>
	</form>
</div>
</div>
	<div class="clr cl21"></div>
	<script>

		var mstatus="~$mstatus`";
		var religion="~$religion`";
		var caste="~$caste`";
		var lage="~$lage`";
		var hage="~$hage`";
		var income="~$income`";
		var mtongue="~$mtongue`";
		var city="~$city`";
		var country="~$country_res`";

		if (mstatus=="" || mstatus.search("Matter")!=-1){
			updateCheckbox("mstatus");
		}
		if (religion=="" || religion.search("Matter")!=-1){
			updateCheckbox("religion");
		}
		if (caste=="" || caste.search("Matter")!=-1){
			updateCheckbox("caste");
		}
		if ("~$lage`"=="" && "~$hage`"==""){
			updateCheckbox("age");
		}
		if (mtongue=="" || mtongue.search("Matter")!=-1){
			updateCheckbox("mtongue");
		}
		if (city=="" || city.search("Matter")!=-1){
			updateCheckbox("city_res");
		}
		if (country=="" || country.search("Matter")!=-1){
			updateCheckbox("country_res");
		}
	function updateCheckbox(id)
	{
		dID(id+"_filter").disabled=true;
		dID(id+"_filter").checked= false;
	}
	</script>
	<script>
</script>
<script>
$('.thickbox').colorbox();
imgLoader = new Image();// preload image
imgLoader.src = tb_pathToImage;
</script>
~if $REG_P6 neq '1'`
	~if ~$FOOT` eq '1'`					~include_partial('global/footer',[data=>~$loggedInProfileid`,pageName=>$pageName])`
		~/if`
	
<style>
	.main_form_cont {
		border: 2px solid #DADADA;
	}
</style>
~/if`


