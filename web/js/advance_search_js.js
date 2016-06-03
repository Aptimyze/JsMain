/*Defining some global variables*/
//defining variable for document.form
//used when changing div class
var alreadyProcessed = false;

//variable to set focus on error
var required_field_name = ""

//age variable.
var age = "";

//used when user selects "Looking for" to change selected gender.
var action = "";

//used to show age error depending on male/female selection.
var gender_val_selected = ""

//submit button variable.
var submit_button_clicked=0;

//storing mstatus selected value
var mstatus_selected_value;

//array of fields which require validation.
var validate_fields = new Array("email","password","confirm_password","fname_user","lname_user","username","gender","day","month","year","partner_age","mstatus","partner_mstatus","has_children","height","partner_height","country_residence","citizenship","city_residence","phone","phone_owner_name","showphone","mobile","mobile_owner_name","showmobile","time_to_call_start","start_am_pm","time_to_call_end","end_am_pm","degree","partner_degree","occupation","income","mtongue","partner_mtongue","religion","partner_religion","caste","caste_entry","partner_caste","termsandconditions");


//defining arrays for different sections to use when changing div class.
var basic_detail_arr = new Array("Gender","Min_Age","Max_Age","Min_Height[]","Max_Height[]","partner_mstatus_displaying_arr[]","partner_mstatus_select_all","partner_mstatus_clear_all","partner_hchild_displaying_arr[]","partner_hchild_select_all","partner_hchild_clear_all","Living_with_parents");
var rel_ethnic_arr = new Array("partner_mtongue_displaying_arr[]","partner_mtongue_select_all","partner_mtongue_clear_all","partner_religion_displaying_arr[]","partner_religion_select_all","partner_religion_clear_all","Sub_caste","partner_manglik_displaying_arr[]","partner_manglik_select_all","partner_manglik_clear_all","Horoscope","partner_sampraday_displaying_arr[]","partner_sampraday_select_all","partner_sampraday_clear_all","partner_mathab_displaying_arr[]","partner_mathab_select_all","partner_mathab_clear_all","speak_urdu","hijab","working_wife","zarathustri","amritdhari","cut_hair","partner_turban_displaying_arr[]","partner_turban_select_all","partner_turban_clear_all","partner_caste_displaying_arr[]","partner_caste_select_all","partner_caste_clear_all");
//var career_edu_arr = new Array("partner_wstatus_displaying_arr[]","partner_wstatus_select_all","partner_wstatus_clear_all","partner_occupation_displaying_arr[]","partner_occupation_select_all","partner_occupation_clear_all","partner_education_displaying_arr[]","partner_education_select_all","partner_education_clear_all","rsLIncome","rsHIncome","doLIncome","DOHIncome","partner_income_select_all","partner_income_clear_all","partner_country_displaying_arr[]","partner_country_select_all","partner_country_clear_all","partner_city_displaying_arr[]","partner_city_select_all","partner_city_clear_all");
var career_edu_arr = new Array("partner_wstatus_displaying_arr[]","partner_wstatus_select_all","partner_wstatus_clear_all","partner_occupation_displaying_arr[]","partner_occupation_select_all","partner_occupation_clear_all","partner_education_displaying_arr[]","partner_education_select_all","partner_education_clear_all","partner_income_select_all","partner_income_clear_all","partner_country_displaying_arr[]","partner_country_select_all","partner_country_clear_all","partner_city_displaying_arr[]","partner_city_select_all","partner_city_clear_all");
var lifestyle_arr = new Array("partner_diet_displaying_arr[]","partner_diet_select_all","partner_diet_clear_all","Drink","Smoke","partner_body_displaying_arr[]","partner_body_select_all","partner_body_clear_all","partner_complexion_displaying_arr[]","partner_complexion_select_all","partner_complexion_clear_all","HIV","partner_handicapped_displaying_arr[]","partner_handicapped_select_all","partner_handicapped_clear_all","partner_nhandicapped_displaying_arr[]","partner_nhandicapped_select_all","partner_nhandicapped_clear_all");
var keyword_arr = new Array("keywords","kwd_rule");
var more_opt_arr = new Array("Photo","Login","Online","Contact_visible");

