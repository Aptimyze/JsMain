<!--start:listint -->    
<div>
	~assign var="loop" value=0`
	~foreach from=$tupleData key=key item=item`
	~assign var="loop" value=$loop+1`
	<!--start:profile div-->
	<div class="boxprofile_v1" id='tupleBox_~$tupleId`_~$loop`'>
		<div class="probrdrbtm_v1">
			<div class="paddala_d_v1">
				<!--start:pic right part-->
				<div class="fl_v1 ~if $item->IS_ALBUM_TEXT`myjsAlbum" oncontextmenu="return false;" id='showAlbum_~$tupleId`_~$item->PROFILECHECKSUM`' style="cursor:pointer;"~else`" ~/if`>
					<div class="imgbrder_v1 imone_v1">
						<img src="~$item->getSearchPicUrl()`" alt="pic" id='SearchPicUrl_~$tupleId`_~$loop`'>
					</div>
					~if $item->getIS_ALBUM() eq 'A' || $item->getIS_ALBUM() eq 'L'` <!-- IS_ALBUM_TEXT-->
					<div class="txtalign_c_v1 pt4_v1">
						<a class="txtcolblu1_v1">
							~$item->IS_ALBUM_TEXT`
						</a>
					</div>
					~/if`
				</div>
				<!--end:pic right part-->
				<!--start:left part-->
				<div class="fl_v1 padleft15_v1">
					<!--start:left top part-->
					<div>
						<!--start:profile info-->
						<div class="fl_v1 wid320_v1">
							<div>
								<!--start:name-->
								<div class="fl_v1">
									<a class="fntsize16_v1 txtcolblu1_v1" id='USERNAME_~$tupleId`_~$loop`'>
									~$item->getUSERNAME()`
									</a>
								</div>
								<!--end:name-->
								<div id='ICONS_~$tupleId`_~$loop`'>
								~foreach from=$item->getICONS() key=icon item=iconValues name=iconsArray`
									<!--start:evalue-->
									<div class="fl_v1 padleft15_v1">
										<div class="~$iconValues['iconClass']`" id='ICON_~$smarty.foreach.iconsArray.index`_~$tupleId`_~$loop`'></div>
									</div>
									<!--end:evalue-->

									<!--start:help-->
									<div id='ICON_PARTIAL_~$smarty.foreach.iconsArray.index`_~$tupleId`_~$loop`'>
										~include_partial($iconValues["partial"], ['astro' => $item->getASTRO_DETAILS(),'pid'=>$item->getPROFILEID()])`
									</div>

									<!--end:help-->
									<!--start:seprator-->
									~if !$smarty.foreach.iconsArray.last`
										<div class="fl_v1 padleft5_v1">
											<div class="seprator2_v1"></div>
										</div>
									~/if`
									<!--end:seprator-->
								~/foreach`
								<!--end:guna match-->
								</div>
								<div class="clr_v1"></div>
							</div>
							<!--start:information-->
							<div class="pt20_v1 txt3color_v1 ftbld_v1">
								<div class="lht20_v1" id='displayString_~$tupleId`_~$loop`'>~$item->getdisplayString()`</div>
							</div>
							<!--end:information-->                                                      
						</div>
						<!--end:profile info-->
						<!--start:comment box-->
						<div class="fl_v1 padleft22_v1">
							<div class="brdr5_v1 bgclr2 hgt70_v1 wid153_v1 classrela_v1 brdr1radius_v1">
								<div class="paddala_e_v1 txt4color_v1" id='CALLOUT_MESSAGES_RightTop_~$tupleId`_~$loop`'>~$item->getCALLOUT_MESSAGES('RightTop')`</div>
								<div class="arrowpos_v1">
									<div class="commentarrow_v1"></div>
								</div>
							</div>
						</div>
						<!--end:comment box-->
						<div class="clr_v1"></div>
					</div>
					<!--end:left top part-->  
					<!--start:button-->
					<div class="pt10_v1">
						<div class="fl_v1">
							<!--start:green btn-->
							<div>
								<input type="submit" value="Accept" class="btnwid_v1 fntinpt_v1 greenbtn_v1" name="">
							</div>
							<!--end:green btn-->
						</div>
						<div class="fl_v1 padleft10_v1">
							<!--start:green btn-->
							<div>
								<input type="submit" value="Not Intrested" class="btnwid_v1 fntinpt_v1 greybtn_v1" name="">
							</div>
							<!--end:green btn-->
						</div>
						<div class="clr_v1"></div>
					</div>
					<!--end:button-->                                          
				</div>
				<!--end:left part-->
				<div class="clr_v1"></div>
			</div>
		</div>
	</div>
	<!--end:profile div-->    
	~/foreach`
</div>
<!--end:listint -->
