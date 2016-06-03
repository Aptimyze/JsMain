<!--start:profile photo select from facebook-->
<div class="pos_fix fullwid layersZ fontlig js-fbImport" style="top:5%;display:none;">



	<div class="bg-white mauto puwid2">
		<!--start:title-->
		<div class="pubdr4 pup5 clearfix color11">
			<div class=" f17 fl">Facebook Photos</div>
			<div class="fr f15 opa80 pt2 js-ImportLoaderHide" id="js-fbCountDiv"></div>
		</div>
		<!--end:title-->

		<!-- loader -->
		<div class="js-ImportLoader" style="text-align:center;height:400px">
			<div id="albumImportLoader" style="padding-top:194px;">
				<img src="~sfConfig::get('app_img_url')`/images/searchImages/loader_small.gif">
			</div>
		</div>
		<!-- loader -->

		<!--start:middle-->
		<div class="fullwid clearfix puhgt2 pos-rel js-ImportLoaderHide">
			<i class="sprite2 pos-abs z2 puic12 pupos8" id="selectedAlbumPointer"></i>
			<!--start:vertical tabs-->
			<div class="fl pos-rel fbcont">
				<!--start:control icons-->
				<div id="controlArrows">
					<i class="sprite2 pos-abs z2 shiftup pupos6 cursp" id="shiftImportBarUp"></i>
					<i class="sprite2 pos-abs z2 shiftdown pupos7 cursp" id="shiftImportBarDown"></i>
				</div>               
				<!--end:control icons-->
				<div class="pb20">
					<ul class="fb-tabs listnone pos-rel wid100 mauto topnavpos2" id="js-addImportAlbum">
					</ul>
				</div>
			</div>
			<!--end:vertical tabs-->
			<!--start:content part-->
			<div class="fr wid79p puhgt2 scrolla">
				<div class="pl15 pr4 pb20">
					<!-- loader -->
					<div class="js-ImportLoader2 disp-none" style="text-align:center;">
						<div id="photoImportLoader" style="padding-top:184px;margin-left:-40px;">
							<img src="~sfConfig::get('app_img_url')`/images/searchImages/loader_small.gif">
						</div>
					</div>
					<!-- loader -->


					<ul class="clearfix hor_list piclist" id="js-addImportPhotos">
					</ul>
				</div>
			</div>
			<!--end:content part-->
		</div>
		<!--end:middle-->
		<!--start:bottom-->
		<div class="pubdr3 pup8 clearfix fontlig">
			<div class="fl bg_pink wid190 txtc lh40" id="uploadFb">
				<a href="#"  onclick="return false;" style="outline: 0;" class="f20 colrw">Upload selected</a>
			</div>
			<div class="fl pt10 pl30 js-cancel js-fbCancel">
				<a href="#"  onclick="return false;" style="outline: 0;" class="color5 f20">Cancel</a>
			</div>
		</div>
		<!--end:bottom-->
	</div>
</div>
<!--end:profile photo select from facebook-->

<span class="disp-none" id="js-addImportAlbumInd">
<li>
	<div class="pubdr6 js-importAlbum cursp" id="album{importAlbumId}" data-id={importAlbumOffset}>
	</div>
	<div class="bg_1 color11 fontreg f11 lh26 pl5 textTru">
		{importAlbumName}
	</div>
	<div class="pos-abs pupos9">
		<div class="disp-tbl txtc bg_pink purad1">
			<div class="disp-cell colrw f14 fontreg pup4 pudim4 vmid">{importAlbumCount}</div>
		</div>
	</div>
</li>
</span>
						

<span class="disp-none" id="js-addImportPhotosInd">
<li class="js-fbPhoto cursp photonumber{photo_number}">
	<i class="sprite2 pos-abs pupos10 js-tick" data-fbId="{saveUrl}"></i>
</li>
</span>