var partner_fields_array = new Array("partner_mstatus","partner_occupation","partner_education","partner_country","partner_religion","partner_mtongue","partner_body","partner_complexion","partner_diet","partner_hchild","partner_handicapped","partner_nhandicapped","partner_wstatus","partner_smoke","partner_drink");
var registration={
	'input' : function(element){
			element.onclick = function(){
				if(this.name.match("muslim_deno"))
				{
					populate_mathab(this.value);
				}
				else if(this.name.match("Gender"))
				{
					var Min_Age=dID('Min_Age');
					set_default_age_range_as(Min_Age);
					gender_change(this.id);
				}
				else if(this.name=="amritdhari")
					change_amritdhari();
				else if(this.name.match("Submit"))
				{
					if(check_incomeRange())
						check_gender();	
					else
						return false;
				}
				else
					add_checkboxes(this);
				change_div_class(this);
			}
		},

	'select' : function(element){
			element.onfocus = function(){
				change_div_class(this);
			}

		},

	'a' : function(element){
			element.onclick = function(){
				var current_id = this.id
				if(this.id.match("_select_all"))
				{
					add_checkboxes(this);
					return false;
				}
				else if(this.id.match("_clear_all"))
				{
					remove_checkboxes(this);
					return false;
				}
			}
			element.onfocus = function(){
				change_div_class(this);
			}
		}
};
if(page=='AS')
{
	Behaviour.register(registration);
}
function populate_mathab(val)
{
	if(val=='152')
	{
		var mathab= new Array("Hanafi","Hanbali","Maliki","Shafi’I");
		var num=1;
	}
	else if(val=='151')
	{
		var mathab= new Array("Ismaili","Ithna ashariyyah","Zaidi","Dawoodi Bohra");
		var num=5;
	}
	else
	{
		var mathab= new Array("Hanafi","Hanbali","Maliki","Shafi’I","Ismaili","Ithna ashariyyah","Zaidi","Dawoodi Bohra");
		var num=1;
	}
	m1=mathab.length;
	var hidden_vals= new Array();
	var shown_vals= new Array();
	for(var m=0;m<m1;m++)
	{
		var label=mathab[m];
		hidden_vals.push(" <input type=\"checkbox\" value=\"");
		hidden_vals.push(num);
		hidden_vals.push("\" name=\"partner_mathab_arr[]\" id=\"partner_mathab_");
		hidden_vals.push(num);
		hidden_vals.push("\"> <label id=\"partner_mathab_label_");
		hidden_vals.push(num);
		hidden_vals.push("\">");
		hidden_vals.push(label);
		hidden_vals.push("</label><br>");
        	shown_vals.push("<input type=\"checkbox\" class=\"chbx \" name=\"partner_mathab_displaying_arr[]\" id=\"partner_mathab_displaying_");
		shown_vals.push(num);
		shown_vals.push("\" value=\"");
		shown_vals.push(num);
		shown_vals.push("\" onClick=\"add_checkboxes(this);\"><label id=\"partner_caste_displaying_label_");
		shown_vals.push(num);
		shown_vals.push("\">");
		shown_vals.push(label)
		shown_vals.push("</label><br>");
		num++;
	}
	document.getElementById("partner_mathab_div").innerHTML = hidden_vals.join('');
        document.getElementById("partner_mathab_source_div").innerHTML = shown_vals.join('');
	document.getElementById("partner_mathab_target_div").innerHTML= "Any";
}


