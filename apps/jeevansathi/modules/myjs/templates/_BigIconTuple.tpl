<!--start:new intrest recived-->
<div>
	<div class="brdr4_v1">
		<!--start:header div-->
		<div class="fullwidth_v1 bgcol1_v1">
			<div class="paddala_d_v1">
				<!--start:left-->
				<div class="fl_v1">
					<div class="fl_v1 wid16_v1 txtalign_c_v1 pt4_v1">
						<div class="numberbox_v1 fnt12_v1">~$widgetData["NEW_COUNT"]`</div>
					</div>
					<div class="fl_v1">
						<div class="fntsize14_v1 padleft10_v1 pt4_v1">~$widgetData["TITLE"]`</div>
					</div>
					<div class="fl_v1">
						<div class="txtcolblu1_v1 padleft4_v1 pt4_v1">[ View all ~$widgetData["VIEW_ALL_COUNT"]` ]  </div>
					</div>
				</div>
				<!--end:left-->
				<!--start:right-->
				<div class="fr_v1">
					<div class="fl_v1">
						<div class="pt4_v1 padright4_v1">[ Showing <span id='SHOWING_START~$widgetData["ID"]`'>~$widgetData["SHOWING_START"]`</span>-<span id='SHOWING_COUNT~$widgetData["ID"]`'>~$widgetData["SHOWING_COUNT"]`</span> of ~$widgetData["NEW_COUNT"]` ]</div>
					</div>
					<div class="fl_v1">

                                                <div class="fl_v1" id='CURRENT_NAV~$widgetData["ID"]`' ~if $widgetData["CURRENT_NAV"] eq 1` style="display:none;" ~/if`>
                                                        <a id="prev1">
                                                                <div class="prevbtn_v1" id='~$widgetData["ID"]`_~$widgetData["CURRENT_NAV"]`'></div>
                                                        </a>
                                                </div>
                                
                                                <div class="fl_v1" id='SHOW_NEXT~$widgetData["ID"]`' ~if !$widgetData["SHOW_NEXT"]` style="display:block;"~/if`>
                                                        <a>
                                                                <div class="nextbtn_v1" id='~$widgetData["ID"]`_~$widgetData["SHOW_NEXT"]`'></div>
                                                        </a>
                                                </div>

						~*
						<!--
						~if $widgetData["CURRENT_NAV"] neq 1`
						<div class="fl_v1" id='CURRENT_NAV~$widgetData["ID"]`'>
							<a id="prev1">
								<div class="prevbtn_v1"></div>
							</a>
						</div>
						~/if`
				
						~if $widgetData["SHOW_NEXT"]`	
						<div class="fl_v1" id='SHOW_NEXT~$widgetData["ID"]`'>
							<a id="next1">
								<div class="nextbtn_v1"></div>
							</a>
						</div>
						~/if`		
						-->
						*`
						<div class="clr_v1"></div>
					</div>
					<div class="clr_v1"></div>
				</div>
				<!--end:right-->
				<div class="clr_v1"></div>
			</div>
		</div>
		<!--end:header div-->  
		<!--start:listint -->   
		~include_partial('global/tuples/_BigIconTuple',[tupleData=>$widgetData["TUPLES"],tupleId=>$widgetData["ID"]])`	
		<!--end:listint -->                 
	</div>
	<!--start:pagination botton-->
	<div class="txtalign_c_v1 pt10_v1" >
		<ul class="navlist">
			~foreach from=$widgetData["NAVIGATION_INDEX"] key=key1 item=item1`
			<li><a href="#">~$item1`</a></li>
			~/foreach`
		</ul>
	</div>
	<!--end:pagination botton-->
</div>
<!--end:new intrest recived-->
