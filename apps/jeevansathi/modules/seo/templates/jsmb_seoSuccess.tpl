<script type="text/javascript">
var whichTab=0;
</script>
<div class="backbtn fbld">~$BREADCRUMB`
	~if $LOGGEDIN`
	<a href="~$SITE_URL`/P/logout.php?mobile_logout=1" style="font: 12px arial; float: right; font-weight: bold; color: #0046C5; margin-right: 10px;">Logout</a>
	~/if`
</div>
<!-- Sub Title -->
	<section class="s-info-bar">
		<div class="pgwrapper">
			~$levelObj->getH1Tag()`~if $levelObj->getGroomURL()` Matrimony ~/if`
		</div>
	</section>
	
	<section class="js-tab-content">
		<div class="js-content">
			<p>~$levelObj->getContent()|decodevar`</p>
		</div>
	</section>
	
	<!-- About_Me-And-Partner_Tabs -->
	<section class="js-tab">
		<a id ="bride"  class="js-tab-open w50"  ~if $levelObj->getPageSource() eq 'N'` onClick="changeTab(this.id)" ~/if`><span>Brides</span> </a>
    	<a id = "groom" value="Groom" class="js-tab-close w50" ~if $levelObj->getPageSource() eq 'N'` onClick="changeTab(this.id)" ~/if`><span>Grooms</span></a>
	</section>
	
	<!-- Search List -->
	<div>
		<div id = "leftProfiles">
		~include_partial('jsmb_seo_profiles_list',[profileArr=>$leftArr])`
		</div>
		<div id = "rightProfiles" style="display:none;">
		~include_partial('jsmb_seo_profiles_list',[profileArr=>$rightArr])`
		</div>
	</div>
	~include_partial('jsmb_tabbing',[SEO_FOOTER=>$SEO_FOOTER,type=>$levelObj->getParentType(),value=>$level1,page=>$levelObj->getPageSource()])`
	
	
