import React from 'react';

class AboutTab extends React.Component {
	constructor(props) {
        super();
        console.log(props);
        //props.about.special_case = "handicapped";
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

			</div>
    	);
    }
}
	
export default AboutTab;
