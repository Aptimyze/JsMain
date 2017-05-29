<!--start:Education-->
              <div class="prfbr3">
              <div class="prfp5 noMultiSelect" id="section-career">
                <div class="clearfix"> <i class="sprite2 fl prfic12"></i>
                  <div class="fl colr5 pl8 f17 pt2" >Education & Career</div>
                   <!--unclock div-->              
                   <div class="fr pt4 disp-none">
                  	<a class="icons colr5 f15 disp_b pl20 prfic35" href="#">Unlock Details</a>                  
                  </div>
                   ~if $bEditView`
                  <div class="fr pt4"><a  class="color5 fontreg f15 js-editBtn cursp editableSections" data-section-id="career">Edit</a> </div>
                  ~/if`
                  <!--unclock div-->
                </div>
                <div class="pl27 prflist1 js-careerView">
                  <ul class="clearfix">
                    <li>
                      <p class="color12 pt15">Highest Education</p>
                      <p class="pt2 pr20">
                        <span id="educationView" ~if $bEditView && $apiData["about"]["education"] eq $notFilledInText`  class="color5" ~else if $apiData["about"]["education"] eq null` class="notFilledInColor" ~/if` >
                          ~if $apiData["about"]["education"] neq null || $bEditView`
                            ~$apiData["about"]["education"]`
                          ~else`
                            Not filled in
                          ~/if`
                      </span>
                      </p>
                    </li>
                    <li>
                      <p class="color12 pt15" id="schoolLabelParent">School Name ~if $bEditView`
                        <span class="~if ($editApi.Education.SCHOOL.value|count_characters:true) eq 0 || $editApi.Education.SCHOOL.screenBit neq 1` disp-none ~/if` js-undSecMsg"> 
                          <span class="disp_ib color5 f13" > Under Screening</span>
                        </span>
                        ~/if`
                      </p>
                      <p class="pt2 pr20">
                        <span id="schoolView" ~if $bEditView && $apiData["about"]["school"] eq $notFilledInText`  class="color5" ~else if $apiData["about"]["school"] eq null` class="notFilledInColor" ~/if` >  
                          ~if $apiData["about"]["school"] neq null || $bEditView`
                            ~$apiData["about"]["school"]`
                          ~else`
                            Not filled in
                          ~/if`
                        </span>
                      </p>
                    </li>
                    ~if $apiData["about"]["non_grad"] neq 1 && $apiData["about"]["under_grad"] neq null || $bEditView`
                    <li>
                      <p class="color12 pt15">UG Degree</p>
                      <p class="pt2 pr20">
                        <span id="under_grad_degView" ~if $bEditView && $bEditView && $apiData["about"]["under_grad"] neq null &&$apiData["about"]["under_grad"]["deg"] eq $notFilledInText`  class="color5" ~else if $apiData["about"]["under_grad"]["deg"] eq null` class="notFilledInColor" ~/if` >  
                          ~if $apiData["about"]["under_grad"]["deg"] neq null || $bEditView`
                            ~if $bEditView && $apiData["about"]["under_grad"] eq null`
                                Not Applicable
                            ~else`
                              ~$apiData["about"]["under_grad"]["deg"]`
                            ~/if`
                            
                          ~else`
                            Not filled in
                            ~/if`
                        </span>  
                      </p>
                    </li>
                    ~/if`

                    ~if $apiData["about"]["education"] neq $apiData["about"]["under_grad"]["deg"] && $apiData["about"]["post_grad"] neq null || $bEditView`
                    <li>
                      <p class="color12 pt15">PG Degree</p>
                      <p class="pt2 pr20">
                        <span id="post_grad_degView" ~if $bEditView && $bEditView && $apiData["about"]["post_grad"] neq null &&$apiData["about"]["post_grad"]["deg"] eq $notFilledInText`  class="color5" ~else if $apiData["about"]["post_grad"]["deg"] eq null` class="notFilledInColor" ~/if` >  
                          ~if $apiData["about"]["post_grad"]["deg"] neq null || $bEditView`
                            ~if $bEditView && $apiData["about"]["post_grad"] eq null`
                                Not Applicable
                            ~else`
                              ~$apiData["about"]["post_grad"]["deg"]`
                            ~/if`
                            
                          ~else`
                            Not filled in
                            ~/if`
                        </span>  
                      </p>
                    </li>
                    ~/if`

                     ~if $apiData["about"]["non_grad"] neq 1 && $apiData["about"]["under_grad"] neq null || $bEditView`
                    <li>
                      <p class="color12 pt15" id="collegeLabelParent">UG College 
                        ~if $bEditView`
                        <span class="~if ($editApi.Education.COLLEGE.value|count_characters:true) eq 0 || $editApi.Education.COLLEGE.screenBit neq 1` disp-none ~/if` js-undSecMsg"> 
                          <span class="disp_ib color5 f13" > Under Screening</span>
                        </span>
                        ~/if`
                      </p>
                      <p class="pt2 pr20"  >
                        <span id="under_grad_collgView" ~if $bEditView &&  $bEditView && $apiData["about"]["under_grad"] neq null && $apiData["about"]["under_grad"]["name"] eq $notFilledInText`  class="color5" ~else if $apiData["about"]["under_grad"]["name"] eq null` class="notFilledInColor" ~/if` >
                         ~if $apiData["about"]["under_grad"]["name"] neq null || $bEditView`
                            ~if $bEditView && $apiData["about"]["under_grad"] eq null`
                              Not Applicable
                            ~else`
                              ~$apiData["about"]["under_grad"]["name"]`
                            ~/if`
                            
                          ~else`
                            Not filled in
                          ~/if`
                        </span>
                        
                      </p>
                    </li>
                    ~/if`

                    ~if $apiData["about"]["education"] neq $apiData["about"]["under_grad"]["deg"] && $apiData["about"]["post_grad"] neq null || $bEditView`
                    <li>
                      <p class="color12 pt15" id="pg_collegeLabelParent" >PG College ~if $bEditView`
                        <span class="~if ($editApi.Education.PG_COLLEGE.value|count_characters:true) eq 0 || $editApi.Education.PG_COLLEGE.screenBit neq 1` disp-none ~/if` js-undSecMsg"> 
                          <span class="disp_ib color5 f13" > Under Screening</span>
                        </span>
                        ~/if`
                      </p>
                      <p class="pt2 pr20" >
                         <span id="post_grad_collgView" ~if $bEditView &&  $bEditView && $apiData["about"]["post_grad"] neq null && $apiData["about"]["post_grad"]["name"] eq $notFilledInText`  class="color5" ~else if $apiData["about"]["post_grad"]["name"] eq null` class="notFilledInColor" ~/if` >
                          ~if $apiData["about"]["post_grad"]["name"] neq null || $bEditView`
                            ~if $bEditView && $apiData["about"]["post_grad"] eq null`
                              Not Applicable
                            ~else`
                              ~$apiData["about"]["post_grad"]["name"]`
                            ~/if`
                            
                          ~else`
                            Not filled in
                          ~/if`
                        </span>
                      </p>
                    </li>
                    ~/if`
                     ~if $apiData["about"]["non_grad"] neq 1 && $apiData["about"]["under_grad"] neq null || $bEditView`
                    <li>
                      <p class="color12 pt15" id="other_ug_degreeLabelParent">Other UG Degree ~if $bEditView`
                        <span class="~if ($editApi.Education.OTHER_UG_DEGREE.value|count_characters:true) eq 0 || $editApi.Education.OTHER_UG_DEGREE.screenBit neq 1` disp-none ~/if` js-undSecMsg"> 
                          <span class="disp_ib color5 f13" > Under Screening</span>
                        </span>
                        ~/if`
                      </p>
                      <p class="pt2 pr20" >
                        <span id="edit_other_ug_degreeView" ~if $bEditView && $bEditView && $apiData["about"]["under_grad"] neq null && $apiData["about"]["other_degree"]["other_ug_degree"] eq $notFilledInText`  class="color5" ~else if $apiData["about"]["other_degree"]["other_ug_degree"] eq null` class="notFilledInColor" ~/if` >
                          ~if $apiData["about"]["other_degree"]["other_ug_degree"] neq null || $bEditView`
                            ~if $bEditView && $apiData["about"]["under_grad"] eq null`
                              Not Applicable
                            ~else`
                              ~$apiData["about"]["other_degree"]["other_ug_degree"]`
                            ~/if`
                            
                          ~else`
                            Not filled in
                          ~/if`
                        </span>
                      </p>
                    </li>
                    ~/if`
                    ~if $apiData["about"]["education"] neq $apiData["about"]["under_grad"]["deg"] && $apiData["about"]["post_grad"] neq null || $bEditView`
                    <li>
                      <p class="color12 pt15" id="other_pg_degreeLabelParent">Other PG Degree 
                        ~if $bEditView`
                          <span class="~if ($editApi.Education.OTHER_PG_DEGREE.value|count_characters:true) eq 0 || $editApi.Education.OTHER_PG_DEGREE.screenBit neq 1` disp-none ~/if` js-undSecMsg"> 
                            <span class="disp_ib color5 f13" > Under Screening</span>
                          </span>
                        ~/if`
                      </p>
                      <p class="pt2 pr20" >
                        <span id="edit_other_pg_degreeView" ~if $bEditView && $bEditView && $apiData["about"]["post_grad"] neq null && $apiData["about"]["other_degree"]["other_pg_degree"] eq $notFilledInText`  class="color5" ~else if $apiData["about"]["other_degree"]["other_pg_degree"] eq null` class="notFilledInColor" ~/if` >
                          ~if $apiData["about"]["other_degree"]["other_pg_degree"] neq null || $bEditView`
                            ~if $bEditView && $apiData["about"]["post_grad"] eq null`
                              Not Applicable
                            ~else`
                              ~$apiData["about"]["other_degree"]["other_pg_degree"]`
                            ~/if`
                            
                          ~else`
                            Not filled in
                          ~/if`
                        </span>
                      </p>
                    </li>
                    ~/if`
                    <li>
                      <p class="color12 pt15">Occupation</p>
                      <p class="pt2 pr20">
                        <span id="occupationView" ~if $bEditView && $apiData["about"]["occupation"] eq $notFilledInText`  class="color5" ~else if $apiData["about"]["occupation"] eq null` class="notFilledInColor" ~/if` >
                          ~if $apiData["about"]["occupation"] neq null || $bEditView`
                            ~$apiData["about"]["occupation"]`
                          ~else`
                            Not filled in
                          ~/if`
                        </span>
                      </p>
                    </li>
                    <li>
                      <p class="color12 pt15">Work Status</p>
                    <p class="pt2 pr20">
                        <span id="edit_work_statusView" ~if $bEditView && $apiData["about"]["decorated_work_status"]["work_status"] eq $notFilledInText` class="color5" ~else if $apiData["about"]["decorated_work_status"]["work_status"] eq null` class="notFilledInColor" ~/if` >
                          ~if $apiData["about"]["decorated_work_status"]["work_status"] neq null || $bEditView`
                            ~$apiData["about"]["decorated_work_status"]["work_status"]`
                          ~else`
                            Not filled in
                          ~/if`
                        </span>
                      </p>
                    </li>
                    <li>
                      <p class="color12 pt15" id="company_nameLabelParent">Organization Name 
                      ~if $bEditView`
                        <span class="~if ($editApi.Career.COMPANY_NAME.value|count_characters:true) eq 0 || $editApi.Career.COMPANY_NAME.screenBit neq 1` disp-none ~/if` js-undSecMsg"> 
                          <span class="disp_ib color5 f13" > Under Screening</span>
                        </span>
                        ~/if`
                        </p>
                      <p class="pt2 pr20">
                        <span id="edit_company_nameView" ~if $bEditView && $apiData["about"]["work_status"]["company"] eq $notFilledInText`  class="color5" ~else if $apiData["about"]["work_status"]["company"] eq null` class="notFilledInColor" ~/if` >
                          ~if $apiData["about"]["work_status"]["company"] neq null || $bEditView`
                            ~$apiData["about"]["work_status"]["company"]` 
                          ~else`
                            Not filled in 
                          ~/if`
                        </span>
                      </p>
                    </li>
                    <li>
                      <p class="color12 pt15">Annual Income</p>
                      <p class="pt2 pr20">
                        <span id="incomeView1" ~if $bEditView && $apiData["about"]["income"] eq $notFilledInText`  class="color5" ~else if $apiData["about"]["income"] eq null` class="notFilledInColor" ~/if` >
                          ~if $apiData["about"]["income"] neq null || $bEditView`
                            ~$apiData["about"]["income"]`
                          ~else`
                            Not filled in
                          ~/if`
                        </span>
                      </p>
                    </li>
                  </ul>
                </div>
                <div class="txtr clearfix pt20 js-careerView">
                  <ul class="listn">
                    ~if ($apiData["about"]["abroad"] neq null || $bEditView)`
                    <li class="disp_ib"> <span class="disp_ib sprite2 prfic13"></span> 
                      <span class="disp_ib colr2" id="abroadView">
                        ~$apiData["about"]["abroad"]`
                      </span> 
                    </li>
                    ~/if`
                    ~if ($apiData["about"]["plan_to_work"] neq null || $bEditView) &&  $apiData["about"]["gender"] eq "Female"`
                    <li class="disp_ib pl20"> <span class="disp_ib sprite2 prfic13"></span> 
                      <span class="disp_ib colr2" id="plan_to_workView">
                        ~$apiData["about"]["plan_to_work"]`
                      </span> 
                    </li>
                    ~/if`
                  </ul>
                </div>
                ~if $bEditView`
                <!--start:Edit Education & Career Details-->
                <div class="pl30 ceditform" id="careerEditForm"><!---Edit Form--></div>
                <!--end:Edit Education & Career Details-->
                ~/if`
              </div>
              </div>
              <!--end:Education--> 
