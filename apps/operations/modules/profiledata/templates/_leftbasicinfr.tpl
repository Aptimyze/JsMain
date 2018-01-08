<div class="lf" style="width:49%">
~if $AGE`
<div class="row2 no_b">
<label>Age</label><div class="rf wgy9f ~$CODEOWN.AGE`" >: ~$AGE` Years 
</div></div>
~/if`
~if $HEIGHT`
<div class="row2 no_b ">
<label>Height</label><div class="rf wgy9f ~$CODEOWN.HEIGHT`" >: ~$HEIGHT|decodevar`
</div></div>
~/if`

~if $PROFILEGENDER`
<div class="row2 no_b ">
<label>Gender</label><div class="rf wgy9f">: ~$PROFILEGENDER`
</div></div>
~/if`

~if $religionSelf`
<div class="row2 no_b ">
<label>Religion</label><div class="rf wgy9f ~$CODEOWN.RELIGION`" >: ~$religionSelf`
</div></div>
~/if`

~if $MTONGUE`
<div class="row2 no_b ">
<label>Mother Tongue</label><div class="rf wgy9f ~$CODEOWN.MTONGUE`" >: ~$MTONGUE|decodevar`
</div></div>
~/if`

~if $CASTE neq ''`
<div class="row2 no_b ">
<label>~$casteLabel`</label><div class="rf wgy9f ~$CODEOWN.$casteLabel`" >: ~$CASTE` 
</div></div>
~/if`
~if $CASTE neq ''`
~if $SUBCASTE`
<div class="row2 no_b ">
<label>Sub Caste</label><div class="rf wgy9f" >: ~$SUBCASTE|decodevar`
</div></div>
~/if`
~/if`
</div>
