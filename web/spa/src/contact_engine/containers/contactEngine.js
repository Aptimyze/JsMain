require ('../style/contact.css')
import React from "react";
import { connect } from "react-redux";
import { commonApiCall } from "../../common/components/ApiResponseHandler";
import * as CONSTANTS from '../../common/constants/apiConstants';
import ThreeDots from "./ThreeDots"

export class contactEngine extends React.Component{
  constructor(props){
    super();
    this.state = {
    	actionDone: false
    }
  }

  componentDidMount(){
    // console.log('in contct');
  	// console.log(this.props);
  }

  componentWillReceiveProps(nextProps){
      if(nextProps.contactAction.contactDone || nextProps.contactAction.acceptDone || nextProps.contactAction.declineDone) {
       this.setState({
       	actionDone: true
       })
      }
  }
  render(){
  	if(this.props.pagesrcbtn == "myjs")
      {
        if(this.props.buttonName == "interest_received") {
          return (<div className="brdr8 fl wid90p hgt60">
                        <div className="txtc wid49p fl eoiAcceptBtn brdr7 pad2" onClick={() => this.props.acceptApi(this.props.buttondata.profilechecksum,this.props.tupleID)}>
                          <input className="inputProChecksum" type="hidden" value={this.props.buttondata.profilechecksum} />
                            <a className="f15 color2 fontreg">Accept</a>
                        </div>
                        <div className="txtc wid49p fl f15 pad2 eoiDeclineBtn" onClick={() => this.props.declineApi(this.props.buttondata.profilechecksum,this.props.tupleID)}>
                          <input className="inputProChecksum" type="hidden" value={this.props.buttondata.profilechecksum} />
                          <a className="f15 color2 fontlig">Decline</a>
                        </div>
                        <div className="clr"></div>
                      </div>);
        }
        else {
          return(
            <div className="brdr8 fullwid hgt60">
                <div className="txtc fullwid fl matchOfDayBtn brdr7 pad2" onClick={() => this.props.contactApi(this.props.buttondata.profilechecksum,this.props.buttonName,this.props.tupleID)}>
                    <input className="inputProChecksum" type="hidden" value={this.props.buttondata.profilechecksum}></input>
                      <span className="f15 color2 fontreg">Send Interest</span>
                  </div>
                <div className="clr"></div>
              </div>);
          }
      } else if(this.props.pagesrcbtn == "pd") {

        if(this.props.buttondata.buttons.primary[0].action == "ACCEPT") {
          return(
            <div id="buttons1" className="view_ce fullwid">
              <div className="wid49p bg7 dispibl txtc pad5new" id="primeWid_1">
                <div id="btnAccept" className="fontlig f13 white cursp dispbl">
                  <i className="ot_sprtie ot_chk"></i>
                  <input className="inputProChecksum" type="hidden" value={this.props.profiledata.profilechecksum}></input>
                  <input className="params" type="hidden" value={this.props.buttondata.buttons.others[0].params}></input>
                  <input className="action" type="hidden" value={this.props.buttondata.buttons.others[0].action}></input>
                  
                  <div className="white">{this.props.buttondata.buttons.others[0].label}</div>
                </div>
              </div>
              <div className="wid49p bg7 dispibl txtc pad5new fr" id="primeWid_2">
                <div id="btnDecline" className="fontlig f13 whitecursp dispbl">
                  <i className="ot_sprtie newitcross"></i>
                  <input className="inputProChecksum" type="hidden" value={this.props.profiledata.profilechecksum}></input>
                  <input className="params" type="hidden" value={this.props.buttondata.buttons.others[1].params}></input>
                  <input className="action" type="hidden" value={this.props.buttondata.buttons.others[1].action}></input>
                  <div className="white">{this.props.buttondata.buttons.others[1].label}</div>
                </div>

              </div>
            </div>
          );
        } else if(this.props.buttondata.buttons.primary[0].action == "REMINDER" || this.props.buttondata.buttons.primary[0].action == "INITIATE") {
          return(<div id="buttons1" className="view_ce fullwid">
            <div className="fullwid bg7 txtc pad5new posrel" >
              <div className="wid60p">
                <i className="mainsp msg_srp"></i>
                <input className="inputProChecksum" type="hidden" value={this.props.profiledata.profilechecksum}></input>
                <input className="action" type="hidden" value={this.props.buttondata.buttons.primary[0].action}></input>
                <div className="white">{this.props.buttondata.buttons.primary[0].label}</div>
              </div>
              <ThreeDots username={this.props.buttondata.username} profileThumbNailUrl={this.props.buttondata.profileThumbNailUrl} />
            </div>
          </div>
          );
        }
      }
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
        acceptApi: (profilechecksum, tupleID) => {
          var url = '&stype=15&profilechecksum='+profilechecksum;
          return commonApiCall(CONSTANTS.ACCEPT_API,url,'ACCEPT','POST',dispatch,true,{},tupleID);
        },
        declineApi: (profilechecksum, tupleID) => {
          var url = '&stype=15&profilechecksum='+profilechecksum;
          return commonApiCall(CONSTANTS.DECLINE_API,url,'DECLINE','POST',dispatch,true,{},tupleID);
        }
    }
}

export default connect(mapStateToProps,mapDispatchToProps)(contactEngine)
