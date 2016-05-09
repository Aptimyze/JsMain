<li>
	~$form['native_state']->renderLabel(null,['style'=>'padding-top:4px'])`
	<div style="">
		<div id="native_state" class="fl">
			~$form['native_state']->render(['maxlength'=>'100','class'=>'txt1','style'=>'width:214px'])`
		</div>
		<div class="fl" style="padding:3px 0px 0px 15px">
			<input type="checkbox" value="1" name="outside_inda" id="chk_outside_india" ~$out_sideIndia`>&nbsp; Outside India
		</div>
		<div class="clr"></div>	
		<div id="err_native_state" style="display: block;padding:0px 0px 0px 175px"></div>
	</div>
	<div class="clr"></div>
	
	<div id="native_city" style="padding:19px 0px 0px 175px;">
		~$form['native_city']->render(['maxlength'=>'100','class'=>'txt1','style'=>'width:214px'])`
	</div>
	
	<div id="native_country" style="padding:19px 0px 0px 175px">
		~$form['native_country']->render(['maxlength'=>'100','class'=>'txt1','style'=>'width:214px'])`
	</div>
	~if $form['native_country']->hasError()`
		<div id="err_country" class="err_msg" style="color:red;display: block;padding:0px 0px 0px 175px">Please provide native country</div>
	~/if`
	
	<div id="native_place" style="padding:19px 0px 0px 0px">
		~$form['ancestral_origin']->renderLabel()`
		~$form['ancestral_origin']->render(['maxlength'=>'100','class'=>'txt1'])`
		<div class="clr"></div>	
		<div id="err_ancestral_origin" style="display: block;padding:0px 0px 0px 175px"></div>
	</div>
</li>
