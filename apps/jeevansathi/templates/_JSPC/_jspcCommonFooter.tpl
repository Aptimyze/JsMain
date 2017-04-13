~assign var=module value= $sf_request->getParameter('module')`
~assign var=loggedIn value= $sf_request->getAttribute('login')`
~assign var=currency value= $sf_request->getAttribute('currency')`
~assign var=action value= $sf_request->getParameter('action')`
~assign var=profilechecksum value= $sf_request->getAttribute('profilechecksum')`
~assign var=profileid value= $sf_request->getAttribute('profileid')`
~assign var=zedoValue value= $sf_request->getAttribute('zedo')`
~assign var=zedo value= $zedoValue["zedo"]`

~if !($profileid eq '8298074' || $profileid eq '13038359' || $profileid eq '12970375')`
    <!--start:help widget-->
    ~include_component('common', 'helpWidget', ['hideHelpMenu'=>'true'])`
    <!--end:help widget-->
~/if`
<!--start:banner-->
~if $zedo['commonFooter'] eq 1`
    <div class="txtc bg-4 pt20 pb20" id="zt_~$zedo['masterTag']`_bottom"> </div>
~/if`
<!--end:banner-->

<!--start:footer-->
<footer>
  <!--pixelcode for register page-->
  ~if isset($pixelcode)`
    ~$pixelcode|decodevar` 
  ~/if`
  <div id="js-footer">
    ~if $module eq "membership" or $module eq "register" or $action eq "phoneVerificationPcDisplay"`
    <div class="bg_2">
        <div class="container mainwid clearfix fontreg f15">
            <div class="fl link wid500">
                <ul class="lh50">
                    <li id="helplineNoFooter">~if $currency eq 'RS'`1-800-419-6299 (Toll Free)~else`+91-120-4393500~/if`</li>
                    <li>|</li>
                    ~if $module neq 'register'`
                    <li id="footerRequestCallback" class="cursp">Request Callback </li>
                    <li>|</li>
                    ~/if`
                    <li id="liveChatLinkFooter" class="cursp">Live Chat</li>
                </ul>
            </div>
            <div class="fl cards mt3">
                <ul class="mt8">
                    <li><i class="headfootsprtie visa"></i></li>
                    <li><i class="headfootsprtie mcard"></i></li>
                </ul>
            </div>
            <div class="fr socialicons">
                <ul class="mt8">
                    <li><a itemprop="sameAs" href="https://www.facebook.com/jeevansathi" target="_blank" class="disp_b headfootsprtie facebooksmall"></a></li>
                    <li><a itemprop="sameAs" href="https://www.twitter.com/jeevansathi_com" target="_blank" class="disp_b headfootsprtie twittersmall"></a></li>
                    <li><a itemprop="sameAs" href="https://www.linkedin.com/company/info-edge-india-ltd" target="_blank" class="disp_b headfootsprtie linkedinsmall"></a></li>
                    <li><a itemprop="sameAs" href="https://plus.google.com/117800057348280296221" target="_blank" class="disp_b headfootsprtie gplussmall"></a></li>
                </ul>
            </div>
            ~if $module neq 'register'`
                ~include_partial('global/JSPC/_jspcReqCallbackSection',['subsection'=>'footer'])`
            ~/if`
        </div>
    </div>
    ~else`
    <div class="fotbg1">
        <div class="container mainwid">
            <div class="fullwidth clearfix pt20 fontlig">
                <!--start:column one-->
                <div class="fl wid265">
                    <ul class="f14 listnone foot">
                        <li class="f16 fontreg">Explore</li>
                        <li><a href="/">Home</a></li>
                        <!--<li><a href="/profile/login.php?SHOW_LOGIN_WINDOW=1">Login</a></li>
                        <li><a href="/profile/registration_pg1.php?source=js_footer">Register free</a></li>-->
                        <li><a href="/search/AdvancedSearch">Advanced search</a></li>
                        <li><a href="/successStory/story">Success stories</a></li>
                        <li><a href="/profile/site_map.php">Sitemap</a></li>
                    </ul>
                </div>
                <!--end:column one-->
                <!--start:column one-->
                <div class="fl wid265">
                    <ul class="f14 listnone foot">
                        <li class="f16 fontreg">Services</li>
                        <!--<li><a target="_blank" href="http://www.jeevansathimatchpoint.com">Jeevansathi offline</a></li>-->
                        <li><a href="/membership/jspc">Membership Options</a></li>
                        <li><a href="http://careers.jeevansathi.com">Jeevansathi Careers</a></li>
                    </ul>
                </div>
                <!--end:column one-->
                <!--start:column three-->
                <div class="fl wid265">
                    <ul class="f14 listnone foot">
                        <li class="f16 fontreg">Help</li>
                        <li><a href="/contactus/index">Contact us</a></li>
                        <!-- <li id="liveChatLinkFooter" class="cursp colorw">Live help</li> -->
                        <li><a href="/faq/feedback?width=512&checksum=~$profilechecksum`">Feedback / Queries</a></li>
                        <li><a href="/contactus/index">Jeevansathi centers (32)</a></li>
                    </ul>
                </div>
                <!--end:column three-->
                <!--start:column four-->
                <div class="fr wid177">
                    <ul class="f14 listnone foot">
                        <li class="f16 fontreg">Legal</li>
                        <li><a href="http://www.infoedge.in/">About Us</a></li>
                        <li><a href="/static/page/fraudalert">Fraud Alert</a></li>
                        <li><a href="/profile/disclaimer.php">Terms of use</a></li>
                        <li><a href="/profile/third_party_content.php">3rd party terms of use</a></li>
                        <li><a href="/profile/privacy_policy.php">Privacy policy</a></li>
                        <li><a href="/profile/conf_policy.php">Privacy Features</a></li>
                        <li><a href="/static/grievance?summon=1">Summons/Notices</a></li>
                        <li><a href="/static/grievance?grievance=1">Grievances</a></li>
                    </ul>
                </div>
                <!--end:column four-->
            </div>
            <div class="pt10 pb30 fullwid clearfix color16">
                <!--start:app available-->
                <div class="fl wid265">
                    <div class="f16 fontreg pb10"> App available on </div>
                    <a itemprop="sameAs" href="https://play.google.com/store/apps/details?id=com.jeevansathi.android" target="_blank" class="footericon"><i class="headfootsprtie androidsmall"></i></a>
                    <a itemprop="sameAs" href="https://itunes.apple.com/in/app/jeevansathi/id969994186" target="_blank" class="footericon"><i class="headfootsprtie idsmall"></i></a>
                </div>
                <!--end:app available-->
                <!--start:follow us-->
                <div class="fl wid265">
                    <div class="f16 fontreg pb10">Follow us </div>
                    <a itemprop="sameAs" href="https://www.facebook.com/jeevansathi" target="_blank" class="footericon"><i class="headfootsprtie facebooksmall"></i></a>
                    <a itemprop="sameAs" href="https://www.twitter.com/jeevansathi_com" target="_blank" class="footericon"><i class="headfootsprtie twittersmall"></i></a>
                    <a itemprop="sameAs" href="https://www.linkedin.com/company/info-edge-india-ltd" target="_blank" class="footericon"><i class="headfootsprtie linkedinsmall"></i></a>
                    <a itemprop="sameAs" href="https://plus.google.com/117800057348280296221" target="_blank" class="footericon"><i class="headfootsprtie gplussmall"></i></a>
                </div>
                <!--end:follow us-->
                <!--start:toll free-->
                <div class="fl wid265">
                    <div class="f16 fontreg padb10">Customer Service ~if $currency eq 'RS'`(Toll free)~/if`</div>
                    <div class="f22 fontlig pt15">~if $currency eq 'RS'`1-800-419-6299~else`+91-120-4393500~/if`</div>
                </div>
                <!--end:toll free-->

            </div>
        </div>
    </div>
    ~/if`
    <!--start:partner site-->
    <div class="bg-white">
        <div class="container mainwid">
            <div class="pb10 pt10 wid800 clearfix txtc pl128">
                <div class="f12 color6 fl pt30 pr36 fontreg">Partner Sites</div>
                <!--start:slider-->
                <div class="fl" style="width:600px;height:80px">
                    <div id="slider">
                        <div id="images">
                            <div class="basic">
                                <ul>
                                    <li class="pl40"><a href="http://www.99acres.com" target="_blank" title="99acres.com"><i class="headfootsprtie acre"></i></a></li>
                                    <li class="pl40"><a href="https://www.naukri.com" target="_blank" title="naukri.com"><i class="headfootsprtie nc"></i></a></li>
                                    <li class="pl40"><a href="http://www.naukrigulf.com" target="_blank" title="naukrigulf"><i class="headfootsprtie ng"></i></a></li>
            
                                </ul>
                            </div>
                            <div class="basic">
                                <ul>
                                    <li class="pl55"><a href="http://www.shiksha.com" target="_blank" title="shiksha"><i class="headfootsprtie shiksha"></i></a></li>
                                    <li class="pl55"><a href="http://www.mydala.com" target="_blank" title="mydala"><i class="headfootsprtie mydala"></i></a></li>
                                    <li class="pl55"><a href="https://www.policybazar.com" target="_blank" title="policybazar"><i class="headfootsprtie pb"></i></a></li>
                
                                </ul>
                            </div>
                            <div class="basic">
                                <ul>
                                    <li class="pl55"><a href="https://www.zomato.com" target="_blank" title="zomato"><i class="headfootsprtie zomato"></i></a></li>
                                    <li class="pl55"><a href="http://www.meritnation.com" target="_blank" title="meritnation"><i class="headfootsprtie meritn"></i></a></li>
                                    <li class="pl30"><a href="http://ambitionbox.com" target="_blank" title="AmbitionBox – Interview Prep & Company Reviews"><i class="headfootsprtie ambitionbox"></i></a></li>
                                </ul>
                            </div>
                        </div>
                        <a id="prev" href="javascript:void(0);"> 
                            <i class="headfootsprtie leftslide"></i> 
                        </a>
                        <a id="next" href="javascript:void(0);">
                            <i class="headfootsprtie rightsmall"></i> 
                        </a> 
                    </div>
                </div>
                <!--endt:slider-->
            </div>
            <div class="txtc pb15">
                <ul class="hor_list clearfix f13 fontlig disp_ib">
                    <li class="pl5"><a href="/?mobile_view=Y" class="color11">View Mobile Version</a></li>
                </ul>
            </div>
        </div>
    </div>
    <!--end:partner site-->
    <div class="bg_3">
        <div class="padall-10 txtc f12 fontreg colr2"> All rights reserved © 2016 Jeevansathi Internet Services. </div>
    </div>
  </div>
