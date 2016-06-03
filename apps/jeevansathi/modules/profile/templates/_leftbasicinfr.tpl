<div class="lf" style="width:49%">
~if $AGE`
<div class="row2 no_b">
<label>Age</label><div class="rf wgy9f ~$CODEOWN.AGE`" >: ~$AGE` Years 
</div></div>
~/if`
~if $HEIGHT`
<div class="row2 no_b ">
<label>Height</label><div class="rf wgy9f ~$CODEOWN.HEIGHT`" >: ~$HEIGHT`
</div></div>
~/if`

~if $PROFILEGENDER`
<div class="row2 no_b ">
<label>Gender</label><div class="rf wgy9f">: ~$PROFILEGENDER`
</div></div>
~/if`

~if $religionSelf`
<div class="row2 no_b ">
<label>Religion</label><div class="rf wgy9f ~$CODEOWN.RELIGION`" >: ~if $PROFILELINK['REL_LINK'] eq ''` ~$religionSelf` ~else` <a href="~$PROFILELINK['REL_LINK']`" style="color:#5E5E5E">~$religionSelf` </a> ~/if`
</div></div>
~/if`

~if $MTONGUE`
<div class="row2 no_b ">
<label>Mother Tongue</label><div class="rf wgy9f ~$CODEOWN.MTONGUE`" >: ~if $PROFILELINK['MTNG_LINK'] eq ''` ~$MTONGUE` ~else` <a href="~$PROFILELINK['MTNG_LINK']`" style="color:#5E5E5E" >~$MTONGUE` </a> ~/if`
</div></div>
~/if`

~if $CASTE neq ''`
<div class="row2 no_b ">
<label>~$casteLabel`</label><div class="rf wgy9f ~$CODEOWN.$casteLabel`" >: ~if $PROFILELINK['CASTE_LINK'] eq ''` ~$CASTE`  ~else` <a href="~$PROFILELINK['CASTE_LINK']`" style="color:#5E5E5E">~$CASTE` </a> ~/if`
</div></div>
~/if`
~if $CASTE neq ''`
~if $SUBCASTE`
<div class="row2 no_b ">
<label>Sub Caste</label><div class="rf wgy9f" >: ~$SUBCASTE`
</div></div>
~/if`
~/if`
</div>
