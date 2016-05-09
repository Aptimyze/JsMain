<div class="lf grey-cluster-area">
	<div class="refine-title">
		<div style="padding-top: 8px;" class="fs18">
			Refine Search Results
		</div>
	</div>
	~assign var="tab" value=0`
	<div id="contentarea" class="green_border">
        	~foreach from=$searchClustersArray key=key item=item`
		~assign var="tab1" value=0`
		~assign var="moreLink" value=0`
		<div class="lf width100" id='~$key`div' style="clear:both" >
			~assign var="tab" value=$tab+1`
			<div class="lf b grey-cluster-subtitle loaderDiv" id="~$key`load">
				<span class="fl">~$clusterLabelMappingArray[$key]`</span>
				<span id="~$key`loader">&nbsp;</span>
			</div>
			
			<div id="~$key`loader_collapse" class = "w195" ~if !$openClusters[$key]` style="display:none;"~else`style="display:block;" ~/if`>
			<div class="sp15">&nbsp;</div>
			<div class="lf  fs12 lh15" style="padding-left:10px;">
				~if $clusterLabelMappingArray[$key] eq 'Height'`
				~foreach from=$item key=key1 item=item1`
                                        ~if $key1 eq 0`
						~if $item1`
							~if $item1 gt 26`
                                                		~assign var="lheight" value=26`
							~else`
                                                		~assign var="lheight" value=$item1`
							~/if`
						~else`
                                                	~assign var="lheight" value=1`
						~/if`
                                        ~elseif $key1 eq 1`
						~if $item1`
							~if $item1 gt 26`
                                                		~assign var="hheight" value=26`
							~else`
                                                		~assign var="hheight" value=$item1`
							~/if`
						~else`
                                                	~assign var="hheight" value=26`
						~/if`
                                        ~/if`
                                ~/foreach`
				<div class="layout">
					<div class="layout-slider">
					      <input id="SliderHeight" type="slider" name="HEIGHT" value="~$lheight`;~$hheight`" style="display:none;" />
					</div>
				</div>
				<div class = "overlap-slider" id = "height_overlap_slider"></div>
				~elseif $clusterLabelMappingArray[$key] eq 'Age'`
				~foreach from=$item key=key1 item=item1`
					~if $key1 eq 0`
						~if $item1`
							~if $item1 gt 56`
                                                		~assign var="lage" value=56`
							~else`
                                                		~assign var="lage" value=$item1`
							~/if`
						~else`
                                                	~assign var="lage" value=18`
						~/if`
					~elseif $key1 eq 1`
						~if $item1`
							~if $item1 gt 56`
								~assign var="hage" value=56`
                                                        ~else`
                                                		~assign var="hage" value=$item1`
							~/if`
						~else`
                                                	~assign var="hage" value=56`
						~/if`
					~/if`
				~/foreach`
				<div class="layout">
                                        <div class="layout-slider">
                                              <input id="SliderAge" type="slider" name="AGE" value="~$lage`;~$hage`" style="display:none;" />
                                        </div>
                                </div>
				<div class = "overlap-slider" id = "age_overlap_slider"></div>
				~elseif $clusterLabelMappingArray[$key] eq 'Income'`
				~foreach from=$item key=key1 item=item1`
                                        ~if $key1 eq '0'`
						~if $item1`
                                                	~assign var="lincome" value=$item1`
						~else`
                                                	~assign var="lincome" value=1`
						~/if`
                                        ~elseif $key1 eq '1'`
						~if $item1`
                                                	~assign var="hincome" value=$item1`
						~else`
                                                	~assign var="hincome" value=16`
						~/if`
                                        ~elseif $key1 eq '2'`
						~if $item1`
                                                	~assign var="lincome_dol" value=$item1`
						~else`
                                                	~assign var="lincome_dol" value=1`
						~/if`
                                        ~elseif $key1 eq '3'`
						~if $item1`
                                                	~assign var="hincome_dol" value=$item1`
						~else`
                                                	~assign var="hincome_dol" value=9`
						~/if`
                                        ~elseif $key1 eq 'Rcheckbox'`
                                                ~assign var="r_checkbox" value=$item1`
                                        ~elseif $key1 eq "Dcheckbox"`
                                                ~assign var="d_checkbox" value=$item1`
                                        ~/if`
                                ~/foreach`
				<div class="b">
					Rupee
				</div>
				<div class="layout" id = "income_rupee_enable">
                                        <div class="layout-slider">
                                              <input id="SliderRupee" type="slider" name="INCOME" value="~$lincome`;~$hincome`" style="display:none;" />
                                        </div>
                                </div>
				<div class = "overlap-slider" id = "rupee_overlap_slider"></div>
				<div class="sp12">&nbsp;</div>
				<div class="b">
					Dollar
				</div>
				<div class="layout" id = "income_dollar_enable">
                                        <div class="layout-slider">
                                              <input id="SliderDollar" type="slider" name="INCOME_DOL" value="~$lincome_dol`;~$hincome_dol`" style="display:none;" />
                                        </div>
                                </div>
				<div class = "overlap-slider" id = "dollar_overlap_slider"></div>
				~else`
					~foreach from=$item key=key1 item=item1`
					~if $key1 eq 'More'`
						~assign var="moreLink" value=1`
					~elseif $key1 eq 's_val'`
						<input type="hidden" id="~$key`_s_val" value='~$item1`'>
					~else`
						<input type="checkbox" name="~$key`[]" ~if $item1[0] eq 'Show'`id="~$key`~$tab1`" ~/if` value="~$item1[1]`" ~if $item1[2]` checked ~/if` class="chbx checkbox-selector1">
						<a href="#" class="checkbox-selector" style="color:#5B5B5B;">~$key1`</a> <!-- lavesh color -->
						~assign var="tab1" value=$tab1+1`
						~if $item1[0] neq 'Show'`
							<span class="gray t11">(~$item1[0]`)</span>
						~/if`
						<br>
					~/if`
				~/foreach`
				~/if`
			</div>
			~if $moreLink`
			<div class="sp12">&nbsp;</div>
			<div style="padding-left:10px">
				~if $key eq 'EDUCATION_GROUPING'`
					~assign var="moreLabel" value='EDU_LEVEL_NEW'`
				~elseif $key eq 'OCCUPATION_GROUPING'`
					~assign var="moreLabel" value='OCCUPATION'`
				~else`
					~assign var="moreLabel" value=$key`
				~/if`
				<a id="~$key`More"  href="~sfConfig::get('app_site_url')`/search/perform?width=775&searchId=~$searchId`&moreLinkCluster=~$moreLabel`&originalCluster=~$key`&searchBasedParam=~$searchBasedParam`&reverseDpp=~$reverseDpp`" class="thickbox blink fs12 b" style="color:#117DAA;">Show More...</a><br>
			</div>
			~/if`
			<div class="sp12">&nbsp;</div>
			</div>
		</div>
		~/foreach`
		<div class="clear"></div>
	</div>
</div>

<!-- cluster Form for submissionm-->
<form id="clusterForm" action="~sfConfig::get('app_site_url')`/search/perform" method="POST">
	<input type="hidden" name="searchId" value="~$searchId`">
	<input type="hidden" name="reverseDpp" value="~$reverseDpp`">
	<input type="hidden" name="searchBasedParam" value="~$searchBasedParam`">
	<input type="hidden" name="NEWSEARCH_CLUSTERING" id="NEWSEARCH_CLUSTERING" value="">
	<input type="hidden" name="selectedClusterArr" id="selectedClusterArr" value="">
	<input type="hidden" name="forCityCluster" id="forCityCluster" value="">
	<input type="hidden" name="addRemoveCluster" id="addRemoveCluster" value="">
</form>

<script>
var searchId = '~$searchId`';
var jsonClustersToShow = '~$jsonClustersToShow`';
var income_arr_rupee_mapping_html = new Array();
var income_arr_dollar_mapping_html = new Array();
var income_arr_rupee_html = new Array();
var income_arr_dollar_html = new Array();
~foreach from = $income_arr_rupee_mapping_html item = value key = index`
	income_arr_rupee_mapping_html[~$index`] = ~$value`;
~/foreach`
~foreach from = $income_arr_dollar_mapping_html item = value key = index`
	income_arr_dollar_mapping_html[~$index`] = ~$value`;
~/foreach`
~foreach from = $income_arr_rupee_html item = value key = index`
	income_arr_rupee_html[~$index`] = '~$value`';
~/foreach`
~foreach from = $income_arr_dollar_html item = value key = index`
	income_arr_dollar_html[~$index`] = '~$value`';
~/foreach`
</script>
