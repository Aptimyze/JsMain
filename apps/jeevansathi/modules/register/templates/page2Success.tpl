<script>var user_login = 1;</script><div style="display:none">
~$errMsg|decodevar`
~$defaultMsg|decodevar`
</div>
<script>
var ugArr = '~$ugGroup`'.split(",");
var bachelorArr = '~$gGroup`'.split(",");
var pgDegreeArr = '~$pgGroup`'.split(",");
var phdArr = '~$phdGroup`'.split(",");
</script>
<noscript>
	<div style="position:fixed;z-index:1000;width:100%">
		<div style="text-align:center;padding-bottom:3px;font-family:verdana,Arial;font-size:12px;font-size-adjust:none;font-stretch:normal;font-style:normal;font-variant:normal;font-weight:normal;line-height:normal;background-color:#E5E5E5;">
			<b><img src="~$IMG_URL`/profile/images/registration_new/error.gif" width="23" height="20"> Javascript is disabled in your browser.Due  to this certain functionalities will not work. 
				<a href="~$SITE_URL`/P/js_help.htm" target="_blank">Click Here</a> , to know how  to enable it.
			</b>
		</div>
	</div>
</noscript>
<p class="tp_bg sprtereg"></p>
		<div class="reg_cont">
		~include_partial("register/regHeader",[SITE_URL=>$SITE_URL,toll=>1])`
     
		 <div class="clr cl_10"></div>
		 <h4 class="drk_gry ntxtleft" style="font-size:16px;">To start sending and receiving interests, add the following details</h4>
	  	<div class="clr cl_5"></div>
	  	
		<div class="fl mt_10" style="width:770px;">
		
	<div class="clr cl_5"></div>

	<form id="reg" action="/register/page2" method="post" onsubmit="" name="form1">
		~foreach from=$form item=field`
			~if $field->isHidden()`
				~$field->render()`
			~/if`
		~/foreach`
		 <input type="hidden" name="gender" value="~$gender`">
		 <input type="hidden" name="SEM" value="~$SEM`">
		 <input type="hidden" name="yourHeading" value="~$yourHeading`">
		 <input type="hidden" name="leadid" value="~$LEADID`" />
		 <input type="hidden" name="adnetwork" value="~$sf_request->getParameter('adnetwork')`" />
	   	 <input type="hidden" name="adnetwork1" value="~$sf_request->getParameter('adnetwork1')`"/>
		 <input type="hidden" name="account" value="~$sf_request->getParameter('account')`" />
		 <input type="hidden" name="campaign" value="~$sf_request->getParameter('campaign')`" />
		 <input type="hidden" name="adgroup" value="~$sf_request->getParameter('adgroup')`" />
		 <input type="hidden" name="keyword" value="~$sf_request->getParameter('keyword')`" />
		 <input type="hidden" name="match" value="~$sf_request->getParameter('match')`" />
		 <input type="hidden" name="lmd" value="~$sf_request->getParameter('lmd')`" />
		 <input type="hidden" name="id" value="~$ID_AFF`" />
		 <input type="hidden" name="groupname" value="~$sf_request->getParameter('groupname')`"/>
		 <input type="hidden" name="source" value="~$sf_request->getParameter('source')`"/>
		 <input type="hidden" name="affiliateid" value="~$sf_request->getParameter('affiliateid')`"/>
		 <div class="main_form_cont pos_rltv fl" style="width:560px;"> 
		
             <div class="pos_rltv nfullwid"> 
   

   <ul class="form" id="setlbwid" style="margin-top:5px;">
     <li style="margin-bottom:4px;">
     
		  <h2 style="float:left;">About ~if $yourHeading`~$yourHeading`~else`Yourself~/if`</h2>
		</li>

		<li>
		  	~if $form['edu_level_new']->hasError()`~$form['edu_level_new']->renderLabel(null,['style'=>'color:red'])`~else`~$form['edu_level_new']->renderLabel()`~/if`
			~$form['edu_level_new']->render(['class'=>'w230','onchange'=>'showDegreeFields();'])`
	  	<div class="clr cl_5"></div>
				 <div id="edu_level_new_err" style="display:~if $form['edu_level_new']->hasError()`inline~else`none~/if`;" class='error' for='reg_edu_level_new'>
					<label class="l1">&nbsp;</label>
					<div class="err_msg">~$form['edu_level_new']->getError()`</div>
				</div>
		</li>

		<li id="degree_pg" style="display:none">
                        ~if $form['degree_pg']->hasError()`~$form['degree_pg']->renderLabel(null,['style'=>'color:red'])`~else`~$form['degree_pg']->renderLabel()`~/if`
                        ~$form['degree_pg']->render(['class'=>'w230'])`
                        <div class="clr cl_5"></div>
                         <div id="degree_pg_err" style="display:~if $form['degree_pg']->hasError()`inline~else`none~/if`;" class='error' for='reg_degree_pg'>
                                <label class="l1">&nbsp;</label>
                                <div class="err_msg">~$form['degree_pg']->getError()`</div>
                        </div>

		</li>
		<li id="pg_college" style="display:none">
                        ~if $form['pg_college']->hasError()`~$form['pg_college']->renderLabel(null,['style'=>'color:red'])`~else`~$form['pg_college']->renderLabel()`~/if`
                        ~$form['pg_college']->render(['class'=>'w225'])`
                        <div id="pg_collegePat" class="fl" style="position:absolute;clear:all"></div>
                        <div class="clr cl_5"></div>
                         <div id="pg_college_err" style="display:~if $form['pg_college']->hasError()`inline~else`none~/if`;" class='error' for='reg_pg_college'>
                                <label class="l1">&nbsp;</label>
                                <div class="err_msg">~$form['pg_college']->getError()`</div>
                        </div>
		</li>
		<li id="degree_ug" style="display:none">
                        ~if $form['degree_ug']->hasError()`~$form['degree_ug']->renderLabel(null,['style'=>'color:red'])`~else`~$form['degree_ug']->renderLabel()`~/if`
                        ~$form['degree_ug']->render(['class'=>'w230'])`
                        <div class="clr cl_5"></div>
                         <div id="degree_ug_err" style="display:~if $form['degree_ug']->hasError()`inline~else`none~/if`;" class='error' for='reg_degree_ug'>
                                <label class="l1">&nbsp;</label>
                                <div class="err_msg">~$form['degree_ug']->getError()`</div>
                        </div>
		</li>
		<li id="college" style="display:none;">
		  	~if $form['college']->hasError()`~$form['college']->renderLabel(null,['style'=>'color:red'])`~else`~$form['college']->renderLabel()`~/if`
			~$form['college']->render(['class'=>'w225'])`
			<div id="collegePat" class="fl" style="position:absolute;clear:all"></div>
			<div class="clr cl_5"></div>
			 <div id="college_err" style="display:~if $form['college']->hasError()`inline~else`none~/if`;" class='error' for='reg_college'>
				<label class="l1">&nbsp;</label>
				<div class="err_msg">~$form['college']->getError()`</div>
			</div>
		</li>
                <li style="display:none;padding-bottom:12px;" id="addMorePgDegree">
                        <label class="l1">&nbsp;</label>
                        <a class="w230" href="#" onclick="showOtherPgDegree();return false;">Add another PG Degree</a>
                </li>

                <li id="other_pg_degree" style="display:none;">
                        ~if $form['other_pg_degree']->hasError()`~$form['other_pg_degree']->renderLabel(null,['style'=>'color:red'])`~else`~$form['other_pg_degree']->renderLabel()`~/if`
                        ~$form['other_pg_degree']->render(['maxlength'=>'40','class'=>'w225'])`
                        <div class="clr cl_5"></div>
                         <div id="other_pg_degree_err" style="display:~if $form['other_pg_degree']->hasError()`inline~else`none~/if`;" class='error' for='reg_other_pg_degree'>
                                <label class="l1">&nbsp;</label>
                                <div class="err_msg">~$form['other_pg_degree']->getError()`</div>
                        </div>
                </li>
		<li style="display:none;padding-bottom:12px;" id="addMoreUgDegree">
                        <label class="l1">&nbsp;</label>
			<a href="#" onclick="showOtherUgDegree();return false;">Add another Graduation Degree</a>
		</li>

		<li id="other_ug_degree" style="display:none">
                        ~if $form['other_ug_degree']->hasError()`~$form['other_ug_degree']->renderLabel(null,['style'=>'color:red'])`~else`~$form['other_ug_degree']->renderLabel()`~/if`
                        ~$form['other_ug_degree']->render(['maxlength'=>'40','class'=>'w225'])`
                        <div class="clr cl_5"></div>
                         <div id="other_ug_degree_err" style="display:~if $form['other_ug_degree']->hasError()`inline~else`none~/if`;" class='error' for='reg_other_ug_degree'>
                                <label class="l1">&nbsp;</label>
                                <div class="err_msg">~$form['other_ug_degree']->getError()`</div>
                        </div>
		</li>
		<li>
			~if $form['occupation']->hasError()`~$form['occupation']->renderLabel(null,['style'=>'color:red'])`~else`~$form['occupation']->renderLabel()`~/if`
			~$form['occupation']->render(['class'=>'w230'])`
	  	<div class="clr cl_5"></div>
				 <div id="occupation_err" style="display:~if $form['occupation']->hasError()`inline~else`none~/if`;" class='error' for="reg_occupation">
					<label class="l1">&nbsp;</label>
					<div class="err_msg">~$form['occupation']->getError()`</div>
				</div>
		</li>
		<li>
			~if $form['income']->hasError()`~$form['income']->renderLabel(null,['style'=>'color:red'])`~else`~$form['income']->renderLabel()`~/if`
			~$form['income']->render(['class'=>'w230'])`
	  	<div class="clr cl_5"></div>
				 <div id="income_err" style="display:~if $form['income']->hasError()`inline~else`none~/if`;" class='error' for='reg_income'>
					<label class="l1">&nbsp;</label>
					<div class="err_msg">~$form['income']->getError()`</div>
				</div>
		</li>
		
		~if $country neq '51'`
		<li>
		  	~$form['res_status']->renderLabel()`
			~$form['res_status']->render(['class'=>'w230'])`
		</li>
		~/if`
		<li>
			<div class="clr"></div>
			<label for="reg_yourinfo" ~if $form['yourinfo']->hasError()`style="color:red"~/if`>Write about ~$yourHeading`<BR> and ~$hisher` interests<u>*</u></label>
            ~$form['yourinfo']->render(['maxlength'=>'3000','class'=>'w342 nh121','onkeyup'=>'aboutFieldCount()'])`
		  	<div class="clr"></div>
		 	<label>&nbsp;</label>
		  	<div class="fl f_11 drk_gry" style="width:345px;margin-top:8px;">
				<span class="fl">Number of characters :&nbsp;</span> 
					<span class="grn fl"> 
						<div id="about_yourself_count" name="wordcount" class="grn" value="" style="background:none; border:0;width:30px;"> </div>
					</span>
				</span>
				<span class="fr">(Minimum number of characters- 100 )</span>
			</div>
		</li>
      	
        <div class="clr"></div>
	<li>
	<div id="yourinfo_err" style="display:~if $form['yourinfo']->hasError()`inline~else`none~/if`;margin:-15px 0 10px 10px; _margin:-70px 0 10px 4px;" class="error" for="reg_yourinfo">
		<label>&nbsp;</label>	
		<span id="about_yourself_error1" class="err_msg fl" style="width:320px;">~$form['yourinfo']->getError()`
		<span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></span>
	</div>
	</li>
	</ul>
  <div class="clr"></div>
     <div align="center" style="_margin-top:-30px;">

		<input type="hidden" name="submit_page2" value="submit">
		~if !$IS_FTO_LIVE`
			<input name="page2submit" type="submit" value="Finish Registration" class="nsubbtn" border="0" style="margin-bottom: 30px;" >
			~else`
				<input name="page2submit" type="image" src="~$SITE_URL`/profile/images/registration_revamp_new/register_to_get_offer.gif" border="0" style="margin-bottom:10px;" >
				<div align="center" style="width:770px;_margin-top:-15px; margin-bottom:20px;font-size:14px";>Get paid membership worth Rs 1100 for FREE
			~/if`
	</div>
    </div>
  </div>
<!-- start:right side panel -->
~include_partial("register/rightpanel")`
<!-- end:right side panel -->
</div>

