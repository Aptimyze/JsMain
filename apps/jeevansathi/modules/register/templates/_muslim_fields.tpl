<!-- Section for Muslim Starts Here -->
~if $caste neq '243'`
<li>
		~$form['maththab']->renderLabel()`
		~$form['maththab']->render(['class'=>'sel_mid1'])`
</li>
~/if`
<li>
		~$form['speak_urdu']->renderLabel()`
		~$form['speak_urdu']->render()`
</li>
<li>
		~$form['namaz']->renderLabel()`
		~$form['namaz']->render(['class'=>'sel_mid1'])`
</li>
<li>
		~$form['zakat']->renderLabel()`
		~$form['zakat']->render()`
 </li>
<li>
		~$form['fasting']->renderLabel()`
		~$form['fasting']->render(['class'=>'sel_mid1'])`
</li>
<li>
		~$form['umrah_hajj']->renderLabel()`
		~$form['umrah_hajj']->render(['class'=>'sel_mid1'])`
</li>
<li>
		~$form['quran']->renderLabel()`
		~$form['quran']->render(['class'=>'sel_mid1'])`
</li>
~if $GENDER eq 'M'`
<!-- Start Only for Muslim Boy -->
<li>
		~$form['sunnah_beard']->renderLabel()`
		~$form['sunnah_beard']->render(['class'=>'sel_mid1'])`
</li>
<li>
		~$form['sunnah_cap']->renderLabel()`
		~$form['sunnah_cap']->render(['class'=>'sel_mid1'])`
</li>
<li>
		~$form['working_marriage']->renderLabel()`
		~$form['working_marriage']->render()`
</li>
<li class="bot_bdr">
		~$form['hijab']->renderLabel()`
		~$form['hijab']->render()`
</li>
<!-- End Only for Muslim Boy -->
~else`
<!-- Only for Muslim girl -->
<li class="bot_bdr">
		~$form['hijab_marriage']->renderLabel()`
		~$form['hijab_marriage']->render()`
</li>
<!-- Only for Muslim girl -->
~/if`
<!-- Section for Muslim Ends Here -->
