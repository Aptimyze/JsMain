<!--start:content-->
<div class="pad5 bg4 fontlig color3 clearfix f14">
    <div class="hgt10"></div>
  <div class="fl"> <span class="f18" id="vpro_username" >~$arrData.username`</span>&nbsp;<span class="f11 color13" id="vpro_last_active" >~$arrData.last_active`</span> </div>
  ~if isset($arrData.subscription_icon)`
	<div class="fr color2 f14 pt5 fontrobbold" id="vpro_subscription" >
        ~if $arrData.subscription_icon eq evalue`
            ~mainMem::EVALUE_LABEL`
        ~else if $arrData.subscription_icon eq jsexclusive` 
            ~mainMem::JSEXCLUSIVE_LABEL`
       ~else if $arrData.subscription_icon eq erishta`
            ~mainMem::ERISHTA_LABEL`
        ~else if $arrData.subscription_icon eq eadvantage`
            ~mainMem::EADVANTAGE_LABEL`
        ~/if` </div>
  ~/if`	
  <div class="clr hgt10"></div>
  <ul class="vpro_info fontlig">
      <li class="wid49p" id="vpro_age" >~$arrData.age` Years  <span id="vpro_height">~$arrData.height`</span></li>
    <li class="wid49p" id="vpro_occupation" >~$arrData.occupation`</li>
    <li class="wid49p" id="vpro_caste" >~$arrData.caste`</li>
    <li class="wid49p" id="vpro_income" >~$arrData.income`</li>
    <li class="wid49p" id="vpro_mtongue" >~$arrData.mtongue`</li>
    <li class="wid49p" id="vpro_education" >~$arrData.educationOnSummary`</li>
    <li class="wid49p" id="vpro_location" >~$arrData.location`</li>
    <li class="wid49p wspace" id="vpro_m_status" >~$arrData.m_status`~if $arrData.have_child`,~$arrData.have_child`~/if`</li>
  </ul>
	~if isset($arrData.myinfo)` 
		<div class="fontlig pad2 wordBreak vpro_lineHeight" id="vpro_myinfo" > ~$arrData.myinfo`</div>
        ~else`
        <div class="hgt10"></div>
	~/if`
	~if isset($arrData.appearance)`
		<div class="f14 color1">Appearance</div>
		<div class="fontlig pb15" id="vpro_appearance" >~$arrData.appearance`</div>
	~/if`
	~if isset($arrData.special_case)`
		<div class="f14 color1">Special Cases</div>
		<div class="fontlig pb15" id="vpro_special_case" >~$arrData.special_case`</div>
	~/if`
</div>
<!--end:content--> 
