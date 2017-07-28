require ('../style/contact.css')
import React from "react";
import { connect } from "react-redux";
import { commonApiCall } from "../../common/components/ApiResponseHandler";
import * as CONSTANTS from '../../common/constants/apiConstants';

export class WriteMessage extends React.Component{
  constructor(props){
    super();
    console.log("writeMessage",props);
  }

  componentDidMount(){
    document.getElementById("ProfilePage").classList.add("scrollhid");
      

  }

  componentWillReceiveProps(nextProps){

  }

  render(){
    return(
      <div id="writeMessageOverlay" className="posabs dispbl scrollhid">
        <div className="posabs vpro_tapoverlay">
          <div className="posrel fullwid z105">
            <div className="pad18 brdr4" id="comm_headerMsg">
              <div className="posrel clearfix fontthin hdrHght_con">
                  <div className="posabs com_left1">
                      <img id="imageId" src="https://172.16.3.185/502/4/10044935-1374696387.jpeg" className="com_brdr_radsrp wid50 hgt50" />
                  </div>
                  <div className="posabs com_right1">
                      <i className="mainsp com_cross"></i>
                  </div>
                  <div className="txtc f19 white pt10" id="usernameId">devadiga2003</div>
                </div>
          </div>
          </div>
        </div>
        <img src="https://www.jeevansathi.com/images/jsms/membership_img/revamp_bg1.jpg" className="classimg1 vpro_pos1 posfix z100" />
      </div>

    );
  }
}

const mapStateToProps = (state) => {
    return{
      contactAction: state.contactEngineReducer
    }
}

const mapDispatchToProps = (dispatch) => {
    return{
        contactApi: (profilechecksum, source, tupleID) => {
          if(source=='matchOfDay')
            var url = '&stype=WMOD&profilechecksum='+profilechecksum;
          else if(source=='match_alert')
            var url = '&stype=WMM&profilechecksum='+profilechecksum;
          else
            var url = '&profilechecksum='+profilechecksum;
          return commonApiCall(CONSTANTS.SEND_INTEREST_API,url,'CONTACT_ACTION','POST',dispatch,true,{},tupleID);
        },
        reminderApi: (profilechecksum, source, tupleID) => {
          if(source=='matchOfDay')
            var url = '&stype=WMOD&profilechecksum='+profilechecksum;
          else if(source=='match_alert')
            var url = '&stype=WMM&profilechecksum='+profilechecksum;
          else
            var url = '&profilechecksum='+profilechecksum;
          return commonApiCall(CONSTANTS.REMINDER_API,url,'REMINDER','POST',dispatch,true,{},tupleID);
        },
    }
}

export default connect(mapStateToProps,mapDispatchToProps)(WriteMessage)
