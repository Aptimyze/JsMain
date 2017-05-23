~if $GR_PAGE eq 1 and $GR_LOGGEDIN eq 0 and $GR_ISEARCH eq 0`
<!-- Google Remarketing Starts -->
<script>
/* <![CDATA[ */
var google_conversion_id = 1056682264;
var google_conversion_label = "j5CPCPy1_gIQmOLu9wM";
var google_custom_params = {
CurrentDate : '~$GR_DATE`',
              PageType : 'SearchResults',
              Gender : '~$GR_GENDER`',
              Manglik : '~$GR_MANGLIK`',
              MaritalStatus : '~$GR_MSTATUS`',
              Religion : '~$GR_RELIGION`',
              Residence : '~$GR_RESIDENCE`',
              Edu_Occ : '~$GR_EDU_OCC`',
              MotherTongue : '~$GR_MTONGUE`',
              Caste : '~$GR_CASTE`'
};
var google_remarketing_only = true;
/* ]]> */
</script>
<script type="text/javascript" src="https://www.googleadservices.com/pagead/conversion.js">
</script>
<noscript>
<div style="display:inline;">
	<img height="1" width="1" style="border-style:none;" alt="" src="https://googleads.g.doubleclick.net/pagead/viewthroughconversion/1056682264/?value=0&amp;label=j5CPCPy1_gIQmOLu9wM&amp;guid=ON&amp;script=0"/>
</div>
</noscript>
<!-- Google Remarketing Ends -->
~/if`
<!-- Sub Title -->
~if $noOfResults eq 0 && $SIM_noOfResults<1`
<section class="s-info-bar">
        <div class="pgwrapper">
                No results found &nbsp;
		~if $searchId`
			<a href="~sfConfig::get('app_site_url')`/search/topSearchBand?isMobile=Y&searchId=~$searchId`" class="pull-right btn pre-next-btn" >Go back</a>
		~else`
			<a href="~sfConfig::get('app_site_url')`/search/topSearchBand?isMobile=Y" class="pull-right btn pre-next-btn" >Go back</a>
		~/if`
        </div>
</section>
<section>
    <div class="pgwrapper">
          <div class="padnnew1">
                <div class="pull-left">
	     		 <div class="crossicon"></div>
                </div>
                <div class="pull-left notftext">
	                       ~if $searchBasedParam eq 'matchalerts' ||  $searchBasedParam eq 'kundlialerts'`
        	                        While we get some recommendations for you, browse your <a href="~sfConfig::get(app_site_url)`/search/partnermatches'" style="color:#0046C5;">Desired Partner Matches</a>
                	        ~else`
                                No Results found for your search, Relax some criteria and search again
				~/if`
                </div>
                <div class="clearfix"></div>
          </div>
    </div>
</section>

