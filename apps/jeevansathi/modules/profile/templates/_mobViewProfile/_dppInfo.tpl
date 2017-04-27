<!--start:content-->


<div class="pad5 bg4 fontlig color3 clearfix f14">
	~if isset($arrData.about_partner)`
		<div class="fontlig pad20 wordBreak vpro_lineHeight" id="vpro_about_partner">~$arrData.about_partner`</div>
    ~else`
        <div class="hgt10"></div>
	~/if`
	~if $myPreview neq "1"`
	<div class="clearfix f13 fontlig">
		<div class="fl color2 VPwid28p">~if $gender eq Male` Her ~else` His ~/if` Preference</div>
		<div class="fr color2 VPwid25p">Matches you</div>
		<div class="fl color13 VPwid46p txtc">
			<span class="js-matching"></span> of 
			<span class="js-total"></span> matchings
		</div>
	</div>
	<div class="clearfix pt10 pb10">
		<div class="fl wid24p txtc">
			<img src="" class="VPimg"/> <!-- see how self image can be displayed -->
		</div>
		<div class="fr wid27p txtc">
			<img src="~$thumbnailPic`" class="VPimg"/>
		</div>
	</div>
	~/if`
	~if isset($arrData.dpp_height)`

		<div class="clearfix js-countFields">
			<div class="fl wid71p">
				<div class="f12 color1">~if $gender eq Male` She ~else` He ~/if` should be</div>
				<div class="fontlig pb15 pt5" id="vpro_dpp_height">~$arrData.dpp_height`</div>
			</div>
			~if  $myPreview neq "1"`
			<div class="fr wid27p txtc VPmt5">
			~if $matchingArr.HEIGHT eq 'gnf'`
				<div class="checkmarkVP"></div>
			~else`
			<div class="dashVP"></div>
			~/if`
			</div>
			~/if`
		</div>
	~/if`
	~if isset($arrData.dpp_age)`
		<div class="clearfix js-countFields">
			<div class="fl wid71p">
				<div class="f12 color1">Age between</div>
				<div class="fontlig pb15 pt5" id="vpro_dpp_age">~$arrData.dpp_age`</div>
			</div>
			~if  $myPreview neq "1"`
			<div class="fr wid27p txtc VPmt5">
			~if $matchingArr.AGE eq 'gnf'`
				<div class="checkmarkVP"></div>
			~else`
			<div class="dashVP"></div>
			~/if`
			</div>
			~/if`
		</div>
	~/if`
	~if isset($arrData.dpp_marital_status)`
		<div class="clearfix js-countFields">			
			<div class="fl wid71p">
				<div class="f12 color1">Marital Status</div>
				<div class="fontlig pb15 pt5" id="vpro_dpp_marital_status">~$arrData.dpp_marital_status`</div>
			</div>
			~if  $myPreview neq "1"`
			<div class="fr wid27p txtc VPmt5">
				~if $matchingArr.MSTATUS eq 'gnf'`
					<div class="checkmarkVP"></div>
				~else`
				<div class="dashVP"></div>
				~/if`
			</div>
			~/if`
		</div>
	~/if`
    ~if isset($arrData.dpp_have_child) && $arrData.dpp_marital_status!='Never Married' && $arrData.dpp_marital_status!=''`
		<div class="clearfix js-countFields">
			<div class="fl wid71p">
				<div class="f12 color1">Have Children</div>
				<div class="fontlig pb15 pt5" id="vpro_dpp_marital_status">~$arrData.dpp_have_child`</div>
			</div>
			~if  $myPreview neq "1"`
			<div class="fr wid27p txtc VPmt5">
			~if $matchingArr.HAVECHILD eq 'gnf'`
				<div class="checkmarkVP"></div>
			~else`
			<div class="dashVP"></div>
			~/if`
			</div>
			~/if`
		</div>
	~/if`
	~if isset($arrData.dpp_manglik)`
		<div class="clearfix js-countFields">
			<div class="fl wid71p">
				<div class="f12 color1">Kundli & Astro</div>
				<div class="fontlig pb15" id="vpro_dpp_manglik">~$arrData.dpp_manglik`</div>
			</div>
			~if  $myPreview neq "1"`
			<div class="fr wid27p txtc VPmt5">
			~if $matchingArr.MANGLIK eq 'gnf'`
				<div class="checkmarkVP"></div>
			~else`
			<div class="dashVP"></div>
			~/if`
			</div>
			~/if`
		</div>
	~/if`	
	~if isset($arrData.dpp_religion)`
		<div class="clearfix js-countFields">
			<div class="fl wid71p">
				<div class="f12 color1">Religion</div>
				<div class="fontlig pb15" id="vpro_dpp_religion">~$arrData.dpp_religion|decodevar`</div>
			</div>
			~if  $myPreview neq "1"`
			<div class="fr wid27p txtc VPmt5">
			~if $matchingArr.RELIGION eq 'gnf'`
				<div class="checkmarkVP"></div>
			~else`
			<div class="dashVP"></div>
			~/if`
			</div>
			~/if`
		</div>
	~/if`
	~if isset($arrData.dpp_mtongue)`
		<div class="clearfix js-countFields">
			<div class="fl wid71p">
				<div class="f12 color1">Mother Tongue</div>
				<div class="fontlig pb15" id="vpro_dpp_mtongue">~$arrData.dpp_mtongue|decodevar`</div>
			</div>
			~if  $myPreview neq "1"`
			<div class="fr wid27p txtc VPmt5">
			~if $matchingArr.MTONGUE eq 'gnf'`
				<div class="checkmarkVP"></div>
			~else`
			<div class="dashVP"></div>
			~/if`
			</div>
			~/if`
		</div>
	~/if`
	~if isset($arrData.dpp_caste)`
		<div class="clearfix js-countFields">
			<div class="fl wid71p">
				<div class="f12 color1">Caste</div>
				<div class="fontlig pb15" id="vpro_dpp_caste">~$arrData.dpp_caste|decodevar`</div>
			</div>
			~if  $myPreview neq "1"`
			<div class="fr wid27p txtc VPmt5">
			~if $matchingArr.CASTE eq 'gnf' || $matchingArr.SECT eq 'gnf'`
				<div class="checkmarkVP"></div>
			~else`
			<div class="dashVP"></div>
			~/if`
			</div>
			~/if`
		</div>
	~/if`
	~if isset($arrData.dpp_city)`
		<div class="clearfix js-countFields">
			<div class="fl wid71p">
				<div class="f12 color1">City</div>
				<div class="fontlig pb15" id="vpro_dpp_city">~$arrData.dpp_city|decodevar`</div>
			</div>
			~if  $myPreview neq "1"`
			<div class="fr wid27p txtc VPmt5">
			~if $matchingArr.CITYRES eq 'gnf'`
				<div class="checkmarkVP"></div>
			~else`
			<div class="dashVP"></div>
			~/if`
			</div>
			~/if`
		</div>
		
	~/if`
	~if isset($arrData.dpp_country)`
		<div class="clearfix js-countFields">
			<div class="fl wid71p">
				<div class="f12 color1">Country</div>
				<div class="fontlig pb15" id="vpro_dpp_country">~$arrData.dpp_country|decodevar`</div>
			</div>
			~if  $myPreview neq "1"`
			<div class="fr wid27p txtc VPmt5">
			~if $matchingArr.COUNTRYRES eq 'gnf'`
				<div class="checkmarkVP"></div>
			~else`
			<div class="dashVP"></div>
			~/if`
			</div>
			~/if`
		</div>
	~/if`
   
