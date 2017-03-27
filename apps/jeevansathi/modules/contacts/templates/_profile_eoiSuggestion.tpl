~assign var='cid' value=0`
~foreach from = $finalResultsArray item = detailsArr key = profileid`
	
	~if $detailsArr->getPROFILEID() neq ''`
        ~assign var='cid' value=$cid+1`
        <div>
	<section class="js-list">
		<div class="pgwrapper">
			~if $detailsArr->getLAST_LOGIN_DT() neq ''`
				<div class="login-info" style="float:left">
					~if $detailsArr->getLAST_LOGIN_DT() eq jschat or $detailsArr->getLAST_LOGIN_DT() eq gtalk`
						Online
					~else`
						~$detailsArr->getLAST_LOGIN_DT()`
					~/if`
				</div>
			~/if`
			~if $detailsArr->getSUBSCRIPTION() neq '' && CommonFunction::isPaid($detailsArr->getSUBSCRIPTION())`
				<div style="float:right; font-weight: 700;color:#800000;">
					~if CommonFunction::isEvalueMember($detailsArr->getSUBSCRIPTION())`
						~mainMem::EVALUE_LABEL`
					~else if CommonFunction::isErishtaMember($detailsArr->getSUBSCRIPTION())`
						~mainMem::ERISHTA_LABEL`
					~else if CommonFunction::isJSExclusiveMember($detailsArr->getSUBSCRIPTION())`
						~mainMem::JSEXCLUSIVE_LABEL`
						~else if CommonFunction::isEadvantageMember($detailsArr->getSUBSCRIPTION())`
						~mainMem::EADVANTAGE_LABEL`
					~/if`
				</div>
			~/if`
			<div style="clear:both"></div>
			<ul>
				<li>
				
					<div class="img-holder disableSave">
                                            ~if $detailsArr->getPHOTO_COUNT() neq 0`
                                                    <a href="~JsConstants::$siteUrl`/social/album?profilechecksum=~$detailsArr->getPROFILECHECKSUM()`&nav_type=SR&~$NAVIGATOR`&fmConfirm=1" id="pic_url~$detailsArr->getPHOTO_COUNT()`" >
                                            ~/if`
                                                    <img src="~$detailsArr->getSearchPicUrl()`" height="100" border="0" oncontextmenu="return false;" galleryimg="NO" />
                                            ~if $detailsArr->getPHOTO_COUNT() neq 0`
                                                    </a>
                                            ~/if`       
                                            ~if $detailsArr->getPHOTO_COUNT() gt 1`
							<div class="v-albm">
                                                            <a href="~sfConfig::get('app_site_url')`/social/album?profilechecksum=~$detailsArr->getPROFILECHECKSUM()`&seq=1&~$NAVIGATOR`&nav_type=SR&searchId=~$searchId`&fmConfirm=1">View Album</a>
							</div>
                                            ~elseif $detailsArr->getHAVEPHOTO() eq '' or $detailsArr->getHAVEPHOTO() eq N`
                                                    ~if $detailsArr->getIS_PHOTO_REQUESTED() eq 0`
                                                            <span class="photoRequestSent">
                                                                	Photo Requested
                                                            </span>									
                                                    ~else`
                                                            <a href="~sfConfig::get('app_site_url')`/social/photoRequest?newPR=1&amp;profilechecksum=~$detailsArr->getPROFILECHECKSUM()`&~$NAVIGATOR`&fmConfirm=1" class="requestPhoto">
                                                                	Request photo
                                                            </a>
                                                    ~/if`
                                            ~/if`
                                        </div>
                                        
					<div class="u-short-info">
						<div class="u-code">
							 <a href="~sfConfig::get('app_site_url')`/profile/viewprofile.php?profilechecksum=~$detailsArr->getPROFILECHECKSUM()`&cid=~$cid`&inf_checksum=~$inf_checksum`&stype=~$stype`&searchid=~$searchId`&j=~$currentPage`&total_rec=~$noOfResults`&Sort=~$SORT`&offset=~$finalval`&~$NAVIGATOR`&fmConfirm=1">~$detailsArr->getUSERNAME()`</a>
							
							~if $detailsArr->getIS_BOOKMARKED() neq 1`
                        					<a href="~sfConfig::get('app_site_url')`/profile/bookmark_add.php?type=show&MODE=S&senders_data=~$detailsArr->getPROFILECHECKSUM()`&~$NAVIGATOR`&nav_type=SR&fmConfirm=1" class ="fontwt">
									<span class="shortlist"><img src="~sfConfig::get('app_img_url')`/images/mobilejs/mobileSearch/slist-arw.jpg" />Shortlist</span>
								</a>
                					~else`
                        					<span class="shortlist"><img src="~sfConfig::get('app_img_url')`/images/mobilejs/mobileSearch/slist-arw.jpg" />Shortlisted</span>
                					~/if`
						</div>
						<a href="~sfConfig::get('app_site_url')`/profile/viewprofile.php?profilechecksum=~$detailsArr->getPROFILECHECKSUM()`&cid=~$cid`&inf_checksum=~$inf_checksum`&stype=~$stype`&j=~$currentPage`&total_rec=~$noOfResults`&Sort=~$SORT`&offset=~$finalval`&~$NAVIGATOR`&fmConfirm=1" class="notextdecoration">
						<div class="u-sdesc">
							~$detailsArr->getAGE()` yrs, ~$detailsArr->getHEIGHT()`<br/>
				                        ~$detailsArr->getCASTE()`<br/>
                        				~$detailsArr->getMTONGUE()`<br/>
                        				~$detailsArr->getEDUCATION()`,~$detailsArr->getOCCUPATION()`<br/>
                        				~$detailsArr->getCITY()`, ~$detailsArr->getINCOME()`<br/>
						
						</div>
					    </a>	
					</div>
					<div class="clearfix"></div>
					<div class="action-btn-2">
								<a href="~sfConfig::get('app_site_url')`/contacts/PostEOI?profilechecksum=~$detailsArr->getPROFILECHECKSUM()`&to_do=eoi&~$NAVIGATOR`&nav_type=SR&STYPE=~$stype`" class="btn active-btn">Express Interest</a>
					             <a href="~sfConfig::get('app_site_url')`/contacts/PreContactDetails?profilechecksum=~$detailsArr->getPROFILECHECKSUM()`&to_do=view_contact&~$NAVIGATOR`&nav_type=SR&STYPE=~$stype`"  class="btn active-btn" >Contact Details</a>
        				
					</div>
				</li>
			</ul>
		</div>
	</section>
	</div>
~/if`
~/foreach`
