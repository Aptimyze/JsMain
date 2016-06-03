<script>
 var page="edit1";
 </script>
<SCRIPT type="text/javascript" language="Javascript" SRC="~$IMG_URL`/min/?f=/profile/js/~$behaviour_js`,/profile/js/~$registration_js`,/js/~$advance_search_js`,/profile/js/~$gadget_as_js`"></SCRIPT>
<!--~$hiddenInput`-->
~$sf_data->getRaw('hiddenInput')`

<div class="edit_scrollbox2_1">

<div>
	<div class="widthauto row4 no-margin-padding">
		<label class="grey fl">Hobbies :</label>
	</div>
	<div class="lf t11 setwidth">
		<div class="setwidth">
			<div class="lf">Select Items</div>
			<div class="rf">
				<a id="hobbies_select_all" onclick="add_checkboxes(this); remove_doesnt_matter_conflict(this);" class="blink">Select All</a>
			</div>
		</div>
		<div class="fl scroll_new t12">
			<div style="display:none" id="hobbies_div">
		       		 <input type="hidden" name="hobbies_str" id="hobbies_str" value="~$HOBBY_str`">
				~foreach from=$HOBBY key=k item=val`
					<input type="checkbox" value="~$k`" name="hobbies_arr[]" id="hobbies_~$k`"><label id="hobbies_label_~$k`">~$val`</label><br>
				~/foreach`
      			</div>
			<div style="overflow:hidden;" id="hobbies_source_div"> 
				~foreach from=$HOBBY key=k item=val`	
					<input id="hobbies_displaying_~$k`" class="chbx checkboxalign" type="checkbox" onclick="add_checkboxes(this); remove_doesnt_matter_conflict(this);" value="~$k`" name="hobbies_displaying_label_arr[]">
					<label id="hobbies_displaying_label_~$k`">~$val`</label><br>
				~/foreach`
			</div>
		</div>
	</div>
	<i class="horz_sp35 fl">&nbsp;</i>
	<div class="lf t11 setwidth">
		<div class="setwidth">
			<div class="lf">Selected Items</div>
			<div class="rf">
				<a id="hobbies_clear_all" onclick="remove_checkboxes(this); remove_doesnt_matter_conflict(this);" class="blink">Clear All</a>
			</div>
		</div>
		<div class="lf scroll_new t12">
			<div style="overflow:hidden;" id="hobbies_target_div">
				<div id="hobbies_DM">
					<label>Doesn't Matter</label>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="sp15"></div>

<div>
	<div class="widthauto row4 no-margin-padding">
		<label class="grey fl">Interests :</label>
	</div>
	<div class="lf t11 setwidth">
		<div style="setwidth">
			<div class="lf">Select Items</div>
			<div class="rf">
				<a id="interest_select_all" onclick="add_checkboxes(this); remove_doesnt_matter_conflict(this);" class="blink">Select All</a>
			</div>
		</div>
		<div class="lf scroll_new t12">
			 <div style="display:none" id="interest_div">
            		    <input type="hidden" name="interest_str" id="interest_str" value="~$INTEREST_str`">
               		    ~foreach from=$INTEREST key=k item=val`
              		   	 <input type="checkbox" value="~$k`" name="interest_arr[]" id="interest_~$k`"><label id="interest_label_~$k`">~$val`</label><br>
               		    ~/foreach`
      			 </div>
      			 <div style="overflow:hidden;" id="interest_source_div">
             		   ~foreach from=$INTEREST key=k item=val`
              			  <input id="interest_displaying_~$k`" class="chbx checkboxalign" type="checkbox" onclick="add_checkboxes(this); remove_doesnt_matter_conflict(this);" value="~$k`" name="interest_displaying_label_arr[]">
				  <label id="interest_displaying_label_~$k`">~$val`</label><br>
               		   ~/foreach`
        		</div>
		</div>
	</div>
	<span class="horz_sp35  fl">&nbsp;</span>
	<div class="lf t11 setwidth">
		<div class="setwidth">
			<div class="lf">Selected Items</div>
			<div class="rf">
				<a id="interest_clear_all" onclick="remove_checkboxes(this); remove_doesnt_matter_conflict(this);" class="blink">Clear All</a>
			</div>
		</div>
		<div class="lf scroll_new t12">
			<div style="overflow:hidden;" id="interest_target_div">
				<div id="interest_DM"><label>Doesn't Matter</label></div>
			</div>
		</div>
	</div>
