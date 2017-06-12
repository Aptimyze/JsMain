import React from "react";
import MyjsHeadHTML from "./MyjsHeader";
import EditBar from "./MyjsEditBar";
import AcceptCount from './MyjsAcceptcount';
import ProfileVisitor from './MyjsProfileVisitor'

require ('../style/jsmsMyjs_css.css');



export default class MyjsPage extends React.Component {
	constructor(props) {
  		super();
  	}
  	render() {
		return(
		  <div id="mainContent">
				  <div className="perspective" id="perspective">
							<div className="" id="pcontainer">
							 <MyjsHeadHTML/>
							 <EditBar/>
							 <AcceptCount/>
							 <ProfileVisitor/>





							</div>
					</div>
			</div>
		);
	}
}