//Function to change div class i.e to change the background color depending on focus
function change_div_class(obj)
{
	if(in_array(obj.name,basic_detail_arr) || in_array(obj.id,basic_detail_arr))
	{
		document.getElementById("basic_details").className="gray_bg";
		document.getElementById("mstatus_label").className="lf b t12";
		document.getElementById("hchild_label").className="lf b t12";
		
		document.getElementById("rel_label").className="lf gray b t12";
                document.getElementById("mtongue_label").className="lf gray b t12";
                document.getElementById("rel_caste").className="lf gray b t12";
                document.getElementById("sampraday_label").className="lf gray b t12";
                document.getElementById("turban_label").className="lf gray b t12";
                document.getElementById("mathab_label").className="lf gray b t12";
                document.getElementById("manglik_label").className="lf gray b t12";

		document.getElementById("work_label").className="lf gray b t12";
                document.getElementById("occ_label").className="lf gray b t12";
                document.getElementById("edu_label").className="lf gray b t12";
                document.getElementById("country_label").className="lf gray b t12";
                document.getElementById("income_label").className="lf gray b t12";
                document.getElementById("city_label").className="lf gray b t12";

		document.getElementById("challenged_label").className="lf gray b t12";
                document.getElementById("nhandicap_label").className="lf  gray b t12";
                document.getElementById("diet_label").className="lf  gray b t12";
                document.getElementById("body_label").className="lf  gray b t12";
                document.getElementById("complexion_label").className="lf  gray b t12";

		document.getElementById("rel_ethnic").className="lf gray";
		document.getElementById("career_edu").className="lf gray";
		document.getElementById("lifestyle").className="lf gray";
		document.getElementById("keyword_search").className="lf gray";
		document.getElementById("more_options").className="lf gray";
	}
	else if(in_array(obj.name,rel_ethnic_arr) || (in_array(obj.id,rel_ethnic_arr)))
        {
		document.getElementById("basic_details").className="lf gray";
		document.getElementById("mstatus_label").className="lf gray b t12";
		document.getElementById("hchild_label").className="lf gray b t12";

		document.getElementById("rel_ethnic").className="gray_bg";
		document.getElementById("rel_label").className="lf b t12";
		document.getElementById("mtongue_label").className="lf b t12";
		document.getElementById("rel_caste").className="lf b t12";
                document.getElementById("sampraday_label").className="lf b t12";
                document.getElementById("turban_label").className="lf b t12";
                document.getElementById("mathab_label").className="lf b t12";
                document.getElementById("manglik_label").className="lf b t12";

                document.getElementById("income_label").className="lf gray b t12";
		document.getElementById("work_label").className="lf gray b t12";
                document.getElementById("occ_label").className="lf gray b t12";
                document.getElementById("edu_label").className="lf gray b t12";
                document.getElementById("country_label").className="lf gray b t12";
                document.getElementById("city_label").className="lf gray b t12";

		document.getElementById("challenged_label").className="lf gray b t12";
                document.getElementById("diet_label").className="lf  gray b t12";
                document.getElementById("body_label").className="lf  gray b t12";
                document.getElementById("complexion_label").className="lf  gray b t12";
                document.getElementById("nhandicap_label").className="lf  gray b t12";

		document.getElementById("career_edu").className="lf gray";
		document.getElementById("lifestyle").className="lf gray";
		document.getElementById("keyword_search").className="lf gray";
		document.getElementById("more_options").className="lf gray";
        }
	else if(in_array(obj.name,career_edu_arr) || (in_array(obj.id,career_edu_arr)))
        {
		document.getElementById("basic_details").className="lf gray";
		document.getElementById("hchild_label").className="lf gray b t12";
		document.getElementById("mstatus_label").className="lf gray b t12";

		document.getElementById("rel_ethnic").className="lf gray";
                document.getElementById("sampraday_label").className="lf gray b t12";
                document.getElementById("turban_label").className="lf gray b t12";
		document.getElementById("rel_caste").className="lf gray b t12";
		document.getElementById("mathab_label").className="lf gray b t12";
                document.getElementById("manglik_label").className="lf gray b t12";

		document.getElementById("career_edu").className="gray_bg";
		document.getElementById("work_label").className="lf b t12";
		document.getElementById("occ_label").className="lf b t12";
		document.getElementById("edu_label").className="lf b t12";
		document.getElementById("country_label").className="lf b t12";
                document.getElementById("income_label").className="lf b t12";
		document.getElementById("city_label").className="lf b t12";

		document.getElementById("challenged_label").className="lf gray b t12";
                document.getElementById("diet_label").className="lf  gray b t12";
                document.getElementById("body_label").className="lf  gray b t12";
                document.getElementById("complexion_label").className="lf  gray b t12";
		document.getElementById("nhandicap_label").className="lf  gray b t12";

		document.getElementById("lifestyle").className="lf gray";
		document.getElementById("keyword_search").className="lf gray";
		document.getElementById("more_options").className="lf gray";
        }
	else if(in_array(obj.name,lifestyle_arr) || (in_array(obj.id,lifestyle_arr)))
        {
		document.getElementById("basic_details").className="lf gray";
		document.getElementById("mstatus_label").className="lf gray b t12";		
		document.getElementById("hchild_label").className="lf gray b t12";

		document.getElementById("rel_ethnic").className="lf gray";
                document.getElementById("sampraday_label").className="lf gray b t12";
                document.getElementById("turban_label").className="lf gray b t12";
		document.getElementById("rel_label").className="lf gray b t12";
                document.getElementById("mtongue_label").className="lf gray b t12";
                document.getElementById("rel_caste").className="lf gray b t12";
                document.getElementById("mathab_label").className="lf gray b t12";
                document.getElementById("manglik_label").className="lf gray b t12";

		document.getElementById("career_edu").className="lf gray";
                document.getElementById("work_label").className="lf gray b t12";
                document.getElementById("occ_label").className="lf gray b t12";
                document.getElementById("income_label").className="lf gray b t12";
                document.getElementById("edu_label").className="lf gray b t12";
                document.getElementById("country_label").className="lf gray b t12";
                document.getElementById("city_label").className="lf gray b t12";

		document.getElementById("lifestyle").className="gray_bg";
                document.getElementById("diet_label").className="lf b t12";
                document.getElementById("body_label").className="lf b t12";
                document.getElementById("complexion_label").className="lf  b t12";
		document.getElementById("challenged_label").className="lf b t12";
		document.getElementById("nhandicap_label").className="lf  b t12";

		document.getElementById("keyword_search").className="lf gray";
		document.getElementById("more_options").className="lf gray";
        }
	else if(in_array(obj.name,keyword_arr))
        {
		document.getElementById("basic_details").className="lf gray";
		document.getElementById("rel_ethnic").className="lf gray";
		document.getElementById("career_edu").className="lf gray";
		document.getElementById("lifestyle").className="lf gray";
		document.getElementById("keyword_search").className="gray_bg";
		document.getElementById("more_options").className="lf gray";
        }
	else if(in_array(obj.name,more_opt_arr))
        {
		document.getElementById("basic_details").className="lf gray";
		document.getElementById("rel_ethnic").className="lf gray";
		document.getElementById("career_edu").className="lf gray";
		document.getElementById("lifestyle").className="lf gray";
		document.getElementById("keyword_search").className="lf gray";
		document.getElementById("more_options").className="gray_bg";
	}
}

