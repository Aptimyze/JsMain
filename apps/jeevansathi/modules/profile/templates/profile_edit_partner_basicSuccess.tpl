<SCRIPT type="text/javascript" language="Javascript" SRC="~$IMG_URL`/min/?f=/profile/js/~$registration_js`,/profile/js/~$gadget_as_js`,/js/~$advance_search_js`"></SCRIPT>
<script>
var docF = document.form1;
var page="edit1";
function validate()
{
	var min_a=parseInt(document.getElementById('mina').value);
        var max_a=parseInt(document.getElementById('maxa').value);

        if(max_a<min_a)
        {
                document.getElementById('my_age').style.display="block"; 
                document.getElementById('mina').focus();
                return false;
        }
	else
	{
		document.getElementById('my_age').style.display="none";
		var min_h=parseInt(document.getElementById('minh').value);
	        var max_h=parseInt(document.getElementById('maxh').value);

        	if(max_h<min_h)
        	{
                	document.getElementById('my_height').style.display="block"; 
                	document.getElementById('minh').focus();
                	return false;
        	}
		else	
		{
			document.getElementById('my_height').style.display="none";
			return true;
		}
	}
}
function showChild()
{
	if(document.getElementById("partner_mstatus_str").value == "N")
		document.getElementById("Have_child").style.display = "none";
}
function fill_default_val()
{
        var city;
}
</script>
~$sf_data->getRaw('hiddenInput')`
<div class="edit_scrollbox2_1 t12">
<div class="row4">
<label style="width:115px;text-align:right;margin-right:10px;">Age :</label>
<select name="Min_Age" style="width:55px;" id="mina">
	<option value="18"~if $MIN_AGE eq '18' or $MIN_AGE eq ''` selected~/if`>18</option>
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
</select> &nbsp; to &nbsp; <select name="Max_Age" style="width:55px;" id="maxa">
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
	<option value="70"~if $MAX_AGE eq '70' or $MAX_AGE eq ''` selected~/if`>70</option>
</select>
<div class="red_new" id="my_age" style="display:none;padding-left:120px;">
<img style="vertical-align: bottom;" src="~$SITE_URL`/profile/images/registration_new/alert.gif"/>
Partner min age should be less than partner max age.
</div>
</div>

<div class="row4">
<label style="width:115px;text-align:right;margin-right:10px;">Height :</label>
<select name="Min_Height" style="width:120px;" id="minh">~$sf_data->getRaw('minheight')`</select> &nbsp; to &nbsp; <select name="Max_Height" style="width:120px;" id="maxh">~$sf_data->getRaw('maxheight')`</select>
<div class="red_new" id="my_height" style="padding-left:120px;display:none;">
<img style="vertical-align: bottom;" src="~$SITE_URL`/profile/images/registration_new/alert.gif"/>
Partner min height should be less than partner max height.
</div>
</div>
<div class="sp8"></div>

<div>
<div class="lf gray b t12" style="width:115px;text-align:right;margin-right:10px">Marital Status :</div>
<div class="lf t11" style="width:260px">
<div style="padding:0 5px 0 2px;"><div class="lf">Select Items</div><div class="rf"><a id="partner_mstatus_select_all" onclick="add_checkboxes(this); remove_doesnt_matter_conflict(this);" class="blink">Select All</a></div></div>
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
<div style="padding:0 5px 0 2px;"><div class="lf">Selected Items</div><div class="rf"><a id="partner_mstatus_clear_all" onclick="remove_checkboxes(this); remove_doesnt_matter_conflict(this);" class="blink">Clear All</a></div></div>
<div class="lf scrollbox3 t12">
	<div style="overflow:hidden;" id="partner_mstatus_target_div">
	<div id="partner_mstatus_DM"><label>Doesn't Matter</label></div>
	</div>
</div></div></div>