</form>
<div class="clr cl21"></div>
 		~if $groupname  eq 'DrivePM_RI_JFM09'`
		 	<img src="http://switch.atdmt.com/action/Jeevansathi_RI_Intermediate_Feb09" height="1" width="1">
		~/if`
		~if $groupname  eq 'DrivePM_NRI_JFM09'`
		 	<img src="http://switch.atdmt.com/action/Jeevansathi_NRI_Intermediate_Feb09" height="1" width="1">
		~/if`
		~if $pixelcode`
	~$pixelcode|decodevar`
	~/if`
		

~if $TIEUP_SOURCE eq 'default59'`
	<!-- Changes done as per Mantis 5339 -->
		<!-- Google Code for Default_Page2 Remarketing List -->
		<script type="text/javascript">
		/* <![CDATA[ */
		var google_conversion_id = 1056682264;
		var google_conversion_language = "en";
		var google_conversion_format = "3";
		var google_conversion_color = "666666";
		var google_conversion_label = "omBaCKSF5gEQmOLu9wM";
		var google_conversion_value = 0;
		/* ]]> */
		</script>
		<script type="text/javascript" src="https://www.googleadservices.com/pagead/conversion.js"></script>
		<noscript>
			<div style="display:inline;">
				<img height="1" width="1" style="border-style:none;" alt="" src="https://www.googleadservices.com/pagead/conversion/1056682264/?label=omBaCKSF5gEQmOLu9wM&amp;guid=ON&amp;script=0"/>
			</div>
		</noscript>
	 <!-- Ends Here -->
 ~/if` 
 <script type="text/javascript">
(function() {
    try {
        var viz = document.createElement("script");
        viz.type = "text/javascript";
        viz.async = true;
        viz.src = ("https:" == document.location.protocol ?"https://ssl.vizury.com" : "http://www.vizury.com")+ "/analyze/pixel.php?account_id=VIZVRM782";

        var s = document.getElementsByTagName("script")[0];
        s.parentNode.insertBefore(viz, s);
        viz.onload = function() {
            try {
                pixel.parse();
            } catch (i) {
            }
        };
        viz.onreadystatechange = function() {
            if (viz.readyState == "complete" || viz.readyState == "loaded") {
                try {
                    pixel.parse();
                } catch (i) {
                }
            }
        };
    } catch (i) {
    }
})();
var college = $("#reg_college").val();
$("#reg_college").autocomplete(SITE_URL+"/profile/autoSug?type=collg&caste=" + college,{maxItemsToShow:10, field: '#collegePat', from_reg:1,left:"156px",marginTop:"0px"});
var pg_college = $("#reg_pg_college").val();
$("#reg_pg_college").autocomplete(SITE_URL+"/profile/autoSug?type=collg&caste=" + pg_college,{maxItemsToShow:10, field: '#pg_collegePat', from_reg:1,left:"156px",marginTop:"0px"});
</script> 
 ~if $IS_FTO_LIVE`
 <script type="text/javascript" language="Javascript">
$.colorbox({href:"~$SITE_URL`/profile/reg_fto_layer.php"});
function check_window()
{
}
   
	</script>
	~/if`
