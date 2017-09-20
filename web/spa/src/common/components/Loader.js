import React from "react";
import * as CONSTANTS from '../../common/constants/apiConstants';
import * as API_SERVER_CONSTANTS from '../../common/constants/apiServerConstants'
export default class Loader extends React.Component {
	constructor(props) {
  	super();
  }
  render() {
    var view;
    if(this.props.show == "page")
    {
      view = <div className="loader simple dark loaderimage"></div>;
    } else if (this.props.show == "div") {
      view = <img src={API_SERVER_CONSTANTS.API_SERVER + "/images/jsms/commonImg/loader.gif"} style={this.props.loaderStyles} />;
    }
		else if (this.props.show == "writeMessageComp") {
      view = <div id="contactLoader" className="posabs txtc z105" style={this.props.loaderStyles}>
							<img src="/images/jsms/commonImg/loader.gif" />
						</div>;
    }
	  return (
	    <div>
        {view}
      </div>
	  );
  }
}
