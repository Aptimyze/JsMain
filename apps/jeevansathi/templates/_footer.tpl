<input type="Hidden" name="id_checked" id="id_checked" value="~$sf_request->getParameter('ID_CHECKED')`" >
<input type="Hidden" name="CALL_THICK" id="CALL_THICK" value="~$sf_request->getParameter('CALL_ME')|decodevar`" >
<input type="Hidden" name="AFTER_LOGIN_CALL" id="AFTER_LOGIN_CALL" value="~$sf_request->getParameter('after_login_call')`">
<input type="hidden" name="NAVIGATOR" value="~$NAVIGATOR`" id="NAVI">
<style type="text/css">
  .appsp {
  background-image:url(/images/Promo/appicons.png);
  background-repeat:no-repeat
}
.icon1 {
  background-position: 1px 0px;
  width: 133px;
  height: 39px;
}
.icon2 {
  background-position:-138px 0px;
  width: 117px;
  height: 39px;
}

</style>

<!--
<div class="ftr-browse-matri mar20top sprte-hdr-foot">
	<div class="ftr-con pad48left">
  	 <span class="fs16 text mar20right " >Browse Matrimonial Profiles by</span>
     <span class="tabs">Others</span><span class="tabs sprte-hdr-foot">Mother Tongue</span><span class="tabs sprte-hdr-foot">Caste</span><span class="tabs sprte-hdr-foot">Religion</span>
       <span class="tabs sprte-hdr-foot">Profession</span> <span class="tabs sprte-hdr-foot">City</span><span class="tabs sprte-hdr-foot">State</span><span class="tabs sprte-hdr-foot">Country</span></div>
	
    </div>
  <div class="h50 clear">
    <div class="ftr-con center fs12" style="line-height:1.1">
      <a href="#">Deaf &amp; Dumb Matrimonials </a>|  <a href="#">Physically Handicapped Matrimonials </a>| <a href="#"> Manglik Matrimonials</a> | <a href="#"> Mentally Challenged Matrimonials</a> | <a href="#"> Divorcee Matrimonials</a>
       | <br />
        <a href="#"> Widower Matrimonials</a> |  <a href="#">Widow Matrimonials</a> | <a href="#"> HIV Positive</a> |  <a href="#">Visually Impaired</a> |  <a href="#">OverWeight</a> | <a href="#"> Senior Citizen </a>
    </div>
</div>
-->
~if 0`
    ~include_component('static', 'callHelp')`
~else`    
    ~include_component('common', 'helpWidget')`
~/if`    
<div id="ftr-id">   
~if !get_slot('passwordReset')`
<div class="ftr-divider block"></div>

<div class="ftr-con">

	~assign var=zedoValue value= $sf_request->getAttribute('zedo')`
	~assign var=zedo value= $zedoValue["zedo"]`
	<div id="zt_~$zedo['masterTag']`_bottom" class="ftr-banner" >
	</div>
</div>
</div>
~/if`
 
   <div style="border-top: 1px solid #eaeaea; border-bottom:3px solid #f5f5f5"></div>
   
   <div class="sp10"></div>

   <div class="ftr-con">
	 <div class=" w142 fl fs12 pad5left"><strong >Explore</strong>
     
     <div class="sp10"></div>
        
        <div >

          <p><a href="~sfConfig::get('app_site_url')`/profile/before_log.php?page=2">My Contacts</a></p>
          <p>&nbsp;            </p>
          <p><a href="~sfConfig::get('app_site_url')`/profile/before_log.php?page=3">Edit Profile</a></p>
          <p>&nbsp;</p>
          <p>
            <a href="~sfConfig::get('app_site_url')`/profile/revamp_filter.php">Settings</a></p>
          <p>&nbsp;</p>
          <p>
            <a href="~sfConfig::get('app_site_url')`/profile/advance_search.php">Advanced Search</a></p>
          <p>&nbsp;</p>
          <p><a href="~sfConfig::get('app_site_url')`/successStory/story">Success Stories</a></p>
          <p>&nbsp;</p>
	<!--
          <p><a href="~sfConfig::get('app_site_url')`/matrimonial-matrimony-">Browse</a></p>
          <p>&nbsp;</p>-->
          <p><a href="~sfConfig::get('app_site_url')`/profile/site_map.php">Sitemap</a></p>
       </div>
        
     </div>
        <div class="w155 fl ftr-links-bdr-grey pad5left fs12"><strong>Services</strong>
     <div class="sp10"></div>
        <div >

