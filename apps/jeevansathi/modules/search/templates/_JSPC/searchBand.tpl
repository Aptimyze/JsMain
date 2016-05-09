~if $pageSource && $pageSource=="homePageJspc"`
<div id="qsbModifyBar">
		<div id="searchModify" class="fullwid hpwhite clearfix cursp">
                        <!--start:left-->
				<div class="fl">
						<div class="fontlig f20 colr4 opa70 hpp2">Search for Bride / Groom</div>
				</div>
				<!--end:left-->
				<!--start:right-->
				<div class="fr wid124 bg_pink txtc hpp1"> <i class="sprite2 hpic1 cursp"></i> </div>
				<!--end:right-->
		</div>
	
</div>
~else if $pageSource && $pageSource=="noProfile"`
~else`
<div id="qsbModifyBar" class="disp-tbl"> 
	<!--start:left-->
	
	<div id="qsbSummary" class="disp-cell pramsel color_white fontlig f14 srpwid4 vmid">
		<div class="textTru srpwid4 pr12 clearfix pl12">
			~$searchSummaryFormatted`
		</div>
	</div>
	<!--end:left--> 
	<!--start:right-->
	<div id="searchModify" style="position:relative; overflow:hidden;" class="disp-cell txtc wid177 bg_pink vmid lh61">
		<div  class="colrw fontlig f22 cursp hoverPink pinkRipple">Modify</div>
	</div>
	<!--end:right--> 
	
