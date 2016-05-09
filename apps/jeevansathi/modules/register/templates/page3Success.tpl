~include_partial("register/reg_tracking",['profile_score'=>$profile_score,'groupname'=>$sf_request->getParameter('groupname'),'pixelcode'=>$pixelcode,'pixelcodeRocketFuel'=>$pixelcodeRocketFuel,'reg_comp_frm_ggl'=>$sf_request->getParameter('reg_comp_frm_ggl'),'reg_comp_frm_ggl_nri'=>$sf_request->getParameter('reg_comp_frm_ggl_nri')])`<!-- Tracking Ends here --><p class="tp_bg sprtereg"></p>
        <div class="reg_cont">
	~include_partial("register/regHeader",[SITE_URL=>$SITE_URL])`
	<div> Social & Family Background <span class="b">&gt;</span> <span class="lt_gry">Education & Professional Background </span><span class="b">&gt;</span> <span class="lt_gry">Desired Partners’ Profile </span></div>	
	<div class="clr cl_10"></div>
	<h4 class="drk_gry ntxtleft" style="font-size:16px;">Other Members would like to know about your family. Fill these details to receive more interests.</h4>
	
	
  	<div class="fl mt_10" style="width:770px;">
    		<div class="clr cl_5"></div>
		<div class="main_form_cont pos_rltv" style="width:770px;">
	  	~if $IS_FTO_LIVE`
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
			 <div class="clr cl21"></div>
		~/if`
			<i class="d_t_r pos_abs h_w_6 sprtereg p_tr_0"></i>
			<i class="d_b_r pos_abs h_w_6 sprtereg p_br_0"></i>
			<i class="d_b_l pos_abs h_w_6 sprtereg p_bl_0"></i>

		<form action="/register/page3" method="post" onsubmit="return validate_name(3);" name="form1" id='form1'>
		<ul class="form" style="margin-top:5px;">
		~foreach from=$form item=field`
			~if $field->isHidden()`
				~$field->render()`
			~/if`
		~/foreach`
		<input type="hidden" name="adnetwork1" value="~$sf_request->getParameter('adnetwork1')`">
		<input type="hidden" name="groupname" value="~$sf_request->getParameter('groupname')`">
		 <input type="hidden" name="source" value="~$sf_request->getParameter('source')`"/>
		<input type="hidden" name="record_id" value="~$sf_request->getParameter('record_id')`" />

		~if $groupname eq "Tyroo_AMJ@120" && $fireTyroo eq 1`
                <script type="text/javascript"> 
                $(window).load(function(){
                  tyrooFunc();
                });
                function tyrooFunc() {
                  var a = document.createElement('script');
                  a.type = 'text/javascript'; 
                  a.async = true;
                  a.src="https://www.s2d6.com/js/globalpixel.js?x=sp&a=879&h=67036&o=~$username`&g=&s=0.00&q=1";
                  var s = document.getElementsByTagName('script')[0]; 
                  s.parentNode.insertBefore(a, s);
                }
                </script>
		~/if`   
		~if $groupname eq Vizury_AMJ13`
	<iframe src="http://www.vizury.com/analyze/analyze.php?account_id=VIZVRM782&param=e500&orderid=&orderprice=&pid1=Y&catid1=~$country_res`&quantity1=&price1=~$mtongue`&pid2=~$GENDER`&catid2=~$caste`&quantity2=&price2=&pid3=&catid3=~$age`&quantity3=&price3=&currency=&section=1&level=1" scrolling="no" width="1" height="1" marginheight="0" marginwidth="0" frameborder="0"></iframe>
	~/if`

			<li class="bot_bdr">
				~$form['name_of_user']->renderLabel()`
				~$form['name_of_user']->render(['maxlength'=>'40','class'=>'txt1','onblur'=>'validate_name(1);'])`
				  <p class="fl f_11 drk_gry">&nbsp;&nbsp;Your name will NOT be disclosed to anybody.</p>
				  <div class="clr"></div>
				  <span id="name_of_user_submit_err" ~if !$name_of_user_Error` style="display:none" ~/if` class="err">
				 	<label>&nbsp;</label>
					Full name cannot contain special characters.
				  </span>
			</li>
			
			<li>
				  <h2 class="mt_10">Social &amp; Religious Background</h2>
			</li>
			~include_partial("register/native_place_fields",['form'=>$form,'out_sideIndia'=>$out_sideIndia])`
			
			~if $partial`
			~include_partial("register/$partial",['form'=>$form,'GENDER'=>$GENDER,'caste'=>$caste])`
			~/if`
			~if $religion eq Religion::HINDU or $religion eq Religion::BUDDHIST or $religion eq Religion::SIKH or $religion eq Religion::JAIN`
			<li>
				~$form['horoscope_match']->renderLabel()`
				~$form['horoscope_match']->render()`
			</li>
			<!-- Horoscope Section Starts Here -->
			<li class="bot_bdr">
				  <label>Create / Upload Your Horoscope :</label>
				  <div class="gry_cont fl" style="width:470px;display:inline" id="horo_section">
						<input type="radio" name="horo" value="H" ~if $horo eq 'H'` checked ~/if`  onClick="change(1);"/> Let Jeevansathi create your Horoscope<br />
					  <div id="create" style="display:none; padding:10px ">
						<div id="frame_show" style="display:block;width:95%">
							<iframe vspace="0" hspace="0" marginheight="0" marginwidth="0" width="401" height="245" frameborder="0" scrolling="no" src="http://vendors.vedic-astrology.net/cgi-bin/JeevanSathi_DataEntry_Matchstro.dll?BirthPlace?JS_UniqueID=~$PROFILEID`&JS_Year=~$YEAR_OF_BIRTH`&JS_Month=~$MONTH_OF_BIRTH`&JS_Day=~$DAY_OF_BIRTH`">
							</iframe>
						</div>
						<div id="frame_show_edit" style="display:none">
							<iframe vspace="0" hspace="0" marginheight="0" marginwidth="0" width="570" height="250" frameborder="0" scrolling="no" src="http://vendors.vedic-astrology.net/cgi-bin/JeevanSathi_DataEntry_Matchstro.dll?BirthPlace?js_UniqueID=~$PROFILEID`&js_year=~$YEAR_OF_BIRTH`&js_month=~$MONTH_OF_BIRTH`&js_day=~$DAY_OF_BIRTH`">
							</iframe>
						</div>
					  </div>

					  <div style="margin-left:100px; display:inline; float:left;" class="drk_gry b f_17">OR</div>
					  <div class="clr"></div>
					  <input type="radio" name="horo" value="D" ~if $horo eq 'D'` checked ~/if` onClick="change(2);reset_value();"/> Upload your digitally scanned horoscope
	
					  <div id="upload" style="display:none;">
						<div class="clr cl_10"></div>
						<p class="fl ml_10">Browse and upload a digitally scanned horoscope</p>
						<div class="clr cl_10"></div>
							 <div class="fl drk_gry" style="display:none" id="horo_loader">
								<img align="absmiddle" src="~$IMG_URL`/profile/images/registration_revamp_new/loader_big.gif"/> Uploading Your Horoscope... 
							 </div>
							 <iframe name="horo_iframe" id="horo_iframe" src="~$SITE_URL`/profile/horoscope_browse.php" scrolling='no' height="53" width="401" style="display:inline;background-color:#F5F5F5;" frameborder="0" marginwidth="0" marginheight="0" vspace="0" hspace="0">
							</iframe>

						<div class="clr cl_5"></div>
						<p class="fl ml_10" style="margin-top:0;" id="horo_error_message">Image file size should not be more than 4MB. Image format should be.gif/ .jpeg/ .jpg </p>
				 	  </div>
				 	  <div class="clr"></div>
				  </div>		

				  <div style="display:none" id="horo_message" class="gry_cont fl">
					<b>Your horoscope has been uploaded. <a class="blue" style="cursor:pointer;" onclick="show_horo_section();">Edit Horoscope</a></b>
				  </div>
			  </li>
		  	  <!-- Horoscope section Ends Here -->
			  ~/if`
			

      		</ul>
	
	        <div class="clr"></div>
      	        <ul class="form" style="padding:0px 10px 10px 0px">
        		<li>
        			  <h2 class="mt_10">Family Details</h2>
      			</li>
        		<li>
					~$form['family_values']->renderLabel()`
					~$form['family_values']->render()`
				</li>
		        <li>
					~$form['family_type']->renderLabel()`
					~$form['family_type']->render()`
			</li>
		        <li>
					~$form['family_status']->renderLabel()`
					~$form['family_status']->render()`
			</li>
      			<li>
					~$form['family_back']->renderLabel()`
					~$form['family_back']->render(['class'=>'sel_mid1'])`
		        </li>
		        <li>
					~$form['mother_occ']->renderLabel()`
					~$form['mother_occ']->render(['class'=>'sel_mid1'])`
				</li>
		        <li>
					~$form['t_brother']->renderLabel()`
					~$form['t_brother']->render(['class'=>'w64 fl','onchange'=>'married_field_brothers();'])`
				  <div id="married_field" ~if $brothers eq '0'` style="display: none" ~/if`>
					  <span class="fl f_11" style="margin:4px;">of which married :</span>
						~$form['m_brother']->render(['class'=>'w64 fl'])`
				  </div>
		        </li>
		        <li>
					~$form['t_sister']->renderLabel()`
					~$form['t_sister']->render(['class'=>'w64 fl','onchange'=>'married_field_sisters();'])`
				  	<div id="married_field_sis" ~if $sisters eq '0'` style="display: none" ~/if`>
					  <span class="fl f_11" style="margin:4px;">of which married :</span>
						~$form['m_sister']->render(['class'=>'w64 fl'])`
				  </div>
		        </li>
		        <li>
					~$form['parent_city_same']->renderLabel()`
					~$form['parent_city_same']->render()`
			</li>
		        <li>
					~$form['familyinfo']->renderLabel()`
			          <span class="lt_gry">Tell us about your parents & siblings, where they live, what they do, their education etc.</span>
			          <div class="clr"></div>
			          <label>&nbsp;</label>
					~$form['familyinfo']->render(['maxlength'=>'1000','class'=>'w343 h98','cols'=>1,'rows'=>1,'onKeyup'=>"changeCount()"])`
			          <div class="clr"></div>
			          <label>&nbsp;</label>
			          <div class="fl f_11 drk_gry" style="width:345px;">
					<span class="fl">Number of characters : 
						<input id="about_family_count" READONLY name="wordcount" type="text" class="grn" value="" size="3" style="background:none; border:0;width:30px;">
					</span>
				  </div>
    			</li>
		        <li>
				 <div class="fl">
					<input type="hidden" name="submit_pg3" value="submit">
					<input name="submit_pg3" type="submit" value="Save & Continue" class="nsubbtn" border="0" style="margin-bottom: 30px; margin-left: 195px; display: inline;" >
				</div>
~if !$RECORD_ID`				<div class="fl">
					 <a href="#" class="fl ml_10 f_13" onclick="return submit_skip()" style="color:#057ec3;"><br />
					          Skip to Next page
					 </a>
				 </div>
				 ~/if`
			</li>
	        </ul>
	        <div class="clr"></div>
</form>
    </div>
  </div>
</div>
<div class="clr cl21"></div>
~include_partial('global/remarketing',[GR=>'0',KO=>'1',SO=>'1'])`
<script>
	function submit_skip()
	{
		document.form1.action=document.form1.action+'?skip_to_next_page_edu=1&sem=~$sem`';
		document.form1.submit();
		return false;
	}
    var caste = $("#caste").val();
    $("#reg_subcaste").autocomplete(SITE_URL+"/profile/autoSug?type=subcaste&caste=" + caste,{maxItemsToShow:10, field: '#subcastes', from_reg:1});
		$("#reg_gothra").autocomplete(SITE_URL+"/profile/autoSug?type=gothra",{maxItemsToShow:10,field:'#gothraPat',from_reg:1});
		$("#reg_diocese").autocomplete(SITE_URL+"/profile/autoSug?type=dioceses",{maxItemsToShow:10,field:'#dioceses',from_reg:1});