<p><a href="~sfConfig::get('app_site_url')`/profile/mem_comparison.php?from_source=from_footer">Membership Options</a></p>
<p>&nbsp;</p>
<p>
  <a href="~sfConfig::get('app_site_url')`/static/advertise">Advertise with us</a></p>
<p>&nbsp;</p>
<p><a href="~sfConfig::get('app_site_url')`/contactus/index">Jeevansathi centers/offices</a></p>
<p>&nbsp;
  </p>
<p><a href="~sfConfig::get('app_site_url')`/contactus/index?st_sel=Delhi">Delhi /NCR</a> |
  <a href="~sfConfig::get('app_site_url')`/contactus/index?st_sel=Maharashtra">Mumbai</a> |
  <a href="~sfConfig::get('app_site_url')`/contactus/index?st_sel=Maharashtra">Pune</a> |
  <a href="~sfConfig::get('app_site_url')`/contactus/index?st_sel=Madhya%20Pradesh">Indore</a> |
  <a href="~sfConfig::get('app_site_url')`/contactus/index?st_sel=Madhya%20Pradesh">Bhopal</a> |
  <a href="~sfConfig::get('app_site_url')`/contactus/index?st_sel=Uttar%20Pradesh">Lucknow</a> |
  <a href="~sfConfig::get('app_site_url')`/contactus/index?st_sel=Uttar%20Pradesh">Kanpur</a> |
  <a href="~sfConfig::get('app_site_url')`/contactus/index?st_sel=Gujarat">Surat</a> |
  <a href="~sfConfig::get('app_site_url')`/contactus/index?st_sel=Gujarat">Baroda</a> |
  <a href="~sfConfig::get('app_site_url')`/contactus/index?st_sel=Karnataka">Bangalore</a> |
  <a href="~sfConfig::get('app_site_url')`/contactus/index">Others</a>
</p>
<p>&nbsp;</p>
<p><a target="_blank" href="http://careers.jeevansathi.com">Jeevansathi Careers</a></p>
        </div>
        </div>
        
        
        <div class="w155 fl ftr-links-bdr-grey pad5left fs12"><strong>Help</strong>
     <div class="sp10"></div>
        
        <div >
          <p><a href="~sfConfig::get('app_site_url')`/contactus/index">Contact Us</a></p>
          <p>&nbsp;</p>
          <p>
	<!-- <a onclick="javascript:window.open('http://server.iad.liveperson.net/hc/13507809/?cmd=file&amp;file=visitorWantsToChat&amp;offlineURL=http://www.jeevansathi.com/profile/faq_redirect.htm&amp;site=13507809&amp;imageUrl=http://www.jeevansathi.com/images_try/liveperson&amp;referrer='+escape(document.location),'chat13507809','width=472,height=320');return false;" target="chat13507809" href="http://server.iad.liveperson.net/hc/13507809/?cmd=file&amp;file=visitorWantsToChat&amp;offlineURL=http://www.jeevansathi.com/profile/faq_redirect.htm&amp;site=13507809&amp;byhref=1&amp;imageUrl=http://www.jeevansathi.com/images_try/liveperson" >Live help</a></p>
          <p>&nbsp;</p>
          <p> --><a href="~sfConfig::get('app_site_url')`/faq/feedback?width=512&checksum=~$sf_request->getAttribute('checksum')`" class="thickbox">Feedback/Queries</a></p>
          <p>&nbsp; </p>
