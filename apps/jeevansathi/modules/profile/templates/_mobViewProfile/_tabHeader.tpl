<!--start:div-->
<div id='tabHeader' class="fullwid bg1">
  <div class="padd22 txtc">
    <div class="posrel">
      <div class="posabs ot_pos1"> <i id="backBtn" class="mainsp arow2"></i></div>
      <div class="fontthin f19 white headerOverflow" id="vpro_headerTitle">
          ~if $myPreview`
            Preview
          ~else if isset($name_of_user) && $name_of_user neq '' && $name_of_user neq null`
            ~$name_of_user`
          ~else if isset($username)`
            ~$username`
          ~else`
            Profile not found
          ~/if`
      </div>
      <div class="posabs vpro_pos1"> 
          ~if isset($showComHistory) && !($myPreview)`
            <i class="vpro_sprite vpro_comHisIcon cursp"></i>
          ~else if $myPreview`
            <i id="closeMyPreview" class="mainsp vpro_cross1 cursp"></i>
          ~/if`
      </div>
    </div>
  </div>
</div>
<!--end:div--> 