</div>

<div class="sp15"></div>

<div>
	<div class="widthauto row4 no-margin-padding">
	<label class="grey">Favourite Music :</label>
	</div>
<div class="lf t11 setwidth">
<div class="setwidth"><div class="lf">Select Items</div><div class="rf"><a id="music_select_all" onclick="add_checkboxes(this); remove_doesnt_matter_conflict(this);" class="blink">Select All</a></div></div>
<div class="lf scroll_new t12">
	<div style="display:none" id="music_div">
                <input type="hidden" name="music_str" id="music_str" value="~$MUSIC_str`">
                ~foreach from=$MUSIC key=k item=val`
                <input type="checkbox" value="~$k`" name="music_arr[]" id="music_~$k`"><label id="music_label_~$k`">~$val`</label><br>
                ~/foreach`
        </div>
        <div style="overflow:hidden;" id="music_source_div">
                ~foreach from=$MUSIC key=k item=val`
                <input id="music_displaying_~$k`" class="chbx checkboxalign" type="checkbox" onclick="add_checkboxes(this); remove_doesnt_matter_conflict(this);" value="~$k`" name="music_displaying_label_arr[]"><label id="music_displaying_label_~$k`">~$val`</label><br>
                ~/foreach`
        </div>
</div>
</div>
<span class="horz_sp35  fl">&nbsp;</span>
<div class="lf t11 setwidth">
<div><div class="lf">Selected Items</div><div class="rf"><a id="music_clear_all" onclick="remove_checkboxes(this); remove_doesnt_matter_conflict(this);" class="blink">Clear All</a></div></div>
<div class="lf scroll_new t12">
<div style="overflow:hidden;" id="music_target_div">
<div id="music_DM"><label>Doesn't Matter</label></div>
</div>
</div></div></div>


<div class="sp15"></div>

<div>
	<div class="widthauto row4 no-margin-padding">
		<label class="grey">Favourite Read :</label>
	</div>
	<div class="lf t11 setwidth">
		<div>
			<div class="lf">Select Items</div><div class="rf"><a id="book_select_all" onclick="add_checkboxes(this); remove_doesnt_matter_conflict(this);" class="blink">Select All</a></div>
		</div>

		<div class="lf scroll_new t12 setwidth">
			 <div style="display:none" id="book_div">
				<input type="hidden" name="book_str" id="book_str" value="~$BOOK_str`">
				~foreach from=$BOOK key=k item=val`
					<input type="checkbox" value="~$k`" name="book_arr[]" id="book_~$k`"><label id="book_label_~$k`">~$val`</label><br>
				~/foreach`
			</div>
			<div style="overflow:hidden;" id="book_source_div">
				~foreach from=$BOOK key=k item=val`
					<input id="book_displaying_~$k`" class="chbx checkboxalign" type="checkbox" onclick="add_checkboxes(this); remove_doesnt_matter_conflict(this);" value="~$k`" name="book_displaying_label_arr[]"><label id="book_displaying_label_~$k`">~$val`</label><br>
				~/foreach`
			</div>
		</div>
	</div>
	<span class="horz_sp35 fl">&nbsp;</span>
	<div class="lf t11 setwidth">
		<div class="setwidth">
				<div class="lf">Selected Items</div>
				<div class="rf">
					<a id="book_clear_all" onclick="remove_checkboxes(this); remove_doesnt_matter_conflict(this);" class="blink">Clear All</a>
				</div>
		</div>
		<div class="lf scroll_new t12">
			<div style="overflow:hidden;" id="book_target_div">
				<div id="book_DM"><label>Doesn't Matter</label></div>
			</div>
		</div>
	</div>
</div>

<div class="sp15"></div>

