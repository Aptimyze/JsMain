import React from 'react';

export default class CareerInfo extends React.Component {
	constructor(props) {
        super();
    }
    render() {
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

        var plan_to_work;
        if(this.props.about.plan_to_work) 
        {   
            plan_to_work = <div>
                <i className="vpro_sprite vpro_pin"></i>
                <div className="fontlig dispibl padl5 vpro_wordwrap vtop" id="vpro_abroad" >
                    {this.props.about.plan_to_work}
                </div>
            </div>;
        }

    	var abroad;
    	if(this.props.about.abroad) 
    	{
    		abroad = <div>
    			<i className="vpro_sprite vpro_pin"></i>
				<div className="fontlig dispibl padl5 vpro_wordwrap vtop vtop" id="vpro_abroad" >
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
                    {plan_to_work}
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
        if(this.props.about.post_grad) {
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
        }
    	

    	var under_grad;
        if(this.props.about.under_grad) {
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
    	if(this.props.about.myedu || this.props.about.post_grad || this.props.about.under_grad || this.props.about.school) 
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

    	return(
    		<div>
	    		{occupationSection}
	  			{educationSection}
    		</div>
    	);
    }
}