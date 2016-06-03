<!--start:about us-->
              <div class="prfp5 prfbr3" id="section-about"> 
                ~if $bEditView`
                <div class="clearfix"> <i class="sprite2 fl edpic6"></i>
                  <div class="fl colr5 pl10 f17 pt2" id="yourinfoLabelParent">About me 
                    ~if $bEditView`
                    <span class="js-aboutView">
                      <span class=" ~if $bEditView && ($editApi.Details.YOURINFO.value|count_characters:true) eq 0 || $editApi.Details.YOURINFO.screenBit neq 1` disp-none ~/if` js-undSecMsg">
                        <span class="disp_ib color5 f13" > Under Screening</span>
                      </span>
                    </span>
                      
                    ~/if` </div>
                  <div class="fr pt4"><a class="color5 fontreg f15 js-editBtn cursp editableSections" data-section-id="about">Edit</a> </div>
                </div>
                ~/if`
                <div ~if $bEditView` class="pl30 pt30 prflist1 fontlig js-aboutView" ~else` class="pl27" ~/if`> 
                  <!--start:about profile-->
                  <p> <pre id="myinfoView" class="wordbreakwrap fontlig"> ~$apiData["about"]["myinfo"]`</pre> </p>
                  <!--end:about profile--> 
                  <!--start:about family-->
                  ~if !$bEditView && $apiData["about"]["gender"] eq "Female"`
                  <p class="fontlig color12 pt15">About her Family</p>
                  ~else if !$bEditView`
                  <p class="fontlig color12 pt15">About his Family</p>
                  ~else`
                  <p class="fontlig color12 pt15" id="familyinfoLabelParent" >About My Family 
                        <span class="~if ($editApi.Family.FAMILYINFO.value|count_characters:true) eq 0 || $editApi.Family.FAMILYINFO.screenBit neq 1` disp-none ~/if` js-undSecMsg">
                          <span class="disp_ib color5 f13" > Under Screening</span>
                        </span>
                   </p> 
                  ~/if`
                  ~if ~$apiData["family"]["myfamily"]` neq "" || $bEditView`
                  <p class="pt2">
                     <pre id="myfamilyView" ~if $bEditView && $apiData["family"]["myfamily"] eq $notFilledInText`  class="fontlig color5"~else`class="wordbreakwrap fontlig" ~/if` >~$apiData["family"]["myfamily"]`</pre>
                </p>
                  ~else`
                  <p class="pt2 notFilledInColor">Not filled in</p>
                  ~/if`
                  <!--end:about family--> 
                  <!--start:about Education-->
                  <p class="fontlig color12 pt15" id="educationLabelParent">Education 
                    ~if $bEditView`
                      <span class="~if ($editApi.Education.EDUCATION.value|count_characters:true) eq 0 || $editApi.Education.EDUCATION.screenBit neq 1` disp-none ~/if` js-undSecMsg">
                        <span class="disp_ib color5 f13" > Under Screening</span>
                      </span>
                    ~/if`
                  </p>
                  ~if ~$apiData["about"]["myedu"]` neq ""`
                  <p class="pt2">
                    <pre id="myeduView" ~if $bEditView && $apiData["about"]["myedu"] eq $notFilledInText`  class="fontlig color5"~else`class="wordbreakwrap fontlig" ~/if` >~$apiData["about"]["myedu"]`
                    </pre>
                  </p>
                  ~else`
                  <p class="pt2 notFilledInColor">Not filled in</p>
                  ~/if`
                  <!--end:about Education--> 
                  <!--start:about Occupation-->
                  <p class="fontlig color12 pt15" id="job_infoLabelParent">Occupation 
                    ~if $bEditView`
                      <span class="~if ($editApi.Career.JOB_INFO.value|count_characters:true) eq 0 || $editApi.Career.JOB_INFO.screenBit neq 1` disp-none ~/if` js-undSecMsg">
                        <span class="disp_ib color5 f13" > Under Screening</span>
                      </span>
                    ~/if`
                  </p>
                  </p>
                  ~if ~$apiData["about"]["mycareer"]` neq ""`
                  <p class="pt2">
                      <pre id="mycareerView" ~if $bEditView && $apiData["about"]["mycareer"] eq $notFilledInText`  class="fontlig color5"~else`class="wordbreakwrap fontlig" ~/if`>
                        ~$apiData["about"]["mycareer"]`
                      </pre>  
                  </p>
                  ~else`
                  <p class="pt2 notFilledInColor">Not filled in</p>
                  ~/if`
                  <!--end:about Occupation--> 
                </div>
                ~if $bEditView`
                <!--start:Edit Education & Career Details-->
                <div class="pl30 ceditform" id="aboutEditForm"><!---Edit Form--></div>
                <!--end:Edit Education & Career Details-->
                ~/if`
              </div>
              <!--end:about us-->