<p><a href="~sfConfig::get('app_site_url')`/faq/index">Frequently Asked Questions</a></p>
<p>&nbsp;  </p>
<p><a href="~sfConfig::get('app_site_url')`/contactus/index">Jeevansathi centers/offices</a></p>
        </div></div>
        <div class="w155 fl ftr-links-bdr-grey pad5left fs12"><strong>Legal</strong>
     <div class="sp10"></div>
        
        <div >
          <p><a href="http://www.infoedge.in/">About Us</a></p>
          <p>&nbsp;</p>

          <p>
            <a href="~sfConfig::get('app_site_url')`/static/page/fraudalert">Fraud Alert</a></p>
          <p>&nbsp;</p>

          <p>
            <a href="~sfConfig::get('app_site_url')`/profile/disclaimer.php">Terms &amp; Conditions</a></p>
          <p>&nbsp;</p>
<p>
          <a href="~sfConfig::get('app_site_url')`/profile/third_party_content.php">Third party Terms &amp; Conditions</a></p>
<p>&nbsp;            </p>
          <p><a href="~sfConfig::get('app_site_url')`/profile/privacy_policy.php">Privacy Policy</a></p>
          <p>&nbsp;            </p>
          <p><a href="~sfConfig::get('app_site_url')`/profile/conf_policy.php">Privacy Features</a></p>
	<p>&nbsp;            </p>
          <p><a href="~sfConfig::get('app_site_url')`/static/grievance?summon=1">Summons/Notices</a></p>
<p>&nbsp;            </p>
          <p><a href="~sfConfig::get('app_site_url')`/static/grievance?grievance=1">Grievances</a></p>
        </div>
</div>
        <div class="w155 fl ftr-links-bdr-grey pad5left fs12"><strong>Our Group</strong>
     <div class="sp10"></div>
        
          <div >
            <p><a target="_blank" href="https://www.naukri.com/">Naukri.com - Jobs in India</a></p>
            <p>&nbsp;</p>
            <p>
              <a target="_blank" href="http://www.naukrigulf.com/">Naukrigulf.com - <br />
              Jobs in middle east</a></p>
            <p>&nbsp;              </p>
            <p><a target="_blank" href="http://www.99acres.com/">99acres.com -<br />
            Properties in India</a></p>
            <p>&nbsp;</p>
            <p>
              <a target="_blank" href="http://www.jeevansathimatchpoint.com/">Jeevansathi Matchpoints</a></p>
            <p>&nbsp;</p>
            <p>
              <a target="_blank" href="http://www.brijj.com/">Brijj.com - <br />
              Professional Networking</a></p>
            <p>&nbsp;</p>
            <p>
              <a target="_blank" href="http://www.shiksha.com/">Shiksha.com - <br />
              Education Career Info</a></p>
            <p>&nbsp;</p>
            <p>
              <a target="_blank" href="http://www.allcheckdeals.com/">AllCheckDeals - <br />
              Property Brokerage in India</a></p>
            <p>&nbsp; </p>
            <p><a target="_blank" href="http://www.firstnaukri.com/">FirstNaukri - 
            Job site for Freshers/College Students</a></p>
          </div>
        
     </div>
        <div class=" w132 fl ftr-links-bdr-grey pad5left fs12"><strong>Our Partners</strong>
     <div class="sp10"></div>
        
        <div >
          <p><a target="_blank" href="https://www.policybazaar.com/">Policybazaar.com -<br /> Insurance <br /> India</a></p>
          <p>&nbsp;</p>
          <a target="_blank" href="http://www.meritnation.com/">Meritnation.com - <br />Online Educational<br /> Assessment</a>            </p>
          <p>&nbsp;</p>
          <p><a target="_blank" href="https://www.zomato.com/">Zomato - <br />Restaurant Directory</a>            </p>
          <p>&nbsp;</p>
          <p><a target="_blank" href="http://www.mydala.com/" title="Online Shopping in India - Best Deals">mydala - Best deals in India</a></p>
        </div></div>
        
        
 </div><!-- FOOTER LINKS ENDS -->
   <div class="clear"></div>
   <div class="sp15"></div>
       <div align="center" style="margin-top:15px;">

    ~if JsConstants::$AndroidPromotion`
   <div style="margin-bottom: 7PX;font-size: 13px;">Get instant notifications. Download our Free Apps</div>
<a href="/static/Appredirect?type=iosPcFooter"><i class="appsp icon1"></i></a> <a href="/static/Appredirect?type=androidMobFooter"><i class="appsp icon2"></i></a>
<div style='height:15px;'> </div>
      
   ~/if`
   
