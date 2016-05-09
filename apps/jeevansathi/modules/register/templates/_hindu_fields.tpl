<!-- Section For Hindu Starts Here -->
<li style="position:relative;">
	~$form['subcaste']->renderLabel()`
	~$form['subcaste']->render(['maxlength'=>'250','class'=>'txt1','onfocus'=>'show_help(this)','onblur'=>'hide_help(this)'])`
<br/>
<div id="subcastes" class="fl" style="position:absolute;clear:all;"></div>
<div class="clr"></div>
	<div class="coverhelp" style="width:20%;float:left;position:relative;margin-left:93px;margin-top:-15px;">
		<div style="display: none;margin-top:2px;border:1px solid #99b1c8; left:315px; top:-7px; margin-right:25px; position:absolute; width:220px;z-index:100;background:#FFFFFF" id="reg_subcaste_help" class="helpbox">
			<div class="helptext" style="float:left; width:200px; font-family:Arial,Helvetica;font-size:11px;color:#000000; font-weight:normal;line-height:13px;padding:4px 6px;">Type a few letters and let our auto suggest feature suggest your subcaste. Otherwise you can enter a new one.
				<div class="helpimg" style="position: absolute; top: 5px; left:-16px;background-image:url(/profile/images/registration_new/arrow2.gif); background-repeat:no-repeat; width:16px; height:12px;"/>
				</div>
			</div>
		</div>
	</div>
</li>
<li style="position:relative;">
	~$form['gothra']->renderLabel()`
	~$form['gothra']->render(['maxlength'=>'250','class'=>'txt1','onfocus'=>'show_help(this)','onblur'=>'hide_help(this)'])`
	<br/>
		<div id="gothraPat" class="fl" style="position:absolute;clear:all"></div>
	  <div class="clr"></div>
	<div class="coverhelp" style="width:20%;float:left;position:relative;margin-left:93px;margin-top:-15px;">
		<div style="display: none;margin-top:2px;border:1px solid #99b1c8; left:315px; top:-7px; margin-right:25px; position:absolute; width:220px;z-index:100;background:#FFFFFF" id="reg_gothra_help" class="helpbox">
			<div class="helptext" style="float:left; width:200px; font-family:Arial,Helvetica;font-size:11px;color:#000000; font-weight:normal;line-height:13px;padding:4px 6px;">Type a few letters and let our auto suggest feature suggest your gothra. Otherwise you can enter a new one.
				<div class="helpimg" style="position: absolute; top: 5px; left:-16px;background-image:url(/profile/images/registration_new/arrow2.gif); background-repeat:no-repeat; width:16px; height:12px;"/>
				</div>
			</div>
		</div>
	</div>
	   
</li>
<li>
	~$form['manglik']->renderLabel()`
	~$form['manglik']->render()`
</li>
<li>
	~$form['nakshatra']->renderLabel()`
	~$form['nakshatra']->render(['class'=>'sel_mid1'])`
</li>
<li>
	~$form['rashi']->renderLabel()`
	~$form['rashi']->render(['class'=>'sel_mid1'])`
</li>
