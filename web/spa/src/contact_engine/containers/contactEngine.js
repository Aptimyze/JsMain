import React from "react";
import { connect } from "react-redux";
import { commonApiCall } from "../../common/components/ApiResponseHandler";
import * as CONSTANTS from '../../common/constants/apiConstants';

export class contactEngine extends React.Component{
  constructor(props){
    super();

  }

  componentDidMount(){
  	// console.log(this.props);
  }

  componentWillReceiveProps(nextProps){
  	if(nextProps.contact.contactDone) {
	    console.log('interest sent');
	}
	if(nextProps.contact.acceptDone){
	   console.log('accept done');
	}
	if(nextProps.contact.declineDone){
	   console.log('decline done');
	}
  }

  render(){
  	if(this.props.buttonName == "interest_received") {
	    return (<div className="brdr8 fl wid90p hgt60">
	                  <div className="txtc wid49p fl eoiAcceptBtn brdr7 pad2" onClick={() => this.props.acceptApi(this.props.buttondata.profilechecksum)}>
	                    <input className="inputProChecksum" type="hidden" value={this.props.buttondata.profilechecksum} />
	                      <a className="f15 color2 fontreg">Accept</a>
	                  </div>
	                  <div className="txtc wid49p fl f15 pad2 eoiDeclineBtn" onClick={() => this.props.declineApi(this.props.buttondata.profilechecksum)}>
	                    <input className="inputProChecksum" type="hidden" value={this.props.buttondata.profilechecksum} />
	                    <a className="f15 color2 fontlig">Decline</a>
	                  </div>
	                  <div className="clr"></div>
	                </div>);
	}
	else {
		return(
    	<div className="brdr8 fullwid hgt60">
        	<div className="txtc fullwid fl matchOfDayBtn brdr7 pad2" onClick={() => this.props.contactApi(this.props.buttondata.profilechecksum,this.props.buttonName)}>
            	<input className="inputProChecksum" type="hidden" value={this.props.buttondata.profilechecksum}></input>
                <span className="f15 color2 fontreg">Send Interest</span>
            </div>
        	<div className="clr"></div>
        </div>);    	
    }
  }
}

const mapStateToProps = (state) => {
    return{
     contact: state.contactEngineReducer
    }
}

const mapDispatchToProps = (dispatch) => {
    return{
        contactApi: (profilechecksum, source) => {
          if(source=='matchOfDay')
            var url = '&stype=WMOD&profilechecksum='+profilechecksum;
          else if(source=='match_alert')
            var url = '&stype=WMM&profilechecksum='+profilechecksum;
          else
            var url = '&profilechecksum='+profilechecksum;
          return commonApiCall(CONSTANTS.SEND_INTEREST_API,url,'CONTACT_ACTION','POST',dispatch,true);
        },
        acceptApi: (profilechecksum) => {
          var url = '&stype=15&profilechecksum='+profilechecksum;
          return commonApiCall(CONSTANTS.ACCEPT_API,url,'ACCEPT','POST',dispatch,true);
        },
        declineApi: (profilechecksum) => {
          var url = '&stype=15&profilechecksum='+profilechecksum;
          return commonApiCall(CONSTANTS.DECLINE_API,url,'DECLINE','POST',dispatch,true);
        }
    }
}

export default connect(mapStateToProps,mapDispatchToProps)(contactEngine)
