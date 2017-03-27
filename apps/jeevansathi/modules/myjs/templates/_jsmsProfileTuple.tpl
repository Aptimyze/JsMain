~if $profileTuple neq ''`
        <div style="margin-right:10px; display: inline-block;margin-left:0px; position:relative;" id="~$section`tuple_~$index`">
           <input class="proChecksum" type="hidden" value="~$profileTuple.profilechecksum`" />
    
            <img class="srp_box2 contactLoader" style="position:absolute;display:none; top:65px; ">
   
                 <div class="bg4" style="overflow-x: hidden; " id="hideOnAction">
                     <a id="detailedProfileRedirect" href="~$SITE_URL`/profile/viewprofile.php?profilechecksum=~$profileTuple.profilechecksum`&~if $section eq "eoi"`responseTracking=~JSTrackingPageType::MYJS_EOI_JSMS`~else`stype=~SearchTypesEnums::MATCHALERT_MYJS_JSMS`~/if`&total_rec=~$total`&actual_offset=~$index+1`&contact_id=~$contactId`" > 
                         <div class="pad16" style="overflow:hidden;height:140px;" >
                             <div style="overflow-x:hidden; height:100%;">
                        <div style="white-space: nowrap; word-wrap:normal; width:100%;" class="overflowWrap">
                         <div class="fl"> <img class="tuple_image" style="height:110px; width:110px;" src=~if $gender eq 'M'`'/images/picture/120x120_f.png'~else`'/images/picture/120x120_m.png'~/if` data-src="~$profileTuple.photo.url`" border="0"/> </div>
                         <div class="fl pl_a" style="width:48%">
                             <div class="f14 color7"> <span class="username">~$profileTuple.username`</span></div>
                            <div class="attr">
                              <ul>
                                  <li class="textTru"><span class="tuple_title">~$profileTuple.tuple_title_field`</span> </li>
                                  <li class="textTru"><span class="tuple_age">~$profileTuple.age`</span> Years  <span class="tuple_height"> ~$profileTuple.height`</span> </li>
                                  <li class="textTru"><span class="tuple_caste" style="white-space: nowrap;">~$profileTuple.caste`</span></li>
                                  <li class="textTru"><span class="tuple_mtongue">~$profileTuple.mtongue`</span> </li> 
                                  ~if $gender eq 'M'`<li class="textTru"><span class="tuple_education">~$profileTuple.education`</span></li>~else`
                                  <li class="textTru"><span class="tuple_income">~$profileTuple.income`</span></li>~/if`
                              </ul>
                            </div>
                         </div>
                         <div class="clr"></div>
                         </div></div>
                         </div> </a>
                              
                              
                  ~if $section eq 'eoi'`     
                    
                 <div class="brdr8 fullwid" style="height:60px;">

                        <div class="txtc wid49p fl eoiAcceptBtn brdr7 pad2" index="~$index`"  >
                            <input class='inputProChecksum' type="hidden" value="~$profileTuple.profilechecksum`" />

                            <a class="f15 color2 fontreg">Accept</a>
                    </div>
                    <div class="txtc wid49p fl f15 pad2 eoiDeclineBtn"  index="~$index`">
                        <input class='inputProChecksum' type="hidden" value="~$profileTuple.profilechecksum`"/>
                         <a class="f15 color2 fontlig">Decline</a>
                    </div>
                    <div class="clr"></div>

                 </div>
~/if`
~if $section eq 'matchAlert'`     
                    
                 <div class="brdr8 fullwid" style="height:60px;">

                        <div class="txtc fullwid fl matchAlertBtn brdr7 pad2" index="~$index`"  >
                            <input class='inputProChecksum' type="hidden" value="~$profileTuple.profilechecksum`" />

                            <a class="f15 color2 fontreg">Send Interest</a>
                    </div>
                    <div class="clr"></div>

                 </div>
~/if`

~if $section eq 'matchOfDay'`     
                    
                 <div class="brdr8 fullwid" style="height:60px;">

                        <div class="txtc fullwid fl matchOfDayBtn brdr7 pad2" index="~$index`"  >
                            <input class='inputProChecksum' type="hidden" value="~$profileTuple.profilechecksum`" />

                            <a class="f15 color2 fontreg">Send Interest</a>
                    </div>
                    <div class="clr"></div>

                 </div>
~/if`



             </div>
        </div>
~/if`
