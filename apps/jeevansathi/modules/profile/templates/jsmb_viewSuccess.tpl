<!-- Sub Title -->	
	~include_partial("profile/appPromotion",['PERSON_SELF'=>$PERSON_SELF,'AppLoggedInUser'=>$sf_request->getAttribute('AppLoggedInUser'),'from_mailer'=>$from_mailer,'PROFILENAME'=>$PROFILENAME,'BREADCRUMB'=>$BREADCRUMB,'SITE_URL'=>sfConfig::get('app_site_url')])`
	~if !$PERSON_SELF`
		<!-- Action Button -->
		<section class="action-btn bdr-btm">
			<div class="pgwrapper">
				
			~if $SHOW_NEXT_PREV`
				~if $SHOW_PREV || $SHOW_NEXT`
					~if $SHOW_PREV`
						~if $fromPage eq contacts`
							<a href="/profile/viewprofile.php?~$prevLink`&responseTracking=~$responseTracking`" class="pull-left btn pre-next-btn">Previous</a>
						~else`
							<a href="/profile/viewprofile.php?show_profile=prev&total_rec=~$total_rec`&actual_offset=~$actual_offset`&j=~$j`&responseTracking=~$responseTracking`&searchid=~$searchid`~$other_params`&~$NAVIGATOR`" class="pull-left btn pre-next-btn">Previous</a>
						~/if`
					~/if`
				~if $SHOW_NEXT`
					~if $fromPage eq contacts`
						<a href="/profile/viewprofile.php?~$nextLink`&responseTracking=~$responseTracking`"  class="pull-right btn pre-next-btn">Next</a>
					~else`
						<a href="/profile/viewprofile.php?show_profile=next&total_rec=~$total_rec`&actual_offset=~$actual_offset`&j=~$j`&responseTracking=~$responseTracking`&searchid=~$searchid`~$other_params`&~$NAVIGATOR`" class="pull-right btn pre-next-btn">Next</a>
					~/if`
				~/if`
			~/if`
	~/if`
			</div>
		</section>
	~/if`
	
	<!-- Search List -->
	<section class="js-list no-brd">
		<div class="pgwrapper">
			~if $PERSON_SELF`
				~if $HAVEPHOTO eq N || !$HAVEPHOTO`
				<div style="padding-bottom:10px;">Upload your photo to get better responses.<br>
					<a href="/search/partnermatches" style="text-decoration:none;color: #0045a9" >Skip to Desired Partner Matches</a>
				</div>
				~/if`
			~/if`
			~if $lastLoginArr["LAST_LOGIN_SHOW"] && !$PERSON_SELF`
				<div class="login-info">~JsCommon::getLastLogin($lastLoginArr["LAST_LOGIN_DT"])`</div>
			~/if`
            ~if CommonFunction::isJsExclusiveMember($profile->getSUBSCRIPTION())`
				<b class="login-info b" style="background-color:white;float:right;color:#800000;font-size:14px" >~mainMem::JSEXCLUSIVE_LABEL`</b>
			~else if CommonFunction::isEvalueMember($profile->getSUBSCRIPTION())`
				<b class="login-info b" style="background-color:white;float:right;color:#800000;font-size:14px" >~mainMem::EVALUE_LABEL`</b>
			~else if CommonFunction::isErishtaMember($profile->getSUBSCRIPTION())`
				<b class="login-info b" style="background-color:white;float:right;color:#800000;font-size:14px">~mainMem::ERISHTA_LABEL`</b>
				~else if CommonFunction::isEadvantageMember($profile->getSUBSCRIPTION())`
				<b class="login-info b" style="background-color:white;float:right;color:#800000;font-size:14px">~mainMem::EADVANTAGE_LABEL`</b>
			~/if`
			<ul>
				<li>
					<div class="img-holder">
						~if $PHOTO eq ""`
							~if $PERSON_SELF`
							<div id="imageClick_PhotoInput" style="cursor:pointer">
								~if $GENDER eq "M"`
									<img src=	"~sfConfig::get('app_img_url')`/~StaticPhotoUrls::mobileUpdate_Male_stockImage`" style="width:75px !important;height:100px !important;"/>
								~else`
									<img src="~sfConfig::get('app_img_url')`/~StaticPhotoUrls::mobileUpdate_Female_stockImage`" style="width:75px !important;height:100px !important;"/>
								~/if`
							</div>
							~/if`
							~if !$PERSON_SELF`
								~if $GENDER eq "M"`
									<img src=	"~sfConfig::get('app_img_url')`/~StaticPhotoUrls::requestPhotoMaleProfile`" style="width:75px !important;height:100px !important;"/>
								~else`
									<img src="~sfConfig::get('app_img_url')`/~StaticPhotoUrls::requestPhotoFemaleProfile`" style="width:75px !important;height:100px !important;"/>
								~/if`
							~/if`	
								~if !$PERSON_SELF`
									~if $PHOTO_REQUESTED`
											<span class="photoRequestSent">
												Photo Requested
											</span>									
									~else`
											<a href="~sfConfig::get('app_site_url')`/social/photoRequest?newPR=1&amp;profilechecksum=~$PROFILECHECKSUM`" class="requestPhoto">
												Request photo
											</a>
									~/if`
								~/if`
						~else`
								~if !$stopAlbumView`<a  href="~sfConfig::get('app_site_url')`/profile/layer_photocheck.php?checksum=~$CHECKSUM`&profilechecksum=~$PROFILECHECKSUM`&seq=1&~$NAVIGATOR`&nav_type=DP" >~/if`
								<img  src="~$PHOTO`" style="width:75px !important;height:100px !important;"/></a>
						~/if`
						~if $ALBUM_CNT && !$stopAlbumView`
							<div class="v-albm">
								<a href="~sfConfig::get("app_site_url")`/profile/layer_photocheck.php?checksum=~$CHECKSUM`&profilechecksum=~$PROFILECHECKSUM`&seq=1&~$NAVIGATOR`&nav_type=DP">~if $ALBUM_CNT eq 1`Larger Photo~else`View Album~/if`</a>
							</div>
						~/if`
						~if $PERSON_SELF`
							~if $NO_OF_PHOTOS`
								<div class="v-albm">
									<a href="~sfConfig::get("app_site_url")`/profile/layer_photocheck.php?checksum=~$CHECKSUM`&profilechecksum=~$PROFILECHECKSUM`&seq=1&~$NAVIGATOR`&nav_type=DP">~if $NO_OF_PHOTOS eq 1`Larger Photo~else`View Album~/if`</a>
								</div>
							~/if`
						~/if`
					</div>
					~if !$PERSON_SELF`
					<div class="u-short-info">
					~else`
					<div class="u-short-info minHeight">
					~/if`
						<div class="u-code">						
							<a>~$PROFILENAME`</a> 
						~if !$PERSON_SELF`
							~if $BOOKMARKED neq 1`
								<span class="shortlist"><img src="~sfConfig::get("app_img_url")`/images/mobilejs/revamp_mob/slist-arw.jpg"/>
								<a href="~sfConfig::get("app_site_url")`/profile/bookmark_add.php?type=show&MODE=S&senders_data=~$PROFILECHECKSUM`&~$NAVIGATOR`&nav_type=DP">Shortlist</a></span>
							~else`
								<span class="shortlist"><img src="~sfConfig::get("app_img_url")`/images/mobilejs/revamp_mob/slist-arw.jpg"/>Shortlisted</span>
							~/if`
							</div>
							
							<div class="u-sdesc1" >
							~$AGE` yrs, ~$HEIGHT|decodevar`<br>~$religionSelf`,~if $smallCasteMobile`~$smallCasteMobile|decodevar`~/if`<br>~$smallMtongueMobile|decodevar`<br>~if $EDU_LEVEL_NEW`~$EDU_LEVEL_NEW`,~/if` ~if $OCCUPATION`~$OCCUPATION`~/if`<br>~if $CITY_RES`~$CITY_RES`~else` ~$COUNTRY_RES`~/if` ~if $incomeSelf` , ~$incomeSelf`~/if`</div>
							
							</div>
							<div class="clearfix"></div>
							<!-- express & Contact button starts-->
							<div class="action-btn-2">									
							~if $preActionUrl`
								~if $preActionUrl neq 'Accept'`
									~if $tabTemplateMobile eq "1"`
									<a class="btn greynew-btn">Interest Expressed</a>
									~else`
									<a href="/contacts/~$preActionUrl`?checksum=~$CHECKSUM`&profilechecksum=~$PROFILECHECKSUM`&to_do=~$TO_DO`&~$NAVIGATOR`&nav_type=DP&STYPE=~$STYPE`" class="btn active-btn">~$tabName`</a>
									~/if`
								~else`
									<a href="/contacts/PostAccept?checksum=~$CHECKSUM`&profilechecksum=~$PROFILECHECKSUM`&to_do=accept&~$NAVIGATOR`&nav_type=DP&STYPE=~$STYPE`~if $responseTracking neq ''`&responseTracking=~$responseTracking`~/if`" class="btn active-btn first">Accept</a>
									<a href="/contacts/PostNotinterest?checksum=~$CHECKSUM`&profilechecksum=~$PROFILECHECKSUM`&to_do=decline&~$NAVIGATOR`&nav_type=DP~if $responseTracking neq ''`&responseTracking=~$responseTracking`~/if`"  class="btn active-btn second">Not Interested</a>
								~/if`
							~/if`
							~if !$sf_request->getAttribute('login')`
								<a href="/contacts/PostEOI?checksum=~$CHECKSUM`&profilechecksum=~$PROFILECHECKSUM`&to_do=~$TO_DO`&~$NAVIGATOR`&nav_type=DP&STYPE=~$STYPE`"  class="btn active-btn">~$tabName`</a>
							~/if`
							<a href="/contacts/PreContactDetails?checksum=~$CHECKSUM`&profilechecksum=~$PROFILECHECKSUM`&to_do=view_contact&~$NAVIGATOR`&nav_type=DP&STYPE=~$STYPE`" class="btn active-btn ~if $preActionUrl eq 'Accept'` third~/if`">Contact Details</a>
							</div>
						<!-- express & Contact button ends-->
						~else`
							</div>
							<div class="mb10" id = "uploadPhotoButton">
							~if $photoUploadSupported eq 1 || $photoUploadSupported eq 3`
								<form name = "uploadPhotoForm" method = "post" enctype = "multipart/form-data" action="~sfConfig::get('app_site_url')`/social/mobileUploadAction/photo?page=profile">
									<input type = "file" name = "photoInput[]" id="photoInput" style = "position:absolute; z-index:2; opacity:0; height:41px; width:162px; filter:alpha(opacity=0); cursor:pointer;" size="0" multiple />
								</form>
								<a href="javascript:void(0)" class="btn pre-next-btn upload-photo" style="z-index:1; cursor:pointer;">Upload Photos</a>
							~elseif $photoUploadSupported eq 2`
								<a href="~sfConfig::get('app_site_url')`/social/mobileUploadAction/mail?page=profile" class="btn pre-next-btn upload-photo" style = "cursor:pointer;">Upload Photos</a>
                                                	~/if`
							</div>
							<div class = "mb10 hide" id = "uploadPhotoLoader">
								&nbsp;&nbsp;&nbsp;<img src = "~sfConfig::get('app_img_url')`/images/searchImages/loader_small.gif" />
							</div>
							~if $photoUploadSupported eq 3`
								<div>&nbsp;or mail your photos at:</div>
								<div>
									&nbsp;<a href="mailto:~sfConfig::get('app_photo_email')`?subject=~$PROFILENAME`">~sfConfig::get("app_photo_email")`</a></div>							
							~/if`
							</div>
							<!--<div>
								<a href="javascript:void(0)" onclick="" class="btn pre-next-btn upload-photo">Create Horoscope</a></div>
							</div>-->
							<div class="action-btn-2"></div>
							<div class="clearfix"> </div>
						~/if`
				</li>
			</ul>
		</div>
	</section>
	<!-- Photo Privacy-->
	~if $PERSON_SELF`
	<section class="s-info-bar"  id="photo_privacy" style=" cursor:pointer;font-weight:normal">
		<div class="pgwrapper">
<a href="#pp"></a>
			<a href='#pp' onclick="return false" style="text-decoration:none">Set Photo Privacy</a><br>(Show your photo to people you like)
		</div>
	</section>
	<div id="photo_privacy_options" style=" cursor:pointer;">
		<section class="s-info-bar" id="photo_privacy_options1" >
			<div class="pgwrapper">
				<div class="clearfix">
					<div class="pull-left" style="font-weight:normal" id="photo_privacy_option1_val">
						Visible to All
					</div>
					<div class="pull-right" id="photo_privacy_option1_img">
						<div class="privacy_option_bg_img"></div>
					</div>
				</div>
			</div>
		</section>
		
		<section class="s-info-bar" id="photo_privacy_options2" style="border-bottom:solid 1px #b3b2b2;">
			<div class="pgwrapper">
				<div class="clearfix">
					<div class="pull-left" style="font-weight:normal" id="photo_privacy_option2_val">
						Visible to those you have <br>accepted or expressed interest in
					</div>
					<div class="pull-right" id="photo_privacy_option2_img">
							<div class="privacy_option_bg_img"></div>
					</div>
				</div>
			</div>
		</section>
		<div style="height:20px;"></div>
	</div>

	~/if`
	<!-- About_Me-And-Partner_Tabs -->
	<section class="js-tab">
			<a href="#tag_switch1" id="tag_switch1" class="js-tab-open w50" name="tag_switch1" onclick="javascript:switch_tag(1);">
			<span>About ~if !$PERSON_SELF` ~$PROFILENAME` ~else`me ~/if`</span></a>
		   <a href="#tag_switch2" id="tag_switch2" name="tag_switch2" class="js-tab-close w50" onclick="javascript:switch_tag(2);"><span>Partner Preference</span></a>
	</section>
	
	<!-- Tab_Content -->
		<section class="js-tab-content">
			<div id="abt_me" style="display:block;" >
				<div id="accordion" class="tab-contents">
					<!-- Basic Info -->				~include_partial("profile/mobBasicProfileSection",['HEIGHT'=>$HEIGHT,'MSTATUS'=>$MSTATUS,'incomeSelf'=>$incomeSelf,'MobileAbtArr'=>$MobileAbtArr,'partnerTab'=>"0",'id'=>"basic"])`
					<!-- Basic Info end here -->				
					
					<!-- Religion and Ethnicity start here -->					~include_partial("profile/mobGenericProfileSecton",['NameValueArr'=>$ReligionAndEth,'LabelHeading'=>"Religion and Ethnicity",'id'=>"religion"])`
					<!-- Religion and Ethnicity end here -->
					
					<!-- Education Section starts -->				~include_partial("profile/mobGenericProfileSecton",['NameValueArr'=>$educationAndOccArr,'LabelHeading'=>"Education and Occupation",'id'=>"education"])`
					<!-- Education Section ends -->
					
					<!-- Family Details start here -->				~include_partial("profile/mobGenericProfileSecton",['NameValueArr'=>$familyArr,'LabelHeading'=>"About Family",'id'=>"family"])`
					<!-- Family details end here -->
					
					<!-- LifeStyle Section starts -->				~include_partial("profile/mobGenericProfileSecton",['NameValueArr'=>$lifeAttrArray,'LabelHeading'=>"Lifestyle",'id'=>"lifestyle"])`
					<!-- Lifestyle Section ends -->
					
					<!-- Astro Details -->				~include_partial("profile/mobGenericProfileSecton",['NameValueArr'=>$AstroKundaliArr,'LabelHeading'=>"Astro Details",'id'=>"astro",'HOROSCOPE'=>$HOROSCOPE,HIDE_HORO=>$HIDE_HORO,PROFILECHECKSUM=>$PROFILECHECKSUM])`
					<!-- Astro Details end here -->
					
					
					<!-- Hobbies Section starts -->				~include_partial("profile/mobGenericProfileSecton",['NameValueArr'=>$Hobbies,'LabelHeading'=>"Hobbies and Interests",'id'=>"hobbies"])`
					<!-- Hobbies Section end -->				
					
				</div>
			</div>
			<div id="dpp_sec" style="display:none;">
				<div id="accordion" class="tab-contents">

					<!-- Basic Info--> ~include_partial("profile/mobBasicProfileSection",['loginProfile'=>$profile,'dpartner'=>$profile->getJpartner(),'MobileAbtArr'=>$MobileAbtArr,'partnerTab'=>"1",'id'=>"dppBasic"])`
					<!-- Basic Info end here -->
					
					<!-- Rest DPP Sections-->
					~include_partial("profile/mobGenericProfileSecton",['NameValueArr'=>$dppReligionAndEthArr,'LabelHeading'=>"Religion & Ethnicity",'id'=>"dppReligion"])`
					~include_partial("profile/mobGenericProfileSecton",['NameValueArr'=>$dppEducationAndOccArr,'LabelHeading'=>"Education and Occupation",'id'=>"dppEducation"])`				 
					~include_partial("profile/mobGenericProfileSecton",['NameValueArr'=>$dpplifeAttrArr,'LabelHeading'=>"Lifestyle and Attributes",'id'=>"dppLifeAttr"])`
					
					<!--Rest DPP Sections end here -->				
				</div>
			</div>
</section>
~if !$PERSON_SELF`	
	<!-- Action Button -->
	<section class="action-btn bdr-btm">
		<div class="pgwrapper">
		~if $SHOW_NEXT_PREV`
			~if $SHOW_PREV || $SHOW_NEXT`
				~if $SHOW_PREV`
					~if $fromPage eq contacts`
						<a href="/profile/viewprofile.php?~$prevLink`&responseTracking=~$responseTracking`" class="pull-left btn pre-next-btn">Previous</a>
					~else`
						<a href="/profile/viewprofile.php?show_profile=prev&total_rec=~$total_rec`&actual_offset=~$actual_offset`&j=~$j`&responseTracking=~$responseTracking`&searchid=~$searchid`~$other_params`&~$NAVIGATOR`" class="pull-left btn pre-next-btn">Previous</a>
					~/if`
				~/if`
			~if $SHOW_NEXT`
				~if $fromPage eq contacts`
					<a href="/profile/viewprofile.php?~$nextLink`&responseTracking=~$responseTracking`"  class="pull-right btn pre-next-btn">Next</a>
				~else`
					<a href="/profile/viewprofile.php?show_profile=next&total_rec=~$total_rec`&actual_offset=~$actual_offset`&j=~$j`&responseTracking=~$responseTracking`&searchid=~$searchid`~$other_params`&~$NAVIGATOR`" class="pull-right btn pre-next-btn">Next</a>
				~/if`
			~/if`
			~/if`
		~/if`
		</div>
	</section>
