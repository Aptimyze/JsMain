<style>
.pink{width:770px!important; border:1px #f0ced6;border-style:solid; background-color:#fdf4f5;}
.title { font:18px arial; color:#505050; position:relative; top:5px; left:8px;}
.blink1 { font:18px arial; color:#1171ae; position:relative; top:5px; right:5px}
.fullwidth { width:100%;}
.no-margin-padding{margin:0!important; padding:0!important}
.sp15 {clear:both; height:15px; overflow:hidden; }
.grey_note_box {background-color:#f6f5f5;padding:5px;width:78%!important;border:1px #dedddd solid; float:right;}
.green-hash {color:#8cc502}
.block { display:block}
.center { text-align:center}
.fs18 { font-size:18px;}
.green_btn_2 { background-image: url("/images/green_button_bg1.gif");    background-repeat: no-repeat;    color: #FFFFFF;    cursor: pointer;height: 35px; width:101px;border:none!important;}
.border-footer {border:1px #F0CED6; border-top-style:solid}
div.row3 label.grey, div.row4 label.grey {vertical-align:top;padding-right:10px;float:left;color:#797979!important; font-size:13px; width:189px}
.btn-add {background:url(/images/sprite_ico.gif) -9px -81px scroll no-repeat; margin:0px 10px; display:inline; width:18px!important; height:18px;}
.btn-rem {background:url(/images/sprite_ico.gif) -9px -102px scroll no-repeat; margin:0px 10px; display:inline; width:18px!important; height:18px;}
.btn-key {background:url(/images/sprite_ico.gif) -8px -5px scroll no-repeat;width:20px; height:20px; display:inline-block;}
.btn-archive {background:url(/images/sprite_ico.gif) -8px -28px scroll no-repeat;width:16px; height:16px; display:inline-block; vertical-align:top}
.vam{vertical-align:middle;}
.green_btn { background-image:url(/images/green_button_bg.gif); background-repeat:repeat-x; height:22px; border:1px #578d00; border-style:solid; padding-bottom:2px; font-size:11px; color:#fff; cursor:pointer;}
.topbg {background-image: url(/images/top_bg.jpg)!important;background-repeat: repeat-x;height: 33px!important;}
.red_new {color:#e93a3e !important}
</style>
~$sf_data->getRaw('hiddenInput')`
	<input type="hidden" name="from_filter" value="1">
	<input type="hidden" name="filter" value="~$filter`">
		~if $filter eq 'age'`
		<div class="row4">
			<label style="width:96px;padding-right:10px">Age :</label>
			<select name="Min_Age" style="width:55px;" id="mina">
				<option value="18"~if $MIN_AGE eq '18'` selected~/if`>18</option>
				<option value="19"~if $MIN_AGE eq '19'` selected~/if`>19</option>
				<option value="20"~if $MIN_AGE eq '20'` selected~/if`>20</option>
				<option value="21"~if $MIN_AGE eq '21'` selected~/if`>21</option>
				<option value="22"~if $MIN_AGE eq '22'` selected~/if`>22</option>
				<option value="23"~if $MIN_AGE eq '23'` selected~/if`>23</option>
				<option value="24"~if $MIN_AGE eq '24'` selected~/if`>24</option>
				<option value="25"~if $MIN_AGE eq '25'` selected~/if`>25</option>
				<option value="26"~if $MIN_AGE eq '26'` selected~/if`>26</option>
				<option value="27"~if $MIN_AGE eq '27'` selected~/if`>27</option>
				<option value="28"~if $MIN_AGE eq '28'` selected~/if`>28</option>
				<option value="29"~if $MIN_AGE eq '29'` selected~/if`>29</option>
				<option value="30"~if $MIN_AGE eq '30'` selected~/if`>30</option>
				<option value="31"~if $MIN_AGE eq '31'` selected~/if`>31</option>
				<option value="32"~if $MIN_AGE eq '32'` selected~/if`>32</option>
				<option value="33"~if $MIN_AGE eq '33'` selected~/if`>33</option>
				<option value="34"~if $MIN_AGE eq '34'` selected~/if`>34</option>
				<option value="35"~if $MIN_AGE eq '35'` selected~/if`>35</option>
				<option value="36"~if $MIN_AGE eq '36'` selected~/if`>36</option>
				<option value="37"~if $MIN_AGE eq '37'` selected~/if`>37</option>
				<option value="38"~if $MIN_AGE eq '38'` selected~/if`>38</option>
				<option value="39"~if $MIN_AGE eq '39'` selected~/if`>39</option>
				<option value="40"~if $MIN_AGE eq '40'` selected~/if`>40</option>
				<option value="41"~if $MIN_AGE eq '41'` selected~/if`>41</option>
				<option value="42"~if $MIN_AGE eq '42'` selected~/if`>42</option>
				<option value="43"~if $MIN_AGE eq '43'` selected~/if`>43</option>
				<option value="44"~if $MIN_AGE eq '44'` selected~/if`>44</option>
				<option value="45"~if $MIN_AGE eq '45'` selected~/if`>45</option>
				<option value="46"~if $MIN_AGE eq '46'` selected~/if`>46</option>
				<option value="47"~if $MIN_AGE eq '47'` selected~/if`>47</option>
				<option value="48"~if $MIN_AGE eq '48'` selected~/if`>48</option>
				<option value="49"~if $MIN_AGE eq '49'` selected~/if`>49</option>
				<option value="50"~if $MIN_AGE eq '50'` selected~/if`>50</option>
				<option value="51"~if $MIN_AGE eq '51'` selected~/if`>51</option>
				<option value="52"~if $MIN_AGE eq '52'` selected~/if`>52</option>
				<option value="53"~if $MIN_AGE eq '53'` selected~/if`>53</option>
				<option value="54"~if $MIN_AGE eq '54'` selected~/if`>54</option>
				<option value="55"~if $MIN_AGE eq '55'` selected~/if`>55</option>
				<option value="56"~if $MIN_AGE eq '56'` selected~/if`>56</option>
				<option value="57"~if $MIN_AGE eq '57'` selected~/if`>57</option>
				<option value="58"~if $MIN_AGE eq '58'` selected~/if`>58</option>
				<option value="59"~if $MIN_AGE eq '59'` selected~/if`>59</option>
				<option value="60"~if $MIN_AGE eq '60'` selected~/if`>60</option>
				<option value="61"~if $MIN_AGE eq '61'` selected~/if`>61</option>
				<option value="62"~if $MIN_AGE eq '62'` selected~/if`>62</option>
				<option value="63"~if $MIN_AGE eq '63'` selected~/if`>63</option>
				<option value="64"~if $MIN_AGE eq '64'` selected~/if`>64</option>
				<option value="65"~if $MIN_AGE eq '65'` selected~/if`>65</option>
				<option value="66"~if $MIN_AGE eq '66'` selected~/if`>66</option>
				<option value="67"~if $MIN_AGE eq '67'` selected~/if`>67</option>
				<option value="68"~if $MIN_AGE eq '68'` selected~/if`>68</option>
				<option value="69"~if $MIN_AGE eq '69'` selected~/if`>69</option>
				<option value="70"~if $MIN_AGE eq '70'` selected~/if`>70</option>
			</select> &nbsp; to &nbsp; 
			<select name="Max_Age" style="width:55px;" id="maxa">
				<option value="18"~if $MAX_AGE eq '18'` selected~/if`>18</option>
				<option value="19"~if $MAX_AGE eq '19'` selected~/if`>19</option>
				<option value="20"~if $MAX_AGE eq '20'` selected~/if`>20</option>
				<option value="21"~if $MAX_AGE eq '21'` selected~/if`>21</option>
				<option value="22"~if $MAX_AGE eq '22'` selected~/if`>22</option>
				<option value="23"~if $MAX_AGE eq '23'` selected~/if`>23</option>
				<option value="24"~if $MAX_AGE eq '24'` selected~/if`>24</option>
				<option value="25"~if $MAX_AGE eq '25'` selected~/if`>25</option>
				<option value="26"~if $MAX_AGE eq '26'` selected~/if`>26</option>
				<option value="27"~if $MAX_AGE eq '27'` selected~/if`>27</option>
				<option value="28"~if $MAX_AGE eq '28'` selected~/if`>28</option>
				<option value="29"~if $MAX_AGE eq '29'` selected~/if`>29</option>
				<option value="30"~if $MAX_AGE eq '30'` selected~/if`>30</option>
				<option value="31"~if $MAX_AGE eq '31'` selected~/if`>31</option>
				<option value="32"~if $MAX_AGE eq '32'` selected~/if`>32</option>
				<option value="33"~if $MAX_AGE eq '33'` selected~/if`>33</option>
				<option value="34"~if $MAX_AGE eq '34'` selected~/if`>34</option>
				<option value="35"~if $MAX_AGE eq '35'` selected~/if`>35</option>
				<option value="36"~if $MAX_AGE eq '36'` selected~/if`>36</option>
				<option value="37"~if $MAX_AGE eq '37'` selected~/if`>37</option>
				<option value="38"~if $MAX_AGE eq '38'` selected~/if`>38</option>
				<option value="39"~if $MAX_AGE eq '39'` selected~/if`>39</option>
				<option value="40"~if $MAX_AGE eq '40'` selected~/if`>40</option>
				<option value="41"~if $MAX_AGE eq '41'` selected~/if`>41</option>
				<option value="42"~if $MAX_AGE eq '42'` selected~/if`>42</option>
				<option value="43"~if $MAX_AGE eq '43'` selected~/if`>43</option>
				<option value="44"~if $MAX_AGE eq '44'` selected~/if`>44</option>
				<option value="45"~if $MAX_AGE eq '45'` selected~/if`>45</option>
				<option value="46"~if $MAX_AGE eq '46'` selected~/if`>46</option>
				<option value="47"~if $MAX_AGE eq '47'` selected~/if`>47</option>
				<option value="48"~if $MAX_AGE eq '48'` selected~/if`>48</option>
				<option value="49"~if $MAX_AGE eq '49'` selected~/if`>49</option>
				<option value="50"~if $MAX_AGE eq '50'` selected~/if`>50</option>
				<option value="51"~if $MAX_AGE eq '51'` selected~/if`>51</option>
				<option value="52"~if $MAX_AGE eq '52'` selected~/if`>52</option>
				<option value="53"~if $MAX_AGE eq '53'` selected~/if`>53</option>
				<option value="54"~if $MAX_AGE eq '54'` selected~/if`>54</option>
				<option value="55"~if $MAX_AGE eq '55'` selected~/if`>55</option>
				<option value="56"~if $MAX_AGE eq '56'` selected~/if`>56</option>
				<option value="57"~if $MAX_AGE eq '57'` selected~/if`>57</option>
				<option value="58"~if $MAX_AGE eq '58'` selected~/if`>58</option>
				<option value="59"~if $MAX_AGE eq '59'` selected~/if`>59</option>
				<option value="60"~if $MAX_AGE eq '60'` selected~/if`>60</option>
				<option value="61"~if $MAX_AGE eq '61'` selected~/if`>61</option>
				<option value="62"~if $MAX_AGE eq '62'` selected~/if`>62</option>
				<option value="63"~if $MAX_AGE eq '63'` selected~/if`>63</option>
				<option value="64"~if $MAX_AGE eq '64'` selected~/if`>64</option>
				<option value="65"~if $MAX_AGE eq '65'` selected~/if`>65</option>
				<option value="66"~if $MAX_AGE eq '66'` selected~/if`>66</option>
				<option value="67"~if $MAX_AGE eq '67'` selected~/if`>67</option>
				<option value="68"~if $MAX_AGE eq '68'` selected~/if`>68</option>
				<option value="69"~if $MAX_AGE eq '69'` selected~/if`>69</option>
				<option value="70"~if $MAX_AGE eq '70'` selected~/if`>70</option>
			</select>
			<div class="clr"></div>
			<div style="padding-left:66px;">
			<div class="red" id="my_age" style="display:none;">
				<img style="vertical-align: bottom;" src="~$IMG_URL`/profile/images/registration_new/alert.gif"/> Partner min age should be less than partner max age.</div>
		</div></div>
		~elseif $filter eq 'mstatus'`
		<div style="padding:5px;width:96%">
			<div class="lf gray b t12" style="width:90px;text-align:right;margin-right:10px">Marital Status :</div>
			<div class="lf t11" style="width:260px">
				<div style="padding:0 5px 0 2px;">
					<div class="lf">Select Items</div>
					<div class="rf"><a id="partner_mstatus_select_all" onclick="add_checkboxes(this); remove_doesnt_matter_conflict(this);" class="blink">Select All</a></div>
				</div>
				<div class="lf scrollbox3 t12">
					<input type="hidden" name="partner_mstatus_str" id="partner_mstatus_str" value="~$partner_mstatus_str`">
					<div style="display:none" id="partner_mstatus_div">
						~foreach from=$MSTATUS item=val key=k`
							<input type="checkbox" value="~$k`" name="partner_mstatus_arr[]" id="partner_mstatus_~$k`"><label id="partner_mstatus_label_~$k`">~$val`</label><br>
						~/foreach`
					</div>
					<div style="overflow:hidden;" id="partner_mstatus_source_div">
						~foreach from=$MSTATUS item=val key=k`
							<input id="partner_mstatus_displaying_~$k`" class="chbx" type="checkbox" onclick="add_checkboxes(this); remove_doesnt_matter_conflict(this); " value="~$k`" name="partner_mstatus_displaying_arr[]"><label id="partner_mstatus_displaying_label_~$k`">~$val`</label><br>
						~/foreach`
					</div>
				</div>
			</div>
			<div class="lf t11" style="width:260px; margin-left:20px;">
				<div style="padding:0 5px 0 2px;">
					<div class="lf">Selected Items</div>
					<div class="rf"><a id="partner_mstatus_clear_all" onclick="remove_checkboxes(this); remove_doesnt_matter_conflict(this);" class="blink">Clear All</a></div>
				</div>
				<div class="lf scrollbox3 t12">
					<div style="overflow:hidden;" id="partner_mstatus_target_div">
						<div id="partner_mstatus_DM"><label>Doesn't Matter</label></div>
					</div>
				</div>
			</div>
		</div>
		~elseif $filter eq 'country' or $filter eq 'city'`
		<div style="padding:5px;width:96%">
			<div class="lf gray b t12" style="width:90px;text-align:right;margin-right:10px">Country living in :</div>
			<div class="lf t11" style="width:260px">
				<div style="padding:0 5px 0 2px;">
					<div class="lf">Select Items</div>
					<div class="rf"><a id="partner_country_select_all" onclick="add_checkboxes(this); " class="blink">Select All</a></div>
				</div>
				<input type="hidden" id="partner_country_selected" value="" style="display:none;">
				<div class="lf scrollbox3 t12">
					 <input type="hidden" name="partner_country_str" id="partner_country_str" ~if $checked_country` value="~$checked_country`" ~else` value="" ~/if`>
					<div style="display:none" id="partner_country_div">
						<input type="checkbox" name="partner_country_arr[]" id="partner_country_DM" value="DM"><label id="partner_country_label_DM">Any</label><br>
						~$hidden_country|decodevar`
					</div>
					<div style="overflow:hidden;" id="partner_country_source_div">
						~$shown_country|decodevar`
					</div>
				</div>
			</div>
			<div class="lf t11" style="width:260px; margin-left:20px;">
				<div style="padding:0 5px 0 2px;">
					<div class="lf">Selected Items</div>
					<div class="rf"><a id="partner_country_clear_all" onclick="remove_checkboxes(this); " class="blink">Clear All</a></div>
				</div>
				<div class="lf scrollbox3 t12">
					<div style="overflow:hidden;" id="partner_country_target_div">
						<div style="overflow:hidden;" id="partner_country_target_div">Any</div>
					</div>
				</div>
			</div>
		</div>
		<div style="padding:5px;width:96%;" id="city">
			<div class="lf gray b t12" style="width:90px;text-align:right;margin-right:10px">City living in :</div>
			<div class="lf t11" style="width:260px">
				<div style="padding:0 5px 0 2px;">
					<div class="lf">Select Items</div>
					<div class="rf"><a id="partner_city_select_all" onclick="add_checkboxes(this); " class="blink">Select All</a></div>
				</div>
				<div class="lf scrollbox3 t12">
					 <input type="hidden" id="partner_city_selected" value="">
					 <input type="hidden" name="partner_city_str" id="partner_city_str"  value="~$checked_city`" >
					 <div style="display:none" id="partner_city_div">
						 <input type="checkbox" name="partner_city_arr[]" id="partner_city_DM" value="DM"><label id="partner_city_label_DM">Any</label><br>
						 ~$hidden_city|decodevar`
						 <input type="checkbox" value="0" name="partner_city_arr[]" id="partner_city_0"> <label id="partner_city_label_0">Others</label><br>
					 </div>
					<div style="overflow:hidden;" id="partner_city_source_div">
						~$shown_city|decodevar`
						<input type="checkbox" class="chbx" name="partner_city_displaying_arr[]" id="partner_city_displaying_0" value="0"><label id="partner_city_displaying_label_0">Others</label><br>
					</div>
				</div>
			</div>
			<div class="lf t11" style="width:260px; margin-left:20px;">
				<div style="padding:0 5px 0 2px;">
					<div class="lf">Selected Items</div>
					<div class="rf"><a id="partner_city_clear_all" onclick="remove_checkboxes(this); " class="blink">Clear All</a></div>
				</div>
				<div class="lf scrollbox3 t12">
					<div style="overflow:hidden;" id="partner_city_target_div"> Any</div>
				</div>
			</div>
		</div>
		~elseif $filter eq 'religion' or $filter eq 'caste'`
		<div style="padding:5px;width:96%">
			<div class="lf gray b t12" style="width:90px;text-align:right;margin-right:10px">Religion :</div>
			<div class="lf t11" style="width:260px">
				<div style="padding:0 5px 0 2px;">
					<div class="lf">Select Items</div>
					<div class="rf"><a id="partner_religion_select_all" onclick="add_checkboxes(this); remove_doesnt_matter_conflict(this);" class="blink">Select All</a></div>
				</div>
				<div class="lf scrollbox3 t12">
					 <input type="hidden" name="partner_religion_str" id="partner_religion_str" value="">
					<div style="display:none" id="partner_religion_div">
						<input type="checkbox" name="partner_religion_arr[]" id="partner_religion_DM" value="DM"><label id="partner_religion_label_DM">Any</label><br>
						~$hidden_religion|decodevar`
					</div>
					<div style="overflow:hidden;" id="partner_religion_source_div">
						~$shown_religion|decodevar`
					</div>
				</div>
			</div>
			<div class="lf t11" style="width:260px; margin-left:20px;">
				<div style="padding:0 5px 0 2px;">
					<div class="lf">Selected Items</div>
						<div class="rf"><a id="partner_religion_clear_all" onclick="remove_checkboxes(this); remove_doesnt_matter_conflict(this);" class="blink">Clear All</a></div>
				</div>
				<div class="lf scrollbox3 t12">
					<div style="overflow:hidden;" id="partner_religion_target_div"> All </div>
				</div>
			</div>
		</div>
		<div style="padding:5px;width:96%">
			<div class="lf gray b t12" style="width:90px;text-align:right;margin-right:10px">Caste :</div>
			<div class="lf t11" style="width:260px">
				<div style="padding:0 5px 0 2px;">
					<div class="lf">Select Items</div>
					<div class="rf"><a id="partner_caste_select_all" onclick="add_checkboxes(this); remove_doesnt_matter_conflict(this);" class="blink">Select All</a></div>
				</div>
				<div class="lf scrollbox3 t12">
					<input type="hidden" id="partner_caste_selected" value="" style="display:none;">
					<div style="display:none" id="partner_caste_div">
						<input type="hidden" name="partner_caste_str" value="" id="partner_caste_str">
						  &nbsp;
					</div>
					<div style="overflow:hidden;" id="partner_caste_source_div"> </div>
				</div>
			</div>
			<div class="lf t11" style="width:260px; margin-left:20px;">
				<div style="padding:0 5px 0 2px;">
					<div class="lf">Selected Items</div>
					<div class="rf"><a id="partner_caste_clear_all" onclick="remove_checkboxes(this); remove_doesnt_matter_conflict(this);" class="blink">Clear All</a></div>
				</div>
				<div class="lf scrollbox3 t12">
					<div style="overflow:hidden;" id="partner_caste_target_div">Any	</div>
				</div>
			</div>
		</div>
		~elseif $filter eq 'community'`
		<div style="padding:5px;width:96%">
			<div class="lf gray b t12" style="width:90px;text-align:right;margin-right:10px">Community :</div>
			<div class="lf t11" style="width:260px">
				<div style="padding:0 5px 0 2px;">
					<div class="lf">Select Items</div>
					<div class="rf"><a id="partner_mtongue_select_all" onclick="add_checkboxes(this); remove_doesnt_matter_conflict(this);" class="blink">Select All</a></div>
				</div>
				<div class="lf scrollbox3 t12">
					<input type="hidden" id="mton_sel" ~if $checked_mtongue` value="~$checked_mtongue`" ~else` value="" ~/if`>
					<input type="hidden" name="partner_mtongue_str" id="partner_mtongue_str" value="~$partner_mtongue_str`">
					 <div style="display:none" id="partner_mtongue_div">
						~foreach from=$MTONGUE_ARR item=val key=k`
							<input type="checkbox" value="~$k`" name="partner_mtongue_arr[]" id="partner_mtongue_~$k`"><label id="partner_mtongue_label_~$k`">~$val|decodevar`</label><br>
						~/foreach`
					</div>
					<div style="overflow:hidden;" id="partner_mtongue_source_div">
						~foreach from=$MTONGUE_ARR item=val key=k`
							<input id="partner_mtongue_displaying_~$k`" class="chbx" type="checkbox" onclick="add_checkboxes(this); remove_doesnt_matter_conflict(this);" value="~$k`" name="partner_mtongue_displaying_arr[]"><label id="partner_mtongue_displaying_label_~$k`">~$val|decodevar`</label><br>
						~/foreach`
					</div>
				</div>
			</div>
			<div class="lf t11" style="width:260px; margin-left:20px;">
				<div style="padding:0 5px 0 2px;">
					<div class="lf">Selected Items</div>
					<div class="rf"><a id="partner_mtongue_clear_all" onclick="remove_checkboxes(this); remove_doesnt_matter_conflict(this);" class="blink">Clear All</a></div>
				</div>
				<div class="lf scrollbox3 t12">
					<div style="overflow:hidden;" id="partner_mtongue_target_div">
						<div id="partner_mtongue_DM"><label>Doesn't Matter</label></div>
					</div>
				</div>
			</div>
		</div>
		~elseif $filter eq 'income'`
		<li style="padding:5px;width:96%">
				<label id="income_label" class="gray b">Annual Income :</label>
				<span style="width: 125px;font-weight:normal;">Select income range</span>
				<div class="clr"></div>
				<div style="height: 80px;" class="scrollbox_adv">
					<div style="margin-bottom: 3px;padding: 3px 3px 3px 6px;">
						<span style="display: none; color: rgb(255, 0, 0);" id="invalidUser">
							<img src="images/iconError_16x16.gif"><b>Please make a selection before continuing.</b>
						</span>
					</div>
					<div style="padding: 0pt 0pt 0pt 100px;font-weight:normal;">
						<span style="color:#4B4B4B;font-weight:bold;">Indian Rupee</span>&nbsp;
						<select style="width: 150px;" name="rsLIncome" id="rsLIncome">
							<option value="">Please Select</option>
                                                         ~foreach from=$MIN_LABEL_RS key=k1 item=v1`
									<option value="~$k1`" ~if $partner_lincome neq '' && $k1 eq $partner_lincome`selected~/if`>~$v1`</option>
                                                         ~/foreach`
						</select> &nbsp;&nbsp;to&nbsp;&nbsp;

						<select style="width: 150px;" name="rsHIncome" id="rsHIncome">
							<option value="">Please Select</option>
							~foreach from=$MAX_LABEL_RS key=k1 item=v1`
                                                                    <option value="~$k1`"  ~if $partner_hincome neq '' && $k1 eq $partner_hincome`selected~/if`>~$v1`</option>
                                                        ~/foreach`
						</select>
					</div>
					<div style="padding: 20px 0pt 0pt 96px;font-weight:normal;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						<span style="color:#4B4B4B;font-weight:bold;">US Dollar</span>&nbsp;
						<select style="width: 150px;" name="doLIncome" id="doLIncome">
							<option value="">Please Select</option>
                                                         ~foreach from=$MIN_LABEL_DO key=k1 item=v1`
                                                              <option value="~$k1`"  ~if $partner_lincome_dol neq '' && $k1 eq $partner_lincome_dol`selected~/if`>~$v1|decodevar`</option>
                                                         ~/foreach`
						</select> &nbsp;&nbsp;to&nbsp;&nbsp;
						<select style="width: 150px;" name="doHIncome" id="doHIncome">
							 <option value="">Please Select</option>
                                                         ~foreach from=$MAX_LABEL_DO key=k1 item=v1`
                                                              <option value="~$k1`"  ~if $partner_hincome_dol neq '' && $k1 eq $partner_hincome_dol`selected~/if`>~$v1|decodevar`</option>
                                                         ~/foreach`
						</select>
					</div>
				</div>				<div style="padding-left:170px;">
				<div class="red" id="my_income" style="display:none;">
					<img style="vertical-align: bottom;" src="~$IMG_URL`/profile/images/registration_new/alert.gif"/>
					Maximum income should be greater than minimum income.
				</div></div>
				<div class="clr"></div>				<div style="padding-left:170px;">
				<div class="red" id="my_income_error" style="display:none;">
					<img style="vertical-align: bottom;" src="~$IMG_URL`/profile/images/registration_new/alert.gif"/>
					Please enter both the values to define income range.
				</div></div>				<div style="padding-left:170px;">
				<div class="red" id="my_income_error_dol" style="display:none;">
					<img style="vertical-align: bottom;" src="~$IMG_URL`/profile/images/registration_new/alert.gif"/>
					Please enter both the values to define dollar range.
				</div></div	>
			</li>
		~/if`
	<input type="hidden" name="city" id="city" value="~$city_str`">
<script>
var page = 'filter';
	function validate_income()
	{
		var rsHIncome=document.getElementById('rsHIncome').value;
		var rsLIncome=document.getElementById('rsLIncome').value;
		var doLIncome=document.getElementById('doLIncome').value;
		var doHIncome=document.getElementById('doHIncome').value;
		if(rsHIncome==19)
		   rsHIncome=30;
		if(doHIncome==19)
			doHIncome=30;

		if( rsLIncome && rsHIncome!='')
		{
			if(parseInt(rsLIncome) > parseInt(rsHIncome) || (parseInt(rsLIncome) == parseInt(rsHIncome) && parseInt(rsHIncome)!=0  ))		  		  {
				document.getElementById('my_income').style.display='inline';
				document.getElementById('my_income_error').style.display='none';
				document.getElementById('my_income_error_dol').style.display='none';
		                document.getElementById('rsLIncome').focus();
		                return false;
			}
		}
		
		if((doLIncome!='') && (doHIncome!=''))
		{
			 if(parseInt(doLIncome) > parseInt(doHIncome) || (parseInt(doLIncome) == parseInt(doHIncome) && parseInt(doHIncome)!=0))
			 {
				document.getElementById('my_income').style.display='inline';
				document.getElementById('my_income_error').style.display='none';
				document.getElementById('my_income_error_dol').style.display='none';
		                document.getElementById('doLIncome').focus();
		                return false;
			 }
		}

		if((rsLIncome!='') && (rsHIncome==''))
		{
			document.getElementById('my_income_error').style.display='inline';
			document.getElementById('my_income_error_dol').style.display='none';
			document.getElementById('my_income').style.display='none';
			document.getElementById('rsHIncome').focus();
			return false;
		}

		if((rsLIncome=='') && (rsHIncome!=''))
		{
			document.getElementById('my_income_error').style.display='inline';
			document.getElementById('my_income_error_dol').style.display='none';
			document.getElementById('my_income').style.display='none';
			document.getElementById('rsLIncome').focus();
			return false;
		}

		if((doLIncome!='') && (doHIncome==''))
		{
			document.getElementById('my_income_error_dol').style.display='inline';
			document.getElementById('my_income_error').style.display='none';
			document.getElementById('my_income').style.display='none';
			document.getElementById('doHIncome').focus();
			return false;
		}

		if(doLIncome=='' && doHIncome!='')
		{
			document.getElementById('my_income_error_dol').style.display='inline';
			document.getElementById('my_income_error').style.display='none';
			document.getElementById('my_income').style.display='none';
			document.getElementById('doLIncome').focus();
			return false;
		}

		document.getElementById('my_income_error_dol').style.display='none';
		document.getElementById('my_income_error').style.display='none';
		document.getElementById('my_income').style.display='none';
		return true;
	}
function validate()
{
        var min_a=parseInt(document.getElementById('mina').value);
        var max_a=parseInt(document.getElementById('maxa').value);
        if(max_a<min_a)
        {
                document.getElementById('my_age').style.display="inline"; 
                document.getElementById('mina').focus();
                return false;
        }
        else
        {
                document.getElementById('my_age').style.display="none";
        }
}
function showChild()
{
        if(document.getElementById("partner_mstatus_str"))
        if(document.getElementById("partner_mstatus_str").value == 'N')
                document.getElementById("child").style.display = "none";
}
var docF = document.form1;
function fill_default_val()
{
        var caste, rel;
        ~foreach from=$checked_religion key=k item=v`
                if(rel)
                        rel+=",'"+"~$v|decodevar`"+"'";
                else
                        rel="'~$v|decodevar`'";
        ~/foreach`
        document.getElementById("partner_religion_str").value=rel;
        ~foreach from=$checked_caste key=k item=v`
                if(caste)
                        caste+=",'"+~$v|decodevar`+"'";
                else
                        caste="'"+~$v|decodevar`+"'";
        ~/foreach`
        document.getElementById("partner_caste_selected").value=caste;
}
	showChild();
	~if $filter eq 'religion'`
		fill_default_val();
		if(document.getElementById("partner_religion_str"))
                var ppre = new Array("partner_religion");
	~elseif $filter eq 'caste'`
		fill_default_val();
		if(document.getElementById("partner_caste_selected"))
                var ppre = new Array("partner_religion","partner_caste");
		else
			var ppre = new Array("partner_religion");
	~elseif $filter eq 'community'`
		var ppre = new Array("partner_mtongue");
	~elseif $filter eq 'country' || $filter eq 'city'`
		 ~if $checked_city`
			var ppre = new Array("partner_country","partner_city");
		~else`
			var ppre = new Array("partner_country");
		~/if`
	~elseif $filter eq 'mstatus'`
		var ppre = new Array("partner_mstatus");
	~elseif $filter eq 'income'`
                var ppre = new Array("partner_income");
        ~/if`
	~if $filter neq 'age'`
        fill_details(ppre);
	~/if`
</script>
</body>
</html>