<div>
	<div class="widthauto row4 no-margin-padding">
		<label class="grey">Favourite Books :</label>
	</div> 
	<textarea cols="6" rows="4" class="textarea2" name="fav_book" maxlength="1000" onkeyup="return ismaxlength(this);">~$FAV_BOOK`</textarea>
</div>
<div class="sp15"></div>

<div >
<div class="widthauto row4 no-margin-padding">
<label class="grey">Dress Style :</label>
</div>
<div class="lf t11 setwidth">
<div class="setwidth"><div class="lf">Select Items</div><div class="rf"><a id="dress_select_all" onclick="add_checkboxes(this); remove_doesnt_matter_conflict(this);" class="blink">Select All</a></div></div>
<div class="lf scroll_new t12">
	 <div style="display:none" id="dress_div">
                <input type="hidden" name="dress_str" id="dress_str" value="~$DRESS_str`">
                ~foreach from=$DRESS key=k item=val`
                <input type="checkbox" value="~$k`" name="dress_arr[]" id="dress_~$k`"><label id="dress_label_~$k`">~$val`</label><br>
                ~/foreach`
        </div>
        <div style="overflow:hidden;" id="dress_source_div">
                ~foreach from=$DRESS key=k item=val`
                <input id="dress_displaying_~$k`" class="chbx checkboxalign" type="checkbox" onclick="add_checkboxes(this); remove_doesnt_matter_conflict(this);" value="~$k`" name="dress_displaying_label_arr[]"><label id="dress_displaying_label_~$k`">~$val`</label><br>
                ~/foreach`
        </div>
</div>
</div>
<span class="horz_sp35  fl">&nbsp;</span>
<div class="lf t11 setwidth">
<div class="setwidth"><div class="lf">Selected Items</div><div class="rf"><a id="dress_clear_all" onclick="remove_checkboxes(this); remove_doesnt_matter_conflict(this);" class="blink">Clear All</a></div></div>
<div class="lf scroll_new t12">
<div style="overflow:hidden;" id="dress_target_div">
<div id="dress_DM"><label>Doesn't Matter</label></div>
</div>
</div></div></div>
<div class="sp15"></div>

<div>
<div class="widthauto row4 no-margin-padding">
<label class="grey">Preferred movies :</label>
</div>
<div class="lf t11 setwidth">
<div><div class="lf">Select Items</div><div class="rf"><a id="movies_select_all" onclick="add_checkboxes(this); remove_doesnt_matter_conflict(this);" class="blink">Select All</a></div></div>
<div class="lf scroll_new t12">
	 <div style="display:none" id="movies_div">
                <input type="hidden" name="movies_str" id="movies_str" value="~$MOVIE_str`">
                ~foreach from=$MOVIE key=k item=val`
                <input type="checkbox" value="~$k`" name="movies_arr[]" id="movies_~$k`"><label id="movies_label_~$k`">~$val`</label><br>
                ~/foreach`
        </div>
        <div style="overflow:hidden;" id="movies_source_div">
                ~foreach from=$MOVIE key=k item=val`
                <input id="movies_displaying_~$k`" class="chbx checkboxalign" type="checkbox" onclick="add_checkboxes(this); remove_doesnt_matter_conflict(this);" value="~$k`" name="movies_displaying_label_arr[]"><label id="movies_displaying_label_~$k`">~$val`</label><br>
                ~/foreach`
        </div>
</div>
</div>
<span class="horz_sp35  fl">&nbsp;</span>
<div class="lf t11 setwidth">
<div><div class="lf">Selected Items</div><div class="rf"><a id="movies_clear_all" onclick="remove_checkboxes(this); remove_doesnt_matter_conflict(this);" class="blink">Clear All</a></div></div>
<div class="lf scroll_new t12">
<div style="overflow:hidden;" id="movies_target_div">
<div id="movies_DM"><label>Doesn't Matter</label></div>
</div>
</div></div></div>
<div class="sp15"></div>

<div>
	<div class="widthauto row4 no-margin-padding">
		<label class="grey">Favourite Movies :</label>
	</div>
	<textarea cols="6" rows="4" class="textarea2" name="fav_movies" maxlength="1000"           onkeyup="return ismaxlength(this);">~$FAV_MOVIES`</textarea>
</div>
<div class="sp15"></div>

<div>
	<div class="widthauto row4 no-margin-padding">
		<label class="grey">Favourite TV shows:</label>
	</div>
	<textarea cols="6" rows="4" class="textarea2" name="fav_tvshow" maxlength="1000" onkeyup="return ismaxlength(this);">~$FAV_TVSHOW`</textarea>
</div>

<div class="sp15"></div>

<div>
	<div class="widthauto row4 no-margin-padding">
		<label class="grey">Sports/Fitness :</label>
	</div>