</div>
<!--end:content--> 
<!--start:content-->
~if isset($arrData.dpp_edu_level) || isset($arrData.dpp_occupation) || isset($arrData.dpp_earning)`
	
<div class="pad5 bg4 fontlig color3 clearfix f14">	
		
		
		~if isset($arrData.dpp_edu_level)`
			<div class="clearfix js-countFields">
				<div class="wid71p fl">
					<div class="f12 color1">Education Level</div>
					<div class="fontlig pb15" id="vpro_dpp_edu_level">~$arrData.dpp_edu_level`</div>
				</div>
				~if  $myPreview neq "1"`
				<div class="fr wid27p txtc VPmt5">
					~if $matchingArr.ELEVEL_NEW eq 'gnf'`
						<div class="checkmarkVP"></div>
					~else`
						<div class="dashVP"></div>
					~/if`
				</div>
				~/if`
			</div>	
		~/if`


		~if isset($arrData.dpp_occupation)`

			<div class="clearfix js-countFields">
				<div class="wid71p fl">
					<div class="f12 color1">Occupation</div>
					<div class="fontlig pb15" id="vpro_dpp_occupation">~$arrData.dpp_occupation`</div>
				</div>
				~if  $myPreview neq "1"`
				<div class="fr wid27p txtc VPmt5">
					~if $matchingArr.OCCUPATION eq 'gnf' && $myPreview neq "1"`
						<div class="checkmarkVP"></div>
					~else`
					<div class="dashVP"></div>
					~/if`
				</div>
				~/if`
			</div>	

		~/if`
		~if isset($arrData.dpp_earning)`
			<div class="clearfix js-countFields">
				<div class="wid71p fl">
					<div class="f12 color1">Earning</div>
					<div class="fontlig pb15" id="vpro_dpp_earning">~$arrData.dpp_earning`</div>
				</div>
				~if  $myPreview neq "1"`
				<div class="fr wid27p txtc VPmt5">
					~if $matchingArr.INCOME eq 'gnf' && $myPreview neq "1"`
						<div class="checkmarkVP"></div>
					~else`
					<div class="dashVP"></div>
					~/if`
				</div>
				~/if`
			</div>

		~/if`

</div>
	
~/if`	
<!--end:content--> 
<!--start:content-->
<div class="pad5 bg4 fontlig color3 clearfix f14">
	~if $arrData.dpp_diet neq null`
		<div class="clearfix js-countFields">
			<div class="wid71p fl">
				<div class="f12 color1">Diet</div>
				<div class="fontlig pb15" id="vpro_dpp_lifestyle">~$arrData.dpp_diet` </div>
			</div>
			~if  $myPreview neq "1"`
			<div class="fr wid27p txtc VPmt5">
				~if $matchingArr.DIET eq 'gnf' && $myPreview neq "1"`
					<div class="checkmarkVP"></div>
				~else`
					<div class="dashVP"></div>
				~/if`
			</div>
			~/if`
		</div>
	~/if`
	~if $arrData.dpp_smoke neq null`		
		<div class="clearfix js-countFields">
			<div class="wid71p fl">
				<div class="f12 color1">Smoke</div>
				<div class="fontlig pb15" id="vpro_dpp_lifestyle">~$arrData.dpp_smoke` </div>
			</div>
			~if  $myPreview neq "1"`
			<div class="fr wid27p txtc VPmt5">
				~if $matchingArr.SMOKE eq 'gnf' && $myPreview neq "1"`
				<div class="checkmarkVP"></div>
				~else`
				<div class="dashVP"></div>
				~/if`
			</div>
			~/if`
		</div>
	~/if`
	~if $arrData.dpp_drink neq null`
		<div class="clearfix js-countFields">
			<div class="wid71p fl">
				<div class="f12 color1">Drink</div>
				<div class="fontlig pb15" id="vpro_dpp_lifestyle">~$arrData.dpp_drink` </div>
			</div>
			~if  $myPreview neq "1"`
			<div class="fr wid27p txtc VPmt5">
				~if $matchingArr.DRINK eq 'gnf' && $myPreview neq "1"`
				<div class="checkmarkVP"></div>
				~else`
				<div class="dashVP"></div>
				~/if`
			</div>
			~/if`
		</div>
	~/if`
	~if $arrData.dpp_complexion neq null`
		<div class="clearfix js-countFields">
			<div class="wid71p fl">
				<div class="f12 color1">Complexion</div>
				<div class="fontlig pb15" id="vpro_dpp_lifestyle">~$arrData.dpp_complexion` </div>
			</div>
			~if  $myPreview neq "1"`
			<div class="fr wid27p txtc VPmt5">
				~if $matchingArr.COMP eq 'gnf'`
				<div class="checkmarkVP"></div>
				~else`
				<div class="dashVP"></div>
				~/if`
			</div>
			~/if`
		</div>
	~/if`
	~if $arrData.dpp_btype neq null`
		<div class="clearfix js-countFields">
			<div class="wid71p fl">
				<div class="f12 color1">Body Type </div>
				<div class="fontlig pb15" id="vpro_dpp_lifestyle">~$arrData.dpp_btype` </div>
			</div>
			~if  $myPreview neq "1"`
			<div class="fr wid27p txtc VPmt5">
				~if $matchingArr.BTYPE eq 'gnf'`
				<div class="checkmarkVP"></div>
				~else`
				<div class="dashVP"></div>
				~/if`
			</div>
			~/if`
		</div>
	~/if`
	~if $arrData.dpp_handi neq null`
		<div class="clearfix js-countFields">
			<div class="wid71p fl">
				<div class="f12 color1">Challenged </div>
				<div class="fontlig pb15" id="vpro_dpp_lifestyle">~$arrData.dpp_handi` </div>
			</div>
			~if  $myPreview neq "1"`
			<div class="fr wid27p txtc VPmt5">
				~if $matchingArr.HANDI eq 'gnf'`
				<div class="checkmarkVP"></div>
				~else`
				<div class="dashVP"></div>
				~/if`
			</div>
			~/if`
		</div>
	~/if`
</div>
<!--end:content-->