/*Function to show caste depending on selected religion*/
function populate_caste_from_religion_as(religions)
{
	var hidden_vals = new Array();
	var shown_vals = new Array();
	var j1= religions.length;
	var others;
	hidden_vals.push("<input type=\"hidden\" name=\"partner_caste_str\" id=\"partner_caste_str\" value=\"\">");
        hidden_vals.push(" <input type=\"checkbox\" value=\"DM\" name=\"partner_caste_arr[]\" id=\"partner_caste_DM\"> <label id=\"partner_caste_label_DM\">Any</label><br>");
	for( var j=0; j<j1;j++)
	{
		religion_value=religions[j];
		if(religion_value!='DM')
		{
			var caste_arr = religion_value.split("|X|");
			if(caste_arr[0]=='1')
				others='242';
			else if(caste_arr[0]=='3')
				others='244';
			else if(caste_arr[0]=='9')
				others='246';
			else if(caste_arr[0]=='2')
				others='243';
			else if(caste_arr[0]=='4')
				others='245';
		
			var caste_string = caste_arr[1].split("#");
			var i1 = caste_string.length;
			var caste_dropdown_array = new Array();
			var caste, caste_option,flag;
			for(var i=0;i<i1-1;i++)
			{
				caste = caste_string[i].split("$");
				if(!((caste[0]==14)||(caste[0]==149)||(caste[0]==154)||(caste[0]==173)||(caste[0]==2)))
				{
                        		caste[1]=caste[1].replace(/:/g,' ');
                        		hidden_vals.push("<input type=\"checkbox\" value=");
                        		hidden_vals.push(caste[0]);
                        		hidden_vals.push(" name=\"partner_caste_arr[]\" id=\"partner_caste_");
                        		hidden_vals.push(caste[0]);
                        		hidden_vals.push("\"> <label id=\"partner_caste_label_");
                        		hidden_vals.push(caste[0]);
                        		hidden_vals.push("\">");
                        		hidden_vals.push(caste[1]);
                        		hidden_vals.push("</label><br>");

					if(!document.getElementById("partner_caste_selected").value.match(caste[0]))
					{
                        		shown_vals.push("<input type=\"checkbox\" class=\"chbx \" name=\"partner_caste_displaying_arr[]\" id=\"partner_caste_displaying_");
                        		shown_vals.push(caste[0]);
                        		shown_vals.push("\" value=\"");
                        		shown_vals.push(caste[0]);
                        		shown_vals.push("\" onClick=\"add_checkboxes(this); ");
					if(document.getElementsByName('type').value=='AS')
                        			shown_vals.push(" change_div_class(this);");
                        		shown_vals.push(" \"><label id=\"partner_caste_displaying_label_");
                        		shown_vals.push(caste[0]);
                        		shown_vals.push("\">");
                        		shown_vals.push(caste[1]);
                        		shown_vals.push("</label><br>");
					}
				}
			}
		}
	}
	hidden_vals.push(" <input type=\"checkbox\" value=\"");
	hidden_vals.push(others);
	hidden_vals.push("\" name=\"partner_caste_arr[]\" id=\"partner_caste_");
	hidden_vals.push(others);
	hidden_vals.push("\"> <label id=\"partner_caste_label_");
	hidden_vals.push(others);
	hidden_vals.push("\">Others</label><br>");

	shown_vals.push("<input type=\"checkbox\" class=\"chbx \" name=\"partner_caste_displaying_arr[]\" id=\"partner_caste_displaying_");
	shown_vals.push(others);
	shown_vals.push("\" value=\"");
	shown_vals.push(others);
	shown_vals.push("\" onClick=\"add_checkboxes(this);\"><label id=\"partner_caste_displaying_label_");
	shown_vals.push(others);
	shown_vals.push("\">Others</label><br>");

	document.getElementById("partner_caste_div").innerHTML = hidden_vals.join('');
        document.getElementById("partner_caste_source_div").innerHTML = shown_vals.join('');

	if(document.getElementById("partner_caste_selected").value)
	{
		document.getElementById("partner_caste_str").value=document.getElementById("partner_caste_selected").value;
		var fill_arr=  new Array('partner_caste');	
		fill_details(fill_arr);
	}
}

