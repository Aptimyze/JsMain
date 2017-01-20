<!--start:Lifestyle-->
               <div class="prfbr3">
              <div class="prfp6 noMultiSelect" id="section-lifestyle"> 
                <div class="clearfix"> <i class="sprite2 fl prfic37"></i>
                  <div class="fl colr5 pl12 f17 pt2">Lifestyle</div>
                  ~if $bEditView`
                  <div class="fr pt4"><a class="color5 fontlig f15 js-editBtn cursp editableSections" data-section-id="lifestyle">Edit</a> </div>
                  ~/if`
                </div>
                <div class="pl31 prflist1 js-lifestyleView">
                  <ul class="clearfix">
                    <li>
                      <p class="fontlig color12 pt15">Appearance</p>
                      <p class="pt2 pr20">
                        <span id="appearanceView" ~if $apiData["lifestyle"]["appearance"] eq null` class="notFilledInColor" ~/if`>
                          ~if $apiData["lifestyle"]["appearance"] neq null || $bEditView`
                            ~$apiData["lifestyle"]["appearance"]`
                          ~else`
                            Not filled in
                          ~/if`
                        </span>  
                      </p>
                    </li>
                    <li>
                      <p class="fontlig color12 pt15">Habits</p>
                      <p class="pt2 pr20">
                        <span id="habbitsView" ~if $apiData["lifestyle"]["habbits"] eq null` class="notFilledInColor" ~/if`>
                          ~if $apiData["lifestyle"]["habbits"] neq null || $bEditView`
                            ~$apiData["lifestyle"]["habbits"]`
                          ~else`
                            Not filled in
                          ~/if`    
                        </span>
                      </p>
                    </li>
                    <li>
                      <p class="fontlig color12 pt15">Assets</p>
                      <p class="pt2 pr20">
                        <span id="assetsView" ~if $apiData["lifestyle"]["assets"] eq null` class="notFilledInColor" ~/if`>
                          ~if $apiData["lifestyle"]["assets"] neq null || $bEditView`
                            ~$apiData["lifestyle"]["assets"]`
                          ~else`
                            Not filled in
                          ~/if`
                        </span>
                      </p>
                    </li>
                    <li>
                      <p class="fontlig color12 pt15">Languages Known</p>
                      <p class="pt2 pr20">
                        <span id="languageView" ~if $bEditView && $apiData["lifestyle"]["language"] eq $notFilledInText` class="color5" ~else if $apiData["lifestyle"]["language"] eq null` class="notFilledInColor" ~/if` >
                          ~if $apiData["lifestyle"]["language"] neq null || $bEditView`
                            ~$apiData["lifestyle"]["language"]`
                          ~else`
                            Not filled in
                          ~/if`
                        </span>
                      </p>
                    </li>
                    <li>
                      <p class="fontlig color12 pt15">Blood Group</p>
                      <p class="pt2 pr20">
                        <span id="blood_groupView" ~if $bEditView && $apiData["lifestyle"]["blood_group"] eq $notFilledInText` class="color5" ~else if $apiData["lifestyle"]["blood_group"] eq null` class="notFilledInColor" ~/if` >
                          ~if $apiData["lifestyle"]["blood_group"] neq null || $bEditView`
                            ~$apiData["lifestyle"]["blood_group"]`
                          ~else`
                            Not filled in
                          ~/if`
                        </span>
                      </p>
                    </li>
                    ~if !$bEditView && $apiData["lifestyle"]["res_status"] neq null`
                    <li>
                      <p class="fontlig color12 pt15">Residential Status</p>
                      <p class="pt2 pr20">~$apiData["lifestyle"]["res_status"]`</p>
                    </li>
                    </li>
                    ~elseif !$bEditView`
                    <li>
                      <p class="fontlig color12 pt15">Residential Status</p>
                      <p class="pt2 pr20 notFilledInColor">Not filled in</p>
                    </li>
                    ~elseif $bEditView`
                    <li>
                      <p class="fontlig color12 pt15">Residential Status</p>
                      <p class="pt2 pr20">
                        <span id="res_statusView" ~if $bEditView && $apiData["lifestyle"]["res_status"] eq $notFilledInText` class="color5"  ~/if` >
                          ~$apiData["lifestyle"]["res_status"]`</span>
                      </p>
                    </li>
                    ~/if`
                    <li>
                      <p class="fontlig color12 pt15">Special Cases</p>
                      <p class="pt2 pr20">
                      <span id="special_casesView" ~if $apiData["lifestyle"]["special_cases"] eq null` class="notFilledInColor" ~/if`>
                        ~if $apiData["lifestyle"]["special_cases"] neq null || $bEditView`
                          ~$apiData["lifestyle"]["special_cases"]`
                        ~else`
                        Not filled in
                        ~/if`
                      </span>
                      </p>
                    </li>
                    ~if $apiData["lifestyle"]["religion_value"] eq "2" || $apiData["lifestyle"]["religion_value"] eq "3" || $apiData["lifestyle"]["religion_value"] eq "4" || $apiData["lifestyle"]["religion_value"] eq "5" || 
                    $apiData["lifestyle"]["religion_value"] eq "9"`
                    <li>
                      <p class="fontlig color12 pt15" id="dioceseLabelParent">
                        Religious Beliefs <span class="~if !isset($editApi.Details.DIOCESE) || ($editApi.Details.DIOCESE.value|count_characters:true) eq 0 || $editApi.Details.DIOCESE.screenBit neq 1` disp-none ~/if` js-undSecMsg">
                          <span class="disp_ib color5 f13" >Under Screening</span>
                        </span>
                      </p>
                      <p class="pt2 pr20">
                      <span id="religious_beliefsView" ~if $apiData["lifestyle"]["religious_beliefs"] eq null` class="notFilledInColor" ~/if`>
                        ~if $apiData["lifestyle"]["religious_beliefs"] neq null || $bEditView`
                          ~$apiData["lifestyle"]["religious_beliefs"]`
                        ~else`
                          Not filled in
                        ~/if`
                      </span>
                      </p>
                    </li>
                    ~/if`
                 
                  </ul>
                </div>
                <div class="txtr clearfix pt20 js-lifestyleView">
                  <ul class="listn">
                  ~if $apiData["lifestyle"]["open_to_pets"] neq null || $bEditView`
                    <li> <span class="disp_ib sprite2 prfic13"></span> <span class="disp_ib colr2" id="open_to_petsView">~$apiData["lifestyle"]["open_to_pets"]`</span> </li>
                    ~/if`
                  </ul>
                </div>
                ~if $bEditView`
                <!--start:Edit LifeStyle -->
                <div class="pl30 ceditform" id="lifestyleEditForm"><!---Edit Form--></div>
                <!--end:Edit LifeStyle -->
                ~/if`
              </div>
              </div>
              <!--end:Lifestyle-->
