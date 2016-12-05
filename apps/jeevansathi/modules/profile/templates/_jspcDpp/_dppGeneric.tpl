<!--start:basic details-->
<div id="~$data.sectionId`">
      <div class="edbrd1 pt30 pb30 ~$data.editId` js-editId" data-sectionId = "~$data.editId`">
        <div class="txtc fontreg "> <span class="disp_ib f17 edcolr1">~$data.title`</span>
            ~if $data.title eq "Desired partner"`<span class="disp_ib pr5fontlig f15 color11 scrmsg msgscr posthide opa90 pt5 ~if $existingData[0]['label_val']|strstr:"$underScreeningMessage"`~else`disp-none~/if`">(Under Screening) </span>~/if` 
            <span class="disp_ib cursp ml30 editclk posthide" id="~$data.editId`"> <i class="sprite2 edic1"></i><span class="disp_ib f15 colr5 pl3">Edit</span> </span> </div>
        <!--start:form basic detail-->
        <section>
          <form class="editform" onsubmit="return false;">
        ~foreach from=$fieldArray key=fieldName item=fieldData`
          ~assign var="fieldID" value=$fieldData.fieldId`
            <div class="clearfix pt20" id="dpp-~$fieldID|lower`Parent">

            ~if $fieldData.type eq "M"` 
             <div class="clearfix wid83p">
               <div class="js-resetall prehide f12 color5 cursp disp_ib vishid fr remwid60" 
               id="~$fieldID|lower`-rem">Remove all</div>
             </div>
             ~/if`

              <label>~$fieldData.label`</label>
               ~if $fieldData.type eq "R_AGE"`
              <div id="ageRange" class="edwid2 fl"> 
                <!--start:prefiller values-->
                <p class="fontlig f15 color11 opa90 pt5 posthide" js-deValue1 = "~$fieldData.prefilledMap`"><span><span class="disp_ib pr5">~$existingData[$fieldData.prefilledMap]['label_val']`</span></span></p>
                <!--end:prefiller values--> 
                <!--start:edit on-->
                <div class="bg-white dpp-sel clearfix prehide">
                  <div class="fl wid50p txtc padalla dppselopt pos_rel cursp" id="agemin" data-attr="agemin">
                    <div id="dpp1-~$fieldID|lower`" class="pos_rel color11 f15 opa90 js-rangeDiv1"> <span></span> years <i class="sprite2 pos_abs dpp_pos2 dpp-drop-down dpp_pos1"></i></div>
                    <!--start:drop down icon--> 
                    <i class="sprite2 pos_abs z2 dpp-up-arrow disp-none drop-agemin dpp-pos2 hide1"></i> 
                    <!--end:drop down icon--> 
                    <!--start:drop down box-->
                    <div class="dppbox pos_abs wid380 z1 dpp-pos3 scrolla hgt200 hide1">
                      <ul class="clearfix list-agemin">
                     ~foreach from=$dropDownData[$fieldData.dropDownMap.minage][0] key=id item=dropDownVal` 
                        ~foreach from=$dropDownVal key=innerId item=value`
                       <li data-dbVal="~$innerId`">~$value`</li>
                        ~/foreach`
                     ~/foreach``
                      </ul>
                    </div>
                    <!--end:drop down box--> 
                  </div>
                  <div class="fl brdrl-1 wid49p txtc padalla dppselopt pos_rel cursp" id="agemax" data-attr="agemax">
                    <div id="dpp2-~$fieldID|lower`" class="pos_rel color11 f15 opa90 js-rangeDiv2"> <span></span> years <i class="sprite2 pos_abs dpp_pos2 dpp-drop-down dpp_pos1"></i> </div>
                    <!--start:drop down icon--> 
                    <i class="sprite2 pos_abs z2 dpp-up-arrow disp-none drop-agemax dpp-pos2 hide1"></i> 
                    <!--end:drop down icon--> 
                    <!--start:drop down box-->
                    <div class="dppbox pos_abs wid380 z1 dpp-pos4 scrolla hgt200 hide1">
                      <ul class="clearfix list-agemax">
                        ~foreach from=$dropDownData[$fieldData.dropDownMap.maxage][0] key=id item=dropDownVal` 
                        ~foreach from=$dropDownVal key=innerId item=value`
                       <li data-dbVal="~$innerId`">~$value`</li>
                        ~/foreach`
                       ~/foreach`
                      </ul>
                    </div>
                    <!--end:drop down box--> 
                  </div>
                </div>
                
                <!--end:edit on--> 
              </div>
              
              ~elseif $fieldData.type eq "R_HEIGHT"`
              <div id="heightRange"class="edwid2 fl"> 
                <!--start:prefiller values-->

                <p class="fontlig f15 color11 opa90 pt5 posthide" js-deValue1 = "~$fieldData.prefilledMap`"><span><span class="disp_ib pr5">~$existingData[$fieldData.prefilledMap]['label_val']`</span></span></p>
                <!--end:prefiller values--> 
                <!--start:edit on-->
                <div class="bg-white dpp-sel clearfix prehide">
                  <div class="fl wid50p txtc padalla dppselopt pos_rel cursp" id="heightmin" data-attr="heightmin">
                      <div id="dpp1-~$fieldID|lower`" class="pos_rel color11 f15 opa90 js-rangeDiv1"> <span></span> <i class="sprite2 pos_abs dpp_pos2 dpp-drop-down dpp_pos1"></i> </div>
                    <!--start:drop down icon--> 
                    <i class="sprite2 pos_abs z2 dpp-up-arrow disp-none drop-heightmin dpp-pos2 hide1"></i> 
                    <!--end:drop down icon--> 
                    <!--start:drop down box-->
                    <div class="dppbox pos_abs edwid2 z1 dpp-pos3 scrolla hgt200 hide1">
                      <ul class="clearfix list-heightmin">
                        ~foreach from=$dropDownData[$fieldData.dropDownMap.minheight][0] key=id item=dropDownVal` 
                        ~foreach from=$dropDownVal key=innerId item=value`
                       <li data-dbVal="~$innerId`">~$value`</li>
                        ~/foreach`
                       ~/foreach`
                      </ul>
                    </div>
                    <!--end:drop down box--> 
                  </div>
                  <div class="fl brdrl-1 wid49p txtc padalla dppselopt pos_rel cursp" id="heightmax" data-attr="heightmax">
                    <div id="dpp1-~$fieldID|lower`" class="pos_rel color11 f15 opa90 js-rangeDiv2"> <span></span> <i class="sprite2 pos_abs dpp_pos2 dpp-drop-down dpp_pos1"></i> </div>
                    <!--start:drop down icon--> 
                    <i class="sprite2 pos_abs z2 dpp-up-arrow disp-none drop-heightmax dpp-pos2 hide1"></i> 
                    <!--end:drop down icon--> 
                    <!--start:drop down box-->
                    <div class="dppbox pos_abs edwid2 z1 dpp-pos4 scrolla hgt200 hide1">
                      <ul class="clearfix list-heightmax">
                       ~foreach from=$dropDownData[$fieldData.dropDownMap.maxheight][0] key=id item=dropDownVal` 
                        ~foreach from=$dropDownVal key=innerId item=value`
                       <li data-dbVal="~$innerId`">~$value`</li>
                        ~/foreach`
                       ~/foreach`
                      </ul>
                    </div>
                    <!--end:drop down box--> 
                  </div>
                </div>
                <!--end:edit on--> 
              </div>

              ~elseif $fieldData.type eq "R_INCOME"`
              <div id="incomeRangeRs" class="edwid2 fl"> 
                <!--start:prefiller values-->
                <p class="fontlig f15 color11 opa90 pt5 posthide js-incomefield" js-deValue1 = "~$fieldData.prefilledMap`"><span><span class="disp_ib pr5">~$existingData[$fieldData.prefilledMap]['label_val']`</span></span></p>
                <!--end:prefiller values--> 
                <!--start:edit on-->
                <div class="bg-white dpp-sel clearfix prehide">
                  <div class="fl wid50p txtc padalla dppselopt pos_rel cursp" id="incomemin" data-attr="incomemin">
                    <div id="dpp1-~$fieldID|lower`" class="pos_rel color11 f15 opa90 js-rangeDiv1" data-income="rs"><span>From INR </span> <i class="sprite2 pos_abs dpp_pos2 dpp-drop-down dpp_pos1"></i> </div>
                    <!--start:drop down icon--> 
                    <i class="sprite2 pos_abs z2 dpp-up-arrow disp-none drop-incomemin dpp-pos2 hide1"></i> 
                    <!--end:drop down icon--> 
                    <!--start:drop down box-->
                    <div class="dppbox pos_abs z1 dpp-pos3 scrolla hgt200 hide1">
                      <ul class="clearfix list-incomemin">
                         ~foreach from=$dropDownData[$fieldData.dropDownMap.minIncomeRs][0] key=id item=dropDownVal` 
                        ~foreach from=$dropDownVal key=innerId item=value`
                       <li data-dbVal="~$innerId`">~$value`</li>
                        ~/foreach`
                       ~/foreach`
                      </ul>
                    </div>
                    <!--end:drop down box--> 
                  </div>
                  <div class="fl brdrl-1 wid49p txtc padalla dppselopt pos_rel cursp" id="incomemax" data-attr="incomemax">
                    <div id="dpp2-~$fieldID|lower`" class="pos_rel color11 f15 opa90 js-rangeDiv2" data-income="rs"><span>To INR </span> <i class="sprite2 pos_abs dpp_pos2 dpp-drop-down dpp_pos1"></i> </div>
                    <!--start:drop down icon--> 
                    <i class="sprite2 pos_abs z2 dpp-up-arrow disp-none drop-incomemax dpp-pos2 hide1"></i> 
                    <!--end:drop down icon--> 
                    <!--start:drop down box-->
                    <div class="dppbox pos_abs z1 dpp-pos4 scrolla hgt200 hide1">
                      <ul class="clearfix list-incomemax">
                         ~foreach from=$dropDownData[$fieldData.dropDownMap.maxIncomeRs][0] key=id item=dropDownVal` 
                        ~foreach from=$dropDownVal key=innerId item=value`
                       <li data-dbVal="~$innerId`">~$value`</li>
                        ~/foreach`
                       ~/foreach`
                      </ul>
                    </div>
                    <!--end:drop down box--> 
                  </div>
                </div>
                <!--end:edit on--> 
              </div>

              <div id="incomeRangeDol" class="edwid2 fl hideMore"> 
                <!--start:edit on-->
                <div class="bg-white dpp-sel clearfix prehide">
                  <div class="fl wid50p txtc padalla dppselopt pos_rel cursp" id="incomedolmin" data-attr="incomedolmin">
                    <div id="dpp1-~$fieldID|lower`" class="pos_rel color11 f15 opa90 js-rangeDiv1" data-income="ds"><span>From US $ </span> <i class="sprite2 pos_abs dpp_pos2 dpp-drop-down dpp_pos1"></i> </div>
                    <!--start:drop down icon--> 
                    <i class="sprite2 pos_abs z2 dpp-up-arrow disp-none drop-incomedolmin dpp-pos2 hide1"></i> 
                    <!--end:drop down icon--> 
                    <!--start:drop down box-->
                    <div class="dppbox pos_abs z1 dpp-pos3 scrolla hgt200 hide1">
                      <ul class="clearfix list-incomedolmin">
                         ~foreach from=$dropDownData[$fieldData.dropDownMap.minIncomeDol][0] key=id item=dropDownVal` 
                        ~foreach from=$dropDownVal key=innerId item=value`
                       <li data-dbVal="~$innerId`">~$value`</li>
                        ~/foreach`
                       ~/foreach`
                      </ul>
                    </div>
                    <!--end:drop down box--> 
                  </div>
                  <div class="fl brdrl-1 wid49p txtc padalla dppselopt pos_rel cursp" id="incomedolmax" data-attr="incomedolmax">
                    <div id="dpp2-~$fieldID|lower`" class="pos_rel color11 f15 opa90 js-rangeDiv2" data-income="ds"><span>To US $ </span> <i class="sprite2 pos_abs dpp_pos2 dpp-drop-down dpp_pos1"></i> </div>
                    <!--start:drop down icon--> 
                    <i class="sprite2 pos_abs z2 dpp-up-arrow disp-none drop-incomedolmax dpp-pos2 hide1"></i> 
                    <!--end:drop down icon--> 
                    <!--start:drop down box-->
                    <div class="dppbox pos_abs z1 dpp-pos4 scrolla hgt200 hide1">
                      <ul class="clearfix list-incomedolmax">
                         ~foreach from=$dropDownData[$fieldData.dropDownMap.maxIncomeDol][0] key=id item=dropDownVal` 
                        ~foreach from=$dropDownVal key=innerId item=value`
                       <li data-dbVal="~$innerId`">~$value`</li>
                        ~/foreach`
                       ~/foreach`
                      </ul>
                    </div>
                    <!--end:drop down box--> 
                  </div>
                </div>
                <!--end:edit on--> 
              </div>
              
              <!--multiselect type start-->
              ~elseif $fieldData.type eq "M"` 
              <div id="multiselect" class="edwid2 fl"> 
                <!--start:prefiller values-->
                <p id="shortContent_~$fieldData.idName`" class="fontlig f15 color11 opa90 pt5 posthide" js-deValue1 = "~$fieldData.prefilledMap`"><span class="disp_ib pr5"><span>~$existingData[$fieldData.prefilledMap]['label_val']|substr:0:58`</span>
                
                <span id="more_~$fieldData.idName`" class="js-moreclk fontlig f15 color5 opa90 pt5 cursp ~if $existingData[$fieldData.prefilledMap]['label_val']|count_characters:true lte 58` disp-none ~/if`"> ...more</span></span></p>
                
                <p id="fullContent_~$fieldData.idName`" class="fontlig f15 color11 opa90 pt5 posthide hideMore" js-deValue2 = "~$fieldData.prefilledMap`"><span class="disp_ib pr5">
                ~$existingData[$fieldData.prefilledMap]['label_val']`</span></p>
                
                <!--end:prefiller values--> 
                <!--start:edit on-->
                <div class="bg-white dpp-sel clearfix prehide">
                  <div class="padalli">
                    <select data-placeholder="" id="dpp-~$fieldID|lower`" multiple class="chosen-select-width js-torem">
                    <option value=""></option>
                    ~assign var="doItOnce" value="false"`
                    ~foreach from=$dropDownData[$fieldData.dropDownMap][0] key=id item=dropDownVal` 
                      ~foreach from=$dropDownVal key=innerId item=value`
                          ~if $innerId==-1` 
                            ~if isset($doItOnce)`</optgroup>~/if`
                          ~assign var="doItOnce" value="true"`
                          <optgroup class="brdrb-4 fullwidImp" value='~$innerId`' label=~$value`>
                          ~else`
                          <option class="textTru chosenDropWid" value='~$innerId`'>~$value`</option>
                          ~/if`
                      ~/foreach`
                      ~/foreach` 
                    </select>
                  </div>
                </div>
                <!--start:edit on--> 
              </div>
              <!-- mutliselect type end -->
              <!--starting desired partner text section-->
              ~elseif $fieldData.type eq "T"`
              <div class="edwid2 fl"> 
                <!--start:prefiller values-->
                ~assign var="spouseTxt" value=$existingData[$fieldData.prefilledMap]['label_val']`
                <p class="fontlig posthide f15 color11 opa90 pt5" js-deValue2 = "~$fieldData.prefilledMap`"><span><span class="disp_ib pr5" id="spouseInfo">~if $spouseTxt|count_characters neq 0`~if $spouseTxt|strstr:"$underScreeningMessage"`~$spouseTxt|strstr:"$underScreeningMessage":true|decodevar`~else`~$spouseTxt|decodevar`~/if`~else`-~/if`</span></span></p>
                <!--end:prefiller values--> 
                <!--start:edit on-->
                <div class="bg-white dpp-sel clearfix prehide">
                  <div class="pt10 pl10 pb10 pr10">
                    <textarea id="dpp-~$fieldID|lower`" maxlength="1000" class="wid99p brdr-0 f15 colr3 js-txtarea noresize fontlig outl-no height90"></textarea>
                  </div>
                </div>
                <!--start:edit on--> 
              </div>
              ~/if`
              <!-- ending desired partner text section-->
              <!--start:filter button-->
              <div class="filbtn fl pos-rel h31">
              ~if $fieldData.filter.FILTER eq "Y"`
                ~if $fieldData.filter.FILTER_VALUE eq "N"`
                <div class="filter posthide ml10 cursp" id="~$fieldData.filter.FILTER_MAP`-filter"><span>Strict Filter OFF</span></div>
                ~else`
                <div class="filterset posthide ml10 cursp" id="~$fieldData.filter.FILTER_MAP`-filter"><span class="colrw"><span>Strict Filter ON</span></div>
                 ~/if`
                <div class="pos-abs edwid3 z2 edpos1 filterhover">
                  <div class="edp3">
                    <div class="pos-rel fullwid edbr1 padall-10 bg-white"> <i class="sprite2 pos-abs edpop1"></i>
                    <input type="hidden" id="~$fieldData.filter.FILTER_MAP`-hint" value="~$fieldData.label`"/>
                      <div class="txtc fontreg">
                      ~if $fieldData.filter.FILTER_VALUE eq "N"`
                        <p class="js-~$fieldData.filter.FILTER_MAP`-filter colr5 f15 lh30">Setting ~$fieldData.label` as strict filter?</p>
                        ~else`
                        <p class="js-~$fieldData.filter.FILTER_MAP`-filter colr5 f15 lh30">~$fieldData.label` set as strict filter</p>
                        ~/if`
                        <p class="f13 color11 lnHt">~$fieldData.filter.FILTER_HINT_TEXT` </p>
                      </div>
                    </div>
                  </div>
                </div>
              ~/if`
              </div>
              <!--end:filter button--> 
            </div>
            ~/foreach`
            <!--start:button-->
            <div class="edp2 prehide">
              <button class="btn-b wid100 padallg f20 js-saveBtn cursp" id="button-~$data.editId`">Save</button>
            </div>
      </form>
     </section>
      </div>
</div>
<!--end:basic details--> 
