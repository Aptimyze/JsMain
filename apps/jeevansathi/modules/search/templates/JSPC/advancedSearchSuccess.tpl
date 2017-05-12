<!--start:header-->
<header>
  <div class="cover1">
    <div id="adv-topNav" class="container mainwid pt30 pb30">
			~include_partial("global/JSPC/_jspcCommonTopNavBar")` 
        
    </div>
  </div>
</header>
~append var='minArray' value='Min_Age' index=0`
~append var='minArray' value='Min_Height' index=1`
~append var='minArray' value='rsLIncome' index=2`
~append var='minArray' value='doLIncome' index=3`
~assign var=zedoValue value= $sf_request->getAttribute('zedo')`
~assign var=zedo value= $zedoValue["zedo"]`
<!--end:header--> 
<!--start:middle-->
<div class="bg-4">
  <div class="mainwid container pb30"> 
    <!--start:tab 1-->
    <div class="advbg1 advbdr2">
      <ul class="hor_list clearfix fontlig f15 pos-rel tab1">
        <li class="~if $loggedIn eq 1`wid33p_2 ~else`advwid1~/if` txtc advp1"> <span class="advbdr1 cursp" id="searchSection">Search</span> </li>
        ~if $loggedIn eq 1` <li class="wid33p_2 txtc advp1"> <span class="advbdr1 cursp" id="savedSearchSection">My Saved Searches</span> </li> ~/if`
        <li class="~if $loggedIn eq 1`wid33p_2 ~else`advwid1~/if` txtc advp1"> <span class="cursp" id="searchByIdSection">Search by Profile ID</span> </li>
        <li class="pos-abs ~if $loggedIn eq 1`wid33p_2 ~else` advwid1 ~/if` bg5 advhgt1" id="bottomThinBar" style="left:0px; bottom:0"></li>
      </ul>
    </div>
    <!--end:tab 1--> 
    <!--start:form section-->
    <div class="bg-white mt30">
      <!--start:search tab section-->
      <div id="srchform" class="vis-hid"> 
        <!--start:form 1-->
        <div class="advp2">
          <ul class="listnone formbox fontreg f15">
						  <li class="js-toggle js-frmfld clearfix ~if $loggedIn eq 1`disp-none~/if`" id="Gender" data='~$dataArray["selectedValues"]["gender"]`'>
	              <label class="pt14">Search for</label>
	              <div class="advbdr3 advbr1">
	                <ul class="hor_list clearfix">
	                  <li id="Gender_F" data='F' class="txtc cursp color11 wid50p advp4 advbdr1 ~if $dataArray['selectedValues']['gender'] eq 'F'`activeopt~/if`"><span class="disp_ib">Bride</span></li>
	                  <li id="Gender_M" data='M' class="txtc cursp color11 advwid4 advp4 ~if $dataArray['selectedValues']['gender'] eq 'M'`activeopt~/if`"><span class="disp_ib">Groom</span></li>
	                </ul>
	              </div>
	            </li>
	          
            ~foreach from=$searchSection["BASIC"] item=value key=kk`
	            
							~if !$searchFeilds[$value]["attr"]`
								
										~if in_array($value,$minArray)`
											<li class="clearfix pt38">
												<label class="pt14">~$searchFeilds[$value]["label"]`</label>
												<div class="advbdr3 advbr1">
					                <div id="~$value`" class="fl js-frmfld wid50p advp4 pos_rel cursp advbdr1 js-fielddd" data='~$dataArray["selectedValues"][$value]`'>
					                  <div class="pos_rel color11"> 
															<span class="disp_ib pl22"></span> ~if $value eq 'Min_Age'` years~/if`
															<i class="sprite2 pos_abs dpp_pos2 dpp-drop-down dpp_pos1"></i>
																<div class="js-dd disp-none hide1">
																 <i class="sprite2 pos_abs z2 dpp-up-arrow dpp-pos2"></i> 
							                    <!--end:drop down icon--> 
							                    <!--start:drop down box-->
							                    ~if strpos($value,'Income')!=false` 
																		<div class="dppbox pos_abs z1 dpp-pos3 scrolla hgt200 ">
																	~else if strpos($value,'Height')!=false` 
																		<div class="dppbox pos_abs z1 dpp-pos3 advwidH scrolla hgt200 ">
																	~else`
																		<div class="dppbox pos_abs wid380 z1 dpp-pos3 scrolla hgt200 ">
							                    ~/if`  
							                      <ul class="clearfix list-min~$searchFeilds[$value]['feild']`">
																			~foreach from=$dataArray[$searchFeilds[$value]["feild"]] item=v key=k`
																				<li id="~$v['VALUE']`" data='~$v["LABEL"]`'>~$v["LABEL"]`</li>
							                        ~/foreach`
						                      </ul>
						                    </div> 
						                   </div>
														</div>
					                </div>
		                ~else`
					                <div id="~$value`" class="js-frmfld fl wid49p advp4 pos_rel cursp js-fielddd" data='~$dataArray["selectedValues"][$value]`'>
						                  <div class="pos_rel color11">
																<span class="disp_ib pl22"> </span>~if $value eq 'Max_Age'` years~/if`
																<i class="sprite2 pos_abs dpp_pos2 dpp-drop-down dpp_pos1"></i> 
																<div class="js-dd disp-none  hide1">
																	<!--start:drop down icon--> 
							                    <i class="sprite2 pos_abs z2 dpp-up-arrow dpp-pos2"></i> 
							                    <!--end:drop down icon--> 
							                    <!--start:drop down box-->
							                    ~if strpos($value,'Income')!=false` 
																		<div class="dppbox pos_abs z1 dpp-pos4 scrolla hgt200 ">
																	~else if strpos($value,'Height')!=false` 
																		<div class="dppbox pos_abs z1 dpp-pos4 advwidH scrolla hgt200 ">
																	~else`
																		<div class="dppbox pos_abs wid380 z1 dpp-pos4 scrolla hgt200 ">
							                    ~/if`
							                   
							                      <ul class="clearfix list-max~$searchFeilds[$value]['feild']`">
																			~foreach from=$dataArray[$searchFeilds[$value]["feild"]] item=v key=k`
																				<li id="~$v['VALUE']`" data='~$v["LABEL"]`'>~$v["LABEL"]`</li>
							                        ~/foreach`
						                        </ul>
						                      </div>
																</div>
															</div>
					                </div>
					               </div>
					             </li>
			              ~/if`
		          ~else`
								<li id="~$value`Parent" class="clearfix pt20" ~if $searchFeilds[$value]["isDependant"]` style="display: none;" ~/if`>

									<div class="clearfix advremall">
										<div class="cursp f12 color5 pb5 js-remall vishid fr" id="~$value`-rem" >Remove all</div>

									</div>
									
									<label class="pt14">~$searchFeilds[$value]["label"]`</label>
									<div class="advbdr3 advbr1">
									  <div id="~$value`Div" class="advp3">
		                  <select id="~$value`" data-placeholder="Doesn't Matter" multiple class="js-frmfld chosen-select-width js-torem" data='~$dataArray["selectedValues"][$value]`' >
		                    <option class="textTru chosenDropWid" value=""></option>
													~assign var="doItOnce" value="false"`
													~foreach from=$dataArray[$searchFeilds[$value]["feild"]] key=id item=v` 
			                          ~if $v["VALUE"]==-1` 
			                            ~if isset($doItOnce)`</optgroup>~/if`
			                          ~assign var="doItOnce" value="true"`
			                          <optgroup class="brdrb-4 fullwidImp" value='~$v["VALUE"]`' label=~$v["LABEL"]`>
			                          ~else`
																	
			                          <option class="textTru chosenDropWid" id="~$v['VALUE']`" value='~$v["VALUE"]`'>~$v["LABEL"]`</option>
			                          ~/if`
			                     
			                      ~/foreach`
		                  </select>
		                </div>
								  </div>
								</li>
		          ~/if`
	              
            ~/foreach`
            
            <li class="js-frmfld js-toggle clearfix pt38" id="HAVEPHOTO" data='~$dataArray["selectedValues"]["HAVEPHOTO"]`'>
              <label class="pt14">Photo</label>
              <div class="advbdr3 advbr1">
                <ul class="hor_list clearfix">
									<li class="color11 cursp wid50p txtc advp4 advbdr1 ~if $dataArray['photo'][0]['VALUE'] eq $dataArray['selectedValues']['HAVEPHOTO']`activeopt~/if`" data="~$dataArray['photo'][0]['VALUE']`" id="~$dataArray['photo'][0]['VALUE']`"> <span class="disp_ib">~$dataArray['photo'][0]['LABEL']`</span> </li>
                  <li class="color11 cursp advwid4 advp4 txtc ~if $dataArray['photo'][1]['VALUE'] eq $dataArray['selectedValues']['HAVEPHOTO']`activeopt~/if`" data="~$dataArray['photo'][1]['VALUE']`" id="~$dataArray['photo'][1]['VALUE']`"> <span class="disp_ib">~$dataArray['photo'][1]['LABEL']`</span> </li>
                  
                </ul>
              </div>
            </li>
          </ul>
        </div>
        <!--end:form 1--> 
        <!--start:form 2-->
        <div ~if $dataArray["selectedValues"]["hideAstro"] eq "1"` class="disp-none"~/if`> 
          <!--start:title-->
          <div class="advbg2 advbdr4">
            <div class="advp7 txtc"> <span class="disp_ib f17 fontreg advcolor1 pr20 pos-rel cursp advopt ~if $dataArray['selectedValues']['openOption']['ASTRO']`js-openSection~/if`" id="astro">Astro <i class="pos-abs vicons advic1 advpos1"></i></span> </div>
          </div>
          <!--end:title--> 
          <!--start:form section-->
          <div class="astroform disp-none" >
              <div class="advp8">
                <ul class="listnone formbox fontreg f15">
									 ~foreach from=$searchSection["ASTRO"] name=a item=value key=kk`
										<li id="~$value`Parent" class="clearfix ~if !$smarty.foreach.a.first` pt20 ~/if`">
										<div class="clearfix advremall">
										<div class="cursp f12 color5 pb5 js-remall vishid fr" id="~$value`-rem" >Remove all</div>

									</div>
												<label class="pt14">~$searchFeilds[$value]["label"]`</label>
												<div class="advbdr3 advbr1">
												  <div id="~$value`Div" class="advp3">
					                  <select id="~$value`" data-placeholder="Doesn't Matter" multiple class="js-frmfld chosen-select-width js-torem" data='~$dataArray["selectedValues"][$value]`'>
					                    <option class="textTru chosenDropWid" value=""></option>
																~assign var="doItOnce" value="false"`
						                    ~foreach from=$dataArray[$searchFeilds[$value]["feild"]] key=id item=v` 
						                          ~if $v["VALUE"]==-1` 
						                            ~if isset($doItOnce)`</optgroup>~/if`
						                          ~assign var="doItOnce" value="true"`
						                          <optgroup class="brdrb-4 fullwidImp" value='~$v["VALUE"]`' label=~$v["LABEL"]`>
						                          ~else`
						                          <option class="textTru chosenDropWid" id="~$v['VALUE']`" value='~$v["VALUE"]`'>~$v["LABEL"]`</option>
						                          ~/if`
						                     
						                      ~/foreach`
					                  </select>
					                </div>
											  </div>
											</li>
									 ~/foreach`
									 <li class="js-frmfld js-toggle clearfix pt38" id="Horoscope" data='~$dataArray["selectedValues"]["Horoscope"]`'>
			              <label class="pt14">Horoscope Available?</label>
			              <div class="advbdr3 advbr1">
			                <ul class="hor_list clearfix">
												<li class="color11 cursp f16 wid50p txtc advp4 advbdr1 ~if $dataArray['Horoscope'][0]['VALUE'] eq $dataArray['selectedValues']['Horoscope']`activeopt~/if`" data="~$dataArray['Horoscope'][0]['VALUE']`" id="~$dataArray['Horoscope'][0]['VALUE']`"> <span class="disp_ib">~$dataArray['Horoscope'][0]['LABEL']`</span> </li>
			                  <li class="color11 cursp f16 advwid4 advp4 txtc ~if $dataArray['Horoscope'][1]['VALUE'] eq $dataArray['selectedValues']['Horoscope']`activeopt~/if`" data="~$dataArray['Horoscope'][1]['VALUE']`" id="~$dataArray['Horoscope'][1]['VALUE']`"> <span class="disp_ib">~$dataArray['Horoscope'][1]['LABEL']`</span> </li>
			                  
			                </ul>
			              </div>
									</li>
                </ul>
              </div>
          </div>
          <!--end:form section--> 
        </div>
        <!--end:form 2--> 
        <!--start:form 3-->
        <div> 
          <!--start:title-->
          <div class="advbg2 advbdr4">
            <div class="advp7 txtc"> <span class="disp_ib f17 fontreg advcolor1 pr20 pos-rel cursp advopt ~if $dataArray['selectedValues']['openOption']['EDUCATION_CAREER']`js-openSection~/if` " id="edu">Education & Career <i class="pos-abs vicons advic1 advpos1"></i></span> </div>
          </div>
          <!--end:title--> 
          <!--start:form section-->
          <div class="eduform disp-none" >
              <div class="advp8">
                <ul class="listnone formbox fontreg f15">
                
                  ~foreach from=$searchSection["EDUCATION_CAREER"] name=e item=value key=kk`
										<li id="~$value`Parent" class="clearfix ~if !$smarty.foreach.e.first` pt20 ~/if`">
										<div class="clearfix advremall">
										<div class="cursp f12 color5 pb5 js-remall vishid fr" id="~$value`-rem" >Remove all</div>

									</div>
												<label class="pt14">~$searchFeilds[$value]["label"]`</label>
												<div class="advbdr3 advbr1">
												  <div id="~$value`Div" class="advp3">
					                  <select id="~$value`" data-placeholder="Doesn't Matter" multiple class="js-frmfld chosen-select-width js-torem" data='~$dataArray["selectedValues"][$value]`'>
					                    <option class="textTru chosenDropWid" value=""></option>
																~assign var="doItOnce" value="false"`
						                    ~foreach from=$dataArray[$searchFeilds[$value]["feild"]] key=id item=v` 
						                          ~if $v["VALUE"]==-1` 
						                            ~if isset($doItOnce)`</optgroup>~/if`
						                          ~assign var="doItOnce" value="true"`
						                          <optgroup class="brdrb-4 fullwidImp" value='~$v["VALUE"]`' label=~$v["LABEL"]`>
						                          ~else`
						                          <option class="textTru chosenDropWid" id="~$v['VALUE']`" value='~$v["VALUE"]`'>~$v["LABEL"]`</option>
						                          ~/if`
						                     
						                      ~/foreach`
					                  </select>
					                </div>
											  </div>
											</li>
									 ~/foreach`
                </ul>
              </div>
          </div>
          <!--end:form section--> 
        </div>
        <!--end:form 3--> 
        <!--start:form 4-->
        <div> 
          <!--start:title-->
          <div class="advbg2 advbdr4">
            <div class="advp7 txtc"> <span class="disp_ib f17 fontreg advcolor1 pr20 pos-rel cursp advopt ~if $dataArray['selectedValues']['openOption']['LIFESTYLE']`js-openSection~/if`" id="lifes">Lifestyle<i class="pos-abs vicons advic1 advpos1"></i></span> </div>
          </div>
          <!--end:title--> 
          <!--start:form section-->
          <div class="lifesform disp-none" >
              <div class="advp8">
                <ul class="listnone formbox fontreg f15">
                
                  ~foreach from=$searchSection["LIFESTYLE"] name=l item=value key=kk`
										<li id="~$value`Parent" class="clearfix ~if !$smarty.foreach.l.first` pt20 ~/if`">
										<div class="clearfix advremall">
										<div class="cursp f12 color5 pb5 js-remall vishid fr" id="~$value`-rem" >Remove all</div>

									</div>
												<label class="pt14">~$searchFeilds[$value]["label"]`</label>
												<div class="advbdr3 advbr1">
												  <div id="~$value`Div" class="advp3">
					                  <select id="~$value`" data-placeholder="Doesn't Matter" multiple class="js-frmfld chosen-select-width js-torem" data='~$dataArray["selectedValues"][$value]`'>
					                    <option class="textTru chosenDropWid" value=""></option>
																~assign var="doItOnce" value="false"`
						                    ~foreach from=$dataArray[$searchFeilds[$value]["feild"]] key=id item=v` 
						                          ~if $v["VALUE"]==-1` 
						                            ~if isset($doItOnce)`</optgroup>~/if`
						                          ~assign var="doItOnce" value="true"`
						                          <optgroup class="brdrb-4 fullwidImp" value='~$v["VALUE"]`' label=~$v["LABEL"]`>
						                          ~else`
						                          <option class="textTru chosenDropWid" id="~$v['VALUE']`" value='~$v["VALUE"]`'>~$v["LABEL"]`</option>
						                          ~/if`
						                     
						                      ~/foreach`
					                  </select>
					                </div>
											  </div>
											</li>
									 ~/foreach`
                   
                </ul>
              </div>
          </div>
          <!--end:form section--> 
        </div>
        <!--end:form 4--> 
        <!--start:form 5-->
          <div id="moreVisArea">  
          <!--start:title-->
          <div class="advbg2 advbdr4">
            <div class="advp7 txtc"> <span class="disp_ib f17 fontreg advcolor1 pr20 pos-rel cursp advopt ~if $dataArray['selectedValues']['openOption']['MORE'] eq '1'`js-openSection~/if`" id="moreopt">More options<i class="pos-abs vicons advic1 advpos1"></i></span> </div>
          </div>
          <!--end:title--> 
          <!--start:form section-->
          <div class="moreoptform disp-none" >
              <div class="advp8">
                <ul class="listnone formbox fontreg f15">
                
                   ~foreach from=$searchSection["MORE"] name=m item=value key=kk`
										<li id="~$value`Parent" class="clearfix ~if !$smarty.foreach.m.first` pt20 ~/if`">
										<div class="clearfix advremall">
										<div class="cursp f12 color5 pb5 js-remall vishid fr" id="~$value`-rem" >Remove all</div>

									</div>
												<label class="pt14">~$searchFeilds[$value]["label"]`</label>
												<div class="advbdr3 advbr1">
												  <div id="~$value`Div" class="advp3">
					                  <select id="~$value`" data-placeholder="Doesn't Matter" multiple class="js-frmfld chosen-select-width js-torem" data='~$dataArray["selectedValues"][$value]`'>
					                    <option class="textTru chosenDropWid" value=""></option>
																~assign var="doItOnce" value="false"`
																~foreach from=$dataArray[$searchFeilds[$value]["feild"]] key=id item=v` 
						                          ~if $v["VALUE"]==-1` 
						                            ~if isset($doItOnce)`</optgroup>~/if`
						                          ~assign var="doItOnce" value="true"`
						                          <optgroup class="brdrb-4 fullwidImp" value='~$v["VALUE"]`' label=~$v["LABEL"]`>
						                          ~else`
																					
																				<option class="textTru chosenDropWid" id="~$v['VALUE']`" value='~$v["VALUE"]`'>~$v["LABEL"]`</option>
						                          ~/if`
						                     
						                      ~/foreach`
					                  </select>
					                </div>
											  </div>
											</li>
									 ~/foreach`
									 <li class="clearfix pt38">
                    <label class="pt14">Search by keyword</label>
                    <div class="advbdr3 advbr1">
                          <div class="clearfix">
                               <div class="fl advbdr1 wid70p">
                               		<div class="advp3 ">
                                    	<input type="text" id="keywords" name="keywords" class="js-frmfld fullwid lh30 brdr-0 outw fontreg f16" value='~$dataArray["selectedValues"]["keywords"]`'/>
                                    </div>
                               </div>
                               <div id="kwd_rule" class="js-frmfld cursp fr wid20p txtc wid29p js-selfSelect ~if $dataArray['selectedValues']['kwd_rule']` activeopt ~/if`" data="AND" value="AND">
                                    <div class="advp3 f13 lh30 fontlig">
                                        Match all words
                                    </div>
                               </div>
                          </div>
                    </div>
                  </li>
                  </ul>
                  
                    <label class="pt14"></label>
                    ~if $loggedIn eq 1`
											<div class="pt30 clearfix advp11" >
			                	<div class="clearfix advbtn1 fontlig f15 advwid6">
			                    	<button id="Login" data="Y" value="Y" class="color11 cursp f16 js-frmfld fl js-selfSelect">New Profiles since last visit</button>
                            <button id="Online" data="Y" value="Y" class=" color11 cursp f16 js-frmfld fr js-selfSelect">Only online profiles</button>
                        </div>
											</div>
										~else`
										 <div class="pt30 clearfix mauto advwid7">
												<div class="clearfix advbtn1 fontlig f15">                    	
													<button id="Online" data="Y" value="Y" class=" color11 cursp f16 js-frmfld fr js-selfSelect ">Only online profiles</button>
                    
												</div>
											</div>
										~/if`
                   
                 
                
              </div>
          </div>
          <!--end:form section--> 
        </div>
        <!--end:form 5--> 
        <div class="avdgr1 advwid5 txtc" id="srchscroll">
						<div style="position: relative; overflow: hidden; left: 50%; transform: translate(-50%, 0px); width: 181px; height: 44px;" class="mt15">
							<button id="Submit" class="cursp bg_pink pinkRipple hoverPink colrw f20 fontreg brdr-0 lh44 advp9 GATracking">Search</button>
						</div>
							
				</div>
				<div id="advSearch_form"></div>
      </div>
      <!--end:search tab section-->
      <!--start:search by profile id section-->
      <div id="savedsearchlist" class="disp-none"> 
        ~include_partial("search/JSPC/_savedSearches")`
      </div>      
      <!--end:search by profile id section-->
      <!--start:search by profile id section-->
      <div id="srchbyidform" class="disp-none"> 
        <!--start:form 1-->
        <div class="advp2">
            <form>
              <ul class="listnone fontreg f15">
                    <li>
                        <div class="advwid1 mauto f14 mb3" id="advtitleErr"></div>
                         <div class="advbdr3 advbr1 advwid1 mauto">
                         	<div class="advp3" id="advSearchProIdBox">
                         		<input type="text" value="" placeholder="Profile ID" id="advSearchProId" maxlength="20" class="fullwid lh30 brdr-0 outw heightie"/>
                            </div>
                         </div>
                    </li>
                    <li class="clearfix pt38 txtc">    
                        <div class="scrollhid pos_rel wid114 mar-aut0">
													<button class="bg_pink colrw f20 fontreg brdr-0 lh44 advp10 cursp pinkRipple hoverPink" id="advsearchByIdBtn" onClick="return false;">Search</button>  
                        </div>
                    </li>
              </ul>
            </form>
         </div>
      
      </div>      
      <!--end:search by profile id section-->
      
    </div>
    <!--end:form section--> 
    <div class='mt30' style="text-align:center;" id='zt_~$zedo["masterTag"]`_searchbottom'> </div>
  </div>
</div>
<!--end:middle--> 
<script type="text/javascript">
var casteData  = ~if isset($casteDropDown)` ~$casteDropDown|decodevar` ~else` '' ~/if`;
var loggedIn  = ~if isset($loggedIn)` ~$loggedIn` ~else` '' ~/if`;
var LeftMargin  = ~if $loggedIn eq 1` '66.6%' ~else` '50%' ~/if`;
var searchList = ~if isset($searchList)` ~$searchList|decodevar` ~else` '' ~/if`;
</script>
<!--start:footer-->
~include_partial('global/JSPC/_jspcCommonFooter')`
<!--end:footer--> 