<div class="lf t11 setwidth">
<div class="setwidth"><div class="lf">Select Items</div><div class="rf"><a id="sports_select_all" onclick="add_checkboxes(this); remove_doesnt_matter_conflict(this);" class="blink">Select All</a></div></div>
<div class="lf scroll_new t12">
	 <div style="display:none" id="sports_div">
                <input type="hidden" name="sports_str" id="sports_str" value="~$SPORTS_str`">
                ~foreach from=$SPORTS key=k item=val`
                <input type="checkbox" value="~$k`" name="sports_arr[]" id="sports_~$k`"><label id="sports_label_~$k`">~$val`</label><br>
                ~/foreach`
        </div>
        <div style="overflow:hidden;" id="sports_source_div">
                ~foreach from=$SPORTS key=k item=val`
                <input id="sports_displaying_~$k`" class="chbx checkboxalign" type="checkbox" onclick="add_checkboxes(this); remove_doesnt_matter_conflict(this);" value="~$k`" name="sports_displaying_label_arr[]"><label id="sports_displaying_label_~$k`">~$val`</label><br>
                ~/foreach`
        </div>
</div>
</div>
<span class="horz_sp35  fl">&nbsp;</span>
<div class="lf t11 setwidth">
<div class="setwidth"><div class="lf">Selected Items</div><div class="rf"><a id="sports_clear_all" onclick="remove_checkboxes(this); remove_doesnt_matter_conflict(this);" class="blink">Clear All</a></div></div>
<div class="lf scroll_new t12">
<div style="overflow:hidden;" id="sports_target_div">
<div id="sports_DM"><label>Doesn't Matter</label></div>
</div>
</div></div></div>

<div class="sp15"></div>

<div >
	<div class="widthauto row4 no-margin-padding">
		<label class="grey">Favourite Cuisine :</label>
	</div>
<div class="lf t11 setwidth">
<div class="setwidth"><div class="lf">Select Items</div><div class="rf"><a id="cuisine_select_all" onclick="add_checkboxes(this); remove_doesnt_matter_conflict(this);" class="blink">Select All</a></div></div>
<div class="lf scroll_new t12">
	 <div style="display:none" id="cuisine_div">
                <input type="hidden" name="cuisine_str" id="cuisine_str" value="~$CUISINE_str`">
                ~foreach from=$CUISINE key=k item=val`
                <input type="checkbox" value="~$k`" name="cuisine_arr[]" id="cuisine_~$k`"><label id="cuisine_label_~$k`">~$val`</label><br>
                ~/foreach`
        </div>
        <div style="overflow:hidden;" id="cuisine_source_div">
                ~foreach from=$CUISINE key=k item=val`
                <input id="cuisine_displaying_~$k`" class="chbx checkboxalign" type="checkbox" onclick="add_checkboxes(this); remove_doesnt_matter_conflict(this);" value="~$k`" name="cuisine_displaying_label_arr[]"><label id="cuisine_displaying_label_~$k`">~$val`</label><br>
                ~/foreach`
        </div>
</div>
</div>
<span class="horz_sp35  fl">&nbsp;</span>
<div class="lf t11 setwidth">
<div class="setwidth"><div class="lf">Selected Items</div><div class="rf"><a id="cuisine_clear_all" onclick="remove_checkboxes(this); remove_doesnt_matter_conflict(this);" class="blink">Clear All</a></div></div>
<div class="lf scroll_new t12">
<div style="overflow:hidden;" id="cuisine_target_div">
<div id="cuisine_DM"><label>Doesn't Matter</label></div>
</div>
</div></div></div>

<div class="sp15"></div>

<div>
	<div class="widthauto row4 no-margin-padding">
		<label class="grey">Food I Cook :</label>
	</div>
<textarea cols="6" rows="4" class="textarea2" name="fav_food" maxlength="1000"           onkeyup="return ismaxlength(this);">~$FAV_FOOD`</textarea>
</div>
<div class="sp15"></div>

<div >
	<div class="widthauto row4 no-margin-padding">
		<label class="grey">Favourite vacation destination :</label>
	</div>
	<textarea cols="6" rows="4" class="textarea2" name="fav_vac_dest" maxlength="1000"           onkeyup="return ismaxlength(this);">~$FAV_VAC_DEST`</textarea>
</div>
<div class="sp15"></div>
</div>


<script>
	var hoin = new Array("hobbies","music","interest","book","movies","sports","cuisine","dress");
	fill_details(hoin);
</script>
