<div class="row2 no_b ">
<label>Ma'thab</label>
<div class="rf" style="width:175px">: ~$religionInfo->MATHTHAB`	
</div>
</div>
<div class="row2 no_b ">
<label>Speak Urdu</label>
<div class="rf" style="width:175px">: ~$religionInfo->SPEAK_URDU`	
</div>
</div>
<div class="row2 no_b ">
<label>Namaz</label>
<div class="rf" style="width:175px">: ~$religionInfo->NAMAZ`	
</div>
</div>
<div class="row2 no_b ">
<label>Zakat</label>
<div class="rf" style="width:175px">: ~$religionInfo->ZAKAT`	
</div>
</div>
<div class="row2 no_b ">
<label>Fasting</label>
<div class="rf" style="width:175px">: ~$religionInfo->FASTING`	
</div>
</div>
<div class="row2 no_b ">
<label>Umrah/Hajj</label>
<div class="rf" style="width:175px">: ~$religionInfo->UMRAH_HAJJ`	
</div>
</div>
<div class="row2 no_b ">
<label>Do You Read The Quran?</label>
<div class="rf" style="width:175px">: ~$religionInfo->QURAN`	
</div>
</div>
~if $loginProfile->getGender() eq 'M'`
<div class="row2 no_b ">
<label>Sunnah Beard</label><div class="rf" style="width:175px">: ~$religionInfo->SUNNAH_BEARD`
</div></div>
<div class="row2 no_b ">
<label>Sunnah Cap</label><div class="rf" style="width:175px">: ~$religionInfo->SUNNAH_CAP`
</div></div>
~/if`
~if $loginProfile->getGender() eq 'M'`
<div class="row2 no_b ">
<label>Hijab</label><div class="rf" style="width:175px">:  ~$religionInfo->HIJAB`
</div></div>
~else`
<div class="row2 no_b ">
<label>Hijab after marriage</label><div class="rf" style="width:175px">: ~$religionInfo->HIJAB_MARRIAGE`
</div></div>
~/if`
~if $loginProfile->getGender() eq 'M'`
<div class="row2 no_b ">
<label>Can the Girl Work After Marriage?</label><div class="rf" style="width:175px">: ~$religionInfo->WORKING_MARRIAGE`
</div></div>
~/if`
