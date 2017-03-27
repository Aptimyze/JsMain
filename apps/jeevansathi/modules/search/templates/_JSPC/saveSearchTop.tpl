~if $loggedIn eq 2`
	<!--start:save search summary-->
	<div class="srppad10 f22 color11">Saved Searches</div>
	<div class="srpbg2 fontlig f14 colr2">
		<div id="savedSearchBottom">
			~foreach from=$savedSearches item=savedDetails key=savedSearchId`
				<div class="srppad11 srpbdr2 srchsum"> ~$savedDetails->dataString` </div>
			~/foreach`
		</div>
	</div>
	<!--end:save search summary-->
~/if`

~if $loggedIn eq 1`
<!--start:div-->
	<div class="mb10 f14 fontlig disp-none" id="saveThisLimitDetails">
		<div class="srpbg2 srppad11 fontlig">
			<div class="txtc colr5">Remove one of these saved searches first.</div>
			<div class="pt15 f14 fontlig" id="seavedSearchesToDelete">
			</div>
		</div>
	</div>
	<!--end:div-->

	<!--start:div-->
	<div class="mb10 f14 fontlig disp-none" id="saveThisSearchDetails">
		<div class="srpbg2 srppad11 fontlig mb10" >
			<div class="srpbdr7" id="saveThisSearchName">
				<input type="text" id="js-saveSearchName" name="saveSearchName" placeholder="Name your search" class="fullwid brdr-0 bgnone pl10 f14 color12 wid96p" style="height:3em;" value="" maxlength="40"/>
			</div>
		</div>
		<button id="saveThisSearchWithName" class="bg_pink cursp fullwid txtc colrw f14 fontlig lh40 brdr-0">Save</button>
	</div>
	<!--end:div-->
	<!--start:div-->
	<div class="mb20 f14 fontlig" id="saveThisSearch">
		<div class="bg5 txtc lh40 colrw cursp"> Save this search </div>
	</div>
        <div id="saveSearchSuccess" class="disp-none mb10 txtc lh40 colrw cursd" style="border: 2px solid rgb(52, 73, 94); color: rgb(52, 73, 94);">Search saved successfully</div>
        <div class="mb10 f14 fontlig disp-none" id="manageSearch">
		<div class="bg5 txtc lh40 colrw cursp"> Manage Searches </div>
	</div>
	<!--end:div-->
~/if`