<div class="switchBx">
                        <b>Switch</b> &nbsp; &nbsp; <a href="~sfConfig::get('app_site_url')`?mobile_view=Y">Mobile</a>&nbsp; &nbsp; <span class="switchDdr">|</span> &nbsp; &nbsp; Desktop
                </div>
   <div class="sp15"></div>
<div class="h77 sprte-hdr-foot ftr-bot" >
   	<div class="ftr-con ">
 <div class="sp10"></div>
     <div class="w130 h38 ftr-naukri-logo sprte-hdr-foot">&nbsp;</div>
     <div class="fs12 center mar5top" >Copyright © ~$smarty.now|date_format:"%Y"` Jeevansathi Internet Services</div>
            </div>
   </div>
   </div>
   	<script type="text/javascript">
~if $sf_request->getAttribute("AJAX_CALL_MEMCACHE") eq 2`
$(document).ready(function(){
$.ajax({
  url: "/common/ProfileMemcache/",
  context: document.body
});
});
~/if`
     var bms_sideBanner = ~if ~$zedo['tag']['side']['id']` neq ''`1 ~else` 0 ~/if`;
        
        imgLoader = new Image();// preload image
        imgLoader.src = tb_pathToImage;
        
       ~if !get_slot('passwordReset')`
        if(bms_sideBanner  && $(window).width() > 1060)
        	$(document).ready(function () {
            	gutterBanner();
    	});
        else $('#gutterBanner').css('display','none');
		~/if`
	$('.thickbox').colorbox();

        var navig=document.getElementById("NAVI").value;
        var loggedInProfileid = '~$data`';

	if(typeof(user_login)!="undefined" && !user_login)
	{
		if(window.parent!==window)
		{
			//parent.document.location.href=document.location.href;
		}
	}
	try{
	if(document.title)
		top.document.title=document.title;
	else
		top.document.title="Jeevansathi.com";
	}
	catch(err)
	{
		
	}
        </script>
~* webengage code starts *`
<script id="_webengage_script_tag" type="text/javascript">
~assign var=zedoValue value= $sf_request->getAttribute('zedo')`
var _weq = _weq || {};
~if $smarty.server.HTTP_HOST eq "xmppdev.jeevansathi.com"`
_weq['webengage.licenseCode'] = '~ldelim`1341067c1';
~else`
_weq['webengage.licenseCode'] = '~ldelim`10a5cc320';
~/if`
_weq['webengage.widgetVersion'] = "4.0";
_weq['webengage.notification.ruleData'] = {
      "Gender" : "~$zedoValue.A2`",
      "Source": "~$zedoValue.j1`",
      "Days since registration" : ~if $zedoValue.j2`~$zedoValue.j2`~else`0~/if`,
      "Paid" : "~$zedoValue.d2`",
      "Community" : "~$zedoValue.j3`"
    };

(function(d)
{ var _we = d.createElement('script'); _we.type = 'text/javascript'; _we.async = true; _we.src = (d.location.protocol == 'https:' ? "https://ssl.widgets.webengage.com" : "http://cdn.widgets.webengage.com") + "/js/widget/webengage-min-v-4.0.js"; var _sNode = d.getElementById('_webengage_script_tag'); _sNode.parentNode.insertBefore(_we, _sNode); }

)(document);
$(window).load(function(){
  $.ajax({
    url:'/profile/trackOldFooter.php',
    type: 'POST',
    async:true,
    cache:false,
  });
});
</script>
~* webengage code ends *`
