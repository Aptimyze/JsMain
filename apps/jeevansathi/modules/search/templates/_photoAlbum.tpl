<!--start:overlay-->

<!--end:overlay--> 
<!--start:photo Layer-->
<div id="photoLayerMain" class="disp-none"> 
  <!--start:photo container-->
  <div class="pos_fix srppos7 fullwid layersZ">
    <div class="pos-rel js-albumopenlayer2">
    	<!--start:photo container-->
        <div class="mainwid hgt512 container txtc" id="photoContainer">
          
        </div>
        <div class="srpcolr6 f17 fontlig txtc"><div id="photoAlbumUsername"></div>  <div id="photoAlbumCaption">1 / 6</div></div>
        <!--end:photo container-->
        <!--start:prv/nxt-->
        <div class="pos-abs srppos8 srppos9 cursp" id="photoAlbumPrev" style="width: 40px">        
          <i class="sprite2 photoprv" id="Albumprevicon" style="margin: 10px"></i>
        </div>
        <div class="pos-abs srppos8 srppos10 cursp" id="photoAlbumNext" style="width: 40px">
          <i class="sprite2 photonxt" id="Albumnexticon" style="margin: 10px"></i>
        </div>
        <i class="pos-abs sprite2 sprclose closepht cursp srppos11" id="photoAlbumClose"></i>
        <!--end:prv/nxt-->
     </div>
    
    
    
  </div>
  <!--end:photo container-->

</div>
<!--end:photo Layer--> 

<!--start:conditional Photo access--> 
<div id="conditionalPhotoLayer" class="disp-none">

  <i class="pos_fix layersZ sprite2 sprclose closepht cursp" id="conditionalLayerClose" style="top:5%;right:5%"></i>

  <div class="pos_fix layersZ fontlig setshare  wid1000">
    <div class="f17 fontlig colrw">
    <div class="txtc f15">
      <p class="pb15">It has been a while since you registered on Jeevansathi, hence we require you to add a photo to be able to see other members' album.</p>
      <p>If you have privacy concerns, you can make your photo visible on only on acceptance through privacy settings.</p>
      </div>
      <a id="uploadPhoto" class="cursp mt20 fullwid hoverPink bg_pink lh63 txtc f18 fontlig colrw brdr-0 wid300 mauto disp_b" href="/social/addPhotos?uploadType=C" onclick="trackJsEventGA('conditional Photo Access', 'Upload Photo','PC','');">Upload Photo</a>
    </div>
  </div>
</div>
<!--end:conditional Photo access--> 