</div>
<i id="qsb-close" class="sprite2 layersZ close pos_fix closepos cursp disp-none"></i>
~/if`

~if $stype && $stype==SearchTypesEnums::Advance`
~else`

<div id="qsb" class="fullwid srewhite clearfix f16 fontlig colr4 pos-rel z4 disp-none">
	
	<input id="sf_sid" type="hidden" value=~$sid`>
	<div id="sf_field_structure" class="disp-none">
			<li id="sf_{field}_{value}" data="{data}" class='{myClass}' group='{group}' {extraattribute}="{extraattributevalue}"><div>{label}</div></li>
	</div>
	<div class="srchexp">
		<div class="srewid fl brdrb-9 srep1" >	
			<div class="srep2 brdrr-3 pos-rel cursp js-exp singleDD"  id="search_gender" data='sf_gender_~$populateDefaultValues["gender"]`' hasDependant ="1">
				<div class="dWrap">
					<input id="search_gender_l" autocomplete='off' readonly ~if $loggedIn` disabled ~/if` class=" cursp sdTxt f16 fontlig brdr-0 bgnone color11 inpw" value=~$populateDefaultValues['gender_label']` placeholder="Bride">
					
					<i class="pos-abs sprite2 sreic1 srepos1 smArw ~if $loggedIn` disp-none ~/if`"></i> 
				</div>
				<!--start:drop down-->
				<ul>
						~foreach from =$staticSearchData["gender"] item=value key=kk`
							~include_partial("search/JSPC/searchDropDownList",["value"=>$value,"field"=>"gender"])`
						~/foreach`
				</ul>
				
				<!--end:drop down--> 
			</div>
		</div>
		<div class="srewid fl brdrb-9 srep1">
			<div class="srep2 brdrr-3 pos-rel cursp js-exp singleDD"  ~if $populateDefaultValues["religion"]` data='sf_religion_~$populateDefaultValues["religion"]`'~else` data="" ~/if` id="search_religion" hasDependant ="1">
				<div class="dWrap">
					<input id="search_religion_l" autocomplete='off' readonly class="cursp sdTxt f16 fontlig brdr-0 bgnone color11 inpw" value='~$populateDefaultValues["religion_label"]`' placeholder="Select Religion">
					<i class="pos-abs sprite2 sreic1 srepos1 smArw"></i>
					
				</div>
				
					<ul>
						~foreach from =$staticSearchData["religion"] item=value key=kk`
								~include_partial("search/JSPC/searchDropDownList",["value"=>$value,"field"=>"religion"])`
							~/foreach`
						</ul>
					
					
			</div>
		</div>
		<div class="srewid fl brdrb-9 srep1" >
					
			<div class="srep2 pos-rel cursp js-exp singleDD" ~if $populateDefaultValues["caste"]` data='sf_caste_~$populateDefaultValues["caste"]`'~else` data="" ~/if` id="search_caste">
				<div class="dWrap">
					<input id="search_caste_l" autocomplete='off' class="cursp sdTxt f16 fontlig brdr-0 bgnone color11 inpw" type="text" placeholder="Select Caste" value='~$populateDefaultValues["caste_label"]`'>
					<i class="pos-abs sprite2 sreic1 srepos1 smArw"></i>
				</div>
				
				<ul>
					~foreach from=$staticSearchData["caste"] item=v key=religion`
							~if !$populateDefaultValues["religion"] || $populateDefaultValues["religion"] eq $religion`
								~foreach from =$v item=value key=kk`
									 ~include_partial("search/JSPC/searchDropDownList",["field"=>"caste","value"=>$value,"extraAttribute"=>"inReligion","extraAttributeValue"=>$religion])`  
	      				~/foreach`
							~/if`
						~/foreach`
					</ul>
			
			</div>
		</div>
		<div class="srewid fl brdrb-9 srep1">
			<div class="srep2 brdrr-3 pos-rel cursp js-exp singleDD"  ~if $populateDefaultValues["mtongue"]` data='sf_mtongue_~$populateDefaultValues["mtongue"]`'~else` data="" ~/if` id="search_mtongue">
				<div class="dWrap">
					<input id="search_mtongue_l" autocomplete='off' class="cursp sdTxt f16 fontlig brdr-0 bgnone color11 inpw" value='~$populateDefaultValues["mtongue_label"]`' placeholder="Select Mother Tongue">
					<i class="pos-abs sprite2 sreic1 srepos1 smArw"></i>
				</div>
				<ul>
						~foreach from =$staticSearchData["mtongue"] item=value key=kk`
							~if $value["IS_GROUP_HEADING"] neq 'Y'`
								~include_partial("search/JSPC/searchDropDownList",["field"=>"mtongue","value"=>$value])`  
      				~/if`
						~/foreach`
					</ul>
				
				
			</div>
			
		</div>
		<div class="srewid fl brdrb-9 srep1">
			<div class="srep3 brdrr-3">
				<div class="fullwid clearfix">
					<div class="fl pos-rel cursp js-exp srewida singleDD" data='sf_lage_~$populateDefaultValues["lage"]`' id="search_lage" hasDependant ="1">
						<div class="dWrap">
							<input id="search_lage_l" autocomplete='off' readonly class="cursp sdTxt f16 fontlig brdr-0 bgnone color11" value='~$populateDefaultValues["lage_label"]` yrs'/>
							<i class="pos-abs sprite2 sreic1 srepos3 smArw"></i> 
						</div>
						<ul>
									~foreach from =$staticSearchData["age"] item=value key=kk`
										~if $populateDefaultValues["gender"] neq 'M' || $value["VALUE"] gt 20`
											~include_partial("search/JSPC/searchDropDownList",["field"=>"lage","value"=>$value])` 
										~/if` 
			      			~/foreach`
								</ul>
						
					</div>
					<div class="fl txtc" style="width:32px">to</div>
					<div class="fr pos-rel cursp js-exp srewida singleDD"  data='sf_hage_~$populateDefaultValues["hage"]`' id="search_hage" hasDependant ="1">
						<div class="dWrap">
							<input id="search_hage_l" autocomplete='off' readonly class="cursp sdTxt f16 fontlig brdr-0 bgnone color11" value='~$populateDefaultValues["hage_label"]` yrs'/>
							<i class="pos-abs sprite2 sreic1 srepos3 smArw"></i>
						</div>
						<ul>
									~foreach from =$staticSearchData["age"] item=value key=kk`
										~if $value["VALUE"] gte $populateDefaultValues["lage"]`
											~include_partial("search/JSPC/searchDropDownList",["field"=>"hage","value"=>$value])`  
										~/if`
			      			~/foreach`
						</ul>
					
					</div>
				</div>
			</div>
		</div>
		<div class="srewid fl brdrb-9 srep1">
			<div class="srep2 pos-rel cursp js-exp singleDD" ~if $populateDefaultValues["location"]` data='sf_location_~$populateDefaultValues["location"]`'~else` data="" ~/if` id="search_location">
				<div class="dWrap">
					<input id="search_location_l" autocomplete='off' class="cursp sdTxt f16 fontlig brdr-0 bgnone color11 inpw" value='~$populateDefaultValues["location_label"]`' placeholder="Select City/ State/ Country">
					<i class="pos-abs sprite2 sreic1 srepos1 smArw"></i> 
				</div>
				<ul> 
						~foreach from =$staticSearchData["location"] item=value key=kk`
							~if $value["ISGROUP"] neq 'Y'`
									~include_partial("search/JSPC/searchDropDownList",["field"=>"location","value"=>$value])`  
							~/if`
      			~/foreach`
					</ul>
			
			</div> 
				
		</div>
		<div class="srewid fl srep1">
			<div class="srep2 brdrr-3 pos-rel cursp js-exp singleDD" ~if $populateDefaultValues["mstatus"]` data='sf_mstatus_~$populateDefaultValues["mstatus"]`'~else` data="" ~/if` id="search_mstatus">
				<div class="dWrap">
					<input  id="search_mstatus_l"  autocomplete='off' readonly class="cursp sdTxt f16 fontlig brdr-0 bgnone color11 inpw" value='~$populateDefaultValues["mstatus_label"]`' placeholder="Select Marital Status">
					<i class="pos-abs sprite2 sreic1 srepos1 smArw"></i> 
				</div>
				<ul> 
						~foreach from =$staticSearchData["mstatus"] item=value key=kk`
									~include_partial("search/JSPC/searchDropDownList",["field"=>"mstatus","value"=>$value])`  
      			~/foreach`
					</ul>
			</div>
				
				
		</div>
		<div class="srewid fl srep1">
			<div class="srep2" ~if $populateDefaultValues["havephoto"]` data='sf_havephoto_~$populateDefaultValues["havephoto"]`'~else` data="" ~/if` id="search_Photos">
				<div class="fullwid clearfix">
					<p class="fl">With photos only</p>
					<div class="fr pos-rel"><input type="checkbox" id="search_havePhoto" ~if $populateDefaultValues["havephoto"] eq "Y"`checked ~/if`> </div>
				</div>
			</div>
		</div>
		<div class="srewid fl">
			<button id="search_submit" class="fontlig f26 colrw bg_pink brdr-0 fullwid txtc lh63 cursp">Search</button>
			 <div id="search_form"></div>
		</div>
	</div>
</div>
       
~/if`
<script type="text/javascript">
var isHomepage=0;
~if isset($pageSource) && $pageSource eq 'homePageJspc'`
		isHomepage=1;
~/if`
</script>        
        
      
