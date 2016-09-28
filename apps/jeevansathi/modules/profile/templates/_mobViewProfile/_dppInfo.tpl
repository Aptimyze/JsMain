<!--start:content-->
<div class="pad5 bg4 fontlig color3 clearfix f14">
	~if isset($arrData.about_partner)`
		<div class="fontlig pad20 wordBreak vpro_lineHeight" id="vpro_about_partner">~$arrData.about_partner`</div>
    ~else`
        <div class="hgt10"></div>
	~/if`
	~if isset($arrData.dpp_height)`
		<div class="f12 color1">~if $gender eq Male` She ~else` He ~/if` should be</div>
		<div class="fontlig pb15" id="vpro_dpp_height">~$arrData.dpp_height`</div>
	~/if`
	~if isset($arrData.dpp_age)`
		<div class="f12 color1">Age between</div>
		<div class="fontlig pb15" id="vpro_dpp_age">~$arrData.dpp_age`</div>
	~/if`
	~if isset($arrData.dpp_marital_status)`
		<div class="f12 color1">Marital Status</div>
		<div class="fontlig pb15" id="vpro_dpp_marital_status">~$arrData.dpp_marital_status`</div>
	~/if`
        ~if isset($arrData.dpp_have_child) && $arrData.dpp_marital_status!='Never Married' && $arrData.dpp_marital_status!=''`
		<div class="f12 color1">Have Children</div>
		<div class="fontlig pb15" id="vpro_dpp_marital_status">~$arrData.dpp_have_child`</div>
	~/if`
	~if isset($arrData.dpp_manglik)`
		<div class="f12 color1">Kundli & Astro</div>
		<div class="fontlig pb15" id="vpro_dpp_manglik">~$arrData.dpp_manglik`</div>
	~/if`	
	~if isset($arrData.dpp_religion)`
		<div class="f12 color1">Religion</div>
		<div class="fontlig pb15" id="vpro_dpp_religion">~$arrData.dpp_religion|decodevar`</div>
	~/if`
	~if isset($arrData.dpp_mtongue)`
		<div class="f12 color1">Mother Tongue</div>
		<div class="fontlig pb15" id="vpro_dpp_mtongue">~$arrData.dpp_mtongue|decodevar`</div>
	~/if`
	~if isset($arrData.dpp_caste)`
		<div class="f12 color1">Caste</div>
		<div class="fontlig pb15" id="vpro_dpp_caste">~$arrData.dpp_caste|decodevar`</div>
	~/if`
	~if isset($arrData.dpp_city)`
		<div class="f12 color1">City</div>
		<div class="fontlig pb15" id="vpro_dpp_city">~$arrData.dpp_city|decodevar`</div>
	~/if`
	~if isset($arrData.dpp_country)`
		<div class="f12 color1">Country</div>
		<div class="fontlig pb15" id="vpro_dpp_country">~$arrData.dpp_country|decodevar`</div>
	~/if`
    ~if isset($arrData.dpp_appearance)`
		<div class="f12 color1">Desired Appearance</div>
		<div class="fontlig pb15" id="vpro_dpp_appearance">~$arrData.dpp_appearance|nl2br`</div>
    ~/if`
    ~if isset($arrData.dpp_special_case)`
        <div class="f12 color1">Special Cases</div>
        <div class="fontlig pb15" id="vpro_dpp_special_case">~$arrData.dpp_special_case|nl2br` </div>
    ~/if`
</div>
<!--end:content--> 
<!--start:content-->
~if isset($arrData.dpp_edu_level) || isset($arrData.dpp_occupation) || isset($arrData.dpp_earning)`
	<div class="pad5 bg4 fontlig color3 clearfix f14">
		<div class="fl"><i class="vpro_sprite vpro_edu"></i></div>
		<div class="fl color2 f14 vpro_padlTop" id="vpro_dppEduOccSection">Desired Education & Occupation</div>
		<div class="clr hgt10"></div>
		~if isset($arrData.dpp_edu_level)`
			<div class="f12 color1">Education Level</div>
			<div class="fontlig pb15" id="vpro_dpp_edu_level">~$arrData.dpp_edu_level`</div>
		~/if`
		~if isset($arrData.dpp_occupation)`
			<div class="f12 color1">Occupation</div>
			<div class="fontlig pb15" id="vpro_dpp_occupation">~$arrData.dpp_occupation`</div>
		~/if`
		~if isset($arrData.dpp_earning)`	  
			<div class="f12 color1">Earning</div>
			<div class="fontlig pb15" id="vpro_dpp_earning">~$arrData.dpp_earning`</div>
		~/if`
	</div>
~/if`	
<!--end:content--> 
<!--start:content-->
~if isset($arrData.dpp_lifestyle)`
	<div class="pad5 bg4 fontlig color3 clearfix f14">
		<div class="fl"><i class="vpro_sprite vpro_lstyle"></i></div>
		<div class="fl color2 f14 vpro_padlTop" id="vpro_dppLifestyleSection">Desired Lifestyle</div>
			<div class="clr hgt10"></div>
			<div class="f12 color1">Habits</div>
			<div class="fontlig pb15" id="vpro_dpp_lifestyle">~$arrData.dpp_lifestyle|nl2br` </div>
	</div>
~/if`	
<!--end:content-->
