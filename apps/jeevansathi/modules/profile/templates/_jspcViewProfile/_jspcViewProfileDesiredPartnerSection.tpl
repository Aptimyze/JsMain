<!--start:Desired Partner-->
              <div class="prfp7 fontlig" id="section-d">
                <div class="clearfix"> <i class="sprite2 fl prfic26 mt6"></i>
                  <div class="fl colr5 pl8 f17 pt3">Desired Partner</div>
                </div>
                <div class="pl32 color11 f15">
                  ~if $apiData["dpp"]["about_partner"]`
                  <p class="pt30 aboutDppText">~$apiData["dpp"]["about_partner"]`</p>
                  ~/if`
                  ~if $loginProfile->getPROFILEID()`
                  <!--start:div-->
                  <div class="clearfix pos-rel mt30 js-checkMatch">
                    <div class="pos-abs fullwid prfpos4 prfbr4 prfwid5"></div>
                    <div class="fr prfwid10" > <span class="disp_ib colr5 bg-white pos-rel z2 txtc prfwid6">~if $apiData["about"]["gender"] eq "Female"`Her~else`His~/if` Preference</span> <span class="disp_ib color11 bg-white pos-rel z2 txtc prfwid8 prfm1"><span class="js-matching"></span> of <span class="js-total"></span> matching</span> <span class="disp_ib colr5 bg-white pos-rel z2 txtc prfwid9 ml62">Matches You</span> </div>
                  </div>
                  <!--end:div--> 
                  ~/if`
                  <!--start:div-->
                  <div class="f15 prfdplist mt30">
                    <ul>
                      ~if $apiData["dpp"]["dpp_age"] neq null`
                      <li class="js-countfields">
                        <p class="disp_ib">Age</p>
                        <p class="disp_ib">~$apiData["dpp"]["dpp_age"]`</p>
                        <p class="disp_ib js-hideMatch"><i class="sprite2 ~if $matchingFields['AGE'] eq 'gnf'`prfic27~else`prfic28~/if`"></i></p>
                      </li>
                      ~/if`
                      ~if $apiData["dpp"]["dpp_height"] neq null`
                      <li class="js-countfields">
                        <p class="disp_ib">Height</p>
                        <p class="disp_ib">~$apiData["dpp"]["dpp_height"]`</p>
                        <p class="disp_ib js-hideMatch"><i class="sprite2 ~if $matchingFields['HEIGHT'] eq 'gnf'`prfic27~else`prfic28~/if`"></i></p>
                      </li>
                      ~/if`
                      ~if $apiData["dpp"]["dpp_marital_status"] neq null`
                      <li class="js-countfields">
                        <p class="disp_ib">Marital Status</p>
                        <p class="disp_ib">~$apiData["dpp"]["dpp_marital_status"]`</p>
                        <p class="disp_ib js-hideMatch"><i class="sprite2 ~if $matchingFields['MSTATUS'] eq 'gnf'`prfic27~else`prfic28~/if`"></i></p>
                      </li>
                      ~/if`
                      ~if $apiData["dpp"]["dpp_have_children"] neq null`
                      <li class="js-countfields">
                        <p class="disp_ib">Have Children</p>
                        <p class="disp_ib">~$apiData["dpp"]["dpp_have_children"]`</p>
                        <p class="disp_ib js-hideMatch"><i class="sprite2 ~if $matchingFields['HAVECHILD'] eq 'gnf'`prfic27~else`prfic28~/if`"></i></p>
                      </li>
                      ~/if`
                      ~if $apiData["dpp"]["dpp_country"] neq null`
                      <li class="js-countfields">
                        <p class="disp_ib">Country</p>
                        <p class="disp_ib moredes">~$apiData["dpp"]["dpp_country"]`</p>
                        <p class="disp_ib js-hideMatch"><i class="sprite2 ~if $matchingFields['COUNTRYRES'] eq 'gnf'`prfic27~else`prfic28~/if`"></i></p>
                      </li>
                      ~/if`
                      ~if $apiData["dpp"]["dpp_city"] neq null`
                      <li class="js-countfields">
                        <p class="disp_ib">State/City</p>
                        <p class="disp_ib moredes">~$apiData["dpp"]["dpp_city"]`</p>
                        <p class="disp_ib js-hideMatch"><i class="sprite2 ~if $matchingFields['CITYRES'] eq 'gnf'`prfic27~else`prfic28~/if`"></i></p>
                      </li>
                      ~/if`
                      ~if $apiData["dpp"]["dpp_religion"] neq null`
                      <li class="js-countfields">
                        <p class="disp_ib">Religion</p>
                        <p class="disp_ib moredes">~$apiData["dpp"]["dpp_religion"]`</p>
                        <p class="disp_ib js-hideMatch"><i class="sprite2 ~if $matchingFields['RELIGION'] eq 'gnf'`prfic27~else`prfic28~/if`"></i></p>
                      </li>
                      ~/if`
                      ~if $apiData["dpp"]["dpp_caste"] neq null`
                      ~assign var = "pogRel" value = $apiData["lifestyle"]["religion_value"]`
                      <li class="js-countfields">
                        <p class="disp_ib">~if $pogRel eq '2' || $pogRel eq '3'`Sect~else`Caste~/if`</p>
                        <p class="disp_ib moredes">~$apiData["dpp"]["dpp_caste"]`</p>
                        <p class="disp_ib js-hideMatch"><i class="sprite2 ~if $matchingFields['CASTE'] eq 'gnf' || $matchingFields['SECT'] eq 'gnf'`prfic27~else`prfic28~/if`"></i></p>
                      </li>
                      ~/if`
                      ~if $apiData["dpp"]["dpp_mtongue"] neq null`
                      <li class="js-countfields">
                        <p class="disp_ib">Mother tongue</p>
                        <p class="disp_ib moredes">~$apiData["dpp"]["dpp_mtongue"]`</p>
                        <p class="disp_ib js-hideMatch"><i class="sprite2 ~if $matchingFields['MTONGUE'] eq 'gnf'`prfic27~else`prfic28~/if`"></i></p>
                      </li>
                      ~/if`
                      ~if $apiData["dpp"]["dpp_manglik"] neq null`
                      <li class="js-countfields">
                        <p class="disp_ib">Manglik</p>
                        <p class="disp_ib moredes">~$apiData["dpp"]["dpp_manglik"]`</p>
                        <p class="disp_ib js-hideMatch"><i class="sprite2 ~if $matchingFields['MANGLIK'] eq 'gnf'`prfic27~else`prfic28~/if`"></i></p>
                      </li>
                      ~/if`
                      ~if $apiData["dpp"]["dpp_edu_level"] neq null`
                      <li class="js-countfields">
                        <p class="disp_ib">Education </p>
                        <p class="disp_ib moredes">~$apiData["dpp"]["dpp_edu_level"]`</p>
                        <p class="disp_ib js-hideMatch"><i class="sprite2 ~if $matchingFields['ELEVEL_NEW'] eq 'gnf'`prfic27~else`prfic28~/if`"></i></p>
                      </li>
                      ~/if`
                      ~if $apiData["dpp"]["dpp_occupation"] neq null`
                      <li class="js-countfields">
                        <p class="disp_ib">Occupation </p>
                        <p class="disp_ib moredes">~$apiData["dpp"]["dpp_occupation"]`</p>
                          
                        <p class="disp_ib js-hideMatch"><i class="sprite2 ~if $matchingFields['OCCUPATION'] eq 'gnf'`prfic27~else`prfic28~/if`"></i></p>
                      </li>
                      ~/if`
                      ~if $apiData["dpp"]["dpp_earning"] neq null`
                      <li class="js-countfields">
                        <p class="disp_ib">Income </p>
                        <p class="disp_ib moredes">~$apiData["dpp"]["dpp_earning"]`</p>
                        <p class="disp_ib js-hideMatch"><i class="sprite2 ~if $matchingFields['INCOME'] eq 'gnf'`prfic27~else`prfic28~/if`"></i></p>
                      </li>
                      ~/if`
                      ~if $apiData["dpp"]["dpp_diet"] neq null`
                      <li class="js-countfields">
                        <p class="disp_ib">Diet </p>
                        <p class="disp_ib moredes">~$apiData["dpp"]["dpp_diet"]`</p>
                        <p class="disp_ib js-hideMatch"><i class="sprite2 ~if $matchingFields['DIET'] eq 'gnf'`prfic27~else`prfic28~/if`"></i></p>
                      </li>
                      ~/if`
                      ~if $apiData["dpp"]["dpp_smoke"] neq null`
                      <li class="js-countfields">
                        <p class="disp_ib">Smoke </p>
                        <p class="disp_ib moredes">~$apiData["dpp"]["dpp_smoke"]`</p>
                        <p class="disp_ib js-hideMatch"><i class="sprite2 ~if $matchingFields['SMOKE'] eq 'gnf'`prfic27~else`prfic28~/if`"></i></p>
                      </li>
                      ~/if`
                      ~if $apiData["dpp"]["dpp_drink"] neq null`
                      <li class="js-countfields">
                        <p class="disp_ib">Drink </p>
                        <p class="disp_ib moredes">~$apiData["dpp"]["dpp_drink"]`</p>
                        <p class="disp_ib js-hideMatch"><i class="sprite2 ~if $matchingFields['DRINK'] eq 'gnf'`prfic27~else`prfic28~/if`"></i></p>
                      </li>
                      ~/if`
                      ~if $apiData["dpp"]["dpp_complexion"] neq null`
                      <li class="js-countfields">
                        <p class="disp_ib">Complexion </p>
                        <p class="disp_ib moredes">~$apiData["dpp"]["dpp_complexion"]`</p>
                        <p class="disp_ib js-hideMatch"><i class="sprite2 ~if $matchingFields['COMP'] eq 'gnf'`prfic27~else`prfic28~/if`"></i></p>
                      </li>
                      ~/if`
                      ~if $apiData["dpp"]["dpp_btype"] neq null`
                      <li class="js-countfields">
                        <p class="disp_ib">Body Type </p>
                        <p class="disp_ib moredes">~$apiData["dpp"]["dpp_btype"]`</p>
                        <p class="disp_ib js-hideMatch"><i class="sprite2 ~if $matchingFields['BTYPE'] eq 'gnf'`prfic27~else`prfic28~/if`"></i></p>
                      </li>
                      ~/if`
                      ~if $apiData["dpp"]["dpp_handi"] neq null`
                      <li class="js-countfields">
                        <p class="disp_ib">Challenged </p>
                        <p class="disp_ib moredes">~$apiData["dpp"]["dpp_handi"]`</p>
                        <p class="disp_ib js-hideMatch"><i class="sprite2 ~if $matchingFields['HANDI'] eq 'gnf'`prfic27~else`prfic28~/if`"></i></p>
                      </li>
                      ~/if`
                    </ul>
                  </div>
                  <!--end:div--> 
                </div>
              </div>
              <!--end:Desired Partner--> 