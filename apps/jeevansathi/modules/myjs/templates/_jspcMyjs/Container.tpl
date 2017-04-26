<div id="interestReceivedContainer" class="disp-none">
<div id="{{div_id}}">
<div class="clearfix fontlig" id="Intr_show">
<div class="fullwid scrollhid">
<div id="disp_{{list_id}}" class="pos-rel li-slide1">
<ul id ="js-{{list_id}}" class="myjs-fulwid hor_list clearfix boxslide myjslist3 pos-rel">
{{INNER_HTML}}
</ul>
</div>
</div>
<div class="clr"></div>
<div class="pt25">
<div class="pos-rel clearfix fontlig">
    <div id="seeAllId_INTERESTRECEIVED" class="pos-abs wid100 txtc myjs-pos8 disp-none"><a href="/inbox/1/1" class="color12">View All <span id='seeAllIntCount'>{{SEE_ALL_TOTAL}}</span></a></div>
<div class="fr myjs-wid8 clearfix opa50">
<div id='panelCounter_INTERESTRECEIVED' class="fl color12 pt5 disp-none"><span id="slideCurrent{{type}}">1 </span> of <span id="slideTotal{{type}}">{{TOTAL_NUM}}</span> </div>
<div id='arrowKeys_INTERESTRECEIVED' class="fr opa50"> <a id="prv-{{list_id}}" class="sprite2 myjs-ic7 disp_ib"></a> <a id="nxt-{{list_id}}" class="sprite2 myjs-ic8 disp_ib myjs-m3"></a> </div>
</div>
</div>
</div>

</div>
<!--end:content for Interest received-->
</div>
</div>
<!--end:engagement container section-->

<div id="filteredInterestContainer" class="disp-none">
<div id="{{div_id}}">
<div class="clearfix fontlig" id="filteredIntr_show">
<div class="fullwid scrollhid">
<div id="disp_{{list_id}}" class="pos-rel li-slide1">
<ul id ="js-{{list_id}}" class="myjs-fulwid hor_list clearfix boxslide myjslist3 pos-rel" style="width:100%">
{{INNER_HTML}}
</ul>
</div>
</div>
<div class="clr"></div>
<div class="pt25">
<div class="pos-rel clearfix fontlig">
    <div id="seeAll_FILTEREDINTEREST_List" class="pos-abs wid100 txtc myjs-pos8 disp-none"><a href="/inbox/12/1" class="color12">View All <span id='seeAllFilteredCount'>{{SEE_ALL_TOTAL}}</span></a></div>
<div class="fr myjs-wid8 clearfix opa50">
<div id='panelCounter_FILTEREDINTEREST' class="fl color12 pt5 disp-none"><span id="slideCurrent{{type}}">1 </span> of <span id="slideTotal{{type}}">{{TOTAL_NUM}}</span> </div>
<div id='arrowKeys_FILTEREDINTEREST' class="fr opa50"> <a id="prv-{{list_id}}" class="sprite2 myjs-ic7 disp_ib"></a> <a id="nxt-{{list_id}}" class="sprite2 myjs-ic8 disp_ib myjs-m3"></a> </div>
</div>
</div>
</div>

</div>
<!--end:content for Interest received-->
</div>
</div>

<div id="expiringInterestContainer" class="disp-none">
<div id="{{div_id}}">
<div class="clearfix fontlig" id="expiringIntr_show">
<div class="fullwid scrollhid">
<div id="disp_{{list_id}}" class="pos-rel li-slide1">
<div id="engBarInfoMessage" class="txtc fontlig f13 engBarInfoMsg" style="position: relative;padding: 0px 27px 20px 27px;width: 850px;"></div>
<ul id ="js-{{list_id}}" class="myjs-fulwid hor_list clearfix boxslide myjslist3 marRightNew pos-rel" style="width:100%">
{{INNER_HTML}}
</ul>
</div>
</div>
<div class="clr"></div>
<div class="pt25">
<div class="pos-rel clearfix fontlig">
    <div id="seeAll_EXPIRINGINTEREST_List" class="pos-abs wid100 txtc myjs-pos8 disp-none">
    
    <a href="/inbox/23/1" class="color12">View All <span id='seeAllExpiringCount'>{{SEE_ALL_TOTAL}}</span></a></div>
<div class="fr myjs-wid8 clearfix opa50">
<div id='panelCounter_EXPIRINGINTEREST' class="fl color12 pt5 disp-none"><span id="slideCurrent{{type}}">1 </span> of <span id="slideTotal{{type}}">{{TOTAL_NUM}}</span> </div>
<div id='arrowKeys_EXPIRINGINTEREST' class="fr opa50"> <a id="prv-{{list_id}}" class="sprite2 myjs-ic7 disp_ib"></a> <a id="nxt-{{list_id}}" class="sprite2 myjs-ic8 disp_ib myjs-m3"></a> </div>
</div>
</div>
</div>

