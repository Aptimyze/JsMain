<!--start:photo pop up select profile photo-->
<div class="pos_fix fullwid layersZ fontlig selectPhoto" style="top:20%;display:none;">
	<div class="bg-white mauto puwid4">
    	<!--start:title-->
        <div class="pubdr4 pup5">
        	<div class="color11 f17">Select profile photo</div>        
        </div>        
        <div class="pup5" style="display:none;" id="selectOtherPhoto">
        	<div class="color11 f13" id="selectProfilePhotoError">To delete a profile photo, please first select another photo as profile photo.</div>        
        </div>        
        <!--end:title-->
       <div style="width:505px; height:250px; overflow:auto">
        	<div class="fontlig pup9">
        	<ul class="clearfix hor_list pulist3" id="selectPhotoList">
            	</ul>
        </div>
    	</div>
        <!--start:title-->
        <div class="pubdr3 pup8 clearfix fontlig">
        	      <div class="fl bg_pink wid190 txtc lh40 cursp" id="select">
                  	<a href="#" onclick="return false;" class="f20 colrw" style="outline: 0;">Select</a>
                  </div>
                  <div class="fl pt10 pl30 cursp js-cancel">
                  	<a href="#" onclick="return false;" class="color5 f20" style="outline: 0;">Cancel</a>
                  </div>
        </div>        
        <!--end:title-->
    </div>
</div>
<!--end:photo pop up-->
<div id="selectPhotoDiv" style="display:none">
                <li class="selectPhotoLi{{PICTUREID}} cursp">
                        <span class="sprite2 pos-abs pupos5 cursp selectPhotoTick{{PICTUREID}}" data-pictureid='{{PICTUREID}}'></span>
                        <div class="brdr-1">
                    </div>
                </li>
</div>