</script>
<script type="text/javascript">
var google_conversion_id = 1056682264;
var google_conversion_language = "en";
var google_conversion_format = "3";
var google_conversion_color = "ffffff";
var google_conversion_label = "j5CPCPy1_gIQmOLu9wM";
var google_conversion_value = 0;
var cityDefault = null;
var countryDefault=~$countryDefault`;
~if $ISEARCH_COOKIE_NOTSET`
(function() {
    try {
        var viz = document.createElement('script');
        viz.type = 'text/javascript';
        viz.async = true;
        viz.src = ('https:' == document.location.protocol ?'https://ssl.vizury.com' : 'http://www.vizury.com')+ '/analyze/pixel.php?account_id=VIZVRM782';

        var s = document.getElementsByTagName('script')[0];
        s.parentNode.insertBefore(viz, s);
        viz.onload = function() {
            pixel.parse();
        };
        viz.onreadystatechange = function() {
            if (viz.readyState == "complete" || viz.readyState == "loaded") {
                pixel.parse();
            }
        };
    } catch (i) {
    }
})();
~/if`
</script>
<script type="text/javascript" src="http://www.googleadservices.com/pagead/conversion.js">
</script>
<noscript>
<div style="display:inline;">
<img height="1" width="1" style="border-style:none;" alt="" src="http://www.googleadservices.com/pagead/conversion/1056682264/?value=0&amp;label=j5CPCPy1_gIQmOLu9wM&amp;guid=ON&amp;script=0"/>
</div>
</noscript>