<div class="sp12"></div>
<div class="row4" id="Have_child">
<label style="width:115px;text-align:right;margin-right:10px;">Have Children :</label>
<input type="radio" class="chbx" name="partner_children" value="" id="partner_children" style="vertical-align:middle" ~if $partner_children eq ""`checked~/if`> Doesn't Matter &nbsp;&nbsp;&nbsp;<input type="radio" class="chbx" name="partner_children" value="Y" id="partner_children" style="vertical-align:middle" ~if $partner_children eq "Y"`checked~/if`> Yes &nbsp;&nbsp;&nbsp;<input type="radio" class="chbx" name="partner_children" value="N" id="partner_children" style="vertical-align:middle" ~if $partner_children eq "N"`checked~/if`> No
</div>
<div class="sp8"></div>
<div>
<div class="lf gray b t12" style="width:115px;text-align:right;margin-right:10px">Country living in :</div>
<div class="lf t11" style="width:260px">
<div style="padding:0 5px 0 2px;"><div class="lf">Select Items</div><div class="rf"><a id="partner_country_select_all" onclick="add_checkboxes(this); " class="blink">Select All</a></div></div>
 <input type="hidden" id="partner_country_selected" value="" style="display:none;">
<div class="lf scrollbox3 t12">
		<input type="hidden" name="partner_country_str" id="partner_country_str" ~if $checked_country` value="~$checked_country`" ~else` value="" ~/if`>
	<div style="display:none" id="partner_country_div">
		<input type="checkbox" name="partner_country_arr[]" id="partner_country_DM" value="DM"><label id="partner_country_label_DM">Any</label><br>
                ~$sf_data->getRaw('hidden_country')`
        </div>
        <div style="overflow:hidden;" id="partner_country_source_div">
        	~$sf_data->getRaw('shown_country')`
        </div>
</div>
</div>
<div class="lf t11" style="width:260px; margin-left:20px;">
<div style="padding:0 5px 0 2px;"><div class="lf">Selected Items</div><div class="rf"><a id="partner_country_clear_all" onclick="remove_checkboxes(this); " class="blink">Clear All</a></div></div>
<div class="lf scrollbox3 t12">
	<div style="overflow:hidden;" id="partner_country_target_div">
        <div style="overflow:hidden;" id="partner_country_target_div">Any</div>
	</div>
</div></div></div>

<div class="sp12"></div>

<div style="padding:5px;width:96%;display:none;" id="city">
<div class="lf gray b t12" style="width:115px;text-align:right;margin-right:10px">City/State :</div>
<div class="lf t11" style="width:260px">
<div style="padding:0 5px 0 2px;"><div class="lf">Select Items</div><div class="rf"><a id="partner_city_select_all" onclick="add_checkboxes(this); " class="blink">Select All</a></div></div>
<div class="lf scrollbox3 t12">
	<input type="hidden" id="partner_city_selected" value="">
		<input type="hidden" name="partner_city_str" id="partner_city_str"  value="~$checked_city`" >
	 <div style="display:none" id="partner_city_div">
                                                        <input type="checkbox" name="partner_city_arr[]" id="partner_city_DM" value="DM"><label id="partner_city_label_DM">Any</label><br>
                                                        ~$sf_data->getRaw('hidden_city')`
                                                        <input type="checkbox" value="0" name="partner_city_arr[]" id="partner_city_0"> <label id="partner_city_label_0">Others</label><br>

	 </div>
	<div style="overflow:hidden;" id="partner_city_source_div">
		~$sf_data->getRaw('shown_city')`
                                                        <input type="checkbox" class="chbx" name="partner_city_displaying_arr[]" id="partner_city_displaying_0" value="0"><label id="partner_city_displaying_label_0">Others</label><br>
	</div>
</div>
</div>
<div class="lf t11" style="width:260px; margin-left:20px;">
<div style="padding:0 5px 0 2px;"><div class="lf">Selected Items</div><div class="rf"><a id="partner_city_clear_all" onclick="remove_checkboxes(this); " class="blink">Clear All</a></div></div>
<div class="lf scrollbox3 t12">
	<div style="overflow:hidden;" id="partner_city_target_div">
        Any</div>
</div></div></div>


</div>
<input type="hidden" name="city" id="city" value="~$city_str`">
<script>
	showChild();
	fill_default_val();
        var ppbd = new Array("partner_mstatus","partner_country");
        fill_details(ppbd);
	~if $checked_city`
        var pr_arr= new Array("partner_city");
        fill_details(pr_arr);
	~/if`
</script>
