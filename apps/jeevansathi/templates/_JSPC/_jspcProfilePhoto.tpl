        <!--start:photo-->
        ~if $arrOutDisplay['pic']['pic_count'] eq "0"`
        <div class="fl pos-rel imgSize js-uploadPhoto cursp" data="~$arrOutDisplay['pic']['pic_count']`,~$arrOutDisplay['about']['username']`,~$arrOutDisplay['page_info']['profilechecksum']`">
        ~else`
         <div class="fl pos-rel imgSize photoClick js-uploadPhoto cursp" data="~$arrOutDisplay['pic']['pic_count']`,~$arrOutDisplay['about']['username']`,~$arrOutDisplay['page_info']['profilechecksum']`">
         ~/if`
          <div class="prfpos2 pos-abs">
            ~if $arrOutDisplay['pic']['pic_count'] neq "0"` <div class="disp-tbl prfclr1 prfdim1 prfrad1 colrw txtc">
             <div class="vmid disp-cell fontlig">~$arrOutDisplay['pic']['pic_count']`</div>
            </div>~/if`
          </div>
           <div class="imgSizeParent bgColorG scrollhid">
            <img src="~$arrOutDisplay['pic']['url']`" class="brdr-0 vtop imgSize" oncontextmenu="return false;" onmousedown="return false;" alt=""/>
           </div>
            ~if $arrOutDisplay['pic']['action']`
            <div id="requestphoto" class="pos-abs srppos3 propos6 fullwid cursp js-hasaction" data='~$arrOutDisplay['page_info']['profilechecksum']`' myaction='~$arrOutDisplay['pic']['action']`'>
              <div class=" bg5 txtc fontlig f14 colrw lh50">~$arrOutDisplay['pic']['label']`</div>
            </div>
            ~else`
            <div id="requestphoto"  class="pos-abs srppos4 propos6 fullwid js-noaction">
              <div class=" txtc colrw opa80 mauto fullwid pos-abs propos6">~$arrOutDisplay['pic']['label']`</div>
            </div>
            ~/if`
	    ~if $arrOutDisplay['pic']['photo_display'] eq 'C'`
            <div id="photoPrivacyText"  class="pos-abs srppos4 propos6 fullwid js-noaction bgColorG">
              <div class=" txtc colrw opa80 mauto fullwidi fontlig" style="line-height:45px;">Visible on Accept</div>
            </div>
	    ~/if`
           </div>
        <!--end:photo-->
