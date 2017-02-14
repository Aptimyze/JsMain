~assign var=zedoValue value= $sf_request->getAttribute('zedo')`
~assign var=zedo value= $zedoValue["zedo"]`
<div id="FTU" >
    <div class="mainwid container pb30">
		<div class="mauto pt20 pb30 txtc wid725">
            <p class="fontthin color11 f24">Welcome~if $nameOfUser` ~$nameOfUser`~/if`! Let's get started</p>
            <p class="myjs-colr4 fontlig f18 opa80 pt10">We are glad to have you as a member of Jeevansathi where lakhs of people connect with each other to discover their perfect match!</p>    
        </div>
		<div class="myjs-bg2 fullwid fontlig color11">
        	<div class="myjs-p12">
            	                <!--start:edit basic-->
                               ~if $computeImportantSection eq 1`
                        <div>
                            <div class="pt30 pb40 pr6 pl6 myjs-bdr7">
                                <p class="f22">Important Fields</p>
                                <div class="f17 pt15">Jeevansathi will allow editing of below mentioned fields only once in 30 days of registration, you filled these details : </div>
                                <ul id="basicList" class="f17 pt15 basicList">
                                            <li>
                                            <div class="wid122 disp_ib">Gender</div>:
                                        <div id="basicGender" class="disp_ib fontreg">~$FTUdata['gender']`</div>
                                    </li>
                                    <li>
                                            <div class="wid122 disp_ib">Date of Birth</div>:
                                        <div id="basicDob" class="disp_ib fontreg">~$FTUdata['DOB']['day']`&nbsp;~$FTUdata['DOB']['month']`&nbsp;~$FTUdata['DOB']['year']`</div>
                                    </li>
                                    <li>
                                            <div class="wid122 disp_ib">Marital Status</div>:
                                        <div id="basicStatus" class="disp_ib fontreg">~$FTUdata['maritalStatus']`</div>
                                    </li>
                                    <li>
                                            <div class="wid122 disp_ib">Religion</div>:
                                        <div id="basicReligion" class="disp_ib fontreg">~$FTUdata['religion']`</div>
                                    </li>
                                </ul>
                                <div class="f17 pt15 mb36">Please make correction NOW if you think you have wrongly filled these details</div> 
                                <a class="colrw" href="/profile/dpp">
                                    <div class="pos-rel myjs-wid21 scrollhid"><button class="bg_pink brdr-0 myjs-wid21 pinkRipple hoverPink  txtc colrw lh50 f17 fontreg cursp">Edit Basic Details</button></div>
                                </a>
                            </div>
                        </div>
                         ~/if`
                        <!--end:edit basic--> 
                        <!--start:upload photo-->
                ~if $profilePic eq "N"`
                <div class="pt30 pb40 pr6 pl6 myjs-bdr7">
                  <p class="f22">Upload your photos</p>
                  <p class="f17 pt15">Profile with photos get 8 times more responses</p>
                    <div class="clearfix pt30">
                        <div class="fl wid25p">
                            <img style="width:220px" src="~$photoUrl`">
                        </div>
                        <div class="fl wid70p ml10 pt25">
                            <p class="f22">Upload photos from</p>
                            <ul class="hor_list clearfix pt30">
                                <li class="cursp">
                                <a href='/social/addPhotos?uploadType=C'><div class="bg_pink disp-tbl hgt50 myjs-wid21">
                                        <div class="disp-cell vmid txtc myjs-bg6 wid20p">
                                            <div class="sprite2 myjs-ic9 mauto"></div>
                                        </div>
                                        <div class="disp-cell vmid wid80p txtc f20 fontrobbold colrw">My computer</div>                                 
                                    </div></a>
                                </li>
                                <li class="cursp ml30">
                                    <a href='/social/addPhotos?uploadType=F'><div class="myjs-bg7 disp-tbl hgt50 myjs-wid21">
                                        <div class="disp-cell vmid txtc myjs-bg8 wid20p">
                                            <div class="sprite2 myjs-ic10 mauto"></div>
                                        </div>
                                        <div class="disp-cell vmid wid80p txtc f20 fontrobbold colrw">facebook</div>                                  
                                    </div></a>
                                </li>
                            </ul>
                            <p class="pt35 f15">Strong Photo Privacy Options | No downloads allowed | Photos are Watermarked</p>
                            <p class="pt6 f15">Jpeg, Jpg | Upto 6MB | 20 photos only</p>
                        </div>
                    </div>                
                </div>
                ~/if`
                <!--end:upload photo-->

                <!--start:manage filters-->
                <div>
                	<div class="pt30 pb40 pr6 pl6 myjs-bdr7">
                    	<p class="f22">Manage Filters</p>
                		<p style="margin-bottom:36px;" class="f17 pt15">You may choose to receive interests only from people who match your preferences. Setting filters restricts unwanted interests in your inbox. However, you may access them through your filtered folder </p>
                       <a class="colrw" href="/profile/dpp"><div class="pos-rel myjs-wid21 scrollhid"><button class="bg_pink brdr-0 myjs-wid21 pinkRipple hoverPink  txtc colrw lh50 f17 fontreg cursp">Set my Filters</button></div></a>
                    </div>                
                </div>
               <!--end:manage filters-->
              
                <!--start:Privacy-->
                <div>
                	<div class="pt30 pb40 pr6 pl6 myjs-bdr7">
                    	<p class="f22">Privacy</p>
                		<p style="margin-bottom:36px;" class="f17 pt15">Control who all can view your profile, photo or contact information. Your details are visible to all by default (recommended) </p>
                        <a class="colrw" href="/settings/jspcSettings?visibility=1"><div class="myjs-wid21 pos-rel scrollhid"><button id="buttonPrivacyFtu" class="bg_pink brdr-0 myjs-wid21 txtc pinkRipple hoverPink colrw lh50 f17 fontreg  cursp">Go to Privacy Settings</button></div></a>
                    </div>                
                    </div>  
                     <!--end:Privacy-->
                       <div>
                       ~if $membershipStatus eq 'Free'` 
                  ~if $schedule_visit_widget eq 1 && $scheduleVisitCount eq 0`
                  <div class="pt30 pb40 pr6 pl6 myjs-bdr7">~else`<div class="pt30 pb40 pr6 pl6">~/if`
                      <p class="f22">Premium Membership</p>
                    <p style="margin-bottom:36px;" class="f17 pt15">Becoming a premium member on Jeevansathi allows you to view contact numbers/Emails of the profiles you like as well as chat with online profiles </p>
                       <a class="colrw" href="/profile/mem_comparison.php"><div class="pos-rel scrollhid myjs-wid21"><button class="bg_pink brdr-0 myjs-wid21 txtc colrw lh50 f17 fontreg  cursp">Upgrade Now</button></div></a>
                    </div>                
                </div>
                ~/if`
                     <!--start:Personal Verification-->
                     ~if $schedule_visit_widget eq 1 && $scheduleVisitCount eq 0`   
                      
                      <div>
                        <div id="schedule_visit"class="pt30 pb20 pr6 pl6">
                            <p class="f22">Personal Verification</p>
                            <p style="margin-bottom:36px;" class="f17 pt15">Meet our representative to help you understand your account better and verify your important details. Your representative would connect with you soon  </p>
                            <div class="pos-rel myjs-wid21 scrollhid"> <button class="bg_pink brdr-0 myjs-wid21 txtc colrw lh50 f17 fontreg cursp pinkRipple hoverPink " id="schedule_visit_action" onclick="scheduleVisit(); return false;">Request a Visit</button></div>
                        </div>                
                      </div>
                    <!--end:Personal Verification-->
                    ~/if`    
                </div>
               
                
            </div>
            </div>
            <!--start:article-->
    <article id="DESIREDPARTNERMATCHES"> 
      <!--start:div-->
      <div class="pt30 clearfix fontlig">
        <div class="fl f22 color11">Here are few matches for you </div>
        <div class="fr f16 pt8"><a href="#" class="color12 icons myjs-ic1 pr15">See All</a> </div>
      </div>
      <!--end:div--> 
      <!--start:slider-->
      <div class="pt15">
        <div class="pos-rel">          
          <div class="fullwid scrollhid">
           <div class="pos-rel li-slide3">
              <ul class="hor_list clearfix myjslist boxslide pos-rel" id="js-slide3">
                <li>
                  <div class="bg-white" style="width:220px; height:320px; overflow:hidden">
                  </div>
                </li>
              <li>
                  <div class="bg-white" style="width:220px; height:320px; overflow:hidden">
                  </div>
                </li>
                <li>
                  <div class="bg-white" style="width:220px; height:320px; overflow:hidden">
                  </div>
                </li>
                <li>
                  <div class="bg-white" style="width:220px; height:320px; overflow:hidden">
                  </div>
                </li>   
                <li>
                  <div class="bg-white" style="width:220px; height:320px; overflow:hidden">
                  </div>
                </li>
              <li>
                  <div class="bg-white" style="width:220px; height:320px; overflow:hidden">
                  </div>
                </li>
                <li>
                  <div class="bg-white" style="width:220px; height:320px; overflow:hidden">
                  </div>
                </li>
                <li>
                  <div class="bg-white" style="width:220px; height:320px; overflow:hidden">
                  </div>
                </li>   
                <li>
                  <div class="bg-white" style="width:220px; height:320px; overflow:hidden">
                  </div>
                </li>           
              </ul>
            </div>            
          </div>          
           <i class="pos-abs sprite2 myjs-ic2 myjs-pos3 scntrl cursp" id="prv-slide3"></i> <i class="pos-abs sprite2 myjs-ic3 myjs-pos4 scntrl cursp" id="nxt-slide3"></i>
        </div>
      </div>
      <!--end:slider-->       
    </article>
    <!--end:article-->  
    <p style='text-indent:15px;' class="fontlig f17 color11 pt30">You can also discover people based on your preferences with Search. <a class="colr5 fontreg f20" href ="/search/AdvancedSearch">Take Me to Search</a></p>

    <!--start:div-->
    <div class="txtc bg-4 pt20 pb20" id="zt_~$zedo['masterTag']`_bottom"> </div>
     
    </div>
    <!--end:div--> 

	</div>
</div>

