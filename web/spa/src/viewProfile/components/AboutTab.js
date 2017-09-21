import React from "react";
import BasicInfo from "./BasicInfo";
import CareerInfo from "./CareerInfo";
import KundliInfo from "../containers/KundliInfo";
import LifestyleInfo from "./LifestyleInfo";

class AboutTab extends React.Component {
	constructor(props) {
        super();
    }
    render() {
			let space='';
			if(this.props.checkUC)
			{
				 space=	<div className="bg4" style={{'height':'60px'}}></div>;
			}
    	return (
		    <div id="AboutTab" className="mb56">
				  <BasicInfo about = {this.props.about}/>
          <CareerInfo about = {this.props.about}/>
  				<KundliInfo username = {this.props.about.username} show_gunascore={this.props.show_gunascore} profilechecksum={this.props.profilechecksum} about = {this.props.about} astroSent ={this.props.astroSent} />
  				<LifestyleInfo about = {this.props.about} life = {this.props.life}/>
					{space}
				<div className="space30"></div>
			</div>
    	);
    }
}

export default AboutTab;
