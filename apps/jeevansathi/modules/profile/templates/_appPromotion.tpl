<!--start:html to be added-->
<script>
AppLoggedInUser=0;
</script>
~if $PERSON_SELF`
		~if $AppLoggedInUser`
	                <div id="appPromoMyProfile" class="pageHdCont" style="display:none;">
	                        <div class="nl_pageHd">	                   
	                        <div class="pull-left nl_wid49">
	                                <div class="nl_f13">To edit your profile, download the Jeevansathi Android App</div>
	                                <div class="nl_f11"> </div>
	                        </div>
	                        <div class="pull-left nl_wid38 nl_txtc">
	                                <div class="nl_pt12">
	                                <a href="~$SITE_URL`/static/appredirect?type=appPromotionProfile" class="nl_btn1">
	                                        Download
	                                </a>
	                            </div>
	                        </div>	                       
	                        <div class="pull-right nl_pt15">
	                                <div id="appPromoHideProfile" class="nl_close"></div>
	                            </div>
	                        <div class="clr"></div>
	                    </div>	                   
	                </div>
	     ~else`
				<div id="appPromoMyProfile" class="pageHdCont" style="display:none;">
	                        <div class="nl_pageHd">	                   
	                        <div class="pull-left nl_wid49">
	                                <div class="nl_f13">To edit your profile, open the Jeevansathi Android App</div>
	                                <div class="nl_f11"> </div>
	                        </div>
	                        <div class="pull-left nl_wid38 nl_txtc">
	                                <div class="nl_pt12">
	                                <a href="~$SITE_URL`/static/appredirect?type=appPromotionProfile" class="nl_btn1">Open</a>
	                            </div>
	                        </div>	                       
	                        <div class="pull-right nl_pt15">
	                                <div id="appPromoHideProfile" class="nl_close"></div>
	                            </div>
	                        <div class="clr"></div>
	                    </div>	                   
	              </div>
	         ~/if`
~else`
~if $from_mailer`
 <!--start:html to be added-->
	                <div id="appPromoMyProfile" class="pageHdCont" style="display:none;">
	                     <div class="nl_pageHd">	                   
	                        <div class="pull-left nl_wid49">
	                                <div class="nl_f18">Jeevansathi App</div>
	                                <div class="nl_f13">FREE - In the Play Store</div>
	                        </div>
	                        <div class="pull-left nl_wid38 nl_txtc">
	                                <div class="nl_pt12">
	                                ~if $AppLoggedInUser`
	                                <a href="~$SITE_URL`/static/appredirect?type=appPromotionProfile" class="nl_btn1">
	                                        Download
	                                </a>
	                                ~else`
	                                <a href="~$SITE_URL`/static/appredirect?type=appPromotionProfile" class="nl_btn1">
	                                        Open
	                                </a>
	                                ~/if`
	                            </div>
	                        </div>	                       
	                        <div class="pull-right nl_pt15">
	                                <div id="appPromoHideProfile" class="nl_close" ></div>
	                            </div>
	                        <div class="clr"></div>
	                    </div>	                   
	                </div>
	                <div class="clr" style="margin-top:13px;"></div>
~/if`
~/if`
<section id = "appPromoUsername" class="s-info-bar">
<div class="pgwrapper">
Profile of ~$PROFILENAME`
~$BREADCRUMB|decodevar`
</div>
</section>


