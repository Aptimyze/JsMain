require ('../style/contact.css')
import React from "react";
import { connect } from "react-redux";
import { commonApiCall } from "../../common/components/ApiResponseHandler";
import * as CONSTANTS from '../../common/constants/apiConstants';
import ThreeDots from "./ThreeDots"
import WriteMessage from "./WriteMessage"
import {performAction} from './contactEngine';
export class contactEnginePD extends React.Component{
  constructor(props){
    super(props);
    this.state = {
    	actionDone: false,
      remindDone: false,
      showMessageOverlay:false,
      buttonData : this.props.buttondata
    };
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
      if(nextProps.contactAction.contactDone) {
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
  bindAction(button){

    switch(button.action)
    {

      case 'REPORT_ABUSE':
      break;

      case 'REPORT_INVALID':
      break;

      default:
        let callBack = (actionButton,responseButtons)=>{
          this.props.hideLoaderDiv();
          this.postAction(actionButton,responseButtons);
        }
        this.props.showLoaderDiv();
        performAction(button,this.props.profiledata.profilechecksum,callBack.bind(this));
        this.props.resetMyjsData();
      break;


    }
  }


  postAction(actionButton,responseButtons)
  {
    switch(actionButton.actions){

      case 'SHORTLIST':

      break;




    }




  }
  render(){
    return (
    <div>{[this.getFrontButton(),
    this.getOverLayDataDisplay()]
  }</div>
  );
    var messageOverlayView;
    if(this.props.profiledata && this.state.showMessageOverlay == true) {
      messageOverlayView = <WriteMessage closeMessageLayer={()=>this.closeMessageLayer()} username={this.props.profiledata.username} profileThumbNailUrl={this.props.profiledata.profileThumbNailUrl} buttonData={this.props.contactAction.message.isPaid} profilechecksum={this.props.profiledata.profilechecksum}/>
    }

  }
getFrontButton(){
  let primaryButton = this.state.buttonData.buttons[0];
  let threeDots = (<div></div>);
  let otherButtons = this.state.buttonData.buttons;
  if(otherButtons[0].action == 'ACCEPT' && otherButtons[1].action == 'DECLINE')
  return(<div id="buttons1" className="view_ce fullwid">

    <div className="wid50p bg7 dispibl txtc pad5new" id="primeWid_1" onClick={() => this.contactAction(otherButtons[0].action)}>

      <div id="btnAccept" className="fontlig f13 white cursp dispbl">
        <i className="ot_sprtie ot_chk"></i>
        <div className="white">{otherButtons[0].label}</div>
      </div>
    </div>
    <div className="wid50p bg7 dispibl txtc pad5new fr" id="primeWid_2" onClick={() => this.contactAction(otherButtons[1].action)}>
      <div id="btnDecline" className="fontlig f13 whitecursp dispbl">
        <i className="ot_sprtie newitcross"></i>
        <div className="white">{otherButtons[1].label}</div>
      </div>
    </div>
  </div>
  );
  if(this.state.buttonData.buttons.others) threeDots =(<div onClick={this.setThreeDotData.bind(this)} className="posabs srp_pos2"><a href="javascript:void(0)"><i className="mainsp threedot1"></i></a></div>);

  if(primaryButton.enable==true){
    return (<div id="buttons1" className="view_ce fullwid">
      <div className="fullwid bg7 txtc pad5new posrel" onClick={() => this.contactAction(primaryButton.action)}>
        <div className="wid60p">
          <i className="mainsp msg_srp"></i>
          <div className="white">{primaryButton.label}</div>
        </div>
        </div>
        {threeDots}

    </div>)
  }
  else return (<div id="buttons1" className="view_ce fullwid">
    <div className="fullwid srp_bg1 txtc pad18 posrel" >
      <div className="wid60p">
        <span className="fontlig f15 color7 dispbl">{primaryButton.label}</span>
      </div>
      {threeDots}
      </div>
  </div>
);

}

setThreeDotData(){
this.setState({
  threeDotsData: this.state.buttonData.buttons.others
})
}

hideThreeDotData(){
this.setState({
  threeDotsData: null
})
}
  getOverLayDataDisplay(object){
      if(this.state.threeDotsData)
        return (<ThreeDots buttondata={this.state.threeDotsData} username={this.props.profiledata.username} profilechecksum={this.props.profiledata.profilechecksum} profileThumbNailUrl={this.props.buttondata.profileThumbNailUrl} />);

      //if(this.state.)
  }

  setFrontButtonDisplay(object){
    this.setState({frontButton:object});
  }

}

const mapStateToProps = (state) => {
    return{
     contactAction: state.contactEngineReducer
    }
}

const mapDispatchToProps = (dispatch) => {
    return{
        resetMyjsData: () => {
          dispatch({type:'RESET_MYJS_TIMESTAMP',payload:{value:-1}});
        }
    }
}

export default connect(mapStateToProps,mapDispatchToProps)(contactEnginePD)
