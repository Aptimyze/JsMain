~if $havePhoto neq 'Y' && $havePhoto neq 'U'`
      <div class="fl"> <img src="~$ProfilePicUrl`" class="vtop" style="width:220px;height:220px;" id="profileImageId"/> </div>
~else`
      <div class="fl pos-rel">
        <img src="~$ProfilePicUrl`" class="vtop" style="width:220px;height:220px;" id="profileImageId"/>
        <!--start:layer-->
        <div class="pos-abs color_white puwid3 pospos3 cursp changePhoto">
                <div class="mauto clearfix wid163 pt10 pb10">
                <i class="sprite2 puic11 fl"></i>
                <div class="color5 f17 fontreg fl pt5 pl10">Change Photo</div>
            </div>
        </div>
      </div>
~/if`
