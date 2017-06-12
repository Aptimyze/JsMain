import React from "react";
import MyjsHeadHTML from "./MyjsHeader";
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




							</div>
					</div>
			</div>
		);
	}
}
