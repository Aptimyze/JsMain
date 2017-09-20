import React from 'react';

export default class BasicInfo extends React.Component {
	constructor(props) {
        super();
    }
    render() {
    	let have_child = "";
    	if(this.props.about.have_child)
    	{
      if(this.props.about.m_status == 'Never Married')
			  have_child = this.props.about.have_child;
      else
        have_child = ", "+this.props.about.have_child;
    	}

    	var myInfo = <div className='hgt10'></div>;
    	if(this.props.about.myinfo)
    	{
    		myInfo = <div className="fontlig pad2 wordBreak vpro_lineHeight" id="vpro_myinfo" >
										<div dangerouslySetInnerHTML={{__html:this.props.about.myinfo}} />
								 </div>;
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
    		special_case = <div className="fontlig pb15" id="vpro_special_case" >{this.props.about.special_case}</div>
    	}
    	return (
    		<div className="pad5 bg4 fontlig color3 clearfix f14">
    			<div className="hgt10"></div>
  				<div className="fl">
	  				<span className="f18" id="vpro_username" >{this.props.about.username}</span>&nbsp;&nbsp;
	  				<span className="f11 color13" id="vpro_last_active" >{this.props.about.last_active}</span>
  				</div>
  				<div className="fr color2 f14 pt5 fontrobbold" id="vpro_subscription">{this.props.about.subscription_text}</div>
  				<div className="clr hgt10"></div>
  				<ul className="vpro_info fontlig">
	  				<li className="wid49p" id="vpro_age" >
	  					{this.props.about.age} Years&nbsp;
	  					<span id="vpro_height">{this.props.about.height}</span>
	  				</li>
	  				<li className="wid49p" id="vpro_occupation" >
	  					{this.props.about.occupation}
					</li>
	    			<li className="wid49p" id="vpro_caste" >
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
	    				{this.props.about.m_status}{have_child}
	    			</li>
  				</ul>
  				{myInfo}
  				{appearanceTitle}
  				{appearance}
  				{special_case_title}
  				{special_case}
  			</div>
    	);
    }
}
