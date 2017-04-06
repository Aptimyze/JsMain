<div class="txtc pt20 pb10 color11 disp-none ccSubHeader"></div>
<!--Start:Basic cc tuples Structure-->
<div class="js-ccTupleStructure disp-none">
	<div id="outerCCTupleDiv{ccTupleIDNo}">
		<div class="ccp2 fontlig color11" id="innerCCTupleDiv{ccTupleIDNo}">
			<div class="fullwid ccnb2">
				<ul class="listnone ccsublist">
				    <li class="clearfix pos-rel CEParent" id="detailccTupleDiv{ccTupleIDNo}" >
				    				<div id="cEButtonsContainer-{profilechecksum}-CC" class="ccopt2 ccwid3 pos-abs z1" style="top:0; right:0">
									{{contactEngineBar}}
									</div>
				    	<a href="/profile/viewprofile.php?profilechecksum={profilechecksum}&total_rec={total_rec}&actual_offset={actual_offset}&contact_id={contact_id}&contact={contact}&self={self}&self_profileid={self_profileid}&flag={flag}&type={type}&page={page}&{tracking}&tupleId={ccTupleIDNo}&fromPage={fromPage}&{NAVIGATOR}" class="js-colorParent js-urlCC">
							<div class="fl">
	                                                    <div class="ccdim1Parent scrollhid bgColorG">
								<img dsrc='{ccTupleImage}' class="ccdim1 vtop"/>
	                                                    </div>
							</div>
							<div class="fl ccwid5 pos-rel">
								<div class="fl f12 pl30 wid70p">
									<p class="pt13"><span class="js-username f17">{username}</span> <span class="js-userLoginStatus disp_ib color12 f11 pl10">{userloginstatus}</span></p>
									{innerTupleContent}
								</div>
								<div class="fr pos-rel">
									<div class="f10 txtr">
										<p class="pt19 cch1">{interest_viewed_data}</p>
										<p class="pt13 color5 f12 h20">{subscription_icon}</p>
										<p>{timeText}</p>                                
									</div>  
									  
								</div> 
							</div>
						</a>                        
					</li>
	
				</ul>
				<div class="fullwid clearfix">
					<div class="fr txtl ccnb1 pt10 pb17 f12 lh22 {personalizedmessageClass} js-hideDetail js-showDetail{profilechecksum}" >
							<p class="pt10 ccww">{personalizedmessage}</p>
					</div>                    
	       </div> 
				</div>
		</div>
	</div>
</div>
<!--End:Basic cc tuples Structure-->

<!--Start:Main cc tuples Structure-->
<div id="ccTuplesMainDiv" class="js-hmin">
</div>
<!--End:Main cc tuples Structure-->

<!--Start:Inner cc tuple Details Structure-->
<div id="innerTupleDetailsContent" class="disp-none">
	<div id="innerTupleDetailsContent{ccTupleIDNo}" class="hgt60">
	 <p class="pt13 textTru">{age},  {height},  {location},  {religion}{casteStr},  {mtongue}</p>
	 <p class="pt10 textTru">{edu_level_new},  {income},  {occupation},	{mstatus}</p>
	 <div class="color5 fontreg pt10 js-viewSimilarLink ccopt2 {handleVsp}" data="{profilechecksum},{username},{stype},{showContactedUsernameDetails}" id="jsCcVSP-{profilechecksum}">View Similar Profiles</div>
	</div>
</div>
<!--End:Inner cc tuple Message Structure-->

<!--Start:Inner cc tuple Details Structure-->
<div id="innerTupleMessageContent" class="disp-none">
	<div id="innerTupleMessageContent{ccTupleIDNo}" >
		<p class="f13 pt10 lh22 contactEngineIcon js-message" style="min-height:60px" id="WRITE_MESSAGE_LIST-{profilechecksum}-CC">{messageBody}</p> 
	</div>  
</div> 
<!--End:Inner cc tuple Message Structure-->  


<div class="disp-none" id="othermessagetuple">
<li class="clearfix pt30"> 
<div class="fl wid85p" id="othermessage{id}">
    <div class="fl pt5"><img dsrc='{otherimage}' class="ccdim3 vtop"></div>
    <div class="fl f12 lh20 pl15 wid90p">
    <div>{message}</div>
    <div class="f10 opa50">{time}</div>
    
    </div>
</div>
</li>
</div>
<div class="disp-none" id ="mymessagetuple">
<li class="clearfix pt30">    
<div class="fr wid85p" id="mymessage{id}">
    <div class="fr pt5"><img dsrc='{myimage}' class="ccdim3 vtop"></div>
    <div class="fr f12 lh20 pr15 wid90p"><div class="txtr">{message}</div> 
    <div class="f10 opa50 txtr">{time}</div></div>
</div>
</li>
</div>
~include_partial('global/JSPC/_jspcContactEngineButtons')`
