import React from "react";

export default class TopError extends React.Component {	
	constructor(props) {
  	super();
    this.state = {
        timeToHide : props.timeToHide || 3000
    };
  }
  componentDidMount() {
    setTimeout(function(){ 
       document.getElementsByClassName("errClass")[0].classList.add("showErr");
    }, 10);

    setTimeout(function(){
       document.getElementsByClassName("errClass")[0].classList.remove("showErr"); 
    },this.state.timeToHide);  
  }	
  
  render() {
	  return (
	    <div id="TopError">
            <div className = "errClass top0 posfix">
                <div className = "pad12_e white f15 op1">{this.props.message}</div>
            </div>
	    </div>
	  );
  }
}





















