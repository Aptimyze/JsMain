<!--start:header-->
~include_partial("inbox/JSPC/inboxHeader")`
<!--end:header--> 
<!--start:middle part-->
<div class="fullwid bg-4" id="ccSection">
	<div class="container mainwid fontlig">  	
		<div class="clearfix fullwid ccbg1">
			<!--start:left-->
			<div class="fl ccwid1" id="vtabs1">
				<!--start:left vertical tabs / 1st level option-->
				<ul class="listnone ccvtab f14 color11">
					~foreach from=$contactCenterTabMapping key=k item=v name=verticalTabLoop`
					<li id="VerticalTab~$k`" data-id="~$k`" class="js-ccVerticalLists cursp"><span>~$v["Vname"]`</span></li>
					~/foreach`
				</ul>        
				<!--end:left vertical tabs/ 1st level option-->
			</div>
			<!--end:left-->
			<!--start:right-->        
			<div class="fr ccwid2">

				<div class="ccbg2 fullwid">
					<!--start:div for second level option-->
					<div class="vone fullwid" id="listingWindow">
						<!--start:horizontal list-->
						~include_partial("inbox/JSPC/inboxListingTabs",[contactCenterTabMapping=>$contactCenterTabMapping])`
						<!--end:horizontal list-->
						<!--end:div for second level option--> 

						<!--start:3rd level option(upload section)-->
						~include_partial("inbox/JSPC/inboxRequestTypeListings",[ccRequestTypeListMapping=>$ccRequestTypeListMapping])`           
						<!--end:3rd level option(upload section)-->

						<!-- loader -->
						<div id="ccResultsLoaderTop" class="ccResultsLoader disp-none" style="padding-top:100px;padding-bottom:100px;text-align:center;">
							<img src="~sfConfig::get('app_img_url')`/images/searchImages/loader_small.gif" style="vertical-align: middle; margin: 0pt 20px 0pt 0pt;">
						</div>
						<!-- loader -->
						
						~include_partial("inbox/JSPC/zeroResults",[noresultmessage=>$noresultmessage,pageHeading=>$pageHeading,total=>$total])`

						<div class="disp-none" id="js-ccContainer">
							<!--start:tuple listing-->
							~include_partial("inboxBasicTuple")`
							<!--end:tuple listing-->
						</div>
					</div>

					<div class="vone fullwid disp-none" id="messageWindow">
						
					</div>
					
				</div>
			</div>
			<!--end:right-->  
		</div>

		<!--start:pagination-->
		<div class="clearfix ccp7">
			~include_partial("inbox/JSPC/pagination",[hidePaginationCount=>$hidePaginationCount])`
			
		</div>
		<!--end:pagination-->
	</div>
</div>
<!--end:middle part--> 
<!--end:div for one-->
<div class="disp-none" id="messageDisplaytuple"><div class="bg-white fullwid ccbrdb1 f13 fontlig color2 ">
		<div class="clearfix ccp6 cursp" id="backToMessage">
			<div class="fl"><i class="sprite2 ccnbck"></i></div>
			<div class="fl pt5 pl10">Back to messages</div>
		</div>
	</div>
	<!--end:horizontal list-->
	<!--end:div for second level option--> 
	<div class="disp-none" id="js-ccContainerMessage">
		<!--start:tuple listing-->
		<div class="js-ccTupleStructure">
			<div id="outerMessageTupleDiv">
				<div class="ccp2 fontlig color11" id="innerMessageTupleDiv">
					<ul class="listnone ccsublist">
						<li class="clearfix pos-rel" id="detailMessageTupleDiv" >
							<a href="/profile/viewprofile.php?profilechecksum={profilechecksum}" onclick="return redirect('/profile/viewprofile.php?checksum={checksum}&profilechecksum={profilechecksum}')" class="js-colorParent">
								<div class="fl">
									<img dsrc='{ccTupleImage}' class="ccdim1 vtop"/>
								</div>
								<div class="fl ccwid5 pos-rel">
									<div class="fl f12 pl30">
										<p class="pt13"><span class="js-username f17">{username}</span> <span class="js-userLoginStatus disp_ib color12 f11 pl10">{userloginstatus}</span></p>
										<div id="messageInnerTupleDetailsContent">
											<p class="pt13">{age},  {height},  {mstatus},  {mtongue}</p>
											<p class="pt10">{edu_level_new},  {occupation},  {income}</p>
										</div>
									</div>
									<div class="fr pos-rel">
										<div class="f10 txtr">
											<p class="pt19 cch1"></p>
											<p class="pt13 color5 f12 h20">{subscription_icon}</p>                           
										</div>    
									</div> 
								</div>
							</a>                        
						</li>
					</ul>
                                        <img id="msgHistoryLoader" src="/images/jspc/commonimg/loader.gif" style="visibility:hidden;margin: 0 auto;height: 22px;display: block;">
					<div id='msgListScroller-{profilechecksum}' class="ccp7 ccbrdb1 cEcontent" style="height:200px">
						<ul class="msglist2 listnone" id="list-{profilechecksum}">
							                 
						</ul>
					</div>
					<div class="ccp8 CEParent" id="WriteArea" style="display:none;">
					<span id="MESSAGE_WRITE_error" class="disp-none mauto f14 mb3 errcolr">Something went wrong, Please try again</span>
						<form>
							<div class="mt10 fullwid bg-white ccbrdb2">
								<div class="ccp3">
									<textarea id="MESSAGE_WRITE-{profilechecksum}-CC-cEMessageText"class="brdr-0 ccout color12 f12 fontlig fullwid ta200" maxlength="3000"></textarea>
								</div>
							</div>
							<div class="pt10">
								<div class="bg10 bg_pink brdr-0 colrw f15 fontlig f15 lh30 pl20 pr20 wid100" id="MESSAGE_WRITE-{profilechecksum}-CC">Send Message</div>
								
							</div>
						</form>
						
					</div>
					<div class="ccp8" id="membershipArea" style="display:none;">
						<form>
							<div class="mt10 fullwid bg-white ccbrdb2">
								<div class="ccp3">
									<textarea disabled class="bgnone brdr-0 ccout color12 f12 fontlig fullwid ta200" maxlength="3000" placeholder="You need to upgrade your membership to write message to this user."></textarea>
								</div>
							</div>
							<div class="pt10">
								<div style="width: 160px;" class="bg_pink brdr-0 colrw f15 fontlig f15 lh30 pl20 pr20 cursp"><a href="/profile/mem_comparison.php" style="text-decoration: none;color: white;">View membership plans</a></div>
								
							</div>
						</form>
						
					</div>
					
				</div>
			</div>
		</div>
	</div>
	<!--end:tuple listing-->
</div>


<div id='callerComment-layer' class="pos_fix layersZ setshare disp-none">
      <div class="prfwid16 fontlig">
        <div class="prfbg6">
                <!--start:div-->          
                <div class="clearfix shrp1">
                    <div class="prfrad prfdim8 prfbr6 fl"> <img src="" border="0" class="otherProfilePic prfdim13 prfrad prfm2"> </div>
                      <div class=" fl ml10 prfbr7 pb10 f13 color11 wid80p pt16">
                       <span class='js-usernameCC'></span><span class='pl10 pr10'>-</span><span class="colr2">Comments</span>
                      </div>
                      <i class="sprite2 sendcross2 cursp pos-abs cepos4 closeCommLayer"></i>
                  </div>

                  
                     <!--start:form-->
                    <div id='commHistory' class="f13 comhp1">
                          <div class="cEcontent mCustomScrollbar">                        
                            <ul id='mainDiv' class="listnone comhis fontlig color11">
                              <li id='commDiv' class="commDiv setl clearfix">
                                    <div class="pl10 prfwid19">
                                      <p class="f15 js-commHeading"></p>
                                        <p class="js-commMessage pt7 color2 f13"></p>
                                        </div>
                                </li>
                                      
                                                         
                            </ul>
                            </div>
                       
                    </div>
                    <!--end:form-->
            <!--end:div-->
               
            </div>    
      </div>  
     </div>



<!--start:footer-->
~include_partial('global/JSPC/_jspcCommonFooter')`
<!--end:footer--> 
<!--End:Inner cc tuple Message Structure-->  
~include_partial('global/JSPC/_jspcContactEngineButtons')`
<script type="text/javascript">
	var lastCCSearchId;
	var activeVerticalTab = "~$activeVerticalTab`";                             //vertical tab ID
	var activeHorizontalTabInfoID = "~$activeHorizontalTab`";                   //horizontal infoID
	var response = ~$firstResponse|decodevar`;                                  //first response
	var ccTabsMappingData = ~$ccTabsMappingData|decodevar`;  //vertical tabs to horizontal tabs list map
	var currentPage;                                                            //current page
	var lastCurrentPage="~$pageNoForFullResponseApis`";                         //last current page
	var CC_RESULTS_PER_PAGE = ~$CC_RESULTS_PER_PAGE`;                           //profile results per page
	var activeRequestTypeID = ~$activeRequestTypeID`;                           //request type ID(0/1)
	var ccRequestTypeListArr = ~$ccRequestTypeListArr|decodevar`;//requestID to horizontal tab infoID map
	var defaultRequestTypeID = ~$defaultRequestTypeID`;                         //default request type ID
	var NAVIGATOR = "~$NAVIGATOR`";
	var clearTimedOutVar;
	var showRequestTypeList = '~$showRequestTypeList`';  //('Y'/'N' : show/hide horoscope list)
	var showIntroCallsList = '~$showIntroCallsList`';	//('Y'/'N' : show/hide intro calls list)
	var vspStype = '~$vspStype`';   //stype for vsp page
</script>