~else`
<section class="s-info-bar">
	<div class="pgwrapper">
		~if $partnermatchesPage eq 1`
			Desired Partner Matches (~$formatNumber_format`)
        	~elseif $searchBasedParam eq 'matchalerts'`
			Daily Recommendations (~$formatNumber_format`)
	        ~elseif $searchBasedParam eq 'kundlialerts'`
			Kundli Matches (~$formatNumber_format`)
		~else`
			Search Result
		~/if` &nbsp;
		<span class="s-info">
			~if $noOfResults neq 0`
                       		[Page ~$currentPage`&nbsp;of&nbsp; ~$maxPages`]
       			~/if`
		</span>
	</div>
</section>
<!-- Action Button -->
<section class="action-btn bdr-btm">
	~if $noOfResults neq 0`
		<div class="pgwrapper">
			~if $currentPage neq 1`
               			~assign var=previous value=$currentPage-1`
               			<a href="~sfConfig::get('app_site_url')`/search/perform?searchId=~$searchId`&currentPage=~$previous`~$reverseDppVars`&searchBasedParam=~$searchBasedParam`" class="pull-left btn pre-next-btn">Previous </a>
       			~/if`
	       		~if $currentPage neq $noOfPages`
               			~assign var='nextPage' value=$currentPage+1`
               			<a href="~sfConfig::get('app_site_url')`/search/perform?searchId=~$searchId`&currentPage=~$nextPage`~$reverseDppVars`&searchBasedParam=~$searchBasedParam`" class="pull-right btn pre-next-btn">Next </a>
       			~/if`
		 </div>
	~/if`
</section>
~/if`

~foreach from = $finalResultsArray item = detailsArr key = profileid`
	~if $detailsArr['PROFILEID'] neq ''`
        ~assign var='resultNumber' value=$resultNumber+1`
        ~assign var="cur_pid" value=$detailsArr['PROFILEID']`


	<div>
	<section class="js-list">
		<div class="pgwrapper">
			~if $detailsArr['userLoginStatus'] neq ''`
				<div class="login-info" style="float:left">
					~if $detailsArr['userLoginStatus'] eq jschat or $detailsArr['userLoginStatus'] eq gtalk`
						Online
					~else`
						~$detailsArr['userLoginStatus']`
					~/if`
				</div>
			~/if`
			~if $detailsArr['SUBSCRIPTION'] neq '' && CommonFunction::isPaid($detailsArr['SUBSCRIPTION'])`
				<div style="float:right; font-weight: 700;color:#800000;">
					~if CommonFunction::isEvalueMember($detailsArr['SUBSCRIPTION'])`
						~mainMem::EVALUE_LABEL`
					~else if CommonFunction::isErishtaMember($detailsArr['SUBSCRIPTION'])`
						~mainMem::ERISHTA_LABEL`
					~else if CommonFunction::isJSExclusiveMember($detailsArr['SUBSCRIPTION'])`
						~mainMem::JSEXCLUSIVE_LABEL`
					~else if CommonFunction::isEadvantageMember($detailsArr['SUBSCRIPTION'])`
						~mainMem::EADVANTAGE_LABEL`
					~/if`
				</div>
			~/if`
			<div style="clear:both"></div>
			<ul>
				<li>
					<div class="img-holder disableSave">
						 ~if $detailsArr['ISALBUM'] eq Y or $detailsArr['ISALBUM'] eq N`
                					<a href="~sfConfig::get('app_site_url')`/social/album?profilechecksum=~$detailsArr['PROFILECHECKSUM']`&nav_type=SR&~$NAVIGATOR`&searchId=~$searchId`" id="pic_url~$pic_cnt`" >
                                					<img src="~$detailsArr['PHOTO']`" width="75" height="100" border="0" oncontextmenu="return false;" galleryimg="NO" >
                					</a>
                				~else if $detailsArr['ISALBUM'] eq L`
                					<a href="~sfConfig::get('app_site_url')`/jsmb/login_home.php" >
	                				                <img src="~$detailsArr['PHOTO']`" width="75" height="100" border="0" oncontextmenu="return false;" galleryimg="NO" >
                					</a>
                				~else if $detailsArr['ISALBUM'] eq F`
	                				        <img src="~$detailsArr['PHOTO']`" width="75" height="100" border="0" oncontextmenu="return false;" galleryimg="NO" >
                				~else if $detailsArr['ISALBUM'] eq 0`
	                				        <img src="~$detailsArr['PHOTO']`" width="75" height="100" border="0" oncontextmenu="return false;" galleryimg="NO" >
									~if $detailsArr['PHOTO_REQUESTED'] eq Y`
											<span class="photoRequestSent">
												Photo Requested
											</span>									
									~else`
											<a href="~sfConfig::get('app_site_url')`/social/photoRequest?newPR=1&amp;profilechecksum=~$detailsArr['PROFILECHECKSUM']`&~$NAVIGATOR`&searchId=~$searchId`" class="requestPhoto">
												Request photo
											</a>
									~/if`
                				~/if`
								
                				~if $detailsArr['ISALBUM'] eq Y`
							<div class="v-albm">
                        					<a href="~sfConfig::get('app_site_url')`/social/album?profilechecksum=~$detailsArr['PROFILECHECKSUM']`&seq=1&~$NAVIGATOR`&nav_type=SR&searchId=~$searchId`">View Album</a>
							</div>
                				~/if`
					</div>
					<div class="u-short-info">
						<div class="u-code">
							 <a href="~sfConfig::get('app_site_url')`/profile/viewprofile.php?profilechecksum=~$detailsArr['PROFILECHECKSUM']`&cid=~$cid`&inf_checksum=~$inf_checksum`&stype=~$stype`&searchid=~$searchId`&j=~$currentPage`&total_rec=~$noOfResults`&Sort=~$SORT`&offset=~$finalval`&~$NAVIGATOR`">~$detailsArr['USERNAME']`</a>
							~if $detailsArr['paid_joined_viewed_icon'] eq entry`
								<img src="~sfConfig::get('app_img_url')`/images/mobilejs/mobileSearch/new-tag.png" alt="" align="absmiddle" />
							~/if`
							~if $detailsArr['BOOKMARKED'] eq N`
                        					<a href="~sfConfig::get('app_site_url')`/profile/bookmark_add.php?type=show&MODE=S&senders_data=~$detailsArr['PROFILECHECKSUM']`&~$NAVIGATOR`&nav_type=SR" class ="fontwt">
									<span class="shortlist"><img src="~sfConfig::get('app_img_url')`/images/mobilejs/mobileSearch/slist-arw.jpg" />Shortlist</span>
								</a>
                					~else`
                        					<span class="shortlist"><img src="~sfConfig::get('app_img_url')`/images/mobilejs/mobileSearch/slist-arw.jpg" />Shortlisted</span>
                					~/if`
						</div>
						<a href="~sfConfig::get('app_site_url')`/profile/viewprofile.php?profilechecksum=~$detailsArr['PROFILECHECKSUM']`&cid=~$cid`&inf_checksum=~$inf_checksum`&stype=~$stype`&searchid=~$searchId`&j=~$currentPage`&total_rec=~$noOfResults`&Sort=~$SORT`&offset=~$finalval`&~$NAVIGATOR`" class="notextdecoration">
						<div class="u-sdesc">
							~$detailsArr['AGE']`,~$detailsArr['DECORATED_HEIGHT']`<br/>
				                        ~$detailsArr['DECORATED_RELIGION']`,~$detailsArr['DECORATED_CASTE']`<br/>
                        				~$detailsArr['DECORATED_MTONGUE']`<br/>
                        				~$detailsArr['DECORATED_EDU_LEVEL_NEW']`,~$detailsArr['DECORATED_OCCUPATION']`<br/>
                        				~$detailsArr['DECORATED_CITY_RES']`, ~$detailsArr['DECORATED_INCOME']`<br/>
						
						</div>
					    </a>	
					</div>
					<div class="clearfix"></div>
					<div class="action-btn-2">
						~if $detailsArr['CONTACT_SENT'] eq Y`
					                <a class="btn greynew-btn">Interest Sent </a>
					                <a href="/contacts/PreContactDetails?profilechecksum=~$detailsArr['PROFILECHECKSUM']`&to_do=view_contact&~$NAVIGATOR`&nav_type=SR&STYPE=~$stype`"  class="btn active-btn" >Contact Details</a>
        					~else if $detailsArr['CONTACT_REQUEST'] eq Y`
                					<div class="footer-top2" style="margin-left:0px;">
                					</div>
                					<div>
                        					&nbsp;&nbsp;This user has expressed interest in you. <br>
                        					<a href="/contacts/PreAccept?profilechecksum=~$detailsArr['PROFILECHECKSUM']`&to_do=accept&~$NAVIGATOR`&nav_type=EOI&index=0" class="btn active-btn">Accept</a>
				                        	<a href="/contacts/PreNotinterest?profilechecksum=~$detailsArr['PROFILECHECKSUM']`&to_do=decline&~$NAVIGATOR`&nav_type=EOI&index=0" class="btn active-btn">Not Interested</a>
                					</div>
        					~else if $detailsArr['CONTACT_REPLIED'] neq Y`
                					<a href="/contacts/PostEOI?profilechecksum=~$detailsArr['PROFILECHECKSUM']`&to_do=eoi&~$NAVIGATOR`&nav_type=SR&STYPE=~$stype`" class="btn active-btn">Express Interest</a>
					                <a href="/contacts/PreContactDetails?profilechecksum=~$detailsArr['PROFILECHECKSUM']`&to_do=view_contact&~$NAVIGATOR`&nav_type=SR&STYPE=~$stype`"  class="btn active-btn" >Contact Details</a>
        					~/if`
					</div>
				</li>
			</ul>
		</div>
	</section>
	</div>
~/if`
~assign var="tab" value=$tab+1`
~/foreach`

<!-- Action Button -->
<section class="action-btn">
        ~if $noOfResults neq 0`
                <div class="pgwrapper">
                        ~if $currentPage neq 1`
                                ~assign var=previous value=$currentPage-1`
                                <a href="~sfConfig::get('app_site_url')`/search/perform?searchId=~$searchId`&currentPage=~$previous`~$reverseDppVars`&searchBasedParam=~$searchBasedParam`" class="pull-left btn pre-next-btn">Previous </a>
                        ~/if`
                        ~if $currentPage neq $noOfPages`
                                ~assign var='nextPage' value=$currentPage+1`
                                <a href="~sfConfig::get('app_site_url')`/search/perform?searchId=~$searchId`&currentPage=~$nextPage`~$reverseDppVars`&searchBasedParam=~$searchBasedParam`" class="pull-right btn pre-next-btn">Next </a>
                        ~/if`
                 </div>
        ~/if`
</section>
~if $pixelcode`
~$pixelcode|decodevar`
~/if`