</div>
<!--end:content for Expiring Interest -->
</div>
</div>

<!--container for photoRequest-->
<!--start:content for request-->
		<div id="photoRequestContainer" class="disp-none">
        <div id={{div_id}} class="clearfix fontlig" id="Acc_show">
         	<div id={{p_id}} class="txtc f20 fontlig color11 pt40">{{HEADING}}</div>   
            <div class="mauto myjs-wid9">
             <ul id={{list_id}} class="hor_list clearfix mysj-btmwid pt45">
              {{INNER_HTML}}     
        	</ul>
            </div>  
            ~if $profilePic eq "N"`
            <div id="upload{{list_id}}" class="mt40">
            	<div class="mauto bg5 txtc lh40 wid200">
                	<a href="/social/addPhotos" class="colrw fontlig">UPLOAD PHOTO</a>
                </div>            
            </div> 
            ~/if`
        </div>
    </div>
        <!--end:content for request--> 


<!--start:large container section-->
<div id="largeContainer" class="disp-none">
<article id="{{div_id}}" style="display: block;">
<!--start:div-->
<div id={{p_id}} class="pt40 clearfix fontlig">
<div class="fl f22 color11">{{HEADING}}  <span class="fontreg colr5 countNumber"  id = {{count_results_id}} >{{COUNT}}</span></div>
<div id="seeAll{{div_id}}" class="fr pt5 f16"><a href={{LISTING_LINK}} onclick="{{SEE_ALL_GA_TRACKING}}" class="color12 icons myjs-ic11 pr15">View All</a> </div>
</div>
<!--end:div-->
<!--start:slider-->
<div class="pt15">
<div class="pos-rel">
<div class="fullwid scrollhid">
<div id=disp_{{list_id}} class="pos-rel li-slide2">
<ul id="js-{{list_id}}" class="hor_list clearfix myjslist boxslide pos-rel" style="width: 2236px;">
{{INNER_HTML}}
</ul>
</div>
</div>
<i class="pos-abs sprite2 myjs-ic2 myjs-pos3 scntrl cursp disp-none" id="prv-{{list_id}}"></i>
<i class="pos-abs sprite2 myjs-ic3 myjs-pos4 scntrl cursp disp-none" id="nxt-{{list_id}}"></i>
</div>
</div>
<!--end:slider-->
</article>
</div>
<!--end:large container section-->

<!--start:small container section-->
<div id="smallContainer" class="disp-none">
<!--start:left-->
<div id="{{div_id}}" class="myjs-wid11 fl">
<p id="{{p_id}}" class="fontlig f22 color11">{{HEADING}}</p>
<ul id={{list_id}} class="hor_list clearfix mysj-btmwid pt30">
{{INNER_HTML}}
</ul>
</div>
<!--end:left-->
</div>


<!--message container section-->

<div id="messageContainer" class="clearfix fontlig fullwid scrollhid disp-none" >
	<div id = "{{div_id}}" >
		<div class="fullwid scrollhid">
			<div id=disp_{{list_id}} class="pos-rel li-slide2">
				<ul id = "js-{{list_id}}" class="hor_list clearfix topslide boxslide pos-rel" style="width: 2236px;">
					{{INNER_HTML}}
				</ul>
			</div>
		</div>
		<div class="pt25">
			<div class="pos-rel clearfix fontlig">
				<div id=seeAll{{list_id}} class="pos-abs wid100 txtc myjs-pos8"><a href={{LISTING_LINK}} class="color12">View All {{SEE_ALL_TOTAL}}</a></div>
				<div id="panelCounter_message" class="fr myjs-wid8 clearfix opa50">
					<div class="fl color12 pt5"><span id="slideCurrent{{list_id}}">1 </span> of <span id="slideTotal{{list_id}}">{{TOTAL_NUM}}</span> </div>
					<div class="fr"> <a id="prv-{{list_id}}" class="sprite2 myjs-ic7 disp_ib"></a> <a id="nxt-{{list_id}}" class="sprite2 myjs-ic8 disp_ib myjs-m3"></a> </div>
				</div>
			</div>
		</div>
	</div>
</div>

<!-- no photo no request case-->
<!--<div id="noPhotoNoRequest" class="disp-none">-->

<div id="noPhotoNoRequest" class="disp-none clearfix fontlig">

		<!--start:upload photo-->
		<div class="pr6 pl30 clearfix fontlig">
			<p class="f22">Upload your photos</p>
			<p class="f17 pt15">Profile with photos get 8 times more responses</p>
			<div class="clearfix pt30">
				<div class="fl wid25p">
					<img src="/images/jspc/commonimg/no-img-m.jpg"/>
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
							</div>
							</a>
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
					<p class="pt6 f15">Jpeg, Jpg, PNG | Upto 10MB | 20 photos only</p>
				</div>
			</div>                
		</div> 
		<!--end:upload photo-->
	</div>
<!--</div> -->
        <!--end:content for request-->
