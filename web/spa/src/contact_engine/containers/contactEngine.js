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
    return(
    	<div className="brdr8 fullwid hgt60">
        	<div className="txtc fullwid fl matchOfDayBtn brdr7 pad2" onClick={() => this.props.contactApi(this.props.profilechecksum,'matchOfDay')}>
            	<input className="inputProChecksum" type="hidden" value={this.props.profilechecksum}></input>
                <span className="f15 color2 fontreg">Send Interest</span>
            </div>
        	<div className="clr"></div>
        </div>
    );
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
          else if(source=='matchAlert')
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
