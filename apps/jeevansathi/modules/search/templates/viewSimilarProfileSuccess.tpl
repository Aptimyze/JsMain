~include_partial('global/header',[showSearchBand=>0,pageName=>$pageName,loggedInProfileid=>$loggedInProfileid,showGutterBanner=>0])`
~JsTrackingHelper::getHeadTrackJs()`
~JsTrackingHelper::setJsLoadFlag(0)`
<input type="hidden" id="percent" value="~$iPCS`"/>
<div class="clr"></div>
<div id="main_cont"> 
  <!--start:new eoi-->
  <div id="neweoi">
    <div class="pad30top pad21bottom"><span>~$BREADCRUMB|decodevar`</span></div>
    <div class="fullwidth"> 
      <!--start:left part-->
      <div class="fl" style="width:708px;"> 
        <!--start:intrest box info-->
        ~if $contactEngineConfirmation`
        <div class="bg1 brdr1 fullwidth f16">
			 <div class="pd20">
				~$contactEngineConfirmation|decodevar`
				~if $PaidStatus eq 'paid'`
				<div class="fs14" style="margin-left:23px;">
					<span>
						<a style="cursor:pointer" onclick="javascript:{check_window('show_layer_message(\'message_layer\',\'link_id\',0)');show_layer_message('message_layer','link_id',1);}" id='link_id'>View & Save
						</a> the message that you have sent to ~$contactedUsername` for later use.
					</span>
				</div>
				~/if`
			</div>
			<!--message Layer-->
			<div style="padding:5px;">
			<div onclick="javascript:check_window('show_layer_message(\'message_layer\',\'link_id\',0)')" id="message_layer" style="z-index:2000; position:relative; background:#ffffff; padding:15px 15px; display:none; color:#505050; border:1px solid #ccc;"  class="t14 lf">
				<p class="mp_0">
				<BR><b>Message:</b><span style="margin-left:4px">~$TRIM_MESSAGE`</span>  </p>
				<a class="blink t12 b" style="position:absolute; top:10px; right:10px;" onclick="javascript:show_layer_message('message_layer','link_id',0)" >Close[X]</a> 
				<div class="sp8"></div>
			~if $SAVE_MESSAGE`
				<div class="f14 mt_10 ml_15" id="s_draft_show_page">
				<input type='hidden' id="s_draft_mes_page" value="~$CUST_MESSAGE`">      
					<div class="lf">
						<input type="checkbox" style="border:0;" checked onclick="javascript:{show_save(this.checked,1);}"/>
						Save this message as
						<div id="page_mes_0" style="display:inline;position:relative" class="f12 mt_10 ml_15">
							<input type="text" class="txta" id="s_draft_name_page" maxchars="50" placeholder="Name" onblur="if(this.placeholder == ''){this.placeholder = 'Name';}" onfocus="if(this.placeholder=='Name'){this.placeholder = '';}"/>
							<div id="page_mes_1" style="display:inline; position:relative">
								&nbsp;
								~if $OVERFLOW`
									Replace with
									<select id="s_draft_id_page" class="txta" style="width:129px; height:18px;">
										<option value="">Select message</option>
										~foreach from=$DRA_MES_OPTION item=message key=id`
										<option value=~$id`>~$message`</option>
										~/foreach`
									</select>
								~/if`
							</div>
						</div>
					</div>
					<span id="but_id_page" style="display:block"> <input type="image" src="~sfConfig::get('app_img_url')`/profile/images/save_btn.gif" style="margin-left:8px; display:inline; border:0;" onclick="javascript:save_draft(1);" ></span>
					  <div class="clr"></div>
				  </div>
				~/if`
			</div>
			<div class="clr" style="height:5px;"></div>
			</div>
			<!--message layer ends here-->
		
        </div>
        ~/if`
        <!--end:intrest box info--> 
        <!-- start:membership message-->
        <style type='text/css'>
		a.btn_offer{display:inline-block; background-color:#c10325; width:100px;color:white;text-align:center;border-radius:5px;padding:4px 0; }
		.small_arrow_offer{background: #c10325 url(~sfConfig::get('app_site_url')`/images/membership-img/offer-small-arrow.png) no-repeat 1px 1px;width: 6px;height: 10px;}
		</style>
        <script type="text/javascript">
		  $.ajax({
		    type: "POST",
		    url: "~sfConfig::get('app_site_url')`/api/v3/membership/membershipDetails",
		    data : {getMembershipMessage:1}
		  }).done(function(msg){
		    if(msg.membership_message != null){
		      $("<div class='fullwidth mt_10' style='border: 1px solid rgb(231, 233, 213); background-color: #3b6f78;'><div class='pd13 center'><div class='fs18' style='font-weight: bold; text-transform: uppercase; color: #ffffff;'>"+msg.membership_message.top+"</div><div class='pad5top' style='color: #ffffff; font-size: 15px;'>"+msg.membership_message.bottom+"&nbsp;&nbsp;<a href='~sfConfig::get('app_site_url')`/membership/jspc' target='_blank' class='btn_offer'>Avail Now <span style='display:inline-block' class='small_arrow_offer'></span></a></div></div></div>").insertBefore('.membership_message');
		    }
		  });
		</script>
        <!-- end:membership message-->
        <div class='membership_message'></div>
        ~if $similarPageShow`
        <!--start:similar profile div-->
        <div class="mar30top">
          <div class="f18 color1">People Similar to ~$Username` you can Express Interest in</div>
          <div class="mar20top fullwidth">
		<div id="eoi_multi" class="fl eoi" >
		<input type="button" class="multibutton btn_view b fl"  value="Express Interest" id="multibutton">&nbsp;<i class="arrow-down"></i>
		</div>
		<div style="font-size:14px;margin-top:6px" class="fl">
			&nbsp;Select Multiple profiles and Express interest
		</div>
		</div>
          <!--start:result tuples--> 
       ~foreach from = $finalResultsArray item = detailsArr key = profileid`
		~assign var='resultNumber' value=$resultNumber+1`
		~if $detailsArr['FEATURED'] neq Y`
			~assign var='searchResultNumber' value=$searchResultNumber+1`
		~/if`

		~if $detailsArr['FILTER_REASONS'] && $filterStart eq 0`
			~assign var='filterStart' value=1`
			<div class="div_search_res_filter_msg pos_rltv1">
				<i class = "filter_arw" style="margin-top:4px;">&nbsp;</i>
				<div style="color:#FFFFFF; margin-left: 33px;">
					<span style="font-size: 18px;" class="b">Profiles below have filtered you out</span>
					<br>
					<span>Your interests will go to their "filtered" folder, so response to your interests may be delayed</span>
				</div>
			</div>
		~/if`

		~include_partial("searchTuple",[detailsArr=>$detailsArr,profileid=>$profileid,resultNumber=>$resultNumber,isAlbumArray=>$isAlbumArray,horoscopeArray=>$horoscopeArray,fieldsDisplayedInSearchTuple=>$fieldsDisplayedInSearchTuple,loggedIn=>$loggedIn,profilePicArray=>$profilePicArray,resultNumber=>$resultNumber,featurePosition=>$featurePosition,profileOrExpressButton=>$profileOrExpressButton,userGender=>$userGender,checksum=>$checksum,searchId=>$searchId,stype=>$stype,NAVIGATOR=>$NAVIGATOR,TOTAL_RECORDS=>$noOfResults,searchResultNumber=>$searchResultNumber,SORT=>$sort_logic,currentPage=>$currentPage,featuredResultNo=>$featuredResultNo,profileChecksum=>$profileChecksum,sameGenderSearch=>~$sameGenderSearch`,boldListing=>~$boldListing`,featured=>~$featured`,totalFeaturedProfiles=>~$totalFeaturedProfiles`])`

	~/foreach`
       
        <!--end:result tuples--> 
         <div class="clr_10"></div>
			 <div class="fl div3">
				<div id="eoi_bottom" class="fl eoi" >
					<input type="button" class="multibottom btn_view b fl" value="Express Interest" >
					&nbsp;<i class="arrow-up"></i> &nbsp;
				</div>
				
				<div style="font-size:14px;margin-top:6px" class="fl">
					&nbsp;Select Multiple profiles and Express interest
				</div>
			</div>
         </div>
        <!--end:similar profile div--> 
        ~else`
        <div style="font-size:14px;margin-top:6px" class="fl">
					&nbsp;There are no profiles similar to ~$Username`
				</div>
        ~/if`
        
        <div style = "display:none">
	<div id = "albumCode">
	</div>
</div>
<!-- photo layer -->
<div style = "display:none" id = "req_mes">
	~if $havephoto eq '' or $havephoto eq 'N'`
	<div class="div_interactions fl  fs12" id="success_mesPROFILEID" style="position:absolute; top:188px; left:176px" >    
		<div class="divlinks fl w240 pos_rltv1" style="padding:0px 0px 10px 10px"  >
			<div class="fr ico_close_green mar_top_4" id="closeIconPROFILEID" onclick = "close_photo_mes(PROFILEID)">
			</div>
		<p class="width100 fl"><i class="ico_right_sml fl">&nbsp;</i>Your photo request has been sent.
		<br />
		
		<font class="b"><a href="~sfConfig::get('app_site_url')`/social/addPhotos">Upload your photo now >></a></font>
		</p>
		</div>
	</div>
	~/if`
</div>
<div style = "display:none" id = "err_mes">
	<div class="div_interactions fl  fs12" id="success_mesPROFILEID" style="position:absolute; top:188px; left:176px" >    
		<div class="divlinks fl w240 pos_rltv1" style="padding:0px 0px 10px 10px"  >
			<div class="fr ico_close_green mar_top_4" id="closeIconPROFILEID" onclick = "close_photo_mes(PROFILEID)">
			</div>
		<p class="width100 fl"><i class="ico_cross fl">&nbsp;</i>ERROR MESSAGE
		</p>
		<br />
		</div>
	</div>
</div>
<!-- photo layer ends here -->

~if $profileStatus eq 'I' || $profileStatus eq 'U'`
<div style="display:none" id="notactive_eoi">
<div class="divlinks fl w350 fs12 eoi">
<div class="sp15"></div>
<i class="ico_right fl"></i>~if $profileStatus eq 'I'`Your interest will be delivered only when your profile is complete. <a href='/profile/viewprofile.php?ownview=1&EditWhatNew=incompletProfile' class="b">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Complete your profile NOW</a>~else` Your interest will be delivered once your profile goes live.~/if`
<div >&nbsp;</div>
<div class="separator fl width100"></div>
                                        <div class="fr b"><a href="#" onclick="return CLOSE_FUNC">Close [x]</a></div>

        </div>
</div>
~else`
~if $PaidStatus eq 'free'`
<div style="display:none" id="free_eoi">
<div class="divlinks fl w350 fs15 eoi">

<div class="sp15"></div>
   	   <i class="ico_right fl"></i>MESSAGE_SUCCESS
       <div >&nbsp;</div>
       ~if $FREE_TRIAL_OFFER`
       ~Messages::getFreeTrialOfferLink()`
  <div class="fs15">See Phone/Email of THISTHESE if HESHE ACCEPTS
    your interest</div>
    ~if $FREE_TRIAL_OFFER eq 'c'`
    <div class="sp24"></div>
<div class="fs15">To avail this offer,</div>

~include_partial("contacts/profile_phone_photo_c", ['profileObj' => $loginProfile])`
~/if`
 <div class="sp24"></div>
 <div class="fs15">~if $FREE_TRIAL_OFFER eq 'c'`Hurry!~/if` Offer~if $FREE_TRIAL_OFFER eq 'd'` expires on ~else` valid till~/if`&nbsp;<strong>~$loginProfile->getPROFILE_STATE()->getFTOStates()->getExpiryDate('y')`</strong>
</div>

<div class="fs15">  ~Messages::getKnowMoreLink()`</div>
<div >&nbsp;</div>
<div class="separator fl width100"></div>
                                        <div class="fr b"><a href="#" onclick="return CLOSE_FUNC">Close [x]</a></div>

        </div>
~else`
<div class="sp24"></div>
       <span >To send a personalized message to THISTHESE, </span>
<div class="sp15"></div>
<span class="fs15"><input class="fto-btn-green sprite-new cp" type="button" value="Buy Paid Membership" name="Membership" onclick="RedirectFromCE('/profile/mem_comparison.php?from_source=srp_after_eoi')"></span>      
<div class="sp24"></div>
 <div class="separator fl width100"></div>       
					<div class="fr b"><a href="#" onclick="return CLOSE_FUNC">Close [x]</a></div>
~/if`            
        </div>
</div>
~else`
<div style="display:none" id="paid_eoi">
<div class="divlinks fl w350 fs15 eoi" id="mes_PROFILEID">

<div class="sp15"></div>
   	   <i class="ico_right fl"></i>MESSAGE_SUCCESS
       <div class="sp15">&nbsp;</div>
       <span>Send HIMHER a personalized message </span>
       <div class="sp15"></div>
       <div class="fr widthauto">
        ~if $drafts`
        <select style="width:auto\9" id="drafts_PROFILEID" onchange="updatetextarea(this)">
       ~foreach from=$drafts key=k item=v`
       ~if !$textMessage`
       ~assign var='textMessage' value=$v.MESSAGE`
       ~/if`
       <option value='~$v.DRAFTID`'>~$v.DRAFTNAME`</option>
       ~/foreach`
       </select>
		~/if`
       </div>
       <div class="sp5"></div>
       
       <span><textarea rows="0" cols="0" class="width100" style="height:55px" id="textarea_PROFILEID">~$textMessage|decodevar`</textarea></span>      
       <div class="sp24"></div>
 <div class="txt_center"><input type="button" class="btn_view b curpt" value="Send" onclick="javascript:SEND_MESSAGE_FUNC" id="send_PROFILEID" style="width:50px"></div>
       <div class="sp24"></div>
 <div class="separator fl width100"></div>       
            <div class="fr b"><a  href="#" onclick="return CLOSE_FUNC">Close [x]</a></div>    
        </div>
</div>
~/if`
~/if`
<div style="display:none" id="inform_mes">
<div class="divlinks fl w350 fs15 eoi">

<div class="sp15"></div>
   	   <div class="fl" ><i class="ICON_CSS fl"></i></div><div class="fs15 fl w300" >INFORM_MESSAGE</div>
		<div class="sp24"></div>
       
 <div class="separator fl width100"></div>       
            <div class="fr b"><a href="#" onclick="return CLOSE_FUNC">Close [x]</a></div>
            
        </div>
</div>
<!--Main container ends here-->
        
      </div>
      <!--end:left part--> 
      <!--start:right part-->
      <div class="fr wid208 bg1 brdr1 fontRight">
        <div class="pad20left pad5right pad20top pad20bottom"> 
          <!--start:profile status-->
          <div class="f16 color1 brdr2 pad5bottom">Profile Completion Status</div>
          <div class="pad20top">
            <div class="fullwidth ">
				<div class="fl">
					<div id="circle">
					</div>
				</div>
              <div class="color2 pad10top pad18left"> Your profile is <br/>
                ~$iPCS`% complete </div>
              <div class="clr"></div>
            </div>
          </div>
          ~if $iPCS neq 100`
          <div class="color2"> Make it 100% complete to get faster and better responses to your interests </div>
          ~/if`
          <div class="pad15top color2">
			  <ul>
				  ~foreach from=$arrMsgDetails key=szKey item=szVal`
					  <li>
						~if $szKey neq PHOTO and $szKey neq RELIGION`
							<a href="~$SITE_URL`/P/viewprofile.php?checksum=~$CHECKSUM`&profilechecksum=~$profileChecksum`&~$arrLinkDetails[$szKey]`">~$szVal`</a><br>
						~elseif $szKey eq PHOTO`
							<a href="~$SITE_URL`/social/addPhotos?checksum=~$CHECKSUM`&profilechecksum=~$profileChecksum`" >~$szVal`</a><br>
						~elseif $szKey eq RELIGION`
							<a href="~$SITE_URL`/P/viewprofile.php?checksum=~$CHECKSUM`&profilechecksum=~$profileChecksum`&~$arrLinkDetails[$szKey]`&gender_logged_in=~$GENDER_LOGGED_IN`" >~$szVal`</a><br>
						~/if`
					   </li>
				~/foreach`
			</ul>
        </div>
            
          <!--end:profile status--> 
          
		
	<!-- EXPITY ALERT -->
          <!--start:membership status-->
          <div class="pad30top color2">
            <div class="f16 color1 brdr2 pad5bottom">Membership Benefits</div>
				
				~if $freeMember`            
				<div class="pad22top">You only have a Free Account. You don’t have access to the following benefits of a Premium account:</div>
				~elseif $erishta`
				<div class="pad10top">You are currently a <b>eRishta</b> member</div>
				~else`
				<div class="pad10top">You are currently a <b>eValue</b> member</div>
				~/if`
				  <!-- EXPITY ALERT -->
					~if $EXPIRY_ALERT`
							<div class="color2">			
							 ~if $EXPIRY_ALERT eq 1`
								  <div>Renew membership before <b>~$EXPIRY_DT`</b></div>
							 ~elseif $EXPIRY_ALERT gt 1`
								 <div>Please renew before <span style="color:#E40410">~$EXPIRY_DT`</span> to get 15% discount on all plans. <a href="~$SITE_URL`/P/mem_comparison.php?from_source=ECP_Renew" ><b>Renew Now</b></a></div>
							  ~/if`
							 </div>
					~/if`            
            ~if $freeMember`
              <ul>~$paymessage|decodevar`</ul>
            <div class="pad20top"> <div class="center bg2 pada1" style="width:70px;"><a href="~$SITE_URL`/P/mem_comparison.php?checksum=~$checksum`&from_source= RightWidget_ECP" class="f16" style="color:#fff;">Upgrade</a> </div></div>            
            ~elseif $erishta`
             <div class="pad22top"> Benefits of your membership:
            <ul>~$paymessage|decodevar`</ul>
            </div>           
            ~else`
            <div class="pad22top"> Benefits of your membership:
            <ul>~$paymessage|decodevar`</ul>
            </div>
           ~/if`
          </div>
        
          <!--end:membership status--> 
          
          <!--start:success story-->
          <div class="pad45top color2">
				<div class="f16 color1 brdr2 pad5bottom">Success Stories</div>
				~foreach from=$rightPanelStory key=k item=story`
				<!--start:story-->
					<div class="pad20top"> <img src="~PictureFunctions::getCloudOrApplicationCompleteUrl($story.FRAME_PIC_URL)`" width="154" height="104" /> </div>
					<div class="pad10top pad10bottom color4 f13"> ~$story.NAME2` weds ~$story.NAME1` </div>
					<div class="f13">~$story.STORY`<a href="~sfConfig::get('app_site_url')`/successStory/completestory?sid=~$story.SID`">Full story</a> </div>
				 <!--end:story-->
				~/foreach`
          </div>
          <!--end:success story--> 
          
          
        </div>
      </div>
      <!--end:left part-->
      <div class="clr"></div>
    </div>
  
  <!--end:new eoi--> 
  
</div>

~include_partial('global/footer',[bms_topright=>$bms_topright,bms_bottom=>$bms_bottom,data=>~$loggedInProfileid`,bms_topright_loggedin=>$bms_topright_loggedin,pageName=>$pageName])`
<script>
//Draft loader
	var dra_loader="<div style=\"padding: 5px 0pt 0pt; text-align: center;\" class=\"f11 mt_10 ml_15 gry\"><img src=\"~sfConfig::get('app_site_url')`/profile/images/loader_small.gif\"/><br/>Saving message...      <div class=\"clr\"/>    </div>";
	var dra_end1='<div style="padding: 5px 4pt 0pt;" class="f12 mt_10 ml_15"> Your message has been saved as <span class="b">';
	var dra_end2="</span><div class='clr'></div>";
	var d_status='~$d_status`';
	var currentPage = "~$currentPage`";
	var loggedIn = "~$loggedIn`";
	var showOnlyGunaMatch='Y';
	var loggedInProfileid = "~$loggedInProfileid`";
	//var searchId = "~$searchId`";
	//if(loggedIn == '1' && '~$searchedGender`' != '~$loggedInGender`')
		//astro_icons();
	var stype='CO';
	var responseTracking = 4;
	var membershipStatus="~$PaidStatus`";
	~if $profileStatus eq 'I' || $profileStatus eq 'U'`
	membershipStatus="notactive";
	~/if`
	var eoiButton="~$profileOrExpressButton`";
	var himher="~$himher`";
	var heshe="~$heshe`";
	~if $drafts and $PaidStatus neq 'free'`
	var pattern1 = /\#n\#/g;
	var MESCE = new Array(); 
	~foreach from=$drafts key=k item=v`
	  temp="~$v.MESSAGE|decodevar`";
      MESCE['~$v.DRAFTID`']=temp.replace(pattern1,"\n");
       ~/foreach`
	~/if`
	var isExpressButton="~$EXPRESSBUTTON`";

	//var bms_searchMid = '~$sf_request->getAttribute('bms_searchMid')`';
	
	var PH_UNVERIFIED_STATUS="~$PH_UNVERIFIED_STATUS`";
  //Stop invalid phone layer
  var SHOW_UNVERIFIED_LAYER="~$SHOW_UNVERIFIED_LAYER`";
	var FREE_TRIAL_OFFER="~$FREE_TRIAL_OFFER`";
	var presetEoiMessage="~$presetEoiMessage|decodevar`";
	var presetAccMessage="~$presetAccMessage|decodevar`";
	var presetDecMessage="~$presetDecMessage|decodevar`";
	var postDataVar={'page_source':'VSM'};

( function( $ ){
		var nPercent        = $( '#percent' ).val() ? $( '#percent' ).val() : 50;
		var showPercentText = $( '#percentOn' ).prop( 'checked' );
		var thickness       = $( '#thickness' ).val() ? $( '#thickness' ).val() : 3;
		var circleSize      = $( '#circle-size' ).val() ? $( '#circle-size' ).val() : 100;
	$( '#circle' ).progressCircle();

	$( '#circle' ).progressCircle({
			nPercent        : nPercent,
			showPercentText : showPercentText,
			thickness       : thickness,
			circleSize      : circleSize
		});
})( jQuery );
function show_layer_message(layer_id,link_id,show_hide)
{
	if(show_hide==1)
		if(dID(layer_id) && dID(link_id))
		{
			function_to_call="show_layer_message('message_layer','link_id',0)";
			common_check=1;
			dID(layer_id).style.display='inline';
			dID(link_id).className="gray b";
			dID(link_id).style.cursor="default";
			
		}
	if(show_hide==0)
		if(dID(layer_id) && dID(link_id))
		{
			function_to_call="";
			common_check=0;
			dID(layer_id).style.display='none';
                        dID(link_id).className="b";
			dID(link_id).style.cursor="pointer";
		}
}
</script>
~BrijjTrackingHelper::setJsLoadFlag(1)`
