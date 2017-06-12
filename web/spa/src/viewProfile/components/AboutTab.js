import React from 'react';

class AboutTab extends React.Component {
	constructor(props) {
        super();
        console.log(props);
    }
    render() {
    	var have_child = "";
    	if(this.props.about.have_child)
    	{
			have_child = this.props.about.have_child;
    	}

    	var myInfo = <div className='hgt10'></div>;
    	if(this.props.about.myinfo) 
    	{
    		myInfo = <div className="fontlig pad2 wordBreak vpro_lineHeight" id="vpro_myinfo" >{this.props.about.myinfo}</div>;
    	}

    	var appearanceTitle,appearance;
    	if(this.props.about.appearance) 
    	{
    		appearanceTitle = <div className="f14 color1">Appearance</div>
    		appearance = <div className="fontlig pb15" id="vpro_appearance">{this.props.about.appearance}</div>
    	}
    	var special_case, special_case_title;
    	if(this.props.about.special_case) 
    	{
    		special_case_title = <div className="f14 color1">Special Cases</div>;
    		special_case = <div class="fontlig pb15" id="vpro_special_case" >{this.props.about.special_case}</div>
    	}
    	
    	var mycareer; 
    	if(this.props.about.mycareer) {
    		mycareer = <div className="fontlig pb15 wordBreak vpro_lineHeight" id="vpro_mycareer">{this.props.about.mycareer}</div>;
    	}

    	var work_status;
    	if(this.props.about.work_status.label || this.props.about.work_status.value || this.props.about.work_status.company)
    	{
    		work_status = <div>
	    		<div className="f12 color1">
	    			{this.props.about.work_status.label}
	    		</div>
	          	<div className="fontlig pb15" >
	          		<span id="vpro_work_status">
	          			{this.props.about.work_status.value}
	          		</span>
	          		<br/>
	                <span id="vpro_company"> 
	                	{this.props.about.work_status.company}
	                </span> 
	            </div>
	        </div>;
    	}

    	var earning;
    	if(this.props.about.earning) 
    	{	
    		earning = <div>
    			<div className="f12 color1">Earning</div>
		  		<div className="fontlig pb15" id="vpro_earning" >{this.props.about.earning}
		  		</div>
    		</div>;
    	}

    	var abroad;
    	if(this.props.about.earning) 
    	{
    		abroad = <div>
    			<div className="fl">
    				<i className="vpro_sprite vpro_pin"></i>
    			</div>
				<div className="fontlig padl5 fl vpro_wordwrap" id="vpro_abroad" >
					{this.props.about.abroad}
				</div>
    		</div>;
    	} 

    	var occupationSection;
    	if(this.props.about.mycareer || this.props.about.work_status.label || this.props.about.work_status.value || this.props.about.work_status.company || this.props.about.earning || this.props.about.plan_to_work || this.props.about.abroad)
    	{
    		occupationSection = <div className="pad5 bg4 fontlig color3 clearfix f14">
	    		<div className="fl">
	    			<i className="vpro_sprite vpro_career"></i>
	    		</div>
	    		<div className="fl color2 f14 vpro_padlTop" id="vpro_careerSection">Career</div>
	  			<div className="clr hgt10"></div>
	  			{mycareer}
	  			{work_status}
	  			{earning}
	  			<div className="clearfix">
	  				{abroad}
	  			</div>
	  		</div>;
    	}

    	var myedu;
    	if(this.props.about.myedu)
    	{
    		myedu = <div className="fontlig pb15 wordBreak vpro_lineHeight" id="vpro_myedu">{this.props.about.myedu}</div>;
    	}

    	var post_grad;
    	if(this.props.about.post_grad.deg || this.props.about.post_grad.name)
    	{
    		post_grad = <div>
    			<div className="f12 color1">Post Graduation</div>
          		<div className="fontlig pb15">
          			<span id="vpro_post_grad_deg">
          				{this.props.about.post_grad.deg}
          			</span><br/>
              		<span id="vpro_post_grad_name">
              			{this.props.about.post_grad.name}
              		</span>
              	</div>
            </div>;
    	}

    	var under_grad;
    	if(this.props.about.under_grad.deg || this.props.about.under_grad.name)
    	{
    		under_grad = <div>
    			<div className="f12 color1">Under Graduation</div>
          		<div className="fontlig pb15">
          			<span id="vpro_under_grad_deg">
          				{this.props.about.under_grad.deg}
          			</span><br/>
              		<span id="vpro_under_grad_name">
              			{this.props.about.under_grad.name}
              		</span>
              	</div>
            </div>;
    	}

    	var school;
    	if(this.props.about.school) 
    	{
    		school = <div>
    			<div className="f12 color1">School</div>
		  		<div className="fontlig pb15" id="vpro_school" >
		  			{this.props.about.school}
		  		</div>
    		</div>;
    	}
    	
    	var educationSection;
    	if(this.props.about.myedu || this.props.about.post_grad.deg || this.props.about.post_grad.name || this.props.about.under_grad.deg || this.props.about.under_grad.name || this.props.about.school) 
    	{
    		educationSection = <div className="pad5 bg4 fontlig color3 clearfix f14">
    			<div className="fl">
    				<i className="vpro_sprite vpro_edu"></i>
    			</div>
	  			<div className="fl color2 f14 vpro_padlTop" id="vpro_educationSection">Education</div>
	  			<div className="clr hgt10"></div>
	  			{myedu}
	  			{post_grad}
	  			{under_grad}
	  			{school}
    		</div>;
    	}

    	var city_country;
    	if(this.props.about.city_country) 
    	{
    		city_country = <div>
    			<div className="f12 color1">City, Country of Birth</div>
            	<div className="fontlig pb15" id="vpro_city_country" >
            		{this.props.about.city_country}
            	</div>
    		</div>;
    	}
    	var date_time;
    	if(this.props.about.date_time) 
    	{
    		date_time = <div>
    			<div className="f12 color1">Date &amp; Time of Birth</div>
            	<div className="fontlig pb15" id="vpro_date_time" >
            		{this.props.about.date_time}
            	</div>
    		</div>;
    	}

    	var rashi;
    	if(this.props.about.more_astro.rashi) {
    		rashi =  <div className="clearfix">
                <div className="fontlig fl vpro_wordwrap" id="vpro_more_astro_rashi" >
                	{this.props.about.more_astro.rashi}
                </div>
            </div>;
    	}

    	var nakshatra;
    	if(this.props.about.more_astro.nakshatra) {
    		nakshatra =  <div className="clearfix">
                <div className="fontlig fl vpro_wordwrap" id="vpro_more_astro_nakshatra" >
                	{this.props.about.more_astro.nakshatra}
                </div>
            </div>;
    	}

    	var downloadHoroscope;
    	if(this.props.about.othersHoroscope == "Y" && (this.props.about.toShowHoroscope == "Y" || this.props.about.toShowHoroscope == ""))
    	{
    		downloadHoroscope = <div>
    			????<br/>
    			<a href = "#">
    				<button className="fontlig lh40 astroBtn1 wid49p">Download Horoscope</button>
    				<button className="fontlig lh40 astroBtn1 fr  js-freeAstroComp wid48p">Get Astro Report</button>
    			</a>
    		</div>
    	}

    	var horoscope;
    	if(this.props.about.sameGender != 1) 
    	{
    		horoscope = <div className="clearfix pb20 pt20">
    		 {downloadHoroscope}
    		</div>;
    	}

    	var horo_match
    	if(this.props.about.more_astro.horo_match) 
    	{
    		horo_match = <div className="clearfix pt10">
                <div className="fl">
                	<i className="vpro_sprite vpro_pin"></i>
                </div>
                <div className="fontlig padl5 fl vpro_wordwrap" id="vpro_more_astro_horo_match">{this.props.about.more_astro.horo_match}
                </div>
            </div>
    	}

    	var more_astro;
    	if(this.props.about.more_astro) 
    	{
    		more_astro = <div>
    			<div className="f12 color1">More</div>
            	<div className="fontlig pb15">
            	{rashi}
            	{nakshatra}
            	{horoscope}
            	{horo_match}
            	<div className="clearfix vpro_dn" id="gunaScore">
                </div>
            	</div>
    		</div>;
    	}

    	var muslim_m;
    	if(this.props.about.muslim_m) 
    	{
    		muslim_m = "?????";
    	}


    	var Religious;
    	if(this.props.about.muslim_m || this.props.about.sikh_m || this.props.about.christian_m) 
    	{
    		Religious = <div className="pad5 bg4 fontlig color3 clearfix f14">
    			<div className="fl">
    				<i className="vpro_sprite vpro_kund"></i>
    			</div>
     			<div className="fl color2 f14 vpro_padlTop">Religious Beliefs</div>
     			<div className="clr hgt10"></div>
     			{muslim_m}
     			<div className="fontlig pb15" id="vpro_more_sikh">
     				{this.props.about.sikh_m}
     			</div>
     			<div className="fontlig pb15" id="vpro_more_christian">
     				{this.props.about.christian_m}
     			</div>
     		</div>;
    	}

    	var kundliSection;
    	if(this.props.about.city_country || this.props.about.date_time || this.props.about.more_astro) 
    	{
    		kundliSection = <div className="pad5 bg4 fontlig color3 clearfix f14">
    			<div className="fl">
    				<i className="vpro_sprite vpro_kund"></i>
    			</div>
      			<div className="fl color2 f14 vpro_padlTop" id="vpro_astroSection">Kundali & Astro</div>
      			<div className="clr hgt10"></div>
      			{city_country}
      			{date_time}
      			{more_astro}
    		</div>;
    	}

    	var lifestyle;
    	if(this.props.life.lifestyle)
    	{
    		lifestyle = <div>
    			<div className="f12 color1">Habits</div>
				<div className="fontlig pb15" id="vpro_lifestyle" >
					{this.props.life.lifestyle}
				</div>
			</div>
    	}

    	var res_status;
    	if(this.props.life.res_status)
    	{
    		res_status = <div>
    			<div className="f12 color1">Residential Status</div>
				<div className="fontlig pb15" id="vpro_res_status" >
					{this.props.life.res_status}
				</div>
			</div>
    	}

    	var assets;
    	if(this.props.life.assets)
    	{
    		assets = <div>
    			<div className="f12 color1">Assets</div>
				<div className="fontlig pb15" id="vpro_res_assets" >
					{this.props.life.assets}
				</div>
			</div>
    	}
    	var skills;
    	if(this.props.life.i_cook)
    	{
    		skills = <div>
    			<div className="f12 color1">Skills</div>  
				<div className="fontlig pb15">
					<div id="i_cook">{this.props.life.skills_i_cook}</div> 
				</div>
			</div>
    	}

    	var hobbies;
    	if(this.props.life.hobbies)
    	{
    		hobbies = <div>
    			<div className="f12 color1">Hobbies</div>
				<div className="fontlig pb15" id="vpro_hobbies">
					{this.props.life.hobbies}
				</div>
    		</div>;
    	}

    	var interest;
    	if(this.props.life.interest)
    	{
    		interest = <div>
    			<div className="f12 color1">Interests</div>
				<div className="fontlig pb15" id="vpro_interest">
					{this.props.life.interest}
				</div>
    		</div>;
    	}

    	var dress_style;
    	if(this.props.life.dress_style)
    	{
    		dress_style = <div>
    			<div className="f12 color1">Dress style</div>
				<div className="fontlig pb15" id="vpro_dress_style">
					{this.props.life.dress_style}
				</div>
    		</div>;
    	}

		var fav_tv_show;
    	if(this.props.life.fav_tv_show)
    	{
    		fav_tv_show = <div>
    			<div className="f12 color1">Favorite TV shows</div>
				<div className="fontlig pb15" id="vpro_fav_tv_show">
					{this.props.life.fav_tv_show}
				</div>
    		</div>;
    	}    
    	
    	var fav_book;
    	if(this.props.life.fav_book)
    	{
    		fav_book = <div>
    			<div className="f12 color1">Favorite books</div>
				<div className="fontlig pb15" id="vpro_fav_book">
					{this.props.life.fav_book}
				</div>
    		</div>;
    	}    

    	var fav_movies;
    	if(this.props.life.fav_movies)
    	{
    		fav_movies = <div>
    			<div className="f12 color1">Favorite Movies</div>
				<div className="fontlig pb15" id="vpro_fav_movies">
					{this.props.life.fav_movies}
				</div>
    		</div>;
    	}    

    	var fav_cuisine;
    	if(this.props.life.fav_cuisine)
    	{
    		fav_cuisine = <div>
    			<div className="f12 color1">Favorite cuisine</div>
				<div className="fontlig pb15" id="vpro_fav_cuisine">
					{this.props.life.fav_cuisine}
				</div>
    		</div>;
    	}   

    	var LifestyleSection;
    	if(this.props.life || this.props.life.assets || this.props.life.skills_speaks || this.props.life.skills_i_cook || this.props.life.hobbies || this.props.life.interest || this.props.life.dress_style || this.props.life.fav_tv_show || this.props.life.fav_book || this.props.life.fav_movies || this.props.life.fav_cuisine)
    	{
    		LifestyleSection = <div className="pad5 bg4 fontlig color3 clearfix f14">
    			<div className="fl">
    				<i className="vpro_sprite vpro_lstyle"></i>
    			</div>
	  			<div className="fl color2 f14 vpro_padlTop" id="vpro_lifestyleSection">Lifestyle</div>
	  			<div className="clr hgt10"></div>
	  			{lifestyle}
	  			{res_status}
	  			{assets}
	  			{skills}
	  			{hobbies}
	  			{interest}
	  			{dress_style}
	  			{fav_tv_show}
	  			{fav_book}
	  			{fav_movies}
	  			{fav_cuisine}
	  			<div className="f12 color1 pb20 wordBreak" id="vpro_posted_by">{this.props.about.posted_by}</div>
    		</div>;
    	}


    	return (
		    <div id="AboutTab">
				<div className="pad5 bg4 fontlig color3 clearfix f14">
    				<div className="hgt10"></div>
  					<div className="fl"> 
	  					<span className="f18" id="vpro_username" >{this.props.about.username}</span>&nbsp;&nbsp;
	  					<span className="f11 color13" id="vpro_last_active" >{this.props.about.last_active}</span> 
  					</div>
  					<div className="fr color2 f14 pt5 fontrobbold" id="vpro_subscription">{this.props.about.subscription_icon}</div>
  					<div className="clr hgt10"></div>
  					<ul className="vpro_info fontlig">
	  					<li className="wid49p" id="vpro_age" >
	  						{this.props.about.age} Years&nbsp;  
	  						<span id="vpro_height">{this.props.about.height}</span>
	  					</li>
	  					<li className="wid49p" id="vpro_occupation" >
	  						{this.props.about.occupation}
	  					</li>
	    				<li className=
	    				"wid49p" id="vpro_caste" >
	    					{this.props.about.caste}
	    				</li>
	    				<li className="wid49p" id="vpro_income" >
	    					{this.props.about.income}
	    				</li>
	    				<li className="wid49p" id="vpro_mtongue" >
	    					{this.props.about.mtongue}
	    				</li>
	    				<li className="wid49p" id="vpro_education" >
	    					{this.props.about.educationOnSummary}
	    				</li>
	    				<li className="wid49p" id="vpro_location" >
	    					{this.props.about.location}
	    				</li>
	    				<li className="wid49p wspace" id="vpro_m_status" >
	    					{this.props.about.m_status}&nbsp;
	    					{have_child}
	    				</li>
  					</ul>
  					{myInfo}
  					{appearanceTitle}
  					{appearance}
  					{special_case_title}
  					{special_case}
  				</div>

  				{occupationSection}
  				{educationSection}
  				{kundliSection}
  				{Religious}
  				{LifestyleSection}

			</div>
    	);
    }
}
	
export default AboutTab;
