 <div style="width:~$WIDTH`px; border:1px solid #cccccc; background-color:#f9f9f9; padding:4px;">
		<strong style = "position:relative;z-index:1">Photo~if sfConfig::get("mod_social_video")` &amp; Video~/if` Privacy Settings</strong>
		<div class="sp8">
		</div>
		<div class="lf no_b">
			<input type="radio" name="photo_display" class="rd1 lf" value="A" ~if $PHOTODISPLAY eq 'F' or $PHOTODISPLAY eq 'A'` checked ~/if`> 
				<span class="lf">
					Visible to All
				</span>
				<span id="im1_1" style="display:none;width:180px;">
					<img src="~sfConfig::get("app_img_url")`/images/loader_extra_small.gif" align="top" />
				</span>
				<div id="im1_2" style="display:none;width:180px;">&nbsp;&nbsp;
					<span>
						<img src="~sfConfig::get("app_img_url")`/images/grtick.gif" align="top"/>
					</span>&nbsp;
					<span class="green">
						Saved
					</span>
				</div>
		</div>
		<div class="sp8">
		</div>
		<div class="lf no_b" style="width:~$WIDTH`px;">
			<input type="radio" name="photo_display" class="rd1 lf" value="C" ~if $PHOTODISPLAY eq 'C' or $PHOTODISPLAY eq 'H'` checked ~/if`> 
				<span class="lf">
					Visible to those you have accepted or expressed interest in. 
					<span id="im2_1" style="display:none;">
						<img src="~sfConfig::get('app_img_url')`/images/loader_extra_small.gif" align="top" />
					</span>
					<span id="im2_2" style="display:none;">&nbsp;&nbsp;
						<span>
							<img src="~sfConfig::get('app_img_url')`/images/grtick.gif" align="top"/>
						</span>&nbsp;
						<span class="green">
							Saved
						</span>
					</span>
				</span>
		</div>

		<div class="sp8">
		</div>
	</div>
