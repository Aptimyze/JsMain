import React from "react";
import { connect } from "react-redux";
import { commonApiCall } from "../../common/components/ApiResponseHandler";
import * as CONSTANTS from '../../common/constants/apiConstants';

export default class ThreeDots extends React.Component{
  constructor(props){
    super();
    //console.log("const1",props)

  }

  componentDidMount(){
  	// console.log(this.props);
  }

  componentWillReceiveProps(nextProps){
  
  }
  getThreeDotLayer() {
    console.log('yesss')
  }

  render(){
    return(
      <div>
        <div onClick={() => this.getThreeDotLayer()} className="posabs srp_pos2">
          <i className="mainsp threedot1"></i>
        </div>
      </div>
      );
  }
  	
}
