require ('../style/contact.css')
import React from "react";
import { connect } from "react-redux";
import { commonApiCall } from "../../common/components/ApiResponseHandler";
import * as CONSTANTS from '../../common/constants/apiConstants';
import ThreeDots from "./ThreeDots"
import WriteMessage from "./WriteMessage"
import {performAction} from './contactEngine';
import ContactDetails from '../components/ContactDetails';

export class contactEnginePD extends React.Component{
  constructor(props){
    super(props);
    this.state = {
    	actionDone: false,
      remindDone: false,
      showMessageOverlay:false,
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
//    performAction(button,this.props.profiledata.profilechecksum,callBack.bind(this));
//    this.props.callContactApi(this.actionUrl[action],action,url);
  }
  bindAction(button,index){


    switch(button.action)
    {

      case 'REPORT_ABUSE':

      break;

      case 'REPORT_INVALID':
      break;

      default:
          let callBack = (responseButtons)=>{
          this.props.hideLoaderDiv();console.log('resp',responseButtons);
          console.log('bi4');
          this.postAction(button,responseButtons,index);
        }
        this.props.showLoaderDiv();
        performAction(this.props.profiledata.profilechecksum,callBack.bind(this),button);
        this.props.resetMyjsData();
      break;


    }
  }

  getNewButtons(newButton,index){
    var temp=this.props.buttondata.buttons.slice(0);
    temp[index] = newButton;console.log('newbutt',temp);
    return temp;
  }
  postAction(actionButton,responseButtons,index)
  {

    switch(actionButton.action){

      case 'SHORTLIST':
        var newButtons = this.getNewButtons(responseButtons.buttondetails.button,index);console.log(newButtons);
        this.props.replaceSingleButton(newButtons);
      break;
      case 'IGNORE':
        if ( jsonOb.button.params.indexOf("&ignore=0") !== -1)
        {
          this.props.replaceOldButtons(getNewButtons(jsonOb.response.button_after_action.buttons.others[jsonOb.index],jsonOb.index));
          this.closeThreeDotLayer();
        }
        else
        {
          this.props.replaceOldButtons({'button':jsonOb.response.button_after_action.buttons.primary[0]},jsonOb.index);
          this.showIgnoreLayer(jsonOb.response.message,jsonOb.response.button_after_action.buttons.primary[0]);
        }

      break;

      case 'CONTACT_DETAIL':
        this.showHideCommon({contactDetailData:responseButtons.actiondetails,showContactDetail:true});
        this.props.unsetScroll();
      break;

      case 'WRITE_MESSAGE':
         console.log('red btn');
        console.log(responseButtons);
        this.showHideCommon({showWriteMsgLayerData:responseButtons,showMsgLayer: true});



      break;




      default:
        this.props.replaceOldButtons(responseButtons);
      break;



    }



  }
  render(){
    return (
    <div>{[this.getFrontButton(),
        this.getOverLayDataDisplay()]
  }</div>
  );
  }

getFrontButton(){

  let primaryButton = this.props.buttondata.buttons[0];
  let threeDots = (<div></div>);
  let otherButtons = this.props.buttondata.buttons;
  if(otherButtons[0].action == 'ACCEPT' && otherButtons[1].action == 'DECLINE')
  {

  return(<div key='1' id="buttons1" className="view_ce fullwid">

    <div className="wid50p bg7 dispibl txtc pad5new brdr6" id="primeWid_1" onClick={() => this.bindAction(otherButtons[0])}>

      <div id="btnAccept" className="fontlig f13 white cursp dispbl">
        <i className="ot_sprtie ot_chk"></i>
        <div className="white">{otherButtons[0].label}</div>
      </div>
    </div>
    <div className="wid50p bg7 dispibl txtc pad5new fr" id="primeWid_2" onClick={() => this.bindAction(otherButtons[1])}>
      <div id="btnDecline" className="fontlig f13 whitecursp dispbl">
        <i className="ot_sprtie newitcross"></i>
        <div className="white">{otherButtons[1].label}</div>
      </div>
    </div>
  </div>
  );
}
  if(this.props.buttondata.buttons)
  {


  threeDots =(<div onClick={this.setThreeDotData.bind(this)} className="posabs srp_pos2"><a href="javascript:void(0)"><i className={"mainsp "+(otherButtons[0].action=='DEFAULT' ? "srp_pinkdots" : "threedot1")}></i></a></div>);
}
if(primaryButton.enable==true)
{

    return (<div id="buttons1" className="view_ce fullwid">
      <div className="fullwid bg7 txtc pad5new posrel" onClick={() => this.bindAction(primaryButton)}>
        <div className="wid60p">
          <i className="mainsp msg_srp"></i>
          <div className="white">{primaryButton.label}</div>
        </div>
        </div>
        {threeDots}

    </div>)
}
else
{

     return (<div id="buttons1" className="view_ce fullwid">
      <div className="fullwid srp_bg1 txtc pad18 posrel" >
        <div className="wid60p">
          <span className="fontlig f15 color7 dispbl">{primaryButton.label}</span>
        </div>
        {threeDots}
        </div>
    </div>
  );
}

}

showHideCommon(data){
  this.setState({
    ...data
  });
  console.log('statecd',this.state);
}
setThreeDotData(){
this.setState({
  showThreeDots: true
});
this.props.unsetScroll();
}

hideThreeDotLayer(){
this.setState({
  showThreeDots: false
})
}

hideWriteLayer(){
  this.setState({
      showMsgLayer: false
  });
}

showReportAbuse(){
this.setState({
  showReportAbuse: true
});
}

hideReportAbuse(){
this.setState({
  showReportAbuse: false
})
}

getOverLayDataDisplay(){

    let layer = '';
      if(this.state.showThreeDots)
        layer = (<ThreeDots bindAction={(buttonObject,index) => this.bindAction(buttonObject,index)} buttondata={this.props.buttondata} closeThreeDotLayer ={this.hideThreeDotLayer.bind(this)} username={this.props.profiledata.username} profilechecksum={this.props.profiledata.profilechecksum} profileThumbNailUrl={this.props.buttondata.profileThumbNailUrl} />);
      if(this.state.showReportAbuse)
        layer =  (<ReportAbuse username={this.props.profiledata.username} profilechecksum={this.props.profiledata.profilechecksum} closeAbuseLayer={() => this.hideReportAbuse()} profileThumbNailUrl={this.props.buttondata.profileThumbNailUrl} />);
      if(this.state.showContactDetail)
        layer =  (<ContactDetails bindAction={(buttonObject,index) => this.bindAction(buttonObject,index)} actionDetails={this.state.contactDetailData} profilechecksum={this.props.profiledata.profilechecksum} closeAbuseLayer={() => this.hideReportAbuse()} profileThumbNailUrl={this.props.buttondata.profileThumbNailUrl} />);

    if(this.state.showMsgLayer)
    {
      console.log('mes write');
      console.log(this.state.showWriteMsgLayerData);
        layer = <WriteMessage username={this.props.profiledata.username} closeWriteMsgLayer={this.hideWriteLayer.bind(this)}  buttonData={this.state.showWriteMsgLayerData} profilechecksum={this.props.profiledata.profilechecksum}/>;
    }
    return (  <div key="2">{layer}</div>)
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
        },
        replaceOldButtons: (newButtons) => {
          dispatch({
            type: 'REPLACE_BUTTONS',
            payload: {newButtonDetails:newButtons.buttondetails}
          });
        },
        replaceSingleButton: (newButtons) => {
          dispatch({
            type: 'REPLACE_BUTTON',
            payload: {newButtons:newButtons}
          });
        }

    }
}

export default connect(mapStateToProps,mapDispatchToProps)(contactEnginePD)