</footer>
<!--end:footer-->
<script type="text/javascript">
    $(window).load(function(){
        ~if $module eq 'register' || $module eq 'membership' || $action eq 'phoneVerificationPcDisplay' || ($module eq 'contactus' && $action eq 'index') || ($module eq 'help' && $action eq 'index')`
            ~if $profileid`
                var udObj = '~CommonUtility::getFreshDeskDetails($profileid)`';
                var userDetails = $.parseJSON(udObj);
                populateFreshDeskGlobal(userDetails['username'], userDetails['email']);
                ~if $module eq 'membership' || $fromSideLink eq '1'`
                    popupFreshDeskGlobal(userDetails['username'], userDetails['email']);
                ~/if`
            ~else`
                ~if $fromSideLink eq '1'`
                    popupFreshDeskGlobal("", "");
                ~/if`
            ~/if`
        ~/if`
        slider();
        ~if $module neq 'register'`
            initializeTopNavBar("~$loggedIn`","~$profileid`","~$module`","~$action`");
        ~/if`
    });
</script>
<!-- Begin Inspectlet Embed Code -->
<script type="text/javascript" id="inspectletjs" > 
window.__insp = window.__insp || [];
__insp.push(['wid', 1937430883]);
(function () {
    function ldinsp() {
        if (typeof window.__inspld != "undefined") return;
        window.__inspld = 1;
        var insp = document.createElement('script');
        insp.type = 'text/javascript';
        insp.async = true;
        insp.id = "inspsync";
        insp.src = ('https:' == document.location.protocol ? 'https' : 'http') + '://cdn.inspectlet.com/inspectlet.js';
        var x = document.getElementsByTagName('script')[0];
        x.parentNode.insertBefore(insp, x);
    };
    setTimeout(ldinsp, 500);
    document.readyState != "complete" ? (window.attachEvent ? window.attachEvent('onload', ldinsp) : window.addEventListener('load', ldinsp, false)) : ldinsp();
})(); 
</script>
<!-- End Inspectlet Embed Code -->
~if $module eq 'register' || $module eq 'membership' || $action eq 'phoneVerificationPcDisplay' || ($module eq 'contactus' && $action eq 'index') || ($module eq 'help' && $action eq 'index')`
    ~if !($profileid eq '8298074' || $profileid eq '13038359' || $profileid eq '12970375')`
        ~include_partial('global/freshDesk')`
    ~/if`
~/if`
