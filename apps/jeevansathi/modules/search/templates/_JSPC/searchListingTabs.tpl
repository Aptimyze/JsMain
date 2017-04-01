<!-- tabs-->
<div class="mainwid container matcgbg scrollhid pos-rel" id="js-seatchListingTabs">
	<!--start:tab left/right shift-->
	<div class="pos-abs z2  tabpos1 ~if $isRightListing eq 1`disp-none~/if`" id="shifttabright">
		<div class="disp-tbl matcgbg txtc tabbdr1 tabdim1 cursp">
			<div class="disp-cell vmid">
				<i class="sprite2 tabright"></i>
			</div>
		</div>
	</div>
	<div class="pos-abs z2 tabpos2 ~if $isRightListing neq 1`disp-none~/if`" id="shifttableft">
		<div class="disp-tbl matcgbg txtc tabbdr2 tabdim1 cursp">
			<div class="disp-cell vmid">
				<i class="sprite2 tableft"></i>
			</div>
		</div>
	</div>
	<!--end:tab left/right shift-->

        <div class="tabwidt pos-rel" style="height: 49px;">
		<ul class="matchtabs listnone clearfix fontlig f15 color11 pos-rel" style="~if $isRightListing eq 1`left: -~$setGap`px;~else`left: 0px;~/if`">
			<li class="active">
				<div><span data-attr="0" id="js-matchalerts" class="js-searchLists cursp">Daily Recommendations</span></div>
			</li>
			<li>
				<div><span data-attr="1" id="js-searchListsDpp" class="js-searchLists cursp">Desired Partner Matches</span></div>
			</li>
			<li>
				<div><span data-attr="2" id="js-searchListsJJ" class="js-searchLists cursp">Just Joined Matches</span></div>
			</li>
                        <li>
				<div><span data-attr="3" id="js-fsoVerified" class="js-searchLists cursp">Verified Matches</span></div>
			</li>
			<li>
				<div><span data-attr="4" id="js-searchListsMM" class="js-searchLists cursp">Mutual Matches</span></div>
			</li>
			<li>
				<div><span data-attr="5" id="js-searchListsRdpp" class="js-searchLists cursp">Members Looking for Me</span></div>
			</li>
			~if $showKundliList`
			<li>
				<div><span data-attr="2" id="js-searchListsKM" class="js-searchLists cursp">Kundli Matches</span></div>
			</li>
			~/if`
			<li>
				<div><span data-attr="3" id="js-shortlisted" class="js-searchLists cursp">Shortlisted Profiles</span></div>
			</li>
			<li>
				<div><span data-attr="4" id="js-visitors" class="cursp js-searchLists">Profile Visitors</span></div>
			</li>
                        <!--
                        ~if CommonFunction::getMainMembership($subscriptionType) eq mainMem::EVALUE || CommonFunction::getMainMembership($subscriptionType) eq mainMem::EADVANTAGE`
                        ~else`
                        <li>
				<div><span data-attr="3" id="js-viewAttempts" class="js-searchLists cursp">Contact View Attempts</span></div>
			</li>
                        ~/if`
                        -->
		</ul>
            <div id="leftPointUnderline" class="pos-abs" style="left:~$clickOn`px;height: 2px; background: #34495e;width: 200px;"></div>
            
	</div>
</div>
<!-- tabs-->
