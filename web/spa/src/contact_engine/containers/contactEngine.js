require ('../style/contact.css')
import React from "react";
import { connect } from "react-redux";
import { commonApiCall } from "../../common/components/ApiResponseHandler";
import * as CONSTANTS from '../../common/constants/apiConstants';
import ThreeDots from "./ThreeDots";
import WriteMessage from "./WriteMessage";

export class contactEngine extends React.Component{
  constructor(props){
    super();console.log(props);
    this.state = {
    	actionDone: false,
      remindDone: false,
      showMessageOverlay:false
    }
    this.actionUrl = {"CONTACT_DETAIL":"/api/v2/contacts/contactDetails","INITIATE":"/api/v2/contacts/postEOI","INITIATE_MYJS":"/api/v2/contacts/postEOI","CANCEL":"/api/v2/contacts/postCancelInterest","SHORTLIST":"/api/v1/common/AddBookmark","DECLINE":"/api/v2/contacts/postNotInterested","REMINDER":"/api/v2/contacts/postSendReminder","MESSAGE":"/api/v2/contacts/postWriteMessage","ACCEPT":"/api/v2/contacts/postAccept","WRITE_MESSAGE":"/api/v2/contacts/WriteMessage","IGNORE":"/api/v1/common/ignoreprofile","PHONEVERIFICATION":"/phone/jsmsDisplay","MEMBERSHIP":"/profile/mem_comparison.php","COMPLETEPROFILE":"/profile/viewprofile.php","PHOTO_UPLOAD":'/social/MobilePhotoUpload',"ACCEPT_MYJS":"/api/v2/contacts/postAccept","DECLINE_MYJS":"/api/v2/contacts/postNotInterested","EDITPROFILE":"/profile/viewprofile.php?ownview=1"};

  }

  componentDidMount(){
  }

  componentWillReceiveProps(nextProps){
      if(nextProps.contactAction.acceptDone) {
       this.setState({
       	actionDone: true
       })
      } else if(nextProps.contactAction.reminderDone) {
        this.setState({
          remindDone: true
        })
      }
  }

  callContactApi(action){
    console.log("yess",action)
  	this.props.showLoaderDiv();
  	if(action == 'ACCEPT')
  		this.props.acceptApi(this.props.profiledata.profilechecksum,this.props.tupleID);
  	else if(action == 'DECLINE')
  		this.props.declineApi(this.props.profiledata.profilechecksum,this.props.tupleID);
  	else if(action == 'INITIATE')
  		this.props.contactApi(this.props.profiledata.profilechecksum,this.props.buttonName,this.props.tupleID);
  	else if(action == 'REMINDER')
  		this.props.reminderApi(this.props.profiledata.profilechecksum,this.props.buttonName,this.props.tupleID);
  }

  performAction(button)
  {
      let profilechecksum = this.props.profilechecksum, callBFun =  this.props.callBack;
      var url = `&${button.params}&profilechecksum=${profilechecksum}`;
      return commonApiCall(this.actionUrl[button.action],url,'','POST').then(()=>{if(typeof callBFun=='function') callBFun();});
  }
  render(){
    var messageOverlayView;
    if(this.state.showMessageOverlay == true) {
      messageOverlayView = <WriteMessage />
    }
  	if(this.props.pagesrcbtn == "myjs")
      {
        if(this.props.buttonName == "interest_received") {
          return (<div className="brdr8 fl wid90p hgt60">
            <div className="txtc wid49p fl eoiAcceptBtn brdr7 pad2" onClick={() => this.performAction(this.props.button[0])}>
              <a className="f15 color2 fontreg">Accept</a>
            </div>
            <div className="txtc wid49p fl f15 pad2 eoiDeclineBtn" onClick={() => this.performAction(this.props.button[1])}>
              <a className="f15 color2 fontlig">Decline</a>
            </div>
            <div className="clr"></div>
          </div>);
        }
        else {
          return(<div className="brdr8 fullwid hgt60">
            <div className="txtc fullwid fl matchOfDayBtn brdr7 pad2" onClick={() => this.performAction()}>
              <span className="f15 color2 fontreg">Send Interest</span>
            </div>
            <div className="clr"></div>
          </div>);
          }
      } else if(this.props.pagesrcbtn == "pd") {
      	if(this.state.actionDone){
          if(this.props.contactAction.accept.buttondetails.button.action == "WRITE_MESSAGE") {
            return (<div id="buttons1" className="view_ce fullwid">
              <div onClick={() => this.callContactApi(this.props.contactAction.accept.buttondetails.button.action)} className="fullwid bg7 txtc pad5new posrel" >
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
          else {return null;}
      	} else if(this.state.remindDone) {
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
        if(this.props.buttondata.buttons.primary[0].action == "ACCEPT") {
          return(<div id="buttons1" className="view_ce fullwid">
          {messageOverlayView}
            <div className="wid50p bg7 dispibl txtc pad5new" id="primeWid_1" onClick={() => this.callContactApi(this.props.buttondata.buttons.others[0].action)}>
              <div id="btnAccept" className="fontlig f13 white cursp dispbl">
                <i className="ot_sprtie ot_chk"></i>
                <input className="params" type="hidden" value={this.props.buttondata.buttons.others[0].params}></input>
                <input className="action" type="hidden" value={this.props.buttondata.buttons.others[0].action}></input>

                <div className="white">{this.props.buttondata.buttons.others[0].label}</div>
              </div>
            </div>
            <div className="wid50p bg7 dispibl txtc pad5new fr" id="primeWid_2" onClick={() => this.callContactApi(this.props.buttondata.buttons.others[1].action)}>
              <div id="btnDecline" className="fontlig f13 whitecursp dispbl">
                <i className="ot_sprtie newitcross"></i>
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
              <div className="wid60p" onClick={() => this.callContactApi(this.props.buttondata.buttons.primary[0].action)}>
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

export default connect(mapStateToProps,mapDispatchToProps)(contactEngine)
