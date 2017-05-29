~foreach $profileArr as $finalval`
<section class="js-list">
			<div class="pgwrapper">
			~if $finalval["LAST_LOGIN_SHOW"] eq "Y"`
				<div class="login-info"> ~JsCommon::getLastLogin($finalval["LAST_LOGIN_DT"])`</div>
			~/if`
				<ul>
					<li>
						<div class="img-holder">
							~if $finalval["MAIN_PIC"][0]`
								<a href= "~sfConfig::get('app_site_url')`/social/album?checksum=&profilechecksum=~$finalval['profilechecksum']`&seq=1"><img  src="~$finalval["MAIN_PIC"][0]`" style="width:75px !important;height:100px !important;"/></a>
								~if $finalval["MAIN_PIC"][1] || $finalval["MAIN_PIC"][2]`
								<div class="v-albm">
									~if $finalval["MAIN_PIC"][2]`
										<a href= "~sfConfig::get('app_site_url')`/social/album?checksum=&profilechecksum=~$finalval['profilechecksum']`&seq=1">View Album</a>
									~else`
										~if $finalval["MAIN_PIC"][1]`
										<a href= "~sfConfig::get('app_site_url')`/social/album?checksum=&profilechecksum=~$finalval['profilechecksum']`&seq=1">Larger Photo</a>
										~/if`
									~/if`
								</div>
								~/if`
							~/if`							
						</div>
						<div class="u-short-info">
							<div class="u-code">
								<a href="~sfConfig::get('app_site_url')`/~$finalval["PROFILE_URL"]`">~$finalval["USERNAME"]`</a><a href="/profile/bookmark_add.php?type=show&MODE=S&senders_data=~$finalval['profilechecksum']`"><span class="shortlist"><img src="~sfConfig::get("app_img_url")`/images/mobilejs/revamp_mob/slist-arw.jpg" />Shortlist</span></a>
							</div>
							<div class="u-sdesc"  onclick="location.href='~sfConfig::get('app_site_url')`/~$finalval["PROFILE_URL"]`';">
								~$finalval["AGE"]` yrs, ~$finalval["HEIGHT"]`<br />
								~$finalval["RELIGION"]`, ~$finalval["CASTE"]`<br />~$finalval["MTONGUE"]`<br />
								~if $finalval["EDU_LEVEL_NEW"]`
								~$finalval["EDU_LEVEL_NEW"]`, ~/if` ~$finalval["OCCUPATION"]`<br />~$finalval["CITY_RES"]`
							</div>
						</div>
						<div class="clearfix"></div>
						<div class="action-btn-2">
							<a href="/contacts/PreEoi?checksum=&profilechecksum=~$finalval['profilechecksum']`&to_do=eoi" class="btn active-btn">Express Interest</a><a href="/contacts/PreContactDetails?checksum=&profilechecksum=~$finalval['profilechecksum']`&to_do=view_contact" class="btn active-btn">Contact Details</a>
						</div>
					</li>
				</ul>
			</div>
		</section>




~/foreach`
