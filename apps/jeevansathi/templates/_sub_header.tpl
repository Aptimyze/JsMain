~JsTrackingHelper::getHeadTrackJs()`
~JsTrackingHelper::setJsLoadFlag(0)`
~if $sf_request->getAttribute('login')`
	~assign var='isLogin' value=1`
~else`
	~assign var='isLogin' value=0`
~/if`
	~assign var='isLogin' value=1`
~assign var='kundli_link' value=3`

<div id="advancedSearch" class="fr" style="~if $isLogin` ~if $kundli_link eq 1 || $kundli_link eq 2`width:900px;~else`width:800px;~/if`~else`width:800px; ~/if` font-size:12px!important;">
	<p class="clr_4"></p>
	<span>
		<a class="fr undr_hver" href="~sfConfig::get('app_site_url')`/P/advance_search.php?page_name=advance_search_page"> Advanced Search</a>
		<i class="fr sprte2 blue_arw ml_17"></i>	
	</span>

	<span id="search_by_id" ~if $pageName eq 'SearchPage'` style="width:200px;z-index:100;position:relative;" onclick="javascript:check_window('search_by_id(\'hide\',1)');" ~else` style="width:200px;z-index:100;position:relative;" onclick="javascript:check_window('search_by_id(\'hide\'~if $isLogin`~if $kundli_link eq 1 || $kundli_link eq 2`,2~/if`~/if`)');" ~/if`> 
		<a class="fr undr_hver" href="#" ~if $pageName eq 'SearchPage'` onclick="search_by_id('show',1); return false;" ~else` onclick="search_by_id('show'~if $isLogin`~if $kundli_link eq 1 || $kundli_link eq 2`,2~/if`~/if`); return false;" ~/if`> Search by profile id</a>
		<i class="fr sprte2 blue_arw ml_17"></i>
		~if $pageName eq 'SearchPage'`
        	<div class="div_interactions fl position2 fs12 w314 " id="saved_by_profile" style="display:none; ~if $isLogin` ~if $kundli_link eq 1 || $kundli_link eq 2`left:480px;~/if` ~/if`" >
                	<div class="fr divHeading lh19 white b"><i class = "fl wht_arw"></i>Search by profile id</div>
                	<div class="divlinks fl w290 "  >
                        	<div class="sp15"></div>
                        	Profile Id &nbsp;<input type="text" class="w_150" id="SEARCH_BY_USERNAME" onkeydown="javascript:check_enter('search_by_username()',event)" /><input type="button"  value="Search" class="btn_view b mar_left_14" style="width:64px;cursor:pointer" onclick="javascript:search_by_username()" />
                                <div class="clr"></div>
                                            <span style="color: red; display: none;font-size: 11px; padding-top:5px;" id="email_error">Email ID is not allowed. Please provide a profile id</span>
                        	<div class="sp15">&nbsp;</div>
                        	<div class="separator fl width100"></div>
                        	<div class="fr b"><a href="#" onclick="search_by_id('hide',1);return false;">Close [x]</a></div>
                	</div>
        	</div>
		~/if`
	</span>
	<span id="save_search_option" ~if $pageName eq 'SearchPage'` style="z-index:100; ~if $sf_request->getAttribute('login')`position:relative;~/if`" onclick="javascript:check_window('save_search_options(\'hide\',~$sf_request->getAttribute("profileid")`,1)')" ~else` style="margin-left:356px;z-index:100; ~if $sf_request->getAttribute('login')`position:relative;~/if`" onclick="javascript:check_window('save_search_options(\'hide\',~$sf_request->getAttribute("profileid")`)')" ~/if`>
        ~if $sf_request->getAttribute('login')`
                        <a class="fr undr_hver" href="#" ~if $pageName eq 'SearchPage'` onclick="save_search_options('show',~$sf_request->getAttribute('profileid')`,1);return false;" ~else` onclick="save_search_options('show',~$sf_request->getAttribute('profileid')`);return false;" ~/if` id = "save_search_text"> My Saved Searches </a>
			<i class="fr sprte2 blue_arw ml_17" id = "save_search_arrw"></i>
			~if $pageName eq 'SearchPage'`
				<div class="div_interactions fl position_subHead fs12" id="my_saved" style = "display:none">
					<div class="fl divHeading lh19 white b"><i class = "fl wht_arw"></i> My Saved Searches </div>
					<div class="divlinks fl " >
						<div style="text-align:center" id="saveSearchLoader"><img src="~sfConfig::get('app_img_url')`/profile/images/ajax-loader.gif"></div>
						<div id="my_save_search_id"></div>
						<div class="separator fl width100">&nbsp; </div>
						<div class="fr b"><a href="#" onclick="save_search_options('hide',~$sf_request->getAttribute('profileid')`,1);return false;">Close [x]</a></div>
					</div>
				</div>
			~/if`
                </span>
        ~else`
		<!--Not to show in case of homepage-->
                <span id="save_search_option" style="z-index:100;"> </span>
                        <a class="fr thickbox" ~if $pageName eq 'SearchPage'` href="~sfConfig::get('app_site_url')`/static/registrationLayer?width=775&pageSource=searchpage" ~else` href="~sfConfig::get('app_site_url')`/profile/login.php?SHOW_LOGIN_WINDOW=1&l_source=L_MSS" ~/if`> My Saved Searches </a>
			<i class="fr sprte2 blue_arw ml_17"></i>
               
        ~/if`
	<p class="clr_4"></p>
	<p class="clr_4"></p>
</div>
<div class="clr"></div>
    <div id="closeBand" style="background-image: url('~sfConfig::get("app_img_url")`/images/qtopc-grey-new.jpg'); background-repeat: repeat-x;display:none" >
        <div align="center">
            <a class="pc_close_band">&nbsp;</a>
        </div>    
         <p class="clr_4"></p>
         <p class="clr_4"></p>
    </div>
<div class="clr"></div>
    <div id="openBand" style="background-image: url('~sfConfig::get("app_img_url")`/images/qtopo-new-grey.jpg'); background-repeat: repeat-x;display:none" >
        <div align="center">
            <a class="pc_open_band">&nbsp;</a>
        </div>
    </div>    

<script>
	logged_in_username="~$sf_request->getAttribute('username')`";
	document.body.onclick=check_window
</script>
