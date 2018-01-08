~assign var=brideGroom value=CommonUtility::getSplitName($levelObj->getH1Tag())`
<div class="fullwid bg10 pad18">
    <div class="posrel txtc">
        <h1>
        <div class="color5 fontthin f19">~$levelObj->getH1Tag()`<span id="brideGroom"> Matrimonial</span></div>
        <a href="/browse-matrimony-profiles-by-community-jeevansathi"></a>
        </h1>
    </div>
</div>
<div id="listView">
    <div id="leftProfiles">
		~if $brideGroom eq 'Grooms'` ~include_partial('seo_mob_profiles_list_amp',[profileArr=>$rightArr,GENDER=>"grooms"])` ~else` ~include_partial('seo_mob_profiles_list_amp',[profileArr=>$leftArr,GENDER=>"brides"])` ~/if`
	</div>
    <button class="ampstart-btn caps m2" on="tap:my-lightbox" role="button" tabindex="0">
  		<div class="bg7 comH_pos2 posfix comH_radius hgt40 wid95p txtc white fontreg f15 pad10"> Search for
            ~if $brideGroom eq 'Grooms'`Brides ~else` Grooms~/if`
        </div>
	</button>
    <amp-lightbox id="my-lightbox" scrollable layout="nodisplay">
        <div class="lightbox" role="button" tabindex="0">
            <h3>
                <div class="fullwid bg10 pad18">
                    <div class="posrel txtc">
                        <div class="color5 fontthin f19">~$levelObj->getH1Tag()`<span id="brideGroom"> Matrimonial</span></div>
                        <a href="/browse-matrimony-profiles-by-community-jeevansathi"></a>
                    </div>
                </div>
                <div class="fullhgt" id="rightProfiles">
                    ~if $brideGroom eq 'Grooms'` ~include_partial('seo_mob_profiles_list_amp',[profileArr=>$leftArr,GENDER=>"brides"])` ~else` ~include_partial('seo_mob_profiles_list_amp',[profileArr=>$rightArr,GENDER=>"grooms"])` ~/if`
                </div>
                <button on="tap:my-lightbox.close" class="closeBtn">
                	<div class="bg7 comH_pos2 posfix comH_radius hgt40 wid95p txtc white fontreg f15 pad10"> Search for 
                    	~if $brideGroom eq 'Grooms'`Grooms ~else` Brides~/if`
                	</div>      
                </button>
            </h3>
        </div>
    </amp-lightbox>
    <div class="textcl pad18">
        <div class="fontlig f13 color1 cursp lh25">~$levelObj->getContent()|decodevar`</div>
    </div>
</div>
<div class="clr hgt35"></div>
