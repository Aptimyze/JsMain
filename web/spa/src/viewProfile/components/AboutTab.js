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
    	return (
		    <div id="AboutTab">
				  <BasicInfo about = {this.props.about}/>
          <CareerInfo about = {this.props.about}/>
  				<KundliInfo show_gunascore={this.props.show_gunascore} profilechecksum={this.props.profilechecksum} about = {this.props.about}/>
  				<LifestyleInfo about = {this.props.about} life = {this.props.life}/>
			</div>
    	);
    }
}
	
export default AboutTab;
