require ('../style/contact.css')
import React from "react";
import { connect } from "react-redux";
import { commonApiCall } from "../../common/components/ApiResponseHandler";
import * as CONSTANTS from '../../common/constants/apiConstants';
import ThreeDots from "./ThreeDots"
import WriteMessage from "./WriteMessage"

export class contactEnginePD extends React.Component{
  constructor(props){
    super();
    this.state = {
    	actionDone: false,
      remindDone: false,
      showMessageOverlay:false
    }
    this.actionUrl = {
      "INITIATE":"/api/v2/contacts/postEOI",
      "ACCEPT": "/api/v2/contacts/postAccept",
      "DECLINE":"/api/v2/contacts/postNotInterested",
      "REMINDER":"/api/v2/contacts/postSendReminder",
      "WRITE_MESSAGE":"/api/v2/contacts/WriteMessage",
      "CANCEL":"/api/v2/contacts/postCancelInterest",
      "SHORTLIST":"/api/v1/common/AddBookmark",      
      "MESSAGE":"/api/v2/contacts/postWriteMessage",
      "CONTACT_DETAIL":"/api/v2/contacts/contactDetails"
    };
  }

  componentDidMount(){
  }

  componentWillReceiveProps(nextProps){
      if(nextProps.contactAction.acceptDone) {
       this.setState({
       	  actionDone: true
       })
      }
      if(nextProps.contactAction.reminderDone) {
        this.setState({
          remindDone: true
        })
      } 
      if (nextProps.contactAction.msgInitiated) {
        this.setState({
          showMessageOverlay: true
        })
      }
  }
  closeMessageLayer() {
    this.setState({showMessageOverlay: false})
  }

  contactAction(action){
  	this.props.showLoaderDiv();
    var url = '&profilechecksum='+this.props.profiledata.profilechecksum;
    this.props.callContactApi(this.actionUrl[action],action,url);
  }  	

  render(){
    var messageOverlayView;
    if(this.props.profiledata && this.state.showMessageOverlay == true) {
      messageOverlayView = <WriteMessage closeMessageLayer={()=>this.closeMessageLayer()} username={this.props.profiledata.username} profileThumbNailUrl={this.props.profiledata.profileThumbNailUrl} buttonData={this.props.contactAction.message.button} />
    }
    if(this.state.actionDone){
      if(this.props.contactAction.accept.buttondetails.button.action == "WRITE_MESSAGE") {
        return (<div id="buttons1" className="view_ce fullwid z100">
          {messageOverlayView}
          <div className="fullwid bg7 txtc pad5new posrel" onClick={() => this.contactAction(this.props.contactAction.accept.buttondetails.button.action)}>
            <div className="wid60p">
              <i className="mainsp ot_msg"></i>
              <input className="action" type="hidden" value={this.props.contactAction.accept.buttondetails.button.action}></input>
              <div className="white">{this.props.contactAction.accept.buttondetails.button.label}</div>
            </div>
            <ThreeDots username={this.props.buttondata.username} profileThumbNailUrl={this.props.buttondata.profileThumbNailUrl} />
          </div>
        </div>
        );
      }
    } 
    else if(this.state.remindDone) {
        return (<div id="buttons2" className="view_ce fullwid lh26">
              <div className="fullwid srp_bg1 txtc pad5new posrel" >
                <div className="wid60p">
                  <div className="white">{this.props.contactAction.reminder.buttondetails.button.label}</div>
                </div>
                <ThreeDots username={this.props.buttondata.username} profileThumbNailUrl={this.props.buttondata.profileThumbNailUrl} />
              </div>
            </div>
        );
    }
    else if(this.props.buttondata.buttons.primary[0].action == "ACCEPT") {
      return(<div id="buttons1" className="view_ce fullwid">

        <div className="wid50p bg7 dispibl txtc pad5new" id="primeWid_1" onClick={() => this.contactAction(this.props.buttondata.buttons.others[0].action)}>

          <div id="btnAccept" className="fontlig f13 white cursp dispbl">
            <i className="ot_sprtie ot_chk"></i>
            <input className="params" type="hidden" value={this.props.buttondata.buttons.others[0].params}></input>
            <input className="action" type="hidden" value={this.props.buttondata.buttons.others[0].action}></input>

            <div className="white">{this.props.buttondata.buttons.others[0].label}</div>
          </div>
        </div>
        <div className="wid50p bg7 dispibl txtc pad5new fr" id="primeWid_2" onClick={() => this.contactAction(this.props.buttondata.buttons.others[1].action)}>
          <div id="btnDecline" className="fontlig f13 whitecursp dispbl">
            <i className="ot_sprtie newitcross"></i>
            <input className="params" type="hidden" value={this.props.buttondata.buttons.others[1].params}></input>
            <input className="action" type="hidden" value={this.props.buttondata.buttons.others[1].action}></input>
            <div className="white">{this.props.buttondata.buttons.others[1].label}</div>
          </div>
        </div>
      </div>
      );
    } 
    else if(this.props.buttondata.buttons.primary[0].action == "REMINDER" || this.props.buttondata.buttons.primary[0].action == "INITIATE") {
      return(<div id="buttons1" className="view_ce fullwid">
        <div className="fullwid bg7 txtc pad5new posrel" >
          <div className="wid60p" onClick={() => this.contactAction(this.props.buttondata.buttons.primary[0].action)}>
            <i className="mainsp msg_srp"></i>
            <input className="action" type="hidden" value={this.props.buttondata.buttons.primary[0].action}></input>
            <div className="white">{this.props.buttondata.buttons.primary[0].label}</div>
          </div>
          <ThreeDots username={this.props.profiledata.username} profilechecksum={this.props.profiledata.profilechecksum} profileThumbNailUrl={this.props.buttondata.profileThumbNailUrl} />
        </div>
      </div>
      );
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
        callContactApi: (api,action,url) => {
          commonApiCall(api,url,action,'POST',dispatch,true);
        }
    }
}

export default connect(mapStateToProps,mapDispatchToProps)(contactEnginePD)