/*populate city depending on country(for advance search)*/
function populate_city_new(country_selected,searched)
{ 
        
	var j1=country_selected.length;
	var country;var country_arr;
	var city_value,city_label;
	var city_arr = new Array();
	var hidden_vals = new Array();
	var shown_vals = new Array();
	var j1=country_selected.length;
        var indiaIsTHere=0;
        hidden_vals.push(" <input type=\"checkbox\" value=\"DM\" name=\"partner_city_arr[]\" id=\"partner_city_DM\"> <label id=\"partner_city_label_DM\">Any</label><br>");
	for (var j=0;j<j1;j++)
	{	
		var city_arr= country_selected[j].split("#");
		var country=city_arr[0];
		if(country!='51')
                    continue;
                else
                    indiaIsTHere=1;
		
		var i1 = city_arr.length;
		var city= new Array();
                var cityLabel=0;
                var stateLabel=0;
                var MajIndian=0;
                if(country=='51' && MajIndian==0)
                    {   
                        MajIndian=1;
                        shown_vals.push("<span style=\"color:#0a89fe\">Major Indian Cities</span><div class=\"clear\" style=\"line-height:5px;\">&#160;</div>");
                        //var popcity= new Array('NCR:Delhi/NCR','KA02:Banglore','TN02:Chennai','AP03:Hyderabad','WB05:Kolkata','MH04:Mumbai','MH08:Pune');
			var popcity= new Array('NCR:Delhi/NCR','MNCR:Mumbai/Mumbai Region','KA02:Banglore','TN02:Chennai','AP03:Hyderabad/Secunderabad','WB05:Kolkata','MH08:Pune/Chinchwad');
			var v,l;
			for (var ij=0;ij<7;ij++)
			{
				city=popcity[ij].split(":");
				v=city[0];
				l=city[1];
                                shown_vals.push("<input type=\"checkbox\" class=\"chbx \" name=\"partner_city_displaying_arr[]\" id=\"partner_city_displaying_");
				shown_vals.push(v);
				shown_vals.push("\" value=");
				shown_vals.push(v);
				shown_vals.push(" onClick=\"add_checkboxes(this); ");
				if(page=='AS')
	                                shown_vals.push(" change_div_class(this);");
				shown_vals.push(" \"><label id=\"partner_city_displaying_label_");
				shown_vals.push(l+"\"");
                                if(typeof l!="undefined")
                                    shown_vals.push(" finder=\""+l.toLowerCase()+"\" ");
				shown_vals.push(">");
				shown_vals.push(l);
				shown_vals.push("</label><br>");
			}
			hidden_vals.push("<input type=\"checkbox\" value=\"NCR\" name=\"partner_city_arr[]\" id=\"partner_city_NCR\"> <label id=\"partner_city_label_NCR\">Delhi/NCR</label><br>");
                        hidden_vals.push();
                        hidden_vals.push(" name=\"partner_city_arr[]\" id=\"partner_city_");
                        hidden_vals.push(city_value);
                        hidden_vals.push("\"> <label id=\"partner_city_label_");
                        hidden_vals.push(city_value+"\"");
                        if(typeof city_label!="undefined")
                            shown_vals.push(" finder=\""+city_label.toLowerCase()+"\" ");
                        hidden_vals.push(">");
                        hidden_vals.push(city_label);
                        hidden_vals.push("</label><br>");

			//mumbai region
			hidden_vals.push("<input type=\"checkbox\" value=\"MNCR\" name=\"partner_city_arr[]\" id=\"partner_city_MNCR\"> <label id=\"partner_city_label_MNCR\">Mumbai/Mumbai Region</label><br>");
			hidden_vals.push();
			hidden_vals.push(" name=\"partner_city_arr[]\" id=\"partner_city_");
			hidden_vals.push(city_value);
			hidden_vals.push("\"> <label id=\"partner_city_label_");
			hidden_vals.push(city_value+"\"");
                        if(typeof city_label!="undefined")
                            hidden_vals.push(" finder=\""+city_label.toLowerCase()+"\" ");
			hidden_vals.push(">");
			hidden_vals.push(city_label);
			hidden_vals.push("</label><br>");
			//mumbai region
                        
		}
                for (var i=1;i<i1;i++)
		{
			city=city_arr[i].split("|");
			city_value=city[1];
			city_label=city[0];
			city_label=city_label.replace(/:/g,' ');
                        if(city_label.search(searched)==-1)
                            continue;
                            
                        if(city_value.length<3){
                            var catLabelTop="Indian States";
                            stateLabel++;
                        }
                        else{
                            var catLabelTop="All Indian Cities";
                            cityLabel++;
                        }

                       
                       
                       
                       if(stateLabel==1 || cityLabel==1){
                            if(stateLabel==1)
                                stateLabel=2;
                            else if(cityLabel==1)
                                cityLabel=2;
                            hidden_vals.push("<span style=\"color:#0a89fe\">");
                            hidden_vals.push(catLabelTop);
                            hidden_vals.push("</span>");
                            hidden_vals.push("<div class=\"clear\" style=\"line-height:5px;\">&#160;</div>");
                        }
                        if(stateLabel==2 || cityLabel==2){
                            if(stateLabel==2)
                                stateLabel=3;
                            else if(cityLabel==2)
                                cityLabel=3;
                        shown_vals.push("<span style=\"color:#0a89fe\">");
                        shown_vals.push(catLabelTop);
                        shown_vals.push("</span>");
                        shown_vals.push("<div class=\"clear\" style=\"line-height:5px;\">&#160;</div>");
                         }
            
                         
                 	hidden_vals.push("<input abc=\"a\" type=\"checkbox\" value=");
			hidden_vals.push(city_value);
			hidden_vals.push(" name=\"partner_city_arr[]\" id=\"partner_city_");
			hidden_vals.push(city_value);
			hidden_vals.push("\"> <label id=\"partner_city_label_");
			hidden_vals.push(city_value+"\"");
                        if(typeof city_label!="undefined")
                            hidden_vals.push(" finder=\""+city_label.toLowerCase()+"\" ");
			hidden_vals.push(">");
			hidden_vals.push(city_label);
			hidden_vals.push("</label><br>");
		
                
			shown_vals.push("<input type=\"checkbox\" class=\"chbx \" name=\"partner_city_displaying_arr[]\" id=\"partner_city_displaying_");
			shown_vals.push(city_value);
			shown_vals.push("\" value=");
			shown_vals.push(city_value);
			shown_vals.push(" onClick=\"add_checkboxes(this); ");
                        if(document.getElementsByName('type').value=='AS')
                        	shown_vals.push(" change_div_class(this);");
			shown_vals.push(" \"><label id=\"partner_city_displaying_label_");

			shown_vals.push(city_value+"\"");
                        if(typeof city_label!="undefined")
                            shown_vals.push(" finder=\""+city_label.toLowerCase()+"\" ");
			shown_vals.push(">");
			shown_vals.push(city_label);
			shown_vals.push("</label><br>");
		
            
            
            
            
		}
	}
        if(indiaIsTHere==1){
	hidden_vals.push("<input type=\"checkbox\" value=\"0\" name=\"partner_city_arr[]\" id=\"partner_city_0\"> <label id=\"partner_city_label_0\">Others</label><br>");
	shown_vals.push("<input type=\"checkbox\" class=\"chbx \" name=\"partner_city_displaying_arr[]\" id=\"partner_city_displaying_0\" value=\"0\" onClick=\"add_checkboxes(this); ");
	if(document.getElementsByName('type').value=='AS')
        	shown_vals.push(" change_div_class(this);");
        shown_vals.push(" \"><label id=\"partner_city_displaying_label_0\">Others</label><br>");	
       
        }
        else{
            $("#partner_city_clear_all").click();
            document.getElementById("city").style.display="none";
        }
        document.getElementById("partner_city_div").innerHTML = hidden_vals.join('');
	document.getElementById("partner_city_source_div").innerHTML = shown_vals.join('');
        
        
	if(document.getElementById("partner_city_selected").value && indiaIsTHere)
        {
                document.getElementById("partner_city_str").value=document.getElementById("partner_city_selected").value;
                var fill_arr=  new Array('partner_city');
                fill_details(fill_arr);
        }

}

/*Function to fill certain details on page rethrow
function fill_details(fill_array)
{
        var curname, fields, fields_type, str_name, csv_str, to_tick_id;
        var csv_arr = new Array();
        var i1 = fill_array.length;
        for(var i=0;i<i1;i++)
        {
                curname = fill_array[i] + "_arr[]";
                if(document.getElementsByName(curname))
                {
                        fields = document.getElementsByName(curname);
                        fields_type = fields[0].type;

                        if(fields_type == "checkbox")
                        {
                                str_name = fill_array[i] + "_str";
                                csv_str = document.getElementById(str_name).value;
				if(csv_str && csv_str!='undefined')
                                {
                                        csv_str = rtrim(ltrim(csv_str,"'"),"'");
                                        csv_arr = csv_str.split("','");
                                        var j1 = csv_arr.length;
                                        for(var j=0;j<j1;j++)
                                        {
                                                to_tick_id = fill_array[i] + "_" + csv_arr[j];
                                                if(document.getElementById(to_tick_id))
                                                        document.getElementById(to_tick_id).checked = true;
                                        }
                                        swap_checkboxes(fill_array[i]);
                                }
                                else
                                {
                                        restore_checkboxes(fill_array[i]);
                                }
                        }
                }
        }
}
*/
