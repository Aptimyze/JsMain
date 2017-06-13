import React from "react";

export default class Loader extends React.Component {	
	constructor(props) {
  	super();
	  console.log(props)
  }
  render() {
    var view;
    if(this.props.show == "page")          
    {
      view = <div className="loader simple dark loaderimage"></div>;
    } else if (this.props.show == "div") {
      view = <img src="https://static.jeevansathi.com/images/jsms/commonImg/loader.gif" />;
    } 
	  return (
	    <div>
        {view}
      </div>
	  );
  }
}










