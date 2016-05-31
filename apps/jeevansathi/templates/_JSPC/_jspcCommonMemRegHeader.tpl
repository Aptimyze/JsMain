~assign var=module value= $sf_request->getParameter('module')`
~assign var=loggedIn value= $sf_request->getAttribute('login')`
~assign var=currency value= $sf_request->getAttribute('currency')`
~assign var=action value= $sf_request->getParameter('action')`
~assign var=profileid value= $sf_request->getAttribute('profileid')`
<!--r_num is variable number whose value is fetched from Auth filter in case of JSPC and assigned to script to be accessed in commonExpiration_js.js-->
~assign var=r_num value=$sf_request->getParameter('revisionNumber')`
<!--start:header-->
<header>
    ~if $module eq 'membership'`
    <div class="mem-coverimg">
    ~else if $module eq 'register' || $action eq 'phoneVerificationPcDisplay'`
    <div class="mem-coverimg pt35">
    ~/if`
        <div class="container mainwid mem-pad1 fontreg">
            <!--start:top navigation bar-->
            <div id="topNavigationBar" class="~if $stickyTopNavBar`stickyTopNavBar~else`pos_rel~/if` fullwid color-block clearfix">
                <!--start:logo-->
                <div id="jeevansathiLogo" class="fl hpwid1 logop1 hpwhite txtc disp-tbl">
                    <p class="lgo" itemtype="http://schema.org/Organization" itemscope="">
                        <a class="disp-cell vmid pl10" href="~if $loggedIn`/myjs/jspcPerform~else`/~/if`" itemprop="url"> <img class="brdr-0 vmid" alt="Indian Matrimonials - We Match Better" src="~sfConfig::get('app_site_url')`/images/jspc/commonimg/logo1.png" itemprop="logo"> </a>
                    </p>
                </div>
                <!--end:logo-->
                <!--start:nav-->
                ~if $login && $module eq 'register' || $action eq 'phoneVerificationPcDisplay'`
                <div class="fr">
                    <div style="padding:10px 30px 0 0"><a href="/profile/viewprofile.php?ownview=1" alt=""><img src="~PictureFunctions::getHeaderThumbnailPicUrl()`" style="height: 46px; width: 46px;border-radius: 23px;"></a></div>
                </div>
                ~/if`
                <div class="~if $module eq 'membership'`fl~else`fr mr20~/if` mt23 toplink f14">
                    <nav>
                        ~if $module eq 'membership'`
                        <a class="cursp" href="~if $loggedIn`/myjs/jspcPerform~else`/~/if`"><span id="homepageLink" class="cursp">HOME</span></a>
                        <span id="headerRequestCallback" class="ml30 cursp">REQUEST CALLBACK</span>
	                    ~/if`
                        <span id="liveChatLinkHeader" class="cursp ml30">LIVE CHAT</span>
                        ~if $module eq 'membership'`
                        <a class="cursp" href="/contactus/index"><span id="contactUsLinkHeader" class="cursp">CONTACT US</span></a>
                        ~/if`
                        <span id="helplineNoHeader" style="color:#fff" class="ml30">~if $currency eq 'RS'`1-800-419-6299 (Toll Free)~else`+91-120-4393500~/if`</span>
                    </nav>
                </div>
                <!--end:nav-->
                <!--start:image-->
                ~if $loggedIn && $module eq 'membership'`
                <div class="fr">
                    <div style="padding:10px 30px 0 0"><a href="/profile/viewprofile.php?ownview=1" alt=""><img src="~PictureFunctions::getHeaderThumbnailPicUrl()`" style="height: 46px; width: 46px;border-radius: 23px;"></a></div>
                </div>
                ~/if`
                <!--end:image-->
		~if $module eq 'membership'`
                    ~include_partial('global/JSPC/_jspcReqCallbackSection',['subsection'=>'header'])`
		~/if`
            </div>
            <!--end:top navigation bar-->
            ~if $module eq 'membership'`
                ~include_partial('global/JSPC/_jspcMembershipHeaderSubsection')`
            ~/if`
            ~if $module eq 'register' && $action neq 'page5'`
                ~include_partial("register/header/_jspcRegTabSection",['PAGE'=>~$PAGE`,'name'=>~$name`])`
            ~/if`
            ~if $module eq 'register' && $action eq 'page5'`
                <div class="f22 txtc fontlig colrw pt26 pb27">Phone Verification</div>
            ~/if`
            ~if $action eq 'phoneVerificationPcDisplay'`
                <div class="f22 txtc fontlig colrw pt26 pb27">Phone Verification</div>
            ~/if`
        </div>
    </div>
</header>
<script type="text/javascript">
    var r_n_u_m = ~$r_num`;
    if(typeof(bindEscapeKey) != 'function'){
        $(document).keyup(function(e) {
            if (e.keyCode == 27) {
                if ($(".overlay1").length) {
                    $(".overlay1").remove();
                    $("#topNavigationBar").removeClass('pos-rel').removeClass('z999');
                    $("#headerRequestCallback").removeClass('js-reqcallbck').removeClass('opa50');
                    $("#headerRequestCallbackLogout").hide();
                    $("#headerRequestCallbackLogin").hide();
                }
            }
        });
    }
    if($('#topNavigationBar').hasClass('stickyTopNavBar')){
        var stickyTopNavBar = 1;
    } else {
        var stickyTopNavBar = 0;
    }
    var stickyHeader = $("#topNavigationBar").offset().top;
    $(window).load(function() {
        if(stickyTopNavBar){
            $(window).scroll(function () {
                if ($(this).scrollTop() > stickyHeader) {
                    $("#topNavigationBar").addClass("pos_fix").removeClass("pos_rel");
                } else {
                    $("#topNavigationBar").addClass("pos_rel").removeClass("pos_fix");
                }
            });
        }
        else{
            $("#topNavigationBar").removeClass("pos_fix");
        }
    });

    //This fucntion returns the revision number and is called in commonExpiration_js.js
    function getR_N_U_M(){
        return(r_n_u_m);
    }
</script>
<!--end:header-->
