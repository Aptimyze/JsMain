<!--start:srp box-->
<div class="js-searchTupleStructure" style="display:none;">
  <div class="srpprofbox mt25 js-tupleOuterDiv pos-rel" id="{tupleOuterDiv}">
    <div class="srpprofbox mt25 js-tupleOuterDiv  CEParent" id="{tupleOuterDiv}Tuple">
        <div class="bg-white fullwid clearfix {highlightedProfile}"> 
          <!--start:image-->
          <div class="fl pos-rel f14 fontlig js-searchTupleImage ~if !$loggedIn` loginLayerJspc loginAlbumSearch ~/if`" id="{tupleImage}" data="{album_count},{username},{profilechecksum},{hasAlbum}"> 
            <!--start:count-->
            <div class="pos-abs srppos1 {countDisplay}" >
              <div class="disp-tbl opaclr1 countwid1 brdr_rad1 colrw txtc">
                <div class="vmid disp-cell">{album_count}</div>
              </div>
            </div>
            {noPhotoDiv}
            <!--end:count--> 
            <div class="srpimghwParent scrollhid bgColorG">
            <img dsrc= "{searchTupleImage}" onload="" oncontextmenu="return false;" onmousedown="return false;" src="~$defaultImage`" class="vmid photoOfTuple srpimghw {hasAlbum}"/> 
            </div>
          </div>
          <!--end:image--> 
          <!--start:description-->
    <div class="srppad13 fl srpwid9 ~if !$loggedIn` loginLayerJspc  loginProfileSearch~/if`">
          <a href="" id="idP{profileNoId}" class="fullwid  cursp js-profileDesc" data="{profilechecksum}"  tupleOffset="{tupleOffset}"> 
            <!--start:name-->
            <div class="srpbdr4 fontlig clearfix pb3">
                <span class="sprtxt1 f24 usernameOfTuple">{username}</span>
                <span class="verified pos-rel {verificationSeal}">  
                        <i class="verIcon js-verificationPage {verificationSeal}"></i>
                        <div class="pos-abs verIcNo"><div class="verIcInnNo">{verificationCount}</div></div> 
                        <span class="hoverDiv js-verificationPage">
                            <div class="{showAadhaar}"><div class="f14 fontreg blueColor">Aadhaar</div>
                            <div class="f12 fontreg lightgrey z999 cursp pt5">Aadhaar Number is verified</div></div>
                            <div class="{showVisit}"><div class="f14 fontreg blueColor">Verified by visit</div>
                            <div class="f12 pt5 fontreg lightgrey {verificationSealDoc}">Documents provided:</div>
                            <ul id="appendDocs" class="f12 fontreg lightgrey {verificationSealDoc}">
                                    {verificationDocumentsList}
                            </ul>
                            <div class="f11 fontreg blueColor z999 cursp pt10 verKnowMore">Know More</div></div>
                        </span>
                </span>  
                <span id = "idG{profileNoId}" class="gunaScore-{profilechecksum} f17 fontreg sprtxt1 pt6 padl15 js-gunaScore"></span>
                <span class="statuson fr padt12">{userloginstatus}</span> 
            </div>
            <!--end:name--> 
            <!--start:listing-->
                <div class="pt10 fontlig colr4 clearfix ulinline">
                  <ul id="profileInfo{profileNoId}" class="fl f14 wid83p descplist">
                    <li class="textTru">{age},  {height}</li>
                    <li class="textTru">{edu_level_new}</li>
                    <li class="textTru">{location}</li>
                    <li class="textTru">{occupation}</li>
                    <li class="textTru">{religion}{caste}</li>
                    <li class="textTru">{income}</li>
                    <li class="textTru">{mtongue}</li>
                    <li class="textTru">{mstatus}</li>
                  </ul>
                  <div class="fr f12 colr5" style="position: absolute; float: right; right: 72px;">{subscription_icon}</div>
                </div>
                <div class="pt15">
                  <div id="studyAt" class="f15 colr_grey fontreg textTru colr_wht"> {StudiedAtDiv}</div>
                  <div id="workAt" class="f15 mt5 colr_grey fontreg textTru colr_wht"> {WorksAtDiv}</div>
                </div>
            </a>
            <!--end:listing--> 
            <!--<a href="~if !$loggedIn` javascript:void(0) ~else` /static/agentinfo ~/if`" class="{verificationSeal}">
                <div id="idVS{profileNoId}" class="pt30 fontlig js-verificationSeal">
                  <div class="f15 colr2 clearfix"> <i class="fl icons verficon {verificationSeal}"></i>
                    <div class="fl pt1 {verificationSeal}">Verified by visit</div> 	
                  </div>
                  <div class="color11 opa70 f12 pt5">{verificationDocumentsList}</div>
                </div>
            </a>-->
        </div>
          <!--end:description--> 
          
           <!--start:option-->
          <div class="srpwid8 fr tupleic contactEngineBar pcChatHelpData" data-pcChat="{orig_username},{profilechecksum},{gender}">
         {{contactEngineBar}}
          </div>
          <!--end:option--> 
        </div>
        <div id="idR{profileNoId}" class="clearfix pt7  cursp" data="{profilechecksum}">
                    <div class="f11 fontlig colr2 fl {showFilterReason} reasonForFilter">Reason for filtering you out : {filterReason}</div>
                    <div id="idRemove{profileNoId}" class="f11 fontlig color11 {removeThisProfile} txtr cursp fr ~if !$loggedIn` loginLayerJspc~else`js-removeProfile~/if`" data="{profilechecksum}" data-chat="{userId},BLOCK">Ignore This Profile</div>
                    <div id="idRemove{profileNoId}" class="f11 fontlig color11 js-removeProfile txtr cursp fr" data="{profilechecksum}">{joinedOnMsg}</div>
            </div>
    </div>
  </div>
  
</div>
<div id="searchTuplesMainDiv">
</div>