~/if`
	
	<script>
	var photoUploadSupport = "~$photoUploadSupported`";
	~if PERSON_SELF`
		$('#imageClick_PhotoInput').click(function(){
				$('#photoInput').trigger("click");
				$('#photoInput').trigger("change");
			});
    ~/if`
	function switch_tag(opt){
	    var	tag1=document.getElementById("tag_switch1");
		var tag2=document.getElementById("tag_switch2");
		var abt_sec=document.getElementById("abt_me");
		var dpp_sec1=document.getElementById("dpp_sec");
		switch(opt){
			case 1:
				tag1.className="js-tab-open w50";
				tag2.className="js-tab-close w50";
				abt_sec.style.display="block";
				dpp_sec1.style.display="none";
				break;
			case 2:
				tag1.className="js-tab-close w50";
				tag2.className="js-tab-open w50"
				abt_sec.style.display="none";
				dpp_sec1.style.display="block";
				break;
			}
	}
	function plusMinuschange(element)
		{
			elementA=document.getElementById(element+"A");
			elementDiv=document.getElementById(element+"Div");
			if(elementA.className=="icon-minus")
			{
				elementA.className="icon-plus";
				elementDiv.style.display="none";
			}
			else
			if(elementA.className=="icon-plus")
			{
				elementA.className="icon-minus";
				elementDiv.style.display="block";
			}
		}

	var person_self="~$PERSON_SELF`";
		var option ='~$PHOTODISPLAY`';	

	</script>
	~if $pixelcode`
		~$pixelcode|decodevar`
	~/if`
~if $PERSON_SELF`		
<style>
.privacy_option_act{background-color:#5a7698;color:#fff}
.privacy_option_noact{background-color:#FFF;color:#000}
.privacy_option_idle{background-color:grey;}
.privacy_option_bg_img{background:url("~sfConfig::get('app_img_url')`/images/mobilejs/revamp_mob/mainsprite.png") 1px -317px no-repeat;width:44px;height:29px}
</style>
~/if`
