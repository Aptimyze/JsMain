 <!--start:Family Details-->
              <div class="prfbr3">
              <div class="prfp6 noMultiSelect" id="section-family"> 
                <div class="clearfix"> <i class="sprite2 fl prfic14"></i>
                  <div class="fl colr5 pl12 f17 pt2">Family Details</div>
                  ~if $bEditView`
                  <div class="fr pt4"><a  class="color5 fontlig f15 js-editBtn cursp editableSections" data-section-id="family">Edit</a> </div>
                  ~/if`
                </div>
                <div class="pl31 prflist1 js-familyView">
                    <ul class="clearfix">
                    <li>
                      <p class="fontlig color12 pt15">Mother is</p>
                      <p class="pt2 pr20">
                        <span id="mother_occView" ~if $bEditView && $apiData["family"]["mother_occ"] eq $notFilledInText`  class="color5" ~else if $apiData["family"]["mother_occ"] eq null` class="notFilledInColor" ~/if` >
                          ~if $apiData["family"]["mother_occ"] neq null || $bEditView`
                            ~$apiData["family"]["mother_occ"]`
                          ~else`
                          Not filled in
                          ~/if`
                        </span>
                      </p>
                    </li>
                    <li>
                      <p class="fontlig color12 pt15">Father is</p>
                      <p class="pt2 pr20">
                        <span id="father_occView" ~if $bEditView && $apiData["family"]["father_occ"] eq $notFilledInText`  class="color5" ~else if $apiData["family"]["father_occ"] eq null` class="notFilledInColor" ~/if` >
                          ~if $apiData["family"]["father_occ"] neq null || $bEditView`
                            ~$apiData["family"]["father_occ"]`
                          ~else`
                            Not filled in
                          ~/if`
                        </span>
                      </p>
                    </li>
                    <li>
                      <p class="fontlig color12 pt15">Sister(s)</p>
                      <p class="pt2 pr20">
                        <span id="sibling_sisterView" ~if $bEditView && $apiData["family"]["sibling"]["sibling_sister"] eq $notFilledInText`  class="color5" ~else if $apiData["family"]["sibling"]["sibling_sister"] eq null` class="notFilledInColor" ~/if` >
                          ~if $apiData["family"]["sibling"]["sibling_sister"] neq null || $bEditView`
                            ~$apiData["family"]["sibling"]["sibling_sister"]`
                          ~else`
                            Not filled in
                          ~/if`
                        </span>
                      </p>
                    </li>
                    <li>
                      <p class="fontlig color12 pt15">Brother(s)</p>
                      <p class="pt2 pr20">
                        <span id="sibling_brotherView" ~if $bEditView && $apiData["family"]["sibling"]["sibling_brother"] eq $notFilledInText`  class="color5" ~else if $apiData["family"]["sibling"]["sibling_brother"] eq null` class="notFilledInColor" ~/if` >
                          ~if $apiData["family"]["sibling"]["sibling_brother"] neq null || $bEditView`
                            ~$apiData["family"]["sibling"]["sibling_brother"]`
                          ~else`
                            Not filled in
                          ~/if`
                        </span>
                      </p>
                    </li>

                    ~if $apiData["lifestyle"]["religion_value"] eq "2" && !$bEditView` 
                    <li>
                      <p class="fontlig color12 pt15">Caste</p>
                      <p class="pt2 pr20">
                        <span id="caste_MuslimView" ~if $bEditView && $apiData["family"]["caste"] eq $notFilledInText`  class="color5" ~else if $apiData["family"]["caste"] eq null` class="notFilledInColor" ~/if` >
                          ~if $apiData["family"]["caste"] neq null`
                            ~$apiData["family"]["caste"]`
                          ~else`
                            Not filled in
                          ~/if`
                        </span>
                      </p>
                    </li>
                    ~/if`


                    ~if $apiData["lifestyle"]["religion_value"] eq "1"`
                    <li>
                      <p class="fontlig color12 pt15" id="subcasteLabelParent">Sub-caste 
                        ~if $bEditView`
                          <span class="~if ($editApi.Details.SUBCASTE.value|count_characters:true) eq 0 || $editApi.Details.SUBCASTE.screenBit neq 1` disp-none ~/if` js-undSecMsg"> 
                            <span class="disp_ib color5 f13" > Under Screening</span>
                          </span>
                        ~/if`
                      </p>
                      <p class="pt2 pr20" >
                        <span id="sub_casteView" ~if $bEditView && $apiData["family"]["sub_caste"] eq $notFilledInText`  class="color5" ~else if $apiData["family"]["sub_caste"] eq null` class="notFilledInColor" ~/if` >
                          ~if $apiData["family"]["sub_caste"] neq null || $bEditView`
                            ~$apiData["family"]["sub_caste"]`
                          ~else`
                            Not filled in
                          ~/if`
                        </span>
                      </p>
                    </li>
                    ~/if`
                    ~if $apiData["lifestyle"]["religion_value"] eq "1" || $apiData["lifestyle"]["religion_value"] eq "4" || $apiData["lifestyle"]["religion_value"] eq "7" || $apiData["lifestyle"]["religion_value"] eq "9"`
                    <li>
                      <p class="fontlig color12 pt15" id="gothraLabelParent">Gothra 
                        ~if $bEditView`
                          <span class="~if ($editApi.Details.GOTHRA.value|count_characters:true) eq 0 || $editApi.Details.GOTHRA.screenBit neq 1` disp-none ~/if` js-undSecMsg"> 
                            <span class="disp_ib color5 f13" > Under Screening</span>
                          </span>
                        ~/if`
                      </p>
                      <p class="pt2 pr20" >
                        <span id="gothraView" ~if $bEditView && $apiData["family"]["gothra"] eq $notFilledInText`  class="color5" ~else if $apiData["family"]["gothra"] eq null` class="notFilledInColor" ~/if` >
                          ~if $apiData["family"]["gothra"] neq null || $bEditView`
                            ~$apiData["family"]["gothra"]`
                          ~else`
                            Not filled in
                          ~/if`
                        </span>
                      </p>
                    </li>
                    ~/if`
                    ~if $apiData["lifestyle"]["religion_value"] eq "1"`
                    <li>
                      <p class="fontlig color12 pt15" id="gothra_maternalLabelParent">Gothra (maternal) 
                        ~if $bEditView`
                          <span class="~if ($editApi.Details.GOTHRA_MATERNAL.value|count_characters:true) eq 0 || $editApi.Details.GOTHRA_MATERNAL.screenBit neq 1` disp-none ~/if` js-undSecMsg"> 
                            <span class="disp_ib color5 f13" > Under Screening</span>
                          </span>
                        ~/if`
                      </p>
                      <p class="pt2 pr20" >
                        <span id="gothra_maternalView" ~if $bEditView && $apiData["family"]["gothra_maternal"] eq $notFilledInText`  class="color5" ~else if $apiData["family"]["gothra_maternal"] eq null` class="notFilledInColor" ~/if` >
                          ~if $apiData["family"]["gothra_maternal"] neq null  || $bEditView`
                            ~$apiData["family"]["gothra_maternal"]`
                          ~else`
                            Not filled in
                          ~/if`
                        </span>
                      </p>
                    </li>
                    ~/if`
                    <li>
                      <p class="fontlig color12 pt15">Family Status</p>
                      <p class="pt2 pr20">
                        <span id="family_statusView" ~if $bEditView && $apiData["family"]["family_status"] eq $notFilledInText`  class="color5" ~else if $apiData["family"]["family_status"] eq null` class="notFilledInColor" ~/if` >
                          ~if $apiData["family"]["family_status"] neq null || $bEditView`
                            ~$apiData["family"]["family_status"]`
                          ~else`
                            Not filled in
                          ~/if`
                        </span>
                      </p>
                    </li>             
                    <li>
                      <p class="fontlig color12 pt15">Family Income</p>
                      <p class="pt2 pr20">
                        <span id="family_incomeView" ~if $bEditView && $apiData["family"]["family_income"] eq $notFilledInText`  class="color5" ~else if $apiData["family"]["family_income"] eq null` class="notFilledInColor" ~/if` >
                          ~if $apiData["family"]["family_income"] neq null || $bEditView`
                            ~$apiData["family"]["family_income"]`
                          ~else`
                            Not filled in
                          ~/if`
                        </span>
                      </p>
                    </li>
                    <li>
                      <p class="fontlig color12 pt15">Family Type</p>
                      <p class="pt2 pr20">
                        <span id="family_typeView" ~if $bEditView && $apiData["family"]["family_type"] eq $notFilledInText`  class="color5" ~else if $apiData["family"]["family_type"] eq null` class="notFilledInColor" ~/if` >
                          ~if $apiData["family"]["family_type"] neq null || $bEditView`
                            ~$apiData["family"]["family_type"]`
                          ~else`
                            Not filled in
                          ~/if`
                        </span>
                      </p>
                    </li>
                    <li>
                      <p class="fontlig color12 pt15">Family Values</p>
                      <p class="pt2 pr20">
                        <span id="family_valuesView" ~if $bEditView && $apiData["family"]["family_values"] eq $notFilledInText`  class="color5" ~else if $apiData["family"]["family_values"] eq null` class="notFilledInColor" ~/if` >
                          ~if $apiData["family"]["family_values"] neq null || $bEditView`
                            ~$apiData["family"]["family_values"]`
                          ~else`
                             Not filled in
                          ~/if`
                        </span>
                      </p>
                    </li>

                    <li>
                      <p class="fontlig color12 pt15" id="ancestral_originLabelParent">
                        Family based out of 
                        ~if $bEditView`
                          <span class="~if ($editApi.Details.ANCESTRAL_ORIGIN.value|count_characters:true) eq 0 || $editApi.Details.ANCESTRAL_ORIGIN.screenBit neq 1` disp-none ~/if` js-undSecMsg"> 
                            <span class="disp_ib color5 f13" > Under Screening</span>
                          </span>
                        ~/if`
                      </p>
                      <p class="pt2 pr20">
                        <span id="native_placeView" ~if $bEditView && $apiData["family"]["native_place"] eq $notFilledInText`  class="color5" ~else if $apiData["family"]["native_place"] eq null` class="notFilledInColor" ~/if` >
                          ~if $apiData["family"]["native_place"] neq null || $bEditView`
                            ~$apiData["family"]["native_place"]`
                          ~else`
                            Not filled in
                          ~/if`
                        </span>
                      </p>
                    </li>
                  </ul>
                </div>
                <div class="txtr clearfix pt20 js-familyView">
                  <ul class="listn">
                  ~if $apiData["family"]["living"] neq null || $bEditView`
                    <li> <span class="disp_ib sprite2 prfic13"></span> 
                      <span class="disp_ib colr2" id="livingView" ~if $bEditView && $apiData["family"]["living"] eq $notFilledInText`  class="color5" ~/if` >
                        ~$apiData["family"]["living"]`
                      </span> 
                    </li>
                    ~/if`
                  </ul>
                </div>
                ~if $bEditView`
                <!--start:Edit Family -->
                <div class="pl30 ceditform" id="familyEditForm"><!---Edit Form--></div>
                <!--end:Edit LifeStyle -->
                ~/if`
              </div>
              </div>
              <!--end:Family Details-->
